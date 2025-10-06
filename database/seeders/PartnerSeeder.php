<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Partner;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test partners
        $partners = [
            [
                'name' => 'Test Partner 1',
                'partner_id' => 'PARTNER_001',
                'partner_secret' => 'secret123', // Will be hashed by the model mutator
                'description' => 'Test partner for API authentication',
                'billing_adress' => '123 Test Street, Test City, TC 12345',
                'contacts' => json_encode([
                    'phone' => '+1234567890',
                    'email' => 'contact@testpartner1.com'
                ]),
                'commercial_contacts' => json_encode([
                    'name' => 'John Doe',
                    'email' => 'john.doe@testpartner1.com',
                    'phone' => '+1234567891'
                ]),
                'technical_contacts' => json_encode([
                    'name' => 'Jane Smith',
                    'email' => 'jane.smith@testpartner1.com',
                    'phone' => '+1234567892'
                ]),
                'url' => 'https://testpartner1.com',
                'is_active' => true,
            ],
            [
                'name' => 'Test Partner 2',
                'partner_id' => 'PARTNER_002',
                'partner_secret' => 'secret456', // Will be hashed by the model mutator
                'description' => 'Second test partner for API authentication',
                'billing_adress' => '456 Demo Avenue, Demo City, DC 67890',
                'contacts' => json_encode([
                    'phone' => '+0987654321',
                    'email' => 'contact@testpartner2.com'
                ]),
                'commercial_contacts' => json_encode([
                    'name' => 'Alice Johnson',
                    'email' => 'alice.johnson@testpartner2.com',
                    'phone' => '+0987654322'
                ]),
                'technical_contacts' => json_encode([
                    'name' => 'Bob Wilson',
                    'email' => 'bob.wilson@testpartner2.com',
                    'phone' => '+0987654323'
                ]),
                'url' => 'https://testpartner2.com',
                'is_active' => true,
            ],
            [
                'name' => 'Inactive Partner',
                'partner_id' => 'PARTNER_INACTIVE',
                'partner_secret' => 'inactive_secret', // Will be hashed by the model mutator
                'description' => 'Inactive partner for testing authentication failures',
                'billing_adress' => '789 Inactive Road, Inactive City, IC 11111',
                'contacts' => json_encode([
                    'phone' => '+1111111111',
                    'email' => 'contact@inactivepartner.com'
                ]),
                'url' => 'https://inactivepartner.com',
                'is_active' => false, // This partner is inactive
            ]
        ];

        foreach ($partners as $partnerData) {
            Partner::updateOrCreate(
                ['partner_id' => $partnerData['partner_id']],
                $partnerData
            );
        }

        $this->command->info('Partners seeded successfully!');
        $this->command->info('Test credentials:');
        $this->command->info('Partner 1: partner_id=PARTNER_001, partner_secret=secret123');
        $this->command->info('Partner 2: partner_id=PARTNER_002, partner_secret=secret456');
        $this->command->info('Inactive: partner_id=PARTNER_INACTIVE, partner_secret=inactive_secret (should fail)');
    }
}