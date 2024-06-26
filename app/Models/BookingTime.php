<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingTime extends Model
{
    use HasFactory;

    protected $fillable = ['AppointmentTime'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
