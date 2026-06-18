<?php

namespace App\Enums;

enum BookingType: int
{
    case CONSULTATION = 0;
    case TREATMENT = 1;
    case REPURCHASING = 2;

    public function label(): string
    {
        return match ($this) {
            self::CONSULTATION => 'Consultation',
            self::TREATMENT => 'Treatment',
            self::REPURCHASING => 'Repurchasing',
        };
    }
}