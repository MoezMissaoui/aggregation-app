<?php

namespace App\Http\Middleware;

use App\Http\Resources\ApiErrorResource;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key') ?? $request->get('api_key');
        
        if (!$apiKey) {
            return response()->json(
                new ApiErrorResource([
                    'code' => 401,
                    'message' => 'API key is required',
                    'errors' => ['Missing API key in request headers or parameters']
                ]),
                401
            );
        }
        
        // Get valid API keys from config
        $validApiKeys = config('api.keys', []);
        
        if (!in_array($apiKey, $validApiKeys)) {
            return response()->json(
                new ApiErrorResource([
                    'code' => 401,
                    'message' => 'Invalid API key',
                    'errors' => ['The provided API key is not valid']
                ]),
                401
            );
        }
        
        // Add API key info to request for logging
        $request->merge(['api_key_used' => $apiKey]);
        
        // Get correlation ID from request (set by CorrelationIdMiddleware)
        $correlationId = $request->get('correlation-id') ?? $request->attributes->get('correlation-id');
        
        // Log API key validation with correlation ID
        if ($correlationId) {
            \Illuminate\Support\Facades\Log::info('API key validated successfully', [
                'correlation-id' => $correlationId,
                'api_key_hash' => substr(hash('sha256', $apiKey), 0, 8) // Log only hash prefix for security
            ]);
        }
        
        return $next($request);
    }
}