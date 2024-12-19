@extends('auth.app')
@section('content')
    <div class="container">
        <h3 class="text-center mb-4">Two-Factor Authentication</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <p>
                        Your OTP will expire in:
                        <span id="countdown" data-expiry="{{ \Carbon\Carbon::parse($otpExpiresAt)->timestamp }}"></span>
                    </p>
                </div>
                <div class="col-md-12">
                    <div class="form-group mb-2">
                        <label for="otp">Enter the OTP sent to your phone:</label>
                        <input type="text" name="otp" id="otp" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Verify OTP</button>
                </div>
            </div>
        </form>
    </div>
@push('scripts')

<script>
    $(document).ready(function () {
        const countdownElement = $('#countdown');
        const expiryTimestamp = countdownElement.data('expiry'); // Get expiry timestamp
        const updateCountdown = () => {
            const now = Math.floor(Date.now() / 1000); // Current timestamp in seconds
            const remaining = Math.max(0, expiryTimestamp - now); // Remaining seconds
            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;

            countdownElement.text(`${minutes}:${seconds.toString().padStart(2, '0')}`);

            if (remaining > 0) {
                setTimeout(updateCountdown, 1000); // Update every second
            } else {
                countdownElement.text('Expired');
            }
        };
        updateCountdown();
    });
</script>
@endpush

@endsection
