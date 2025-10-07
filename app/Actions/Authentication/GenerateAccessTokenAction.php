<?php

namespace App\Actions\Authentication;

use App\Models\Partner;

class GenerateAccessTokenAction
{
    /**
     * Generate an access token for the authenticated partner.
     *
     * @param Partner $partner
     * @return array
     */
    public function execute(Partner $partner): array
    {
        // Use the existing create_token helper function
        return create_token($partner);
    }
}