<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    public function consultationTreatment()
    {
        return $this->hasMany(ConsultationTreatment::class);
    }
}
