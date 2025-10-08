<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'msisdn' => $this->msisdn,
            'date_subscription' => $this->date_subscription?->toISOString(),
            'date_last_status_update' => $this->date_last_status_update?->toISOString(),
            'date_end_trial_period' => $this->date_end_trial_period?->toISOString(),
            'date_last_unsub' => $this->date_last_unsub?->toISOString(),
            'date_first_success_payment' => $this->date_first_success_payment?->toISOString(),
            'billing_status' => $this->billing_status,
            'date_expired' => $this->date_expired?->toISOString(),
            'is_in_trial_period' => $this->isInTrialPeriod(),
            'is_expired' => $this->isExpired(),
            'days_since_subscription' => $this->daysSinceSubscription(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}