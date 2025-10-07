<?php

namespace App\Actions\Subscription;

use App\Models\ServiceOffer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ValidateServiceOfferAction
{
    /**
     * Valide l'existence et l'état d'une offre de service pour un partenaire
     */
    public function execute(int $service_offer_id, int $partner_id): ServiceOffer
    {
        $serviceOffer = ServiceOffer::where('id', $service_offer_id)
            ->whereHas('service', function ($query) use ($partner_id) {
                $query->where('partner_id', $partner_id);
            })
            ->first();

        if (!$serviceOffer) {
            throw new ModelNotFoundException(
                "Service offer with ID {$service_offer_id} not found for partner {$partner_id}"
            );
        }

        if (!$serviceOffer->is_active) {
            throw new \Exception("Service offer {$service_offer_id} is not active");
        }

        return $serviceOffer;
    }
}