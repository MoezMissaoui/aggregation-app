<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TransactionController extends BaseController
{
    /**
     * Display a listing of transactions.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            
            $query = Transaction::with(['subscription.subscriber', 'subscription.serviceOffer.service']);
            
            if ($request->has('subscription_id')) {
                $query->where('subscription_id', $request->get('subscription_id'));
            }
            
            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->get('date_from'));
            }
            
            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->get('date_to'));
            }
            
            if ($request->has('min_amount')) {
                $query->where('price', '>=', $request->get('min_amount'));
            }
            
            if ($request->has('max_amount')) {
                $query->where('price', '<=', $request->get('max_amount'));
            }
            
            $transactions = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            return $this->sendPaginatedResponse($transactions, 'Transactions retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving transactions', [$e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created transaction.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'subscription_id' => 'required|exists:subscriptions,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        try {
            $transaction = Transaction::create($request->all());
            $transaction->load(['subscription.subscriber', 'subscription.serviceOffer.service']);
            
            return $this->sendResponse($transaction, 'Transaction created successfully', 201);
            
        } catch (\Exception $e) {
            return $this->sendError('Error creating transaction', [$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified transaction.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $transaction = Transaction::with(['subscription.subscriber', 'subscription.serviceOffer.service', 'subscription.serviceOffer.operator'])
                                    ->findOrFail($id);
            
            return $this->sendResponse($transaction, 'Transaction retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Transaction not found', [$e->getMessage()], 404);
        }
    }

    /**
     * Get transactions by subscriber.
     *
     * @param Request $request
     * @param int $subscriberId
     * @return JsonResponse
     */
    public function bySubscriber(Request $request, int $subscriberId): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            
            $query = Transaction::with(['subscription.serviceOffer.service'])
                              ->whereHas('subscription', function ($q) use ($subscriberId) {
                                  $q->where('subscriber_id', $subscriberId);
                              });
            
            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->get('date_from'));
            }
            
            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->get('date_to'));
            }
            
            $transactions = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            return $this->sendPaginatedResponse($transactions, 'Subscriber transactions retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving subscriber transactions', [$e->getMessage()], 500);
        }
    }

    /**
     * Get transaction statistics.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $query = Transaction::query();
            
            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->get('date_from'));
            }
            
            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->get('date_to'));
            }
            
            $stats = [
                'total_transactions' => $query->count(),
                'total_revenue' => $query->sum('price'),
                'average_transaction_value' => $query->avg('price'),
                'max_transaction_value' => $query->max('price'),
                'min_transaction_value' => $query->min('price'),
            ];
            
            return $this->sendResponse($stats, 'Transaction statistics retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving transaction statistics', [$e->getMessage()], 500);
        }
    }
}