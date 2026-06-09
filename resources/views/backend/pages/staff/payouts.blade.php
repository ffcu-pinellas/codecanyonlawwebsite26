@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .payout-status-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .badge-pending { background-color: #f39c12; color: white; }
    .badge-approved { background-color: #3498db; color: white; }
    .badge-paid { background-color: #2ecc71; color: white; }
</style>
@endsection

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

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card card-dark bg-dark">
                    <div class="card-header">
                        <h6 class="card-title">{{ __($title) }}</h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive style-scroll">
                            <table class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Staff Name') }}</th>
                                        <th>{{ __('Hourly Rate / Position') }}</th>
                                        <th>{{ __('Requested Amount') }}</th>
                                        <th>{{ __('Submission Date') }}</th>
                                        <th>{{ __('Notes') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payoutRequests as $payout)
                                        @php
                                            $detail = $payout->user->staffDetail;
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $payout->user->name }}</strong>
                                                <div class="small text-muted">ID: {{ $detail->staff_id ?? 'N/A' }}</div>
                                            </td>
                                            <td>
                                                <div>{{ $detail->position ?? __('Associate') }}</div>
                                                <small class="text-muted">${{ number_format($detail->hourly_rate ?? 0.00, 2) }}/hr</small>
                                            </td>
                                            <td>
                                                <h5 class="text-success font-weight-bold mb-0">${{ number_format($payout->amount, 2) }}</h5>
                                            </td>
                                            <td>{{ $payout->created_at->format('M d, Y - h:i A') }}</td>
                                            <td>
                                                @if($payout->notes)
                                                    <span class="small text-muted"><em>"{{ $payout->notes }}"</em></span>
                                                @else
                                                    <span class="text-muted small">--</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="payout-status-badge badge-{{ $payout->status }}">{{ $payout->status }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($payout->status === 'pending')
                                                        <form action="{{ route('admin.staff.payouts.status', $payout->id) }}" method="POST" class="d-inline mr-1">
                                                            @csrf
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit" class="btn btn-xs btn-info"><i class="fas fa-check mr-1"></i>{{ __('Approve') }}</button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if($payout->status === 'approved' || $payout->status === 'pending')
                                                        <form action="{{ route('admin.staff.payouts.status', $payout->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="paid">
                                                            <button type="submit" class="btn btn-xs btn-success"><i class="fas fa-money-bill-wave mr-1"></i>{{ __('Mark Paid') }}</button>
                                                        </form>
                                                    @endif
                                                </div>
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
