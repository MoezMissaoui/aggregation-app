<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:3',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the subscription that owns the transaction.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the subscriber through the subscription.
     */
    public function subscriber()
    {
        return $this->hasOneThrough(Subscriber::class, Subscription::class, 'id', 'id', 'subscription_id', 'subscriber_id');
    }

    /**
     * Get the service offer through the subscription.
     */
    public function serviceOffer()
    {
        return $this->hasOneThrough(ServiceOffer::class, Subscription::class, 'id', 'id', 'subscription_id', 'service_offer_id');
    }

    /**
     * Get the service through subscription and service offer.
     */
    public function service()
    {
        return $this->subscription->serviceOffer->service ?? null;
    }

    /**
     * Scope to filter by price range.
     */
    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    /**
     * Scope to get transactions for a specific period.
     */
    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope to get today's transactions.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope to get this month's transactions.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * Get total amount for the query.
     */
    public function scopeTotalAmount($query)
    {
        return $query->sum('price');
    }
}