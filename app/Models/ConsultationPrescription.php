<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationPrescription extends Model
{
    protected $guarded = ['id'];
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
