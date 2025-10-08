<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';
    case DELETED = 'deleted';
    case EXPIRED = 'expired';

    /**
     * Get all enum values as an array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all enum names as an array
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Get the enum case from a string value
     */
    public static function fromString(string $value): ?self
    {
        return self::tryFrom($value);
    }

    /**
     * Check if a value is valid
     */
    public static function isValid(string $value): bool
    {
        return self::tryFrom($value) !== null;
    }

    /**
     * Get human-readable label for the status
     */
    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::SUSPENDED => 'Suspended',
            self::DELETED => 'Deleted',
            self::EXPIRED => 'Expired',
        };
    }

    /**
     * Get description for the status
     */
    public function description(): string
    {
        return match($this) {
            self::ACTIVE => 'Subscription is currently active and billing',
            self::INACTIVE => 'Subscription is inactive and not billing',
            self::SUSPENDED => 'Subscription is temporarily suspended',
            self::DELETED => 'Subscription has been cancelled/deleted',
            self::EXPIRED => 'Subscription has expired',
        };
    }

    /**
     * Check if the status represents an active subscription
     */
    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * Check if the status represents an inactive subscription
     */
    public function isInactive(): bool
    {
        return in_array($this, [self::INACTIVE, self::SUSPENDED, self::DELETED, self::EXPIRED]);
    }
}