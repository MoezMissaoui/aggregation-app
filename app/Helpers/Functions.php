<?php




if (!function_exists('create_token')) {
    /**
     * Create a token for the given client.
     *
     * @param \Laravel\Sanctum\Client $client
     * @return array
     */
    function create_token($client): array
    {

        // Revoke existing tokens for this client (optional - for security)
        $client->tokens()->delete();

        $expiration = config('sanctum.expiration');

        // Generate new access token
        $tokenName = "oauth2_token_" . $client->name . "_" . now()->timestamp;
        $token = $client->createToken($tokenName, ['*']);
        $token->accessToken->update(['expires_at' => now()->addMinutes($expiration)]);

        $token_text = $token->plainTextToken;
        $tab_token = explode('|', $token_text);
        $token_text = ($tab_token[1]) ? $tab_token[1] : $token_text;

        return [
            'access_token' => $token_text,
            'expires_in' => $expiration,
        ];
    }
}