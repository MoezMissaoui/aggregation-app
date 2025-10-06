<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'msisdn',
        'date_subscription',
        'date_last_status_update',
        'date_end_trial_period',
        'date_last_unsub',
        'date_first_success_payment',
        'billing_status',
        'date_expired',
    ];

    protected $casts = [
        'date_subscription' => 'datetime',
        'date_last_status_update' => 'datetime',
        'date_end_trial_period' => 'datetime',
        'date_last_unsub' => 'datetime',
        'date_first_success_payment' => 'datetime',
        'date_expired' => 'datetime',
    ];

    /**
     * Get the logs for the subscriber.
     */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    /**
     * Get the subscriptions for the subscriber.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the transactions through subscriptions.
     */
    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Subscription::class);
    }

    /**
     * Scope to filter by MSISDN.
     */
    public function scopeByMsisdn($query, $msisdn)
    {
        return $query->where('msisdn', $msisdn);
    }

    /**
     * Scope to filter by billing status.
     */
    public function scopeByBillingStatus($query, $status)
    {
        return $query->where('billing_status', $status);
    }

    /**
     * Scope to get expired subscribers.
     */
    public function scopeExpired($query)
    {
        return $query->where('date_expired', '<', now());
    }

    /**
     * Scope to get active subscribers (not expired).
     */
    public function scopeActive($query)
    {
        return $query->where('date_expired', '>', now());
    }

    /**
     * Check if subscriber is in trial period.
     */
    public function isInTrialPeriod()
    {
        return $this->date_end_trial_period > now();
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
        return $this->date_subscription->diffInDays(now());
    }
}