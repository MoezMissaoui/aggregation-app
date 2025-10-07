<?php

namespace App\Actions\Subscription;

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

        $query = Subscription::where('subscriber_id', $subscriber->id)
            ->where('status', 'active');

        // Si un service_offer_id spécifique est fourni
        if (isset($data['service_offer_id'])) {
            $query->where('service_offer_id', $data['service_offer_id']);
        } else {
            // Sinon, filtrer par partner_id via la relation service_offer
            $query->whereHas('serviceOffer', function ($q) use ($data) {
                $q->where('partner_id', $data['partner_id']);
            });
        }

        $subscriptions = $query->get();

        if ($subscriptions->isEmpty()) {
            throw new \Exception("No active subscriptions found for the given criteria");
        }

        // Marquer les abonnements comme supprimés
        $unsubscribedCount = 0;
        foreach ($subscriptions as $subscription) {
            $subscription->update([
                'status' => 'deleted',
                'end' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $unsubscribedCount++;
        }

        // Mettre à jour le statut de l'abonné si plus d'abonnements actifs
        $activeSubscriptions = Subscription::where('subscriber_id', $subscriber->id)
            ->where('status', 'active')
            ->count();

        if ($activeSubscriptions === 0) {
            $subscriber->update(['status' => 'inactive']);
        }

        return [
            'unsubscribed_count' => $unsubscribedCount,
            'subscriber_status' => $subscriber->fresh()->status,
        ];
    }
}