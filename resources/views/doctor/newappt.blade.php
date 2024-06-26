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
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">All Appointment
                        <!--begin::Separator-->
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <!--end::Separator-->
                        <!--begin::Description-->
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Appoinment</small>
                        <!--end::Description-->
                        <!--begin::Separator-->
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <!--end::Separator-->
                        <!--begin::Description-->
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">New Appoinment</small>
                        <!--end::Description-->
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
                {{-- <!--begin::Actions-->
                <div class="d-flex align-items-center py-1">
                    <!--begin::Button-->
                    <a href="/booking-apt" class="btn btn-sm btn-primary">Create</a>
                    <!--end::Button-->
                </div>
                <!--end::Actions--> --}}
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
                                    <span class="card-label fw-bolder fs-3 mb-1">New Appointment</span>
                                </h3>
                                {{-- <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-trigger="hover" title="Click to book an appoinment">
                                    <a href="/booking-apt" class="btn btn-sm btn-light btn-active-primary">
                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                                                    rx="1" transform="rotate(-90 11.364 20.364)" fill="black" />
                                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->New Appointment</a>
                                </div> --}}
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body py-3">
                                <!--begin::Table container-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <!--begin::Table head-->
                                        <thead>
                                            <tr class="fw-bolder text-muted">
                                                <th class="min-w-40px">No</th>
                                                <th class="min-w-90px">Appt. No.</th>
                                                <th class="min-w-150px">Patient Name</th>
                                                <th class="min-w-120px">Appt. Date</th>
                                                <th class="min-w-100px">Appt. Time</th>
                                                <th class="min-w-100px">Status</th>
                                                <th class="min-w-60px text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @if ($appointment != null || $appointment != 0)
                                                @foreach ($appointment as $appointmenttable)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="d-flex justify-content-start flex-column">
                                                                    <a
                                                                        class="text-dark fw-bolder text-hover-primary fs-6">{{ $no++ }}</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a
                                                                class="text-dark fw-bolder text-hover-primary d-block fs-6">{{ $appointmenttable->AppointmentNumber }}</a>
                                                        </td>
                                                        <td>
                                                            <a
                                                                class="text-dark fw-bolder text-hover-primary d-block fs-6">{{ $appointmenttable->name }}</a>
                                                        </td>
                                                        <td>
                                                            <a
                                                                class="text-dark fw-bolder text-hover-primary d-block fs-6">{{ $appointmenttable->patient->user->email ?? 'No Email' }}</a>
                                                        </td>
                                                        <td>
                                                            <a
                                                                class="text-dark fw-bolder text-hover-primary d-block fs-6">{{ $appointmenttable->doctor->user->name ?? 'No Doctor' }}</a>
                                                        </td>
                                                        <td>
                                                            <a
                                                                class="text-dark fw-bolder text-hover-primary d-block fs-6">{{ date('d-m-Y', strtotime($appointmenttable->AppointmentDate)) }}</a>
                                                        </td>
                                                        <td>
                                                            <a
                                                                class="text-dark fw-bolder text-hover-primary d-block fs-6">{{ $appointmenttable->bookingtime->AppointmentTime }}</a>
                                                        </td>
                                                        @if ($appointmenttable->Status == 'Approved')
                                                            <td>
                                                                <span
                                                                    class="badge badge-light-success fs-8 fw-bolder my-2">Approved</span>
                                                            </td>
                                                        @elseif ($appointmenttable->Status == 'Cancelled')
                                                            <td>
                                                                <span
                                                                    class="badge badge-light-danger fs-8 fw-bolder my-2">Rejected</span>
                                                            </td>
                                                        @else
                                                            <td>
                                                                <span
                                                                    class="badge badge-light-warning fs-8 fw-bolder my-2">In
                                                                    Progress</span>
                                                            </td>
                                                        @endif

                                                        <td>
                                                            <div class="d-flex justify-content-end flex-shrink-0">
                                                                <a href="{{ route('detailAppointment.show', [$appointmenttable->id, $appointmenttable->AppointmentNumber]) }}"
                                                                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                                    <!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
                                                                    <span class="svg-icon svg-icon-3">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none">
                                                                            <path
                                                                                d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                                                                fill="black" />
                                                                            <path opacity="0.3"
                                                                                d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                                                                fill="black" />
                                                                        </svg>
                                                                    </span>
                                                                    <!--end::Svg Icon-->
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="8"> No record found against this search</td>
                                                </tr>
                                            @endif
                                        </tbody>

                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Table container-->
                                <!--begin::Pagination-->
                                <div class="d-flex justify-content-end">
                                    {{ $appointment->links('vendor.pagination.bootstrap-4') }}
                                </div>
                                <!--end::Pagination-->
                            </div>
                            <!--begin::Body-->
                        </div>
                        <!--end::Tables Widget 9-->
                    </div>
                    <!--end::Col-->
                </div>
            </div>
        </div>
    </div>
@endsection
