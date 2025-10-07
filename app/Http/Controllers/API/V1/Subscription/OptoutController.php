<?php

namespace App\Http\Controllers\API\V1\Subscription;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionOptoutRequest;
use App\Services\Subscription\SubscriptionService;
use App\Helpers\ApiResponse;

class OptoutController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Handle subscription opt-out
     */
    public function __invoke(SubscriptionOptoutRequest $request, string $partner_id)
    {
        try {
            $result = $this->subscriptionService->optout($request->validated(), $partner_id);
            
            return ApiResponse::success($result, 'Subscription opt-out successful');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }
}