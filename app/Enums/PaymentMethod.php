<?php

namespace App\Enums;

enum PaymentMethod: int
{
    case CASH = 0;
    case BANK_TRANSFER = 1;
    case QRIS = 2;
    case DEBIT_CARD = 3;
    case CREDIT_CARD = 4;
    case EWALLET = 5;

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Cash',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::QRIS => 'QRIS',
            self::DEBIT_CARD => 'Debit Card',
            self::CREDIT_CARD => 'Credit Card',
            self::EWALLET => 'E-Wallet',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::CASH => 'bg-label-success',
            self::BANK_TRANSFER => 'bg-label-primary',
            self::QRIS => 'bg-label-info',
            self::DEBIT_CARD => 'bg-label-warning',
            self::CREDIT_CARD => 'bg-label-danger',
            self::EWALLET => 'bg-label-secondary',
        };
    }
}