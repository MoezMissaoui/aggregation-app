<?php

namespace App\Http\Controllers\API\V1\Partner;

use App\Http\Controllers\API\V1\BaseController;
use App\Http\Requests\OAuth2TokenRequest;
use App\Helpers\ApiResponse;
use App\Services\Authentication\AuthenticationService;
use App\Http\Resources\OAuth2TokenResource;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class AccessTokenController extends BaseController
{
    protected AuthenticationService $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * Handle the incoming request to generate an authentication token for partner users.
     *
     * @param OAuth2TokenRequest $request
     * @return JsonResponse
     */
    function __invoke(OAuth2TokenRequest $request): JsonResponse
    {
        try {
            // Get validated data from the form request
            $credentials = $request->validated();

            // Authenticate partner and generate token using the service
            $tokenResponse = $this->authenticationService->authenticatePartner($credentials);

            return ApiResponse::success(
                new OAuth2TokenResource($tokenResponse), 
                'Access token generated successfully'
            );

        } catch (ModelNotFoundException $e) {
            return ApiResponse::error([], 'Inactive client', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            // Handle specific authentication errors
            if (str_contains($e->getMessage(), 'Invalid client secret') || 
                str_contains($e->getMessage(), 'Unsupported grant type')) {
                return ApiResponse::error([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
            return ApiResponse::error([], 'Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}