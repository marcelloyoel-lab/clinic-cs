<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationTreatment extends Model
{
    protected $guarded = ['id'];
    
    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
