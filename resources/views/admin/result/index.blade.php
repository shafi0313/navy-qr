@extends('admin.layouts.app')
@php
    $pageTitle = 'All Applicants';
    $folder = 'result';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', $pageTitle, 'Index']])
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet" />

    <!-- DataTables Buttons CSS -->
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">{{ $pageTitle }}</h4>
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
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

        <!-- DataTables Buttons JS and Dependencies -->
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <!-- JSZip for Excel export -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
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
                    lengthMenu: [
                        [10, 25, 50, 100, 200, 500, 1000, 10000, -1],
                        [10, 25, 50, 100, 200, 500, 1000, 10000, 'All']
                    ],
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
                            searchable: false
                        },
                        {
                            data: 'serial_no',
                            name: 'serial_no',
                            title: 'Roll No'
                        },
                        {
                            data: 'eligible_district',
                            name: 'eligible_district',
                            title: 'District'
                        },
                        {
                            data: 'name',
                            name: 'name',
                            title: 'Name'
                        },
                        {
                            data: 'candidate_designation',
                            name: 'candidate_designation',
                            title: 'Branch'
                        },
                        {
                            data: 'specialty',
                            name: 'specialty',
                            title: 'Specialty'
                        },
                        {
                            data: 'medical',
                            name: 'medical',
                            title: 'Pre. Medical',
                            className: 'text-center'
                        },
                        // {
                        //     data: 'p_m_remark',
                        //     name: 'p_m_remark',
                        //     title: 'Pre. M. Re.',
                        // },
                        {
                            data: 'bangla',
                            name: 'bangla',
                            title: 'Bangla'
                        },
                        {
                            data: 'english',
                            name: 'english',
                            title: 'English'
                        },
                        {
                            data: 'math',
                            name: 'math',
                            title: 'Math'
                        },
                        {
                            data: 'science',
                            name: 'science',
                            title: 'Science'
                        },
                        {
                            data: 'general_knowledge',
                            name: 'general_knowledge',
                            title: 'General Knowledge'
                        },
                        {
                            data: 'written',
                            name: 'written',
                            title: 'Written',
                            className: 'text-center'
                        },
                        {
                            data: 'final',
                            name: 'final',
                            title: 'Final M.',
                            className: 'text-center'
                        },
                        // {
                        //     data: 'f_m_remark',
                        //     name: 'f_m_remark',
                        //     title: 'Final M. Re.',
                        // },
                        {
                            data: 'total_viva',
                            name: 'total_viva',
                            title: 'Viva',
                            className: 'text-center'
                        },
                        {
                            data: 'remark',
                            name: 'remark',
                            title: 'Remark'
                        },
                    ],
                    scroller: {
                        loadingIndicator: true
                    },
                    order: [
                        [1, 'asc']
                    ],
                    // Adjusted DOM structure
                    dom: '<"top"lfB>rt<"bottom"ip>',
                    buttons: [{
                            extend: 'excelHtml5',
                            title: 'Application Data',
                            exportOptions: {
                                columns: ':visible',
                                modifier: {
                                    search: 'applied',
                                    order: 'applied',
                                    page: 'all'
                                }
                            },
                            action: function(e, dt, button, config) {
                                const originalServerSide = dt.settings()[0].oFeatures.bServerSide;
                                dt.settings()[0].oFeatures.bServerSide = false;

                                $.ajax({
                                    url: dt.ajax.url(),
                                    data: dt.ajax.params(),
                                    success: (json) => {
                                        $.fn.dataTable.ext.buttons.excelHtml5.action.call(
                                            this, e, dt, button, config);
                                        dt.settings()[0].oFeatures.bServerSide =
                                            originalServerSide;
                                        dt.ajax.reload(null, false);
                                    },
                                    error: function(xhr, error, thrown) {
                                        console.error('Error fetching data for export:',
                                            error);
                                    }
                                });
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Application Data',
                            exportOptions: {
                                columns: ':visible',
                                modifier: {
                                    search: 'applied',
                                    order: 'applied',
                                    page: 'all'
                                }
                            },
                            orientation: 'landscape',
                            pageSize: 'A4',
                            action: function(e, dt, button, config) {
                                const originalServerSide = dt.settings()[0].oFeatures.bServerSide;
                                dt.settings()[0].oFeatures.bServerSide = false;

                                $.ajax({
                                    url: dt.ajax.url(),
                                    data: dt.ajax.params(),
                                    success: (json) => {
                                        $.fn.dataTable.ext.buttons.pdfHtml5.action.call(
                                            this, e, dt, button, config);
                                        dt.settings()[0].oFeatures.bServerSide =
                                            originalServerSide;
                                        dt.ajax.reload(null, false);
                                    },
                                    error: function(xhr, error, thrown) {
                                        console.error('Error fetching data for export:',
                                            error);
                                    }
                                });
                            }
                        }
                    ]
                });

                // Filter functionality
                $(".filter").find('select').on('change', function() {
                    table.draw();
                });

                $(".filter").find('a').on('click', function(e) {
                    e.preventDefault();
                    $(".filter").find('select').val('').trigger('change');
                    table.draw();
                });
            });


















            //             $(function() {
            //     let table = $('#data_table').DataTable({
            //         processing: true,
            //         serverSide: true,
            //         deferRender: true,
            //         ordering: true,
            //         responsive: true,
            //         scrollY: 400,
            //         scrollX: true,
            //         ajax: {
            //             url: "{{ route('admin.' . $route . '.index') }}",
            //             type: "get",
            //             data: function(d) {
            //                 return $.extend(d, {
            //                     district: $('.district').val(),
            //                     exam_date: $('.exam_date').val()
            //                 });
            //             },
            //         },
            //         columns: [{
            //                             data: 'DT_RowIndex',
            //                             name: 'DT_RowIndex',
            //                             className: 'text-center',
            //                             width: '60px',
            //                             title: 'SL',
            //                             orderable: false,
            //                             searchable: false,
            //                         },
            //                         {
            //                             data: 'serial_no',
            //                             name: 'serial_no',
            //                             title: 'Roll No',
            //                         },
            //                         {
            //                             data: 'eligible_district',
            //                             name: 'eligible_district',
            //                             title: 'District',
            //                         },
            //                         {
            //                             data: 'name',
            //                             name: 'name',
            //                             title: 'Name',
            //                         },
            //                         {
            //                             data: 'candidate_designation',
            //                             name: 'candidate_designation',
            //                             title: 'Branch',
            //                         },
            //                         {
            //                             data: 'bangla',
            //                             name: 'bangla',
            //                             title: 'Bangla',
            //                         },
            //                         {
            //                             data: 'english',
            //                             name: 'english',
            //                             title: 'english',
            //                         },
            //                         {
            //                             data: 'math',
            //                             name: 'math',
            //                             title: 'math',
            //                         },
            //                         {
            //                             data: 'science',
            //                             name: 'science',
            //                             title: 'science',
            //                         },
            //                         {
            //                             data: 'general_knowledge',
            //                             name: 'general_knowledge',
            //                             title: 'general knowledge',
            //                         },
            //                         {
            //                             data: 'written',
            //                             name: 'written',
            //                             title: 'Written',
            //                             className: 'text-center',
            //                         },
            //                         {
            //                             data: 'total_viva',
            //                             name: 'total_viva',
            //                             title: 'Viva',
            //                             className: 'text-center',
            //                         },
            //                         {
            //                             data: 'remark',
            //                             name: 'remark',
            //                             title: 'Remark',
            //                         },
            //                         {
            //                             data: 'medical',
            //                             name: 'medical',
            //                             title: 'Pre. Medical',
            //                             className: 'text-center',
            //                         },
            //                         {
            //                             data: 'final',
            //                             name: 'final',
            //                             title: 'Final M.',
            //                             className: 'text-center',
            //                         },
            //                     ],
            //         scroller: {
            //             loadingIndicator: true
            //         },
            //         order: [[1, 'asc']],

            //         // Add the export buttons
            //         dom: 'Bfrtip',
            //         buttons: [
            //             {
            //                 extend: 'csvHtml5',
            //                 title: 'Application Data',
            //                 exportOptions: {
            //                     columns: ':visible'
            //                 }
            //             },
            //             {
            //                 extend: 'excelHtml5',
            //                 title: 'Application Data',
            //                 exportOptions: {
            //                     columns: ':visible'
            //                 }
            //             },
            //             {
            //                 extend: 'pdfHtml5',
            //                 title: 'Application Data',
            //                 exportOptions: {
            //                     columns: ':visible'
            //                 }
            //             },
            //             {
            //                 extend: 'print',
            //                 title: 'Application Data',
            //                 exportOptions: {
            //                     columns: ':visible'
            //                 }
            //             }
            //         ]
            //     });

            //     $(".filter").find('select').on('change', function() {
            //         table.draw();
            //     });

            //     $(".filter").find('a').on('click', function(e) {
            //         e.preventDefault();
            //         $(".filter").find('select').val('').trigger('change');
            //         table.draw();
            //     });
            // });












            // $(function() {
            //     let table = $('#data_table').DataTable({
            //         processing: true,
            //         serverSide: true,
            //         deferRender: true,
            //         ordering: true,
            //         responsive: true,
            //         scrollY: 400,
            //         scrollX: true,
            //         ajax: {
            //             url: "{{ route('admin.' . $route . '.index') }}",
            //             type: "get",
            //             data: function(d) {
            //                 return $.extend(d, {
            //                     district: $('.district').val(),
            //                     exam_date: $('.exam_date').val()
            //                 });
            //             },
            //         },
            //         columns: [{
            //                 data: 'DT_RowIndex',
            //                 name: 'DT_RowIndex',
            //                 className: 'text-center',
            //                 width: '60px',
            //                 title: 'SL',
            //                 orderable: false,
            //                 searchable: false,
            //             },
            //             {
            //                 data: 'serial_no',
            //                 name: 'serial_no',
            //                 title: 'Roll No',
            //             },
            //             {
            //                 data: 'eligible_district',
            //                 name: 'eligible_district',
            //                 title: 'District',
            //             },
            //             {
            //                 data: 'name',
            //                 name: 'name',
            //                 title: 'Name',
            //             },
            //             {
            //                 data: 'candidate_designation',
            //                 name: 'candidate_designation',
            //                 title: 'Branch',
            //             },
            //             {
            //                 data: 'bangla',
            //                 name: 'bangla',
            //                 title: 'Bangla',
            //             },
            //             {
            //                 data: 'english',
            //                 name: 'english',
            //                 title: 'english',
            //             },
            //             {
            //                 data: 'math',
            //                 name: 'math',
            //                 title: 'math',
            //             },
            //             {
            //                 data: 'science',
            //                 name: 'science',
            //                 title: 'science',
            //             },
            //             {
            //                 data: 'general_knowledge',
            //                 name: 'general_knowledge',
            //                 title: 'general knowledge',
            //             },
            //             {
            //                 data: 'written',
            //                 name: 'written',
            //                 title: 'Written',
            //                 className: 'text-center',
            //             },
            //             {
            //                 data: 'total_viva',
            //                 name: 'total_viva',
            //                 title: 'Viva',
            //                 className: 'text-center',
            //             },
            //             {
            //                 data: 'remark',
            //                 name: 'remark',
            //                 title: 'Remark',
            //             },
            //             {
            //                 data: 'medical',
            //                 name: 'medical',
            //                 title: 'Pre. Medical',
            //                 className: 'text-center',
            //             },
            //             {
            //                 data: 'final',
            //                 name: 'final',
            //                 title: 'Final M.',
            //                 className: 'text-center',
            //             },
            //         ],
            //         scroller: {
            //             loadingIndicator: true
            //         },
            //         order: [
            //             [1, 'asc']
            //         ]
            //     });

            //     $(".filter").find('select').on('change', function() {
            //         table.draw();
            //     });

            //     $(".filter").find('a').on('click', function(e) {
            //         e.preventDefault();
            //         $(".filter").find('select').val('').trigger('change');
            //         table.draw();
            //     });
            // });
        </script>
        {{-- Filter Get JS --}}
        @include('admin.layouts.includes.applicant-get-filter-js')
        {{-- /Filter Get JS --}}
    @endpush
@endsection
