<?php

namespace App\Http\Middleware;
    
use App\Models\ApiLog;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Closure;

class CorrelationIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Handle request processing (validation, logging, database storage)
        $requestResult = $this->handleRequest($request);
        
        // If request handling returned an error response, return it immediately
        if ($requestResult instanceof Response) {
            return $requestResult;
        }
        
        // Extract the request data from successful request handling
        $requestData = $requestResult;
        
        // Process the request
        $response = $next($request);

        // Handle response processing (correlation ID generation, logging, database storage)
        return $this->handleResponse($request, $response, $requestData);
    }
    
    /**
     * Handle request processing including validation, logging, and database storage.
     *
     * @param Request $request
     * @return array|Response Returns array with correlation_id and log_id on success, or error Response on failure
     */
    private function handleRequest(Request $request)
    {
        // Get correlation-id from header
        $correlationId = $request->header('correlation-id');
        
        // Return error if correlation-id is missing
        if (!$correlationId) {
            return response()->json([
                'success' => false,
                'message' => 'Correlation ID is required',
                'error' => 'Missing correlation-id in request headers'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // Validate correlation ID format
        if (!$this->isValidCorrelationId($correlationId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid correlation ID format',
                'error' => 'The correlation-id must be alphanumeric (8-64 characters)'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // Add correlation ID to request attributes for use throughout the application
        $request->attributes->set('correlation-id', $correlationId);
        $request->merge(['correlation-id' => $correlationId]);
        
        // Set correlation ID in log context for all subsequent logs
        Log::withContext(['correlation-id' => $correlationId]);
        
        // Log the incoming request with correlation ID
        Log::info('Incoming API request', [
            'correlation-id' => $correlationId,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        // Create initial log record with request data
        $logId = $this->createInitialLogRecord($request, $correlationId);
        
        return [
            'correlation_id' => $correlationId,
            'log_id' => $logId
        ];
    }

    /**
     * Create initial log record with request data.
     *
     * @param Request $request
     * @param string $correlationId
     * @return int|null Returns log ID on success, null on failure
     */
    private function createInitialLogRecord(Request $request, string $correlationId): ?int
    {
        try {
            $apiLog = ApiLog::create([
                'correlation_id' => $correlationId,
                'endpoint' => $request->getPathInfo(),
                'request_header' => [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'headers' => $request->headers->all(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now()->toISOString(),
                ],
                'request_body' => $request->all(),
                'response_header' => null,
                'response_body' => null,
            ]);
            
            return $apiLog->id;
        } catch (\Exception $e) {
            // Log the error but don't break the request flow
            Log::error('Failed to create initial log record', [
                'correlation-id' => $correlationId,
                'error' => $e->getMessage(),
            ]);
            
            return null;
        }
    }

    /**
     * Handle response processing including correlation ID generation, logging, and database storage.
     *
     * @param Request $request
     * @param Response $response
     * @param array $requestData
     * @return Response
     */
    private function handleResponse(Request $request, Response $response, array $requestData): Response
    {
        $requestCorrelationId = $requestData['correlation_id'];
        $logId = $requestData['log_id'];
        
        // Generate a new correlation ID for the response
        $responseCorrelationId = $this->generateCorrelationId();

        // Add response correlation ID to response headers
        $response->headers->set('correlation-id', $responseCorrelationId);

        // Update the existing log record with response data
        $this->updateLogRecordWithResponse($logId, $response, $responseCorrelationId);

        // Log the outgoing response
        Log::info('Outgoing API response', [
            'request_correlation-id' => $requestCorrelationId,
            'response_correlation-id' => $responseCorrelationId,
            'status_code' => $response->getStatusCode(),
        ]);

        return $response;
    }

    /**
     * Update existing log record with response data.
     *
     * @param int|null $logId
     * @param Response $response
     * @param string $responseCorrelationId
     * @return void
     */
    private function updateLogRecordWithResponse(?int $logId, Response $response, string $responseCorrelationId): void
    {
        if (!$logId) {
            Log::warning('Cannot update log record: log ID is null');
            return;
        }
        
        try {
            ApiLog::where('id', $logId)->update([
                'response_header' => [
                    'correlation_id' => $responseCorrelationId,
                    'status_code' => $response->getStatusCode(),
                    'headers' => $response->headers->all(),
                    'timestamp' => now()->toISOString(),
                ],
                'response_body' => $response->getContent(),
            ]);
        } catch (\Exception $e) {
            // Log the error but don't break the request flow
            Log::error('Failed to update log record with response', [
                'log_id' => $logId,
                'response_correlation_id' => $responseCorrelationId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Generate a new correlation ID.
     *
     * @return string
     */
    private function generateCorrelationId(): string
    {
        return 'txn_' . Str::uuid()->toString();
    }

    /**
     * Validate correlation ID format.
     *
     * @param string $correlationId
     * @return bool
     */
    private function isValidCorrelationId(string $correlationId): bool
    {
        // Allow only alphanumeric characters
        // Length between 8 and 64 characters
        return preg_match('/^[a-zA-Z0-9]{8,64}$/', $correlationId);
    }

}