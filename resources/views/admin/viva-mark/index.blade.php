@extends('admin.layouts.app')
@php
    $pageTitle = 'Viva Mark';
    $folder = 'viva-mark';
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
                            title: 'Final M.',
                            className: 'text-center',
                        },
                        {
                            data: 'total_viva',
                            name: 'total_viva',
                            title: 'viva',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            title: 'Action',
                            width: '60px',
                            orderable: false,
                            searchable: false
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
    @endpush
@endsection
