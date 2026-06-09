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
                        <div class="table-responsive style-scroll">
                            <table class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">{{ __('SL') }}</th>
                                        <th>{{ __('Logged In At') }}</th>
                                        <th>{{ __('IP Address') }}</th>
                                        <th>{{ __('User Agent') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($loginLogs as $log)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $log->logged_in_at->format('M d, Y - h:i:s A') }}</td>
                                            <td><span class="badge badge-info">{{ $log->ip_address }}</span></td>
                                            <td class="small text-muted">{{ $log->user_agent }}</td>
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
