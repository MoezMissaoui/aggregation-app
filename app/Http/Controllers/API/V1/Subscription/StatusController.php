<?php

namespace App\Http\Controllers\API\V1\Subscription;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionStatusRequest;
use App\Http\Resources\SubscriptionResource;
use App\Services\Subscription\SubscriptionService;
use App\Helpers\ApiResponse;

class StatusController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Handle subscription status check
     */
    public function __invoke(SubscriptionStatusRequest $request, string $partner_id)
    {
        try {
            $result = $this->subscriptionService->getStatus($request->validated(), $partner_id);

            return ApiResponse::success(new SubscriptionResource($result), 'Subscription status retrieved successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }
}