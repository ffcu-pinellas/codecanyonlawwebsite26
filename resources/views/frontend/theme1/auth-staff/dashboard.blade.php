@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    .staff-portal-wrap {
        color: #f1f5f9;
    }
    .exec-welcome-card {
        background: linear-gradient(135deg, #0b132b 0%, #1c2541 60%, #1e293b 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        position: relative;
        overflow: hidden;
    }
    .exec-welcome-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(212, 175, 55, 0.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .staff-avatar-ring {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d4af37, #f39c12);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0b132b;
        font-weight: 800;
        font-size: 1.8rem;
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.4);
        flex-shrink: 0;
    }
    .badge-exec-gold {
        background: rgba(212, 175, 55, 0.15);
        color: #f1c40f;
        border: 1px solid rgba(212, 175, 55, 0.3);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .badge-exec-green {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
    }
    .badge-exec-warn {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
    }
    .exec-kpi-card {
        background: #111827;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 22px;
        height: 100%;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .exec-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        border-color: rgba(212, 175, 55, 0.3);
    }
    .kpi-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }
    .kpi-icon-gold { background: rgba(212, 175, 55, 0.15); color: #f1c40f; }
    .kpi-icon-green { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .kpi-icon-blue { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
    .kpi-icon-purple { background: rgba(168, 85, 247, 0.15); color: #a855f7; }
    
    .attendance-dock-card {
        background: #111827;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 25px;
    }
    .pulse-radar-dot {
        width: 12px;
        height: 12px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 rgba(16, 185, 129, 0.6);
        animation: pulseRadar 1.8s infinite;
    }
    @keyframes pulseRadar {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { box-shadow: 0 0 0 12px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .exec-timer-display {
        font-family: 'Courier New', Courier, monospace;
        font-weight: 800;
        font-size: 2.8rem;
        letter-spacing: 2px;
        color: #f1c40f;
        text-shadow: 0 0 15px rgba(241, 196, 15, 0.3);
    }
    .exec-clock-display {
        font-family: 'Courier New', Courier, monospace;
        font-weight: 800;
        font-size: 2.5rem;
        letter-spacing: 2px;
        color: #94a3b8;
    }
    .btn-exec-clockin {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: white;
        font-weight: 700;
        font-size: 1.15rem;
        padding: 16px 45px;
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35);
        transition: all 0.2s ease;
    }
    .btn-exec-clockin:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5);
        color: white;
    }
    .btn-exec-clockout {
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        color: white;
        font-weight: 700;
        font-size: 1.15rem;
        padding: 16px 45px;
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.35);
        transition: all 0.2s ease;
    }
    .btn-exec-clockout:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.5);
        color: white;
    }
    .quick-action-tile {
        background: #1e293b;
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        padding: 18px;
        color: #f1f5f9;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.2s ease;
    }
    .quick-action-tile:hover {
        background: #334155;
        border-color: rgba(212, 175, 55, 0.4);
        color: #ffffff;
        text-decoration: none;
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4 staff-portal-wrap">

    <!-- Top Executive Profile Header -->
    <div class="exec-welcome-card">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center flex-wrap" style="gap: 20px;">
                    <div class="staff-avatar-ring">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="d-flex align-items-center flex-wrap mb-1" style="gap: 10px;">
                            <h3 class="font-weight-bold text-white mb-0">{{ $user->name }}</h3>
                            <span class="badge-exec-green"><i class="fas fa-check-circle mr-1"></i> {{ __('Active Staff') }}</span>
                            <span class="badge-exec-gold"><i class="fas fa-id-badge mr-1"></i> {{ $staffDetail->staff_id }}</span>
                        </div>
                        <p class="text-white-50 mb-2">
                            {{ $staffDetail->position ?: __('CPA Practice Associate & Staff Attorney') }}
                            &bull; <span class="text-light"><i class="fas fa-envelope mr-1"></i> {{ $user->email }}</span>
                            @if($user->phone)
                                &bull; <span class="text-light"><i class="fas fa-phone mr-1"></i> {{ $user->phone }}</span>
                            @endif
                        </p>
                        <div>
                            @if(!empty($staffDetail->bank_account_number) || !empty($staffDetail->payment_info))
                                <span class="badge-exec-green"><i class="fas fa-university mr-1"></i> {{ __('Direct Deposit Verified') }}</span>
                            @else
                                <a href="{{ route('staff.payment-method') }}" class="badge-exec-warn text-decoration-none">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ __('Direct Deposit Setup Needed') }}
                                </a>
                            @endif
                            <span class="text-muted small ml-2">
                                <i class="fas fa-calendar-alt mr-1"></i> Hired: {{ $staffDetail->hired_at ? $staffDetail->hired_at->format('M d, Y') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0 border-left-lg border-secondary pl-lg-4">
                <div class="small text-muted text-uppercase font-weight-bold mb-1">{{ __('Firm Timezone (UTC/EST)') }}</div>
                <div class="h5 text-warning font-weight-bold mb-0" id="live-firm-clock">{{ date('h:i:s A') }}</div>
                <div class="small text-white-50">{{ date('l, F d, Y') }}</div>
            </div>
        </div>
    </div>

    <!-- 4-Grid KPI Metric Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="exec-kpi-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="text-muted small text-uppercase font-weight-bold">{{ __('Hours Logged') }}</span>
                        <h3 class="text-white font-weight-bold mb-0 mt-1">{{ $totalHoursWorked }} <small class="text-muted font-weight-normal" style="font-size: 0.9rem;">hrs</small></h3>
                    </div>
                    <div class="kpi-icon-box kpi-icon-blue">
                        <i class="fas fa-history"></i>
                    </div>
                </div>
                <div class="text-muted small border-top border-secondary pt-2 mt-2">
                    <i class="fas fa-business-time mr-1 text-info"></i> {{ __('Cumulative cycle hours') }}
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="exec-kpi-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="text-muted small text-uppercase font-weight-bold">{{ __('Earned Compensation') }}</span>
                        <h3 class="text-success font-weight-bold mb-0 mt-1">${{ number_format($totalEarned, 2) }}</h3>
                    </div>
                    <div class="kpi-icon-box kpi-icon-green">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="text-muted small border-top border-secondary pt-2 mt-2">
                    <span class="text-light font-weight-bold">${{ number_format($staffDetail->hourly_rate, 2) }}/hr</span> {{ __('Standard Rate') }}
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="exec-kpi-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="text-muted small text-uppercase font-weight-bold">{{ __('Active Tasks') }}</span>
                        <h3 class="text-warning font-weight-bold mb-0 mt-1">{{ $pendingTasksCount ?? 0 }}</h3>
                    </div>
                    <div class="kpi-icon-box kpi-icon-gold">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
                <div class="text-muted small border-top border-secondary pt-2 mt-2 d-flex justify-content-between align-items-center">
                    <span>{{ __('Pending / In Progress') }}</span>
                    <a href="{{ route('staff.tasks.index') }}" class="text-warning font-weight-bold text-decoration-none">{{ __('Kanban') }} &rarr;</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="exec-kpi-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="text-muted small text-uppercase font-weight-bold">{{ __('Approved Reimbursement') }}</span>
                        <h3 class="text-info font-weight-bold mb-0 mt-1">${{ number_format($approvedReimbursementTotal ?? 0, 2) }}</h3>
                    </div>
                    <div class="kpi-icon-box kpi-icon-purple">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="text-muted small border-top border-secondary pt-2 mt-2 d-flex justify-content-between align-items-center">
                    <span>{{ __('Employer Payable') }}</span>
                    <a href="{{ route('staff.financial-ledger') }}" class="text-info font-weight-bold text-decoration-none">{{ __('Ledger') }} &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Dock & Weekly Analytics Row -->
    <div class="row">
        <!-- Interactive Attendance Dock -->
        <div class="col-lg-6 mb-4">
            <div class="attendance-dock-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-3 mb-4">
                        <div class="d-flex align-items-center" style="gap: 10px;">
                            <div class="kpi-icon-box kpi-icon-blue" style="width: 38px; height: 38px; font-size: 1rem;">
                                <i class="fas fa-business-time"></i>
                            </div>
                            <h5 class="font-weight-bold text-white mb-0">{{ __('Attendance & Time Punch') }}</h5>
                        </div>
                        @if($activeLog)
                            <span class="badge-exec-green d-flex align-items-center" style="gap: 6px;">
                                <span class="pulse-radar-dot"></span> {{ __('CLOCKED IN') }}
                            </span>
                        @else
                            <span class="badge-exec-gold">{{ __('OFF DUTY') }}</span>
                        @endif
                    </div>

                    <div class="text-center py-4">
                        @if($activeLog)
                            <p class="text-muted small text-uppercase font-weight-bold mb-1">{{ __('Current Session Elapsed') }}</p>
                            <div class="exec-timer-display mb-3" id="session-timer">00:00:00</div>
                            <p class="text-white-50 small mb-4">
                                <i class="fas fa-clock mr-1 text-success"></i> Session started at: <strong class="text-white">{{ $activeLog->clocked_in_at->format('h:i:s A') }}</strong>
                            </p>
                            <form id="clock-out-form" action="{{ route('staff.clock-out') }}" method="POST">
                                @csrf
                                <input type="hidden" name="latitude" id="clock-out-lat">
                                <input type="hidden" name="longitude" id="clock-out-lng">
                                <button type="button" onclick="submitWithLocation('clock-out-form')" class="btn btn-exec-clockout btn-lg px-5">
                                    <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Punch Out Now') }}
                                </button>
                            </form>
                        @else
                            <p class="text-muted small text-uppercase font-weight-bold mb-1">{{ __('Local Clock Time') }}</p>
                            <div class="exec-clock-display mb-3" id="digital-clock">00:00:00 AM</div>
                            <p class="text-white-50 small mb-4">
                                <i class="fas fa-map-marker-alt mr-1 text-info"></i> Ready to start shift with secure GPS verification
                            </p>
                            <form id="clock-in-form" action="{{ route('staff.clock-in') }}" method="POST">
                                @csrf
                                <input type="hidden" name="latitude" id="clock-in-lat">
                                <input type="hidden" name="longitude" id="clock-in-lng">
                                <button type="button" onclick="submitWithLocation('clock-in-form')" class="btn btn-exec-clockin btn-lg px-5">
                                    <i class="fas fa-sign-in-alt mr-2"></i> {{ __('Punch In Now') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="border-top border-secondary pt-3 mt-4">
                    <div class="row text-center text-muted small">
                        <div class="col-4 border-right border-secondary">
                            <span class="d-block text-uppercase font-weight-bold" style="font-size: 0.65rem;">Hourly Rate</span>
                            <span class="text-white font-weight-bold">${{ number_format($staffDetail->hourly_rate, 2) }}/hr</span>
                        </div>
                        <div class="col-4 border-right border-secondary">
                            <span class="d-block text-uppercase font-weight-bold" style="font-size: 0.65rem;">Hours Worked</span>
                            <span class="text-white font-weight-bold">{{ $totalHoursWorked }} hrs</span>
                        </div>
                        <div class="col-4">
                            <span class="d-block text-uppercase font-weight-bold" style="font-size: 0.65rem;">Total Earned</span>
                            <span class="text-success font-weight-bold">${{ number_format($totalEarned, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weekly Performance Area Chart -->
        <div class="col-lg-6 mb-4">
            <div class="attendance-dock-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-3 mb-3">
                        <div class="d-flex align-items-center" style="gap: 10px;">
                            <div class="kpi-icon-box kpi-icon-gold" style="width: 38px; height: 38px; font-size: 1rem;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h5 class="font-weight-bold text-white mb-0">{{ __('Weekly Hours Velocity') }}</h5>
                        </div>
                        <span class="text-muted small">{{ __('Last 7 Days') }}</span>
                    </div>
                    <div id="weekly-hours-chart" style="min-height: 250px;"></div>
                </div>
                <div class="border-top border-secondary pt-2 text-right">
                    <a href="{{ route('staff.financial-ledger') }}" class="text-warning small font-weight-bold text-decoration-none">
                        {{ __('View Detailed Timesheet History') }} &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Operations Hub -->
    <div class="attendance-dock-card mb-4">
        <h5 class="font-weight-bold text-white mb-3"><i class="fas fa-th-large mr-2 text-warning"></i>{{ __('Staff Operations & Quick Hub') }}</h5>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-3">
                <a href="{{ route('staff.tasks.index') }}" class="quick-action-tile">
                    <div class="kpi-icon-box kpi-icon-gold">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold">{{ __('Interactive Kanban Tasks') }}</div>
                        <div class="text-muted small">{{ __('Manage assigned cases & deliverables') }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <a href="{{ route('staff.financial-ledger') }}" class="quick-action-tile">
                    <div class="kpi-icon-box kpi-icon-green">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold">{{ __('Financial Ledger & Payouts') }}</div>
                        <div class="text-muted small">{{ __('Ledger balance, bonuses & pay history') }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <a href="javascript:void(0)" data-toggle="modal" data-target="#quickReceiptModal" class="quick-action-tile">
                    <div class="kpi-icon-box kpi-icon-purple">
                        <i class="fas fa-camera"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold">{{ __('Scan Expense Receipt') }}</div>
                        <div class="text-muted small">{{ __('Snap photo or upload reimbursement receipt') }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <a href="{{ route('staff.messages') }}" class="quick-action-tile">
                    <div class="kpi-icon-box kpi-icon-blue">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold">{{ __('Counsel & Internal Chat') }}</div>
                        <div class="text-muted small">{{ __('Direct encrypted messaging with partners') }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <a href="{{ route('staff.payment-method') }}" class="quick-action-tile">
                    <div class="kpi-icon-box kpi-icon-gold">
                        <i class="fas fa-university"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold">{{ __('Direct Deposit Routing') }}</div>
                        <div class="text-muted small">{{ __('Manage checking/savings account details') }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <a href="{{ route('staff.direct-deposit-form.download') }}" class="quick-action-tile">
                    <div class="kpi-icon-box kpi-icon-green">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold">{{ __('Download Direct Deposit PDF') }}</div>
                        <div class="text-muted small">{{ __('Pre-filled official authorization agreement') }}</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Quick Receipt Scanner & Reimbursement -->
<div class="modal fade" id="quickReceiptModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background:#111827; color:#f1f5f9; border:1px solid rgba(255,255,255,0.1);">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-weight-bold text-white"><i class="fas fa-camera mr-2 text-warning"></i>{{ __('Scan & Submit Receipt') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('staff.reimbursement.request') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="small text-muted text-uppercase font-weight-bold">{{ __('Expense / Receipt Description') }} <span class="text-danger">*</span></label>
                        <input type="text" name="description" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Court filing fees or client file courier" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small text-muted text-uppercase font-weight-bold">{{ __('Amount ($)') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text bg-secondary text-white border-secondary">$</span></div>
                            <input type="number" step="0.01" min="0.01" name="amount" class="form-control bg-dark text-white border-secondary" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small text-muted text-uppercase font-weight-bold">{{ __('Snap Receipt or Upload') }} <span class="text-danger">*</span></label>
                        <input type="file" name="attachment" id="quickReceiptFileInput" accept="image/*,application/pdf" capture="environment" class="form-control-file text-white" required onchange="previewQuickReceipt(this)">
                        <small class="text-muted d-block mt-1">{{ __('Tap to use camera on mobile, or select PDF/Image from computer.') }}</small>
                        <div id="quickReceiptPreviewBox" class="mt-2 text-center" style="display:none;">
                            <img id="quickReceiptPreviewImg" src="" style="max-height: 140px; border-radius: 8px; border: 1px solid #475569;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-warning font-weight-bold px-4">{{ __('Submit for Approval') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    function previewQuickReceipt(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('quickReceiptPreviewImg').src = e.target.result;
                    document.getElementById('quickReceiptPreviewBox').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }
    }

    function submitWithLocation(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const latField = form.querySelector('input[name="latitude"]');
                    const lngField = form.querySelector('input[name="longitude"]');
                    if (latField) latField.value = position.coords.latitude;
                    if (lngField) lngField.value = position.coords.longitude;
                    form.submit();
                },
                function(error) {
                    console.warn("Geolocation fallback: ", error.message);
                    form.submit();
                },
                { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
            );
        } else {
            form.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Digital clock update
        const clockElement = document.getElementById('digital-clock');
        const liveClock = document.getElementById('live-firm-clock');

        setInterval(() => {
            const now = new Date();
            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            const timeStr = `${String(hours).padStart(2, '0')}:${minutes}:${seconds} ${ampm}`;

            if (clockElement) clockElement.textContent = timeStr;
            if (liveClock) liveClock.textContent = timeStr;
        }, 1000);

        // Stopwatch for active attendance session
        @if($activeLog)
            const clockedInTime = new Date('{{ $activeLog->clocked_in_at->toIso8601String() }}');
            const timerElement = document.getElementById('session-timer');

            setInterval(() => {
                const now = new Date();
                const diffMs = now - clockedInTime;
                
                if (diffMs > 0) {
                    const diffSecs = Math.floor(diffMs / 1000);
                    const hours = Math.floor(diffSecs / 3600);
                    const minutes = Math.floor((diffSecs % 3600) / 60);
                    const seconds = diffSecs % 60;

                    timerElement.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }
            }, 1000);
        @endif

        // ApexCharts Performance
        const options = {
            series: [{
                name: 'Hours Worked',
                data: @json($weeklyHours)
            }],
            chart: {
                type: 'area',
                height: 250,
                toolbar: { show: false },
                zoom: { enabled: false },
                background: 'transparent'
            },
            theme: {
                mode: 'dark'
            },
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#f1c40f']
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 100]
                }
            },
            colors: ['#f1c40f'],
            xaxis: {
                categories: @json($weeklyDays),
                labels: {
                    style: {
                        colors: '#94a3b8',
                        fontSize: '11px',
                        fontFamily: 'Montserrat, sans-serif'
                    }
                },
                axisBorder: { color: 'rgba(255,255,255,0.1)' }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#94a3b8',
                        fontSize: '11px',
                        fontFamily: 'Montserrat, sans-serif'
                    }
                }
            },
            grid: {
                borderColor: 'rgba(255,255,255,0.06)'
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function(val) {
                        return val + " hrs";
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#weekly-hours-chart"), options);
        chart.render();
    });
</script>
@endsection
