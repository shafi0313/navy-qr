@extends('auth.app')
@section('content')
<div class="container">
    <h3 class="text-center mb-4">Two-Factor Authentication</h3>
    <form method="POST" action="{{ route('two-factor.verify') }}">
        @csrf
        <div class="form-group">
            <label for="otp">Enter the OTP sent to your phone:</label>
            <input type="text" name="otp" id="otp" class="form-control" required>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Verify OTP</button>
    </form>
</div>
@endsection
