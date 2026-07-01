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
        $this->chatModel->ensureReactionsTable();
        $this->chatModel->ensureBlocksTable();
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

        $myId = (int) $this->session->get('userID');

        $existing = $this->chatModel->findDirectConversationId($myId, $targetUserId);
        if ($existing !== null) {
            return $this->response->setJSON(['success' => true, 'conversation_id' => $existing]);
        }

        // A school-affiliated user may not originate a brand-new conversation with an
        // unaffiliated user (e.g. Super Admin/Admin) — that user must message them first.
        $myUnaffiliated     = (int) $this->session->get('roleID') === 1 || (int) $this->session->get('schID') === 0;
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

    /**
     * POST /chat/conversation/(:num)/clear
     * Bulk "remove for me" — deletes every message in the conversation for the requesting user only.
     */
    public function clearConversation(int $conversationId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $myId = (int) $this->session->get('userID');

        if (!$this->chatModel->isParticipant($conversationId, $myId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $this->chatModel->clearConversationForUser($conversationId, $myId);

        return $this->response->setJSON(['success' => true, 'conversationId' => $conversationId]);
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

        $otherUserId = $this->chatModel->getOtherParticipant($conversationId, $myId);
        if ($otherUserId && $this->chatModel->isBlockedBetween($myId, $otherUserId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You cannot call this user.']);
        }

        $content   = json_encode(['call_type' => $callType, 'status' => $status, 'duration' => $duration]);
        $messageId = $this->chatModel->saveMessage($conversationId, $myId, 'call', $content);
        $message   = $this->chatModel->getMessage($messageId, $myId);

        return $this->response->setJSON(['success' => true, 'message' => $message]);
    }

    // ------------------------------------------------------------------ Reactions

    /**
     * POST /chat/message/(:num)/react
     * Toggle semantics: posting the same emoji the user already reacted with removes it,
     * otherwise it replaces their previous reaction (single reaction per user per message).
     */
    public function reactMessage(int $messageId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $myId  = (int) $this->session->get('userID');
        $emoji = trim((string) ($this->request->getPost('emoji') ?? ''));

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

        $existing = $this->chatModel->getUserReaction($messageId, $myId);
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
     * POST /chat/block/(:num)
     * Toggles a block against the given user (blocks if not already blocked-by-me, unblocks if it is).
     */
    public function block(int $targetUserId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $myId = (int) $this->session->get('userID');

        if ($this->chatModel->blockedByMe($myId, $targetUserId)) {
            $this->chatModel->unblockUser($myId, $targetUserId);
            return $this->response->setJSON(['success' => true, 'blocked' => false]);
        }

        $this->chatModel->blockUser($myId, $targetUserId);
        return $this->response->setJSON(['success' => true, 'blocked' => true]);
    }

    /**
     * GET /chat/block-status/(:num)
     */
    public function blockStatus(int $targetUserId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $myId = (int) $this->session->get('userID');

        return $this->response->setJSON([
            'success'      => true,
            'blocked'      => $this->chatModel->isBlockedBetween($myId, $targetUserId),
            'blockedByMe'  => $this->chatModel->blockedByMe($myId, $targetUserId),
        ]);
    }

    /**
     * POST /chat/internal/block-check
     * Machine-to-machine endpoint used by the separate navuli_chat Socket.IO server to check
     * whether call signaling between two users should be blocked. Authenticated via a shared
     * secret header instead of session auth (the caller has no CodeIgniter session).
     */
    public function internalBlockCheck()
    {
        $secret = env('CHAT_JWT_SECRET', 'navuli-chat-secret-change-me-in-production');
        $header = $this->request->getHeaderLine('X-Chat-Internal-Secret');

        if (!$header || !hash_equals($secret, $header)) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $userA = (int) ($this->request->getJSON(true)['user_a'] ?? 0);
        $userB = (int) ($this->request->getJSON(true)['user_b'] ?? 0);

        if (!$userA || !$userB) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'user_a and user_b required']);
        }

        return $this->response->setJSON(['blocked' => $this->chatModel->isBlockedBetween($userA, $userB)]);
    }

    // ------------------------------------------------------------------ Transcript

    /**
     * GET /chat/transcript/(:num)
     * Downloads the full message history of a conversation as a PDF.
     */
    public function transcript(int $conversationId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $myId = (int) $this->session->get('userID');

        if (!$this->chatModel->isParticipant($conversationId, $myId)) {
            return $this->response->setStatusCode(403)->setBody('Access denied');
        }

        $participants = $this->db->query("
            SELECT u.user_id, u.fname, u.lname
            FROM   chat_participants cp
            INNER JOIN users u ON u.user_id = cp.user_id
            WHERE  cp.conversation_id = ?
        ", [$conversationId])->getResultArray();

        $names = [];
        foreach ($participants as $p) {
            $names[(int) $p['user_id']] = trim($p['fname'] . ' ' . $p['lname']);
        }

        $allMessages = [];
        $page = 1;
        do {
            $batch = $this->chatModel->getMessages($conversationId, $myId, $page);
            $allMessages = array_merge($allMessages, $batch);
            $page++;
        } while (count($batch) > 0);

        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';
        set_error_handler(static function (int $errno, string $errstr): bool {
            return str_contains($errstr, 'iCCP') || str_contains($errstr, 'gd-png') || str_contains($errstr, 'libpng warning');
        }, E_WARNING);

        $title = 'Chat Transcript — ' . implode(' & ', $names);

        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Navuli Fiji');
        $pdf->SetTitle($title);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetAutoPageBreak(true, 14);
        $pdf->AddPage();

        $sx = 12; $cw = 186;

        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($cw, 7, $title, 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->Cell($cw, 5, 'Generated ' . date('d M Y, h:i A'), 0, 1, 'C');
        $pdf->Ln(3);
        $pdf->SetLineStyle(['width' => 0.4, 'color' => [26, 86, 219]]);
        $pdf->Line($sx, $pdf->GetY(), $sx + $cw, $pdf->GetY());
        $pdf->Ln(4);

        foreach ($allMessages as $msg) {
            $senderName = $names[(int) $msg['sender_id']] ?? trim($msg['fname'] . ' ' . $msg['lname']);
            $time       = date('d M Y, h:i A', strtotime($msg['created_at']));

            switch ($msg['message_type']) {
                case 'deleted':
                    $body = '[Message removed]';
                    break;
                case 'image':
                    $body = '[Photo attachment' . (count($msg['files']) > 1 ? ' x' . count($msg['files']) : '') . ']';
                    break;
                case 'file':
                    $fileName = $msg['files'][0]['original_name'] ?? 'file';
                    $body     = '[File: ' . $fileName . ']';
                    break;
                case 'call':
                    $callInfo = json_decode((string) $msg['content'], true) ?: [];
                    $body     = '[' . ucfirst($callInfo['call_type'] ?? 'voice') . ' call — ' . ($callInfo['status'] ?? 'ended') . ']';
                    break;
                default:
                    $body = (string) $msg['content'];
            }

            $pdf->SetFont('helvetica', 'B', 8.5);
            $pdf->SetTextColor(30, 30, 30);
            $pdf->Cell($cw, 4.5, $senderName . '  —  ' . $time, 0, 1, 'L');

            $pdf->SetFont('helvetica', '', 8.5);
            $pdf->SetTextColor(55, 65, 81);
            $pdf->MultiCell($cw, 4.5, $body, 0, 'L');
            $pdf->Ln(2);
        }

        restore_error_handler();
        $filename = 'chat_transcript_' . $conversationId . '_' . date('Ymd_His') . '.pdf';
        $pdf->Output($filename, 'D');
        exit;
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
