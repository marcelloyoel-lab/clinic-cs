<?php

namespace App\Enums;

enum ConsultationStatus: int
{
    case DRAFT = 0;
    case ON_GOING = 1;
    case PAYMENT = 2;
    case FINISHED = 3;
    case CANCELLED = -1;
    
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::ON_GOING => 'On Going',
            self::PAYMENT => 'Waiting Payment',
            self::FINISHED => 'Finished',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-label-warning',
            self::ON_GOING => 'bg-label-primary',
            self::PAYMENT => 'bg-label-info',
            self::FINISHED => 'bg-label-success',
            self::CANCELLED => 'bg-label-danger',
        };
    }
}