@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    /* Top Fast-Punch Attendance Dock - Sleek Executive Floating Dock */
    .top-punch-dock {
        background: #11151e;
        border: 1px solid #28303f;
        border-radius: 10px;
        padding: 12px 20px;
        margin-bottom: 18px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }
    .pulse-radar-dot {
        width: 9px;
        height: 9px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 rgba(16, 185, 129, 0.6);
        animation: pulseRadar 1.8s infinite;
    }
    @keyframes pulseRadar {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .dock-timer-digits {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-weight: 800;
        font-size: 1.45rem;
        letter-spacing: 1.5px;
        color: #fecc56;
        text-shadow: 0 0 10px rgba(254, 204, 86, 0.25);
        line-height: 1;
    }
    .dock-clock-digits {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-weight: 800;
        font-size: 1.35rem;
        letter-spacing: 1.5px;
        color: #cbd5e1;
        line-height: 1;
    }
    .btn-fast-clockin {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.88rem;
        padding: 8px 20px;
        border-radius: 8px;
        border: none;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .btn-fast-clockin:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.45);
    }
    .btn-fast-clockout {
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.88rem;
        padding: 8px 20px;
        border-radius: 8px;
        border: none;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .btn-fast-clockout:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.45);
    }

    /* Executive Profile Card */
    .staff-profile-card {
        background: #11151e;
        border: 1px solid #28303f;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 18px;
    }
    .staff-avatar-ring {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fecc56, #f59e0b);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0b132b;
        font-weight: 800;
        font-size: 1.3rem;
        box-shadow: 0 0 12px rgba(254, 204, 86, 0.3);
        flex-shrink: 0;
    }
    .badge-exec-gold {
        background: rgba(254, 204, 86, 0.12);
        color: #fecc56;
        border: 1px solid rgba(254, 204, 86, 0.3);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .badge-exec-green {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .badge-exec-warn {
        background: rgba(245, 158, 11, 0.12);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* KPI Metrics */
    .metric-card {
        background: #11151e;
        border: 1px solid #28303f;
        border-radius: 12px;
        padding: 20px;
        height: 100%;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        border-color: rgba(254, 204, 86, 0.35);
    }
    .metric-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .icon-blue { background: rgba(59, 130, 246, 0.12); color: #60a5fa; }
    .icon-green { background: rgba(16, 185, 129, 0.12); color: #4ade80; }
    .icon-gold { background: rgba(254, 204, 86, 0.12); color: #fecc56; }
    .icon-purple { background: rgba(168, 85, 247, 0.12); color: #c084fc; }

    /* Action Tiles */
    .action-tile {
        background: #161a23;
        border: 1px solid #28303f;
        border-radius: 10px;
        padding: 16px;
        color: #f1f5f9;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.2s ease;
        height: 100%;
    }
    .action-tile:hover {
        background: #1f2533;
        border-color: rgba(254, 204, 86, 0.4);
        color: #ffffff;
        text-decoration: none;
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">

    <!-- 1. TOP FAST-PUNCH ATTENDANCE DOCK (INSTANT ACCESS AT VERY TOP) -->
    <div class="top-punch-dock">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center" style="gap: 14px;">
            <!-- Left: Status Indicator -->
            <div class="d-flex align-items-center" style="gap: 12px;">
                @if($activeLog)
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <span class="pulse-radar-dot"></span>
                        <span class="badge-exec-green text-uppercase">{{ __('Shift In Progress') }}</span>
                    </div>
                    <div class="d-none d-sm-block text-muted small border-left border-secondary pl-3 ml-2">
                        <i class="fas fa-sign-in-alt text-success mr-1"></i> Punched in at <strong class="text-white">{{ $activeLog->clocked_in_at->format('h:i A') }}</strong>
                    </div>
                @else
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <span class="badge-exec-gold text-uppercase">{{ __('Off Duty') }}</span>
                    </div>
                    <div class="d-none d-sm-block text-muted small border-left border-secondary pl-3 ml-2">
                        <i class="fas fa-shield-alt text-warning mr-1"></i> GPS-verified timekeeper ready
                    </div>
                @endif
            </div>

            <!-- Center: Live Digital Clock or Elapsed Duration -->
            <div class="text-center">
                @if($activeLog)
                    <div class="text-muted small text-uppercase font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">{{ __('Session Elapsed') }}</div>
                    <div class="dock-timer-digits" id="session-timer">00:00:00</div>
                @else
                    <div class="text-muted small text-uppercase font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">{{ __('Live Clock') }}</div>
                    <div class="dock-clock-digits" id="digital-clock">00:00:00 AM</div>
                @endif
            </div>

            <!-- Right: Instant Punch Button -->
            <div>
                @if($activeLog)
                    <form id="top-clock-out-form" action="{{ route('staff.clock-out') }}" method="POST">
                        @csrf
                        <input type="hidden" name="latitude" id="top-clock-out-lat">
                        <input type="hidden" name="longitude" id="top-clock-out-lng">
                        <button type="button" onclick="submitPunch('top-clock-out-form')" class="btn btn-fast-clockout">
                            <i class="fas fa-sign-out-alt mr-1"></i> {{ __('Punch Out') }}
                        </button>
                    </form>
                @else
                    <form id="top-clock-in-form" action="{{ route('staff.clock-in') }}" method="POST">
                        @csrf
                        <input type="hidden" name="latitude" id="top-clock-in-lat">
                        <input type="hidden" name="longitude" id="top-clock-in-lng">
                        <button type="button" onclick="submitPunch('top-clock-in-form')" class="btn btn-fast-clockin">
                            <i class="fas fa-sign-in-alt mr-1"></i> {{ __('Punch In Now') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- 2. EXECUTIVE PROFILE STRIP -->
    <div class="staff-profile-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center" style="gap: 16px;">
            <div class="d-flex align-items-center" style="gap: 16px;">
                <div class="staff-avatar-ring">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="d-flex align-items-center flex-wrap mb-1" style="gap: 8px;">
                        <h4 class="font-weight-bold text-white mb-0">{{ $user->name }}</h4>
                        <span class="badge-exec-gold"><i class="fas fa-id-badge mr-1"></i> {{ $staffDetail->staff_id }}</span>
                        @if(!empty($staffDetail->bank_account_number) || !empty($staffDetail->payment_info))
                            <span class="badge-exec-green"><i class="fas fa-university mr-1"></i> {{ __('Direct Deposit Active') }}</span>
                        @else
                            <a href="{{ route('staff.payment-method') }}" class="badge-exec-warn text-decoration-none">
                                <i class="fas fa-exclamation-triangle mr-1"></i> {{ __('Direct Deposit Needed') }}
                            </a>
                        @endif
                    </div>
                    <div class="text-muted small">
                        <span>{{ $staffDetail->position ?: __('CPA Practice Associate & Staff Attorney') }}</span>
                        &bull; <span class="text-light"><i class="fas fa-envelope mr-1"></i> {{ $user->email }}</span>
                        @if($user->phone)
                            &bull; <span class="text-light"><i class="fas fa-phone mr-1"></i> {{ $user->phone }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="text-md-right text-muted small">
                <div><span class="text-white-50">Assigned Supervisor:</span> <strong class="text-warning">{{ $officer?->name ?: 'Practice Principal' }}</strong></div>
                <div><span class="text-white-50">Standard Compensation:</span> <strong class="text-success">${{ number_format($staffDetail->hourly_rate, 2) }}/hr</strong></div>
            </div>
        </div>
    </div>

    <!-- 3. KPI METRIC TILES -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="metric-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="text-muted small text-uppercase font-weight-bold" style="font-size: 11px;">{{ __('Cycle Hours') }}</span>
                        <h3 class="text-white font-weight-bold mb-0 mt-1">{{ $totalHoursWorked }} <small class="text-muted" style="font-size: 13px;">hrs</small></h3>
                    </div>
                    <div class="metric-icon-box icon-blue">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="text-muted small border-top border-secondary pt-2 mt-2">
                    <i class="fas fa-calendar-check mr-1 text-info"></i> {{ __('Cumulative logged cycle') }}
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="metric-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="text-muted small text-uppercase font-weight-bold" style="font-size: 11px;">{{ __('Estimated Pay') }}</span>
                        <h3 class="text-success font-weight-bold mb-0 mt-1">${{ number_format($totalEarned, 2) }}</h3>
                    </div>
                    <div class="metric-icon-box icon-green">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="text-muted small border-top border-secondary pt-2 mt-2">
                    <span class="text-light font-weight-bold">${{ number_format($staffDetail->hourly_rate, 2) }}/hr</span> standard rate
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="metric-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="text-muted small text-uppercase font-weight-bold" style="font-size: 11px;">{{ __('Active Tasks') }}</span>
                        <h3 class="text-warning font-weight-bold mb-0 mt-1">{{ $pendingTasksCount ?? 0 }}</h3>
                    </div>
                    <div class="metric-icon-box icon-gold">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
                <div class="text-muted small border-top border-secondary pt-2 mt-2 d-flex justify-content-between align-items-center">
                    <span>Pending review</span>
                    <a href="{{ route('staff.tasks.index') }}" class="text-warning font-weight-bold text-decoration-none">{{ __('Kanban Board') }} &rarr;</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="metric-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="text-muted small text-uppercase font-weight-bold" style="font-size: 11px;">{{ __('Reimbursements') }}</span>
                        <h3 class="text-info font-weight-bold mb-0 mt-1">${{ number_format($approvedReimbursementTotal ?? 0, 2) }}</h3>
                    </div>
                    <div class="metric-icon-box icon-purple">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="text-muted small border-top border-secondary pt-2 mt-2 d-flex justify-content-between align-items-center">
                    <span>Employer payable</span>
                    <a href="{{ route('staff.financial-ledger') }}" class="text-info font-weight-bold text-decoration-none">{{ __('View Ledger') }} &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. PERFORMANCE VELOCITY & TIMESHEET HISTORY -->
    <div class="row mb-4">
        <!-- 7-Day Performance Velocity Chart -->
        <div class="col-lg-7 mb-4 mb-lg-0">
            <div class="staff-profile-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-3 mb-3">
                        <h6 class="font-weight-bold text-white mb-0">
                            <i class="fas fa-chart-area mr-2 text-warning"></i>{{ __('Weekly Velocity (Last 7 Days)') }}
                        </h6>
                        <span class="text-muted small">{{ __('Daily logged hours') }}</span>
                    </div>
                    <div id="weekly-hours-chart" style="min-height: 250px;"></div>
                </div>
                <div class="border-top border-secondary pt-2 mt-2 text-right">
                    <a href="{{ route('staff.financial-ledger') }}" class="text-warning small font-weight-bold text-decoration-none">
                        {{ __('Detailed Ledger & Paystubs') }} &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Shifts Activity Table -->
        <div class="col-lg-5">
            <div class="staff-profile-card h-100">
                <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-3 mb-3">
                    <h6 class="font-weight-bold text-white mb-0">
                        <i class="fas fa-history mr-2 text-info"></i>{{ __('Recent Shift Logs') }}
                    </h6>
                    <span class="badge badge-secondary">{{ $timeLogs->count() }} {{ __('Total') }}</span>
                </div>
                <div class="table-responsive" style="max-height: 270px; overflow-y: auto;">
                    <table class="table table-sm table-borderless mb-0">
                        <thead>
                            <tr class="text-muted small border-bottom border-secondary">
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Duration') }}</th>
                                <th class="text-right">{{ __('Wages') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($timeLogs->take(6) as $log)
                                <tr class="border-bottom border-secondary">
                                    <td class="py-2">
                                        <div class="text-white small font-weight-bold">{{ $log->clocked_in_at->format('M d, Y') }}</div>
                                        <div class="text-muted" style="font-size: 11px;">{{ $log->clocked_in_at->format('h:i A') }} - {{ $log->clocked_out_at ? $log->clocked_out_at->format('h:i A') : 'Active' }}</div>
                                    </td>
                                    <td class="py-2 text-light small align-middle">
                                        {{ round($log->duration_seconds / 3600, 2) }} hrs
                                    </td>
                                    <td class="py-2 text-success small font-weight-bold text-right align-middle">
                                        ${{ number_format($log->earned_amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">
                                        {{ __('No previous shifts recorded yet.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. OPERATIONS & QUICK ACTION HUB -->
    <div class="staff-profile-card mb-4">
        <h6 class="font-weight-bold text-white mb-3"><i class="fas fa-th-large mr-2 text-warning"></i>{{ __('Staff Operations & Quick Actions') }}</h6>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-3">
                <a href="{{ route('staff.tasks.index') }}" class="action-tile">
                    <div class="metric-icon-box icon-gold">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-white">{{ __('Kanban Task Board') }}</div>
                        <div class="text-muted small">{{ __('Manage assigned client cases & deliverables') }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <a href="{{ route('staff.financial-ledger') }}" class="action-tile">
                    <div class="metric-icon-box icon-green">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-white">{{ __('Financial Ledger') }}</div>
                        <div class="text-muted small">{{ __('Payout requests, bonuses & paystub PDFs') }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <a href="javascript:void(0)" data-toggle="modal" data-target="#quickReceiptModal" class="action-tile">
                    <div class="metric-icon-box icon-purple">
                        <i class="fas fa-camera"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-white">{{ __('Snap Expense Receipt') }}</div>
                        <div class="text-muted small">{{ __('Mobile camera scanner for instant reimbursement') }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <a href="{{ route('staff.messages') }}" class="action-tile">
                    <div class="metric-icon-box icon-blue">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-white">{{ __('Internal Team Chat') }}</div>
                        <div class="text-muted small">{{ __('Direct secure communications with supervisors') }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <a href="{{ route('staff.payment-method') }}" class="action-tile">
                    <div class="metric-icon-box icon-gold">
                        <i class="fas fa-university"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-white">{{ __('Direct Deposit Routing') }}</div>
                        <div class="text-muted small">{{ __('Checking / savings account distribution') }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <a href="{{ route('staff.direct-deposit-form.download') }}" class="action-tile">
                    <div class="metric-icon-box icon-green">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-white">{{ __('Official Direct Deposit PDF') }}</div>
                        <div class="text-muted small">{{ __('Pre-filled banking authorization document') }}</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Quick Receipt Scanner & Reimbursement -->
<div class="modal fade" id="quickReceiptModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background:#11151e; color:#f1f5f9; border:1px solid #28303f;">
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
                        <label class="small text-muted text-uppercase font-weight-bold">{{ __('Expense Description') }} <span class="text-danger">*</span></label>
                        <input type="text" name="description" class="form-control" placeholder="e.g. Court filing fees or travel fuel" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small text-muted text-uppercase font-weight-bold">{{ __('Amount ($)') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text bg-secondary text-white border-secondary">$</span></div>
                            <input type="number" step="0.01" min="0.01" name="amount" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small text-muted text-uppercase font-weight-bold">{{ __('Receipt Photo / PDF') }} <span class="text-danger">*</span></label>
                        <input type="file" name="attachment" id="quickReceiptFileInput" accept="image/*,application/pdf" capture="environment" class="form-control-file text-white" required onchange="previewQuickReceipt(this)">
                        <small class="text-muted d-block mt-1">{{ __('Tap to use camera on phone, or choose file from computer.') }}</small>
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

    function submitPunch(formId) {
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
                    console.warn("Geolocation fallback:", error.message);
                    form.submit();
                },
                { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
            );
        } else {
            form.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Digital Clock (Local)
        const clockElement = document.getElementById('digital-clock');
        if (clockElement) {
            setInterval(() => {
                const now = new Date();
                let hours = now.getHours();
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12;
                clockElement.textContent = `${String(hours).padStart(2, '0')}:${minutes}:${seconds} ${ampm}`;
            }, 1000);
        }

        // Ticking Stopwatch for active shift
        @if($activeLog)
            const clockedInTime = new Date('{{ $activeLog->clocked_in_at->toIso8601String() }}');
            const timerElement = document.getElementById('session-timer');

            if (timerElement) {
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
            }
        @endif

        // ApexCharts Performance
        const options = {
            series: [{
                name: 'Hours Logged',
                data: @json($weeklyHours)
            }],
            chart: {
                type: 'area',
                height: 250,
                toolbar: { show: false },
                zoom: { enabled: false },
                background: 'transparent'
            },
            theme: { mode: 'dark' },
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#fecc56']
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 100]
                }
            },
            colors: ['#fecc56'],
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
