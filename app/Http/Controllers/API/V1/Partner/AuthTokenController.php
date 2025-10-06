<?php

namespace App\Http\Controllers\API\V1\Partner;

use App\Http\Controllers\API\V1\BaseController;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthTokenController extends BaseController
{

    /**
     * Handle the incoming request to generate an authentication token for partner users.
     *
     * @param Request $request
     * @return JsonResponse
     */
    function __invoke(Request $request)
    {
        
    }

}