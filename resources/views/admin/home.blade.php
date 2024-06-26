@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Admin Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ Auth::user()->name }}
                    <br>
                    {{$msg}}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- DOM dataTable -->
    <div class="col-md-12">
        <div class="widget">
            <header class="widget-header">
                <h4 class="widget-title">All Appointment</h4>
            </header><!-- .widget-header -->
            <hr class="widget-separator">
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover js-basic-example dataTable table-custom">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Appointment Number</th>
                                <th>Patient Name</th>
                                <th>Email</th>
                                <th>Doctor Name</th>
                                <th>Appointment Date</th>
                                <th>Appointment Time</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @if ($appointment != null || $appointment != 0)
                                @foreach ($appointment as $appointment)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $appointment->AppointmentNumber }}</td>
                                        <td>{{ $appointment->name }}</td>
                                        <td>{{ $appointment->patient->user->email ?? 'No Email'}}</td>
                                        <td>{{ $appointment->doctor->user->name }}</td>
                                        <td>{{ $appointment->AppointmentDate }}</td>
                                        <td>{{ $appointment->bookingTime->AppointmentTime }}</td>

                                        @if ($appointment->Status == '')
                                            <td>Not Updated Yet</td>
                                        @else
                                            <td>{{ $appointment->Status }}</td>
                                        @endif

                                        <td><a href="{{ route('detailAppointment.show', [$appointment->id, $appointment->AppointmentNumber]) }}"
                                                class="btn btn-primary">View</a></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8"> No record found against this search</td>
                                </tr>
                            @endif

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S.No</th>
                                <th>Appointment Number</th>
                                <th>Patient Name</th>
                                <th>Email</th>
                                <th>Doctor Name</th>
                                <th>Appointment Date</th>
                                <th>Appointment Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->


</div><!-- .row -->
<div class="row">
    <!-- DOM dataTable -->
    <div class="col-md-12">
        <div class="widget">
            <header class="widget-header">
                <h4 class="widget-title">All Doctor</h4>
            </header><!-- .widget-header -->
            <hr class="widget-separator">
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover js-basic-example dataTable table-custom">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Doctor Name</th>
                                <th>Specialization</th>
                                <th>Joined Date</th>
                                <th>Email</th>
                                <th>Action</th>

                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @if ($doctor != null || $doctor != 0)
                                @foreach ($doctor as $doctor)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $doctor->user->name }}</td>
                                        <td>{{ $doctor->specialization->specialization }}</td>
                                        <td>{{ $doctor->created_at }}</td>
                                        <td>{{ $doctor->user->email }}</td>
                                        <td><a href="{{ route('detailAppointment.show', [$appointment->id, $appointment->AppointmentNumber]) }}"
                                                class="btn btn-primary">View</a></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8"> No record found against this search</td>
                                </tr>
                            @endif

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S.No</th>
                                <th>Doctor Name</th>
                                <th>Specialization</th>
                                <th>Joined Date</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->


</div><!-- .row -->

<div class="row">
    <!-- DOM dataTable -->
    <div class="col-md-12">
        <div class="widget">
            <header class="widget-header">
                <h4 class="widget-title">All Patient</h4>
            </header><!-- .widget-header -->
            <hr class="widget-separator">
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover js-basic-example dataTable table-custom">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Patient Name</th>
                                <th>Joined Date</th>
                                <th>Email</th>
                                <th>Action</th>

                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @if ($patient != null || $patient != 0)
                                @foreach ($patient as $patient)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $patient->user->name }}</td>
                                        <td>{{ $patient->created_at }}</td>
                                        <td>{{ $patient->user->email }}</td>
                                        <td><a href="{{ route('detailAppointment.show', [$appointment->id, $appointment->AppointmentNumber]) }}"
                                                class="btn btn-primary">View</a></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8"> No record found against this search</td>
                                </tr>
                            @endif

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S.No</th>
                                <th>Patient Name</th>
                                <th>Joined Date</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->


</div><!-- .row -->
@endsection
