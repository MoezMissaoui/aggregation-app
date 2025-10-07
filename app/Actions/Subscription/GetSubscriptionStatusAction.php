<?php

namespace App\Actions\Subscription;

use App\Models\Subscription;
use App\Models\Subscriber;

class GetSubscriptionStatusAction
{
    /**
     * Récupère le statut des abonnements d'un abonné
     */
    public function execute(array $data): array
    {
        $subscriber = Subscriber::where('msisdn', $data['msisdn'])->first();

        if (!$subscriber) {
            throw new \Exception("Subscriber with MSISDN {$data['msisdn']} not found");
        }

        // Récupérer les abonnements actifs du partenaire
        $subscriptions = Subscription::with('serviceOffer')
            ->where('subscriber_id', $subscriber->id)
            ->where('status', 'active')
            ->whereHas('serviceOffer', function ($query) use ($data) {
                $query->whereHas('service', function ($query) use ($data) {
                    $query->where('partner_id', $data['partner_id']);
                });
            })
            ->get();

        // Mapper les données des abonnements
        $subscriptionData = $subscriptions->map(function ($subscription) {
            return [
                'subscription_id' => $subscription->id,
                'service_offer_id' => $subscription->service_offer_id,
                'service_name' => $subscription->serviceOffer->name,
                'status' => $subscription->status,
                'start_date' => $subscription->start->toISOString(),
                'end_date' => $subscription->end->toISOString(),
                'tarif' => $subscription->serviceOffer->tarif,
                'frequency' => $subscription->serviceOffer->frequency,
                'currency' => $subscription->serviceOffer->currency,
            ];
        });

        return [
            'subscriber_id' => $subscriber->id,
            'msisdn' => $subscriber->msisdn,
            'subscriber_status' => $subscriber->status,
            'subscriptions' => $subscriptionData,
        ];
    }
}
