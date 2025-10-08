<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tarif',
        'frequency',
        'currency',
        'service_id',
        'operator_id',
        'is_active',
        'auto_renew',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'tarif' => 'decimal:3',
        'frequency' => 'integer',
        'is_active' => 'boolean',
        'auto_renew' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the service that owns the service offer.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the operator that owns the service offer.
     */
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    /**
     * Get the subscriptions for the service offer.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the logs for the service offer.
     */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    /**
     * Get the transactions through subscriptions.
     */
    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Subscription::class);
    }

    /**
     * Get the subscribers through subscriptions.
     */
    public function subscribers()
    {
        return $this->hasManyThrough(Subscriber::class, Subscription::class);
    }

    /**
     * Scope to filter by operator.
     */
    public function scopeForOperator($query, $operatorId)
    {
        return $query->where('operator_id', $operatorId);
    }

    /**
     * Scope to filter by service.
     */
    public function scopeForService($query, $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }
}