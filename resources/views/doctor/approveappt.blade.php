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
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Approved Appointment</small>
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
                                    <span class="card-label fw-bolder fs-3 mb-1">Approved Appointment</span>
                                </h3>
                                <div class="card-toolbar">
                                    <input type="text" name="search" id="search" class="form-control"
                                        placeholder="Search Appointments">
                                </div>
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
                                        <tbody id="approvedappointment_table">
                                            {{-- @include('partials.appointment_table', ['appointment' => $appointment]) --}}
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Table container-->
                                <!--begin::Pagination-->
                                <div class="d-flex justify-content-end" id="pagination">
                                </div>
                                <!--end::Pagination-->
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        function fetch_data(query = '', page = 1) {
            $.ajax({
                url: "{{ route('searchapprovedapptdoc') }}",
                method: 'GET',
                data: {
                    query: query,
                    page: page
                },
                dataType: 'json',
                success: function(data) {
                    $('#approvedappointment_table').html(data.table_data);
                    $('#pagination').html(data.pagination);
                }
            });
        }

        $(document).ready(function() {
            fetch_data();

            $(document).on('keyup', '#search', function() {
                var query = $(this).val();
                fetch_data(query);
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var query = $('#search').val();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data(query, page);
            });
        });
    </script>
@endsection
