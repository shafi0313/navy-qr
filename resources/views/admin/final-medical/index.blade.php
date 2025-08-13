@extends('admin.layouts.app')
@php
    $pageTitle = '4.0 - Final Medical';
    $folder = 'final_medical';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => $pageTitle, 'menuName' => 7])
    @include('admin.layouts.includes.table-option')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Filter HTML --}}
                    @include('admin.layouts.includes.applicant-get-filter-html')
                    {{-- /Filter HTML --}}
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
                            return $.extend(d, {
                                district: $('.district').val(),
                                team: $('.team').val(),
                                exam_date: $('.exam_date').val()
                            });
                        },
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
                            title: 'Branch',
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
                        {
                            data: 'medical',
                            name: 'medical',
                            title: 'Pre. Medical',
                            className: 'text-center',
                        },
                        {
                            data: 'written',
                            name: 'written',
                            title: 'Written',
                            className: 'text-center',
                        },
                        {
                            data: 'final',
                            name: 'final',
                            title: 'Remarks',
                            className: 'text-center',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            title: 'action',
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

        {{-- Filter Get JS --}}
        @include('admin.layouts.includes.applicant-get-filter-js')
        {{-- /Filter Get JS --}}

        <script>
            // function fMPass(id) {
            //     swal({
            //         title: "Are you sure?",
            //         text: "This change will affect all records!",
            //         icon: "warning",
            //         buttons: true,
            //         dangerMode: true,
            //     }).then((willDelete) => {
            //         if (willDelete) {
            //             showLoadingAnimation();
            //             $.ajax({
            //                 url: '{{ ('admin.final_medicals.pass') }}',
            //                 type: "PATCH",
            //                 data: {
            //                     id: id,
            //                 },
            //                 success: (res) => {
            //                     hideLoadingAnimation();
            //                     swal({
            //                         icon: "success",
            //                         title: "Success",
            //                         text: res.message,
            //                     });
            //                     $(".table").DataTable().ajax.reload();
            //                 },
            //                 error: (err) => {
            //                     hideLoadingAnimation();
            //                     swal({
            //                         icon: "error",
            //                         title: "Oops...",
            //                         text: err.responseJSON.message,
            //                     });
            //                 },
            //             });
            //         }
            //     });
            // }

            // function fMFail(id) {
            //     swal({
            //         title: "Are you sure?",
            //         text: "This change will affect all records!",
            //         icon: "warning",
            //         buttons: true,
            //         dangerMode: true,
            //     }).then((willDelete) => {
            //         if (willDelete) {
            //             showLoadingAnimation();
            //             $.ajax({
            //                 url: '{{ ('admin.final_medicals.unfit_store') }}',
            //                 type: "PATCH",
            //                 data: {
            //                     id: id,
            //                 },
            //                 success: (res) => {
            //                     hideLoadingAnimation();
            //                     swal({
            //                         icon: "success",
            //                         title: "Success",
            //                         text: res.message,
            //                     });
            //                     $(".table").DataTable().ajax.reload();
            //                 },
            //                 error: (err) => {
            //                     hideLoadingAnimation();
            //                     swal({
            //                         icon: "error",
            //                         title: "Oops...",
            //                         text: err.responseJSON.message,
            //                     });
            //                 },
            //             });
            //         }
            //     });
            // }
        </script>
    @endpush
@endsection
