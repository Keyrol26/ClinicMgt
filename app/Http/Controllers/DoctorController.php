<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Specialization;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Hash;
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
        // return view('doctor.appointmentdetail', compact('appointment'));
        return view('doctor.apptdetails', compact('appointment'));
    }
    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        $appointment->Remark = $request->Remark;
        $appointment->Status = $request->Status;
        $appointment->updated_at = now();
        $appointment->update();

        // Alert::success('Berhasil', 'Remark and status has been updated');
        return back();
    }
    public function reportupdate(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        $appointment->DocMsg = $request->docmsg;
        $appointment->updated_at = now();
        $appointment->update();

        // Alert::success('Berhasil', 'Remark and status has been updated');
        return back();
    }


    //New Appt
    public function newapptdoc()
    {
        return view('doctor.newappt');
    }

    public function searchnewapptdoc(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            if ($query != '') {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
                    ->where('doctor_id', Auth::user()->doctor->id)
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
                    ->where('doctor_id', Auth::user()->doctor->id)
                    ->whereNull('appointments.Status')
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            }

            $total_row = $data->count();
            $rowCount = 1;

            $output = '';
            if ($total_row > 0) {
                foreach ($data as $row) {
                    $output .= '
                <tr>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $rowCount . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->AppointmentNumber . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->name . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . date('d-m-Y', strtotime($row->AppointmentDate)) . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->bookingTime->AppointmentTime . '</a></td>
                    <td>' . ($row->Status == 'Approved' ? '<span class="badge badge-light-success fs-8 fw-bolder my-2">Approved</span>' : ($row->Status == 'Cancelled' ? '<span class="badge badge-light-danger fs-8 fw-bolder my-2">Rejected</span>' : '<span class="badge badge-light-warning fs-8 fw-bolder my-2">In Progress</span>')) . '</td>
                    <td>
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="' . route('detailAppointment.show', [$row->id, $row->AppointmentNumber]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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

    //Approved Appt
    public function approvedapptdoc()
    {
        return view('doctor.approveappt');
    }
    public function searchapprovedapptdoc(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            if ($query != '') {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
                    ->where('doctor_id', Auth::user()->doctor->id)
                    ->where('Status', "like", 'Approved')
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
                    ->where('doctor_id', Auth::user()->doctor->id)
                    ->where('Status', "like", 'Approved')
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            }

            $total_row = $data->count();
            $rowCount = 1;

            $output = '';
            if ($total_row > 0) {
                foreach ($data as $row) {
                    $output .= '
                <tr>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $rowCount . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->AppointmentNumber . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->name . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . date('d-m-Y', strtotime($row->AppointmentDate)) . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->bookingTime->AppointmentTime . '</a></td>
                    <td>' . ($row->Status == 'Approved' ? '<span class="badge badge-light-success fs-8 fw-bolder my-2">Approved</span>' : ($row->Status == 'Cancelled' ? '<span class="badge badge-light-danger fs-8 fw-bolder my-2">Rejected</span>' : '<span class="badge badge-light-warning fs-8 fw-bolder my-2">In Progress</span>')) . '</td>
                    <td>
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="' . route('detailAppointment.show', [$row->id, $row->AppointmentNumber]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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

    //Cancelled Appt
    public function cancelapptdoc()
    {
        return view('doctor.cancelappt');
    }
    public function searchcancelapptdoc(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            if ($query != '') {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
                    ->where('doctor_id', Auth::user()->doctor->id)
                    ->where('Status', "like", 'Cancelled')
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
                    ->where('doctor_id', Auth::user()->doctor->id)
                    ->where('Status', "like", 'Cancelled')
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            }

            $total_row = $data->count();
            $rowCount = 1;

            $output = '';
            if ($total_row > 0) {
                foreach ($data as $row) {
                    $output .= '
                <tr>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $rowCount . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->AppointmentNumber . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->name . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . date('d-m-Y', strtotime($row->AppointmentDate)) . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->bookingTime->AppointmentTime . '</a></td>
                    <td>' . ($row->Status == 'Approved' ? '<span class="badge badge-light-success fs-8 fw-bolder my-2">Approved</span>' : ($row->Status == 'Cancelled' ? '<span class="badge badge-light-danger fs-8 fw-bolder my-2">Rejected</span>' : '<span class="badge badge-light-warning fs-8 fw-bolder my-2">In Progress</span>')) . '</td>
                    <td>
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="' . route('detailAppointment.show', [$row->id, $row->AppointmentNumber]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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

    //All Appt
    public function allapptdoc()
    {
        return view('doctor.allappt');
    }
    public function searchallapptdoc(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            if ($query != '') {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
                    ->where('doctor_id', Auth::user()->doctor->id)
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
                    ->where('doctor_id', Auth::user()->doctor->id)
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            }

            $total_row = $data->count();
            $rowCount = 1;

            $output = '';
            if ($total_row > 0) {
                foreach ($data as $row) {
                    $output .= '
                <tr>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $rowCount . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->AppointmentNumber . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->name . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . date('d-m-Y', strtotime($row->AppointmentDate)) . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->bookingTime->AppointmentTime . '</a></td>
                    <td>' . ($row->Status == 'Approved' ? '<span class="badge badge-light-success fs-8 fw-bolder my-2">Approved</span>' : ($row->Status == 'Cancelled' ? '<span class="badge badge-light-danger fs-8 fw-bolder my-2">Rejected</span>' : '<span class="badge badge-light-warning fs-8 fw-bolder my-2">In Progress</span>')) . '</td>
                    <td>
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="' . route('detailAppointment.show', [$row->id, $row->AppointmentNumber]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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

    //All Appt
    public function patientlistdoc()
    {
        return view('doctor.patientlist');
    }

    public function searchpatientlistdoc(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->get('query'); // Rename $query to $search

            // Fetch appointments with relationships
            $queryBuilder = Appointment::with(['patient', 'doctor', 'bookingTime'])
                ->where('doctor_id', Auth::user()->doctor->id);

            // Apply search query if provided
            if ($search != '') {
                $queryBuilder->where(function ($query) use ($search) {
                    $query
                        ->orWhereHas('bookingTime', function ($query) use ($search) {
                            $query->where('AppointmentTime', 'like', "%{$search}%");
                        })
                        ->orWhere('appointments.AppointmentDate', 'like', "%{$search}%")
                        ->orWhere('appointments.AppointmentNumber', 'like', "%{$search}%")
                        ->orWhere('appointments.name', 'like', "%{$search}%");
                });
            }

            // Get the data without grouping
            $data = $queryBuilder->get();

            // Group by patient name
            $groupedData = $data->groupBy('name');

            // Select only one record from each group (e.g., the first one)
            $selectedData = new Collection();
            foreach ($groupedData as $group) {
                $selectedData->push($group->first());
            }

            // Paginate the selected data
            $page = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10;
            $sliced = $selectedData->slice(($page - 1) * $perPage, $perPage)->values();
            $paginatedAppointments = new LengthAwarePaginator($sliced, $selectedData->count(), $perPage, $page, [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
            ]);

            // Prepare the output for JSON response
            $output = '';
            $rowCount = 1;
            foreach ($paginatedAppointments as $row) {
                $output .= '
                <tr>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $rowCount . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->name . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->patient->user->email . '</a></td>
                    <td>
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="' . route('detailAppointment.show', [$row->id, $row->AppointmentNumber]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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

            // If no data found, display a message
            if ($paginatedAppointments->isEmpty()) {
                $output = '
                <tr>
                    <td align="center" colspan="7">No Data Found</td>
                </tr>';
            }

            // Return JSON response with table data and pagination links
            return response()->json([
                'table_data' => $output,
                'pagination' => (string) $paginatedAppointments->links('vendor.pagination.bootstrap-4'),
            ]);
        }
    }


    //Profile
    public function profile()
    {
        $id = Auth::user()->doctor->id;
        $profile = Doctor::with('user')
            ->where('user_id', $id)
            ->get();
        return view('doctor.profile.overview', compact('profile'));
    }

    public function docsetting()
    {
        $id = Auth::user()->doctor->id;
        $profile = Doctor::with('user')
            ->where('user_id', $id)
            ->get();
        $Specializations = Specialization::all();

        return view('doctor.profile.setting', compact('profile', 'Specializations'));
    }
    public function docupdate(Request $request, $id)
    {

        $user = User::findOrFail($id);
        // dd($user->doctor);

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

        $user->doctor->update([
            'phoneno' => $request->phoneno,
            'gender' => $request->gender,
            'address' => $request->address,
            'dob' => $request->dob,
            'updated_at' => now(),
        ]);

        return to_route('docprofile');
    }

    public function docpass()
    {
        $id = Auth::user()->doctor->id;
        $profile = Doctor::with('user')
            ->where('user_id', $id)
            ->get();

        return view('doctor.profile.security', compact('profile'));
    }

    public function docpassupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->password),
            'updated_at' => now(),
        ]);
        return to_route('docprofile');
    }
}