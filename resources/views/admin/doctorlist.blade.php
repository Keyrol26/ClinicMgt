@extends('layouts.master')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Doctor
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Doctor List</small>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">All Doctor</small>
                </h1>
            </div>
        </div>
    </div>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="row gy-5 g-xl-12">
                <div class="col-xl-12">
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">All Doctor</span>
                            </h3>
                            <div class="card-toolbar">
                                <input type="text" name="search" id="search" class="form-control" placeholder="Search Doctors">
                            </div>
                            <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to book an appointment">
                                <a href="{{ route('newdoctor') }}" class="btn btn-sm btn-light btn-active-primary">
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black" />
                                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black" />
                                        </svg>
                                    </span>
                                    New Doctor
                                </a>
                            </div>
                        </div>
                        <div class="card-body py-3">
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bolder text-muted">
                                            <th class="min-w-40px">No</th>
                                            <th class="min-w-150px">Doctor Name</th>
                                            <th class="min-w-150px">Email</th>
                                            <th class="min-w-150px">Specialization</th>
                                            <th class="min-w-60px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="appointment_table">
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end" id="pagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        function fetch_data(query = '', page = 1) {
            $.ajax({
                url: "{{ route('admin.searchdoctorlist') }}",
                method: 'GET',
                data: {
                    query: query,
                    page: page
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#appointment_table').html(data.table_data);
                    $('#pagination').html(data.pagination);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
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
