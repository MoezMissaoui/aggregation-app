<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in proper order to maintain referential integrity
        $this->call([
            PartnerSeeder::class,      // First: Create partners
            ServiceSeeder::class,      // Second: Create services (depends on partners)
            ServiceOfferSeeder::class, // Fourth: Create service offers (depends on services and operators)
        ]);

        $this->command->info('All seeders completed successfully!');
        $this->command->info('Database has been seeded with:');
        $this->command->info('- Partners (6 partners including test and sandbox environments)');
        $this->command->info('- Services (8 services across different categories)');
        $this->command->info('- Service Offers (comprehensive offers across all operators)');
    }
}
