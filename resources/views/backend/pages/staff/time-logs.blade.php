@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('content')
    <div id="wrapper-content">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                    <a class="breadcrumb-item text-white" href="{{ route('admin.staff.index') }}">{{ __('Staff Directory') }}</a>
                    <span class="breadcrumb-item active">{{ __($title) }}</span>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-dark bg-dark">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="card-title">{{ __($title) }}</h6>
                            <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4 text-center border-right border-secondary">
                                <span class="text-muted small text-uppercase font-weight-bold d-block">{{ __('Total Completed Shifts') }}</span>
                                <h3 class="text-white font-weight-bold">{{ $timeLogs->whereNotNull('clocked_out_at')->count() }}</h3>
                            </div>
                            <div class="col-md-4 text-center border-right border-secondary">
                                <span class="text-muted small text-uppercase font-weight-bold d-block">{{ __('Total Hours Logged') }}</span>
                                <h3 class="text-white font-weight-bold" id="total-hours-container">
                                    {{ round($timeLogs->whereNotNull('clocked_out_at')->sum('duration_seconds') / 3600, 2) }} hrs
                                </h3>
                            </div>
                            <div class="col-md-4 text-center">
                                <span class="text-muted small text-uppercase font-weight-bold d-block">{{ __('Total Wages Earned') }}</span>
                                <h3 class="text-success font-weight-bold" id="total-wages-container">
                                    ${{ number_format($timeLogs->whereNotNull('clocked_out_at')->sum('earned_amount'), 2) }}
                                </h3>
                            </div>
                        </div>

                        <div class="table-responsive style-scroll mt-3">
                            <table class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('SL') }}</th>
                                        <th>{{ __('Clocked In') }}</th>
                                        <th>{{ __('Clocked Out') }}</th>
                                        <th>{{ __('Duration') }}</th>
                                        <th>{{ __('Hourly Rate') }}</th>
                                        <th>{{ __('IP details') }}</th>
                                        <th>{{ __('Location Map') }}</th>
                                        <th class="text-right">{{ __('Wages Earned') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($timeLogs as $log)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $log->clocked_in_at->format('M d, Y - h:i:s A') }}</td>
                                            <td>
                                                @if($log->clocked_out_at)
                                                    {{ $log->clocked_out_at->format('M d, Y - h:i:s A') }}
                                                @else
                                                    <span class="badge badge-success"><i class="fas fa-spinner fa-spin mr-1"></i>{{ __('Active Session') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($log->clocked_out_at)
                                                    {{ round($log->duration_seconds / 3600, 2) }} hrs
                                                @else
                                                    <span class="ticking-duration text-warning font-weight-bold" data-start="{{ $log->clocked_in_at->toIso8601String() }}">00:00:00</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($log->hourly_rate_at_time, 2) }}/hr</td>
                                            <td>
                                                <small class="d-block text-muted"><strong>In:</strong> {{ $log->clock_in_ip ?: 'N/A' }}</small>
                                                @if($log->clocked_out_at)
                                                    <small class="d-block text-muted"><strong>Out:</strong> {{ $log->clock_out_ip ?: 'N/A' }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($log->clock_in_latitude && $log->clock_in_longitude)
                                                    <a href="https://www.google.com/maps?q={{ $log->clock_in_latitude }},{{ $log->clock_in_longitude }}" target="_blank" class="btn btn-xs btn-outline-info p-1 m-1" title="{{ __('Clock In Location') }}">
                                                        <i class="fas fa-map-marker-alt text-success"></i> {{ __('In Location') }}
                                                    </a>
                                                @endif
                                                @if($log->clocked_out_at && $log->clock_out_latitude && $log->clock_out_longitude)
                                                    <a href="https://www.google.com/maps?q={{ $log->clock_out_latitude }},{{ $log->clock_out_longitude }}" target="_blank" class="btn btn-xs btn-outline-info p-1 m-1" title="{{ __('Clock Out Location') }}">
                                                        <i class="fas fa-map-marker-alt text-danger"></i> {{ __('Out Location') }}
                                                    </a>
                                                @endif
                                                @if(!$log->clock_in_latitude && (!$log->clock_out_latitude || !$log->clocked_out_at))
                                                    <span class="text-muted small">{{ __('N/A') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-right text-success font-weight-bold">
                                                @if($log->clocked_out_at)
                                                    ${{ number_format($log->earned_amount, 2) }}
                                                @else
                                                    <span class="ticking-wage" data-start="{{ $log->clocked_in_at->toIso8601String() }}" data-rate="{{ $log->hourly_rate_at_time }}">$0.00</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        const durationElements = document.querySelectorAll('.ticking-duration');
        const wageElements = document.querySelectorAll('.ticking-wage');
        const totalHoursEl = document.getElementById('total-hours-container');
        const totalWagesEl = document.getElementById('total-wages-container');

        if (durationElements.length > 0) {
            const staticHours = parseFloat('{{ round($timeLogs->whereNotNull('clocked_out_at')->sum('duration_seconds') / 3600, 2) }}');
            const staticWages = parseFloat('{{ $timeLogs->whereNotNull('clocked_out_at')->sum('earned_amount') }}');

            setInterval(() => {
                let sessionWagesSum = 0;
                let sessionHoursSum = 0;

                durationElements.forEach((el, index) => {
                    const startTime = new Date(el.getAttribute('data-start'));
                    const now = new Date();
                    const diffMs = now - startTime;

                    if (diffMs > 0) {
                        const diffSecs = Math.floor(diffMs / 1000);
                        const hours = Math.floor(diffSecs / 3600);
                        const minutes = Math.floor((diffSecs % 3600) / 60);
                        const seconds = diffSecs % 60;

                        el.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

                        // Calculate ticking wages
                        const wageEl = wageElements[index];
                        if (wageEl) {
                            const rate = parseFloat(wageEl.getAttribute('data-rate'));
                            const earned = (diffSecs / 3600) * rate;
                            wageEl.textContent = `$${earned.toFixed(2)}`;
                            
                            sessionWagesSum += earned;
                            sessionHoursSum += (diffSecs / 3600);
                        }
                    }
                });

                // Update grand totals in real time!
                if (totalHoursEl) {
                    totalHoursEl.textContent = `${(staticHours + sessionHoursSum).toFixed(2)} hrs`;
                }
                if (totalWagesEl) {
                    totalWagesEl.textContent = `$${(staticWages + sessionWagesSum).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                }
            }, 1000);
        }
    });
</script>
@endsection
