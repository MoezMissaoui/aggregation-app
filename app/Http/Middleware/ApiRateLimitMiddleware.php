<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiExceptionHandler;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ApiRateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key') ?? $request->get('api_key');
        $maxRequests = config('api.rate_limit.requests_per_minute', 60);
        $decayMinutes = 1;

        if (!$apiKey) {
            // If no API key, use IP-based rate limiting
            $key = 'rate_limit:' . $request->ip();
        } else {
            // Use API key-based rate limiting
            $key = 'rate_limit:api_key:' . $apiKey;
        }
        
        $attempts = Cache::get($key, 0);
        
        if ($attempts >= $maxRequests) {
            $retryAfter = Cache::get($key . ':retry_after', 60);
            $response = ApiExceptionHandler::handleRateLimitException($retryAfter);
            
            return response()->json($response, $response['status_code'])
                ->header('Retry-After', $retryAfter)
                ->header('X-RateLimit-Limit', $maxRequests)
                ->header('X-RateLimit-Remaining', 0);
        }
        
        // Increment the counter
        Cache::put($key, $attempts + 1, now()->addMinutes($decayMinutes));
        Cache::put($key . ':retry_after', 60, now()->addMinutes($decayMinutes));
        
        $response = $next($request);
        
        // Add rate limit headers to response
        $remaining = max(0, $maxRequests - ($attempts + 1));
        
        return $response
            ->header('X-RateLimit-Limit', $maxRequests)
            ->header('X-RateLimit-Remaining', $remaining)
            ->header('X-RateLimit-Reset', now()->addMinutes($decayMinutes)->timestamp);
    }
}