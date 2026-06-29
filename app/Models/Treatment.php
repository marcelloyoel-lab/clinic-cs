<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $guarded = ['id'];
    
    public function consultationTreatment()
    {
        return $this->hasMany(ConsultationTreatment::class);
    }

    public function invoiceItem(){
        return $this->hasMany(InvoiceItem::class);
    }
}
