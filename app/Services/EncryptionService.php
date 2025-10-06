<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;

class EncryptionService
{
    /**
     * Encryption configuration
     */
    private string $cipher;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cipher = config('app.cipher', 'AES-256-CBC');
    }

    /**
     * Encrypt data using Laravel's default encryption
     *
     * @param mixed $data
     * @param bool $serialize
     * @return string
     * @throws EncryptException
     */
    public function encrypt($data, bool $serialize = true): string
    {
        try {
            return Crypt::encrypt($data, $serialize);
        } catch (\Exception $e) {
            throw new EncryptException('Failed to encrypt data: ' . $e->getMessage());
        }
    }

    /**
     * Decrypt data using Laravel's default encryption
     *
     * @param string $encryptedData
     * @param bool $unserialize
     * @return mixed
     * @throws DecryptException
     */
    public function decrypt(string $encryptedData, bool $unserialize = true)
    {
        try {
            return Crypt::decrypt($encryptedData, $unserialize);
        } catch (\Exception $e) {
            throw new DecryptException('Failed to decrypt data: ' . $e->getMessage());
        }
    }

    /**
     * Hash data using bcrypt
     *
     * @param string $data
     * @param array $options
     * @return string
     */
    public function hash(string $data, array $options = []): string
    {
        return Hash::make($data, $options);
    }

    /**
     * Verify hashed data
     *
     * @param string $data
     * @param string $hashedData
     * @return bool
     */
    public function verifyHash(string $data, string $hashedData): bool
    {
        return Hash::check($data, $hashedData);
    }

    /**
     * Generate a secure random key
     *
     * @param int $length
     * @return string
     */
    public function generateKey(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Generate a secure random token
     *
     * @param int $length
     * @return string
     */
    public function generateToken(int $length = 40): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Encrypt using AES-256-CBC
     *
     * @param string|array $data
     * @param string|null $key
     * @return string
     * @throws EncryptException
     */
    public function encryptSensitive(string|array $data, ?string $key = null): string
    {
        try {
            $key = $key ?: $this->getEncryptionKey();

            $ivlen = openssl_cipher_iv_length($this->cipher);
            $iv = openssl_random_pseudo_bytes($ivlen);

            if (is_array($data))
                $data = json_encode($data);
            
            $encrypted = openssl_encrypt($data, $this->cipher, $key, 0, $iv);
            
            if ($encrypted === false) {
                throw new EncryptException('AES-256 encryption failed');
            }

            return base64_encode($encrypted);
        } catch (\Exception $e) {
            throw new EncryptException('AES-256 encryption failed: ' . $e->getMessage());
        }
    }

    /**
     * Decrypt using AES-256-CBC
     *
     * @param string|array $encryptedData
     * @param string|null $key
     * @return string
     * @throws DecryptException
     */
    public function decryptSensitive(string|array $encryptedData, ?string $key = null): string
    {
        try {
            $key = $key ?: $this->getEncryptionKey();

            if (is_array($encryptedData))
                $encryptedData = json_encode($encryptedData);
            

            $data = base64_decode($encryptedData);
            $ivlen = openssl_cipher_iv_length($this->cipher);
            $iv = openssl_random_pseudo_bytes($ivlen);

            $decrypted = openssl_decrypt($data, $this->cipher, $key, 0, $iv);

            if ($decrypted === false) {
                throw new DecryptException('AES-256 decryption failed');
            }

            return $decrypted;
        } catch (\Exception $e) {
            throw new DecryptException('AES-256 decryption failed: ' . $e->getMessage());
        }
    }


    /**
     * Generate API key with specific format
     *
     * @param string $prefix
     * @param int $length
     * @return string
     */
    public function generateApiKey(string $prefix = 'ak', int $length = 32): string
    {
        $randomPart = $this->generateToken($length);
        return $prefix . '_' . $randomPart;
    }

    /**
     * Generate partner secret
     *
     * @param int $length
     * @return string
     */
    public function generatePartnerSecret(int $length = 64): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        $secret = '';
        
        for ($i = 0; $i < $length; $i++) {
            $secret .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        return $secret;
    }



    /**
     * Get encryption key from configuration
     *
     * @return string
     * @throws EncryptException
     */
    private function getEncryptionKey(): string
    {
        $key = config('app.key');
        
        if (!$key) {
            throw new EncryptException('Encryption key not configured');
        }

        // Remove 'base64:' prefix if present
        if (str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        return $key;
    }

    /**
     * Validate encryption configuration
     *
     * @return array
     */
    public function validateConfiguration(): array
    {
        $issues = [];

        // Check if app key is set
        if (!config('app.key')) {
            $issues[] = 'Application key (APP_KEY) is not set';
        }

        // Check if encryption cipher is supported
        $cipher = $this->cipher;
        if (!in_array(strtolower($cipher), openssl_get_cipher_methods())) {
            $issues[] = "Encryption cipher '{$cipher}' is not supported";
        }

        // Check OpenSSL extension
        if (!extension_loaded('openssl')) {
            $issues[] = 'OpenSSL extension is not loaded';
        }

        return [
            'valid' => empty($issues),
            'issues' => $issues,
            'cipher' => $cipher,
            'key_length' => strlen(config('app.key', '')),
            'openssl_available' => extension_loaded('openssl')
        ];
    }

    /**
     * Get encryption statistics
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return [
            'available_ciphers' => openssl_get_cipher_methods(),
            'current_cipher' => $this->cipher,
            'key_configured' => !empty(config('app.key')),
            'openssl_version' => defined('OPENSSL_VERSION_TEXT') ? OPENSSL_VERSION_TEXT : 'Unknown'
        ];
    }
}