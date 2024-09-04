@extends('auth.app')
@section('content')
    <div class="text-center w-75 m-auto">
        <img src="assets/images/users/avatar-1.jpg" height="64" alt="user-image" class="rounded-circle shadow">
        <h4 class="text-dark-50 text-center mt-3">{{ auth()->user()->name }}</h4>
        <p class="text-muted mb-4">Enter your password to access the admin.</p>
    </div>

    <form action="{{ route('login.unlock') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input class="form-control" type="password" required="" id="password" placeholder="Enter your password">
        </div>

        <div class="mb-0 text-center">
            <button class="btn btn-primary" type="submit">Log In</button>
        </div>
    </form>
@endsection
