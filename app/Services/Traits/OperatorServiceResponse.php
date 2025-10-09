<?php

namespace App\Services\Traits;

trait OperatorServiceResponse
{


    
    /**
     * Create a success response
     */
    private function successResponse(string $message, array $data = []): array
    {
        return [
            'status' => true,
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * Create an error response
     */
    private function errorResponse(string $message, array $data = []): array
    {
        return [
            'status' => false,
            'message' => $message,
            'data' => $data
        ];
    }
}
