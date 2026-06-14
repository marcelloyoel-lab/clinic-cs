<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    public function consultationPrescription()
    {
        return $this->hasMany(ConsultationPrescription::class);
    }
}
