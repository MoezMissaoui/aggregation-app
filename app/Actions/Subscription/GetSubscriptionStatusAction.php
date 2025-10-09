<?php

namespace App\Actions\Subscription;

use App\Enums\SubscriptionStatus;
use App\Http\Resources\SubscriberResource;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Models\Subscriber;

class GetSubscriptionStatusAction
{
    /**
     * Récupère le statut des abonnements d'un abonné
     */
    public function execute(array $data): Subscription
    {
        $subscriber = Subscriber::where('msisdn', $data['data']['msisdn'])->first();

        if (!$subscriber) {
            throw new \Exception("Subscriber with MSISDN {$data['data']['msisdn']} not found");
        }

        // Récupérer le dernier abonnement actif du partenaire
        $subscription = Subscription::with('serviceOffer')
            ->where('subscriber_id', $subscriber->id)
            ->where('service_offer_id', $data['data']['service_offer_id'])
            ->whereHas('serviceOffer.service.partner', function ($query) use ($data) {
                $query->where('partner_id', $data['partner_id']);
            })
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$subscription) {
            throw new \Exception("No subscription found for service offer ID {$data['data']['service_offer_id']}");
        }

        return $subscription->load('subscriber');
    }
}
