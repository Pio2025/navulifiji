<?php

namespace App\Libraries;

/**
 * Manual HS256 HMAC JWT encode/decode for the mobile API.
 * Mirrors ChatController::buildJwt()'s pattern, keyed by a dedicated MOBILE_JWT_SECRET.
 */
class ApiJwt
{
    public static function encode(array $claims, int $ttlSeconds = 2592000): string
    {
        $secret  = env('MOBILE_JWT_SECRET', 'navuli-mobile-secret-change-me-in-production');
        $header  = self::b64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = self::b64url(json_encode(array_merge($claims, [
            'iat' => time(),
            'exp' => time() + $ttlSeconds,
        ])));
        $sig = self::b64url(hash_hmac('sha256', "$header.$payload", $secret, true));

        return "$header.$payload.$sig";
    }

    /**
     * Returns the decoded payload array, or null if the token is missing/malformed/
     * has an invalid signature/is expired.
     */
    public static function decode(?string $token): ?array
    {
        if (empty($token) || substr_count($token, '.') !== 2) {
            return null;
        }

        [$header, $payload, $sig] = explode('.', $token);

        $secret   = env('MOBILE_JWT_SECRET', 'navuli-mobile-secret-change-me-in-production');
        $expected = self::b64url(hash_hmac('sha256', "$header.$payload", $secret, true));

        if (!hash_equals($expected, $sig)) {
            return null;
        }

        $claims = json_decode(self::b64urlDecode($payload), true);
        if (!is_array($claims)) {
            return null;
        }

        if (!empty($claims['exp']) && time() >= (int) $claims['exp']) {
            return null;
        }

        return $claims;
    }

    private static function b64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function b64urlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', (4 - strlen($data) % 4) % 4));
    }
}

/**
 * Holds the current request's decoded JWT claims — set by ApiJwtFilter::before(),
 * read by Api\* controllers. A plain static is safe here since each HTTP request
 * runs in its own PHP process/lifecycle (no cross-request leakage).
 */
class ApiAuth
{
    private static ?array $claims = null;

    public static function setClaims(array $claims): void
    {
        self::$claims = $claims;
    }

    public static function claims(): ?array
    {
        return self::$claims;
    }

    public static function userId(): int
    {
        return (int) (self::$claims['userId'] ?? 0);
    }
}
