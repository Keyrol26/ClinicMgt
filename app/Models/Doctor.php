<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        "specialization_id",
        'gender',
        'address',
        'phoneno',
        'dob',
        'qualification'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }

    public function appointment()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }
}
