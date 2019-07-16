@extends('layouts.app')

@section('title')
    Register Page
@endsection

@section('content')

    <div class="signup-page">
        <div class="signup-box">
            <div class="logo">
                <h2>Register</h2>
            </div>
            <div class="card">
                <div class="body">
                    <form id="sign_up" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="msg">Register a new membership</div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Name Surname" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">email</i>
                            </span>
                            <div class="form-line">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" name="email" value="{{ old('email') }}" required autocomplete="email">
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">lock</i>
                            </span>
                            <div class="form-line">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">lock</i>
                            </span>
                            <div class="form-line">
                                <input id="password-confirm" type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">SIGN UP</button>

                        @error('name')
                        <span class="invalid-feedback col-red" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                        @error('email')
                        <span class="invalid-feedback col-red" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                        @error('password')
                        <span class="invalid-feedback col-red" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror

                        <div class="m-t-25 m-b--5 align-center">
                            <a href="{{ route('login') }}">You already have a membership?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
@endsection
