@extends('admin.layouts.app')
@php
    $pageTitle = 'Application Search';
    $folder = 'application-search';
    $route = 'application-search';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => $pageTitle, 'menuName' => 3])

    <div class="row">
        <div class="col-12">
            <div class="card">
                {{-- <form onsubmit="ajaxStoreModal(event, this, 'createModal')" action="{{ route('admin.' . $route . '.store') }}"
                    method="POST">
                    @csrf --}}
                <div class="card-body">
                    <div class="row justify-content-center gy-2 mb-3">
                        <div class="col-md-6">
                            <label for="application_id" class="form-label required">Applicant </label>
                            <select name="application_id" id="application_id" class="form-select"></select>
                        </div>
                        <div class="col-md-3" style="margin-top: 35px">
                            <button type="submit" class="btn btn-primary" id="filter_btn">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Search</button>
                        </div>
                    </div>
                </div> <!-- end card-body -->
                {{-- </form> --}}
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">Applicant</h4>
                    </div>
                    <table id="data_table" class="table table-bordered table-centered mb-0 w-100">
                        <thead>
                            <tr>
                                <th>Exam Date</th>
                                <th>Roll No</th>
                                <th>Designation</th>
                                <th>Name</th>
                                <th>District</th>
                                <th>Gate Entry</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
        <script>
            $('#application_id').select2({
                width: '100%',
                placeholder: 'Select...',
                allowClear: true,
                ajax: {
                    url: window.location.origin + '/dashboard/select-2-ajax',
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    data: function(params) {
                        return {
                            q: $.trim(params.term),
                            type: 'getApplicant',
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            })

            function getDataById(id) {
                $.ajax({
                    url: "{{ route('admin.application_search.show', ['id' => ':id']) }}".replace(':id',
                        id),
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            $('#data_table tbody').empty();
                            const data = response.data;
                            data.forEach(item => {
                                const row = `<tr>
                                    <td>${item.exam_date}</td>
                                    <td>${item.serial_no}</td>
                                    <td>${item.candidate_designation}</td>
                                    <td>${item.name}</td>
                                    <td>${item.eligible_district}</td>
                                    <td><a class="btn btn-primary ok_btn">OK</a></td>
                                 </tr>`;
                                $('#data_table tbody').append(row);
                            });
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            }

            $('#application_id').on('select2:select', function(e) {
                $('#data_table tbody').empty();
            });

            $(document).ready(function() {
                $('#filter_btn').click(function() {
                    const id = $('#application_id').val();
                    getDataById(id);
                });
            });


            $(document).on('click', '.ok_btn', function(e) {
                e.preventDefault();
                let application_id = $('#application_id').find(":selected").val();
                showLoadingAnimation();
                $.ajax({
                    url: "{{ route('admin.application-search.store') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        application_id: application_id
                    },
                    success: function(res) {
                        hideLoadingAnimation();
                        swal({
                            icon: "success",
                            title: "Success",
                            text: res.message,
                        }).then((confirm) => {
                            // If the user confirms (clicks OK)
                            if (confirm) {
                                $('#data_table tbody').empty();
                            }
                        });
                    },
                    error: function(err) {
                        hideLoadingAnimation();
                        swal({
                            icon: "error",
                            title: "Oops...",
                            text: err.responseJSON ? err.responseJSON.message :
                                'An error occurred.',
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
