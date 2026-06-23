<?php
namespace App\Models;
use CodeIgniter\Model;

class TwoFactorModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'user_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'two_factor_method',
        'two_factor_secret',
        'two_factor_enabled',
        'otp_code',
        'otp_expiry',
        'otp_verified',
    ];

    public function enableAuthenticator(int $userId, string $secret): bool
    {
        return $this->update($userId, [
            'two_factor_method'  => 'authenticator',
            'two_factor_secret'  => $secret,
            'two_factor_enabled' => 1,
        ]);
    }

    public function enableOtpEmail(int $userId): bool
    {
        return $this->update($userId, [
            'two_factor_method'  => 'otp_email',
            'two_factor_secret'  => null,
            'two_factor_enabled' => 1,
        ]);
    }

    public function disable2FA(int $userId): bool
    {
        return $this->update($userId, [
            'two_factor_method'  => null,
            'two_factor_secret'  => null,
            'two_factor_enabled' => 0,
            'otp_code'           => null,
            'otp_expiry'         => null,
            'otp_verified'       => 0,
        ]);
    }

    public function saveOtp(int $userId, string $code): bool
    {
        return $this->update($userId, [
            'otp_code'    => $code,
            'otp_expiry'  => time() + (10 * 60), // 10 minutes
            'otp_verified'=> 0,
        ]);
    }

    public function clearOtp(int $userId): bool
    {
        return $this->update($userId, [
            'otp_code'    => null,
            'otp_expiry'  => null,
            'otp_verified'=> 0,
        ]);
    }

    public function get2FAData(int $userId): ?array
    {
        return $this->select('user_id, email, fname, lname, two_factor_method, two_factor_secret, two_factor_enabled, otp_code, otp_expiry, otp_verified')
                    ->find($userId);
    }
}