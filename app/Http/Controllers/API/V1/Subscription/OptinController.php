<?php

namespace App\Http\Controllers\API\V1\Subscription;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionOptinRequest;
use App\Services\Subscription\SubscriptionService;
use App\Helpers\ApiResponse;

class OptinController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Handle subscription opt-in
     */
    public function __invoke(SubscriptionOptinRequest $request, string $partner_id)
    {
        try {
            $result = $this->subscriptionService->optin($request->validated(), $partner_id);
            
            return ApiResponse::success($result, 'Subscription opt-in successful');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }
}