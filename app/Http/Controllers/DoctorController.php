<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BookingTime;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function doctorHome()
    {
        $appointmentslist = Appointment::with('patient.user', 'doctor.user', 'bookingTime')
            ->join('booking_times', 'appointments.AppointmentTime_id', '=', 'booking_times.id')
            ->where('doctor_id', Auth::user()->doctor->id)
            ->orderByDesc('appointments.AppointmentDate')
            ->orderBy('booking_times.id')
            ->take(4)
            ->get();


        $appointmentsapproved = Appointment::where('doctor_id', Auth::user()->doctor->id)
            ->where('appointments.Status', "like", 'Approved')->count();

        $appointmentscancel = Appointment::where('doctor_id', Auth::user()->doctor->id)
            ->where('appointments.Status', "like", 'Cancelled')->count();

        $appointmentsnew = Appointment::where('doctor_id', Auth::user()->doctor->id)
            ->whereNull('appointments.Status')->count();

        $appointmentsall = Appointment::where('doctor_id', Auth::user()->doctor->id)->count();


        // dd($appointmentsapproved,$appointmentsall,$appointmentscancel);
        return view('doctor.index', compact('appointmentslist', 'appointmentsall', 'appointmentsapproved', 'appointmentscancel', 'appointmentsnew'));
    }

    public function show($id, $aptnum)
    {
        $appointment = Appointment::with('patient.user', 'doctor.user')->where('appointments.id', $id)->where('AppointmentNumber', $aptnum)->first();
        // dd($appointment);
        return view('doctor.appointmentdetail', compact('appointment'));
    }
    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        $appointment->Remark = $request->Remark;
        $appointment->Status = $request->Status;
        $appointment->update();

        // Alert::success('Berhasil', 'Remark and status has been updated');
        return to_route('doctor.home');
    }

    public function newapptdoc()
    {
        $Specializations = Specialization::all();
        $bookingtime = BookingTime::all();
        $appointment = Appointment::with('patient', 'doctor', 'bookingTime')
            ->where('doctor_id', Auth::user()->doctor->id)
            ->whereNull('appointments.Status')
            // ->join('booking_times', 'appointments.AppointmentTime_id', '=', 'booking_times.id')
            ->orderBy('appointments.AppointmentDate')
            ->orderBy('AppointmentTime_id')
            ->paginate(10);


        // dd($appointment);
        return view('doctor.newappt', compact('Specializations', 'appointment', 'bookingtime'));
    }
}
