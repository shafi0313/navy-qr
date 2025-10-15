@extends('admin.layouts.app')
@php
    $pageTitle = '5.0 - Final Viva & HBsAg/Dope Test';
    $folder = 'viva-mark';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => $pageTitle, 'menuName' => 8])
    {{-- @include('admin.layouts.includes.table-option') --}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Filter HTML --}}
                    {{-- @include('admin.layouts.includes.applicant-get-filter-html') --}}
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
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="viva">Viva Filter</label>
                                    <select name="viva" class="form-control w-100 compact viva" id="viva">
                                        <option value="">All</option>
                                        <option value="null">Pending</option>
                                        <option value="pass">Passed</option>
                                        <option value="fail">Failed</option>
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
                    ajax: {
                        url: "{{ route('admin.' . $route . '.index') }}",
                        type: "get",
                        data: function(d) {
                            return $.extend(d, {
                                district: $('.district').val(),
                                team: $('.team').val(),
                                exam_date: $('.exam_date').val(),
                                viva: $('.viva').val()
                            });
                        },
                    },
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
                            title: 'Primary Medical',
                            className: 'text-center',
                        },
                        {
                            data: 'written_mark',
                            name: 'written_mark',
                            title: 'written Mark',
                            className: 'written_mark',
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
                            title: 'Final Medical',
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
                            className: 'text-center',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    scroller: {
                        loadingIndicator: true
                    },
                    order: []
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
