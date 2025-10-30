@extends('admin.layouts.app')
@section('title', 'User')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => 'Users', 'insId' => 2])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">List of Users</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="fa-solid fa-plus"></i> Add New
                        </button>
                    </div>
                    {{-- Filter HTML --}}
                    <div class="row justify-content-center filter align-items-end">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="team">Team</label>
                                <select name="team" class="form-control w-100 team" id="team">
                                    <option value="all">All</option>
                                    <option value="">Without Team</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <a href="" class="btn btn-danger">Clear</a>
                            </div>
                        </div>
                    </div>
                    {{-- /Filter HTML --}}

                    <table id="data_table" class="table table-bordered bordered table-centered mb-0 w-100">
                        <thead></thead>
                        <tbody></tbody>
                    </table>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @include('admin.user.admin.create')

    @push('scripts')
        <script>
            $(function() {
                let table = $('#data_table').DataTable({
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    ordering: true,
                    // responsive: true,
                    scrollX: true,
                    scrollY: 400,
                    ajax: {
                        url: "{{ route('admin.admin-users.index') }}",
                        type: "get",
                        data: function(d) {
                            return $.extend(d, {
                                team: $('.team').val(),
                            });
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            title: 'SL',
                            className: "text-center",
                            width: "17px",
                            searchable: false,
                            orderable: false,
                        },
                        {
                            data: 'name',
                            name: 'name',
                            title: 'Name'
                        },
                        {
                            data: 'email',
                            name: 'email',
                            title: 'Email'
                        },
                        {
                            data: 'role.name',
                            name: 'role.name',
                            title: 'Role'
                        },
                        {
                            data: 'team',
                            name: 'team',
                            title: 'Team',
                            className: 'text-center',
                        },
                        {
                            data: 'mobile',
                            name: 'mobile',
                            title: 'mobile',
                        },
                        {
                            data: 'is_active',
                            name: 'is_active',
                            title: 'Status',
                            className: 'text-center',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            title: 'Action',
                            className: "text-center",
                            width: "60px",
                            orderable: false,
                            searchable: false,
                        },
                    ],
                    scroller: {
                        loadingIndicator: true
                    },
                    order: [
                        [4, 'asc'],
                        [1, 'asc']
                    ],
                });
                $(".filter").find('select').on('change', function() {
                    table.draw();
                });

                $(".filter").find('a').on('click', function(e) {
                    e.preventDefault();
                    $(".filter").find('select').val('all').trigger('change');
                    table.draw();
                });
            });
        </script>
    @endpush
@endsection
