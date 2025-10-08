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
            'timestamp' => now()->toISOString(),
            'correlation_id' => $request->get('correlation-id'),
        ];
    }
}