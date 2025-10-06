<?php

namespace App\Services;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;

class EncryptionService
{
    private string $cipher;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cipher = config('app.cipher', 'AES-256-CBC');
    }

    /**
     * Encrypt using AES-256-CBC
     *
     * @param string $data
     * @param string|null $key
     * @return string
     * @throws EncryptException
     */
    public function encryptSensitive(string $data, ?string $key = null): string
    {
        try {
            $key = $key ?: $this->getEncryptionKey();
            $iv = random_bytes(16); // AES-256-CBC requires 16-byte IV
            
            $encrypted = openssl_encrypt($data, $this->cipher, $key, 0, $iv);
            
            if ($encrypted === false) {
                throw new EncryptException('AES-256 encryption failed');
            }

            // Combine IV and encrypted data, then base64 encode the result
            return base64_encode($iv . $encrypted);
        } catch (\Exception $e) {
            throw new EncryptException('AES-256 encryption failed: ' . $e->getMessage());
        }
    }

    /**
     * Decrypt using AES-256-CBC
     *
     * @param string $encryptedData
     * @param string|null $key
     * @return string
     * @throws DecryptException
     */
    public function decryptSensitive(string $encryptedData, ?string $key = null): string
    {
        try {
            $key = $key ?: $this->getEncryptionKey();
            
            // Decode the base64 string
            $data = base64_decode($encryptedData);
            
            if ($data === false) {
                throw new DecryptException('Invalid base64 encoded data');
            }
            
            // Extract IV (first 16 bytes) and encrypted data (remaining bytes)
            if (strlen($data) < 16) {
                throw new DecryptException('Invalid encrypted data - too short');
            }
            
            $iv = substr($data, 0, 16);
            $encrypted = substr($data, 16);

            $decrypted = openssl_decrypt($encrypted, $this->cipher, $key, 0, $iv);

            if ($decrypted === false) {
                throw new DecryptException('AES-256 decryption failed');
            }

            return $decrypted;
        } catch (\Exception $e) {
            throw new DecryptException('AES-256 decryption failed: ' . $e->getMessage());
        }
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
     * Get encryption key from app configuration
     *
     * @return string
     * @throws EncryptException
     */
    private function getEncryptionKey(): string
    {
        $key = config('app.key');
        
        if (!$key) {
            throw new EncryptException('Application encryption key not configured');
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
        $cipher = config('app.cipher', 'AES-256-CBC');
        if (!in_array($cipher, openssl_get_cipher_methods())) {
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
            'current_cipher' => config('app.cipher', 'AES-256-CBC'),
            'key_configured' => !empty(config('app.key')),
            'openssl_version' => defined('OPENSSL_VERSION_TEXT') ? OPENSSL_VERSION_TEXT : 'Unknown',
            'supported_algorithms' => [
                'AES-256-CBC',
                'AES-256-GCM',
                'ChaCha20-Poly1305'
            ]
        ];
    }
}