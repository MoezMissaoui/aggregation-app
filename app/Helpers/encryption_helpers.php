<?php

use App\Services\EncryptionService;
use Illuminate\Support\Facades\App;



if (!function_exists('encrypt_sensitive')) {
    /**
     * Encrypt data using AES-256-CBC.
     *
     * @param string $data
     * @param string|null $key
     * @return string
     */
    function encrypt_sensitive(string $data, ?string $key = null): string
    {
        return App::make(EncryptionService::class)->encryptSensitive($data, $key);
    }
}

if (!function_exists('decrypt_sensitive')) {
    /**
     * Decrypt data using AES-256-CBC.
     *
     * @param array|string $encryptedData
     * @param string|null $key
     * @return string
     */
    function decrypt_sensitive($encryptedData, ?string $key = null): string
    {
        return App::make(EncryptionService::class)->decryptSensitive($encryptedData, $key);
    }
}

if (!function_exists('encryption_stats')) {
    /**
     * Get encryption service statistics.
     *
     * @return array
     */
    function encryption_stats(): array
    {
        return App::make(EncryptionService::class)->getStatistics();
    }
}

if (!function_exists('validate_encryption_config')) {
    /**
     * Validate encryption configuration.
     *
     * @return bool
     */
    function validate_encryption_config(): bool
    {
        return App::make(EncryptionService::class)->validateConfiguration();
    }
}

if (!function_exists('custom_encrypt_simple')) {
    /**
     * Simple custom encryption using Caesar cipher variant with dynamic shifting.
     * Note: This is for obfuscation purposes, not cryptographically secure.
     *
     * @param string $data The data to encrypt
     * @param string|null $key Optional key, generates random if not provided
     * @return string Contains encrypted data with embedded metadata
     */
    function custom_encrypt_simple(string $data, ?string $key = null): string
    {
        // Generate or use provided key
        if (!$key) {
            $key = bin2hex(random_bytes(8));
        }
        
        // Calculate checksum for integrity verification
        $checksum = crc32($data);
        
        // Create a seed from the key
        $seed = 0;
        for ($i = 0; $i < strlen($key); $i++) {
            $seed += ord($key[$i]) * ($i + 1);
        }
        $seed = $seed % 256;
        
        $encrypted = '';
        $dataLength = strlen($data);
        
        // Encrypt each character with dynamic shifting
        for ($i = 0; $i < $dataLength; $i++) {
            $char = ord($data[$i]);
            
            // Dynamic shift based on position, seed, and key
            $keyIndex = $i % strlen($key);
            $keyChar = ord($key[$keyIndex]);
            $shift = ($seed + $keyChar + $i) % 256;
            
            // Apply Caesar cipher with wrapping
            $encryptedChar = ($char + $shift) % 256;
            $encrypted .= chr($encryptedChar);
        }
        
        // Create metadata: checksum + length + seed
        $metadata = pack('N', $checksum) . pack('n', $dataLength) . chr($seed);
        
        // Combine metadata + encrypted data
        $combined = $metadata . $encrypted;
        
        // Apply final obfuscation layer
        $final = '';
        for ($i = 0; $i < strlen($combined); $i++) {
            $final .= chr(ord($combined[$i]) ^ ($i % 256));
        }
        
        return base64_encode($final);
    }
}

if (!function_exists('custom_decrypt_simple')) {
    /**
     * Simple custom decryption for data encrypted with custom_encrypt_simple.
     *
     * @param string $encryptedData Base64 encoded encrypted data
     * @param string $key The key used for encryption
     * @return string Decrypted data
     * @throws Exception If decryption fails or data is corrupted
     */
    function custom_decrypt_simple(string $encryptedData, string $key): string
    {
        $encoded = base64_decode($encryptedData);
        if ($encoded === false) {
            throw new Exception('Invalid base64 encoded data');
        }
        
        // Remove final obfuscation layer
        $combined = '';
        for ($i = 0; $i < strlen($encoded); $i++) {
            $combined .= chr(ord($encoded[$i]) ^ ($i % 256));
        }
        
        // Extract metadata (4 bytes checksum + 2 bytes length + 1 byte seed)
        if (strlen($combined) < 7) {
            throw new Exception('Invalid encrypted data format');
        }
        
        $checksum = unpack('N', substr($combined, 0, 4))[1];
        $dataLength = unpack('n', substr($combined, 4, 2))[1];
        $seed = ord($combined[6]);
        
        // Extract encrypted data
        $encrypted = substr($combined, 7);
        
        if (strlen($encrypted) !== $dataLength) {
            throw new Exception('Data length mismatch');
        }
        
        // Decrypt each character
        $decrypted = '';
        for ($i = 0; $i < $dataLength; $i++) {
            $encryptedChar = ord($encrypted[$i]);
            
            // Calculate the same shift used during encryption
            $keyIndex = $i % strlen($key);
            $keyChar = ord($key[$keyIndex]);
            $shift = ($seed + $keyChar + $i) % 256;
            
            // Reverse Caesar cipher with wrapping
            $originalChar = ($encryptedChar - $shift + 256) % 256;
            $decrypted .= chr($originalChar);
        }
        
        // Verify integrity using checksum
        if (crc32($decrypted) !== $checksum) {
            throw new Exception('Data integrity check failed - wrong key or corrupted data');
        }
        
        return $decrypted;
    }
}
