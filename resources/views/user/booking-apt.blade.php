{{-- @extends('layouts.master')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <section class="section-padding" id="booking">
            <div class="container">
                <div class="row">

                    <div class="col-lg-8 col-12 mx-auto">
                        <div class="booking-form">

                            <h2 class="text-center mb-lg-3 mb-2">Book an appointment</h2>

                            <form role="form" method="post" action="{{ route('appointment.booking') }}">
                                @csrf
                                @method('POST')
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <input type="text" name="Name" id="Name" class="form-control"
                                            placeholder="Full name" required='true'>
                                    </div>


                                    <div class="col-lg-6 col-12">
                                        <input type="date" name="AppointmentDate" id="AppointmentDate" value=""
                                            class="form-control">

                                    </div>

                                    <div class="col-lg-6 col-12">
                                        <select name="AppointmentTime" id="AppointmentTime" class="form-control" required>
                                            <option value="">Select Appointment Time</option>
                                            <!--- Fetching States--->
                                            @foreach ($bookingtime as $bookingtime)
                                                <option value="{{ $bookingtime->id }}">
                                                    {{ $bookingtime->AppointmentTime }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-10 col-12">
                                        <select name="Specialization" id="Specialization" class="form-control" required>
                                            <option value="">Select specialization</option>
                                            <!--- Fetching States--->
                                            @foreach ($Specializations as $specialization)
                                                <option value="{{ $specialization->id }}">
                                                    {{ $specialization->specialization }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-6 col-12">
                                        <select name="Doctor" id="Doctor" class="form-control">
                                            <option value=''>Select Doctor</option>
                                        </select>
                                    </div>



                                    <div class="col-12">
                                        <textarea class="form-control" rows="5" id="Message" name="Message" placeholder="Additional Message"></textarea>
                                    </div>

                                    <div class="col-lg-3 col-md-4 col-6 mx-auto">
                                        <button type="submit" class="form-control" name="submit" id="submit-button">Book
                                            Now</button>
                                    </div>
                                </div>
                            </form>


                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#Specialization").on('change', function() {
                let id_specialization = $('#Specialization').val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('appointment.getDoctor') }}",
                    data: {
                        "id_special": id_specialization,
                        '_token': '{{ csrf_token() }}'
                    },
                    cache: false,

                    success: function(msg) {
                        $('#Doctor').html(msg);
                    },

                    error: function(data) {
                        console.log('error', data);
                    },
                })
            });
        });
    </script>
@endsection --}}


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
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Booking Form
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
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Booking-Form</small>
                        <!--end::Description-->
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
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Basic info-->
                <div class="card mb-5 mb-xl-10">
                    <!--begin::Card header-->
                    <div class="card-header border-0" data-bs-target="#kt_account_profile_details"
                        aria-controls="kt_account_profile_details">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bolder m-0">Appoinment Booking Form</h3>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--begin::Card header-->
                    <!--begin::Content-->
                    <div id="kt_account_profile_details" class="collapse show">
                        <!--begin::Form-->
                        <form class="form" role="form" method="post" action="{{ route('appointment.booking') }}">
                            @csrf
                            @method('POST')
                            <!--begin::Card body-->
                            <div class="card-body border-top p-9">
                                <!--begin::Name Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Full Name</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="Name" id="Name"
                                            class="form-control form-control-lg form-control-solid" placeholder="Full name"
                                            value="" />
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Date Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Appointment Date</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="date" name="AppointmentDate" id="AppointmentDate"
                                            class="form-control form-control-lg form-control-solid"
                                            placeholder="Appointment Date" value="" />
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Time Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                        <span class="required">Appointment Time</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <select name="AppointmentTime" id="AppointmentTime" aria-label="Select Appointment Time"
                                            data-control="select2" data-placeholder="Select a appointment time..."
                                            class="form-select form-select-solid form-select-lg fw-bold">
                                            <option value="">Select Appointment Time</option>
                                            @foreach ($bookingtime as $bookingtime)
                                                <option value="{{ $bookingtime->id }}">
                                                    {{ $bookingtime->AppointmentTime }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Specialization Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                        <span class="required">Specialization</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <select name="Specialization" id="Specialization" aria-label="Select Specialization"
                                            data-control="select2" data-placeholder="Select a specialization..."
                                            class="form-select form-select-solid form-select-lg fw-bold">
                                            <option value="">Select Specialization</option>
                                            @foreach ($Specializations as $specialization)
                                                <option value="{{ $specialization->id }}">
                                                    {{ $specialization->specialization }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Doctor Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                        <span class="required">Doctor</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <select name="Doctor" id="Doctor" aria-label="Select Doctor"
                                            data-control="select2" data-placeholder="Select a doctor..."
                                            class="form-select form-select-solid form-select-lg fw-bold">
                                            <option value=''>Select Doctor</option>
                                        </select>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Msg Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">Message</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <textarea name="Message" id="Message"
                                            class="form-control form-control-lg form-control-solid" placeholder="Additional Message"
                                            value="" ></textarea>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card body-->
                            <!--begin::Actions-->
                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                                <button type="submit" name="submit" id="submit-button" class="btn btn-primary">Submit</button>
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Basic info-->
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#Specialization").on('change', function() {
                let id_specialization = $('#Specialization').val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('appointment.getDoctor') }}",
                    data: {
                        "id_special": id_specialization,
                        '_token': '{{ csrf_token() }}'
                    },
                    cache: false,

                    success: function(msg) {
                        $('#Doctor').html(msg);
                    },

                    error: function(data) {
                        console.log('error', data);
                    },
                })
            });
        });
    </script>
@endsection