@extends('admin.layouts.app')
@php
    $pageTitle = '3.2 - Written Exam';
    $folder = 'exam-mark';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => $pageTitle, 'menuName' => 6])
    <!-- DataTables Buttons CSS -->
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet" />
    {{-- @include('admin.layouts.includes.table-option') --}}

    {{-- <div class="row">
        <div class="col-12">
            <div class="card">
                <form onsubmit="ajaxStoreModal(event, this, 'createModal')" action="{{ route('admin.' . $route . '.store') }}"
                    method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row gy-2 mb-3">
                            <div class="col-md-6">
                                <label for="application_id" class="form-label required">Applicant </label>
                                <select name="application_id" id="application_id" class="form-select"></select>
                            </div>
                        </div>
                        <div class="row gy-2">
                            @if (in_array(user()->role_id, [1, 4]))
                                <div class="col-md-2">
                                    <label for="bangla" class="form-label required">Bangla </label>
                                    <input type="number" step="any" name="bangla" id="bangla" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label for="english" class="form-label required">English </label>
                                    <input type="number" step="any" name="english" id="english" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label for="math" class="form-label required">math </label>
                                    <input type="number" step="any" name="math" id="math" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label for="science" class="form-label required">science </label>
                                    <input type="number" step="any" name="science" id="science" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label for="general_knowledge" class="form-label required">general knowledge </label>
                                    <input type="number" step="any" name="general_knowledge" id="general_knowledge"
                                        class="form-control" required>
                                </div>
                            @endif

                        </div>
                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div> <!-- end card-body -->
                </form>
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row --> --}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Filter HTML --}}
                    <div class="col-md-12 mb-1">
                        <div class="row justify-content-center filter align-items-end">
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="district">District</label>
                                    <select name="district" class="form-control w-100 district" id="district">
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="exam_date">Exam Date</label>
                                    <select name="exam_date" class="form-control w-100 exam_date" id="exam_date">
                                    </select>
                                </div>
                            </div>
                            @if (user()->role_id == 1)
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-label" for="team">@lang('Team')</label>
                                        <select name="team" class="form-control w-100 team" id="team">
                                            <option value="">All</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="written_exam">Result Filter</label>
                                    <select name="written_exam" class="form-control w-100 compact written_exam"
                                        id="written_exam">
                                        <option value="">All</option>
                                        <option value="pending">Pending</option>
                                        <option value="passed">Passed</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <a href="" class="btn btn-danger">Clear</a>
                                </div>
                            </div>
                        </div>
                    </div>

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
            min-width: 70px !important;
        }
    </style>
    @push('scripts')
        <!-- DataTables Buttons JS and Dependencies -->
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <!-- JSZip for Excel export -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>

        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
        @include('admin.includes.table-common-column')
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
                                team: $('.team').val(),
                                exam_date: $('.exam_date').val(),
                                written_exam: $('.written_exam').val(),
                            });
                        },
                    },
                    stateSave: true,
                    columnDefs: [{
                        orderable: false,
                        searchable: false,
                        targets: '_all'
                    }],
                    columns: [
                        ...commonColumns,
                        {
                            data: 'ssc_result',
                            name: 'ssc_result',
                            title: 'SSC GPA',
                            className: 'ssc_result',
                        },
                        {
                            data: 'medical',
                            name: 'medical',
                            title: 'Primary medical',
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
                            title: 'GK',
                        },
                        {
                            data: 'written',
                            name: 'written',
                            title: 'Remarks',
                            className: 'text-center',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            title: 'Action',
                            className: 'text-center',
                            width: '60px',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    scroller: {
                        loadingIndicator: true
                    },
                    order: [],
                    // Adjusted DOM structure
                    dom: '<"top"lfB>rt<"bottom"ip>',
                    buttons: [{
                            extend: 'excelHtml5',
                            title: null,
                            text: 'Export',
                            className: 'btn btn-success',
                            exportOptions: {
                                columns: ':visible',
                                modifier: {
                                    search: 'applied',
                                    order: 'applied',
                                    page: 'all'
                                }
                            },
                            action: function(e, dt, button, config) {
                                if (@json(!in_array(user()->role_id, [1]))) {
                                    swal({
                                        icon: "error",
                                        title: "Oops...",
                                        text: "You are not authorized to perform this action",
                                    });
                                    return false;
                                }
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
                        // {
                        //     extend: 'pdfHtml5',
                        //     title: null,
                        //     text: 'Export to PDF',
                        //     exportOptions: {
                        //         columns: ':visible',
                        //         modifier: {
                        //             search: 'applied',
                        //             order: 'applied',
                        //             page: 'all'
                        //         }
                        //     },
                        //     orientation: 'landscape',
                        //     pageSize: 'A4',
                        //     action: function(e, dt, button, config) {
                        //         if ('{{ user()->role_id }}' != 1) {
                        //             swal({
                        //                 icon: "error",
                        //                 title: "Oops...",
                        //                 text: "You are not authorized to perform this action",
                        //             });
                        //             return false;
                        //         }
                        //         const originalServerSide = dt.settings()[0].oFeatures.bServerSide;
                        //         dt.settings()[0].oFeatures.bServerSide = false;

                        //         $.ajax({
                        //             url: dt.ajax.url(),
                        //             data: dt.ajax.params(),
                        //             success: (json) => {
                        //                 $.fn.dataTable.ext.buttons.pdfHtml5.action.call(
                        //                     this, e, dt, button, config);
                        //                 dt.settings()[0].oFeatures.bServerSide =
                        //                     originalServerSide;
                        //                 dt.ajax.reload(null, false);
                        //             },
                        //             error: function(xhr, error, thrown) {
                        //                 console.error('Error fetching data for export:',
                        //                     error);
                        //             }
                        //         });
                        //     }
                        // }
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
