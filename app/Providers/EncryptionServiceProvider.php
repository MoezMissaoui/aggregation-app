<?php

namespace App\Providers;

use App\Services\EncryptionService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class EncryptionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register the EncryptionService as a singleton
        $this->app->singleton(EncryptionService::class, function ($app) {
            return new EncryptionService();
        });

        // Register the service with an alias
        $this->app->alias(EncryptionService::class, 'encryption');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Validate encryption configuration on boot
        $this->validateEncryptionConfiguration();
    }

    /**
     * Validate encryption configuration.
     *
     * @return void
     */
    protected function validateEncryptionConfiguration()
    {
        try {
            $encryptionService = $this->app->make(EncryptionService::class);
            
            if (!$encryptionService->validateConfiguration()) {
                Log::warning('Encryption service configuration validation failed');
            }
        } catch (\Exception $e) {
            Log::error('Failed to validate encryption configuration: ' . $e->getMessage());
        }
    }

}