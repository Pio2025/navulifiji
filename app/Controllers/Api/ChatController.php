<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Models\ChatModel;
use CodeIgniter\Controller;

/**
 * Mobile chat REST surface — mirrors the web ChatController's endpoints
 * (same ChatModel/tables, so mobile and web share the same conversations),
 * but authenticated via JWT (ApiAuth) instead of a CodeIgniter session.
 * Realtime delivery is handled client-side by connecting to the same
 * navuli_chat Socket.IO server the web app uses (token from getSocketToken()).
 */
class ChatController extends Controller
{
    protected $chatModel;
    protected $db;

    private const ALLOWED_EXTENSIONS = [
        'jpg', 'jpeg', 'png', 'gif', 'webp',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
        'txt', 'zip',
    ];

    private const ALLOWED_MIME_TYPES = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'application/zip',
        'application/x-zip-compressed',
        'application/octet-stream',
    ];

    private const MAX_FILE_BYTES = 10 * 1024 * 1024; // 10 MB

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->chatModel = new ChatModel();
        $this->chatModel->ensureDeletionTable();
        $this->chatModel->ensureReactionsTable();
        $this->chatModel->ensureBlocksTable();
        $this->db = \Config\Database::connect();
    }

    // ------------------------------------------------------------------ Token

    private function buildJwt(int $userId): string
    {
        $secret  = env('CHAT_JWT_SECRET', 'navuli-chat-secret-change-me-in-production');
        $header  = $this->b64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = $this->b64url(json_encode(['userId' => $userId, 'iat' => time(), 'exp' => time() + 3600]));
        $sig     = $this->b64url(hash_hmac('sha256', "$header.$payload", $secret, true));
        return "$header.$payload.$sig";
    }

    private function b64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * GET /api/chat/socket-token
     */
    public function getSocketToken()
    {
        $userId = ApiAuth::userId();
        return $this->response->setJSON(['success' => true, 'token' => $this->buildJwt($userId), 'userId' => $userId]);
    }

    // ------------------------------------------------------------------ Conversations

    /**
     * GET /api/chat/conversation/(:num)
     * Returns (or creates) a direct conversation with the given user.
     */
    public function getOrCreateConversation(int $targetUserId)
    {
        $myId   = ApiAuth::userId();
        $claims = ApiAuth::claims();

        $existing = $this->chatModel->findDirectConversationId($myId, $targetUserId);
        if ($existing !== null) {
            return $this->response->setJSON(['success' => true, 'conversation_id' => $existing]);
        }

        $myUnaffiliated     = (int) ($claims['roleID'] ?? 0) === 1 || (int) ($claims['schID'] ?? 0) === 0;
        $targetUnaffiliated = !$this->chatModel->hasActiveAdmission($targetUserId);

        if (!$myUnaffiliated && $targetUnaffiliated) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'This user must message you first before you can start a conversation.',
            ]);
        }

        $conversationId = $this->chatModel->getOrCreateDirectConversation($myId, $targetUserId);

        return $this->response->setJSON([
            'success'         => true,
            'conversation_id' => $conversationId,
        ]);
    }

    /**
     * GET /api/chat/conversations
     */
    public function getConversations()
    {
        $myId          = ApiAuth::userId();
        $conversations = $this->chatModel->getConversations($myId);

        return $this->response->setJSON([
            'success'       => true,
            'conversations' => $conversations,
        ]);
    }

    // ------------------------------------------------------------------ Messages

    /**
     * GET /api/chat/messages/(:num)?page=1
     */
    public function getMessages(int $conversationId)
    {
        $myId = ApiAuth::userId();

        if (!$this->chatModel->isParticipant($conversationId, $myId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $page     = max(1, (int) ($this->request->getGet('page') ?? 1));
        $messages = $this->chatModel->getMessages($conversationId, $myId, $page);

        return $this->response->setJSON([
            'success'  => true,
            'messages' => $messages,
            'page'     => $page,
        ]);
    }

    /**
     * POST /api/chat/messages
     */
    public function sendMessage()
    {
        $myId           = ApiAuth::userId();
        $body           = $this->request->getJSON(true) ?? [];
        $conversationId = (int) ($this->request->getPost('conversation_id') ?? ($body['conversation_id'] ?? 0));
        $content        = trim((string) ($this->request->getPost('content') ?? ($body['content'] ?? '')));

        if (!$conversationId || $content === '') {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        if (!$this->chatModel->isParticipant($conversationId, $myId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $otherUserId = $this->chatModel->getOtherParticipant($conversationId, $myId);
        if ($otherUserId && $this->chatModel->isBlockedBetween($myId, $otherUserId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You cannot message this user.']);
        }

        $messageId = $this->chatModel->saveMessage($conversationId, $myId, 'text', $content);
        $message   = $this->chatModel->getMessage($messageId, $myId);

        return $this->response->setJSON([
            'success' => true,
            'message' => $message,
        ]);
    }

    // ------------------------------------------------------------------ File upload

    /**
     * POST /api/chat/upload
     * Single document upload  →  field name: "file"
     * Multiple photo upload   →  field name: "files[]"  (all must be images, max 10)
     */
    public function uploadFile()
    {
        $myId           = ApiAuth::userId();
        $conversationId = (int) ($this->request->getPost('conversation_id') ?? 0);

        if (!$conversationId) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        if (!$this->chatModel->isParticipant($conversationId, $myId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $otherUserId = $this->chatModel->getOtherParticipant($conversationId, $myId);
        if ($otherUserId && $this->chatModel->isBlockedBetween($myId, $otherUserId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You cannot message this user.']);
        }

        $subDir      = 'uploads/chat/' . date('Y-m');
        $absoluteDir = FCPATH . $subDir;
        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0755, true)) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Could not create upload directory']);
        }

        // ── Multiple photo upload ─────────────────────────────────────────
        $multiFiles = $this->request->getFileMultiple('files');
        if ($multiFiles && isset($multiFiles[0]) && $multiFiles[0]->isValid()) {
            $imageExts  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $imageMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $saved      = [];

            foreach (array_slice($multiFiles, 0, 10) as $f) {
                if (!$f->isValid() || $f->hasMoved()) continue;
                if ($f->getSize() > self::MAX_FILE_BYTES) continue;
                $ext  = strtolower($f->getExtension());
                $mime = $f->getMimeType();
                if (!in_array($ext, $imageExts, true) || !in_array($mime, $imageMimes, true)) continue;

                $storedName = bin2hex(random_bytes(16)) . '.' . $ext;
                if (!$f->move($absoluteDir, $storedName)) continue;

                $saved[] = [
                    'original_name' => $f->getClientName(),
                    'stored_name'   => $storedName,
                    'file_path'     => $subDir . '/' . $storedName,
                    'file_type'     => $mime,
                    'file_size'     => $f->getSize(),
                ];
            }

            if (empty($saved)) {
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'No valid images']);
            }

            $messageId = $this->chatModel->saveMessage($conversationId, $myId, 'image', null);
            foreach ($saved as $s) {
                $this->chatModel->saveMessageFile($messageId, $s);
            }

            return $this->response->setJSON(['success' => true, 'message' => $this->chatModel->getMessage($messageId, $myId)]);
        }

        // ── Single file upload ────────────────────────────────────────────
        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'No valid file uploaded']);
        }
        if ($file->getSize() > self::MAX_FILE_BYTES) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'File exceeds 10 MB limit']);
        }

        $ext = strtolower($file->getExtension());
        if (!in_array($ext, self::ALLOWED_EXTENSIONS, true)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'File type not allowed']);
        }
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'File type not allowed']);
        }

        $storedName = bin2hex(random_bytes(16)) . '.' . $ext;
        if (!$file->move($absoluteDir, $storedName)) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Upload failed']);
        }

        $messageType = str_starts_with($mimeType, 'image/') ? 'image' : 'file';
        $messageId   = $this->chatModel->saveMessage($conversationId, $myId, $messageType, null);
        $this->chatModel->saveMessageFile($messageId, [
            'original_name' => $file->getClientName(),
            'stored_name'   => $storedName,
            'file_path'     => $subDir . '/' . $storedName,
            'file_type'     => $mimeType,
            'file_size'     => $file->getSize(),
        ]);

        return $this->response->setJSON(['success' => true, 'message' => $this->chatModel->getMessage($messageId, $myId)]);
    }

    // ------------------------------------------------------------------ Polling fallback

    /**
     * GET /api/chat/messages/(:num)/new?after=:id
     */
    public function getNewMessages(int $conversationId)
    {
        $myId = ApiAuth::userId();

        if (!$this->chatModel->isParticipant($conversationId, $myId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false]);
        }

        $afterId  = max(0, (int) ($this->request->getGet('after') ?? 0));
        $messages = $this->chatModel->getMessagesAfter($conversationId, $myId, $afterId);

        if ($messages) {
            $this->chatModel->markRead($conversationId, $myId);
        }

        return $this->response->setJSON(['success' => true, 'messages' => $messages]);
    }

    // ------------------------------------------------------------------ Unread count

    /**
     * GET /api/chat/unread-count
     */
    public function getUnreadCount()
    {
        $myId  = ApiAuth::userId();
        $count = $this->chatModel->getTotalUnreadCount($myId);

        return $this->response->setJSON(['success' => true, 'count' => $count]);
    }

    /**
     * GET /api/chat/unread-per-user
     */
    public function getUnreadPerUser()
    {
        $myId   = ApiAuth::userId();
        $counts = $this->chatModel->getUnreadCountsPerUser($myId);

        return $this->response->setJSON(['success' => true, 'counts' => $counts]);
    }

    // ------------------------------------------------------------------ Read receipt

    /**
     * POST /api/chat/read/(:num)
     */
    public function markRead(int $conversationId)
    {
        $myId = ApiAuth::userId();

        if (!$this->chatModel->isParticipant($conversationId, $myId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $this->chatModel->markRead($conversationId, $myId);

        return $this->response->setJSON(['success' => true]);
    }

    // ------------------------------------------------------------------ Delete message

    /**
     * POST /api/chat/message/(:num)/delete
     * scope=me        → soft-delete for requesting user only
     * scope=everyone  → hard-delete for all (sender only)
     */
    public function deleteMessage(int $messageId)
    {
        $myId  = ApiAuth::userId();
        $body  = $this->request->getJSON(true) ?? [];
        $scope = ($this->request->getPost('scope') ?? ($body['scope'] ?? '')) === 'everyone' ? 'everyone' : 'me';

        $msg = $this->db->table('chat_messages')->where('id', $messageId)->get()->getRowArray();
        if (!$msg) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Message not found']);
        }

        $conversationId = (int) $msg['conversation_id'];

        if (!$this->chatModel->isParticipant($conversationId, $myId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        if ($scope === 'everyone') {
            if ((int) $msg['sender_id'] !== $myId) {
                return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Only the sender can remove for everyone']);
            }
            $this->chatModel->deleteForEveryone($messageId, $myId);
        } else {
            $this->chatModel->deleteForMe($messageId, $myId);
        }

        return $this->response->setJSON([
            'success'        => true,
            'messageId'      => $messageId,
            'scope'          => $scope,
            'conversationId' => $conversationId,
        ]);
    }

    /**
     * POST /api/chat/conversation/(:num)/clear
     */
    public function clearConversation(int $conversationId)
    {
        $myId = ApiAuth::userId();

        if (!$this->chatModel->isParticipant($conversationId, $myId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $this->chatModel->clearConversationForUser($conversationId, $myId);

        return $this->response->setJSON(['success' => true, 'conversationId' => $conversationId]);
    }

    // ------------------------------------------------------------------ Reactions

    /**
     * POST /api/chat/message/(:num)/react
     */
    public function reactMessage(int $messageId)
    {
        $myId  = ApiAuth::userId();
        $body  = $this->request->getJSON(true) ?? [];
        $emoji = trim((string) ($this->request->getPost('emoji') ?? ($body['emoji'] ?? '')));

        if ($emoji === '') {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Emoji required']);
        }

        $msg = $this->db->table('chat_messages')->where('id', $messageId)->get()->getRowArray();
        if (!$msg) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Message not found']);
        }

        $conversationId = (int) $msg['conversation_id'];
        if (!$this->chatModel->isParticipant($conversationId, $myId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $existing  = $this->chatModel->getUserReaction($messageId, $myId);
        $reactions = ($existing === $emoji)
            ? $this->chatModel->removeReaction($messageId, $myId)
            : $this->chatModel->setReaction($messageId, $myId, $emoji);

        return $this->response->setJSON([
            'success'        => true,
            'messageId'      => $messageId,
            'conversationId' => $conversationId,
            'reactions'      => $reactions,
        ]);
    }

    // ------------------------------------------------------------------ Block

    /**
     * POST /api/chat/block/(:num)
     */
    public function block(int $targetUserId)
    {
        $myId = ApiAuth::userId();

        if ($this->chatModel->blockedByMe($myId, $targetUserId)) {
            $this->chatModel->unblockUser($myId, $targetUserId);
            return $this->response->setJSON(['success' => true, 'blocked' => false]);
        }

        $this->chatModel->blockUser($myId, $targetUserId);
        return $this->response->setJSON(['success' => true, 'blocked' => true]);
    }

    /**
     * GET /api/chat/block-status/(:num)
     */
    public function blockStatus(int $targetUserId)
    {
        $myId = ApiAuth::userId();

        return $this->response->setJSON([
            'success'     => true,
            'blocked'     => $this->chatModel->isBlockedBetween($myId, $targetUserId),
            'blockedByMe' => $this->chatModel->blockedByMe($myId, $targetUserId),
        ]);
    }

    // ------------------------------------------------------------------ Recipient picker

    /**
     * GET /api/chat/users?search=&page=1
     * Users the current account may start/see a conversation with — mirrors the web
     * UserController::getChatUserList() visibility rules (same-school, or an
     * unaffiliated user I already have a conversation with).
     */
    public function users()
    {
        $myId       = ApiAuth::userId();
        $claims     = ApiAuth::claims();
        $myRoleId   = (int) ($claims['roleID'] ?? 0);
        $mySchId    = (int) ($claims['schID'] ?? 0);
        $isUnaffiliated = $myRoleId === 1 || $mySchId === 0;

        $page    = max(1, (int) ($this->request->getGet('page') ?? 1));
        $search  = trim((string) ($this->request->getGet('search') ?? ''));
        $perPage = 50;
        $offset  = ($page - 1) * $perPage;

        $searchSQL    = '';
        $searchParams = [];
        if ($search !== '') {
            $searchSQL    = ' AND (u.fname LIKE ? OR u.lname LIKE ?)';
            $like         = '%' . $search . '%';
            $searchParams = [$like, $like];
        }

        if ($isUnaffiliated) {
            $combinedSQL = "
                SELECT DISTINCT u.user_id, u.fname, u.lname, u.profile_photo, u.online_status
                FROM users u
                WHERE u.user_id != ? AND u.user_status = 'Active' $searchSQL
            ";
            $combinedParams = array_merge([$myId], $searchParams);
        } else {
            $sameSchoolSQL = "
                SELECT DISTINCT u.user_id, u.fname, u.lname, u.profile_photo, u.online_status
                FROM users u
                INNER JOIN admission a ON a.user_id_fk = u.user_id AND a.sch_id_fk = ? AND a.admission_status = 'Active'
                WHERE u.user_id != ? AND u.user_status = 'Active' $searchSQL
            ";
            $sameSchoolParams = array_merge([$mySchId, $myId], $searchParams);

            $crossSchoolSQL = "
                SELECT DISTINCT u.user_id, u.fname, u.lname, u.profile_photo, u.online_status
                FROM users u
                INNER JOIN chat_participants cp1 ON cp1.user_id = u.user_id
                INNER JOIN chat_participants cp2 ON cp2.conversation_id = cp1.conversation_id AND cp2.user_id = ?
                INNER JOIN chat_conversations cc ON cc.id = cp1.conversation_id AND cc.type = 'direct'
                WHERE u.user_id != ? AND u.user_status = 'Active'
                  AND NOT EXISTS (SELECT 1 FROM admission ad WHERE ad.user_id_fk = u.user_id AND ad.admission_status = 'Active')
                  AND EXISTS (
                      SELECT 1 FROM chat_messages m
                      LEFT JOIN chat_message_deletions cmd ON cmd.message_id = m.id AND cmd.user_id = ?
                      WHERE m.conversation_id = cc.id AND cmd.id IS NULL
                  )
                  $searchSQL
            ";
            $crossSchoolParams = array_merge([$myId, $myId, $myId], $searchParams);

            $combinedSQL    = "($sameSchoolSQL) UNION ($crossSchoolSQL)";
            $combinedParams = array_merge($sameSchoolParams, $crossSchoolParams);
        }

        $total = (int) $this->db->query(
            "SELECT COUNT(*) AS cnt FROM ($combinedSQL) AS combined",
            $combinedParams
        )->getRow()->cnt;

        $users = $this->db->query(
            "SELECT * FROM ($combinedSQL) AS combined
             ORDER BY CASE WHEN combined.online_status = 'Online' THEN 0 ELSE 1 END ASC, combined.fname ASC
             LIMIT ? OFFSET ?",
            array_merge($combinedParams, [$perPage, $offset])
        )->getResultArray();

        $out = array_map(fn ($u) => [
            'userId'       => (int) $u['user_id'],
            'name'         => trim($u['fname'] . ' ' . $u['lname']),
            'photo'        => !empty($u['profile_photo']) ? base_url('uploads/profilePhoto/' . $u['profile_photo']) : base_url('app/assets/media/avatars/blank.png'),
            'onlineStatus' => $u['online_status'] ?? 'Offline',
        ], $users);

        return $this->response->setJSON([
            'success'  => true,
            'users'    => $out,
            'hasMore'  => ($offset + $perPage) < $total,
            'nextPage' => $page + 1,
            'total'    => $total,
        ]);
    }
}
