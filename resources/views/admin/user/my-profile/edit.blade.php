@extends('admin.layouts.app')
@section('title', 'My Profile')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => 'My Profile'])
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body position-relative">
                    <form onsubmit="ajaxStore(event, this, 'editModal')"
                        action="{{ route('admin.admin-users.update', user()->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="row gy-2">
                            <div class="col-md-6">
                                <label for="name" class="form-label required">Name </label>
                                <input type="text" name="name" value="{{ old('name') ?? user()->name }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label required">Email </label>
                                <input type="email" name="email" value="{{ old('email') ?? user()->email }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="user_name" class="form-label">user name </label>
                                <input type="text" name="user_name" value="{{ old('name_name') ?? user()->name_name }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label required">Phone </label>
                                <input type="text" name="phone" value="{{ old('phone') ?? user()->phone }}"
                                    class="form-control" oninput="phoneIn(event)">
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender *</label>
                                <select class="form-select" name="gender">
                                    <option selected disabled value="">Choose...</option>
                                    @foreach ($genders as $key => $gender)
                                        <option value="{{ $key }}" @selected($key == $myProfile->gender)>
                                            {{ $gender }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label required">address </label>
                                <input type="text" name="address" value="{{ old('address') ?? user()->address }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Old Image </label>
                                <img src="{{ imagePath('user', user()->image) }}" width="100px">
                            </div>
                            <div class="col-md-6">
                                <label for="image" class="form-label">image </label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Old Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="col-md-12 text-center mt-2">
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <button type="submit" class="btn btn-warning">Update</button>
                            </div>
                        </div>
                    </form>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col-->
    </div>

    @push('scripts')
    @endpush
@endsection
