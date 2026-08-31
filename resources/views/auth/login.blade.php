@extends('auth.layouts.master-layout')

@section('content')
    <div class="card card-light p-0 my-sm-4 my-0 bg-dark">
        <div class="box-account">
            <img class="box-account-logo" src="{{ asset($logoFavicon?$logoFavicon->logo:'') }}" style="max-height: 60px; max-width: 100%; object-fit: contain;"/>
            <h6 class="box-account-title text-white"> {{__('Client Portal Login')}} </h6>
            <p class="text-muted small text-center mb-3">{{ __('Access your confidential case files, financial schedules, and tax records.') }}</p>
            <form class="box-account-form" action="{{ route('login') }}" method="post">
                @csrf
                <span class="reauth-email"> </span>
                <div class="form-group">
                    <input class="form-control" type="email" name="email" required placeholder="Client Email Address" id="inputPseudo">
                </div>
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <div class="form-group">
                    <input class="form-control" type="password" name="password" required placeholder="Password" id="inputPassword">
                </div>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" class="custom-control-input" id="customCheck1">
                    <label class="custom-control-label text-white" for="customCheck1">{{__('Remember me')}}</label>
                </div>
                <button class="btn btn-warning btn-lg btn-block btn-wave-light font-weight-bold text-dark" type="submit">{{__('Sign In to Client Portal')}}</button>
            </form>
            @if (Route::has('password.request'))
                <p class="box-account-text text-white mb-2">{{__('Forgot your password')}} ? <a href="{{ route('password.request') }}"
                                                                                 class="text-warning">{{__('Recover')}}</a></p>
            @endif
            <div class="border-top border-secondary pt-3 mt-3 text-center">
                <small class="text-muted">{{ __('Staff or Legal Officer?') }}</small>
                <a href="{{ route('admin.login') }}" class="text-info font-weight-bold ml-1 small">{{ __('Sign in to Staff Portal') }} &rarr;</a>
            </div>
        </div>
    </div>
@endsection
