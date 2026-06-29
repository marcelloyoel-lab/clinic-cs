<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = ['id'];

    public function booking(){
        return $this->belongsTo(Booking::class);
    }

    public function patient(){
        return $this->belongsTo(Patient::class);
    }

    public function cashier(){
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function invoiceItem(){
        return $this->hasMany(InvoiceItem::class);
    }

    public function invoicePayment(){
        return $this->hasMany(InvoicePayment::class);
    }
}
