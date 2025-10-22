<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'subscription_id' => $this->id,
            'status' => $this->status,

            'date_subscription' => $this->date_subscription?->toISOString(),
            'date_last_status_update' => $this->date_last_status_update?->toISOString(),
            'date_end_free_period' => $this->date_end_free_period?->toISOString(),
            'date_last_unsub' => $this->date_last_unsub?->toISOString(),
            'date_first_success_payment' => $this->date_first_success_payment?->toISOString(),
            'billing_status' => $this->billing_status,
            'date_expired' => $this->date_expired?->toISOString(),
            'is_in_trial_period' => $this->isInFreePeriod(),
            'is_expired' => $this->isExpired(),
            'days_since_subscription' => $this->daysSinceSubscription(),

            'canal' => $this->canal,
            'subscriber' => new SubscriberResource($this->whenLoaded('subscriber')),
            'service_offer' => new ServiceOfferResource($this->whenLoaded('serviceOffer')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}