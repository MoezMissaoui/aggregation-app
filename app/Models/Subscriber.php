<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'msisdn'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
}