@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .status-badge-active {
        background-color: #27ae60;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    .status-badge-inactive {
        background-color: #c0392b;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
    <div id="wrapper-content">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                    <span class="breadcrumb-item active">{{ __($title) }}</span>
                    <span class="breadcrumb-info" id="time"></span>
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
                            <div class="col-md-6 col-sm-12 text-md-right text-left">
                                <a href="{{ route('admin.staff.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> {{ __('Register New Staff') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive style-scroll">
                            <table class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Staff ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Position') }}</th>
                                        <th>{{ __('Hourly Rate') }}</th>
                                        <th>{{ __('Hired Date') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Direct Deposit Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($staffUsers as $staff)
                                        @php
                                            $detail = $staff->staffDetail;
                                        @endphp
                                        <tr>
                                            <td>{{ $detail ? $detail->staff_id : 'N/A' }}</td>
                                            <td>
                                                <strong>{{ $staff->name }}</strong>
                                                <div class="text-muted small">{{ $staff->email }}</div>
                                            </td>
                                            <td>{{ $detail ? ($detail->position ?: __('Associate')) : 'N/A' }}</td>
                                            <td>${{ number_format($detail ? $detail->hourly_rate : 0.00, 2) }}/hr</td>
                                            <td>{{ $detail && $detail->hired_at ? $detail->hired_at->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                @if($detail && $detail->is_active)
                                                    <span class="status-badge-active">{{ __('Active') }}</span>
                                                @else
                                                    <span class="status-badge-inactive">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($detail)
                                                    @if($detail->payment_method === 'direct_deposit')
                                                        @if($detail->payment_verified)
                                                            <span class="badge badge-success"><i class="fas fa-check"></i> {{ __('Verified') }}</span>
                                                        @else
                                                            <span class="badge badge-warning"><i class="fas fa-clock"></i> {{ __('Pending Verification') }}</span>
                                                            <div class="mt-1">
                                                                <form action="{{ route('admin.staff.verify-payment', $staff->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="payment_verified" value="1">
                                                                    <button type="submit" class="btn btn-xs btn-success">{{ __('Approve') }}</button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                        <div class="mt-1 small">
                                                            @if($detail->void_check_path)
                                                                <a href="{{ route('admin.staff.download-payment-form', [$staff->id, 'void_check']) }}" class="btn btn-xs btn-outline-info p-0 px-1" title="{{ __('Download Void Check') }}"><i class="fas fa-file-invoice-dollar"></i> {{ __('Void Check') }}</a>
                                                            @endif
                                                            @if($detail->direct_deposit_form_path)
                                                                <a href="{{ route('admin.staff.download-payment-form', [$staff->id, 'direct_deposit']) }}" class="btn btn-xs btn-outline-info p-0 px-1 ml-1" title="{{ __('Download DD Form') }}"><i class="fas fa-file-signature"></i> {{ __('DD Form') }}</a>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="badge badge-secondary">{{ __('Paper Check') }}</span>
                                                    @endif
                                                @else
                                                    <span>N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap">
                                                     <a href="{{ route('admin.staff.edit', $staff->id) }}" class="btn btn-xs btn-info m-1" title="{{ __('Edit Settings') }}"><i class="fas fa-edit"></i></a>
                                                     <a href="{{ route('admin.staff.ledger.index', $staff->id) }}" class="btn btn-xs btn-warning m-1 text-white" title="{{ __('Financial Ledger') }}"><i class="fas fa-wallet"></i></a>
                                                     <a href="{{ route('admin.staff.time-logs', $staff->id) }}" class="btn btn-xs btn-success m-1" title="{{ __('Time & Wage Logs') }}"><i class="fas fa-history"></i></a>
                                                     <a href="{{ route('admin.staff.login-logs', $staff->id) }}" class="btn btn-xs btn-secondary m-1" title="{{ __('Login Audit') }}"><i class="fas fa-user-lock"></i></a>
                                                     <a href="{{ route('admin.staff.messages', $staff->id) }}" class="btn btn-xs btn-primary m-1" title="{{ __('Secure Chat') }}"><i class="fas fa-comments"></i></a>
                                                    
                                                    <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member? This deletes all associated time-logs, messages and log records.');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-danger m-1" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                                                    </form>
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
