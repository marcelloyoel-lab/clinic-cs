<?php

namespace App\Enums;

enum ConsultationStatus: int
{
    case DRAFT = 0;
    case ON_GOING = 1;
    case PAYMENT = 2;
    case FINISHED = 3;
    case CANCELLED = -1;
    
}