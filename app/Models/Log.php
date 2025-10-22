<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'subscription_id',
        'service_id',
        'service_offer_id',
        'transaction_id',
        'reference',
        'event_type',
        'request',
        'response',
    ];

    protected $casts = [
        'request' => 'array',
        'response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the subscriber that owns the log.
     */
    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * Get the subscription that owns the log.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the service that owns the log.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the service offer that owns the log.
     */
    public function serviceOffer()
    {
        return $this->belongsTo(ServiceOffer::class);
    }

    /**
     * Get the transaction that owns the log.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Scope to filter by event type.
     */
    public function scopeByEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope to filter by reference.
     */
    public function scopeByReference($query, $reference)
    {
        return $query->where('reference', $reference);
    }

    /**
     * Scope to get recent logs.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}