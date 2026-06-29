<?php

namespace App\Models;

use App\Enums\ConsultationStatus;
use App\Enums\Gender;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $casts = [
        'gender' => Gender::class,
        'dob' => 'date',
    ];

    protected $guarded = ['id'];
    
    public function booking()
    {
        return $this->hasMany(Booking::class);
    }

    public function consultation()
    {
        return $this->hasMany(Consultation::class);
    }

    public function getAgeAttribute()
    {
        return $this->dob
            ? Carbon::parse($this->dob)->age
            : null;
    }

    public function lastVisit()
    {
        return $this->hasOne(Consultation::class)
            ->where('status', ConsultationStatus::FINISHED)
            ->latestOfMany();
    }

    public function invoice(){
        return $this->hasMany(Invoice::class);
    }
}
