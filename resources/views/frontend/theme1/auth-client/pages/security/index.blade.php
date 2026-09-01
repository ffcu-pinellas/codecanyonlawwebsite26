@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | Security & Authentication Desk')

@section('page-css')
<style>
    .security-hero-title {
        color: #fecc56;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-size: 1.4rem;
    }
    .security-card {
        background: #161a23;
        border: 1px solid #28303f;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        height: 100%;
        overflow: hidden;
    }
    .security-card-header {
        background: #1f2533;
        border-bottom: 1px solid #2e3849;
        padding: 16px 20px;
        color: #fecc56;
        font-weight: 700;
        font-size: 13.5px;
    }
    .security-card-body {
        padding: 22px;
    }
    
    .btn-gold {
        background: linear-gradient(135deg, #fecc56, #f0a500);
        color: #000 !important;
        border: none;
        font-weight: 700;
        border-radius: 6px;
        padding: 7px 16px;
        font-size: 12.5px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-gold:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(254,204,86,0.45);
    }
    .btn-portal-secondary {
        background: #1f2533;
        color: #e2e8f0;
        border: 1px solid #374151;
        font-weight: 600;
        border-radius: 6px;
        padding: 7px 14px;
        font-size: 12.5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .btn-portal-secondary:hover {
        background: #28303f;
        color: #fff;
    }

    .watchdog-box {
        background: #11151e;
        border: 1px solid #28303f;
        border-radius: 8px;
        padding: 14px;
        height: 100%;
    }
    
    /* TABLE STYLING */
    .table-security {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }
    .table-security thead th {
        background: #191f2c;
        color: #fecc56;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-top: none;
        border-bottom: 2px solid #2e3849;
        padding: 12px 16px;
        white-space: nowrap;
    }
    .table-security tbody tr {
        border-bottom: 1px solid #222936;
        transition: background 0.15s;
    }
    .table-security tbody tr:hover {
        background: rgba(254,204,86,0.03);
    }
    .table-security td {
        padding: 12px 16px;
        vertical-align: middle;
        font-size: 12.5px;
        color: #e2e8f0;
    }

    /* LIGHT MODE STYLING */
    body.light-mode .security-card, html.light-mode .security-card {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06) !important;
    }
    body.light-mode .security-card-header, html.light-mode .security-card-header {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
        color: #b45309 !important;
    }
    body.light-mode .watchdog-box, html.light-mode .watchdog-box {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .security-hero-title, html.light-mode .security-hero-title {
        color: #0f172a !important;
    }
    body.light-mode .table-security thead th, html.light-mode .table-security thead th {
        background: #f8fafc !important;
        color: #b45309 !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .table-security tbody tr, html.light-mode .table-security tbody tr {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .table-security td, html.light-mode .table-security td {
        color: #334155 !important;
        border-color: #e2e8f0 !important;
        background: #ffffff !important;
    }
    body.light-mode .table-security td strong, html.light-mode .table-security td strong {
        color: #0f172a !important;
    }
    body.light-mode .modal-content, html.light-mode .modal-content {
        background: #ffffff !important;
        color: #0f172a !important;
        border-color: #cbd5e1 !important;
    }
    body.light-mode .modal-header, html.light-mode .modal-header {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .modal-footer, html.light-mode .modal-footer {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .modal-body .form-control, html.light-mode .modal-body .form-control {
        background: #ffffff !important;
        color: #0f172a !important;
        border-color: #cbd5e1 !important;
    }

    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0 py-2">

    <!-- PAGE HEADER -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4" style="gap: 12px;">
        <div>
            <h4 class="security-hero-title mb-1">
                <i class="fas fa-user-shield mr-2"></i> {{ __('Security & Authentication Desk') }}
            </h4>
            <p class="text-muted small mb-0">
                {{ __('Review active portal sessions, authorized devices, and automated sign-in alerts.') }}
            </p>
        </div>
        <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
            <button type="button" class="btn-portal-secondary" data-toggle="modal" data-target="#changePasswordModal">
                <i class="fas fa-key text-warning mr-1"></i> {{ __('Change Password') }}
            </button>
            <button type="button" class="btn-gold" data-toggle="modal" data-target="#securityPinModal">
                <i class="fas fa-shield-alt mr-1"></i> {{ __('Security PIN') }}
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm font-weight-bold mb-4" style="background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3);">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm font-weight-bold mb-4" style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3);">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <!-- SECTION 1: ACTIVE SESSION & WATCHDOG -->
    <div class="row mb-4">
        <!-- Current Active Session Card -->
        <div class="col-lg-5 mb-3 mb-lg-0">
            <div class="security-card">
                <div class="security-card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-desktop mr-2 text-success"></i> {{ __('Current Active Session') }}</span>
                    <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 10.5px;">
                        <i class="fas fa-circle mr-1" style="font-size: 7px;"></i> {{ __('Online Now') }}
                    </span>
                </div>
                <div class="security-card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 52px; height: 52px; background: rgba(34, 197, 94, 0.15); color: #4ade80; font-size: 24px; border: 1px solid rgba(34, 197, 94, 0.3);">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-white mb-0" style="font-size: 16px;">{{ $currentSession->device_type }}</h5>
                            <small class="text-muted">{{ $currentSession->browser }} on {{ $currentSession->platform }}</small>
                        </div>
                    </div>

                    <div class="p-3 rounded mb-4" style="background: #11151e; border: 1px solid #28303f;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-muted">{{ __('Connected IP:') }}</span>
                            <span class="small font-weight-bold text-warning font-monospace">{{ $currentSession->ip }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-muted">{{ __('Protocol Security:') }}</span>
                            <span class="small font-weight-bold text-success"><i class="fas fa-lock mr-1"></i> {{ $currentSession->protocol }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">{{ __('Session Established:') }}</span>
                            <span class="small text-light">{{ $currentSession->online_since }}</span>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-danger btn-block btn-sm font-weight-bold py-2" data-toggle="modal" data-target="#logoutOtherDevicesModal">
                        <i class="fas fa-sign-out-alt mr-1"></i> {{ __('Sign Out All Other Devices') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Automated Threat & Location Watchdog Card -->
        <div class="col-lg-7">
            <div class="security-card">
                <div class="security-card-header">
                    <span><i class="fas fa-shield-virus mr-2 text-warning"></i> {{ __('Automated Threat & Location Watchdog') }}</span>
                </div>
                <div class="security-card-body">
                    <p class="small text-muted mb-4" style="line-height: 1.6;">
                        {{ __('Our cyber intelligence engine verifies every sign-in attempt against known network signatures. Any access from an unrecognized IP, device, or geographic location triggers an') }} <strong class="text-warning">{{ __('Instant Security Email Alert') }}</strong> {{ __('to') }} <strong class="text-white">{{ $user->email }}</strong>.
                    </p>

                    <div class="row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <div class="watchdog-box">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-envelope-open-text text-warning mr-2"></i>
                                    <strong class="text-white small">{{ __('Instant Sign-In Alerts') }}</strong>
                                </div>
                                <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 10px;">
                                    <i class="fas fa-check mr-1"></i> {{ __('Active on') }} {{ $user->email }}
                                </span>
                                <small class="text-muted d-block mt-2" style="font-size: 11px;">
                                    {{ __('Immediate email notification with IP geolocation & device telemetry.') }}
                                </small>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="watchdog-box">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-fingerprint text-warning mr-2"></i>
                                    <strong class="text-white small">{{ __('Auto-Inactivity Lock') }}</strong>
                                </div>
                                @if($user->pin_hash)
                                    <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 10px;">
                                        <i class="fas fa-check mr-1"></i> {{ __('Enabled (10 Min PIN Lock)') }}
                                    </span>
                                @else
                                    <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" style="font-size: 10px;">
                                        <i class="fas fa-clock mr-1"></i> {{ __('PIN Not Configured') }}
                                    </span>
                                @endif
                                <small class="text-muted d-block mt-2" style="font-size: 11px;">
                                    {{ __('Requires 4-digit PIN verification when executing contracts or settlements.') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: SIGN-IN & DEVICE ACCESS HISTORY (IFW EXACT REPLICA) -->
    <div class="security-card">
        <div class="security-card-header d-flex justify-content-between align-items-center flex-wrap" style="gap: 8px;">
            <span><i class="fas fa-history mr-2 text-warning"></i> {{ __('Sign-In & Device Access History (Past 30 Events)') }}</span>
            <span class="badge badge-dark text-muted border border-secondary px-3 py-1 font-weight-bold" style="font-size: 11px;">
                <i class="fas fa-lock text-success mr-1"></i> {{ __('Immutable Security Audit') }}
            </span>
        </div>
        <div class="table-responsive">
            <table class="table-security">
                <thead>
                    <tr>
                        <th>{{ __('Timestamp (UTC)') }}</th>
                        <th>{{ __('Device / Platform') }}</th>
                        <th>{{ __('Browser') }}</th>
                        <th>{{ __('IP Address') }}</th>
                        <th>{{ __('Security Flag') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historyList as $item)
                        <tr>
                            <td>
                                <strong class="text-white d-block">{{ $item['timestamp'] ?? now()->format('M j, Y, g:i a') }}</strong>
                            </td>
                            <td>
                                <i class="fas fa-laptop text-warning mr-1"></i> 
                                <span class="font-weight-bold text-white">{{ $item['device'] ?? 'Desktop PC' }}</span>
                                <small class="text-muted d-block">{{ $item['platform'] ?? 'Windows 10/11' }}</small>
                            </td>
                            <td>
                                <span class="text-light">{{ $item['browser'] ?? 'Mozilla Firefox' }}</span>
                            </td>
                            <td>
                                <span class="font-monospace text-warning">{{ $item['ip'] ?? $currentSession->ip }}</span>
                            </td>
                            <td>
                                <span class="badge badge-secondary px-2 py-1 font-weight-bold" style="background: rgba(148,163,184,0.15); color: #cbd5e1; border: 1px solid rgba(148,163,184,0.3); font-size: 11px;">
                                    <i class="fas fa-check mr-1 text-success"></i> {{ $item['flag'] ?? __('Recognized Device') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 11px;">
                                    {{ $item['status'] ?? __('Authorized Session') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL 1: CHANGE PASSWORD -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark text-white border-secondary" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header border-secondary py-3 px-4" style="background: #1f2533;">
                <h5 class="modal-title text-warning font-weight-bold">
                    <i class="fas fa-key mr-2"></i> {{ __('Update Account Password') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('client.password-update') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-light">{{ __('Current Password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control bg-black text-white border-secondary" required placeholder="••••••••">
                    </div>
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-light">{{ __('New Permanent Password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control bg-black text-white border-secondary" required placeholder="At least 8 characters">
                    </div>
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-light">{{ __('Confirm New Password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control bg-black text-white border-secondary" required placeholder="••••••••">
                    </div>
                </div>
                <div class="modal-footer border-secondary py-2 px-4" style="background: #1f2533;">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-warning btn-sm font-weight-bold text-dark">{{ __('Save Password') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 2: SECURITY PIN -->
<div class="modal fade" id="securityPinModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark text-white border-warning" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header border-secondary py-3 px-4" style="background: #1f2533;">
                <h5 class="modal-title text-warning font-weight-bold">
                    <i class="fas fa-shield-alt mr-2"></i> {{ __('4-Digit Security PIN') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('client.security.set-pin') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        {{ __('Your 4-digit PIN secures legal agreements, e-signatures, retainer disbursements, and security-sensitive modifications.') }}
                    </p>
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-light">{{ __('New 4-Digit PIN') }} <span class="text-danger">*</span></label>
                        <input type="password" name="pin" maxlength="4" pattern="[0-9]{4}" class="form-control bg-black text-white border-secondary text-center font-weight-bold" style="font-size: 20px; letter-spacing: 8px;" placeholder="••••" required autocomplete="off">
                    </div>
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-light">{{ __('Confirm 4-Digit PIN') }} <span class="text-danger">*</span></label>
                        <input type="password" name="pin_confirmation" maxlength="4" pattern="[0-9]{4}" class="form-control bg-black text-white border-secondary text-center font-weight-bold" style="font-size: 20px; letter-spacing: 8px;" placeholder="••••" required autocomplete="off">
                    </div>
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-light">{{ __('Account Password (to authorize change)') }} <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control bg-black text-white border-secondary" required placeholder="••••••••">
                    </div>
                </div>
                <div class="modal-footer border-secondary py-2 px-4" style="background: #1f2533;">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-warning btn-sm font-weight-bold text-dark">{{ __('Save PIN') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 3: SIGN OUT ALL OTHER DEVICES -->
<div class="modal fade" id="logoutOtherDevicesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark text-white border-danger" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header border-secondary py-3 px-4" style="background: #1f2533;">
                <h5 class="modal-title text-danger font-weight-bold">
                    <i class="fas fa-exclamation-triangle mr-2"></i> {{ __('Terminate All Other Sessions') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('client.security.logout-all-devices') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-light small mb-3">
                        {{ __('This will immediately invalidate and log out all active sessions across other browsers, phones, and PCs, requiring password re-entry.') }}
                    </p>
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-light">{{ __('Confirm Password to Authorize Terminations:') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control bg-black text-white border-secondary" required placeholder="••••••••">
                    </div>
                </div>
                <div class="modal-footer border-secondary py-2 px-4" style="background: #1f2533;">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger btn-sm font-weight-bold">{{ __('Sign Out All Other Devices') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
