<?php

namespace App\Models;

use App\Enums\ConsultationStatus;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $casts = [
        'status' => ConsultationStatus::class,
    ];

    protected $guarded = ['id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function consultationPrescription()
    {
        return $this->hasMany(ConsultationPrescription::class);
    }

    public function consultationDiagnose()
    {
        return $this->hasMany(ConsultationDiagnose::class);
    }

    public function consultationTreatment()
    {
        return $this->hasMany(ConsultationTreatment::class);
    }

    public function doctor(){
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
