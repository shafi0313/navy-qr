@extends('admin.layouts.app')
@section('title', 'User')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => 'Users', 'insId' => 2])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.rm.sd') }}" method="POST">
                        @csrf
                        <div class="row gy-2">
                            <div class="col-md-6">
                                <label for="password" class="form-label required">password </label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->


    @push('scripts')
    @endpush
@endsection
