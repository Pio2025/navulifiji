<?php

/**
 * Password Generation Helper for CodeIgniter 4
 * 
 * Place this file in: app/Helpers/password_helper.php
 * Load in controller: helper('password');
 * Use: $password = generatePassword(10);
 */

if (!function_exists('generatePassword')) {
    /**
     * Generate a strong random password
     * 
     * @param int $length Password length (default: 12)
     * @param bool $includeSpecialChars Include special characters (default: true)
     * @param bool $includeNumbers Include numbers (default: true)
     * @param bool $includeUppercase Include uppercase letters (default: true)
     * @param bool $includeLowercase Include lowercase letters (default: true)
     * @return string Generated password
     * 
     * @example generatePassword(10) returns "aB3$xY9@kL"
     */
    function generatePassword(
        int $length = 12,
        bool $includeSpecialChars = true,
        bool $includeNumbers = true,
        bool $includeUppercase = true,
        bool $includeLowercase = true
    ): string {
        $chars = '';
        
        if ($includeLowercase) {
            $chars .= 'abcdefghijklmnopqrstuvwxyz';
        }
        
        if ($includeUppercase) {
            $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        
        if ($includeNumbers) {
            $chars .= '0123456789';
        }
        
        if ($includeSpecialChars) {
            $chars .= '!@#$%^&*()_+-=[]{}|;:,.<>?';
        }
        
        // Ensure we have at least some characters to work with
        if (empty($chars)) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        }
        
        $password = '';
        $charsLength = strlen($chars);
        
        // Generate random password using cryptographically secure random_int
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $charsLength - 1)];
        }
        
        return $password;
    }
}

if (!function_exists('generateStrongPassword')) {
    /**
     * Generate a guaranteed strong password with at least:
     * - 1 uppercase letter
     * - 1 lowercase letter
     * - 1 number
     * - 1 special character
     * 
     * @param int $length Password length (minimum 8, default: 12)
     * @return string Generated strong password
     * 
     * @example generateStrongPassword(12) returns "aB3$xY9@kL2m"
     */
    function generateStrongPassword(int $length = 12): string {
        // Ensure minimum length
        if ($length < 8) {
            $length = 8;
        }
        
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $special = '!@#$%^&*()_+-=[]{}|;:,.<>?';
        
        // Guarantee at least one of each type
        $password = '';
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];
        
        // Fill the rest randomly
        $allChars = $lowercase . $uppercase . $numbers . $special;
        $allCharsLength = strlen($allChars);
        
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $allChars[random_int(0, $allCharsLength - 1)];
        }
        
        // Shuffle the password to avoid predictable patterns
        $passwordArray = str_split($password);
        shuffle($passwordArray);
        
        return implode('', $passwordArray);
    }
}

if (!function_exists('generateReadablePassword')) {
    /**
     * Generate a readable password (no ambiguous characters)
     * Excludes: 0, O, l, 1, I (easily confused characters)
     * 
     * @param int $length Password length (default: 12)
     * @return string Generated readable password
     * 
     * @example generateReadablePassword(10) returns "aB3xY9tkR5"
     */
    function generateReadablePassword(int $length = 12): string {
        $lowercase = 'abcdefghijkmnopqrstuvwxyz'; // removed 'l'
        $uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // removed 'I' and 'O'
        $numbers = '23456789'; // removed '0' and '1'
        $special = '!@#$%^&*()_+-=';
        
        $allChars = $lowercase . $uppercase . $numbers . $special;
        $allCharsLength = strlen($allChars);
        
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $allChars[random_int(0, $allCharsLength - 1)];
        }
        
        return $password;
    }
}

if (!function_exists('generatePinCode')) {
    /**
     * Generate a numeric PIN code
     * 
     * @param int $length PIN length (default: 6)
     * @return string Generated PIN code
     * 
     * @example generatePinCode(6) returns "789012"
     */
    function generatePinCode(int $length = 6): string {
        $pin = '';
        
        for ($i = 0; $i < $length; $i++) {
            $pin .= random_int(0, 9);
        }
        
        return $pin;
    }
}

if (!function_exists('generateMemorablePassword')) {
    /**
     * Generate a memorable password using word combinations
     * Format: Word1-Word2-Number-Symbol
     * 
     * @return string Generated memorable password
     * 
     * @example generateMemorablePassword() returns "Dragon-Ocean-78@"
     */
    function generateMemorablePassword(): string {
        $words = [
            'Apple', 'Banana', 'Cherry', 'Dragon', 'Eagle', 'Falcon',
            'Garden', 'Hammer', 'Island', 'Jungle', 'Kitten', 'Lemon',
            'Mountain', 'Ninja', 'Ocean', 'Panda', 'Queen', 'River',
            'Safari', 'Tiger', 'Unicorn', 'Violet', 'Wizard', 'Xray',
            'Yellow', 'Zebra', 'Cloud', 'Phoenix', 'Storm', 'Thunder'
        ];
        
        $symbols = ['!', '@', '#', '$', '%', '^', '&', '*'];
        
        $word1 = $words[random_int(0, count($words) - 1)];
        $word2 = $words[random_int(0, count($words) - 1)];
        $number = random_int(10, 99);
        $symbol = $symbols[random_int(0, count($symbols) - 1)];
        
        return $word1 . '-' . $word2 . '-' . $number . $symbol;
    }
}

if (!function_exists('generateAlphanumericCode')) {
    /**
     * Generate an alphanumeric code (uppercase letters and numbers only)
     * Useful for verification codes, reference numbers, etc.
     * 
     * @param int $length Code length (default: 8)
     * @return string Generated alphanumeric code
     * 
     * @example generateAlphanumericCode(8) returns "A3K9M2N7"
     */
    function generateAlphanumericCode(int $length = 8): string {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Removed confusing: I, O, 0, 1
        $code = '';
        $charsLength = strlen($chars);
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, $charsLength - 1)];
        }
        
        return $code;
    }
}

if (!function_exists('validatePasswordStrength')) {
    /**
     * Validate password strength
     * 
     * @param string $password Password to validate
     * @return array Strength details [score, level, hasLowercase, hasUppercase, hasNumber, hasSpecial]
     * 
     * @example validatePasswordStrength('aB3$xY9@kL') returns ['score' => 6, 'level' => 'strong', ...]
     */
    function validatePasswordStrength(string $password): array {
        $strength = [
            'score' => 0,
            'length' => strlen($password),
            'hasLowercase' => preg_match('/[a-z]/', $password) ? true : false,
            'hasUppercase' => preg_match('/[A-Z]/', $password) ? true : false,
            'hasNumber' => preg_match('/[0-9]/', $password) ? true : false,
            'hasSpecial' => preg_match('/[^a-zA-Z0-9]/', $password) ? true : false,
            'level' => 'weak'
        ];
        
        // Calculate score
        if ($strength['length'] >= 8) $strength['score']++;
        if ($strength['length'] >= 12) $strength['score']++;
        if ($strength['length'] >= 16) $strength['score']++;
        if ($strength['hasLowercase']) $strength['score']++;
        if ($strength['hasUppercase']) $strength['score']++;
        if ($strength['hasNumber']) $strength['score']++;
        if ($strength['hasSpecial']) $strength['score']++;
        
        // Determine level
        if ($strength['score'] >= 6) {
            $strength['level'] = 'strong';
        } elseif ($strength['score'] >= 4) {
            $strength['level'] = 'medium';
        } else {
            $strength['level'] = 'weak';
        }
        
        return $strength;
    }
}