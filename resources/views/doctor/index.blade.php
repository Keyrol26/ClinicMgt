@php
    use Carbon\Carbon;
@endphp
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
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard
                        <!--begin::Separator-->
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <!--end::Separator-->
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
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Row-->
                <div class="row g-5 g-xl-8">
                    <div class="col-xl-6">
                        <!--begin::List Widget 9-->
                        <div class="card card-xl-stretch mb-5 mb-xl-8">
                            <!--begin::Header-->
                            <div class="card-header align-items-center border-0 mt-3">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="fw-bolder text-dark fs-3">Total Appoinment</span>
                                </h3>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-5">
                                <!--begin::Item-->
                                <div class="d-flex mb-7">
                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center flex-wrap flex-grow-1 mt-n2 mt-lg-n1">
                                        <!--begin::Title-->
                                        <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pe-3">
                                            <a href="{{ route('allapptdoc') }}"
                                                class="fs-5 text-gray-800 text-hover-primary fw-bolder">All
                                                Appoinment</a>
                                            {{-- <span class="text-gray-400 fw-bold fs-7 my-1">Study highway types</span>
                                            <span class="text-gray-400 fw-bold fs-7">By:
                                                <a href="#" class="text-primary fw-bold">CoreAd</a></span> --}}
                                        </div>
                                        <!--end::Title-->
                                        <!--begin::Info-->
                                        <div class="text-end py-lg-0 py-2">
                                            <span class="text-gray-800 fw-boldest fs-3">{{ $appointmentsall }}</span>
                                            <span class="text-gray-400 fs-7 fw-bold d-block">Appt</span>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Item-->
                                <div class="d-flex mb-7">
                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center flex-wrap flex-grow-1 mt-n2 mt-lg-n1">
                                        <!--begin::Title-->
                                        <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pe-3">
                                            <a href="{{ route('newapptdoc') }}"
                                                class="fs-5 text-gray-800 text-hover-primary fw-bolder">New
                                                Appoinment</a>
                                            {{-- <span class="text-gray-400 fw-bold fs-7 my-1">Study highway types</span>
                                            <span class="text-gray-400 fw-bold fs-7">By:
                                                <a href="#" class="text-primary fw-bold">CoreAd</a></span> --}}
                                        </div>
                                        <!--end::Title-->
                                        <!--begin::Info-->
                                        <div class="text-end py-lg-0 py-2">
                                            <span class="text-gray-800 fw-boldest fs-3">{{ $appointmentsnew }}</span>
                                            <span class="text-gray-400 fs-7 fw-bold d-block">Appt</span>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Item-->
                                <div class="d-flex mb-7">
                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center flex-wrap flex-grow-1 mt-n2 mt-lg-n1">
                                        <!--begin::Title-->
                                        <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pe-3">
                                            <a href="{{ route('approvedapptdoc') }}"
                                                class="fs-5 text-gray-800 text-hover-primary fw-bolder">Approved
                                                Appoinment</a>
                                            {{-- <span class="text-gray-400 fw-bold fs-7 my-1">Study highway types</span>
                                            <span class="text-gray-400 fw-bold fs-7">By:
                                                <a href="#" class="text-primary fw-bold">CoreAd</a></span> --}}
                                        </div>
                                        <!--end::Title-->
                                        <!--begin::Info-->
                                        <div class="text-end py-lg-0 py-2">
                                            <span class="text-gray-800 fw-boldest fs-3">{{ $appointmentsapproved }}</span>
                                            <span class="text-gray-400 fs-7 fw-bold d-block">Appt</span>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Item-->
                                <div class="d-flex mb-7">
                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center flex-wrap flex-grow-1 mt-n2 mt-lg-n1">
                                        <!--begin::Title-->
                                        <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pe-3">
                                            <a href="{{ route('cancelapptdoc') }}"
                                                class="fs-5 text-gray-800 text-hover-primary fw-bolder">Cancelled
                                                Appoinment</a>
                                            {{-- <span class="text-gray-400 fw-bold fs-7 my-1">Study highway types</span>
                                            <span class="text-gray-400 fw-bold fs-7">By:
                                                <a href="#" class="text-primary fw-bold">CoreAd</a></span> --}}
                                        </div>
                                        <!--end::Title-->
                                        <!--begin::Info-->
                                        <div class="text-end py-lg-0 py-2">
                                            <span class="text-gray-800 fw-boldest fs-3">{{ $appointmentscancel }}</span>
                                            <span class="text-gray-400 fs-7 fw-bold d-block">Appt</span>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Item-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::List Widget 9-->
                    </div>
                    <div class="col-xl-6">
                        <!--begin::List Widget 7-->
                        <div class="card card-xl-stretch mb-xl-8">
                            <!--begin::Header-->
                            <div class="card-header align-items-center border-0 mt-4">
                                <h3 class="card-title align-items-start flex-column">
                                    <a href="{{ route('allapptdoc') }}" class="fw-bolder text-dark">Latest
                                        Appoinment</a>
                                </h3>
                                <div class="card-toolbar">
                                    <!--end::Menu-->
                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-3">
                                @foreach ($appointmentslist as $appointmentslist)
                                    <!--begin::Item-->
                                    <div class="d-flex align-items-sm-center mb-7">
                                        <!--begin::Title-->
                                        <div class="d-flex flex-row-fluid flex-wrap align-items-center">
                                            <div class="flex-grow-1 me-2">
                                                <a class="text-gray-800 fw-bolder fs-6">{{ $appointmentslist->name }}</a>
                                                <span class="text-muted fw-bold d-block pt-1">Date:
                                                    {{ Carbon::parse($appointmentslist->AppointmentDate)->format('d F, Y') }}
                                                    |Time: {{ $appointmentslist->bookingtime->AppointmentTime }}</span>
                                            </div>
                                            @if ($appointmentslist->Status == 'Approved')
                                                <td>
                                                    <span
                                                        class="badge badge-light-success fs-8 fw-bolder my-2">Approved</span>
                                                </td>
                                            @elseif ($appointmentslist->Status == 'Cancelled')
                                                <td>
                                                    <span
                                                        class="badge badge-light-danger fs-8 fw-bolder my-2">Rejected</span>
                                                </td>
                                            @else
                                                <td>
                                                    <span class="badge badge-light-warning fs-8 fw-bolder my-2">In
                                                        Progress</span>
                                                </td>
                                            @endif
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                @endforeach
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::List Widget 7-->
                    </div>
                </div>
                <!--end::Row-->
            </div>
        </div>
    </div>
@endsection
