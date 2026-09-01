@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | Profile & Security Hub')

@section('page-css')
<style>
.profile-nav-pills .nav-link {
    background: #161a23;
    border: 1px solid #28303f;
    color: #94a3b8;
    font-weight: 700;
    font-size: 13.5px;
    padding: 12px 20px;
    border-radius: 10px;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}
.profile-nav-pills .nav-link:hover {
    background: #1f2533;
    color: #fecc56;
    border-color: #374151;
}
.profile-nav-pills .nav-link.active {
    background: linear-gradient(135deg, #1f2533, #161a23);
    color: #fecc56 !important;
    border-color: #fecc56 !important;
    box-shadow: 0 4px 14px rgba(254,204,86,0.18);
}
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
    width: 90px;
    height: 90px;
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
body.light-mode .profile-nav-pills .nav-link, html.light-mode .profile-nav-pills .nav-link {
    background: #ffffff !important;
    border-color: #cbd5e1 !important;
    color: #475569 !important;
}
body.light-mode .profile-nav-pills .nav-link.active, html.light-mode .profile-nav-pills .nav-link.active {
    background: #f8fafc !important;
    color: #b45309 !important;
    border-color: #fecc56 !important;
}
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

    <!-- 3-TAB SELECTOR (IFW REPLICA) -->
    <ul class="nav nav-pills profile-nav-pills mb-4 flex-column flex-md-row" id="profileTabs" role="tablist" style="gap: 10px;">
        <li class="nav-item flex-fill">
            <a class="nav-link active text-center justify-content-center" id="tab-personal" data-toggle="pill" href="#pane-personal" role="tab">
                <i class="fas fa-user-edit mr-1"></i> {{ __('Personal Information') }}
            </a>
        </li>
        <li class="nav-item flex-fill">
            <a class="nav-link text-center justify-content-center" id="tab-security" data-toggle="pill" href="#pane-security" role="tab">
                <i class="fas fa-shield-alt mr-1"></i> {{ __('Security & 4-Digit PIN') }}
                @if(!$user->pin_hash)
                    <span class="badge badge-warning text-dark ml-2 py-1 px-2" style="font-size: 10px;">Action Req.</span>
                @else
                    <span class="badge badge-success ml-2 py-1 px-2" style="font-size: 10px;">Protected</span>
                @endif
            </a>
        </li>
        <li class="nav-item flex-fill">
            <a class="nav-link text-center justify-content-center" id="tab-preferences" data-toggle="pill" href="#pane-preferences" role="tab">
                <i class="fas fa-sliders-h mr-1"></i> {{ __('Preferences & Regional') }}
            </a>
        </li>
    </ul>

    <!-- 3 TABS CONTENT PANES -->
    <div class="tab-content" id="profileTabsContent">

        <!-- ==================== TAB 1: PERSONAL INFORMATION ==================== -->
        <div class="tab-pane fade show active" id="pane-personal" role="tabpanel">
            <div class="row">
                <!-- Avatar & Status Box -->
                <div class="col-lg-4 mb-4">
                    <div class="profile-card text-center p-4">
                        <div class="position-relative d-inline-block mb-3">
                            @if($user->profile_photo_path)
                                <img src="{{ Storage::url($user->profile_photo_path) }}" class="profile-avatar-box">
                            @else
                                <div class="profile-avatar-box d-flex align-items-center justify-content-center" style="background: rgba(254,204,86,0.15); color: #fecc56; font-size: 30px; font-weight: bold;">
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
                    </div>
                </div>

                <!-- Personal Fields Form -->
                <div class="col-lg-8 mb-4">
                    <div class="profile-card">
                        <div class="profile-card-header">
                            <span><i class="fas fa-user-edit mr-2"></i> {{ __('Personal Identity & Contact Information') }}</span>
                            <span class="badge badge-dark text-muted border border-secondary">{{ __('Tab 1') }}</span>
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
                </div>
            </div>
        </div>

        <!-- ==================== TAB 2: SECURITY & 4-DIGIT PIN ==================== -->
        <div class="tab-pane fade" id="pane-security" role="tabpanel">
            <div class="row">
                <!-- 4-Digit Security PIN Section -->
                <div class="col-lg-6 mb-4">
                    <div class="profile-card h-100" id="pin-section">
                        <div class="profile-card-header">
                            <span><i class="fas fa-key mr-2"></i> {{ __('4-Digit Security PIN (Signing & Wire Protection)') }}</span>
                            @if($user->pin_hash)
                                <span class="badge badge-success px-3 py-1">{{ __('PIN Active') }}</span>
                            @else
                                <span class="badge badge-warning text-dark px-3 py-1">{{ __('Action Required') }}</span>
                            @endif
                        </div>
                        <div class="p-4 profile-card-body d-flex flex-column justify-content-between">
                            <div>
                                <p class="text-muted small mb-3">
                                    {{ __('Your 4-digit security PIN is required for authorizing settlement disbursements, signing legal engagement letters, and executing confidential document transactions.') }}
                                </p>
                                <form action="{{ route('client.security.set-pin') }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold text-light">{{ __('Current Password') }} <span class="text-danger">*</span></label>
                                        <input type="password" name="current_password" class="form-control" placeholder="••••••••" required>
                                        @error('current_password') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold text-light">{{ __('New 4-Digit PIN') }} <span class="text-danger">*</span></label>
                                        <input type="password" name="pin" maxlength="4" class="form-control text-center font-weight-bold text-warning" placeholder="••••" required inputmode="numeric" style="letter-spacing: 4px; font-size: 1.2rem;">
                                        @error('pin') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="small font-weight-bold text-light">{{ __('Confirm New PIN') }} <span class="text-danger">*</span></label>
                                        <input type="password" name="pin_confirmation" maxlength="4" class="form-control text-center font-weight-bold text-warning" placeholder="••••" required inputmode="numeric" style="letter-spacing: 4px; font-size: 1.2rem;">
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-block font-weight-bold text-dark py-2">
                                        <i class="fas fa-lock mr-1"></i> {{ $user->pin_hash ? __('Update Security PIN') : __('Set 4-Digit Security PIN') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Password Section -->
                <div class="col-lg-6 mb-4">
                    <div class="profile-card h-100">
                        <div class="profile-card-header">
                            <span><i class="fas fa-shield-alt mr-2"></i> {{ __('Portal Access Password') }}</span>
                            <span class="badge badge-dark text-muted border border-secondary">{{ __('Encrypted') }}</span>
                        </div>
                        <div class="p-4 profile-card-body d-flex flex-column justify-content-between">
                            <div>
                                <p class="text-muted small mb-3">
                                    {{ __('Ensure your account is protected with a complex alphanumeric passphrase (minimum 8 characters).') }}
                                </p>
                                <form action="{{ route('user-password.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold text-light">{{ __('Current Password') }} <span class="text-danger">*</span></label>
                                        <input type="password" name="current_password" class="form-control" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold text-light">{{ __('New Password') }} <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="small font-weight-bold text-light">{{ __('Confirm New Password') }} <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-outline-warning btn-block font-weight-bold py-2">
                                        <i class="fas fa-key mr-1"></i> {{ __('Update Password') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== TAB 3: PREFERENCES & REGIONAL ==================== -->
        <div class="tab-pane fade" id="pane-preferences" role="tabpanel">
            <div class="profile-card mb-4">
                <div class="profile-card-header">
                    <span><i class="fas fa-coins mr-2"></i> {{ __('Display Currency & Benchmark Valuation') }}</span>
                    <span class="badge badge-warning text-dark font-weight-bold">{{ $user->preferred_currency ?: 'USD ($)' }}</span>
                </div>
                <div class="p-4 profile-card-body">
                    <p class="text-muted small mb-3">
                        {{ __('Choose your preferred benchmark currency for settlement valuation, retainer calculation, and ledger schedules.') }}
                    </p>
                    <div class="row mb-4">
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
                            <div class="col-sm-6 col-md-4 mb-3">
                                <button type="button" class="btn btn-block text-left d-flex justify-content-between align-items-center py-3 px-3 {{ ($user->preferred_currency ?: 'USD') === $code ? 'btn-warning text-dark font-weight-bold' : 'btn-dark text-light border border-secondary' }}" onclick="changePortalCurrency('{{ $code }}')">
                                    <div>
                                        <strong class="d-block">{{ $cMeta['name'] }}</strong>
                                        <small class="text-muted">{{ $code }} Standard</small>
                                    </div>
                                    <span class="font-weight-bold h5 mb-0">{{ $cMeta['sym'] }}</span>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-top pt-3" style="border-color: #28303f !important;">
                        <h6 class="text-warning font-weight-bold mb-2"><i class="fas fa-bell mr-1"></i> {{ __('Legal Communication & Security Dispatch Notices') }}</h6>
                        <p class="text-muted small mb-0">
                            {{ __('All formal correspondence, retainer invoices, and case milestone status updates are automatically dispatched via end-to-end encrypted notification channels to') }} <strong class="text-white">{{ $user->email }}</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
