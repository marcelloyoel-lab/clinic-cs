<?php

namespace App\Enums;

enum BookingStatus: int
{
    case BOOKED = 0;
    case IN_PROGRESS = 1;
    case FINISHED = 2;
    case CANCELLED = -1;

    public function label(): string
    {
        return match ($this) {
            self::BOOKED => 'Booked',
            self::IN_PROGRESS => 'In Progress',
            self::FINISHED => 'Finished',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::BOOKED => 'bg-label-primary',
            self::IN_PROGRESS => 'bg-label-warning',
            self::FINISHED => 'bg-label-success',
            self::CANCELLED => 'bg-label-danger',
        };
    }
}