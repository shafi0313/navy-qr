@extends('admin.layout.master')

@section('title', ucfirst($role->name).' '.__('app.manage').' '.__('app.permissions'))
@section('content')
<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 class="page-title">@lang('app.manage') @lang('role.role') @lang('app.permissions')</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.role.index') }}">@lang('role.role')</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('role.role')
                            @lang('app.permissions')</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="form-horizontal" method="POST"
                                action="{{route('admin.role.permission',$role->id)}}">
                                @csrf
                                <div class="form-row">
                                    @foreach($allpermissions as $allpermission)
                                    @foreach($allpermission as $perm)
                                    <div class="col-3 my-2">
                                        {{-- <div class="icheck-primary"> --}}
                                            <input type="checkbox" name="permissions[]" value="{{$perm->name}}"
                                                id="role{{$perm->id}}" {{in_array($perm->name, $permissions) ? 'checked'
                                            : '' }}>
                                            <label for="role{{$perm->id}}">
                                                {{ucfirst(str_replace('-', ' ', $perm->name))}}
                                            </label>
                                        {{-- </div> --}}
                                    </div>
                                    @endforeach
                                    @endforeach
                                </div>
                                <div class="col-sm-8 mx-auto">
                                    <button type="submit" class="btn btn-primary w-100">
                                        {{__('app.update')}}
                                        {{__('app.permission')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
