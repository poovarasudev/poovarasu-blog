@extends('layouts.app')

@section('title')
    Reset Password
@endsection

@section('content')
    <div class="fp-page">
        <div class="fp-box">
            <div class="logo">
                <h2>Forgot Password</h2>
            </div>
            <div class="card">
                <div class="body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form id="forgot_password" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="msg">
                            Enter your email address that you used to register. We'll send you an email with a
                            link to reset your password.
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                            <div class="form-line">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">RESET MY PASSWORD</button>

                        <div class="row m-t-20 m-b--5 align-center">
                            <a href="{{ route('login') }}">Sign In!</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
