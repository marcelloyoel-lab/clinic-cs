<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case PENDING = 0;
    case PROCESSING = 1;
    case PAID = 2;
    case FAILED = -1;
    case EXPIRED = -2;
    case CANCELLED = -3;
    case REFUNDED = -4;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::PAID => 'Paid',
            self::FAILED => 'Failed',
            self::EXPIRED => 'Expired',
            self::CANCELLED => 'Cancelled',
            self::REFUNDED => 'Refunded',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-label-warning',
            self::PROCESSING => 'bg-label-info',
            self::PAID => 'bg-label-success',
            self::FAILED => 'bg-label-danger',
            self::EXPIRED => 'bg-label-dark',
            self::CANCELLED => 'bg-label-secondary',
            self::REFUNDED => 'bg-label-primary',
        };
    }
}