<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\Log as BillingLog;
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
        $now = now();

        $subscriptions = Subscription::active()
            ->select(['id','subscriber_id','service_offer_id','date_end_free_period','date_expired','status'])
            ->with([
                'serviceOffer:id,auto_renew',
                'latestTransaction:id,subscription_id,next_payment_date',
            ])
            ->get();

        foreach ($subscriptions as $subscription) {
            if ($subscription->isInFreePeriod()) {
                $this->info('In Free Trial period for subscription: ' . $subscription->id);
                $this->writeLog($subscription, 'billing.trial_skip', 'Skipped during free trial');
                continue;
            }

            $this->info('Active subscription: ' . $subscription->id);
            $this->writeLog($subscription, 'billing.active', 'Processing active subscription');

            $autoRenew = $subscription->serviceOffer?->auto_renew ?? false;
            if ($subscription->isExpired() && $autoRenew) {
                $this->info('Expired subscription: ' . $subscription->id);
                $this->writeLog($subscription, 'billing.expired_autorenew_skip', 'Expired with auto-renew set; skipping');
                continue;
            }

            if ($subscription->isNoTransactions()) {
                $this->info('No transactions subscription: ' . $subscription->id);
                $this->writeLog($subscription, 'billing.no_transactions', 'First charge candidate: no prior transactions');
                /*
                 * TODO: CALL Payment Gateway of operator.
                 *
                 * TODO: Create Transaction ROW.
                 */
                continue;
            }

            $lastTransaction = $subscription->latestTransaction; // eager-loaded
            if ($lastTransaction && $lastTransaction->next_payment_date && $lastTransaction->next_payment_date > $now) {
                $this->info('Next payment scheduled for last transaction: ' . $lastTransaction->id);
                $this->writeLog($subscription, 'billing.deferred', 'Deferred until next_payment_date', $lastTransaction);
                /*
                 * TODO: CALL Payment Gateway of operator.
                 *
                 * TODO: Create Transaction ROW with next_payment_date.
                 */
            } else {
                $this->info('Last transaction: ' . ($lastTransaction?->id ?? 'none'));
                $this->writeLog($subscription, 'billing.charge_now', 'Charge now', $lastTransaction);
                /*
                 * TODO: CALL Payment Gateway of operator.
                 *
                 * TODO: Create Transaction ROW.
                 */
            }
        }
    }

    /**
     * Persist a structured log entry for billing decisions.
     */
    private function writeLog(Subscription $subscription, string $eventType, string $message, $lastTransaction = null): void
    {
        BillingLog::create([
            'subscriber_id' => $subscription->subscriber_id,
            'subscription_id' => $subscription->id,
            'service_offer_id' => $subscription->service_offer_id,
            'transaction_id' => $lastTransaction?->id,
            'reference' => 'app:billing',
            'event_type' => $eventType,
            'request' => [
                'status' => (string) $subscription->status,
                'auto_renew' => $subscription->serviceOffer?->auto_renew,
                'date_end_free_period' => optional($subscription->date_end_free_period)->toIso8601String(),
                'date_expired' => optional($subscription->date_expired)->toIso8601String(),
                'next_payment_date' => optional($lastTransaction?->next_payment_date)->toIso8601String(),
            ],
            'response' => [
                'message' => $message,
            ],
        ]);
    }
}
