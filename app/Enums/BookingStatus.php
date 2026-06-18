<?php

namespace App\Enums;

enum BookingStatus: int
{
    case BOOKED = 0;
    case IN_PROGRESS = 1;
    case FINISHED = 2;
}