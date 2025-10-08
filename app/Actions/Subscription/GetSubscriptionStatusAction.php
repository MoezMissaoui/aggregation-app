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
        $subscriber = Subscriber::where('msisdn', $data['msisdn'])->first();

        if (!$subscriber) {
            throw new \Exception("Subscriber with MSISDN {$data['msisdn']} not found");
        }

        // Récupérer le dernier abonnement actif du partenaire
        $subscription = Subscription::with('serviceOffer')
            ->where('subscriber_id', $subscriber->id)
            ->where('status', SubscriptionStatus::ACTIVE)
            ->whereHas('serviceOffer', function ($query) use ($data) {
                $query->whereHas('service', function ($query) use ($data) {
                    $query->where('partner_id', $data['partner_id']);
                });
            })
            ->orderBy('created_at', 'desc')
            ->first();

        return $subscription->load('subscriber');
    }
}
