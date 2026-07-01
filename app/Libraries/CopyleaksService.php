<?php

namespace App\Libraries;

class CopyleaksService
{
    private const LOGIN_URL = 'https://id.copyleaks.com/v3/account/login/api';
    private const API_BASE  = 'https://api.copyleaks.com/v3/education';

    private string $email;
    private string $apiKey;

    public function __construct()
    {
        $this->email  = (string) getenv('COPYLEAKS_EMAIL');
        $this->apiKey = (string) getenv('COPYLEAKS_KEY');

        if (!$this->email || !$this->apiKey) {
            throw new \RuntimeException('Copyleaks credentials not set in .env (COPYLEAKS_EMAIL / COPYLEAKS_KEY)');
        }
    }

    /**
     * Authenticate and return Bearer token.
     */
    public function getToken(): string
    {
        $res = $this->request('POST', self::LOGIN_URL, [], [
            'email' => $this->email,
            'key'   => $this->apiKey,
        ]);

        if ($res['code'] !== 200) {
            throw new \RuntimeException('Copyleaks login failed [' . $res['code'] . ']: ' . $res['body']);
        }

        $data = json_decode($res['body'], true);
        if (empty($data['access_token'])) {
            throw new \RuntimeException('Copyleaks login: no access_token in response');
        }

        return $data['access_token'];
    }

    /**
     * Submit a file to Copyleaks for scanning.
     * $webhookBase should be the URL without trailing slash, e.g. https://yourdomain.com/copyleaks/webhook
     * Copyleaks will POST to {webhookBase}/{STATUS} when the scan completes.
     */
    public function submitFile(string $token, string $scanId, string $filePath, string $webhookBase): void
    {
        if (!file_exists($filePath)) {
            throw new \RuntimeException('Submission file not found: ' . $filePath);
        }

        $content  = file_get_contents($filePath);
        $base64   = base64_encode($content);
        $filename = basename($filePath);

        // Copyleaks rejects webhook URLs pointing to private/local IPs.
        // Only include the webhook when the base URL is a real public hostname.
        $properties = ['sandbox' => false];
        if ($this->isPublicUrl($webhookBase)) {
            $properties['webhooks'] = [
                'status' => rtrim($webhookBase, '/') . '/{STATUS}',
            ];
        }

        $res = $this->request(
            'PUT',
            self::API_BASE . '/submit/file/' . $scanId,
            ['Authorization: Bearer ' . $token],
            [
                'base64'     => $base64,
                'filename'   => $filename,
                'properties' => $properties,
            ]
        );

        // 200 or 201 = accepted
        if (!in_array($res['code'], [200, 201])) {
            throw new \RuntimeException('Copyleaks submitFile failed [' . $res['code'] . ']: ' . $res['body']);
        }
    }

    /**
     * Start the scan process for already-submitted file(s).
     */
    public function startScan(string $token, string $scanId): void
    {
        $res = $this->request(
            'POST',
            self::API_BASE . '/start',
            ['Authorization: Bearer ' . $token],
            [
                'triggerId'   => $scanId,
                'submissions' => [['id' => $scanId]],
            ]
        );

        if (!in_array($res['code'], [200, 201])) {
            throw new \RuntimeException('Copyleaks startScan failed [' . $res['code'] . ']: ' . $res['body']);
        }
    }

    /**
     * Generate a unique scan ID tied to the submission.
     * Copyleaks scan IDs must be alphanumeric.
     */
    public static function generateScanId(int $submissionId): string
    {
        return 'sub' . $submissionId . 'x' . substr(md5(uniqid((string)$submissionId, true)), 0, 18);
    }

    /**
     * Fetch scan result from Copyleaks (use for polling on localhost where webhooks don't work).
     * Returns the raw result array on success, null if the scan is not yet complete.
     */
    public function getScanResult(string $token, string $scanId): ?array
    {
        $res = $this->request(
            'GET',
            self::API_BASE . '/results/' . $scanId . '/summary',
            ['Authorization: Bearer ' . $token]
        );

        if ($res['code'] === 404 || $res['code'] === 204) {
            return null; // not ready yet
        }

        if ($res['code'] !== 200) {
            throw new \RuntimeException('Copyleaks getScanResult failed [' . $res['code'] . ']: ' . $res['body']);
        }

        return json_decode($res['body'], true) ?? [];
    }

    // ── Private helpers ──────────────────────────────────────────────────────

    /**
     * Returns true when $url points to a routable public hostname (not localhost/private).
     * Copyleaks rejects webhook URLs targeting private networks.
     */
    private function isPublicUrl(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) return false;

        // Reject localhost variants and private IP ranges
        if (in_array(strtolower($host), ['localhost', '127.0.0.1', '::1'])) return false;
        if (preg_match('/^(10\.|172\.(1[6-9]|2\d|3[01])\.|192\.168\.)/', $host)) return false;

        return true;
    }

    /**
     * @param string  $method  GET|POST|PUT
     * @param string  $url
     * @param array   $headers Additional headers (Content-Type: application/json is always added)
     * @param array   $body    Payload — will be JSON-encoded
     * @return array{code:int, body:string}
     */
    private function request(string $method, string $url, array $headers = [], array $body = []): array
    {
        $jsonBody = !empty($body) ? json_encode($body) : null;

        $headers = array_merge(['Content-Type: application/json'], $headers);

        // On Windows/WAMP dev environments cURL has no CA bundle — disable verification
        // in non-production only. In production CURLOPT_SSL_VERIFYPEER must be true.
        $isProd = (getenv('CI_ENVIRONMENT') === 'production');

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => strtoupper($method),
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => $isProd,
            CURLOPT_SSL_VERIFYHOST => $isProd ? 2 : 0,
        ]);

        if ($jsonBody !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
        }

        $body    = curl_exec($ch);
        $code    = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            throw new \RuntimeException('Copyleaks cURL error: ' . $curlErr);
        }

        return ['code' => $code, 'body' => $body ?: ''];
    }
}
