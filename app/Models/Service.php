<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'shortcode',
        'sub_keyword',
        'unsub_keyword',
        'url',
        'partner_id',
        'is_active',
    ];

    /**
     * Get the partner that owns the service.
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get the service offers for the service.
     */
    public function serviceOffers()
    {
        return $this->hasMany(ServiceOffer::class);
    }

    /**
     * Get the API keys for the service.
     */
    public function apiKeys()
    {
        return $this->hasMany(ApiKey::class);
    }

    /**
     * Get only active API keys for the service.
     */
    public function activeApiKeys()
    {
        return $this->hasMany(ApiKey::class)->active();
    }

    /**
     * Get only valid (active and not expired) API keys for the service.
     */
    public function validApiKeys()
    {
        return $this->hasMany(ApiKey::class)->valid();
    }

}