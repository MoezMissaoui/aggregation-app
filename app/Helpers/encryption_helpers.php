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
