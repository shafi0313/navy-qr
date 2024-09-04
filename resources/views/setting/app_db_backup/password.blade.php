@extends('admin.layouts.app')
@section('title', 'App/DB Backup')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', 'App/DB Backup', 'Index']])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">App/DB Backup</h4>
                    </div>
                    <form action="{{ route('admin.backup.checkPassword') }}" method="post"
                        class="row g-3 justify-content-center">
                        @csrf
                        <div class="col-md-4">
                            <label for="password" class="form-label required">Password </label>
                            <input type="password" name="password" class="form-control" value="{{ old('password') }}"
                                required />
                            @if ($errors->has('password'))
                                <div class="alert alert-danger">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="col-12 text-center">
                            <button class="btn btn-primary" type="submit">
                                Submit form
                            </button>
                        </div>
                    </form>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
    @endpush
@endsection
