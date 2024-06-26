<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function adminHome()
    {
        $appointment = Appointment::with('patient.user', 'doctor.user','bookingTime')->get();
        $patient = Patient::with('user')->get();
        $doctor = Doctor::with('user','specialization')->get();
        
        // dd($appointment);
        return view('admin.home',compact('appointment','patient','doctor'),["msg"=>"Hello! I am admin"]);
    }
}
