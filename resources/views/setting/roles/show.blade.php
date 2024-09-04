@extends('admin.layouts.app')
@section('title', 'Manage Permissions')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', 'Manage Permissions', 'Permission']])

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


                    <div class="row">
                        <div class="col-sm-4 mx-auto">
                            <p>Switch Role</p>
                            <div>
                                <select class="form-control" onchange="location = $(this).find(':selected').data('route')">
                                    @foreach ($roles as $tmp_role)
                                        <option value="{{ $tmp_role->id }}"
                                            @if ($tmp_role->id == $role->id) selected @endif
                                            data-route="{{ route('admin.role.show', $tmp_role->id) }}">
                                            {{ ucfirst($tmp_role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="{{ route('admin.role.permission', $role->id) }}">
                                @csrf
                                <div class="row py-3">
                                    <div class="col-sm-8 mx-auto">
                                        <button type="submit" class="btn btn-primary w-100">
                                            @lang('Update Permission')
                                        </button>
                                    </div>
                                </div>
                                {{-- ! Dashboard --}}
                                <div class="row my-5">
                                    <div class="col-sm-3">
                                        <label for="title"> @lang('App Moderation')</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <p>@lang('role.do-you', ['plugin' => __('nav.dashboard')])</p>
                                        <div>
                                            <input type="radio" value="access-dashboard" class="flat-red "
                                                name="permissions[]" id="access-dashbiar"
                                                @if ($permissions['access-dashboard'] == 1) checked @endif>
                                            <label class="chk-label-margin mx-1" for="access-dashbiar">
                                                @lang('app.yes')
                                            </label>
                                        </div>
                                        <div>
                                            <input type="radio" class="flat-red " name="permissions[]" id="no-access"
                                                @if ($permissions['access-dashboard'] == 0) checked @endif>
                                            <label class="chk-label-margin mx-1" for="no-access">
                                                @lang('app.no')
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Visitor Info --}}
                                {{-- @include('setting.roles.permissions.visitor-info', [
                                    'name' => 'Visitor Info Moderation',
                                    'permission' => 'visitor-info',
                                ]) --}}

                                {{-- Roles & Permission --}}
                                @include('setting.roles.permissions.roles-&-permission', [
                                    'name' => 'Roles & Permission Moderation',
                                    'permission' => 'roles-&-permission',
                                ])

                                {{-- Roles --}}
                                @include('setting.roles.permissions.role', [
                                    'name' => 'Roles Moderation',
                                    'permission' => 'role',
                                ])

                                {{-- Permission --}}
                                @include('setting.roles.permissions.permission', [
                                    'name' => 'Permission Moderation',
                                    'permission' => 'permission',
                                ])

                                {{-- App Backup --}}
                                {{-- @include('setting.roles.permissions.app-backup', [
                                    'name' => 'App Backup Moderation',
                                    'permission' => 'app-backup',
                                ]) --}}

                                {{-- User --}}
                                @include('setting.roles.permissions.user', ['name' => 'User Moderation'])




                                <div class="row py-3">
                                    <div class="col-sm-8 mx-auto">
                                        <button type="submit" class="btn btn-primary w-100">
                                            @lang('Update Permission')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
