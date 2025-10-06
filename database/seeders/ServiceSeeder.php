<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Partner;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get partners to link services to
        $partner1 = Partner::where('partner_id', 'PARTNER_001')->first();
        $partner2 = Partner::where('partner_id', 'PARTNER_002')->first();
        $partner3 = Partner::where('partner_id', 'PARTNER_003')->first();

        $services = [
            [
                'name' => 'Premium News Service',
                'description' => 'Daily premium news updates and alerts',
                'shortcode' => '1234',
                'sub_keyword' => 'NEWS',
                'unsub_keyword' => 'STOP',
                'url' => 'https://api.partner1.com/news',
                'partner_id' => $partner1->id,
                'is_active' => true,
            ],
            [
                'name' => 'Weather Alerts',
                'description' => 'Real-time weather alerts and forecasts',
                'shortcode' => '1235',
                'sub_keyword' => 'WEATHER',
                'unsub_keyword' => 'STOP',
                'url' => 'https://api.partner1.com/weather',
                'partner_id' => $partner1->id,
                'is_active' => true,
            ],
            [
                'name' => 'Sports Updates',
                'description' => 'Live sports scores and match updates',
                'shortcode' => '2234',
                'sub_keyword' => 'SPORTS',
                'unsub_keyword' => 'QUIT',
                'url' => 'https://api.partner2.com/sports',
                'partner_id' => $partner2->id,
                'is_active' => true,
            ],
            [
                'name' => 'Entertainment News',
                'description' => 'Celebrity news and entertainment updates',
                'shortcode' => '2235',
                'sub_keyword' => 'CELEB',
                'unsub_keyword' => 'QUIT',
                'url' => 'https://api.partner2.com/entertainment',
                'partner_id' => $partner2->id,
                'is_active' => true,
            ],
            [
                'name' => 'Financial Market Updates',
                'description' => 'Stock market and financial news',
                'shortcode' => '3234',
                'sub_keyword' => 'FINANCE',
                'unsub_keyword' => 'END',
                'url' => 'https://api.partner3.com/finance',
                'partner_id' => $partner3->id,
                'is_active' => true,
            ],
            [
                'name' => 'Health Tips',
                'description' => 'Daily health tips and wellness advice',
                'shortcode' => '3235',
                'sub_keyword' => 'HEALTH',
                'unsub_keyword' => 'END',
                'url' => 'https://api.partner3.com/health',
                'partner_id' => $partner3->id,
                'is_active' => true,
            ],
            [
                'name' => 'Tech News',
                'description' => 'Latest technology news and updates',
                'shortcode' => '1236',
                'sub_keyword' => 'TECH',
                'unsub_keyword' => 'STOP',
                'url' => 'https://api.partner1.com/tech',
                'partner_id' => $partner1->id,
                'is_active' => true,
            ],
            [
                'name' => 'Travel Deals',
                'description' => 'Exclusive travel deals and offers',
                'shortcode' => '2236',
                'sub_keyword' => 'TRAVEL',
                'unsub_keyword' => 'QUIT',
                'url' => 'https://api.partner2.com/travel',
                'partner_id' => $partner2->id,
                'is_active' => false, // Inactive service for testing
            ],
        ];

        foreach ($services as $serviceData) {
            Service::create($serviceData);
        }

        $this->command->info('Services seeded successfully!');
    }
}