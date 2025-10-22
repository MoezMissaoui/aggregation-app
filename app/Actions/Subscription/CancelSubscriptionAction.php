<?php

namespace App\Actions\Subscription;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\Subscriber;
use Carbon\Carbon;

class CancelSubscriptionAction
{
    /**
     * Annule les abonnements d'un abonné
     */
    public function execute(array $data): array
    {
        $subscriber = Subscriber::where('msisdn', $data['msisdn'])->first();

        if (!$subscriber) {
            throw new \Exception("Subscriber with MSISDN {$data['msisdn']} not found");
        }

        $subscriptions = Subscription::where('subscriber_id', $subscriber->id)
            ->where('status', SubscriptionStatus::ACTIVE)
            ->where('service_offer_id', $data['service_offer_id'])->get();

        if ($subscriptions->isEmpty()) {
            throw new \Exception("No active subscriptions found for the given offer and partner");
        }


        // Marquer les abonnements comme supprimés
        foreach ($subscriptions as $subscription) {
            $subscription->update([
                'status' => SubscriptionStatus::DELETED,
                'date_last_status_update' => Carbon::now(),
                'date_last_unsub' => Carbon::now()
            ]);
        }

        return [
            'subscriber' => $subscriber->load('subscriptions'),
        ];
    }
}