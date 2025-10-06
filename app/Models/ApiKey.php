<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key_hash',
        'key_prefix',
        'service_id',
        'description',
        'permissions',
        'expires_at',
        'is_active',
    ];

    protected $hidden = [
        'key_hash',
    ];

    protected $casts = [
        'permissions' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the service that owns the API key.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Generate a new API key and store its hash.
     * 
     * @param string $name
     * @param int $serviceId
     * @param array $permissions
     * @param string|null $description
     * @param Carbon|null $expiresAt
     * @return array ['api_key' => string, 'model' => ApiKey]
     */
    public static function generate(
        string $name,
        int $serviceId,
        array $permissions = [],
        ?string $description = null,
        ?Carbon $expiresAt = null
    ): array {
        // Generate a random API key
        $apiKey = 'sk_' . Str::random(48);
        $keyPrefix = substr($apiKey, 0, 8);
        
        // Create the API key record
        $model = self::create([
            'name' => $name,
            'key_hash' => hash('sha256', $apiKey),
            'key_prefix' => $keyPrefix,
            'service_id' => $serviceId,
            'description' => $description,
            'permissions' => $permissions,
            'expires_at' => $expiresAt,
            'is_active' => true,
        ]);

        return [
            'api_key' => $apiKey,
            'model' => $model,
        ];
    }

    /**
     * Verify if a given API key matches this record.
     */
    public function verifyKey(string $apiKey): bool
    {
        return hash_equals($this->key_hash, hash('sha256', $apiKey));
    }

    /**
     * Find an API key by its value and verify it.
     */
    public static function findByKey(string $apiKey): ?self
    {
        $keyPrefix = substr($apiKey, 0, 8);
        $keyHash = hash('sha256', $apiKey);

        return self::where('key_prefix', $keyPrefix)
                   ->where('key_hash', $keyHash)
                   ->where('is_active', true)
                   ->first();
    }

    /**
     * Check if the API key is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if the API key is valid (active and not expired).
     */
    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    /**
     * Update the last used timestamp.
     */
    public function markAsUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Revoke the API key (deactivate it).
     */
    public function revoke(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Check if the API key has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        if (empty($this->permissions)) {
            return false;
        }

        return in_array($permission, $this->permissions) || in_array('*', $this->permissions);
    }

    /**
     * Scope to get only active API keys.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only non-expired API keys.
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope to get valid API keys (active and not expired).
     */
    public function scopeValid($query)
    {
        return $query->active()->notExpired();
    }

    /**
     * Get the masked API key for display purposes.
     */
    public function getMaskedKeyAttribute(): string
    {
        return $this->key_prefix . str_repeat('*', 40);
    }
}