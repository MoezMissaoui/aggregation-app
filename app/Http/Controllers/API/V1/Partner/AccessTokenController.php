<?php

namespace App\Http\Controllers\API\V1\Partner;

use App\Http\Controllers\API\V1\BaseController;
use App\Http\Requests\OAuth2TokenRequest;
use App\Helpers\ApiResponse;
use App\Models\Partner;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class AccessTokenController extends BaseController
{

    /**
     * Handle the incoming request to generate an authentication token for partner users.
     *
     * @param OAuth2TokenRequest $request
     * @return JsonResponse
     */
    function __invoke(OAuth2TokenRequest $request)
    {
        try {
            // Get validated data from the form request
            $validated = $request->validated();
            
            $clientId = $validated['client_id'];
            $clientSecret = $validated['client_secret'];
            $grantType = $validated['grant_type'];

            // Find the partner by client_id (partner_id)
            $partner = Partner::where('partner_id', $clientId)
                             ->where('is_active', true)
                             ->first();

            if (!$partner) {
                return ApiResponse::error([], 'Inactive client', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Verify the client secret
            if (decrypt_sensitive($partner->partner_secret, config('app.encryption_key')) !== $clientSecret) {
                return ApiResponse::error([], 'Invalid client secret', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Create a token for the partner
            $tokenData = create_token($partner);

            // Prepare the OAuth2 compliant response
            $response = [
                'access_token' => $tokenData['access_token'],
                'expires_in' => $tokenData['expires_in'],   
                'token_type' => 'Bearer',
                'scope' => 'api'
            ];

            return ApiResponse::success($response, 'Access token generated successfully');

        } catch (\Exception $e) {
            return ApiResponse::error([], 'Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}