<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $guarded = ['id'];
    
    public function consultationPrescription()
    {
        return $this->hasMany(ConsultationPrescription::class);
    }

    public function invoiceItem(){
        return $this->hasMany(InvoiceItem::class);
    }
}
