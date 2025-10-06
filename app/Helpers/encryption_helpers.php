<?php

use App\Services\EncryptionService;
use Illuminate\Support\Facades\App;

if (!function_exists('secure_encrypt')) {
    /**
     * Encrypt data using the encryption service.
     *
     * @param mixed $data
     * @return string
     */
    function secure_encrypt($data)
    {
        return App::make(EncryptionService::class)->encrypt($data);
    }
}

if (!function_exists('secure_decrypt')) {
    /**
     * Decrypt data using the encryption service.
     *
     * @param string $encryptedData
     * @return mixed
     */
    function secure_decrypt($encryptedData)
    {
        return App::make(EncryptionService::class)->decrypt($encryptedData);
    }
}

if (!function_exists('secure_hash')) {
    /**
     * Hash data using the encryption service.
     *
     * @param string $data
     * @param array $options
     * @return string
     */
    function secure_hash($data, array $options = [])
    {
        return App::make(EncryptionService::class)->hash($data, $options);
    }
}

if (!function_exists('secure_verify_hash')) {
    /**
     * Verify a hash using the encryption service.
     *
     * @param string $data
     * @param string $hash
     * @return bool
     */
    function secure_verify_hash($data, $hash)
    {
        return App::make(EncryptionService::class)->verifyHash($data, $hash);
    }
}

if (!function_exists('generate_secure_key')) {
    /**
     * Generate a secure random key.
     *
     * @param int $length
     * @return string
     */
    function generate_secure_key($length = 32)
    {
        return App::make(EncryptionService::class)->generateKey($length);
    }
}

if (!function_exists('generate_secure_token')) {
    /**
     * Generate a secure random token.
     *
     * @param int $length
     * @return string
     */
    function generate_secure_token($length = 32)
    {
        return App::make(EncryptionService::class)->generateToken($length);
    }
}

if (!function_exists('encrypt_sensitive')) {
    /**
     * Encrypt sensitive data with metadata.
     *
     * @param mixed $data
     * @param string $context
     * @return string
     */
    function encrypt_sensitive($data, $key = null)
    {
        return App::make(EncryptionService::class)->encryptSensitive($data, $key);
    }
}

if (!function_exists('decrypt_sensitive')) {
    /**
     * Decrypt sensitive data with metadata validation.
     *
     * @param string $encryptedData
     * @param string $context
     * @return mixed
     */
    function decrypt_sensitive($encryptedData, $key = null)
    {
        return App::make(EncryptionService::class)->decryptSensitive($encryptedData, $key);
    }
}

if (!function_exists('generate_api_key')) {
    /**
     * Generate a secure API key.
     *
     * @param string $prefix
     * @param int $length
     * @return string
     */
    function generate_api_key($prefix = '', $length = 32)
    {
        return App::make(EncryptionService::class)->generateApiKey($prefix, $length);
    }
}

if (!function_exists('generate_partner_secret')) {
    /**
     * Generate a secure partner secret.
     *
     * @param int|null $length
     * @return string
     */
    function generate_partner_secret($length = null)
    {
        return App::make(EncryptionService::class)->generatePartnerSecret($length);
    }
}


if (!function_exists('encryption_stats')) {
    /**
     * Get encryption service statistics.
     *
     * @return array
     */
    function encryption_stats()
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
    function validate_encryption_config()
    {
        return App::make(EncryptionService::class)->validateConfiguration();
    }
}