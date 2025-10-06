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
        // Create comprehensive test partners
        $partners = [
            [
                'name' => 'NewsHub Media',
                'partner_id' => '550e8400-e29b-41d4-a716-446655440001',
                'partner_secret' => encrypt_sensitive('5ft55gyawcc96iop', config('app.encryption_key')),
                'description' => 'Leading news and media content provider specializing in real-time news updates and weather alerts',
                'billing_adress' => '123 Media Plaza, News District, Abidjan, Côte d\'Ivoire',
                'contacts' => json_encode([
                    'phone' => '+225-01-23-45-67',
                    'email' => 'contact@newshub.ci',
                    'fax' => '+225-01-23-45-68'
                ]),
                'commercial_contacts' => json_encode([
                    'name' => 'Kouadio Jean-Baptiste',
                    'email' => 'commercial@newshub.ci',
                    'phone' => '+225-01-23-45-69',
                    'position' => 'Commercial Director'
                ]),
                'technical_contacts' => json_encode([
                    'name' => 'Aminata Traoré',
                    'email' => 'tech@newshub.ci',
                    'phone' => '+225-01-23-45-70',
                    'position' => 'Technical Lead'
                ]),
                'url' => 'https://newshub.ci',
                'is_active' => true,
            ],
            [
                'name' => 'SportsTech Solutions',
                'partner_id' => '550e8400-e29b-41d4-a716-446655440002',
                'partner_secret' => encrypt_sensitive('89tyu5hg99yui10', config('app.encryption_key')),
                'description' => 'Sports and entertainment content aggregator providing live scores, match updates, and celebrity news',
                'billing_adress' => '456 Sports Avenue, Plateau District, Abidjan, Côte d\'Ivoire',
                'contacts' => json_encode([
                    'phone' => '+225-02-34-56-78',
                    'email' => 'contact@sportstech.ci',
                    'fax' => '+225-02-34-56-79'
                ]),
                'commercial_contacts' => json_encode([
                    'name' => 'Fatou Diallo',
                    'email' => 'sales@sportstech.ci',
                    'phone' => '+225-02-34-56-80',
                    'position' => 'Sales Manager'
                ]),
                'technical_contacts' => json_encode([
                    'name' => 'Youssouf Koné',
                    'email' => 'dev@sportstech.ci',
                    'phone' => '+225-02-34-56-81',
                    'position' => 'Lead Developer'
                ]),
                'url' => 'https://sportstech.ci',
                'is_active' => true,
            ],
            [
                'name' => 'FinanceWise Analytics',
                'partner_id' => '550e8400-e29b-41d4-a716-446655440003',
                'partner_secret' => encrypt_sensitive('aert41525gr87yudf', config('app.encryption_key')),
                'description' => 'Financial market data provider offering real-time market updates, health tips, and wellness content',
                'billing_adress' => '789 Finance Tower, Business District, Abidjan, Côte d\'Ivoire',
                'contacts' => json_encode([
                    'phone' => '+225-03-45-67-89',
                    'email' => 'contact@financewise.ci',
                    'fax' => '+225-03-45-67-90'
                ]),
                'commercial_contacts' => json_encode([
                    'name' => 'Marie-Claire Ouattara',
                    'email' => 'business@financewise.ci',
                    'phone' => '+225-03-45-67-91',
                    'position' => 'Business Development Manager'
                ]),
                'technical_contacts' => json_encode([
                    'name' => 'Ibrahim Sangaré',
                    'email' => 'support@financewise.ci',
                    'phone' => '+225-03-45-67-92',
                    'position' => 'Technical Support Manager'
                ]),
                'url' => 'https://financewise.ci',
                'is_active' => true,
            ],
            [
                'name' => 'Global Content Network',
                'partner_id' => '550e8400-e29b-41d4-a716-446655440004',
                'partner_secret' => encrypt_sensitive('aert41525gr87yu25', config('app.encryption_key')),
                'description' => 'International content distribution network providing diverse digital services across multiple verticals',
                'billing_adress' => '321 Global Street, International Zone, Abidjan, Côte d\'Ivoire',
                'contacts' => json_encode([
                    'phone' => '+225-04-56-78-90',
                    'email' => 'contact@globalcontent.ci',
                    'fax' => '+225-04-56-78-91'
                ]),
                'commercial_contacts' => json_encode([
                    'name' => 'Adama Coulibaly',
                    'email' => 'partnerships@globalcontent.ci',
                    'phone' => '+225-04-56-78-92',
                    'position' => 'Partnership Director'
                ]),
                'technical_contacts' => json_encode([
                    'name' => 'Nana Akoto',
                    'email' => 'api@globalcontent.ci',
                    'phone' => '+225-04-56-78-93',
                    'position' => 'API Integration Specialist'
                ]),
                'url' => 'https://globalcontent.ci',
                'is_active' => true,
            ],
            [
                'name' => 'Legacy Systems Inc',
                'partner_id' => '550e8400-e29b-41d4-a716-446655440005',
                'partner_secret' => encrypt_sensitive('12rt41525gr87yu25', config('app.encryption_key')),
                'description' => 'Legacy partner with outdated systems - used for testing backward compatibility',
                'billing_adress' => '999 Old Tech Road, Legacy District, Abidjan, Côte d\'Ivoire',
                'contacts' => json_encode([
                    'phone' => '+225-09-99-99-99',
                    'email' => 'contact@legacy.ci'
                ]),
                'commercial_contacts' => json_encode([
                    'name' => 'Ancien Système',
                    'email' => 'old@legacy.ci',
                    'phone' => '+225-09-99-99-98'
                ]),
                'technical_contacts' => json_encode([
                    'name' => 'Support Technique',
                    'email' => 'tech@legacy.ci',
                    'phone' => '+225-09-99-99-97'
                ]),
                'url' => 'https://legacy.ci',
                'is_active' => false, // Inactive for testing
            ],
            [
                'name' => 'Test Partner Sandbox',
                'partner_id' => '550e8400-e29b-41d4-a716-446655440006',
                'partner_secret' => encrypt_sensitive('12rt41uytgr87yu25', config('app.encryption_key')),
                'description' => 'Sandbox environment partner for development and testing purposes',
                'billing_adress' => '000 Sandbox Avenue, Test Environment, Dev City',
                'contacts' => json_encode([
                    'phone' => '+000-00-00-00-00',
                    'email' => 'sandbox@test.dev'
                ]),
                'commercial_contacts' => json_encode([
                    'name' => 'Test Commercial',
                    'email' => 'commercial@test.dev',
                    'phone' => '+000-00-00-00-01'
                ]),
                'technical_contacts' => json_encode([
                    'name' => 'Test Technical',
                    'email' => 'technical@test.dev',
                    'phone' => '+000-00-00-00-02'
                ]),
                'url' => 'https://sandbox.test.dev',
                'is_active' => true,
            ]
        ];

        foreach ($partners as $partnerData) {
            Partner::updateOrCreate(
                ['partner_id' => $partnerData['partner_id']],
                $partnerData
            );
        }

        $this->command->info('Partners seeded successfully!');
        $this->command->info('Created ' . count($partners) . ' partners with comprehensive data.');
        $this->command->info('');
        $this->command->info('Test credentials:');
        $this->command->info('NewsHub Media: partner_id=550e8400-e29b-41d4-a716-446655440001, partner_secret=secret123');
        $this->command->info('SportsTech Solutions: partner_id=550e8400-e29b-41d4-a716-446655440002, partner_secret=secret456');
        $this->command->info('FinanceWise Analytics: partner_id=550e8400-e29b-41d4-a716-446655440003, partner_secret=finance789');
        $this->command->info('Global Content Network: partner_id=550e8400-e29b-41d4-a716-446655440004, partner_secret=global2024');
        $this->command->info('Test Partner Sandbox: partner_id=550e8400-e29b-41d4-a716-446655440006, partner_secret=sandbox_test');
        $this->command->info('Legacy Systems (INACTIVE): partner_id=550e8400-e29b-41d4-a716-446655440005, partner_secret=legacy_old');
    }
}