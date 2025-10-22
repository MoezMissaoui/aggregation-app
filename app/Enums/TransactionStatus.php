<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case SUCCEEDED = 'succeeded';
    case FAILED = 'failed';

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
            self::SUCCEEDED => 'Succeeded',
            self::FAILED => 'Failed',
        };
    }

    /**
     * Convenience helpers
     */
    public function isSuccessful(): bool
    {
        return $this === self::SUCCEEDED;
    }

    public function isFailed(): bool
    {
        return $this === self::FAILED;
    }
}