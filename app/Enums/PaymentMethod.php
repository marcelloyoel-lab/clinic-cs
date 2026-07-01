<?php

namespace App\Enums;

enum PaymentMethod: int
{
    case CASH = 0;

    // QR
    case QRIS = 1;

    // E-Wallet
    case GOPAY = 2;
    case SHOPEEPAY = 3;

    // Cards
    case CREDIT_CARD = 4;

    // Virtual Account
    case BCA_VA = 5;
    case BNI_VA = 6;
    case BRI_VA = 7;
    case MANDIRI_BILL = 8;
    case PERMATA_VA = 9;

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Cash',

            self::QRIS => 'QRIS',

            self::GOPAY => 'GoPay',
            self::SHOPEEPAY => 'ShopeePay',

            self::CREDIT_CARD => 'Credit Card',

            self::BCA_VA => 'BCA Virtual Account',
            self::BNI_VA => 'BNI Virtual Account',
            self::BRI_VA => 'BRI Virtual Account',
            self::MANDIRI_BILL => 'Mandiri Bill',
            self::PERMATA_VA => 'Permata Virtual Account',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::CASH => 'bg-label-success',

            self::QRIS => 'bg-label-info',

            self::GOPAY,
            self::SHOPEEPAY => 'bg-label-secondary',

            self::CREDIT_CARD => 'bg-label-danger',

            self::BCA_VA,
            self::BNI_VA,
            self::BRI_VA,
            self::MANDIRI_BILL,
            self::PERMATA_VA => 'bg-label-primary',
        };
    }
}