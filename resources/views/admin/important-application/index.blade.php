@extends('admin.layouts.app')
@php
    $pageTitle = '8.0 - All Documents Held Import & List';
    $folder = 'important-application';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => $pageTitle, 'menuName' => 10])
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet" />

    <!-- DataTables Buttons CSS -->
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet" />
    <style>
        .dt-buttons {
            margin-left: 2rem;
        }

        .remark {
            min-width: 300px !important;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>

    <div class="row gy-2 justify-content-between">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.important_application_imports.import') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div style="display: flex;">
                            <div style="flex-grow: 1">
                                <label for="file" class="form-label required">Bulk Import </label>
                                <a href="{{ asset('uploads/important-application-format.xlsx') }}" download>(Download
                                    Format)</a>
                                <input type="file" name="file" class="form-control" required>
                            </div>

                            <div style="margin-top: 28px; margin-left: 10px;">
                                <button type="submit" class="btn btn-success">Import</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form onsubmit="ajaxStoreModal(event, this, 'createModal')"
                        action="{{ route('admin.' . $route . '.store') }}" method="POST">
                        @csrf
                        <div style="display: flex;">
                            <div style="flex-grow: 1">
                                <label for="application_id" class="form-label required">Single Entry </label>
                                <select name="application_id" id="application_id" class="form-select"></select>
                            </div>
                            <div style="margin-top: 25px; margin-left: 10px;">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @if ($writtenMarks->count() > 0)
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>From Number</th>
                                        <th>Bangla</th>
                                        <th>English</th>
                                        <th>Math</th>
                                        <th>Science</th>
                                        <th>GK</th>
                                        <th class="no-sort" width="60px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($writtenMarks as $writtenMark)
                                        <tr>
                                            <td>{{ @$x += 1 }}</td>
                                            <td>{{ $writtenMark->serial_no }}</td>
                                            <td>{{ $writtenMark->bangla }}</td>
                                            <td>{{ $writtenMark->english }}</td>
                                            <td>{{ $writtenMark->math }}</td>
                                            <td>{{ $writtenMark->science }}</td>
                                            <td>{{ $writtenMark->general_knowledge }}</td>
                                            <td class="text-center">
                                                <form
                                                    action="{{ route('admin.important-application-imports.destroy', $writtenMark->id) }}"
                                                    method="post"
                                                    onclick="return confirm('Do you want to delete this data?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" title="Delete"
                                                        class="btn btn-sm btn-link btn-danger">
                                                        <i class="fa fa-times text-light"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $writtenMarks->links() }}
                        <form action="{{ route('admin.important-application-imports.store') }}" method="post">
                            @csrf @method('POST')
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <a href="{{ route('admin.important_application_imports.all_deletes') }}"
                                        onclick="return confirm('Do you want to delete all data on this page?')"
                                        class="btn btn-danger">Delete All</a>

                                    <button type="submit" onclick="return confirm('Are you sure?')"
                                        class="btn btn-primary">Post</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- @else
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info">No data found!</div>
                    </div>
                </div> --}}
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of Applicants</h4>
                    </div> --}}
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

        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>

        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
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
                    autoWidth: false,
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
                                exam_date: $('.exam_date').val()
                            });
                        },
                    },
                    columnDefs: [{
                        orderable: false,
                        searchable: false,
                        targets: '_all'
                    }],
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            className: 'text-center',
                            width: '60px',
                            title: 'SL',
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
                            data: 'dob',
                            name: 'dob',
                            title: 'Date of Birth'
                        },
                        {
                            data: 'candidate_designation',
                            name: 'candidate_designation',
                            title: 'Branch'
                        },
                        {
                            data: 'medical',
                            name: 'medical',
                            title: 'Primary Medical',
                            className: 'text-center'
                        },
                        {
                            data: 'written_mark',
                            name: 'written_mark',
                            title: 'Written Mark',
                            className: 'written_mark'
                        },
                        {
                            data: 'total_marks',
                            name: 'total_marks',
                            title: 'GT'
                        },
                        {
                            data: 'final',
                            name: 'final',
                            title: 'Final M.',
                            className: 'text-center'
                        },
                        {
                            data: 'total_viva',
                            name: 'total_viva',
                            title: 'Viva',
                            className: 'text-center'
                        },
                        {
                            data: 'ssc_group',
                            name: 'ssc_group',
                            title: 'SSC Group',
                            className: 'text-center'
                        },
                        {
                            data: 'ssc_gpa',
                            name: 'ssc_gpa',
                            title: 'SSC GPA',
                            className: 'text-center'
                        },
                        {
                            data: 'remark',
                            name: 'remark',
                            title: 'Remark',
                            className: 'remark'
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
                            title: 'Application Data',
                            text: 'Export to Excel',
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
                        //     title: 'Application Data',
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
        </script>
        {{-- Filter Get JS --}}
        @include('admin.layouts.includes.applicant-get-filter-js')
        {{-- /Filter Get JS --}}
    @endpush
@endsection
