<?php

namespace App\Actions\Subscription;

use App\Models\ServiceOffer;
use App\Models\Subscription;
use Carbon\Carbon;

class CreateSubscriptionAction
{
    /**
     * Crée un nouvel abonnement ou réactive un abonnement existant
     */
    public function execute(array $data): Subscription
    {
        // Vérifier s'il existe déjà un abonnement actif
        $existingSubscription = Subscription::where('subscriber_id', $data['subscriber_id'])
            ->where('service_offer_id', $data['service_offer_id'])
            ->where('status', 'active')
            ->first();

        if ($existingSubscription)
            return $existingSubscription;

        $offer = ServiceOffer::find($data['service_offer_id']);
        if (!$offer)
            throw new \Exception('Service offer not found');
        
        // Créer un nouvel abonnement
        $subscription = Subscription::create([
            'subscriber_id' => $data['subscriber_id'],
            'service_offer_id' => $data['service_offer_id'],
            'status' => 'active',
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDay($offer->frequency),
            'canal' => $data['canal'] ?? 'api',
        ]);

        return $subscription;
    }
}