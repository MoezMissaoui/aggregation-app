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
        $clientId = $credentials['client_id'];
        $clientSecret = $credentials['client_secret'];
        $grantType = $credentials['grant_type'];

        // Validate grant type
        if ($grantType !== 'client_credentials') {
            throw new Exception('Unsupported grant type');
        }

        // Find the partner by client_id (partner_id)
        $partner = Partner::where('partner_id', $clientId)
                         ->where('is_active', true)
                         ->first();

        if (!$partner) {
            throw new ModelNotFoundException('Inactive client');
        }

        // Verify the client secret using the model method
        if (!$partner->verifyClientSecret($clientSecret)) {
            throw new Exception('Invalid client secret');
        }

        return $partner;
    }
}