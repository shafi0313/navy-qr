@extends('admin.layouts.app')
@php
    $pageTitle = '4.0 - Final Medical';
    $folder = 'final_medical';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => $pageTitle, 'menuName' => 7])

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
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label" for="is_final_pass">Final Medical Filter</label>
                                    <select name="is_final_pass" class="form-control w-100 is_final_pass compact"
                                        id="is_final_pass">
                                        <option value="">All</option>
                                        <option value="null">Pending</option>
                                        <option value="1">Fit</option>
                                        <option value="0">Unfit</option>
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
                                team: $('.team').val(),
                                exam_date: $('.exam_date').val(),
                                is_final_pass: $('.is_final_pass').val(),
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
                            data: 'medical',
                            name: 'medical',
                            title: 'Primary Medical',
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
                            title: 'Remarks',
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
                    order: [],
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
