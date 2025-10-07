<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'correlation_id',
        'endpoint',
        'request_header',
        'request_body',
        'response_header',
        'response_body',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'request_header' => 'array',
        'request_body' => 'array',
        'response_header' => 'array',
        'response_body' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope to filter by correlation ID.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $correlationId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCorrelationId($query, string $correlationId)
    {
        return $query->where('correlation_id', $correlationId);
    }

    /**
     * Scope to filter by endpoint.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $endpoint
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByEndpoint($query, string $endpoint)
    {
        return $query->where('endpoint', $endpoint);
    }

    /**
     * Scope to get recent logs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope to filter logs between dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetweenDates($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get the request data as a formatted string.
     *
     * @return string
     */
    public function getFormattedRequestAttribute(): string
    {
        $requestData = [
            'header' => $this->request_header,
            'body' => $this->request_body,
        ];
        return json_encode($requestData, JSON_PRETTY_PRINT);
    }

    /**
     * Get the response data as a formatted string.
     *
     * @return string
     */
    public function getFormattedResponseAttribute(): string
    {
        $responseData = [
            'header' => $this->response_header,
            'body' => $this->response_body,
        ];
        return json_encode($responseData, JSON_PRETTY_PRINT);
    }
}