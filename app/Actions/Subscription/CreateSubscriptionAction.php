<?php

namespace App\Actions\Subscription;

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
        
        // Créer un nouvel abonnement
        $subscription = Subscription::create([
            'subscriber_id' => $data['subscriber_id'],
            'service_offer_id' => $data['service_offer_id'],
            'status' => 'active',
            'start' => Carbon::now(),
            'end' => Carbon::now()->addMonth(),
            'canal' => $data['canal'] ?? 'api',
        ]);

        return $subscription;
    }
}