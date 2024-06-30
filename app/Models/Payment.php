<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'stripe_payment_id',
        'appt_id',
        'amount',
        'patient_id',
    ];

    public function appt()
    {
        return $this->belongsTo(Appointment::class, 'appt_id');
    }

}
