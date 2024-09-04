@extends('admin.layout.master')

@section('title', __('role.title'))
@section('content')
    <div class="main-content app-content mt-0">
        <div class="side-app">
            {{-- <!-- CONTAINER --> --}}
            <div class="main-container container-fluid">
                {{-- <!-- PAGE-HEADER --> --}}
                <div class="page-header">
                    <h1 class="page-title">@lang('app.manage') @lang('role.roles')</h1>
                    <div>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('role.role')</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <!-- right column -->
                    <div class="col-md-7 mx-auto">
                        <form class="form-horizontal" method="POST" action="{{ route('admin.role.store') }}">
                            @csrf
                            <!-- Role Creation -->
                            <div class="card">
                                <div class="card-header with-border">
                                    {{ __('app.create_role') }}
                                </div>
                                <div class="card-body">
                                    <div class="form-group ">
                                        <div class="col-sm-12 mx-auto">
                                            <input type="text" name="name" class="form-control" id="password"
                                                placeholder="{{ __('app.name_of_role') }}">
                                            @if ($errors->has('role'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('role') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-8 mx-auto">
                                        <button type="submit"
                                            class="btn btn-success text-white btn-outline-secondary col-sm-12">{{ __('app.create_role') }}</button>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- Role Creation -->
                            </div>
                            <!-- /.card-body -->
                        </form>
                        <!-- form end -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
@endsection
