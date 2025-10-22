<?php

namespace App\Actions\Subscription;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\Subscriber;
use App\Models\ServiceOffer;
use Carbon\Carbon;

class CreateSubscriptionAction
{
    /**
     * Crée un nouvel abonnement ou réactive un abonnement existant
     */
    public function execute(array $data): array
    {
        // Vérifier s'il existe déjà un abonnement actif
        $existingSubscription = Subscription::where('subscriber_id', $data['subscriber_id'])
            ->where('service_offer_id', $data['service_offer_id'])
            ->first();


        $offer = ServiceOffer::find($data['service_offer_id']);
        if (!$offer)
            throw new \Exception('Service offer not found');

        if ($existingSubscription) {
            if ($existingSubscription->status === SubscriptionStatus::ACTIVE) {
                return [
                    'subscription' => $existingSubscription,
                    'is_new' => false,
                    'message' => 'already_subscribed'
                ];
            }

            // TODO: Payement nécessaire pour réactiver l'abonnement

            // Réactiver l'abonnement
            $existingSubscription->update([
                'status' => SubscriptionStatus::ACTIVE,
                'date_last_status_update' => Carbon::now(),
                'date_expired' => Carbon::now()->addDay($offer->frequency),
            ]);

            return [
                'subscription' => $existingSubscription,
                'is_new' => false,
                'message' => 'subscription_reactivated'
            ];
        }

        // TODO: Payement nécessaire pour créer un nouvel abonnement

        // Créer un nouvel abonnement
        $subscription = Subscription::create([
            'subscriber_id' => $data['subscriber_id'],
            'service_offer_id' => $data['service_offer_id'],
            'status' => SubscriptionStatus::ACTIVE,
            'date_subscription' => Carbon::now(),
            'date_last_status_update' => Carbon::now(),
            'date_end_free_period' => Carbon::now()->addDay($offer->free_days),
            'date_expired' => Carbon::now()->addDay($offer->frequency + $offer->free_days),
            'canal' => $data['canal'] ?? 'api',
        ]);

        return [
            'subscription' => $subscription,
            'is_new' => true,
            'message' => 'subscription_created'
        ];
    }
}