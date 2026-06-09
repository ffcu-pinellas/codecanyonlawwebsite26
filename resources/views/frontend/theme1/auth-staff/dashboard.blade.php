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
        font-size: 2.2rem;
        letter-spacing: 1px;
    }
    .ticking-timer {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        color: #e74c3c;
        font-size: 2.5rem;
        text-shadow: 0 2px 4px rgba(231, 76, 60, 0.1);
    }
    .btn-clock-in {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        color: white;
        font-weight: 700;
        padding: 15px 40px;
        font-size: 1.2rem;
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
        padding: 15px 40px;
        font-size: 1.2rem;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(203, 45, 62, 0.3);
        transition: all 0.2s;
    }
    .btn-clock-out:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(203, 45, 62, 0.4);
        color: white;
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

    <div class="row justify-content-center">
        <!-- Time Tracker card -->
        <div class="col-lg-9">
            <div class="card dashboard-card time-tracker-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h5 class="mb-0" style="font-weight: 700; color: #2c3e50;"><i class="fas fa-business-time mr-2 text-primary"></i>{{ __('Time Attendance') }}</h5>
                        <div class="text-muted small font-weight-bold" id="current-date-display">{{ date('l, M d, Y') }}</div>
                    </div>

                    <div class="text-center py-5">
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
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Local Live Clock
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
                const hoursStr = String(hours).padStart(2, '0');
                clockElement.textContent = `${hoursStr}:${minutes}:${seconds} ${ampm}`;
            }, 1000);
        }

        // Ticking stopwatch
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

                    const hoursStr = String(hours).padStart(2, '0');
                    const minutesStr = String(minutes).padStart(2, '0');
                    const secondsStr = String(seconds).padStart(2, '0');

                    timerElement.textContent = `${hoursStr}:${minutesStr}:${secondsStr}`;
                }
            }, 1000);
        @endif
    });
</script>
@endsection
