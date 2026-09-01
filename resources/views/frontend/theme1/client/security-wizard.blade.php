@extends('auth.layouts.master-layout')

@section('content')
<style>
    .security-wizard-card {
        max-width: 580px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.12);
        overflow: hidden;
    }
    .wizard-header {
        background: #0f172a;
        color: #f8fafc;
        padding: 30px 25px;
        text-align: center;
        border-bottom: 3px solid #f59e0b;
    }
    .wizard-badge {
        display: inline-block;
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }
    .security-wizard-card .form-control {
        background-color: #ffffff !important;
        color: #0f172a !important;
        border: 1px solid #94a3b8 !important;
        font-size: 14px !important;
        padding: 10px 14px !important;
        height: auto !important;
        font-weight: 500 !important;
    }
    .security-wizard-card .form-control:focus {
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2) !important;
        background-color: #ffffff !important;
        color: #0f172a !important;
    }
    .security-wizard-card .form-control:disabled,
    .security-wizard-card .form-control[readonly] {
        background-color: #e2e8f0 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        opacity: 1 !important;
        border-color: #cbd5e1 !important;
        cursor: not-allowed !important;
    }
    .pin-input-field {
        letter-spacing: 12px !important;
        font-size: 24px !important;
        font-weight: 800 !important;
        text-align: center !important;
        max-width: 220px !important;
        margin: 0 auto !important;
        border-radius: 8px !important;
        background-color: #f8fafc !important;
        color: #0f172a !important;
    }
</style>

<div class="container my-5">
    <div class="security-wizard-card">
        <div class="wizard-header">
            <span class="wizard-badge"><i class="fas fa-shield-alt mr-1"></i> First-Time Account Activation</span>
            <h3 class="font-weight-bold text-white mb-1" style="font-size: 22px;">Welcome, {{ $user->name }}</h3>
            <p class="text-slate-300 small mb-0" style="color: #94a3b8;">Please establish your permanent password and private 4-digit Security PIN to access your confidential Legal & CPA Client Portal.</p>
        </div>

        <div class="card-body p-4 p-md-5">
            @if(session('error'))
                <div class="alert alert-danger font-weight-bold mb-4">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 pl-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-warning mb-4" style="background: #fffbeb; border: 1px solid #fef3c7; color: #92400e; font-size: 13px; border-radius: 8px;">
                <i class="fas fa-info-circle mr-1"></i> <strong>Confidentiality Notice:</strong> Your 4-digit PIN will be used as a secondary verification code when viewing privileged tax schedules, e-signing legal agreements, and authorizing trust/settlement disbursements.
            </div>

            <form action="{{ route('client.security.wizard.process') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark" style="font-size: 13px;">{{ __('Registered Email / Username') }}</label>
                    <input type="text" class="form-control bg-light" value="{{ $user->email }}" readonly disabled style="font-size: 14px;">
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="password" class="font-weight-bold text-dark" style="font-size: 13px;">{{ __('New Permanent Password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" required placeholder="At least 8 characters" style="font-size: 14px;">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="password_confirmation" class="font-weight-bold text-dark" style="font-size: 13px;">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Repeat new password" style="font-size: 14px;">
                    </div>
                </div>

                <hr class="my-4" style="border-color: #f1f5f9;">

                <div class="form-group text-center mb-4">
                    <label for="pin" class="font-weight-bold text-dark d-block mb-1" style="font-size: 14px;">
                        <i class="fas fa-key text-warning mr-1"></i> {{ __('Create 4-Digit Security PIN') }} <span class="text-danger">*</span>
                    </label>
                    <small class="text-muted d-block mb-3">{{ __('Enter a private 4-digit numeric code (e.g. 7482)') }}</small>
                    <input type="password" name="pin" id="pin" maxlength="4" pattern="\d{4}" class="form-control pin-input-field" required placeholder="••••" inputmode="numeric">
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="phone" class="font-weight-bold text-dark" style="font-size: 13px;">{{ __('Direct Phone Number') }}</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="+1 (555) 000-0000" style="font-size: 14px;">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="preferred_currency" class="font-weight-bold text-dark" style="font-size: 13px;">{{ __('Preferred Portal Currency') }}</label>
                        <select name="preferred_currency" id="preferred_currency" class="form-control" style="font-size: 14px;">
                            <option value="USD" {{ old('preferred_currency', $user->preferred_currency) == 'USD' ? 'selected' : '' }}>USD - US Dollar ($)</option>
                            <option value="EUR" {{ old('preferred_currency', $user->preferred_currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro (€)</option>
                            <option value="GBP" {{ old('preferred_currency', $user->preferred_currency) == 'GBP' ? 'selected' : '' }}>GBP - British Pound (£)</option>
                            <option value="CAD" {{ old('preferred_currency', $user->preferred_currency) == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar ($)</option>
                            <option value="AUD" {{ old('preferred_currency', $user->preferred_currency) == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar ($)</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning btn-block font-weight-bold py-3 text-dark shadow-sm" style="font-size: 15px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-lock-open mr-1"></i> {{ __('Activate Portal & Enter Dashboard') }}
                    </button>
                </div>
            </form>
        </div>
        <div class="card-footer bg-light text-center py-3" style="font-size: 12px; color: #64748b;">
            <i class="fas fa-shield-alt mr-1"></i> 256-Bit SSL Encrypted &bull; Privileged Legal & CPA Client Portal
        </div>
    </div>
</div>
@endsection
