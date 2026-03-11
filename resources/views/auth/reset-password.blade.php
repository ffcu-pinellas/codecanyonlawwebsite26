@extends('auth.layouts.master-layout')

@section('content')
    <div class="card card-light p-0 my-sm-4 my-0 bg-dark">
        <div class="box-account">
            <img class="box-account-logo" src="{{ asset($logoFavicon?$logoFavicon->logo:'') }}"/>
            <h6 class="box-account-title text-white"> {{__('Reset Your Password')}} </h6>
            <form class="box-account-form" action="{{ route('password.update') }}" method="post">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <span class="reauth-email"> </span>
                <div class="form-group">
                    <input class="form-control" type="email" name="email" required placeholder="{{__('Email')}}" id="inputPseudo" value="{{ old('email', $email) }}">
                </div>
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <div class="form-gropu">
                    <input class="form-control" type="password" name="password" required placeholder="{{__('Password')}}" id="inputPassword">
                </div>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                <div class="form-gropu">
                    <input class="form-control" type="password" name="password_confirmation" required placeholder="{{__('Confirmation Password')}}" id="passwordConfirmation">
                </div>
                @if ($errors->has('password_confirmation'))
                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                @endif
                <button class="btn btn-primary btn-lg btn-block btn-wave-light" type="submit">{{ __('Reset Password') }}</button>
            </form>
        </div>
    </div>
@endsection
