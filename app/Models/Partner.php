<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Partner extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'partner_id',
        'partner_secret',
        'description',
        'billing_adress',
        'contacts',
        'commercial_contacts',
        'technical_contacts',
        'url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'contacts' => 'array',
        'commercial_contacts' => 'array',
        'technical_contacts' => 'array',
    ];

    /**
     * Get the services for the partner.
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Scope a query to only include active partners.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Verify if the provided client secret matches the partner's secret.
     *
     * @param string $clientSecret
     * @return bool
     */
    public function verifyClientSecret(string $clientSecret): bool
    {
        return decrypt_sensitive($this->partner_secret, config('app.encryption_key')) === $clientSecret;
    }
}