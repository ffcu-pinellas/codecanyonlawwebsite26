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
                                <span class="text-muted small text-uppercase font-weight-bold d-block">{{ __('Total Shifts') }}</span>
                                <h3 class="text-white font-weight-bold">{{ $timeLogs->count() }}</h3>
                            </div>
                            <div class="col-md-4 text-center border-right border-secondary">
                                <span class="text-muted small text-uppercase font-weight-bold d-block">{{ __('Total Hours Logged') }}</span>
                                <h3 class="text-white font-weight-bold">{{ round($timeLogs->sum('duration_seconds') / 3600, 2) }} hrs</h3>
                            </div>
                            <div class="col-md-4 text-center">
                                <span class="text-muted small text-uppercase font-weight-bold d-block">{{ __('Total Wages Earned') }}</span>
                                <h3 class="text-success font-weight-bold">${{ number_format($timeLogs->sum('earned_amount'), 2) }}</h3>
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
                                        <th class="text-right">{{ __('Wages Earned') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($timeLogs as $log)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $log->clocked_in_at->format('M d, Y - h:i:s A') }}</td>
                                            <td>{{ $log->clocked_out_at ? $log->clocked_out_at->format('M d, Y - h:i:s A') : __('Active Session') }}</td>
                                            <td>{{ $log->clocked_out_at ? round($log->duration_seconds / 3600, 2) . ' hrs' : 'Ticking...' }}</td>
                                            <td>${{ number_format($log->hourly_rate_at_time, 2) }}/hr</td>
                                            <td class="text-right text-success font-weight-bold">${{ number_format($log->earned_amount, 2) }}</td>
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
