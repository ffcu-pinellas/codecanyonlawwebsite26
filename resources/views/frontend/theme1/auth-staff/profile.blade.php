@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    .staff-settings-card {
        background: #11151e;
        border: 1px solid #28303f;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .staff-settings-card-header {
        background: #161a23;
        border-bottom: 1px solid #28303f;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .staff-settings-card-body {
        padding: 24px;
    }
    .profile-nav-pills .nav-link {
        background: #161a23;
        border: 1px solid #28303f;
        color: #94a3b8;
        font-weight: 700;
        font-size: 13.5px;
        padding: 12px 20px;
        border-radius: 10px;
        transition: all 0.2s ease;
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
        background: linear-gradient(135deg, rgba(254, 204, 86, 0.15) 0%, rgba(22, 26, 35, 0.4) 100%);
        color: #fecc56 !important;
        border-color: #fecc56 !important;
        box-shadow: 0 4px 14px rgba(254, 204, 86, 0.18);
    }
    .btn-gold-save {
        background: linear-gradient(135deg, #fecc56, #f0a500);
        color: #000 !important;
        font-weight: 700;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 13.5px;
        box-shadow: 0 4px 14px rgba(254, 204, 86, 0.3);
        transition: all 0.2s ease;
    }
    .btn-gold-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(254, 204, 86, 0.45);
    }

    /* Light mode */
    body.light-mode .staff-settings-card, html.light-mode .staff-settings-card {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06) !important;
    }
    body.light-mode .staff-settings-card-header, html.light-mode .staff-settings-card-header {
        background: #f8fafc !important;
        border-bottom-color: #e2e8f0 !important;
    }
    body.light-mode .profile-nav-pills .nav-link, html.light-mode .profile-nav-pills .nav-link {
        background: #ffffff !important;
        border-color: #cbd5e1 !important;
        color: #475569 !important;
    }
    body.light-mode .profile-nav-pills .nav-link:hover, html.light-mode .profile-nav-pills .nav-link:hover {
        background: #f1f5f9 !important;
        color: #0f172a !important;
    }
    body.light-mode .profile-nav-pills .nav-link.active, html.light-mode .profile-nav-pills .nav-link.active {
        background: rgba(254, 204, 86, 0.15) !important;
        color: #b45309 !important;
        border-color: #fecc56 !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">

    <!-- Top Breadcrumb & Page Banner -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap" style="gap: 12px;">
        <div>
            <h4 class="font-weight-bold text-white mb-1">
                <i class="fas fa-user-cog mr-2 text-warning"></i>{{ __('Staff Profile & Account Settings') }}
            </h4>
            <p class="text-muted small mb-0">{{ __('Manage your personal contact info, practitioner credentials, and security password.') }}</p>
        </div>
        <div>
            <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary btn-sm text-light font-weight-bold px-3">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Dashboard') }}
            </a>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('message'))
        <div class="alert alert-{{ session('alert-type') == 'error' ? 'danger' : 'success' }} alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-{{ session('alert-type') == 'error' ? 'exclamation-triangle' : 'check-circle' }} mr-2"></i> {{ session('message') }}
            <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Profile Navigation Pills -->
    <ul class="nav nav-pills profile-nav-pills mb-4" id="staffProfileTabs" role="tablist" style="gap: 10px;">
        <li class="nav-item">
            <a class="nav-link active" id="personal-tab" data-toggle="pill" href="#tab-personal" role="tab" aria-controls="tab-personal" aria-selected="true">
                <i class="fas fa-id-card"></i> {{ __('Personal Information') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="security-tab" data-toggle="pill" href="#tab-security" role="tab" aria-controls="tab-security" aria-selected="false">
                <i class="fas fa-key"></i> {{ __('Password & Security') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="employment-tab" data-toggle="pill" href="#tab-employment" role="tab" aria-controls="tab-employment" aria-selected="false">
                <i class="fas fa-briefcase"></i> {{ __('Practitioner Dossier') }}
            </a>
        </li>
    </ul>

    <div class="tab-content" id="staffProfileTabContent">

        <!-- TAB 1: PERSONAL INFORMATION -->
        <div class="tab-pane fade show active" id="tab-personal" role="tabpanel" aria-labelledby="personal-tab">
            <div class="row">
                <!-- Avatar & Identity Summary -->
                <div class="col-lg-4 mb-4">
                    <div class="staff-settings-card text-center p-4">
                        <div class="mb-3 position-relative d-inline-block">
                            @if($user->profile_photo_path)
                                @php
                                    $uPhoto = $user->profile_photo_path;
                                    $uSrc = str_starts_with($uPhoto, 'assets/') || str_starts_with($uPhoto, 'http') || str_starts_with($uPhoto, '/assets') ? asset($uPhoto) : Storage::url($uPhoto);
                                @endphp
                                <img src="{{ $uSrc }}" alt="{{ $user->name }}" class="rounded-circle img-thumbnail" style="width: 110px; height: 110px; object-fit: cover; border-color: #fecc56; border-width: 3px;">
                            @else
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 110px; height: 110px; background: rgba(254,204,86,0.15); color: #fecc56; font-size: 38px; font-weight: 800; border: 3px solid #fecc56;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <h5 class="font-weight-bold text-white mb-1">{{ $user->name }}</h5>
                        <p class="text-muted small mb-2">{{ $staffDetail?->position ?: __('Practice Associate & Staff Attorney') }}</p>
                        
                        <div class="d-flex justify-content-center align-items-center flex-wrap mb-3" style="gap: 6px;">
                            <span class="badge" style="background: rgba(254, 204, 86, 0.15); color: #fecc56; border: 1px solid rgba(254, 204, 86, 0.3); font-size: 11px;">
                                ID: {{ $staffDetail?->staff_id ?: 'STF-' . sprintf('%04d', $user->id) }}
                            </span>
                            @if(!empty($staffDetail?->bank_account_number) || !empty($staffDetail?->payment_info))
                                <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); font-size: 11px;">
                                    <i class="fas fa-university mr-1"></i> {{ __('Direct Deposit Set') }}
                                </span>
                            @endif
                        </div>

                        <div class="border-top border-secondary pt-3 text-left small text-muted">
                            <div class="mb-2"><i class="fas fa-envelope text-warning mr-2"></i> {{ $user->email }}</div>
                            <div class="mb-2"><i class="fas fa-phone text-warning mr-2"></i> {{ $user->phone ?: __('Not Provided') }}</div>
                            <div><i class="fas fa-map-marker-alt text-warning mr-2"></i> {{ $user->address ?: __('Not Provided') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Edit Information Form -->
                <div class="col-lg-8">
                    <div class="staff-settings-card">
                        <div class="staff-settings-card-header">
                            <h6 class="font-weight-bold text-warning mb-0">
                                <i class="fas fa-edit mr-2"></i>{{ __('Edit Personal Contact Profile') }}
                            </h6>
                            <span class="text-muted small">{{ __('Saved to Firm Personnel File') }}</span>
                        </div>
                        <div class="staff-settings-card-body">
                            <form action="{{ route('staff.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-white">{{ __('Full Legal Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-white">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-white">{{ __('Direct Phone Number') }}</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="e.g. (555) 234-5678">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-white">{{ __('Profile Photo') }}</label>
                                        <input type="file" name="photo" class="form-control p-1" accept="image/*">
                                        <small class="text-muted">{{ __('Accepted: JPG, PNG, WEBP (Max 3MB)') }}</small>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label class="font-weight-bold text-white">{{ __('Home / Mailing Address') }}</label>
                                        <textarea name="address" rows="2" class="form-control" placeholder="Street, City, State, ZIP">{{ old('address', $user->address) }}</textarea>
                                    </div>
                                </div>

                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-gold-save">
                                        <i class="fas fa-save mr-1"></i> {{ __('Save Profile Changes') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: PASSWORD & SECURITY -->
        <div class="tab-pane fade" id="tab-security" role="tabpanel" aria-labelledby="security-tab">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="staff-settings-card">
                        <div class="staff-settings-card-header">
                            <h6 class="font-weight-bold text-warning mb-0">
                                <i class="fas fa-lock mr-2"></i>{{ __('Update Practice Password & Security') }}
                            </h6>
                            <span class="badge badge-info">{{ __('256-Bit Encrypted') }}</span>
                        </div>
                        <div class="staff-settings-card-body">
                            <form action="{{ route('staff.password.update') }}" method="POST">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-white">{{ __('Current Password') }} <span class="text-danger">*</span></label>
                                    <input type="password" name="current_password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-white">{{ __('New Password') }} <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="new-password">
                                    <small class="text-muted">{{ __('Minimum 8 characters with letters, numbers, and symbols.') }}</small>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-white">{{ __('Confirm New Password') }} <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required autocomplete="new-password">
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-gold-save">
                                        <i class="fas fa-shield-alt mr-1"></i> {{ __('Update Password') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- 4-Digit Security PIN Card for Fast 2FA Access -->
                    <div class="staff-settings-card mt-4">
                        <div class="staff-settings-card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="font-weight-bold text-warning mb-0">
                                    <i class="fas fa-key mr-2"></i>{{ __('4-Digit Security PIN (Fast 2FA Gate Login)') }}
                                </h6>
                                <small class="text-muted">{{ __('Use this numeric PIN to log in instantly at the Security Gate without waiting for email codes.') }}</small>
                            </div>
                            <span class="badge {{ $user->pin_hash ? 'badge-success' : 'badge-warning' }}" style="font-size: 11px;">
                                <i class="fas {{ $user->pin_hash ? 'fa-check-circle' : 'fa-exclamation-triangle' }} mr-1"></i>
                                {{ $user->pin_hash ? __('PIN Configured & Active') : __('PIN Not Set (Email OTP Only)') }}
                            </span>
                        </div>
                        <div class="staff-settings-card-body">
                            <form action="{{ route('staff.pin.update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label class="font-weight-bold text-white">{{ __('Create / Update 4-Digit PIN') }} <span class="text-danger">*</span></label>
                                        <input type="password" name="pin" maxlength="4" pattern="[0-9]{4}" inputmode="numeric" class="form-control text-center font-weight-bold" style="letter-spacing: 8px; font-size: 20px;" placeholder="••••" required autocomplete="off">
                                        <small class="text-muted">{{ __('Enter 4 numeric digits.') }}</small>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label class="font-weight-bold text-white">{{ __('Confirm Security PIN') }} <span class="text-danger">*</span></label>
                                        <input type="password" name="pin_confirmation" maxlength="4" pattern="[0-9]{4}" inputmode="numeric" class="form-control text-center font-weight-bold" style="letter-spacing: 8px; font-size: 20px;" placeholder="••••" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-gold-save">
                                        <i class="fas fa-lock mr-1"></i> {{ __('Save Security PIN') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: EMPLOYMENT & PRACTITIONER DOSSIER -->
        <div class="tab-pane fade" id="tab-employment" role="tabpanel" aria-labelledby="employment-tab">
            <div class="staff-settings-card">
                <div class="staff-settings-card-header">
                    <h6 class="font-weight-bold text-warning mb-0">
                        <i class="fas fa-briefcase mr-2"></i>{{ __('Firm Employment Record & Credentials') }}
                    </h6>
                    <span class="badge badge-success">{{ __('Active') }}</span>
                </div>
                <div class="staff-settings-card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="p-3 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid #28303f;">
                                <small class="text-muted text-uppercase font-weight-bold d-block">{{ __('Practitioner ID') }}</small>
                                <strong class="text-warning" style="font-size: 16px;">{{ $staffDetail?->staff_id ?: 'STF-' . sprintf('%04d', $user->id) }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="p-3 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid #28303f;">
                                <small class="text-muted text-uppercase font-weight-bold d-block">{{ __('Position & Title') }}</small>
                                <strong class="text-white" style="font-size: 16px;">{{ $staffDetail?->position ?: __('Practice Associate & Staff Attorney') }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="p-3 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid #28303f;">
                                <small class="text-muted text-uppercase font-weight-bold d-block">{{ __('Assigned Supervisor / Principal') }}</small>
                                <strong class="text-white" style="font-size: 16px;">{{ $officer?->name ?: __('Gerald W. Allen, Attn, Esq.') }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="p-3 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid #28303f;">
                                <small class="text-muted text-uppercase font-weight-bold d-block">{{ __('Standard Hourly Rate') }}</small>
                                <strong class="text-success" style="font-size: 16px;">${{ number_format($staffDetail?->hourly_rate ?: 0, 2) }} / hr</strong>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="p-3 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid #28303f;">
                                <small class="text-muted text-uppercase font-weight-bold d-block">{{ __('Pay Schedule & Cycle') }}</small>
                                <strong class="text-white" style="font-size: 16px;">{{ ucfirst($staffDetail?->pay_schedule ?: 'Bi-weekly') }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="p-3 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid #28303f;">
                                <small class="text-muted text-uppercase font-weight-bold d-block">{{ __('Direct Deposit Preferences') }}</small>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <strong class="text-white">{{ ucfirst($staffDetail?->payment_method ?: 'Direct Deposit') }}</strong>
                                    <a href="{{ route('staff.payment-method') }}" class="btn btn-sm btn-outline-warning font-weight-bold">
                                        <i class="fas fa-university mr-1"></i> {{ __('Configure Banking') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

