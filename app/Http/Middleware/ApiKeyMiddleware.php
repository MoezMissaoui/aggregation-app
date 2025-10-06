<?php

namespace App\Http\Middleware;

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
            return response()->json([
                'success' => false,
                'message' => 'API key is required',
                'error' => 'Missing API key in request headers or parameters'
            ], 401);
        }
        
        // Get valid API keys from config
        $validApiKeys = config('api.keys', []);
        
        if (!in_array($apiKey, $validApiKeys)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key',
                'error' => 'The provided API key is not valid'
            ], 401);
        }
        
        // Add API key info to request for logging
        $request->merge(['api_key_used' => $apiKey]);
        
        return $next($request);
    }
}