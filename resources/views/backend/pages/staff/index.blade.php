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
                                                    <div class="mb-1">
                                                        @if($detail->payment_method === 'direct_deposit')
                                                            <span class="badge badge-info"><i class="fas fa-university mr-1"></i> {{ __('Direct Deposit') }}</span>
                                                        @else
                                                            <span class="badge badge-secondary"><i class="fas fa-money-check-alt mr-1"></i> {{ __('Paper Check') }}</span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        @if($detail->payment_verified)
                                                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> {{ __('Verified') }}</span>
                                                        @else
                                                            <span class="badge badge-warning"><i class="fas fa-clock"></i> {{ __('Pending') }}</span>
                                                        @endif
                                                        <button type="button" class="btn btn-xs btn-outline-light ml-1" data-toggle="modal" data-target="#paymentModal{{ $staff->id }}"><i class="fas fa-eye"></i> {{ __('Details') }}</button>
                                                    </div>
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

    @foreach ($staffUsers as $staff)
        @php
            $detail = $staff->staffDetail;
        @endphp
        @if($detail)
            <!-- Payment Details Modal -->
            <div class="modal fade text-dark" id="paymentModal{{ $staff->id }}" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel{{ $staff->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content bg-white text-dark">
                        <div class="modal-header border-bottom">
                            <h6 class="modal-title font-weight-bold" id="paymentModalLabel{{ $staff->id }}"><i class="fas fa-wallet mr-2 text-info"></i>{{ __('Payment Setup: ') }}{{ $staff->name }}</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-left">
                            <div class="mb-3 pb-3 border-bottom text-dark">
                                <strong>{{ __('Payment Method:') }}</strong>
                                @if($detail->payment_method === 'direct_deposit')
                                    <span class="badge badge-info text-uppercase text-white">{{ __('Direct Deposit') }}</span>
                                @else
                                    <span class="badge badge-secondary text-uppercase text-white">{{ __('Paper Check') }}</span>
                                @endif
                            </div>

                            @if($detail->payment_method === 'direct_deposit')
                                <h6 class="font-weight-bold text-info mb-2 small text-uppercase">{{ __('Bank routing & account information') }}</h6>
                                <table class="table table-bordered table-sm small mb-3">
                                    <tr>
                                        <th style="width: 40%;" class="bg-light font-weight-bold text-dark">{{ __('Bank Name') }}</th>
                                        <td class="text-dark">{{ $detail->bank_name ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light font-weight-bold text-dark">{{ __('Account Holder') }}</th>
                                        <td class="text-dark">{{ $detail->account_name ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light font-weight-bold text-dark">{{ __('Account Number') }}</th>
                                        <td class="text-dark"><code>{{ $detail->account_number ?: 'N/A' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light font-weight-bold text-dark">{{ __('Routing Number') }}</th>
                                        <td class="text-dark"><code>{{ $detail->routing_number ?: 'N/A' }}</code></td>
                                    </tr>
                                </table>

                                <h6 class="font-weight-bold text-info mb-2 small text-uppercase">{{ __('Uploaded Document Files') }}</h6>
                                <div class="d-flex flex-wrap mb-3">
                                    @if($detail->void_check_path)
                                        <a href="{{ route('admin.staff.download-payment-form', [$staff->id, 'void_check']) }}" class="btn btn-xs btn-outline-info mr-2 mb-2"><i class="fas fa-file-invoice-dollar mr-1"></i> {{ __('Download Void Check') }}</a>
                                    @else
                                        <span class="text-muted small mr-3 mb-2"><i class="fas fa-times-circle text-danger"></i> {{ __('No Void Check Uploaded') }}</span>
                                    @endif

                                    @if($detail->direct_deposit_form_path)
                                        <a href="{{ route('admin.staff.download-payment-form', [$staff->id, 'direct_deposit']) }}" class="btn btn-xs btn-outline-info mb-2"><i class="fas fa-file-signature mr-1"></i> {{ __('Download DD Form') }}</a>
                                    @else
                                        <span class="text-muted small mb-2"><i class="fas fa-times-circle text-danger"></i> {{ __('No DD Form Uploaded') }}</span>
                                    @endif
                                </div>
                            @else
                                <h6 class="font-weight-bold text-info mb-2 small text-uppercase">{{ __('Check Issuance Info') }}</h6>
                                <table class="table table-bordered table-sm small mb-3">
                                    <tr>
                                        <th style="width: 40%;" class="bg-light font-weight-bold text-dark">{{ __('Payee Name') }}</th>
                                        <td class="text-dark">{{ $detail->check_name ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light font-weight-bold text-dark">{{ __('Delivery Address') }}</th>
                                        <td class="text-dark" style="white-space: pre-line;">{{ $detail->check_address ?: 'N/A' }}</td>
                                    </tr>
                                </table>
                            @endif

                            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="text-dark">{{ __('Verification:') }}</strong>
                                    @if($detail->payment_verified)
                                        <span class="text-success font-weight-bold small"><i class="fas fa-check-circle"></i> {{ __('VERIFIED') }}</span>
                                    @else
                                        <span class="text-warning font-weight-bold small"><i class="fas fa-clock"></i> {{ __('PENDING REVIEW') }}</span>
                                    @endif
                                </div>
                                <div>
                                    <form action="{{ route('admin.staff.verify-payment', $staff->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @if($detail->payment_verified)
                                            <input type="hidden" name="payment_verified" value="0">
                                            <button type="submit" class="btn btn-xs btn-warning font-weight-bold text-dark">{{ __('Mark Unverified') }}</button>
                                        @else
                                            <input type="hidden" name="payment_verified" value="1">
                                            <button type="submit" class="btn btn-xs btn-success font-weight-bold text-white">{{ __('Approve & Verify') }}</button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top bg-light">
                            <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
