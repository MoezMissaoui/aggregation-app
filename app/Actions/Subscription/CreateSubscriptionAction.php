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
    public function execute(array $data): Subscription
    {
        // Vérifier s'il existe déjà un abonnement actif
        $existingSubscription = Subscription::where('subscriber_id', $data['subscriber_id'])
            ->where('service_offer_id', $data['service_offer_id'])
            ->where('status', SubscriptionStatus::ACTIVE)
            ->first();

        if ($existingSubscription)
            return $existingSubscription;

        $offer = ServiceOffer::find($data['service_offer_id']);
        if (!$offer)
            throw new \Exception('Service offer not found');
        
        // Vérifier si c'est la première inscription de l'abonné
        $subscriber = Subscriber::find($data['subscriber_id']);
        $isFirstSubscription = $subscriber->subscriptions()->count() === 0;

        
        // Calculer la durée de l'abonnement (ajouter free_days seulement pour la première inscription)
        $subscriptionDays = $offer->frequency;
        if ($isFirstSubscription && $offer->free_days > 0) {
            $subscriptionDays += $offer->free_days;
        }


        // Créer un nouvel abonnement
        $subscription = Subscription::create([
            'subscriber_id' => $data['subscriber_id'],
            'service_offer_id' => $data['service_offer_id'],
            'status' => SubscriptionStatus::ACTIVE,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDay($subscriptionDays),
            'canal' => $data['canal'] ?? 'api',
        ]);

        // update subscriber billing status
        $subscriber = $subscription->subscriber;
        $subscriber->date_subscription = Carbon::now();
        $subscriber->date_end_trial_period = $isFirstSubscription && $offer->free_days > 0 ? Carbon::now()->addDay($offer->free_days) : null;
        $subscriber->date_last_status_update = Carbon::now();
        $subscriber->date_expired = $subscription->end_date;
        $subscriber->save();

        return $subscription;
    }
}