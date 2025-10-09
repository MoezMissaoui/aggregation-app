<?php

namespace App\Rules;

use App\Models\ServiceOffer;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class ValidServiceOffer implements ValidationRule
{
    /**
     * The partner ID to validate against.
     */
    private string $partner_id;

    /**
     * Crée une nouvelle instance de la règle.
     */
    public function __construct(string $partner_id)
    {
        $this->partner_id = $partner_id;
    }
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        // Check if the service offer exists and belongs to the partner
        $serviceOffer = ServiceOffer::where('id', $value)
            ->whereHas('service.partner', function ($query) {
                $query->where('partner_id', $this->partner_id);
            })
            ->first();

        if (!$serviceOffer) {
            $fail("Service offer with ID {$value} not found for partner {$this->partner_id}");
            return;
        }

        // Check if the service offer is active
        if (!$serviceOffer->is_active) {
            $fail("Service offer {$value} is not active");
            return;
        }
    }
}