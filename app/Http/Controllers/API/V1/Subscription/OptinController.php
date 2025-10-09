<?php

namespace App\Http\Controllers\API\V1\Subscription;

use App\Services\Subscription\SubscriptionService;
use App\Http\Requests\SubscriptionOptinRequest;
use App\Http\Resources\SubscriptionResource;
use App\Http\Controllers\Controller;
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
            
            // Déterminer le message approprié
            $message = $result['is_new'] 
                ? 'Subscription opt-in successful' 
                : 'User is already subscribed';

            return ApiResponse::success(
                new SubscriptionResource($result['subscription']->load(['subscriber', 'serviceOffer'])), 
                $message
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }
}