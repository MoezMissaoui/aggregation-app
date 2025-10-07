<?php

namespace App\Actions\Subscription;

use App\Models\Partner;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ValidatePartnerAction
{
    /**
     * Valide l'existence et l'état d'un partenaire
     */
    public function execute(string $partner_id): Partner
    {
        $partner = Partner::where('partner_id', $partner_id)->first();

        if (!$partner) {
            throw new ModelNotFoundException("Partner with ID {$partner_id} not found");
        }

        if (!$partner->is_active) {
            throw new \Exception("Partner {$partner_id} is not active");
        }

        return $partner;
    }
}