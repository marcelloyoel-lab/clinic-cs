<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $casts = [
        'gender' => Gender::class,
        'dob' => 'date',
    ];

    protected $guarded = ['id'];
    
    public function booking()
    {
        return $this->hasMany(Booking::class);
    }
}
