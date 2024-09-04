@extends('auth.app')
@section('content')
    <div class="text-center w-75 m-auto">
        <h4 class="text-dark-50 text-center pb-0">Sign In</h4>
        <p class="text-muted mb-4">Enter your email address and password to access admin panel.
        </p>
    </div>

    <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="mb-3">
            <label for="emailaddress" class="form-label">Email address</label>
            <input type="email" name="email" value="{{ old('username') ?: old('email') }}" class="form-control" id="emailaddress" required="" placeholder="Enter your email">
        </div>

        <div class="mb-3">
            <a href="{{ route('password.request') }}" class="text-muted float-end fs-12">Forgot your
                password?</a>
            <label for="password" class="form-label">Password</label>
            <div class="input-group input-group-merge">
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password">
                <div class="input-group-text" data-password="false">
                    <span class="password-eye"></span>
                </div>
            </div>
        </div>

        <div class="mb-3 mb-3">
            <div class="form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="checkbox-signin" checked>
                <label class="form-check-label" for="checkbox-signin">Remember me</label>
            </div>
        </div>

        <div class="mb-3 mb-0 text-center">
            <button class="btn btn-primary" type="submit"> Log In </button>
        </div>

    </form>
@endsection
