<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BookingTime;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\TomorrowOrLater;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function userindex()
    {
        $appointmentlist = Appointment::with('patient', 'doctor', 'bookingTime')
            ->where('patient_id', Auth::user()->patient->id)
            // ->whereNotNull('appointments.Status')
            // ->join('booking_times', 'appointments.AppointmentTime_id', '=', 'booking_times.id')
            ->orderBy('appointments.AppointmentDate')
            ->orderBy('AppointmentTime_id')
            ->take(3)
            ->get();

        $appointmentstatus = Appointment::with('patient', 'doctor', 'bookingTime')
            ->where('patient_id', Auth::user()->patient->id)
            ->whereNull('appointments.Status')
            // ->join('booking_times', 'appointments.AppointmentTime_id', '=', 'booking_times.id')
            ->orderBy('appointments.AppointmentDate')
            ->orderBy('AppointmentTime_id')
            ->take(3)
            ->get();

        $appointmentschedule = Appointment::with('patient', 'doctor', 'bookingTime')
            ->where('patient_id', Auth::user()->patient->id)
            ->where('appointments.Status', "like", 'Approved')
            // ->join('booking_times', 'appointments.AppointmentTime_id', '=', 'booking_times.id')
            ->orderBy('appointments.AppointmentDate')
            ->orderBy('AppointmentTime_id')
            ->limit(2)
            ->get();

        $doc1 = Doctor::with('user', 'specialization')
            ->orderBy('id')
            ->limit(1)
            ->get();

        $doc2 = Doctor::with('user', 'specialization')
            ->orderByDesc('id')
            ->limit(1)
            ->get();

        // dd($doc1);
        return view('user.index', compact('appointmentlist', 'appointmentstatus', 'appointmentschedule', 'doc1', 'doc2'));
    }

    public function statuslist()
    {
        $Specializations = Specialization::all();
        $bookingtime = BookingTime::all();
        $appointment = Appointment::with('patient', 'doctor', 'bookingTime')
            ->where('patient_id', Auth::user()->patient->id)
            ->whereNull('appointments.Status')
            // ->join('booking_times', 'appointments.AppointmentTime_id', '=', 'booking_times.id')
            ->orderBy('appointments.AppointmentDate')
            ->orderBy('AppointmentTime_id')
            ->paginate(10);



        // dd($appointment);
        return view('user.appt-status-list', compact('Specializations', 'appointment', 'bookingtime'));
    }

    public function historylist()
    {
        $Specializations = Specialization::all();
        $bookingtime = BookingTime::all();
        $appointment = Appointment::with('patient', 'doctor', 'bookingTime')
            ->where('patient_id', Auth::user()->patient->id)
            ->whereNotNull('appointments.Status')
            // ->join('booking_times', 'appointments.AppointmentTime_id', '=', 'booking_times.id')
            ->orderBy('appointments.AppointmentDate')
            ->orderBy('AppointmentTime_id')
            ->paginate(10);


        // dd($appointment);
        return view('user.appt-history-list', compact('Specializations', 'appointment', 'bookingtime'));
    }

    public function bookingpage()
    {

        $Specializations = Specialization::all();
        $bookingtime = BookingTime::all();
        $appointment = Appointment::with('patient', 'doctor', 'bookingTime')->where('patient_id', Auth::user()->patient->id)->get();

        $schedule = Appointment::with('bookingTime')
            ->where('AppointmentDate', Carbon::tomorrow()->toDateString())
            ->select('AppointmentTime_id')
            ->get();

        $appointmentTimeIds = $schedule->pluck('AppointmentTime_id')->toArray();

        $allIds = range(0, 14); // the complete range of IDs from 1 to 10

        $missingIds = array_diff($allIds, $appointmentTimeIds);

        // dd($missingIds); 
        return view('user.booking-apt', compact('Specializations', 'appointment', 'bookingtime'));
    }

    public function get_doctor(Request $request)
    {
        $id_special = $request->id_special;
        $doctors = Doctor::with('user')->where('specialization_id', $id_special)->get();

        $opt = "<option value=''>Select Doctor</option>";
        foreach ($doctors as $doctor) {
            $doctorname = $doctor->user->name;
            $opt .= "<option value='$doctor->id'>$doctorname</option>";
        }

        echo $opt;
    }

    public function store(Request $request)
    {

        // dd( $request->all());
        $request->validate([
            'Name' => 'required|max:255',
            'AppointmentDate' => ['required', 'date', new TomorrowOrLater],
        ]);
        Appointment::create([
            'AppointmentNumber' => random_int(10000, 99999),
            'name' => $request->Name,
            'patient_id' => Auth::user()->patient->id,
            'AppointmentDate' => $request->AppointmentDate,
            'AppointmentTime_id' => $request->AppointmentTime,
            'doctor_id' => $request->Doctor,
            'Message' => $request->Message,
            'ApplyDate' => now(),
        ]);

        // return back()->withSuccess('Thank You', 'Your Appointment Request Has Been Send. We Will Contact You Soon');
        return redirect()->route('statuslist');
    }

    public function profile()
    {
        $id = Auth::user()->patient->id;


        $profile = Patient::with('user')
            ->where('user_id', $id)
            ->get();
        // dd($profile);
        // dd($appointment);
        return view('user.profile', compact('profile'));
    }

    public function usersetting()
    {
        $id = Auth::user()->patient->id;
        $profile = Patient::with('user')
            ->where('user_id', $id)
            ->get();
        return view('user.profile-setting', compact('profile'));
    }

    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        // dd($request->all());
        // $request->validate([
        //     'Name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     // 'phoneno' => ['required', 'int', 'max:10'],
        //     // 'gender' => ['required'],
        //     // 'address' => ['required', 'string', 'max:255'],
        // ]);

        $user->update([
            'name' => $request->Name,
            'email' => $request->email,
            'updated_at' => now(),
        ]);

        // dd($user->name);


        $user->patient->update([
            'phoneno' => $request->phoneno,
            'gender' => $request->gender,
            'address' => $request->address,
            'dob' => $request->dob,
            'updated_at' => now(),
        ]);

        return to_route('profile');
    }

}
