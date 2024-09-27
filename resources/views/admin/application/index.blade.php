@extends('admin.layouts.app')
@php
    $pageTitle = 'Application';
    $folder = 'application';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', $pageTitle, 'Index']])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of {{ $pageTitle }}s</h4>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="row justify-content-center filter align-items-end">
                            <div class="col">
                                <div class="form-group my-3">
                                    <label class="form-label" for="gender">@lang('Gender')</label>
                                    <select name="gender" class="gender select_2 form-control w-100">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group my-3">
                                    <a href="" class="btn btn-danger">Clear</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="data_table" class="table table-bordered table-centered mb-0 w-100">
                        <thead></thead>
                        <tbody></tbody>
                    </table>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    {{-- @include('admin.' . $folder . '.create', ['pageTitle' => $pageTitle, 'route' => $route]) --}}

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
                            data: 'serial_no',
                            name: 'serial_no',
                            title: 'serial no',
                        },
                        {
                            data: 'name',
                            name: 'name',
                            title: 'Name',
                        },
                        {
                            data: 'medical',
                            name: 'medical',
                            title: 'p. medical',
                            className: 'text-center',
                        },
                        {
                            data: 'written',
                            name: 'written',
                            title: 'written',
                            className: 'text-center',
                        },
                        {
                            data: 'final',
                            name: 'final',
                            title: 'final m.',
                            className: 'text-center',
                        },
                        {
                            data: 'viva',
                            name: 'viva',
                            title: 'viva',
                            className: 'text-center',
                        },
                        // {
                        //     data: 'action',
                        //     name: 'action',
                        //     title: 'Action',
                        //     width: '60px',
                        //     orderable: false,
                        //     searchable: false
                        // },
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
    @endpush
@endsection
