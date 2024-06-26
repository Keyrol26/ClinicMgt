<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ['user_id',"specialization_id"];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function specialization()
    {
        return $this->hasOne(Specialization::class, 'id');
    }

    public function appointment()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }
}
