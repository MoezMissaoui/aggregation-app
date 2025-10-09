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
        $subscriber = Subscriber::where('msisdn', $msisdn)->first(['id', 'msisdn']);
        if (!$subscriber) {
            $subscriber = Subscriber::create([
                'msisdn' => $msisdn,
            ]);
        }
        return $subscriber;
    }
}