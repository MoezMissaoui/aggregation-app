<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ApiKey;
use App\Models\Service;
use Carbon\Carbon;

class ApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get services to create API keys for
        $services = Service::all();

        foreach ($services as $service) {
            // Create 2-3 API keys per service with different configurations
            
            // Production API Key
            $prodKey = ApiKey::generate(
                name: "Production API Key - {$service->name}",
                serviceId: $service->id,
                permissions: ['read', 'write', 'subscribe', 'unsubscribe'],
                description: "Production API key for {$service->name} with full permissions",
                expiresAt: Carbon::now()->addYear()
            );

            // Development API Key
            $devKey = ApiKey::generate(
                name: "Development API Key - {$service->name}",
                serviceId: $service->id,
                permissions: ['read', 'subscribe'],
                description: "Development API key for {$service->name} with limited permissions",
                expiresAt: Carbon::now()->addMonths(6)
            );

            // Testing API Key (some services only)
            if ($service->id % 2 == 0) {
                $testKey = ApiKey::generate(
                    name: "Testing API Key - {$service->name}",
                    serviceId: $service->id,
                    permissions: ['read'],
                    description: "Testing API key for {$service->name} with read-only access",
                    expiresAt: Carbon::now()->addMonths(3)
                );

                $this->command->info("Created testing API key for {$service->name}: {$testKey['api_key']}");
            }

            // Create one expired key for testing
            if ($service->id == 1) {
                $expiredKey = ApiKey::generate(
                    name: "Expired API Key - {$service->name}",
                    serviceId: $service->id,
                    permissions: ['read'],
                    description: "Expired API key for testing purposes",
                    expiresAt: Carbon::now()->subDays(30)
                );

                $this->command->info("Created expired API key for {$service->name}: {$expiredKey['api_key']}");
            }

            // Create one inactive key for testing
            if ($service->id == 2) {
                $inactiveKey = ApiKey::generate(
                    name: "Inactive API Key - {$service->name}",
                    serviceId: $service->id,
                    permissions: ['read', 'write'],
                    description: "Inactive API key for testing purposes",
                    expiresAt: Carbon::now()->addMonths(6)
                );

                // Deactivate the key
                $inactiveKey['model']->update(['is_active' => false]);

                $this->command->info("Created inactive API key for {$service->name}: {$inactiveKey['api_key']}");
            }

            $this->command->info("Created production API key for {$service->name}: {$prodKey['api_key']}");
            $this->command->info("Created development API key for {$service->name}: {$devKey['api_key']}");
        }

        // Create some API keys with special permissions
        $newsService = Service::where('shortcode', '1234')->first();
        if ($newsService) {
            $adminKey = ApiKey::generate(
                name: "Admin API Key - News Service",
                serviceId: $newsService->id,
                permissions: ['*'], // All permissions
                description: "Administrative API key with full access to all operations",
                expiresAt: Carbon::now()->addYears(2)
            );

            $this->command->info("Created admin API key for News Service: {$adminKey['api_key']}");
        }

        $this->command->info('API Keys seeded successfully!');
        $this->command->warn('IMPORTANT: Save these API keys securely. They cannot be retrieved again!');
    }
}