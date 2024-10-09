<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <meta name="robots" content="noindex">
    <link rel="icon" href="{{ imagePath('logo', 'navy_fav.png') }}" type="image/x-icon" />

    {{-- <!-- Fonts and icons --> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus+SC&display=swap" rel="stylesheet">

    {{-- <!-- CSS Files --> --}}
    <link href="{{ asset('backend/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <style>
        .row {
            min-height: 100vh;
            position: relative;
            background: #f9fbfd;
        }

        .login .wrapper.wrapper-login {
            display: flex;
            justify-content: center;
            align-items: center;
            height: unset;
            padding: 15px;
        }

        .wrapper {
            min-height: 100vh;
            position: relative;
            top: 0;
            height: 100vh;
        }

        .login .wrapper.wrapper-login .container-login,
        .login .wrapper.wrapper-login .container-signup {
            width: 400px;
            background: #fff;
            padding: 60px 25px;
            border-radius: 5px;
            -webkit-box-shadow: 0 .75rem 1.5rem rgba(18, 38, 63, .03);
            -moz-box-shadow: 0 .75rem 1.5rem rgba(18, 38, 63, .03);
            box-shadow: 0 .75rem 1.5rem rgba(18, 38, 63, .03);
            border: 1px solid #ebecec;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-md-8"
            style="background: url('{{ asset('uploads/images/background.jpg') }}');background-size: cover;background-repeat: no-repeat;">
        </div>
        <div class="col-md-4">
            <div class="login">
                <div class="wrapper wrapper-login">
                    <div class="container container-login animated fadeIn">
                        <h2 class="text-center"
                            style="font-family: 'Marcellus SC', serif; font-weight: bold; font-size: 23px;">Bangladesh
                            Navy</h2>
                        <h3 class="text-center">Login</h3>
                        @if (session()->has('message'))
                            <div class="alert alert-{{ session('type') }}" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="login-form">
                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Email address</label>
                                    <input type="email" name="email" value="{{ old('username') ?: old('email') }}"
                                        class="form-control" id="emailaddress" required=""
                                        placeholder="Enter your email">
                                </div>

                                <div class="mb-3">
                                    <a href="{{ route('password.request') }}" class="text-muted float-end fs-12">Forgot
                                        your
                                        password?</a>
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="Enter your password">

                                    </div>
                                </div>
                                <div class="form-group form-action-d-flex mb-3">
                                    <div class="mb-3 mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="remember" class="form-check-input"
                                                id="checkbox-signin" checked>
                                            <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 mb-0 text-center">
                                    <button class="btn btn-primary" type="submit"> Log In </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
