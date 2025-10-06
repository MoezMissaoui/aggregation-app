<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends BaseController
{
    /**
     * Display a listing of subscriptions.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            
            $query = Subscription::with(['subscriber', 'serviceOffer.service', 'serviceOffer.operator']);
            
            if ($request->has('status')) {
                $query->where('status', $request->get('status'));
            }
            
            if ($request->has('subscriber_id')) {
                $query->where('subscriber_id', $request->get('subscriber_id'));
            }
            
            if ($request->has('service_offer_id')) {
                $query->where('service_offer_id', $request->get('service_offer_id'));
            }
            
            $subscriptions = $query->paginate($perPage);
            
            return $this->sendPaginatedResponse($subscriptions, 'Subscriptions retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving subscriptions', [$e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created subscription.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'subscriber_id' => 'required|exists:subscribers,id',
            'service_offer_id' => 'required|exists:service_offers,id',
            'status' => 'required|in:active,inactive,suspended,cancelled',
            'start' => 'required|date',
            'end' => 'nullable|date|after:start',
            'canal' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        try {
            $subscription = Subscription::create($request->all());
            $subscription->load(['subscriber', 'serviceOffer.service', 'serviceOffer.operator']);
            
            return $this->sendResponse($subscription, 'Subscription created successfully', 201);
            
        } catch (\Exception $e) {
            return $this->sendError('Error creating subscription', [$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified subscription.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $subscription = Subscription::with(['subscriber', 'serviceOffer.service', 'serviceOffer.operator', 'transactions'])
                                      ->findOrFail($id);
            
            return $this->sendResponse($subscription, 'Subscription retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Subscription not found', [$e->getMessage()], 404);
        }
    }

    /**
     * Update the specified subscription.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'subscriber_id' => 'sometimes|required|exists:subscribers,id',
            'service_offer_id' => 'sometimes|required|exists:service_offers,id',
            'status' => 'sometimes|required|in:active,inactive,suspended,cancelled',
            'start' => 'sometimes|required|date',
            'end' => 'nullable|date|after:start',
            'canal' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        try {
            $subscription = Subscription::findOrFail($id);
            $subscription->update($request->all());
            $subscription->load(['subscriber', 'serviceOffer.service', 'serviceOffer.operator']);
            
            return $this->sendResponse($subscription, 'Subscription updated successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Error updating subscription', [$e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified subscription.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $subscription = Subscription::findOrFail($id);
            $subscription->delete();
            
            return $this->sendResponse(null, 'Subscription deleted successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Error deleting subscription', [$e->getMessage()], 500);
        }
    }

    /**
     * Activate a subscription.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function activate(int $id): JsonResponse
    {
        try {
            $subscription = Subscription::findOrFail($id);
            $subscription->update(['status' => 'active']);
            $subscription->load(['subscriber', 'serviceOffer.service', 'serviceOffer.operator']);
            
            return $this->sendResponse($subscription, 'Subscription activated successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Error activating subscription', [$e->getMessage()], 500);
        }
    }

    /**
     * Deactivate a subscription.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deactivate(int $id): JsonResponse
    {
        try {
            $subscription = Subscription::findOrFail($id);
            $subscription->update(['status' => 'inactive']);
            $subscription->load(['subscriber', 'serviceOffer.service', 'serviceOffer.operator']);
            
            return $this->sendResponse($subscription, 'Subscription deactivated successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Error deactivating subscription', [$e->getMessage()], 500);
        }
    }
}