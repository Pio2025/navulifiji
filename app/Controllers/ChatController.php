<?php

namespace App\Controllers;

use App\Models\ChatModel;

class ChatController extends BaseController
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

    public function __construct()
    {
        helper(['form', 'url']);
        $this->session   = \Config\Services::session();
        $this->chatModel = new ChatModel();
        $this->chatModel->ensureDeletionTable();
        $this->db        = \Config\Database::connect();
    }

    // ------------------------------------------------------------------ Token

    /**
     * GET /chat/token
     * Issues a short-lived JWT for authenticating the Socket.IO connection.
     */
    public function getToken()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $userId = (int) $this->session->get('userID');

        return $this->response->setJSON([
            'success' => true,
            'token'   => $this->buildJwt($userId),
            'userId'  => $userId,
        ]);
    }

    // ------------------------------------------------------------------ Conversations

    /**
     * GET /chat/conversation/(:num)
     * Returns (or creates) a direct conversation with the given user.
     */
    public function getOrCreateConversation(int $targetUserId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $myId           = (int) $this->session->get('userID');
        $conversationId = $this->chatModel->getOrCreateDirectConversation($myId, $targetUserId);

        return $this->response->setJSON([
            'success'         => true,
            'conversation_id' => $conversationId,
        ]);
    }

    /**
     * GET /chat/conversations
     * Lists all conversations for the logged-in user, newest first.
     */
    public function getConversations()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $myId          = (int) $this->session->get('userID');
        $conversations = $this->chatModel->getConversations($myId);

        return $this->response->setJSON([
            'success'       => true,
            'conversations' => $conversations,
        ]);
    }

    // ------------------------------------------------------------------ Messages

    /**
     * GET /chat/messages/(:num)?page=1
     * Returns paginated messages for a conversation (oldest first, 30 per page).
     */
    public function getMessages(int $conversationId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $myId = (int) $this->session->get('userID');

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
     * POST /chat/messages
     * Saves a text message. Returns the full message record for Socket.IO broadcast.
     */
    public function sendMessage()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $myId           = (int) $this->session->get('userID');
        $conversationId = (int) ($this->request->getPost('conversation_id') ?? 0);
        $content        = trim((string) ($this->request->getPost('content') ?? ''));

        if (!$conversationId || $content === '') {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        if (!$this->chatModel->isParticipant($conversationId, $myId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $messageId = $this->chatModel->saveMessage($conversationId, $myId, 'text', $content);
        $message   = $this->chatModel->getMessage($messageId);

        return $this->response->setJSON([
            'success' => true,
            'message' => $message,
        ]);
    }

    // ------------------------------------------------------------------ File upload

    /**
     * POST /chat/upload
     * Single document upload  →  field name: "file"
     * Multiple photo upload   →  field name: "files[]"  (all must be images, max 10)
     */
    public function uploadFile()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $myId           = (int) $this->session->get('userID');
        $conversationId = (int) ($this->request->getPost('conversation_id') ?? 0);

        if (!$conversationId) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        if (!$this->chatModel->isParticipant($conversationId, $myId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Access denied']);
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

            return $this->response->setJSON(['success' => true, 'message' => $this->chatModel->getMessage($messageId)]);
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

        return $this->response->setJSON(['success' => true, 'message' => $this->chatModel->getMessage($messageId)]);
    }

    // ------------------------------------------------------------------ Polling fallback

    /**
     * GET /chat/messages/(:num)/new?after=:id
     * Returns messages in a conversation newer than the given message ID.
     * Used by the short-poll fallback when Socket.IO is disconnected.
     */
    public function getNewMessages(int $conversationId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $myId = (int) $this->session->get('userID');

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
     * GET /chat/unread-count
     * Returns the total number of unread messages across all conversations.
     */
    public function getUnreadCount()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $myId  = (int) $this->session->get('userID');
        $count = $this->chatModel->getTotalUnreadCount($myId);

        return $this->response->setJSON(['success' => true, 'count' => $count]);
    }

    // ------------------------------------------------------------------ Read receipt

    /**
     * POST /chat/read/(:num)
     * Marks all messages in a conversation as read for the current user.
     */
    public function markRead(int $conversationId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $myId = (int) $this->session->get('userID');

        if (!$this->chatModel->isParticipant($conversationId, $myId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $this->chatModel->markRead($conversationId, $myId);

        return $this->response->setJSON(['success' => true]);
    }

    // ------------------------------------------------------------------ Delete message

    /**
     * POST /chat/message/(:num)/delete
     * scope=me        → soft-delete for requesting user only
     * scope=everyone  → hard-delete for all (sender only)
     */
    public function deleteMessage(int $messageId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $myId  = (int) $this->session->get('userID');
        $scope = $this->request->getPost('scope') === 'everyone' ? 'everyone' : 'me';

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

    // ------------------------------------------------------------------ Call event

    /**
     * POST /chat/call-event
     * Saves a call log message (voice/video, ended/missed/declined/cancelled).
     * Only the outgoing caller side should call this.
     */
    public function callEvent()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $myId           = (int) $this->session->get('userID');
        $conversationId = (int) ($this->request->getPost('conversation_id') ?? 0);
        $callType       = $this->request->getPost('call_type') === 'video' ? 'video' : 'voice';
        $status         = in_array($this->request->getPost('status'), ['ended', 'missed', 'declined', 'cancelled'], true)
                            ? $this->request->getPost('status') : 'ended';
        $duration       = max(0, (int) ($this->request->getPost('duration') ?? 0));

        if (!$conversationId) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false]);
        }

        if (!$this->chatModel->isParticipant($conversationId, $myId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false]);
        }

        $content   = json_encode(['call_type' => $callType, 'status' => $status, 'duration' => $duration]);
        $messageId = $this->chatModel->saveMessage($conversationId, $myId, 'call', $content);
        $message   = $this->chatModel->getMessage($messageId);

        return $this->response->setJSON(['success' => true, 'message' => $message]);
    }

    // ------------------------------------------------------------------ JWT helpers

    private function buildJwt(int $userId): string
    {
        $secret  = env('CHAT_JWT_SECRET', 'navuli-chat-secret-change-me-in-production');
        $header  = $this->b64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = $this->b64url(json_encode([
            'userId' => $userId,
            'iat'    => time(),
            'exp'    => time() + 3600,
        ]));
        $sig = $this->b64url(hash_hmac('sha256', "$header.$payload", $secret, true));

        return "$header.$payload.$sig";
    }

    private function b64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
