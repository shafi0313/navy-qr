@extends('admin.layouts.app')
@section('title', 'Roles & Permissions')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', 'Roles & Permissions', 'Role']])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of Roles</h4>
                        @if (user()->id == 1)
                            <a href="{{ route('admin.permission.index') }}" class="btn btn-primary ms-auto me-2"
                                style="min-width: 200px">
                                <i class="fa fa-plus"></i> Manage Permission
                            </a>
                        @endif
                        @can('role-add')
                            <a data-toggle="modal" data-bs-target="#createModal" data-bs-toggle="modal" class="btn btn-primary"
                                style="min-width: 200px">
                                <i class="fa fa-plus"></i> Add Role
                            </a>
                        @endcan
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <table id="data_table" class="table table-bordered table-centered mb-0 w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Total User</th>
                                <th>Created at</th>
                                <th class="no-sort" width="60px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ ucfirst($role->name) }}</td>
                                    <td>{{ $role->users->count() }}</td>
                                    <td>{{ $role->created_at->format('d M, Y h:i A') }}</td>
                                    <td class="">
                                        <div class="d-flex align-items-center gap-3 fs-6">
                                            @can('permission-change')
                                                <a href="{{ route('admin.role.show', $role->id) }}" class="text-success" title="Update Permission">
                                                    <i class="fa-solid fa-shield"></i>
                                                </a>
                                            @endcan
                                            @can('role-edit')
                                                <a data-route="{{ route('admin.role.edit', $role->id) }}"
                                                    data-value="{{ $role->id }}" onclick="ajaxEdit(this)"
                                                    href="javascript:void(0)" class="text-primary" title="Edit Role">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endcan
                                            @if ($role->removable && user()->can('role-delete'))
                                                <a data-route="{{ route('admin.role.destroy', $role->id) }}"
                                                    data-value="{{ $role->id }}" onclick="ajaxDelete(this, 'nodt')"
                                                    href="javascript:void(0)" class="text-danger" title="Delete Role">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @can('role-add')
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createModalLabel">Add Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.role.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="name" class="form-label required">Name </label>
                                <input type="search" name="name" id="name" class="form-control"
                                    value="{{ old('name') }}" required />
                                @if ($errors->has('name'))
                                    <div class="alert alert-danger">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan

    @push('scripts')
    @endpush
@endsection




