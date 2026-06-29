<?php

namespace App\Enums;

enum InvoiceItemType: int
{
    case CONSULTATION = 0;
    case MEDICINE = 1;
    case TREATMENT = 2;

    public function label(): string
    {
        return match ($this) {
            self::CONSULTATION => 'Consultation',
            self::MEDICINE => 'Medicine',
            self::TREATMENT => 'Treatment',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::CONSULTATION => 'bg-label-primary',
            self::MEDICINE => 'bg-label-success',
            self::TREATMENT => 'bg-label-info',
        };
    }
}