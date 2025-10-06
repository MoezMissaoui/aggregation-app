<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Encryption Facade
 * 
 * Provides easy access to encryption service methods.
 * 
 * @method static string encrypt(mixed $data)
 * @method static mixed decrypt(string $encryptedData)
 * @method static string hash(string $data, array $options = [])
 * @method static bool verifyHash(string $data, string $hash)
 * @method static string generateKey(int $length = 32)
 * @method static string generateToken(int $length = 32)
 * @method static string encryptAES256(mixed $data, string $key = null)
 * @method static mixed decryptAES256(string $encryptedData, string $key = null)
 * @method static string encryptSensitive(mixed $data, string $context = 'default')
 * @method static mixed decryptSensitive(string $encryptedData, string $context = 'default')
 * @method static bool encryptFile(string $filePath, string $outputPath = null, string $key = null)
 * @method static bool decryptFile(string $encryptedFilePath, string $outputPath = null, string $key = null)
 * @method static string generateApiKey(string $prefix = null)
 * @method static string generatePartnerSecret(int $length = null)
 * @method static string encryptDatabaseField(mixed $value, string $field)
 * @method static mixed decryptDatabaseField(string $encryptedValue, string $field)
 * @method static bool validateConfiguration()
 * @method static array getStatistics()
 * 
 * @see \App\Services\EncryptionService
 */
class Encryption extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'encryption';
    }
}