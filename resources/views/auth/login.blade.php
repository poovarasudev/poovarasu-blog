@extends('layouts.app')

@section('content')
    <div class="login-page">
        <div class="login-box">
            <div class="logo">
                <h2>Login</h2>
            </div>
            <div class="card">
                <div class="body">
                    <form method="POST" id="sign_in" action="{{ route('login') }}">
                        @csrf
                        <div class="msg">Sign in to start your session</div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">lock</i>
                            </span>
                            <div class="form-line">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="current-password">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-8 p-t-5">
                                <input class="form-check-input filled-in chk-col-pink" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>
                            <div class="col-xs-4">
                                <button class="btn btn-block bg-pink waves-effect" type="submit">SIGN IN</button>
                            </div>
                        </div>
                        <div class="row m-t-15 m-b--20">
                            <div class="col-xs-6">
                                <a href="{{ route('register') }}">Register Now!</a>
                            </div>
                            <div class="col-xs-6 align-right">
                                <a href="{{ route('password.request') }}">Forgot Password?</a>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback col-red" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                        @error('email')
                        <span class="invalid-feedback col-red" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection