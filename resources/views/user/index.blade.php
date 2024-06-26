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
                <!--begin::Actions-->
                <div class="d-flex align-items-center py-1">
                    <!--begin::Button-->
                    <a href="/booking-apt" class="btn btn-sm btn-primary">Create</a>
                    <!--end::Button-->
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Row-->
                <div class="row g-5 g-xl-10">
                    <div class="col-xl-5">
                        <!--begin::Statistics Widget 1-->
                        <div class="card bgi-no-repeat card-xl-stretch mb-xl-10"
                            style="background-position: right top; background-size: 30% auto; background-image: url(metronic/assets/media/svg/shapes/abstract-4.svg)">
                            <!--begin::Body-->
                            <div class="card-body">
                                <a class="card-title fs-4 fw-bolder text-dark">Appoinment
                                    Schedule</a>
                                @foreach ($appointmentschedule as $appointmentschedule)
                                    <div class="fw-bolder text-primary my-6">Date :
                                        {{ Carbon::parse($appointmentschedule->AppointmentDate)->format('d F, y') }}</div>
                                    <p class="text-dark-75 fw-bold fs-5 m-0">Time :
                                        {{ $appointmentschedule->bookingtime->AppointmentTime }}
                                        <br />Doctor : {{ $appointmentschedule->doctor->user->name }}
                                        <br />Doc. Spec. :
                                        {{ $appointmentschedule->doctor->specialization->specialization }}
                                    </p>
                                @endforeach
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Statistics Widget 1-->
                    </div>
                    <div class="col-xl-7">
                        <!--begin::List Widget 7-->
                        <div class="card card-xl-stretch mb-xl-8">
                            <!--begin::Header-->
                            <div class="card-header align-items-center border-0 mt-4">
                                <h3 class="card-title align-items-start flex-column">
                                    <a href="{{ route('statuslist') }}"
                                        class="fw-bolder text-dark text-hover-primary">Appoinment Status</a>
                                </h3>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-3">
                                @foreach ($appointmentstatus as $appointmentstatus)
                                    <!--begin::Item-->
                                    <div class="d-flex align-items-sm-center mb-7">
                                        <!--begin::Title-->
                                        <div class="d-flex flex-row-fluid flex-wrap align-items-center">
                                            <div class="flex-grow-1 me-2">
                                                <a class="text-gray-800 fw-bolder fs-6">{{ $appointmentstatus->name }}</a>
                                                <span class="text-muted fw-bold d-block pt-1">Date:
                                                    {{ $appointmentstatus->AppointmentDate }}</span>
                                                <span class="text-muted fw-bold d-block pt-1">Time:
                                                    {{ $appointmentstatus->bookingtime->AppointmentTime }}</span>
                                            </div>
                                            @if ($appointmentstatus->Status == 'Approved')
                                                <span class="badge badge-light-success fs-8 fw-bolder my-2">Approved</span>
                                            @elseif ($appointmentstatus->Status == 'Cancelled')
                                                <span class="badge badge-light-danger fs-8 fw-bolder my-2">Rejected</span>
                                            @else
                                                <span class="badge badge-light-warning fs-8 fw-bolder my-2">In
                                                    Progress</span>
                                            @endif
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                @endforeach
                                <!--end::Item-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::List Widget 7-->
                    </div>
                </div>
                <div class="row g-5 g-xl-10">
                    <div class="col-xl-7">
                        <!--begin::List Widget 7-->
                        <div class="card card-xl-stretch mb-xl-8">
                            <!--begin::Header-->
                            <div class="card-header align-items-center border-0 mt-4">
                                <h3 class="card-title align-items-start flex-column">
                                    <a href="{{ route('historylist') }}"
                                        class="fw-bolder text-dark text-hover-primary">Appoinment List</a>
                                </h3>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-3">
                                <!--begin::Item-->
                                @foreach ($appointmentlist as $appointmentlist)
                                    <!--begin::Item-->
                                    <div class="d-flex align-items-sm-center mb-7">
                                        <!--begin::Title-->
                                        <div class="d-flex flex-row-fluid flex-wrap align-items-center">
                                            <div class="flex-grow-1 me-2">
                                                <a class="text-gray-800 fw-bolder  fs-6">{{ $appointmentlist->name }}</a>
                                                <span class="text-muted fw-bold d-block pt-1">Date:
                                                    {{ $appointmentlist->AppointmentDate }}</span>
                                                <span class="text-muted fw-bold d-block pt-1">Time:
                                                    {{ $appointmentlist->bookingtime->AppointmentTime }}</span>
                                            </div>
                                            @if ($appointmentlist->Status == 'Approved')
                                                <span class="badge badge-light-success fs-8 fw-bolder my-2">Approved</span>
                                            @elseif ($appointmentlist->Status == 'Cancelled')
                                                <span class="badge badge-light-danger fs-8 fw-bolder my-2">Rejected</span>
                                            @else
                                                <span class="badge badge-light-warning fs-8 fw-bolder my-2">In
                                                    Progress</span>
                                            @endif
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                @endforeach
                                <!--end::Item-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::List Widget 7-->
                    </div>
                    <div class="col-xl-5">
                        <!--begin::Statistics Widget 1-->
                        <div class="col-xl-12">
                            <!--begin::Statistics Widget 2-->
                            <div class="card card-xl-stretch mb-xl-6">
                                <!--begin::Body-->
                                <div class="card-body d-flex align-items-center pt-3 pb-0">
                                    @foreach ($doc1 as $doc1)
                                        <div class="d-flex flex-column flex-grow-1 py-2 py-lg-13 me-2">
                                            <a class="fw-bolder text-dark fs-4 mb-2 ">Dr.
                                                {{ $doc1->user->name }} ({{ $doc1->specialization->specialization }})</a>
                                            <span class="fw-bold text-muted fs-5">To cure sometimes, to relieve often, to
                                                comfort always.</span>
                                        </div>
                                        <img src="metronic/assets/media/svg/avatars/029-boy-11.svg" alt=""
                                            class="align-self-end h-100px" />
                                    @endforeach
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Statistics Widget 2-->
                        </div>
                        <!--end::Statistics Widget 1-->
                        <!--begin::Statistics Widget 1-->
                        <div class="col-xl-12">
                            <!--begin::Statistics Widget 2-->
                            <div class="card card-xl-stretch mb-xl-6">
                                <!--begin::Body-->
                                <div class="card-body d-flex align-items-center pt-3 pb-0">
                                    @foreach ($doc2 as $doc2)
                                        <div class="d-flex flex-column flex-grow-1 py-2 py-lg-13 me-2">
                                            <a class="fw-bolder text-dark fs-4 mb-2 text-hover-primary">Dr.
                                                {{ $doc2->user->name }} ({{ $doc2->specialization->specialization }})</a>
                                            <span class="fw-bold text-muted fs-5">The best way to find yourself is to lose
                                                yourself in the service of others.</span>
                                        </div>
                                        <img src="metronic/assets/media/svg/avatars/004-boy-1.svg" alt=""
                                            class="align-self-end h-100px" />
                                    @endforeach
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Statistics Widget 2-->
                        </div>
                        <!--end::Statistics Widget 1-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
