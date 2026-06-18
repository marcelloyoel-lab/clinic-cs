<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationDiagnose extends Model
{
    protected $guarded = ['id'];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
