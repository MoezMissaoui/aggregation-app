<?php

namespace App\Rules;

use App\Models\Subscriber;
use App\Models\Subscription;
use App\Enums\SubscriptionStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\In;

class ValidSubscriberWithOffer implements ValidationRule
{
    /**
     * The partner ID to validate against.
     */
    private int $service_offer_id;

    /**
     * Crée une nouvelle instance de la règle.
     */
    public function __construct(int $service_offer_id)
    {
        $this->service_offer_id = $service_offer_id;
    }
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (!$this->service_offer_id) {
            $fail('Wrong service offer ID.');
            return;
        }

        // Check if subscriber exists with the given MSISDN
        $subscriber = Subscriber::where('msisdn', $value)->first();

        if (!$subscriber) {
            $fail('The subscriber with this MSISDN does not exist.');
            return;
        }

        // Check if subscriber has an active subscription with the specified service offer
        $activeSubscription = Subscription::where('subscriber_id', $subscriber->id)
            ->where('service_offer_id', $this->service_offer_id)
            ->whereIn('status', [SubscriptionStatus::ACTIVE, SubscriptionStatus::SUSPENDED])
            ->first();

        if (!$activeSubscription) {
            $fail('The subscriber does not have an active subscription for this service offer.');
            return;
        }
    }
}