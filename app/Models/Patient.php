<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    public function booking()
    {
        return $this->hasMany(Booking::class);
    }
}
