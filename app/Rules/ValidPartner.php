<?php

namespace App\Rules;

use App\Models\Partner;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPartner implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            $fail('User is not authenticated');
            return;
        }

        // Check if the partner exists and get its status
        $partner = Partner::where('partner_id', $value)->first(['id', 'is_active']);

        if (!$partner) {
            $fail("Partner with ID {$value} not found");
            return;
        }

        // Check if the partner_id matches the authenticated user's partner_id
        if (auth()->user()->partner_id != $value) {
            $fail('Partner ID does not match authenticated user');
            return;
        }

        // Check if the partner is active
        if (!$partner->is_active) {
            $fail("Partner {$value} is not active");
            return;
        }
    }
}