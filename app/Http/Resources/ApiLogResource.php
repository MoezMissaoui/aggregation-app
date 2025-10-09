<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class ApiLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'correlation_id' => $this->correlation_id,
            'endpoint' => $this->endpoint,
            'method' => $this->method,
            'request_headers' => $this->request_headers,
            'request_body' => $this->request_body,
            'response_status' => $this->response_status,
            'response_body' => $this->response_body,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'processing_time' => $this->processing_time,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}