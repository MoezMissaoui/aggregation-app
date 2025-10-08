<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceOffer;
use App\Models\Service;
use App\Models\Operator;

class ServiceOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, create some operators if they don't exist
        $operators = [
            ['name' => 'Orange', 'slug' => 'orange', 'description' => 'Orange Telecom Operator'],
            ['name' => 'MTN', 'slug' => 'mtn', 'description' => 'MTN Mobile Network'],
            ['name' => 'Moov', 'slug' => 'moov', 'description' => 'Moov Telecom Services'],
            ['name' => 'Telecel', 'slug' => 'telecel', 'description' => 'Telecel Mobile Network'],
        ];

        foreach ($operators as $operatorData) {
            Operator::firstOrCreate(['slug' => $operatorData['slug']], $operatorData);
        }

        // Get operators and services
        $orange = Operator::where('slug', 'orange')->first();
        $mtn = Operator::where('slug', 'mtn')->first();
        $moov = Operator::where('slug', 'moov')->first();
        $telecel = Operator::where('slug', 'telecel')->first();

        $services = Service::all();

        $serviceOffers = [];

        foreach ($services as $service) {
            // Create offers for each operator with different pricing
            
            // Orange offers
            $serviceOffers[] = [
                'name' => "{$service->name} - Orange Daily",
                'tarif' => 50.000, // 50 FCFA
                'frequency' => 1, // Daily
                'free_days' => 0, // No free trial for daily plans
                'currency' => 'XOF',
                'service_id' => $service->id,
                'operator_id' => $orange->id,
                'is_active' => true,
                'auto_renew' => true,
                'start_date' => now(),
                'end_date' => now()->addYear(),
            ];

            $serviceOffers[] = [
                'name' => "{$service->name} - Orange Weekly",
                'tarif' => 300.000, // 300 FCFA
                'frequency' => 7, // Weekly
                'free_days' => 3, // 3 days free trial (less than 7)
                'currency' => 'XOF',
                'service_id' => $service->id,
                'operator_id' => $orange->id,
                'is_active' => true,
                'auto_renew' => true,
                'start_date' => now(),
                'end_date' => now()->addYear(),
            ];

            // MTN offers
            $serviceOffers[] = [
                'name' => "{$service->name} - MTN Daily",
                'tarif' => 45.000, // 45 FCFA
                'frequency' => 1, // Daily
                'free_days' => 0, // No free trial for daily plans
                'currency' => 'XOF',
                'service_id' => $service->id,
                'operator_id' => $mtn->id,
                'is_active' => true,
                'auto_renew' => true,
                'start_date' => now(),
                'end_date' => now()->addYear(),
            ];

            $serviceOffers[] = [
                'name' => "{$service->name} - MTN Monthly",
                'tarif' => 1000.000, // 1000 FCFA
                'frequency' => 30, // Monthly
                'free_days' => 7, // 7 days free trial (less than 30)
                'currency' => 'XOF',
                'service_id' => $service->id,
                'operator_id' => $mtn->id,
                'is_active' => true,
                'auto_renew' => true,
                'start_date' => now(),
                'end_date' => now()->addYear(),
            ];

            // Moov offers (only for some services)
            if ($service->id % 2 == 0) {
                $serviceOffers[] = [
                    'name' => "{$service->name} - Moov Daily",
                    'tarif' => 55.000, // 55 FCFA
                    'frequency' => 1, // Daily
                    'free_days' => 0, // No free trial for daily plans
                    'currency' => 'XOF',
                    'service_id' => $service->id,
                    'operator_id' => $moov->id,
                    'is_active' => true,
                    'auto_renew' => true,
                    'start_date' => now(),
                    'end_date' => now()->addYear(),
                ];

                $serviceOffers[] = [
                    'name' => "{$service->name} - Moov Weekly",
                    'tarif' => 350.000, // 350 FCFA
                    'frequency' => 7, // Weekly
                    'free_days' => 2, // 2 days free trial (less than 7)
                    'currency' => 'XOF',
                    'service_id' => $service->id,
                    'operator_id' => $moov->id,
                    'is_active' => true,
                    'auto_renew' => true,
                    'start_date' => now(),
                    'end_date' => now()->addYear(),
                ];
            }

            // Telecel offers (only for premium services)
            if (in_array($service->id, [1, 3, 5])) {
                $serviceOffers[] = [
                    'name' => "{$service->name} - Telecel Premium Daily",
                    'tarif' => 75.000, // 75 FCFA
                    'frequency' => 1, // Daily
                    'free_days' => 0, // No free trial for daily plans
                    'currency' => 'XOF',
                    'service_id' => $service->id,
                    'operator_id' => $telecel->id,
                    'is_active' => true,
                    'auto_renew' => true,
                    'start_date' => now(),
                    'end_date' => now()->addYear(),
                ];

                $serviceOffers[] = [
                    'name' => "{$service->name} - Telecel Premium Monthly",
                    'tarif' => 1500.000, // 1500 FCFA
                    'frequency' => 30, // Monthly
                    'free_days' => 14, // 14 days free trial (less than 30)
                    'currency' => 'XOF',
                    'service_id' => $service->id,
                    'operator_id' => $telecel->id,
                    'is_active' => true,
                    'auto_renew' => true,
                    'start_date' => now(),
                    'end_date' => now()->addYear(),
                ];
            }
        }

        // Add some special promotional offers
        $newsService = Service::where('shortcode', '1234')->first();
        if ($newsService) {
            $serviceOffers[] = [
                'name' => "Premium News - Orange Promo",
                'tarif' => 25.000, // 25 FCFA (50% discount)
                'frequency' => 1, // Daily
                'free_days' => 0, // No free trial for daily plans
                'currency' => 'XOF',
                'service_id' => $newsService->id,
                'operator_id' => $orange->id,
                'is_active' => true,
                'auto_renew' => true,
                'start_date' => now(),
                'end_date' => now()->addYear(),
            ];

            $serviceOffers[] = [
                'name' => "Premium News - MTN Student Plan",
                'tarif' => 20.000, // 20 FCFA (student discount)
                'frequency' => 1, // Daily
                'free_days' => 0, // No free trial for daily plans
                'currency' => 'XOF',
                'service_id' => $newsService->id,
                'operator_id' => $mtn->id,
                'is_active' => true,
                'auto_renew' => true,
                'start_date' => now(),
                'end_date' => now()->addYear(),
            ];
        }

        // Add some inactive offers for testing
        $sportsService = Service::where('shortcode', '2234')->first();
        if ($sportsService) {
            $serviceOffers[] = [
                'name' => "Sports Updates - Orange Discontinued",
                'tarif' => 100.000, // 100 FCFA
                'frequency' => 1, // Daily
                'free_days' => 0, // No free trial for discontinued service
                'currency' => 'XOF',
                'service_id' => $sportsService->id,
                'operator_id' => $orange->id,
                'is_active' => false, // Inactive for testing
                'auto_renew' => true,
                'start_date' => now(),
                'end_date' => now()->addYear(),
            ];
        }

        // Create all service offers
        foreach ($serviceOffers as $offerData) {
            ServiceOffer::create($offerData);
        }

        $this->command->info('Service offers seeded successfully!');
        $this->command->info('Created ' . count($serviceOffers) . ' service offers across ' . count($operators) . ' operators.');
    }
}