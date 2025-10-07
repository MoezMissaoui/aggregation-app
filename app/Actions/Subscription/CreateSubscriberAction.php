<?php

namespace App\Actions\Subscription;

use App\Models\Subscriber;

class CreateSubscriberAction
{
    /**
     * Crée ou récupère un abonné basé sur le MSISDN
     */
    public function execute(string $msisdn): Subscriber
    {
        $subscriber = Subscriber::where('msisdn', $msisdn)->first();

        if (!$subscriber) {
            $subscriber = Subscriber::create([
                'msisdn' => $msisdn,
                'status' => 'active',
            ]);
        } else {
            // Réactiver l'abonné s'il était inactif
            if ($subscriber->status !== 'active') {
                $subscriber->update(['status' => 'active']);
            }
        }

        return $subscriber;
    }
}