@extends('auth.layouts.master-layout')

@section('content')
    <div class="card card-light p-0 my-sm-5 my-3 bg-dark border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; max-width: 450px; width: 100%;">
        <!-- Decorative Header Gradient -->
        <div style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); padding: 40px 30px; text-align: center; color: white;">
            @if($logoFavicon && $logoFavicon->logo)
                <img class="mb-3" src="{{ asset($logoFavicon->logo) }}" style="max-height: 60px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));"/>
            @else
                <i class="fas fa-user-shield fa-3x mb-3 text-white"></i>
            @endif
            <h4 class="mb-1" style="font-weight: 700; letter-spacing: 0.5px;">{{ __('Staff Portal') }}</h4>
            <p class="mb-0 opacity-75 small">{{ __('Access your time tracker, wages ledger and communications.') }}</p>
        </div>

        <div class="p-4" style="background: #111; border-top: 1px solid rgba(255,255,255,0.05);">
            @if(session('error'))
                <div class="alert alert-danger mb-3" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form class="box-account-form" action="{{ route('staff.login') }}" method="post">
                @csrf
                <div class="form-group mb-3">
                    <label for="inputPseudo" class="text-white-50 small mb-1">{{ __('Email Address') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input class="form-control bg-dark text-white border-secondary" type="email" name="email" required placeholder="name@company.com" id="inputPseudo" value="{{ old('email') }}">
                    </div>
                    @if ($errors->has('email'))
                        <span class="text-danger small mt-1 d-block">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="form-group mb-4">
                    <label for="inputPassword" class="text-white-50 small mb-1">{{ __('Security Password') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-lock"></i></span>
                        </div>
                        <input class="form-control bg-dark text-white border-secondary" type="password" name="password" required placeholder="••••••••" id="inputPassword">
                    </div>
                    @if ($errors->has('password'))
                        <span class="text-danger small mt-1 d-block">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <div class="form-group d-flex justify-content-between align-items-center mb-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember" class="custom-control-input" id="customCheck1">
                        <label class="custom-control-label text-white-50 small" for="customCheck1">{{ __('Keep me logged in') }}</label>
                    </div>
                </div>

                <button class="btn btn-primary btn-lg btn-block text-uppercase font-weight-bold" type="submit" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none; font-size: 0.9rem; padding: 12px; border-radius: 8px; box-shadow: 0 4px 15px rgba(30, 60, 114, 0.4);">
                    {{ __('Sign In') }}
                </button>
            </form>
            
            <div class="text-center mt-4 pt-2 border-top border-secondary">
                <a href="{{ route('home') }}" class="text-info small"><i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Main Website') }}</a>
            </div>
        </div>
    </div>
@endsection
