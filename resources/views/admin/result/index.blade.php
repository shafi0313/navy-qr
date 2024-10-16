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
                    }, {
                        data: 'serial_no',
                        name: 'serial_no',
                        title: 'Roll No',
                    }, {
                        data: 'eligible_district',
                        name: 'eligible_district',
                        title: 'District',
                    }, {
                        data: 'name',
                        name: 'name',
                        title: 'Name',
                    }, {
                        data: 'candidate_designation',
                        name: 'candidate_designation',
                        title: 'Branch',
                    }, {
                        data: 'bangla',
                        name: 'bangla',
                        title: 'Bangla',
                    }, {
                        data: 'english',
                        name: 'english',
                        title: 'english',
                    }, {
                        data: 'math',
                        name: 'math',
                        title: 'math',
                    }, {
                        data: 'science',
                        name: 'science',
                        title: 'science',
                    }, {
                        data: 'general_knowledge',
                        name: 'general_knowledge',
                        title: 'general knowledge',
                    }, {
                        data: 'written',
                        name: 'written',
                        title: 'Written',
                        className: 'text-center',
                    }, {
                        data: 'total_viva',
                        name: 'total_viva',
                        title: 'Viva',
                        className: 'text-center',
                    }, {
                        data: 'remark',
                        name: 'remark',
                        title: 'Remark',
                    }, {
                        data: 'medical',
                        name: 'medical',
                        title: 'Pre. Medical',
                        className: 'text-center',
                    }, {
                        data: 'final',
                        name: 'final',
                        title: 'Final M.',
                        className: 'text-center',
                    }, ],
                    scroller: {
                        loadingIndicator: true
                    },
                    order: [
                        [1, 'asc']
                    ],

                    // Add export buttons
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'csvHtml5',
                            title: 'Application Data',
                            exportOptions: {
                                columns: ':visible',
                                modifier: {
                                    search: 'applied',
                                    order: 'applied',
                                    page: 'all' // Export all data
                                }
                            },
                            action: function(e, dt, button, config) {
                                dt.one('preXhr', function(e, settings, data) {
                                    data.length = -1; // Get all records from the server
                                }).one('xhr', function(e, settings, json) {
                                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt,
                                        button, config);
                                    data.length = table.page
                                .len(); // Reset the length back to the original
                                });
                                dt.ajax.reload();
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            title: 'Application Data',
                            exportOptions: {
                                columns: ':visible',
                                modifier: {
                                    search: 'applied',
                                    order: 'applied',
                                    page: 'all' // Export all data
                                }
                            },
                            action: function(e, dt, button, config) {
                                dt.one('preXhr', function(e, settings, data) {
                                    data.length = -1; // Get all records from the server
                                }).one('xhr', function(e, settings, json) {
                                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e,
                                        dt, button, config);
                                    data.length = table.page
                                .len(); // Reset the length back to the original
                                });
                                dt.ajax.reload();
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
                                    page: 'all' // Export all data
                                }
                            },
                            orientation: 'landscape',
                            pageSize: 'A4',
                            action: function(e, dt, button, config) {
                                dt.one('preXhr', function(e, settings, data) {
                                    data.length = -1; // Get all records from the server
                                }).one('xhr', function(e, settings, json) {
                                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt,
                                        button, config);
                                    data.length = table.page
                                .len(); // Reset the length back to the original
                                });
                                dt.ajax.reload();
                            }
                        }
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
