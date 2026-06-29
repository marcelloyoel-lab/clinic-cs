<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentSession extends Model
{
    protected $guarded = ['id'];

    public function invoiceItem(){
        return $this->belongsTo(InvoiceItem::class);
    }

    public function doctor(){
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
