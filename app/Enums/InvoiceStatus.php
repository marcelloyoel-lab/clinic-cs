<?php

namespace App\Enums;

enum InvoiceStatus: int
{
    case DRAFT = 0;
    case UNPAID = 1;
    case PARTIALLY_PAID = 2;
    case PAID = 3;
    case VOID = -1;
    case REFUNDED = -2;

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::UNPAID => 'Unpaid',
            self::PARTIALLY_PAID => 'Partially Paid',
            self::PAID => 'Paid',
            self::VOID => 'Void',
            self::REFUNDED => 'Refunded',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-label-secondary',
            self::UNPAID => 'bg-label-warning',
            self::PARTIALLY_PAID => 'bg-label-info',
            self::PAID => 'bg-label-success',
            self::VOID => 'bg-label-dark',
            self::REFUNDED => 'bg-label-danger',
        };
    }
}