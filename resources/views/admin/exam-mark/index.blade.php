@extends('admin.layouts.app')
@php
    $pageTitle = 'Written Exam Mark';
    $folder = 'exam-mark';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', $pageTitle, 'Index']])

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
                            @if (in_array(user()->role_id, [1,4]))
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
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of Applicants</h4>
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
        <script>
            $(function() {
                let table = $('#data_table').DataTable({
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    ordering: true,
                    responsive: true,
                    scrollY: 400,
                    ajax: {
                        url: "{{ route('admin.' . $route . '.index') }}",
                        type: "get",
                        data: function(d) {
                            return $.extend({}, d, {
                                "gender": $('.gender').val()
                            });
                        }
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
                            title: 'Exam Date',
                        },
                        {
                            data: 'serial_no',
                            name: 'serial_no',
                            title: 'Roll no',
                        },
                        {
                            data: 'candidate_designation',
                            name: 'candidate_designation',
                            title: 'des',
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
                            title: 'Remarks',
                            className: 'text-center',
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
