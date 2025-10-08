<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiErrorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->resource['code'] ?? 'error',
            'message' => $this->resource['message'] ?? 'An error occurred',
            'errors' => $this->resource['errors'] ?? [],
            'debug' => $this->when(
                config('app.debug') && config('api.response.include_debug_info', false),
                $this->resource['debug'] ?? []
            ),
            'timestamp' => now()->toISOString(),
            'correlation_id' => $request->get('correlation-id'),
        ];
    }
}