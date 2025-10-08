<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_DELETED = 'deleted';
    const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'subscriber_id',
        'service_offer_id',
        'status',
        'start_date',
        'end_date',
        'canal',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the subscriber that owns the subscription.
     */
    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * Get the service offer that owns the subscription.
     */
    public function serviceOffer()
    {
        return $this->belongsTo(ServiceOffer::class);
    }

    /**
     * Get the transactions for the subscription.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the logs for the subscription.
     */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    /**
     * Get the service through the service offer.
     */
    public function service()
    {
        return $this->hasOneThrough(Service::class, ServiceOffer::class, 'id', 'id', 'service_offer_id', 'service_id');
    }

    /**
     * Scope to get active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope to get suspended subscriptions.
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', self::STATUS_SUSPENDED);
    }

    /**
     * Scope to get deleted subscriptions.
     */
    public function scopeDeleted($query)
    {
        return $query->where('status', self::STATUS_DELETED);
    }

    /**
     * Scope to get expired subscriptions.
     */
    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    /**
     * Scope to filter by subscriber.
     */
    public function scopeBySubscriber($query, $subscriberId)
    {
        return $query->where('subscriber_id', $subscriberId);
    }

    /**
     * Check if subscription is active.
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if subscription is expired.
     */
    public function isExpired()
    {
        return $this->end_date < now();
    }
}