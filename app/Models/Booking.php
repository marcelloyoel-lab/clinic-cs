<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $casts = [
        'type' => BookingType::class,
        'status' => BookingStatus::class,
        'date' => 'date',
    ];

    protected $guarded = ['id'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

}
