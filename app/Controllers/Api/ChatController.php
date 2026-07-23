<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use CodeIgniter\Controller;

/**
 * Mirrors ChatController::getToken()/buildJwt() but for the mobile app's Bearer/JWT
 * auth instead of session auth — issues the same short-lived HS256 token the
 * navuli_chat Socket.IO server expects on `auth.token`.
 */
class ChatController extends Controller
{
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

    /**
     * GET /api/chat/socket-token — issues a short-lived JWT for authenticating the
     * mobile app's Socket.IO connection.
     */
    public function getSocketToken()
    {
        $userId = ApiAuth::userId();

        return $this->response->setJSON([
            'success' => true,
            'token'   => $this->buildJwt($userId),
            'userId'  => $userId,
        ]);
    }
}
