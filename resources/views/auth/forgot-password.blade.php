@extends('auth.layouts.master-layout')

@section('content')
    <div class="card card-light p-0 my-sm-4 my-0 bg-dark">
        <div class="box-account">
            <img class="box-account-logo" src="{{ asset($logoFavicon?$logoFavicon->logo:'') }}"/>
            <p class="box-account-title text-white"> {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }} </p>
            @if (session('status'))
                <div class="text-sm text-success font-weight-bold">
                    {{ session('status') }}
                </div>
            @endif
            <form class="box-account-form" action="{{ route('password.email') }}" method="post">
                @csrf
                <span class="reauth-email"> </span>
                <div class="form-group">
                    <input class="form-control" type="email" name="email" required placeholder="Email" id="inputPseudo" value="{{ old('email') }}">
                </div>
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <button class="btn btn-primary btn-lg btn-block btn-wave-light" type="submit">{{ __('Email Password Reset Link') }}</button>
            </form>
        </div>
    </div>
@endsection
