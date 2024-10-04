@extends('admin.layouts.app')
@php
    $pageTitle = 'Total Applicants';
    $folder = 'application';
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
                        <h4 class="card-title">{{ $pageTitle }}</h4>
                    </div>
                    @include('admin.layouts.includes.applicant-get-filter-html')
                    <table id="data_table" class="table table-bordered table-centered mb-0 w-100">
                        <thead></thead>
                        <tbody></tbody>
                    </table>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    {{-- @include('admin.' . $folder . '.create', ['pageTitle' => $pageTitle, 'route' => $route]) --}}

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
                            console.log($('.exam_date').val());
                            // Use $.extend to combine the original data (d) with custom parameters
                            return $.extend(d, {
                                district: $('.district').val(),
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
                        {
                            data: 'remark',
                            name: 'remark',
                            title: 'remark',
                        },
                        // {
                        //     data: 'medical',
                        //     name: 'medical',
                        //     title: 'p. medical',
                        //     className: 'text-center',
                        // },
                        // {
                        //     data: 'written',
                        //     name: 'written',
                        //     title: 'written',
                        //     className: 'text-center',
                        // },
                        // {
                        //     data: 'final',
                        //     name: 'final',
                        //     title: 'final m.',
                        //     className: 'text-center',
                        // },
                        // {
                        //     data: 'viva',
                        //     name: 'viva',
                        //     title: 'viva',
                        //     className: 'text-center',
                        // },
                        // {
                        //     data: 'action',
                        //     name: 'action',
                        //     title: 'Action',
                        //     width: '60px',
                        //     orderable: false,
                        //     searchable: false
                        // },
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
        @include('admin.layouts.includes.applicant-get-filter-js')
    @endpush
@endsection
