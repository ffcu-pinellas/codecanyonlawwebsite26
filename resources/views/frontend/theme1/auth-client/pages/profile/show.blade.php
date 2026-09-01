@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | Profile & Security Hub')

@section('page-css')
<style>
.profile-card {
    background: #161a23;
    border: 1px solid #28303f;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    margin-bottom: 24px;
    overflow: hidden;
}
.profile-card-header {
    background: #1f2533;
    border-bottom: 1px solid #2e3849;
    padding: 16px 20px;
    color: #fecc56;
    font-weight: 700;
    font-size: 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.profile-avatar-box {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fecc56;
    margin: 0 auto 12px;
}
.btn-gold {
    background: linear-gradient(135deg, #fecc56, #f0a500);
    color: #000 !important;
    border: none;
    font-weight: 700;
    border-radius: 6px;
    padding: 8px 18px;
    font-size: 13px;
    transition: all 0.2s;
}
.btn-gold:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(254,204,86,0.45);
}

/* Light mode overrides */
body.light-mode .profile-card, html.light-mode .profile-card {
    background: #ffffff !important;
    border-color: #e2e8f0 !important;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06) !important;
}
body.light-mode .profile-card-header, html.light-mode .profile-card-header {
    background: #f8fafc !important;
    border-color: #e2e8f0 !important;
    color: #b45309 !important;
}
body.light-mode .profile-card-body, html.light-mode .profile-card-body {
    background: #ffffff !important;
    color: #0f172a !important;
}
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap" style="gap:10px;">
        <div>
            <h4 class="font-weight-bold text-white mb-1">
                <i class="fas fa-user-shield text-warning mr-2"></i> {{ __('Profile & Account Security Hub') }}
            </h4>
            <p class="text-muted small mb-0">{{ __('Manage verified personal identity details, 4-digit security PIN, currency preferences, and security credentials.') }}</p>
        </div>
        <a href="{{ route('client.dashboard') }}" class="btn btn-sm btn-outline-secondary text-light font-weight-bold px-3">
            <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Dashboard') }}
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success border-0 font-weight-bold mb-4" style="background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3);">
            <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 font-weight-bold mb-4" style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3);">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- LEFT COLUMN: Profile Identity Card & Photo -->
        <div class="col-lg-4 mb-4">
            <div class="profile-card text-center p-4">
                <div class="position-relative d-inline-block mb-3">
                    @if($user->profile_photo_path)
                        <img src="{{ Storage::url($user->profile_photo_path) }}" class="profile-avatar-box">
                    @else
                        <div class="profile-avatar-box d-flex align-items-center justify-content-center" style="background: rgba(254,204,86,0.15); color: #fecc56; font-size: 32px; font-weight: bold;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="badge badge-success position-absolute" style="bottom: 12px; right: 6px; border-radius: 50%; padding: 6px;" title="Online & Protected">
                        <i class="fas fa-check" style="font-size: 10px;"></i>
                    </span>
                </div>

                <h5 class="font-weight-bold text-white mb-1">{{ $user->name }}</h5>
                <span class="badge badge-success px-3 py-1 font-weight-bold mb-3" style="font-size: 11px;">
                    <i class="fas fa-shield-alt mr-1"></i> {{ __('Verified Client Account') }}
                </span>

                <!-- Avatar Upload Form -->
                <form action="{{ route('user-profile-information.update') }}" method="POST" enctype="multipart/form-data" class="p-3 rounded text-left mb-3" style="background: #11151e; border: 1px solid #28303f;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="phone" value="{{ $user->phone }}">
                    <input type="hidden" name="address" value="{{ $user->address }}">
                    <label class="small font-weight-bold text-warning mb-2 d-block">
                        <i class="fas fa-camera mr-1"></i> {{ __('Update Profile Photo') }}
                    </label>
                    <input type="file" name="photo" class="form-control-file form-control-sm text-muted mb-2" required accept="image/*">
                    <button type="submit" class="btn btn-warning btn-sm btn-block font-weight-bold text-dark">
                        <i class="fas fa-upload mr-1"></i> {{ __('Upload Photo') }}
                    </button>
                </form>

                <!-- Contact & Account Metadata -->
                <div class="border-top pt-3 text-left small text-muted" style="border-color: #28303f !important;">
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Account Email:') }}</span>
                        <strong class="text-white text-truncate ml-2">{{ $user->email }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Phone Contact:') }}</span>
                        <strong class="text-white">{{ $user->phone ?: __('Not configured') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Security PIN:') }}</span>
                        @if($user->pin_hash)
                            <span class="badge badge-success">{{ __('Active (4-Digit)') }}</span>
                        @else
                            <span class="badge badge-warning text-dark">{{ __('Not Set') }}</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>{{ __('Active Currency:') }}</span>
                        <strong class="text-warning">{{ $user->preferred_currency ?: 'USD ($)' }}</strong>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top" style="border-color: #28303f !important;">
                    <a href="{{ route('client.kyc.hub') }}" class="btn btn-outline-secondary btn-sm btn-block font-weight-bold text-light mb-2">
                        <i class="fas fa-id-card text-warning mr-1"></i> {{ __('Identity Verification (KYC)') }}
                    </a>
                    <a href="{{ route('client.documents.index') }}" class="btn btn-outline-secondary btn-sm btn-block font-weight-bold text-light">
                        <i class="fas fa-file-contract text-warning mr-1"></i> {{ __('Contracts & Agreements') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: 4 Integrated Configuration Sections -->
        <div class="col-lg-8 mb-4">
            
            <!-- SECTION 1: PERSONAL DETAILS -->
            <div class="profile-card mb-4">
                <div class="profile-card-header">
                    <span><i class="fas fa-user-edit mr-2"></i> {{ __('Personal Identity & Contact Information') }}</span>
                    <span class="badge badge-dark text-muted border border-secondary">{{ __('Profile Section 1') }}</span>
                </div>
                <div class="p-4 profile-card-body">
                    <form action="{{ route('user-profile-information.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="small font-weight-bold text-light">{{ __('Full Legal Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control font-weight-bold" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="small font-weight-bold text-light">{{ __('Telephone / Mobile Number') }} <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="+1 (555) 000-0000" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="small font-weight-bold text-light">{{ __('Registered Email Address') }}</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                <small class="text-muted">{{ __('Used for authentication and formal legal communication.') }}</small>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="small font-weight-bold text-light">{{ __('Residential / Business Address') }} <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}" placeholder="Street, City, State/Province, Postal Code" required>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-gold font-weight-bold px-4">
                                    <i class="fas fa-save mr-1"></i> {{ __('Save Personal Information') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SECTION 2: 4-DIGIT SECURITY PIN -->
            <div class="profile-card mb-4" id="pin-section">
                <div class="profile-card-header">
                    <span><i class="fas fa-key mr-2"></i> {{ __('4-Digit Security PIN (Document & Wire Protection)') }}</span>
                    @if($user->pin_hash)
                        <span class="badge badge-success px-3 py-1">{{ __('PIN Active') }}</span>
                    @else
                        <span class="badge badge-warning text-dark px-3 py-1">{{ __('Action Required') }}</span>
                    @endif
                </div>
                <div class="p-4 profile-card-body">
                    <p class="text-muted small mb-3">
                        {{ __('Your 4-digit security PIN is required for authorizing settlement disbursements, signing legal engagement letters, and executing confidential document transactions.') }}
                    </p>
                    <form action="{{ route('client.pin.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            @if($user->pin_hash)
                                <div class="col-md-4 form-group mb-3">
                                    <label class="small font-weight-bold text-light">{{ __('Current PIN') }} <span class="text-danger">*</span></label>
                                    <input type="password" name="current_pin" maxlength="4" class="form-control text-center font-weight-bold" placeholder="••••" required inputmode="numeric">
                                </div>
                            @endif
                            <div class="col-md-4 form-group mb-3">
                                <label class="small font-weight-bold text-light">{{ __('New 4-Digit PIN') }} <span class="text-danger">*</span></label>
                                <input type="password" name="pin" maxlength="4" class="form-control text-center font-weight-bold" placeholder="••••" required inputmode="numeric">
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="small font-weight-bold text-light">{{ __('Confirm New PIN') }} <span class="text-danger">*</span></label>
                                <input type="password" name="pin_confirmation" maxlength="4" class="form-control text-center font-weight-bold" placeholder="••••" required inputmode="numeric">
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-warning btn-sm font-weight-bold text-dark px-4 py-2">
                                    <i class="fas fa-lock mr-1"></i> {{ $user->pin_hash ? __('Update Security PIN') : __('Set 4-Digit Security PIN') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SECTION 3: PREFERRED CURRENCY -->
            <div class="profile-card mb-4">
                <div class="profile-card-header">
                    <span><i class="fas fa-coins mr-2"></i> {{ __('Display Currency Preference') }}</span>
                    <span class="badge badge-warning text-dark font-weight-bold">{{ $user->preferred_currency ?: 'USD' }}</span>
                </div>
                <div class="p-4 profile-card-body">
                    <p class="text-muted small mb-3">
                        {{ __('Choose your preferred benchmark currency for settlement valuation, retainer calculation, and ledger schedules.') }}
                    </p>
                    <div class="row">
                        @php
                            $currList = [
                                'USD' => ['sym' => '$', 'name' => 'United States Dollar (USD)'],
                                'EUR' => ['sym' => '€', 'name' => 'Euro (EUR)'],
                                'GBP' => ['sym' => '£', 'name' => 'British Pound (GBP)'],
                                'CAD' => ['sym' => '$', 'name' => 'Canadian Dollar (CAD)'],
                                'AUD' => ['sym' => '$', 'name' => 'Australian Dollar (AUD)'],
                            ];
                        @endphp
                        @foreach($currList as $code => $cMeta)
                            <div class="col-sm-6 col-md-4 mb-2">
                                <button type="button" class="btn btn-sm btn-block text-left d-flex justify-content-between align-items-center py-2 px-3 {{ ($user->preferred_currency ?: 'USD') === $code ? 'btn-warning text-dark font-weight-bold' : 'btn-dark text-light border border-secondary' }}" onclick="changePortalCurrency('{{ $code }}')">
                                    <span>{{ $code }}</span>
                                    <span class="font-weight-bold">{{ $cMeta['sym'] }}</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- SECTION 4: SECURITY PASSWORD -->
            <div class="profile-card mb-4">
                <div class="profile-card-header">
                    <span><i class="fas fa-shield-alt mr-2"></i> {{ __('Portal Access Password') }}</span>
                    <span class="badge badge-dark text-muted border border-secondary">{{ __('Encrypted SHA-256') }}</span>
                </div>
                <div class="p-4 profile-card-body">
                    <form action="{{ route('user-password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label class="small font-weight-bold text-light">{{ __('Current Password') }} <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="small font-weight-bold text-light">{{ __('New Password') }} <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="small font-weight-bold text-light">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-outline-warning btn-sm font-weight-bold px-4 py-2">
                                    <i class="fas fa-key mr-1"></i> {{ __('Update Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

