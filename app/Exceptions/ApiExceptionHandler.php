<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class ApiExceptionHandler
{
    /**
     * Handle API exceptions and return consistent JSON responses.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse|null
     */
    public static function handle(Request $request, Throwable $exception): ?JsonResponse
    {
        // Check if this is an API request
        if (!$request->is('api/*')) {
            return null; // Let the default handler take care of non-API requests
        }

        $response = self::getExceptionResponse($exception);
        
        // Add debug information in development
        if (config('app.debug') && config('api.response.include_debug_info', false)) {
            $response['data']['debug'] = [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ];
        }

        return ApiResponse::error($response['data'], $response['message'], $response['status_code']);
    }

    /**
     * Get the appropriate response for the given exception.
     *
     * @param Throwable $exception
     * @return array
     */
    private static function getExceptionResponse(Throwable $exception): array
    {
        switch (true) {
            case $exception instanceof ValidationException:
                return self::handleValidationException($exception);
                
            case $exception instanceof ModelNotFoundException:
                return self::handleModelNotFoundException($exception);
                
            case $exception instanceof NotFoundHttpException:
                return self::handleNotFoundHttpException();
                
            case $exception instanceof MethodNotAllowedHttpException:
                return self::handleMethodNotAllowedException($exception);
                
            case $exception instanceof AuthenticationException:
            case $exception instanceof UnauthorizedHttpException:
                return self::handleAuthenticationException();
                
            case $exception instanceof HttpException:
                return self::handleHttpException($exception);
                
            default:
                return self::handleGenericException($exception);
        }
    }

    /**
     * Handle validation exceptions.
     *
     * @param ValidationException $exception
     * @return array
     */
    private static function handleValidationException(ValidationException $exception): array
    {
        return [
            'data' => [
                'errors' => $exception->errors()
            ],
            'message' => 'Validation failed',
            'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY
        ];
    }

    /**
     * Handle model not found exceptions.
     *
     * @param ModelNotFoundException $exception
     * @return array
     */
    private static function handleModelNotFoundException(ModelNotFoundException $exception): array
    {
        $model = class_basename($exception->getModel());
        
        return [
            'data' => [
                'errors' => ["The requested {$model} could not be found"]
            ],
            'message' => "{$model} not found",
            'status_code' => Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * Handle not found HTTP exceptions.
     *
     * @return array
     */
    private static function handleNotFoundHttpException(): array
    {
        return [
            'data' => [
                'errors' => ['The requested API endpoint does not exist']
            ],
            'message' => 'Endpoint not found',
            'status_code' => Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * Handle method not allowed exceptions.
     *
     * @param MethodNotAllowedHttpException $exception
     * @return array
     */
    private static function handleMethodNotAllowedException(MethodNotAllowedHttpException $exception): array
    {
        $allowedMethods = implode(', ', $exception->getHeaders()['Allow'] ?? []);
        
        return [
            'data' => [
                'errors' => [
                    'The HTTP method used is not allowed for this endpoint',
                    $allowedMethods ? "Allowed methods: {$allowedMethods}" : ''
                ]
            ],
            'message' => 'Method not allowed',
            'status_code' => Response::HTTP_METHOD_NOT_ALLOWED
        ];
    }

    /**
     * Handle authentication exceptions.
     *
     * @return array
     */
    private static function handleAuthenticationException(): array
    {
        return [
            'data' => [
                'errors' => ['Authentication required. Please provide a valid API key']
            ],
            'message' => 'Unauthorized',
            'status_code' => Response::HTTP_UNAUTHORIZED
        ];
    }

    /**
     * Handle HTTP exceptions.
     *
     * @param HttpException $exception
     * @return array
     */
    private static function handleHttpException(HttpException $exception): array
    {
        $statusCode = $exception->getStatusCode();
        $message = Response::$statusTexts[$statusCode] ?? 'HTTP Error';
        
        return [
            'data' => [
                'errors' => [$exception->getMessage() ?: $message]
            ],
            'message' => $message,
            'status_code' => $statusCode
        ];
    }

    /**
     * Handle generic exceptions.
     *
     * @param Throwable $exception
     * @return array
     */
    private static function handleGenericException(Throwable $exception): array
    {
        // Log the exception for debugging
        Log::error('API Exception: ' . $exception->getMessage(), [
            'exception' => $exception,
            'trace' => $exception->getTraceAsString()
        ]);

        return [
            'data' => [
                'errors' => [
                    config('app.debug') 
                        ? $exception->getMessage() 
                        : 'An unexpected error occurred. Please try again later.'
                ]
            ],
            'message' => 'Internal server error',
            'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR
        ];
    }

    /**
     * Handle database connection exceptions.
     *
     * @return array
     */
    public static function handleDatabaseException(): array
    {
        return [
            'data' => [
                'errors' => ['Unable to connect to the database. Please try again later.']
            ],
            'message' => 'Database connection error',
            'status_code' => Response::HTTP_SERVICE_UNAVAILABLE
        ];
    }

    /**
     * Handle rate limiting exceptions.
     *
     * @param int $retryAfter
     * @return array
     */
    public static function handleRateLimitException(int $retryAfter = 60): array
    {
        return [
            'data' => [
                'errors' => [
                    'Too many requests. Please try again later.',
                    "Retry after: {$retryAfter} seconds"
                ],
                'retry_after' => $retryAfter
            ],
            'message' => 'Rate limit exceeded',
            'status_code' => Response::HTTP_TOO_MANY_REQUESTS
        ];
    }
}