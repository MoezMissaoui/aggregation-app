<?php

namespace App\Services\Authentication;

use App\Actions\Authentication\ValidateOAuth2CredentialsAction;
use App\Actions\Authentication\GenerateAccessTokenAction;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class AuthenticationService
{
    protected ValidateOAuth2CredentialsAction $validateCredentialsAction;
    protected GenerateAccessTokenAction $generateTokenAction;

    public function __construct(
        ValidateOAuth2CredentialsAction $validateCredentialsAction,
        GenerateAccessTokenAction $generateTokenAction
    ) {
        $this->validateCredentialsAction = $validateCredentialsAction;
        $this->generateTokenAction = $generateTokenAction;
    }

    /**
     * Authenticate a partner using OAuth2 credentials and generate an access token.
     *
     * @param array $credentials
     * @return array
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function authenticatePartner(array $credentials): array
    {
        // Validate OAuth2 credentials and get the partner
        $partner = $this->validateCredentialsAction->execute($credentials);

        // Generate access token for the authenticated partner
        $tokenData = $this->generateTokenAction->execute($partner);

        // Return OAuth2 compliant response
        return [
            'access_token' => $tokenData['access_token'],
            'expires_in' => $tokenData['expires_in'],
            'token_type' => 'Bearer',
            'scope' => 'api'
        ];
    }
}