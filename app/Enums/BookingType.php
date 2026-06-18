<?php

namespace App\Enums;

enum BookingType: int
{
    case CONSULTATION = 0;
    case TREATMENT = 1;
    case REPURCHASING = 2;
}