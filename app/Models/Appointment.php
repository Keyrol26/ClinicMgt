<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Yaml\Tests\YamlTest;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'AppointmentNumber',
        'AppointmentDate',
        'AppointmentTime_id',
        'name',
        'patient_id',
        'doctor_id',
        'Message',
        'ApplyDate',
        'ApplyDate',
        'Remark',
        'Status',
        'DocMsg'
    ];
    protected $dates = ['AppointmentDate'];
    protected $primary = ['id'];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function bookingTime()
    {
        return $this->belongsTo(BookingTime::class, 'AppointmentTime_id');
    }

}