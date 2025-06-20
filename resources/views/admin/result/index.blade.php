@extends('admin.layouts.app')
@php
    $pageTitle = '6.0 - Merit List';
    $folder = 'result';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => $pageTitle, 'insId' => 9])
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet" />

    <!-- DataTables Buttons CSS -->
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet" />
    <style>
        .dt-buttons {
            margin-left: 2rem;
        }

        .ssc_result {
            min-width: 50px !important;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Filter HTML --}}
                    <div class="col-md-12 mb-2">
                        <div class="row justify-content-center filter align-items-end">
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="district">@lang('District')</label>
                                    <select name="district" class="form-control w-100 district" id="district">
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="ssc_gpa">GPA</label>
                                    <select name="ssc_gpa" class="form-control w-100 ssc_gpa" id="ssc_gpa">
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="ssc_group">Group</label>
                                    <select name="ssc_group" class="form-control w-100 ssc_group" id="ssc_group">
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="candidate_designation">Branch</label>
                                    <select name="candidate_designation" class="form-control w-100 candidate_designation"
                                        id="candidate_designation">
                                    </select>
                                </div>
                            </div>
                            <div class="col">
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
                            </div>
                            @if (user()->role_id == 1)
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-label" for="team">@lang('Team')</label>
                                        <select name="team" class="form-control w-100 team" id="team">
                                            <option value="">Select</option>
                                            <option value="all">All</option>
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
                        url: "{{ route('admin.' . $route . '.index') }}",
                        type: "get",
                        data: function(d) {
                            return $.extend(d, {
                                district: $('.district').val(),
                                ssc_gpa: $('.ssc_gpa').val(),
                                ssc_group: $('.ssc_group').val(),
                                candidate_designation: $('.candidate_designation').val(),
                                dob: $('.dob').val(),
                                exam_date: $('.exam_date').val(),
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
                            data: 'ssc_result',
                            name: 'ssc_result',
                            title: 'SSC GPA',
                            className: 'ssc_result',
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
                        // {
                        //     data: 'specialty',
                        //     name: 'specialty',
                        //     title: 'Specialty'
                        // },
                        {
                            data: 'medical',
                            name: 'medical',
                            title: 'Pre. Medical',
                            className: 'text-center'
                        },
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
                            title: 'GK'
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
                            data: 'height',
                            name: 'height',
                            title: 'Height',
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
                            data: 'viva_remark',
                            name: 'viva_remark',
                            title: 'Remark',
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
                            title: 'Application Data',
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
            $('#ssc_gpa').select2({
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
                            type: 'getGpa',
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            })
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
            $('#dob').select2({
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
                            type: 'getBob',
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            })
            $('#height').select2({
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
                            type: 'getHeight',
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
