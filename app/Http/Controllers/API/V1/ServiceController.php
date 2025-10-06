<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ServiceController extends BaseController
{
    /**
     * Display a listing of services.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            
            $query = Service::with(['partner', 'serviceOffers.operator']);
            
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }
            
            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }
            
            $services = $query->paginate($perPage);
            
            return $this->sendPaginatedResponse($services, 'Services retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving services', [$e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created service.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'partner_id' => 'required|exists:partners,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        try {
            $service = Service::create($request->all());
            $service->load(['partner', 'serviceOffers.operator']);
            
            return $this->sendResponse($service, 'Service created successfully', 201);
            
        } catch (\Exception $e) {
            return $this->sendError('Error creating service', [$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified service.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $service = Service::with(['partner', 'serviceOffers.operator', 'subscriptions'])
                             ->findOrFail($id);
            
            return $this->sendResponse($service, 'Service retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Service not found', [$e->getMessage()], 404);
        }
    }

    /**
     * Update the specified service.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'partner_id' => 'sometimes|required|exists:partners,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        try {
            $service = Service::findOrFail($id);
            $service->update($request->all());
            $service->load(['partner', 'serviceOffers.operator']);
            
            return $this->sendResponse($service, 'Service updated successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Error updating service', [$e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified service.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete();
            
            return $this->sendResponse(null, 'Service deleted successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Error deleting service', [$e->getMessage()], 500);
        }
    }

    /**
     * Get service statistics.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function statistics(int $id): JsonResponse
    {
        try {
            $service = Service::findOrFail($id);
            
            $stats = [
                'total_subscriptions' => $service->subscriptions()->count(),
                'active_subscriptions' => $service->subscriptions()->where('status', 'active')->count(),
                'total_transactions' => $service->transactions()->count(),
                'total_revenue' => $service->transactions()->sum('price'),
                'service_offers_count' => $service->serviceOffers()->count(),
            ];
            
            return $this->sendResponse($stats, 'Service statistics retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving service statistics', [$e->getMessage()], 500);
        }
    }
}