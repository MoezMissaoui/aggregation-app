<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Partner extends Authenticatable
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
     * Verify if the provided partner secret matches the partner's secret.
     *
     * @param string $partnerSecret
     * @return bool
     */
    public function verifyPartnerSecret(string $partnerSecret): bool
    {
        return decrypt_sensitive($this->partner_secret, config('app.encryption_key')) === $partnerSecret;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->partner_secret;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return null;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        // Not implemented for partners
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return null;
    }
}