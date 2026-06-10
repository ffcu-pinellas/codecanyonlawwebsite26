@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('content')
    <div id="wrapper-content">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                    <span class="breadcrumb-item active">{{ __($title) }}</span>
                </nav>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card card-dark bg-dark">
                    <div class="card-header d-block">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <h6 class="card-title lh-35">{{ __($title) }}</h6>
                            </div>
                            <div class="col-md-6 col-sm-12 text-md-right text-left text-muted small lh-35">
                                {{ __('Tracking administrative mutations, creations, and security logs') }}
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive style-scroll">
                            <table class="table table-striped table-bordered table-dark miw-500 small" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Action') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Performed By') }}</th>
                                        <th>{{ __('IP Address') }}</th>
                                        <th>{{ __('User Agent') }}</th>
                                        <th>{{ __('Timestamp') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($logs as $log)
                                        <tr>
                                            <td>
                                                <span class="badge badge-info">{{ $log->action }}</span>
                                            </td>
                                            <td>{{ $log->description }}</td>
                                            <td>
                                                @if($log->user)
                                                    <strong>{{ $log->user->name }}</strong>
                                                    <div class="text-muted small">{{ $log->user->email }}</div>
                                                @else
                                                    <span class="text-muted">{{ __('System / Guest') }}</span>
                                                @endif
                                            </td>
                                            <td><code>{{ $log->ip_address ?: 'N/A' }}</code></td>
                                            <td title="{{ $log->user_agent }}">
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                                    {{ $log->user_agent ?: 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }} <small>({{ $log->created_at->diffForHumans() }})</small></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                                <p>{{ __('No activity logs found.') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
