@extends('admin.layouts.app')
@section('title', 'Roles & Permissions')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', 'Roles & Permissions', 'Permission']])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of Permission</h4>
                        @can('permission-add')
                            <a data-toggle="modal" data-bs-target="#createModal" data-bs-toggle="modal" class="btn btn-primary"
                                style="min-width: 200px">
                                <i class="fa fa-plus"></i> Add New
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
                    <table id="data_table" class="table table-bordered bordered table-centered mb-0 w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Module</th>
                                <th>Name</th>
                                <th class="no-sort" width="60px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td>{{ ucfirst(str_replace('-', ' ', $permission->module)) }}</td>
                                    <td>{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</td>
                                    <td>
                                        @can('permission-edit')
                                            <div class="d-flex align-items-center gap-3 fs-6">
                                                <a data-route="{{ route('admin.permission.edit', $permission->id) }}"
                                                    data-value="{{ $permission->id }}" onclick="ajaxEdit(this)"
                                                    href="javascript:void(0)" class="text-warning" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" title=""
                                                    data-bs-original-title="Edit Permission" aria-label="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            @endcan
                                            @if ($permission->removable && user()->can('permission-delete'))
                                                <a data-route="{{ route('admin.permission.destroy', $permission->id) }}"
                                                    data-value="{{ $permission->id }}" onclick="ajaxDelete(this, 'nodt')"
                                                    href="javascript:void(0)" class="text-danger" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" title=""
                                                    data-bs-original-title="Delete Permission" aria-label="Delete">
                                                    <i class="fa-solid fa-trash"></i>
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
                    <h1 class="modal-title fs-5" id="createModalLabel">Add Permission</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.permission.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="module" class="form-label required">Module </label>
                                <input type="search" name="module" class="form-control" value="{{ old('module') }}"
                                    required />
                                @if ($errors->has('module'))
                                    <div class="alert alert-danger">{{ $errors->first('module') }}</div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label for="name" class="form-label required">Name </label>
                                <input type="search" name="name" class="form-control" value="{{ old('name') }}"
                                    required />
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


