<?php

namespace App\Actions\Authentication;

use App\Models\Partner;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ValidateOAuth2CredentialsAction
{
    /**
     * Validate OAuth2 credentials and return the authenticated partner.
     *
     * @param array $credentials
     * @return Partner
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function execute(array $credentials): Partner
    {
        $partnerId = $credentials['partner_id'];
        $partnerSecret = $credentials['partner_secret'];
        $grantType = $credentials['grant_type'];

        // Validate grant type
        if ($grantType !== 'client_credentials') {
            throw new Exception('Unsupported grant type');
        }

        // Find the partner by partner_id
        $partner = Partner::where('partner_id', $partnerId)
                         ->where('is_active', true)
                         ->first();

        if (!$partner) {
            throw new ModelNotFoundException('Inactive partner');
        }

        // Verify the partner secret using the model method
        if (!$partner->verifyPartnerSecret($partnerSecret)) {
            throw new Exception('Invalid partner secret');
        }

        return $partner;
    }
}