<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use App\Models\Specialization;
use App\Rules\DifferentEmail;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $totalapproved = Appointment::with('patient', 'doctor', 'bookingTime')
            ->where('Status', "like", 'Approved')
            ->count();

        $totalcancel = Appointment::with('patient', 'doctor', 'bookingTime')
            ->where('Status', "like", 'Cancelled')
            ->count();

        $totaldoctor = Doctor::count();
        $totalpatient = Patient::count();

        $listdoctor = Doctor::with('user', 'specialization')
            // ->whereNotnull('specialization_id')
            ->inRandomOrder()
            ->take(6)
            ->get();

        $totalamount = Payment::sum('amount');

        // Get the current year
        $currentYear = Carbon::now()->year;

        // Array to store monthly appointment counts
        $monthlyCounts = [];

        // Get the count of appointments for each month
        for ($month = 1; $month <= 12; $month++) {
            $monthlyCounts[Carbon::create()->month($month)->format('M')] = Appointment::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->count();
        }

        // dd($monthlyCounts);
        return view('admin.index', compact('totalapproved', 'totalcancel', 'totaldoctor', 'totalpatient', 'listdoctor', 'totalamount', 'monthlyCounts'));
    }


    public function show($id, $aptnum)
    {
        $appointment = Appointment::with('patient.user', 'doctor.user')->where('appointments.id', $id)->where('AppointmentNumber', $aptnum)->first();
        // dd($appointment);
        // return view('doctor.appointmentdetail', compact('appointment'));
        return view('admin.apptdetails', compact('appointment'));
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
        return view('admin.newappt');
    }

    public function searchnewapptdoc(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            if ($query != '') {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
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
                    ->whereNull('appointments.Status')
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            }

            $total_row = $data->total();
            $current_page = $data->currentPage();
            $per_page = $data->perPage();

            $output = '';
            if ($total_row > 0) {
                foreach ($data as $index => $row) {
                    $rowCount = ($current_page - 1) * $per_page + $index + 1;
                    $output .= '
                <tr>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $rowCount . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->AppointmentNumber . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->name . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . date('d-m-Y', strtotime($row->AppointmentDate)) . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->bookingTime->AppointmentTime . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->doctor->user->name . '</a></td>
                    <td>' . ($row->Status == 'Approved' ? '<span class="badge badge-light-success fs-8 fw-bolder my-2">Approved</span>' : ($row->Status == 'Cancelled' ? '<span class="badge badge-light-danger fs-8 fw-bolder my-2">Rejected</span>' : '<span class="badge badge-light-warning fs-8 fw-bolder my-2">In Progress</span>')) . '</td>
                    <td>
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="' . route('admindetailAppointment.show', [$row->id, $row->AppointmentNumber]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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
        return view('admin.approveappt');
    }
    public function searchapprovedapptdoc(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            if ($query != '') {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
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
                    ->where('Status', "like", 'Approved')
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            }

            $total_row = $data->total();
            $current_page = $data->currentPage();
            $per_page = $data->perPage();

            $output = '';
            if ($total_row > 0) {
                foreach ($data as $index => $row) {
                    $rowCount = ($current_page - 1) * $per_page + $index + 1;
                    $output .= '
                <tr>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $rowCount . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->AppointmentNumber . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->name . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . date('d-m-Y', strtotime($row->AppointmentDate)) . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->bookingTime->AppointmentTime . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->doctor->user->name . '</a></td>
                    <td>' . ($row->Status == 'Approved' ? '<span class="badge badge-light-success fs-8 fw-bolder my-2">Approved</span>' : ($row->Status == 'Cancelled' ? '<span class="badge badge-light-danger fs-8 fw-bolder my-2">Rejected</span>' : '<span class="badge badge-light-warning fs-8 fw-bolder my-2">In Progress</span>')) . '</td>
                    <td>
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="' . route('admindetailAppointment.show', [$row->id, $row->AppointmentNumber]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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

    //Rescheduled Appt
    public function resdapptdoc()
    {
        return view('admin.resappt');
    }
    public function searchresdapptdoc(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            if ($query != '') {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
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
                    ->where('Status', "like", 'Rescheduled')
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            }

            $total_row = $data->total();
            $current_page = $data->currentPage();
            $per_page = $data->perPage();

            $output = '';
            if ($total_row > 0) {
                foreach ($data as $index => $row) {
                    $rowCount = ($current_page - 1) * $per_page + $index + 1;
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
                            <a href="' . route('admindetailAppointment.show', [$row->id, $row->AppointmentNumber]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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
        return view('admin.cancelappt');
    }
    public function searchcancelapptdoc(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            if ($query != '') {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
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
                    ->where('Status', "like", 'Cancelled')
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            }

            $total_row = $data->total();
            $current_page = $data->currentPage();
            $per_page = $data->perPage();

            $output = '';
            if ($total_row > 0) {
                foreach ($data as $index => $row) {
                    $rowCount = ($current_page - 1) * $per_page + $index + 1;
                    $output .= '
                <tr>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $rowCount . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->AppointmentNumber . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->name . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . date('d-m-Y', strtotime($row->AppointmentDate)) . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->bookingTime->AppointmentTime . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->doctor->user->name . '</a></td>
                    <td>' . ($row->Status == 'Approved' ? '<span class="badge badge-light-success fs-8 fw-bolder my-2">Approved</span>' : ($row->Status == 'Cancelled' ? '<span class="badge badge-light-danger fs-8 fw-bolder my-2">Rejected</span>' : '<span class="badge badge-light-warning fs-8 fw-bolder my-2">In Progress</span>')) . '</td>
                    <td>
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="' . route('admindetailAppointment.show', [$row->id, $row->AppointmentNumber]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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
        return view('admin.allappt');
    }
    public function searchallapptdoc(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            if ($query != '') {
                $data = Appointment::with('patient', 'doctor', 'bookingTime')
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
                    ->orderBy('appointments.AppointmentDate')
                    ->orderBy('AppointmentTime_id')
                    ->paginate(10);
            }

            $total_row = $data->total();
            $current_page = $data->currentPage();
            $per_page = $data->perPage();

            $output = '';
            if ($total_row > 0) {
                foreach ($data as $index => $row) {
                    $rowCount = ($current_page - 1) * $per_page + $index + 1;
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
                            <a href="' . route('admindetailAppointment.show', [$row->id, $row->AppointmentNumber]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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
        // $data = Patient::with('appointment', 'user')->get();
        // dd($data);
        return view('admin.patientlist');
    }

    public function searchpatientlistdoc(Request $request)
    {
        if ($request->ajax()) {
            // Log the queries
            DB::listen(function ($query) {
                Log::info(
                    $query->sql,
                    $query->bindings,
                    $query->time
                );
            });

            $query = $request->get('query');
            $data = Patient::with('appointment', 'user');

            if ($query != '') {
                $data->where(function ($q) use ($query) {
                    $q->orWhereHas('user', function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%");
                    });
                });
            }

            $data = $data->paginate(10);

            $total_row = $data->total();
            $rowCount = ($data->currentPage() - 1) * $data->perPage() + 1;

            $output = '';
            if ($total_row > 0) {
                foreach ($data as $row) {
                    $output .= '
                <tr>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $rowCount . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->user->name . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->user->email . '</a></td>
                    <td>
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="' . route('patientprofile', [$row->id]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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

            return response()->json([
                'table_data' => $output,
                'pagination' => (string) $data->links('vendor.pagination.bootstrap-4')
            ]);
        }
    }

    public function doctorlist()
    {
        $data = Doctor::with('specialization', 'user')->get();
        // dd($data);
        return view('admin.doctorlist');
    }

    public function searchdoctorlist(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            $data = Doctor::with('specialization', 'user');

            if ($query != '') {
                $data->where(function ($q) use ($query) {
                    $q->whereHas('specialization', function ($q) use ($query) {
                        $q->where('specialization', 'like', "%{$query}%");
                    })
                        ->orWhereHas('user', function ($q) use ($query) {
                            $q->where('name', 'like', "%{$query}%")
                                ->orWhere('email', 'like', "%{$query}%");
                        });
                });
            }

            $data = $data->paginate(10);

            $total_row = $data->total();
            $rowCount = ($data->currentPage() - 1) * $data->perPage() + 1;

            $output = '';
            if ($total_row > 0) {
                foreach ($data as $row) {
                    $output .= '
                <tr>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $rowCount . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->user->name . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . $row->user->email . '</a></td>
                    <td><a class="text-dark fw-bolder text-hover-primary d-block fs-6">' . ($row->specialization ? $row->specialization->specialization : 'N/A') . '</a></td>
                    <td>
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="' . route('admindocprofile', [$row->id]) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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
                <td align="center" colspan="5">No Data Found</td>
            </tr>';
            }

            return response()->json([
                'table_data' => $output,
                'pagination' => (string) $data->appends($request->query())->links('vendor.pagination.bootstrap-4')
            ]);
        }
    }




    //Admin Profile
    public function profile()
    {
        $id = Auth::user()->admin->id;
        $profile = Admin::with('user')
            ->where('user_id', $id)
            ->get();
        return view('admin.profile.overview', compact('profile'));
    }

    public function adminsetting()
    {
        $id = Auth::user()->admin->id;
        $profile = Admin::with('user')
            ->where('user_id', $id)
            ->get();

        return view('admin.profile.setting', compact('profile'));
    }
    public function adminupdate(Request $request, $id)
    {

        $user = User::findOrFail($id);

        // dd($request->all());
        $request->validate([
            'Name' => ['required', 'string', 'min:255'],
        ]);

        $user->update([
            'name' => $request->Name,
            'email' => $request->email,
            'updated_at' => now(),
        ]);


        return to_route('adminprofile');
    }

    public function adminpass()
    {
        $id = Auth::user()->admin->id;
        $profile = Admin::with('user')
            ->where('user_id', $id)
            ->get();

        return view('admin.profile.security', compact('profile'));
    }

    public function adminpassupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->password),
            'updated_at' => now(),
        ]);
        return to_route('adminprofile');
    }

    //Doctor Profile
    public function docprofile($id)
    {
        $profile = Doctor::with('specialization', 'user')
            ->findOrFail($id);

        // $user = Doctor::findOrFail($id);
        // dd($user->id);
        return view('admin.docprofile.overview', compact('profile'));
    }

    public function admindocsetting($id)
    {
        $profile = Doctor::with('specialization', 'user')
            ->findOrFail($id);
        $Specializations = Specialization::all();
        return view('admin.docprofile.setting', compact('profile', 'Specializations'));
    }
    public function admindocupdate(Request $request, $id)
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

        $user->doctor->update([
            'phoneno' => $request->phoneno,
            'gender' => $request->gender,
            'address' => $request->address,
            'specialization_id' => $request->specialization,
            'dob' => $request->dob,
            'updated_at' => now(),
        ]);

        return back();
    }

    public function admindocpass($id)
    {
        $profile = Doctor::with('specialization', 'user')
            ->findOrFail($id);

        return view('admin.docprofile.security', compact('profile'));
    }

    public function admindocpassupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);


        $user->update([
            'password' => Hash::make($request->password),
            'updated_at' => now(),
        ]);
        return back();
    }

    public function admindocemail(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email' => ['string', 'email', 'max:255', new DifferentEmail($user->email), 'unique:users'],
        ]);
        $user->update([
            'email' => $request->email,
            'updated_at' => now(),
        ]);
        return back();
    }


    //Patient Profile
    public function patientprofile($id)
    {
        $profile = Patient::with('user')
            ->findOrFail($id);

        // $user = Doctor::findOrFail($id);
        // dd($user->id);
        return view('admin.patientprofile.overview', compact('profile'));
    }

    public function patientsetting($id)
    {
        $profile = Patient::with('user')
            ->findOrFail($id);
        return view('admin.patientprofile.setting', compact('profile'));
    }
    public function patientupdate(Request $request, $id)
    {

        $user = User::findOrFail($id);
        // dd($user->doctor);

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

        $user->patient->update([
            'phoneno' => $request->phoneno,
            'gender' => $request->gender,
            'address' => $request->address,
            'dob' => $request->dob,
            'updated_at' => now(),
        ]);

        return back();
    }

    public function patientpass($id)
    {
        $profile = Patient::with('user')
            ->findOrFail($id);

        return view('admin.patientprofile.security', compact('profile'));
    }

    public function patientpassupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->password),
            'updated_at' => now(),
        ]);
        return back();
    }

    public function patientemailupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email' => ['string', 'email', 'max:255', new DifferentEmail($user->email), 'unique:users'],
        ]);
        $user->update([
            'email' => $request->email,
            'updated_at' => now(),
        ]);
        return back();
    }

    //Create new Doctor
    public function newdoctor()
    {
        $Specializations = Specialization::all();

        return view('admin.newdoctor', compact('Specializations'));
    }

    public function storedoctor(Request $request)
    {

        // dd( $request->all());
        $request->validate([
            'Name' => 'required|max:255',
            'Specialization' => 'required',
            'dob' => 'required',
        ]);
        $f5name = substr(Str::lower($request->Name), 0, 5);
        $email = $f5name . '' . '@clinic.com';
        $dob = Carbon::parse($request->dob)->format('d');
        $password = $f5name . '' . $dob;
        $user = User::create([
            'name' => $request->Name,
            'email' => $email,
            'role' => 1,
            'password' => Hash::make($password),
            'created_at' => now(),
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'dob' => $request->dob,
            "specialization_id" => $request->Specialization,
            'created_at' => now(),
        ]);

        // return back()->withSuccess('Thank You', 'Your Appointment Request Has Been Send. We Will Contact You Soon');
        return redirect()->route('admin.doclistdoc');
    }

    public function apptdelete($id)
    {
        $appt = Appointment::findOrFail($id);
        $appt->delete();
        return view('admin.allappt');
    }

    public function patientdelete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return view('admin.patientlist');
    }
    public function doctordelete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return view('admin.doctorlist');
    }


}
