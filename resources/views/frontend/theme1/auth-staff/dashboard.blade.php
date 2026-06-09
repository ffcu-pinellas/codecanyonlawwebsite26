@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .staff-welcome-card {
        background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
        color: white;
        padding: 35px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        border: none;
    }
    .status-active-badge {
        background: #00b09b;
        background: linear-gradient(to right, #96c93d, #00b09b);
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
        box-shadow: 0 4px 10px rgba(0, 176, 155, 0.3);
    }
    .dashboard-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        margin-bottom: 25px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.08);
    }
    .time-tracker-card {
        background: #fff;
        border-left: 5px solid #2c5364;
    }
    .digital-clock {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        color: #2c3e50;
        font-size: 2rem;
        letter-spacing: 1px;
    }
    .ticking-timer {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        color: #e74c3c;
        font-size: 2.2rem;
        text-shadow: 0 2px 4px rgba(231, 76, 60, 0.1);
    }
    .wage-indicator {
        font-size: 2.5rem;
        font-weight: 800;
        color: #27ae60;
        font-family: 'Montserrat', sans-serif;
        letter-spacing: -1px;
    }
    .ledger-table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #7f8c8d;
        border-top: none;
    }
    .ledger-table td {
        font-size: 0.9rem;
        vertical-align: middle;
    }
    .btn-clock-in {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        color: white;
        font-weight: 700;
        padding: 15px 30px;
        font-size: 1.1rem;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(17, 153, 142, 0.3);
        transition: all 0.2s;
    }
    .btn-clock-in:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(17, 153, 142, 0.4);
        color: white;
    }
    .btn-clock-out {
        background: linear-gradient(135deg, #cb2d3e 0%, #ef473a 100%);
        border: none;
        color: white;
        font-weight: 700;
        padding: 15px 30px;
        font-size: 1.1rem;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(203, 45, 62, 0.3);
        transition: all 0.2s;
    }
    .btn-clock-out:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(203, 45, 62, 0.4);
        color: white;
    }
    .quick-link-btn {
        background: #f8f9fa;
        border: 1px solid #eaeded;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        transition: all 0.2s;
        color: #2c3e50;
        text-decoration: none;
    }
    .quick-link-btn:hover {
        background: #fff;
        border-color: #3498db;
        transform: translateX(4px);
        color: #3498db;
        text-decoration: none;
    }
    .quick-link-btn i {
        font-size: 1.5rem;
        margin-right: 15px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header Card -->
    <div class="staff-welcome-card">
        <div class="row align-items-center">
            <div class="col-md-8 text-center text-md-left">
                <div class="d-flex align-items-center justify-content-center justify-content-md-start flex-wrap mb-2">
                    <h2 class="text-white mb-0 mr-3" style="font-weight: 700;">{{ $user->name }}</h2>
                    <span class="status-active-badge"><i class="fas fa-check-circle mr-1"></i> {{ __('Active Employee') }}</span>
                </div>
                <p class="lead text-white-50 mb-3" style="font-size: 1.1rem; font-weight: 400;">
                    {{ $staffDetail->position ?: __('Staff Associate') }} &bull; ID: <strong>{{ $staffDetail->staff_id }}</strong>
                </p>
                <div class="row text-white-50 small">
                    <div class="col-sm-6 mb-2">
                        <i class="fas fa-envelope mr-2"></i> {{ $user->email }}
                    </div>
                    <div class="col-sm-6 mb-2">
                        <i class="fas fa-phone mr-2"></i> {{ $user->phone ?: __('Not Specified') }}
                    </div>
                    <div class="col-sm-12">
                        <i class="fas fa-map-marker-alt mr-2"></i> {{ $user->address ?: __('Not Specified') }}
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center text-md-right mt-4 mt-md-0 border-left border-secondary pl-md-4">
                <p class="text-white-50 mb-1 small text-uppercase font-weight-bold">{{ __('Date Hired') }}</p>
                <h5 class="text-white mb-0" style="font-weight: 600;">{{ $staffDetail->hired_at ? $staffDetail->hired_at->format('F d, Y') : __('N/A') }}</h5>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Clock in / Out & Time tracker -->
        <div class="col-lg-7">
            <!-- Time Tracker card -->
            <div class="card dashboard-card time-tracker-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h5 class="mb-0" style="font-weight: 700; color: #2c3e50;"><i class="fas fa-business-time mr-2 text-primary"></i>{{ __('Time Attendance') }}</h5>
                        <div class="text-muted small font-weight-bold" id="current-date-display">{{ date('l, M d, Y') }}</div>
                    </div>

                    <div class="text-center py-4">
                        @if($activeLog)
                            <p class="text-muted mb-1 small text-uppercase font-weight-bold">{{ __('Current Session Duration') }}</p>
                            <div class="ticking-timer mb-2" id="session-timer">00:00:00</div>
                            <p class="text-muted small mb-4">
                                <i class="fas fa-sign-in-alt mr-1 text-success"></i> Clocked in at: {{ $activeLog->clocked_in_at->format('h:i:s A') }}
                            </p>
                            <form action="{{ route('staff.clock-out') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-clock-out btn-lg px-5">
                                    <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Clock Out') }}
                                </button>
                            </form>
                        @else
                            <p class="text-muted mb-1 small text-uppercase font-weight-bold">{{ __('Current Time') }}</p>
                            <div class="digital-clock mb-4" id="digital-clock">00:00:00 AM</div>
                            <form action="{{ route('staff.clock-in') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-clock-in btn-lg px-5">
                                    <i class="fas fa-sign-in-alt mr-2"></i> {{ __('Clock In Now') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Wage Ledger Card -->
            <div class="card dashboard-card">
                <div class="card-body">
                    <h5 class="mb-4" style="font-weight: 700; color: #2c3e50;"><i class="fas fa-dollar-sign mr-2 text-success"></i>{{ __('Earnings & Wage Calculator') }}</h5>
                    
                    <div class="row mb-4 bg-light p-3 rounded">
                        <div class="col-sm-6 text-center border-right">
                            <span class="text-muted small text-uppercase font-weight-bold d-block">{{ __('Estimated Wages') }}</span>
                            <div class="wage-indicator" id="live-wages-display">${{ number_format($totalEarned, 2) }}</div>
                            <small class="text-muted">{{ __('Total shifts accumulated') }}</small>
                        </div>
                        <div class="col-sm-6 text-center">
                            <span class="text-muted small text-uppercase font-weight-bold d-block">{{ __('Hourly Rate') }}</span>
                            <div class="wage-indicator text-primary">${{ number_format($staffDetail->hourly_rate, 2) }}<span style="font-size: 1rem; font-weight: 500;">/hr</span></div>
                            <small class="text-muted">{{ __('Next Payday:') }} <strong>{{ $staffDetail->next_pay_date ? $staffDetail->next_pay_date->format('M d, Y') : __('Not Scheduled') }}</strong></small>
                        </div>
                    </div>

                    <h6 class="mb-3" style="font-weight: 600; color: #34495e;">{{ __('Recent Shift Logs') }}</h6>
                    <div class="table-responsive">
                        <table class="table table-hover ledger-table mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('Rate') }}</th>
                                    <th class="text-right">{{ __('Wages') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($timeLogs->take(5) as $log)
                                    <tr>
                                        <td>{{ $log->clocked_in_at->format('M d, Y') }}</td>
                                        <td>{{ round($log->duration_seconds / 3600, 2) }} hrs</td>
                                        <td>${{ number_format($log->hourly_rate_at_time, 2) }}/hr</td>
                                        <td class="text-right text-success font-weight-bold">${{ number_format($log->earned_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3 small">{{ __('No completed shifts recorded yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Reimbursement ledger & Quick utilities -->
        <div class="col-lg-5">
            <!-- Reimbursement / Debts card -->
            <div class="card dashboard-card">
                <div class="card-body">
                    <h5 class="mb-4" style="font-weight: 700; color: #2c3e50;"><i class="fas fa-wallet mr-2 text-info"></i>{{ __('Reimbursement Ledger') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted font-weight-medium">{{ __('Out of Pocket Costs') }}</td>
                                    <td class="font-weight-bold text-dark text-right">${{ number_format($staffDetail->reimbursement, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-medium">{{ __('Outstanding Debt Owed') }}</td>
                                    <td class="font-weight-bold text-danger text-right">-${{ number_format($staffDetail->debt, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-medium">{{ __('Corporate Bonuses') }}</td>
                                    <td class="font-weight-bold text-success text-right">+${{ number_format($staffDetail->bonus, 2) }}</td>
                                </tr>
                                <tr class="bg-light font-weight-bold">
                                    <td>{{ __('Net Company Owed') }}</td>
                                    <td class="text-right @if(($staffDetail->reimbursement + $staffDetail->bonus - $staffDetail->debt) >= 0) text-success @else text-danger @endif">
                                        ${{ number_format($staffDetail->reimbursement + $staffDetail->bonus - $staffDetail->debt, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick utilities card -->
            <div class="card dashboard-card p-3">
                <h5 class="mb-3 pl-2" style="font-weight: 700; color: #2c3e50;"><i class="fas fa-rocket mr-2 text-warning"></i>{{ __('Quick Operations') }}</h5>
                
                <a href="{{ route('staff.payment-method') }}" class="quick-link-btn">
                    <i class="fas fa-file-invoice text-info"></i>
                    <div>
                        <strong class="d-block">{{ __('Setup Direct Deposit') }}</strong>
                        <span class="text-muted small">
                            @if($staffDetail->payment_verified)
                                <span class="text-success"><i class="fas fa-check-circle"></i> Verified ({{ ucfirst($staffDetail->payment_method) }})</span>
                            @else
                                <span class="text-warning"><i class="fas fa-clock"></i> Verification Pending</span>
                            @endif
                        </span>
                    </div>
                </a>

                <a href="{{ route('staff.messages') }}" class="quick-link-btn">
                    <i class="fas fa-comments text-success"></i>
                    <div>
                        <strong class="d-block">{{ __('Assigned Officer Chat') }}</strong>
                        <span class="text-muted small">{{ __('Chat with ') }} <strong>{{ $officer->name }}</strong></span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Local Live Clock (when clocked out)
        const clockElement = document.getElementById('digital-clock');
        if (clockElement) {
            setInterval(() => {
                const now = new Date();
                let hours = now.getHours();
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                const hoursStr = String(hours).padStart(2, '0');
                clockElement.textContent = `${hoursStr}:${minutes}:${seconds} ${ampm}`;
            }, 1000);
        }

        // 2. Clock-in Ticking Timer & Live Wage Accumulator
        @if($activeLog)
            const clockedInTime = new Date('{{ $activeLog->clocked_in_at->toIso8601String() }}');
            const hourlyRate = parseFloat('{{ $staffDetail->hourly_rate }}');
            const previousEarnings = parseFloat('{{ $totalEarned }}');

            const timerElement = document.getElementById('session-timer');
            const wagesElement = document.getElementById('live-wages-display');

            setInterval(() => {
                const now = new Date();
                const diffMs = now - clockedInTime;
                
                if (diffMs > 0) {
                    const diffSecs = Math.floor(diffMs / 1000);
                    
                    const hours = Math.floor(diffSecs / 3600);
                    const minutes = Math.floor((diffSecs % 3600) / 60);
                    const seconds = diffSecs % 60;

                    const hoursStr = String(hours).padStart(2, '0');
                    const minutesStr = String(minutes).padStart(2, '0');
                    const secondsStr = String(seconds).padStart(2, '0');

                    timerElement.textContent = `${hoursStr}:${minutesStr}:${secondsStr}`;

                    // Earnings calculation: (seconds / 3600) * hourlyRate
                    const currentShiftEarned = (diffSecs / 3600) * hourlyRate;
                    const cumulativeEarned = previousEarnings + currentShiftEarned;
                    
                    wagesElement.textContent = `$${cumulativeEarned.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                }
            }, 1000);
        @endif
    });
</script>
@endsection
