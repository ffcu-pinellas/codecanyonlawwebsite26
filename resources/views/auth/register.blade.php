@extends('auth.layouts.master-layout')

@section('content')
    <div class="card card-light p-0 my-sm-4 my-0 bg-dark">
        <div class="box-account register">
            <img class="box-account-logo" src="{{ asset($logoFavicon?$logoFavicon->logo:'') }}"/>
            <h6 class="box-account-title text-white"> {{__('Sign Up')}} </h6>
            <form class="box-account-form" action="{{ route('register') }}" method="post">
                @csrf
                <span class="reauth-email"> </span>
                <div class="form-group">
                    <input class="form-control" type="text" name="name" required placeholder="Name" id="name" value="{{ old('name') }}">
                </div>
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <div class="form-group">
                    <input class="form-control" type="email" name="email" required placeholder="Email" id="email" value="{{ old('email') }}">
                </div>
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <div class="form-group">
                    <input class="form-control" type="tel" name="phone" required placeholder="Phone" id="phone" value="{{ old('phone') }}">
                </div>
                @if ($errors->has('phone'))
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                @endif
                <div class="form-group">
                    <textarea rows="3" class="form-control" name="address" required placeholder="Address" id="address">{{ old('address') }}</textarea>
                </div>
                @if ($errors->has('address'))
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                @endif
                <div class="form-gropu">
                    <input class="form-control" type="password" name="password" required placeholder="Password" id="password">
                </div>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                <div class="form-gropu">
                    <input class="form-control" type="password" name="password_confirmation" required placeholder="Confirm Password" id="password_confirmation">
                </div>
                @if ($errors->has('password_confirmation'))
                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                @endif
                <button class="btn btn-primary btn-lg btn-block btn-wave-light" type="submit">{{__('Sign Up')}}</button>
            </form>
            <p class="box-account-text text-white">{{__('Already have an account')}} ? <a href="{{ route('login') }}"
                                                                                       class="text-info text-grey">{{__('Login')}}</a></p>
        </div>
    </div>
@endsection
