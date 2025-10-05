@extends('admin.layouts.app')
@php
    $pageTitle = '2.0 - Primary Medical';
    $folder = 'primary_medical';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => $pageTitle, 'menuName' => 4])

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
                    <div class="col-md-12 mb-2">
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
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="is_medical_pass">Primary Medical</label>
                                    <select name="is_medical_pass" class="form-control w-100 is_medical_pass"
                                        id="is_medical_pass">
                                        <option value="">All</option>
                                        <option value="1">Fit</option>
                                        <option value="0">Unfit</option>
                                        <option value="null">Pending</option>
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
                    lengthMenu: [
                        [10, 100, 500, 5000, 10000, -1],
                        [10, 100, 500, 5000, 10000, 'All']
                    ],
                    ajax: {
                        url: "{{ route('admin.' . $route . '.index') }}",
                        type: "get",
                        data: function(d) {
                            return $.extend(d, {
                                district: $('.district').val(),
                                exam_date: $('.exam_date').val(),
                                team: $('.team').val(),
                                is_medical_pass: $('.is_medical_pass').val()
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
                            title: 'district',
                        },
                        {
                            data: 'medical',
                            name: 'medical',
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
                                if ('{{ user()->role_id }}' != 1) {
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
                    ],
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
