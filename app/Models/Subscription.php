<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'service_offer_id',
        'status',
        'date_subscription',
        'date_last_status_update',
        'date_end_free_period',
        'date_last_unsub',
        'date_first_success_payment',
        'billing_status',
        'date_expired',
        'canal',
    ];

    protected $casts = [
        'status' => SubscriptionStatus::class,
        'date_subscription' => 'datetime',
        'date_last_status_update' => 'datetime',
        'date_end_free_period' => 'datetime',
        'date_last_unsub' => 'datetime',
        'date_first_success_payment' => 'datetime',
        'date_expired' => 'datetime',
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
        return $query->where('status', SubscriptionStatus::ACTIVE);
    }

    /**
     * Scope to get suspended subscriptions.
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', SubscriptionStatus::SUSPENDED);
    }

    /**
     * Scope to get deleted subscriptions.
     */
    public function scopeDeleted($query)
    {
        return $query->where('status', SubscriptionStatus::DELETED);
    }

    /**
     * Scope to get expired subscriptions.
     */
    public function scopeExpired($query)
    {
        return $query->where('status', SubscriptionStatus::EXPIRED);
    }

    /**
     * Scope to filter by subscriber.
     */
    public function scopeBySubscriber($query, $subscriberId)
    {
        return $query->where('subscriber_id', $subscriberId);
    }

    /**
     * Check if subscriber is in trial period.
     */
    public function isInFreePeriod()
    {
        return $this->date_end_free_period > now();
    }

    /**
     * Check if subscriber is expired.
     */
    public function isExpired()
    {
        return $this->date_expired < now();
    }

    /**
     * Get days since subscription.
     */
    public function daysSinceSubscription()
    {
        return max(0, (int) $this->date_subscription->diffInDays(now()));
    }

    /**
     * Efficiently check if there are no transactions without counting rows.
     */
    public function isNoTransactions()
    {
        return $this->transactions()->doesntExist();
    }

    /**
     * Latest transaction relation for eager-loading.
     */
    public function latestTransaction()
    {
        return $this->hasOne(Transaction::class)->latestOfMany();
    }
}