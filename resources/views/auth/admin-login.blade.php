@extends('auth.layouts.master-layout')

@section('content')
<style>
    .admin-login-box {
        max-width: 480px;
        margin: 30px auto;
        background: #0f172a;
        border: 1px solid #334155;
        border-top: 4px solid #f59e0b;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        padding: 35px 30px;
    }
    .admin-badge {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 12px;
    }
</style>

<div class="admin-login-box text-white">
    <div class="text-center mb-4">
        @if(isset($logoFavicon) && $logoFavicon->logo)
            <img src="{{ asset($logoFavicon->logo) }}" alt="{{ config('app.name') }}" style="max-height: 55px; max-width: 100%; object-fit: contain;" class="mb-3">
        @else
            <h4 class="font-weight-bold text-white mb-2">{{ config('app.name', 'Your CPA Expert') }}</h4>
        @endif
        <div><span class="admin-badge"><i class="fas fa-user-shield mr-1"></i> {{ __('Staff & Attorney Portal') }}</span></div>
        <p class="text-muted small mb-0">{{ __('Authorized access only for legal officers, CPAs, and practice staff.') }}</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger font-weight-bold py-2 small mb-3">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="admin_email" class="small font-weight-bold text-light">{{ __('Staff Email Address') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-envelope"></i></span>
                </div>
                <input type="email" name="email" id="admin_email" class="form-control bg-dark text-white border-secondary" required placeholder="attorney@yourcpaexpert.com" value="{{ old('email') }}">
            </div>
            @if ($errors->has('email'))
                <span class="text-danger small mt-1 d-block font-weight-bold">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="form-group mb-3">
            <label for="admin_pwd" class="small font-weight-bold text-light">{{ __('Password') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-lock"></i></span>
                </div>
                <input type="password" name="password" id="admin_pwd" class="form-control bg-dark text-white border-secondary" required placeholder="••••••••">
            </div>
            @if ($errors->has('password'))
                <span class="text-danger small mt-1 d-block font-weight-bold">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="remember" class="custom-control-input" id="rememberMe">
                <label class="custom-control-label small text-muted" for="rememberMe">{{ __('Keep me signed in') }}</label>
            </div>
        </div>

        <button type="submit" class="btn btn-warning btn-block font-weight-bold text-dark py-2 shadow-sm" style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">
            <i class="fas fa-sign-in-alt mr-1"></i> {{ __('Authenticate Staff Session') }}
        </button>
    </form>

    <div class="text-center mt-4 pt-3 border-top border-secondary">
        <small class="text-muted">{{ __('Are you a client?') }}</small>
        <a href="{{ route('login') }}" class="small text-warning font-weight-bold ml-1">{{ __('Sign in to Client Portal') }} &rarr;</a>
    </div>
</div>
@endsection
