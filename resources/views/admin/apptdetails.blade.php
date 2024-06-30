@extends('layouts.master')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <!--begin::Title-->
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Appointment
                        <!--begin::Separator-->
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <!--end::Separator-->
                        <!--begin::Description-->
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Appointment List</small>
                        <!--end::Description-->
                        <!--begin::Separator-->
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <!--end::Separator-->
                        <!--begin::Description-->
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Appointment Details</small>
                        <!--end::Description-->
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="row gy-5 g-xl-12">
                    <!--begin::Col-->
                    <div class="col-xl-12">
                        <!--begin::Tables Widget 9-->
                        <div class="card card-xl-stretch mb-5 mb-xl-8">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">Appointment Details</span>
                                </h3>
                                <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-trigger="hover" title="Click to delete this appoinment">
                                    <a href="" class="btn btn-sm btn-light btn-active-danger" data-toggle="modal"
                                        data-target="#deletemodal">
                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                        <i class="bi bi-trash"></i>
                                        <!--end::Svg Icon-->Delete Appointment</a>
                                </div>
                                <!-- Modal-->
                                <div class="modal fade" id="deletemodal" data-backdrop="static" tabindex="-1"
                                    role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                    <form action="{{ route('apptdelete.delete', $appointment->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirm Delete this
                                                        Appointment?</h5>
                                                    <br>
                                                    <span class="modal-title" id="exampleModalLabel">Appointment Number:
                                                        {{ $appointment->AppointmentNumber ?? '-' }}</span>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-primary font-weight-bold"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit"
                                                        class="btn btn-danger font-weight-bold">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body py-3">
                                <!--begin::Table container-->
                                <div class="table-responsive table-bordered table-striped">
                                    <!--begin::Table-->
                                    <table
                                        class="table table-bordered table-striped table-row-gray-300 align-middle gs-0 gy-4">
                                        <tr class="fw-bolder ">
                                            <th class="min-w-25% text-muted">Appointment Number</th>
                                            <td class="min-w-25%">: {{ $appointment->AppointmentNumber ?? '-' }}</td>
                                            <th class="min-w-25% text-muted">Patient Name</th>
                                            <td class="min-w-25%">: {{ $appointment->name }}</td>
                                        </tr>
                                        <tr class="fw-bolder ">
                                            <th class="min-w-25% text-muted">Mobile Number</th>
                                            <td class="min-w-25%"> : {{ $appointment->patient->phoneno ?? '-' }}</td>
                                            <th class="min-w-25% text-muted">Email</th>
                                            <td class="min-w-25%">: {{ $appointment->patient->user->email }}</td>
                                        </tr>
                                        <tr class="fw-bolder ">
                                            <th class="min-w-25% text-muted">Appointment Date</th>
                                            <td class="min-w-25%">: {{ $appointment->AppointmentDate ?? '-' }}</td>
                                            <th class="min-w-25% text-muted">Appointment Time</th>
                                            <td class="min-w-25%">: {{ $appointment->bookingTime->AppointmentTime }}</td>
                                        </tr>
                                        <tr class="fw-bolder ">
                                            <th class="min-w-25% text-muted">Apply Date</th>
                                            <td class="min-w-25%">: {{ $appointment->created_at ?? '-' }}</td>
                                            <th class="min-w-25% text-muted">Appointment Final Status</th>

                                            <td class="min-w-25%">
                                                @if ($appointment->Status == '')
                                                    : Not yet updated
                                                @endif

                                                @if ($appointment->Status == 'Approved')
                                                    : Appointment has been approved
                                                @endif

                                                @if ($appointment->Status == 'Cancelled')
                                                    : Appointment has been cancelled
                                                @endif

                                                @if ($appointment->Status == 'Rescheduled')
                                                    : Appointment need to be rescheduled
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="fw-bolder ">
                                            <th class="min-w-25% text-muted">Patient Message</th>
                                            @if ($appointment->Message == '')
                                                <td class="max-w-60%" colspan="3">: -</td>
                                            @else
                                                <td class="max-w-60%" colspan="3">: {{ $appointment->Message }}</td>
                                            @endif

                                        </tr>
                                        <tr class="fw-bolder ">
                                            <th class="min-w-25% text-muted">Doctor Remarks</th>
                                            @if ($appointment->Remark == '')
                                                <td class="min-w-25%"colspan="3">: Not Updated Yet</td>
                                            @else
                                                <td class="min-w-25%" colspan="3">: {{ $appointment->Remark }}</td>
                                            @endif

                                        </tr>
                                        <tr class="fw-bolder ">
                                            <th class="min-w-25% text-muted">Appointment Report</th>
                                            @if ($appointment->DocMsg == '')
                                                <td class="min-w-25%"colspan="3">: No Report Yet</td>
                                            @else
                                                <td class="min-w-25%" colspan="3">: {{ $appointment->DocMsg }}</td>
                                            @endif

                                        </tr>
                                    </table>
                                    <!--end::Table-->
                                    @if ($appointment->Status == '' or $appointment->Status == 'Rescheduled')
                                        <p align="center" style="padding-top: 20px">
                                            <button class="btn btn-primary waves-effect waves-light w-lg"
                                                data-toggle="modal" data-target="#statusmodal">Take Action</button>
                                        </p>
                                    @elseif ($appointment->Status != '')
                                        <p align="center" style="padding-top: 20px">
                                            <button class="btn btn-primary waves-effect waves-light w-lg"
                                                data-toggle="modal" data-target="#reportmodal">Update</button>
                                        </p>
                                    @endif
                                    @include('admin.modal')

                                </div>
                                <!--end::Table container-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Tables Widget 9-->
                    </div>
                    <!--end::Col-->
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
