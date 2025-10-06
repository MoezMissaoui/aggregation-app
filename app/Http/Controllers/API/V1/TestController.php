<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TestController extends BaseController
{
    /**
     * Display a test response.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = [
            'message' => 'API is working correctly!',
            'timestamp' => now(),
            'version' => '1.0.0',
            'endpoints' => [
                'GET /api/v1/test' => 'Test endpoint',
                'POST /api/v1/test' => 'Test POST endpoint',
                'GET /api/health' => 'Health check',
                'GET /api/v1/public/services' => 'List all services',
            ],
            'environment' => app()->environment(),
        ];

        return $this->sendResponse($data, 'Test endpoint accessed successfully');
    }

    /**
     * Store test data.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        $data = [
            'received_data' => $request->only(['name', 'email', 'message']),
            'processed_at' => now(),
            'request_id' => uniqid('test_'),
            'status' => 'processed',
        ];

        return $this->sendResponse($data, 'Test data processed successfully', 201);
    }

    /**
     * Get system information.
     *
     * @return JsonResponse
     */
    public function systemInfo(): JsonResponse
    {
        $data = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'debug_mode' => config('app.debug'),
            'database_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
            'mail_driver' => config('mail.default'),
        ];

        return $this->sendResponse($data, 'System information retrieved successfully');
    }
}