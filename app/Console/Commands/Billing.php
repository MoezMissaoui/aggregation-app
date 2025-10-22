<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class Billing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:billing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle billing for subscriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscriptions = Subscription::where('is_active', true)
            ->where(function($query) {
                $query->where('end_date', '>', now())
                    ->orWhere('start_date', '<=', now());
            })
            ->get();
    }
}
