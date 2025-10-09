<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HealthCheckResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->resource['status'] ?? 'ok',
            'timestamp' => now()->toISOString(),
            'service' => $this->resource['service'] ?? 'aggregation-api',
            'version' => $this->resource['version'] ?? '1.0.0',
            'memory_usage' => $this->getMemoryUsage(),
            'database_connection' => $this->checkDatabaseConnection(),
        ];
    }

    /**
     * Get the current memory usage.
     *
     * @return array<string, mixed>
     */
    private function getMemoryUsage(): array
    {
        return [
            'current' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
            'peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB'
        ];
    }

    /**
     * Check the database connection status.
     *
     * @return string
     */
    private function checkDatabaseConnection(): string
    {
        try {
            DB::connection()->getPdo();
            return 'connected';
        } catch (\Exception $e) {
            return 'disconnected';
        }
    }
}