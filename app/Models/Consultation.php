<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
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
