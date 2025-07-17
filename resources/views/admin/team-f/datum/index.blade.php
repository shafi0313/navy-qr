@extends('admin.layouts.app')
@php
    $pageTitle = 'Team F Candidates Data';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => $pageTitle, 'menuName' => 9])
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet" />

    <!-- DataTables Buttons CSS -->
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet" />
    <style>
        .dt-buttons {
            margin-left: 2rem;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Filter HTML --}}
                    <div class="row justify-content-center filter align-items-end">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-label" for="ssc_group">Group</label>
                                <select name="ssc_group" class="form-control w-100 ssc_group" id="ssc_group">
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-label" for="district">@lang('District')</label>
                                <select name="district" class="form-control w-100 district" id="district">
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="ssc_gpa">GPA</label>
                                    <select name="ssc_gpa" class="form-control w-100 ssc_gpa" id="ssc_gpa">
                                    </select>
                                </div>
                            </div> --}}
                        <div class="col">
                            <div class="form-group">
                                <label class="form-label" for="candidate_designation">Branch</label>
                                <select name="candidate_designation" class="form-control w-100 candidate_designation"
                                    id="candidate_designation">
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="dob">DOB</label>
                                    <select name="dob" class="form-control w-100 dob" id="dob">
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="height">Height</label>
                                    <select name="height" class="form-control w-100 height" id="height">
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="exam_date">@lang('Exam Date')</label>
                                    <select name="exam_date" class="form-control w-100 exam_date" id="exam_date">
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="is_important">All doc. held</label>
                                    <select name="is_important" class="form-control w-100 is_important" id="is_important">
                                        <option value="">Select</option>
                                        <option value="">All</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div> --}}
                        @if (user()->role_id == 1)
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="team">@lang('Team')</label>
                                    <select name="team" class="form-control w-100 team" id="team">
                                        <option value="">Select</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col">
                            <div class="form-group">
                                <a href="" class="btn btn-danger">Clear</a>
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
                    lengthMenu: [
                        [10, 25, 50, 100, 200, 500, 1000, 10000, -1],
                        [10, 25, 50, 100, 200, 500, 1000, 10000, 'All']
                    ],
                    ajax: {
                        url: "{{ route('admin.team-f-datum.index') }}",
                        type: "get",
                        data: function(d) {
                            return $.extend(d, {
                                district: $('.district').val(),
                                ssc_group: $('.ssc_group').val(),
                                candidate_designation: $('.candidate_designation').val(),
                                team: $('.team').val(),
                                is_important: $('.is_important').val()
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
                            data: 'name',
                            name: 'name',
                            title: 'Name'
                        },
                        {
                            data: 'ssc_group',
                            name: 'ssc_group',
                            title: 'SSC Group',
                        },
                        {
                            data: 'eligible_district',
                            name: 'eligible_district',
                            title: 'District'
                        },
                        {
                            data: 'candidate_designation',
                            name: 'candidate_designation',
                            title: 'Branch'
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
                        {
                            extend: 'pdfHtml5',
                            title: null,
                            text: 'Export to PDF',
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
        </script>
        {{-- Filter Get JS --}}
        @include('admin.layouts.includes.applicant-get-filter-js')
        {{-- /Filter Get JS --}}
        <script>
            $('#ssc_group').select2({
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
                            type: 'getSSCGroups',
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            })
            $('#candidate_designation').select2({
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
                            type: 'getBranch',
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
    @endpush
@endsection
