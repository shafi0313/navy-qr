@extends('admin.layouts.app')
@php
    $pageTitle = '5.0 - Final Viva & HBsAg/Dope Test';
    $folder = 'viva-mark';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => $pageTitle])
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
<style>
    .ssc_result {
        min-width: 50px !important;
    }
</style>
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
                    scrollX: true,
                    ajax: {
                        url: "{{ route('admin.' . $route . '.index') }}",
                        type: "get",
                        data: function(d) {
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
                            data: 'ssc_result',
                            name: 'ssc_result',
                            title: 'SSC GPA',
                            className: 'ssc_result',
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
                            data: 'bangla',
                            name: 'bangla',
                            title: 'Bangla',
                        },
                        {
                            data: 'english',
                            name: 'english',
                            title: 'english',
                        },
                        {
                            data: 'math',
                            name: 'math',
                            title: 'math',
                        },
                        {
                            data: 'science',
                            name: 'science',
                            title: 'science',
                        },
                        {
                            data: 'general_knowledge',
                            name: 'general_knowledge',
                            title: 'general knowledge',
                        },
                        {
                            data: 'written',
                            name: 'written',
                            title: 'Total Written',
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
                            data: 'dup_test',
                            name: 'dup_test',
                            title: 'Dope Test',
                        },
                        {
                            data: 'exam_mark.viva_remark',
                            name: 'exam_mark.viva_remark',
                            title: 'Remark',
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
