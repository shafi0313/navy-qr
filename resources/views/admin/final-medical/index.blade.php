@extends('admin.layouts.app')
@php
    $pageTitle = 'Final Medical';
    $folder = 'final_medical';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', $pageTitle, 'Index']])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of Applicants</h4>
                    </div>
                    {{-- <div class="col-md-12 mb-2">
                        <div class="row justify-content-center filter align-items-end">
                            <div class="col">
                                <div class="form-group my-3">
                                    <label class="form-label" for="gender">@lang('Gender')</label>
                                    <select name="gender" class="gender select_2 form-control w-100">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group my-3">
                                    <a href="" class="btn btn-danger">Clear</a>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <table id="data_table" class="table table-bordered table-centered mb-0 w-100">
                        <thead></thead>
                        <tbody></tbody>
                    </table>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
        <script>
            $(function() {
                let table = $('#data_table').DataTable({
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    ordering: true,
                    responsive: true,
                    scrollY: 400,
                    ajax: {
                        url: "{{ route('admin.' . $route . '.index') }}",
                        type: "get",
                        data: function(d) {
                            return $.extend({}, d, {
                                "gender": $('.gender').val()
                            });
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            className: 'text-center',
                            width: '60px',
                            title: 'SL',
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'exam_date',
                            name: 'exam_date',
                            title: 'exam date',
                        },
                        {
                            data: 'serial_no',
                            name: 'serial_no',
                            title: 'Roll no',
                        },
                        {
                            data: 'candidate_designation',
                            name: 'candidate_designation',
                            title: 'Des',
                        },
                        {
                            data: 'name',
                            name: 'name',
                            title: 'Name',
                        },
                        {
                            data: 'eligible_district',
                            name: 'eligible_district',
                            title: 'District',
                        },
                        // {
                        //     data: 'medical',
                        //     name: 'medical',
                        //     title: 'P. medical',
                        //     className: 'text-center',
                        // },
                        {
                            data: 'action',
                            name: 'action',
                            title: 'action',
                            className: 'text-center',
                        },
                        {
                            data: 'final_medical',
                            name: 'final_medical',
                            title: 'Remarks',
                            className: 'text-center',
                        },
                    ],
                    scroller: {
                        loadingIndicator: true
                    },
                    order: [
                        [1, 'asc']
                    ]
                });
                $(".filter").find('select').on('change', function() {
                    table.draw();
                });

                $(".filter").find('a').on('click', function(e) {
                    e.preventDefault();
                    $(".filter").find('select').val('').trigger('change');
                    table.draw();
                });
            });
        </script>
        <script>

            function fMPass(id) {
                swal({
                    title: "Are you sure?",
                    text: "This change will affect all records!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        showLoadingAnimation();
                        $.ajax({
                            url: '{{ route('admin.final_medicals.pass') }}',
                            type: "PATCH",
                            data: {
                                id: id,
                            },
                            success: (res) => {
                                hideLoadingAnimation();
                                swal({
                                    icon: "success",
                                    title: "Success",
                                    text: res.message,
                                });
                                $(".table").DataTable().ajax.reload();
                            },
                            error: (err) => {
                                hideLoadingAnimation();
                                swal({
                                    icon: "error",
                                    title: "Oops...",
                                    text: err.responseJSON.message,
                                });
                            },
                        });
                    }
                });
            }
            function fMFail(id) {
                swal({
                    title: "Are you sure?",
                    text: "This change will affect all records!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        showLoadingAnimation();
                        $.ajax({
                            url: '{{ route('admin.final_medicals.fail') }}',
                            type: "PATCH",
                            data: {
                                id: id,
                            },
                            success: (res) => {
                                hideLoadingAnimation();
                                swal({
                                    icon: "success",
                                    title: "Success",
                                    text: res.message,
                                });
                                $(".table").DataTable().ajax.reload();
                            },
                            error: (err) => {
                                hideLoadingAnimation();
                                swal({
                                    icon: "error",
                                    title: "Oops...",
                                    text: err.responseJSON.message,
                                });
                            },
                        });
                    }
                });
            }
        </script>
    @endpush
@endsection
