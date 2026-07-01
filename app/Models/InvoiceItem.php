<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $guarded = ['id'];

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }

    public function medicine(){
        return $this->belongsTo(Medicine::class);
    }

    public function treatment(){
        return $this->belongsTo(Treatment::class);
    }

    public function treatmentSession(){
        return $this->hasMany(TreatmentSession::class);
    }
}
