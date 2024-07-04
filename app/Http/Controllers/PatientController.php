<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Charge;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BookingTime;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Specialization;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\TomorrowOrLater;
use App\Rules\DifferentEmail;
use Carbon\Carbon;
use Hash;

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
        // Validate the request
        $request->validate([
            'Name' => 'required|max:255',
            'AppointmentDate' => 'required|date',
            new TomorrowOrLater,
            'AppointmentTime' => 'required',
            'Doctor' => 'required',
            'Message' => 'max:255',
            'stripeToken' => 'required'
        ]);

        // Set Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Create the charge on Stripe's servers
            $charge = Charge::create([
                'amount' => 5000, // Amount in cents
                'currency' => 'MYR',
                'source' => $request->stripeToken,
                'description' => 'Booking Appointment payment for ' . $request->Name .','. Auth::user()->patient->id . "," . Auth::user()->email,
                // 'customer' => $request->Name .','. Auth::user()->patient->id . "," . Auth::user()->email,
            ]);

            // Create appointment record in the database
            $appt = Appointment::create([
                'AppointmentNumber' => random_int(10000, 99999),
                'name' => $request->Name,
                'patient_id' => Auth::user()->patient->id,
                'AppointmentDate' => $request->AppointmentDate,
                'AppointmentTime_id' => $request->AppointmentTime,
                'doctor_id' => $request->Doctor,
                'Message' => $request->Message,
                'ApplyDate' => now(),
            ]);

            // Create payment record in the database
            Payment::create([
                'appt_id' => $appt->id,
                'patient_id' => Auth::user()->patient->id,
                'stripe_payment_id' => $charge->id,
                'amount' => 50.00
            ]);

            return redirect()->route('statuslist')->withSuccess('Thank You', 'Your Appointment Request Has Been Sent. We Will Contact You Soon');
        } catch (\Exception $ex) {
            return back()->withErrors(['error' => $ex->getMessage()]);
        }
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
        $request->validate([
            'Name' => ['max:255'],
            'phoneno' => ['max:10'],
            'address' => ['max:255'],
            'dob' => ['before:tomorrow'],
        ]);

        $user->update([
            'name' => $request->Name,
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

    public function usersecurity()
    {
        $id = Auth::user()->patient->id;
        $profile = Patient::with('user')
            ->where('user_id', $id)
            ->get();

        return view('user.security', compact('profile'));
    }

    public function userpassupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->password),
            'updated_at' => now(),
        ]);
        return to_route('profile');
    }

    public function useremailupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'email' => ['string', 'email', 'max:255', new DifferentEmail($user->email), 'unique:users'],
        ]);
        $user->update([
            'email' => $request->email,
            'updated_at' => now(),
        ]);
        return to_route('profile');
    }
    public function statuslist()
    {
        return view('user.appt-status-list');
    }
    public function searchstatuslist(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            if ($query != '') {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
                    ->where('patient_id', Auth::user()->patient->id)
                    ->whereNull('appointments.Status')
                    ->when($query, function ($query, $search) {
                        return $query->where(function ($query) use ($search) {
                            $query
                                ->orWhereHas('bookingTime', function ($query) use ($search) {
                                    $query->where('AppointmentTime', 'like', "%{$search}%");
                                })
                                ->orWhere('appointments.AppointmentDate', 'like', "%{$search}%")
                                ->orWhere('appointments.AppointmentNumber', 'like', "%{$search}%")
                                ->orWhere('appointments.name', 'like', "%{$search}%");
                        });
                    })
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            } else {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
                    ->where('patient_id', Auth::user()->patient->id)
                    ->whereNull('appointments.Status')
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            }

            $output = '';
            $rowCount = ($data->currentPage() - 1) * $data->perPage() + 1;

            if ($data->isNotEmpty()) {
                foreach ($data as $row) {
                    $output .= '
                <tr>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $rowCount . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->AppointmentNumber . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->name . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . date('d-m-Y', strtotime($row->AppointmentDate)) . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->bookingTime->AppointmentTime . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->doctor->user->name . '</a></td>
                    <td>' . (
                        $row->Status == 'Approved' ? '<span class="badge badge-light-success fs-8 fw-bolder my-2">Approved</span>' :
                        ($row->Status == 'Cancelled' ? '<span class="badge badge-light-danger fs-8 fw-bolder my-2">Rejected</span>' :
                            ($row->Status == 'Rescheduled' ? '<span class="badge badge-danger fs-8 fw-bolder my-2">Rescheduled</span>' :
                                '<span class="badge badge-light-warning fs-8 fw-bolder my-2">In Progress</span>')
                        )
                    ) . '</td><td> 
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="' . route('userdetailAppointment.show', [$row->id, $row->AppointmentNumber]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="black" />
                                        <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="black" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </td>
                </tr>';
                    $rowCount++;
                }
            } else {
                $output = '
            <tr>
                <td align="center" colspan="7">No Data Found</td>
            </tr>';
            }

            return response()->json(['table_data' => $output, 'pagination' => (string) $data->links('vendor.pagination.bootstrap-4')]);
        }
    }

    public function historylist()
    {
        return view('user.appt-history-list');
    }
    public function searchhistorylist(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            if ($query != '') {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
                    ->where('patient_id', Auth::user()->patient->id)
                    ->whereNotNull('appointments.Status')
                    ->when($query, function ($query, $search) {
                        return $query->where(function ($query) use ($search) {
                            $query
                                ->orWhereHas('bookingTime', function ($query) use ($search) {
                                    $query->where('AppointmentTime', 'like', "%{$search}%");
                                })
                                ->orWhere('appointments.AppointmentDate', 'like', "%{$search}%")
                                ->orWhere('appointments.AppointmentNumber', 'like', "%{$search}%")
                                ->orWhere('appointments.name', 'like', "%{$search}%");
                        });
                    })
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            } else {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
                    ->where('patient_id', Auth::user()->patient->id)
                    ->whereNotNull('appointments.Status')
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            }

            $output = '';
            $rowCount = ($data->currentPage() - 1) * $data->perPage() + 1;

            if ($data->isNotEmpty()) {
                foreach ($data as $row) {
                    $output .= '
                <tr>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $rowCount . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->AppointmentNumber . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->name . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . date('d-m-Y', strtotime($row->AppointmentDate)) . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->bookingTime->AppointmentTime . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->doctor->user->name . '</a></td>
                    <td>' . (
                        $row->Status == 'Approved' ? '<span class="badge badge-light-success fs-8 fw-bolder my-2">Approved</span>' :
                        ($row->Status == 'Cancelled' ? '<span class="badge badge-light-danger fs-8 fw-bolder my-2">Rejected</span>' :
                            ($row->Status == 'Rescheduled' ? '<span class="badge badge-danger fs-8 fw-bolder my-2">Rescheduled</span>' :
                                '<span class="badge badge-light-warning fs-8 fw-bolder my-2">In Progress</span>')
                        )
                    ) . '</td><td> 
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="' . route('userdetailAppointment.show', [$row->id, $row->AppointmentNumber]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="black" />
                                        <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="black" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </td>
                </tr>';
                    $rowCount++;
                }
            } else {
                $output = '
            <tr>
                <td align="center" colspan="7">No Data Found</td>
            </tr>';
            }

            return response()->json(['table_data' => $output, 'pagination' => (string) $data->links('vendor.pagination.bootstrap-4')]);
        }
    }

    public function rescheduledlist()
    {
        return view('user.appt-res-list');
    }

    public function searchrescheduledlist(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            if ($query != '') {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
                    ->where('patient_id', Auth::user()->patient->id)
                    ->where('Status', "like", 'Rescheduled')
                    ->when($query, function ($query, $search) {
                        return $query->where(function ($query) use ($search) {
                            $query
                                ->orWhereHas('bookingTime', function ($query) use ($search) {
                                    $query->where('AppointmentTime', 'like', "%{$search}%");
                                })
                                ->orWhere('appointments.AppointmentDate', 'like', "%{$search}%")
                                ->orWhere('appointments.AppointmentNumber', 'like', "%{$search}%")
                                ->orWhere('appointments.name', 'like', "%{$search}%");
                        });
                    })
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            } else {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
                    ->where('patient_id', Auth::user()->patient->id)
                    ->where('Status', "like", 'Rescheduled')
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            }

            $output = '';
            $rowCount = ($data->currentPage() - 1) * $data->perPage() + 1;

            if ($data->isNotEmpty()) {
                foreach ($data as $row) {
                    $output .= '
                <tr>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $rowCount . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->AppointmentNumber . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->name . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . date('d-m-Y', strtotime($row->AppointmentDate)) . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->bookingTime->AppointmentTime . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->doctor->user->name . '</a></td>
                    <td>' . (
                        $row->Status == 'Approved' ? '<span class="badge badge-light-success fs-8 fw-bolder my-2">Approved</span>' :
                        ($row->Status == 'Cancelled' ? '<span class="badge badge-light-danger fs-8 fw-bolder my-2">Rejected</span>' :
                            ($row->Status == 'Rescheduled' ? '<span class="badge badge-danger fs-8 fw-bolder my-2">Rescheduled</span>' :
                                '<span class="badge badge-light-warning fs-8 fw-bolder my-2">In Progress</span>')
                        )
                    ) . '</td><td> 
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="' . route('userdetailAppointment.show', [$row->id, $row->AppointmentNumber]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="black" />
                                        <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="black" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </td>
                </tr>';
                    $rowCount++;
                }
            } else {
                $output = '
            <tr>
                <td align="center" colspan="7">No Data Found</td>
            </tr>';
            }

            return response()->json(['table_data' => $output, 'pagination' => (string) $data->links('vendor.pagination.bootstrap-4')]);
        }
    }
    public function appointmentshow($id, $aptnum)
    {
        $appointment = Appointment::with('patient.user', 'doctor.user')->where('appointments.id', $id)->where('AppointmentNumber', $aptnum)->first();
        $bookingtime = BookingTime::all();
        // return view('doctor.appointmentdetail', compact('appointment'));
        return view('user.apptdetails', compact('appointment', 'bookingtime'));
    }
    public function appointmentupdate(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        $appointment->AppointmentDate = $request->AppointmentDate;
        $appointment->AppointmentTime_id = $request->AppointmentTime;
        $appointment->updated_at = now();
        $appointment->update();

        // Alert::success('Berhasil', 'Remark and status has been updated');
        return back();
    }
}
