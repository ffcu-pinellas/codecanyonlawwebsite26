@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .ledger-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .badge-reimbursement { background-color: #3498db; color: white; }
    .badge-bonus { background-color: #2ecc71; color: white; }
    .badge-debt { background-color: #e74c3c; color: white; }
    .ledger-summary-val {
        font-size: 1.5rem;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
    }
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @php
            $totalsQuery = $staff->staffLedgerEntries()
                ->where('status', '!=', 'pending')
                ->selectRaw("
                    SUM(CASE WHEN type = 'reimbursement' THEN amount ELSE 0 END) as orig_reim,
                    SUM(CASE WHEN type = 'reimbursement' THEN paid_amount ELSE 0 END) as paid_reim,
                    SUM(CASE WHEN type = 'debt' THEN amount ELSE 0 END) as orig_debt,
                    SUM(CASE WHEN type = 'debt' THEN paid_amount ELSE 0 END) as paid_debt,
                    SUM(CASE WHEN type = 'bonus' THEN amount ELSE 0 END) as orig_bonus,
                    SUM(CASE WHEN type = 'bonus' THEN paid_amount ELSE 0 END) as paid_bonus
                ")
                ->first();

            $origReim = $totalsQuery->orig_reim ?: 0.00;
            $paidReim = $totalsQuery->paid_reim ?: 0.00;
            $remReim = max(0, $origReim - $paidReim);

            $origDebt = $totalsQuery->orig_debt ?: 0.00;
            $paidDebt = $totalsQuery->paid_debt ?: 0.00;
            $remDebt = max(0, $origDebt - $paidDebt);

            $origBonus = $totalsQuery->orig_bonus ?: 0.00;
            $paidBonus = $totalsQuery->paid_bonus ?: 0.00;
            $remBonus = max(0, $origBonus - $paidBonus);

            $netRemaining = $remReim + $remBonus - $remDebt;
        @endphp

        <!-- Summary Statistics Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-dark text-white border-info">
                    <div class="card-body py-2">
                        <small class="text-info font-weight-bold text-uppercase">{{ __('Reimbursements') }}</small>
                        <div class="ledger-summary-val">${{ number_format($remReim, 2) }}</div>
                        <small class="text-muted">{{ __('Orig: ') }} ${{ number_format($origReim, 2) }} | {{ __('Paid: ') }} ${{ number_format($paidReim, 2) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark text-white border-danger">
                    <div class="card-body py-2">
                        <small class="text-danger font-weight-bold text-uppercase">{{ __('Employee Debts') }}</small>
                        <div class="ledger-summary-val">${{ number_format($remDebt, 2) }}</div>
                        <small class="text-muted">{{ __('Orig: ') }} ${{ number_format($origDebt, 2) }} | {{ __('Paid: ') }} ${{ number_format($paidDebt, 2) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark text-white border-success">
                    <div class="card-body py-2">
                        <small class="text-success font-weight-bold text-uppercase">{{ __('Corporate Bonuses') }}</small>
                        <div class="ledger-summary-val">${{ number_format($remBonus, 2) }}</div>
                        <small class="text-muted">{{ __('Orig: ') }} ${{ number_format($origBonus, 2) }} | {{ __('Paid: ') }} ${{ number_format($paidBonus, 2) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark text-white border-secondary">
                    <div class="card-body py-2">
                        <small class="text-muted font-weight-bold text-uppercase">{{ __('Net Owed to Employee') }}</small>
                        <div class="ledger-summary-val @if($netRemaining >= 0) text-success @else text-danger @endif">${{ number_format($netRemaining, 2) }}</div>
                        <small class="text-muted">{{ __('Payable alongside next payday') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Side: Add Ledger Entry Form -->
            <div class="col-lg-4 mb-4">
                <div class="card card-dark bg-dark border-secondary">
                    <div class="card-header border-secondary">
                        <h6 class="card-title mb-0 text-white font-weight-bold">{{ __('Add Ledger Entry') }}</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.staff.ledger.store', $staff->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="type" class="text-white">{{ __('Entry Type') }} <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control bg-dark text-white border-secondary" required>
                                    <option value="reimbursement">{{ __('Funds Owed by Employer (Out-of-Pocket Costs)') }}</option>
                                    <option value="debt">{{ __('Debts/Funds Owed by Employee (Damages, Advances)') }}</option>
                                    <option value="bonus">{{ __('Corporate Bonus') }}</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="amount" class="text-white">{{ __('Amount ($)') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="form-control bg-dark text-white border-secondary" placeholder="0.00" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="paid_amount" class="text-white">{{ __('Paid Amount ($) - Optional') }}</label>
                                <input type="number" step="0.01" min="0" name="paid_amount" id="paid_amount" class="form-control bg-dark text-white border-secondary" placeholder="0.00">
                                <small class="text-muted">{{ __('Enter any fraction already paid or leave blank') }}</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="description" class="text-white">{{ __('Description / Reference') }} <span class="text-danger">*</span></label>
                                <input type="text" name="description" id="description" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Fuel costs reimbursement" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="entry_date" class="text-white">{{ __('Transaction Date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="entry_date" id="entry_date" class="form-control bg-dark text-white border-secondary" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="form-group mb-4">
                                <label for="attachment" class="text-white">{{ __('Upload Proof / Document') }}</label>
                                <input type="file" name="attachment" id="attachment" class="form-control-file text-white">
                                <small class="text-muted d-block mt-1">{{ __('Optional PDF, ZIP or image proof (Max 10MB)') }}</small>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm btn-block"><i class="fas fa-plus mr-1"></i> {{ __('Add to Ledger') }}</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side: Ledger Table -->
            <div class="col-lg-8">
                <div class="card card-dark bg-dark border-secondary">
                    <div class="card-header border-secondary">
                        <h6 class="card-title mb-0 text-white font-weight-bold">{{ __('Transaction Ledger History') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive style-scroll">
                            <table class="table table-striped table-bordered miw-500 text-white border-secondary" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="bg-secondary text-white">
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th class="text-right">{{ __('Original') }}</th>
                                        <th class="text-right">{{ __('Paid') }}</th>
                                        <th class="text-right">{{ __('Remaining') }}</th>
                                        <th class="text-center">{{ __('Proof') }}</th>
                                        <th class="text-center">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($entries as $entry)
                                        <tr class="border-secondary">
                                            <td>{{ $entry->entry_date ? $entry->entry_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                {{ $entry->description }}
                                                @if($entry->created_by === 'staff')
                                                    <span class="badge badge-light text-dark ml-1" style="font-size: 0.65rem;">{{ __('Staff Requested') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($entry->type === 'reimbursement')
                                                    <span class="ledger-badge badge-reimbursement">{{ __('Employer Owed') }}</span>
                                                @elseif($entry->type === 'bonus')
                                                    <span class="ledger-badge badge-bonus">{{ __('Bonus') }}</span>
                                                @elseif($entry->type === 'debt')
                                                    <span class="ledger-badge badge-debt">{{ __('Employee Debt') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($entry->status === 'pending')
                                                    <span class="badge badge-warning status-badge text-uppercase font-weight-bold" style="font-size: 0.7rem;">{{ __('Pending') }}</span>
                                                @elseif($entry->status === 'approved')
                                                    <span class="badge badge-info status-badge text-uppercase font-weight-bold" style="font-size: 0.7rem;">{{ __('Approved') }}</span>
                                                @elseif($entry->status === 'partially_paid')
                                                    <span class="badge badge-primary status-badge text-uppercase font-weight-bold" style="font-size: 0.7rem;">{{ __('Partially Paid') }}</span>
                                                @elseif($entry->status === 'paid')
                                                    <span class="badge badge-success status-badge text-uppercase font-weight-bold" style="font-size: 0.7rem;">{{ __('Paid') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-right font-weight-bold text-light">${{ number_format($entry->amount, 2) }}</td>
                                            <td class="text-right font-weight-bold text-success">${{ number_format($entry->paid_amount, 2) }}</td>
                                            <td class="text-right font-weight-bold @if($entry->type === 'debt') text-danger @else text-info @endif">
                                                ${{ number_format(max(0, $entry->amount - $entry->paid_amount), 2) }}
                                            </td>
                                            <td class="text-center">
                                                @if($entry->attachment_path)
                                                    <a href="{{ route('admin.staff.ledger.proof', $entry->id) }}" target="_blank" class="btn btn-xs btn-outline-info" title="{{ __('Download Proof') }}"><i class="fas fa-file-download"></i></a>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    @if($entry->status === 'pending')
                                                        <button type="button" class="btn btn-xs btn-success mr-1" data-toggle="modal" data-target="#approveModal-{{ $entry->id }}" title="{{ __('Approve Request') }}"><i class="fas fa-check"></i></button>
                                                    @elseif($entry->status === 'approved' || $entry->status === 'partially_paid')
                                                        <button type="button" class="btn btn-xs btn-info mr-1" data-toggle="modal" data-target="#payModal-{{ $entry->id }}" title="{{ __('Record Payment') }}"><i class="fas fa-hand-holding-usd"></i></button>
                                                    @endif

                                                    <form action="{{ route('admin.staff.ledger.destroy', [$staff->id, $entry->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ledger entry?');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-danger" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4 small">{{ __('No ledger transactions recorded yet.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals for approving and paying ledger items -->
    @foreach ($entries as $entry)
        <!-- Approve Modal -->
        @if($entry->status === 'pending')
            <div class="modal fade" id="approveModal-{{ $entry->id }}" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel-{{ $entry->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content bg-dark text-white border-secondary">
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title font-weight-bold" id="approveModalLabel-{{ $entry->id }}">{{ __('Approve Reimbursement') }}</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.staff.ledger.approve', [$staff->id, $entry->id]) }}" method="POST">
                            @csrf
                            <div class="modal-body text-left">
                                <p>{{ __('Review reimbursement requested by staff:') }}</p>
                                <div class="bg-secondary p-3 rounded mb-3 text-white">
                                    <strong>{{ __('Description:') }}</strong> {{ $entry->description }}<br>
                                    <strong>{{ __('Requested Amount:') }}</strong> ${{ number_format($entry->amount, 2) }}<br>
                                    <strong>{{ __('Date:') }}</strong> {{ $entry->entry_date ? $entry->entry_date->format('M d, Y') : 'N/A' }}
                                </div>
                                <div class="form-group mb-0">
                                    <label for="paid_amount-{{ $entry->id }}" class="font-weight-bold small text-muted text-uppercase mb-2 text-white">{{ __('Initial Paid Amount ($) - Optional') }}</label>
                                    <input type="number" step="0.01" min="0" max="{{ $entry->amount }}" name="paid_amount" id="paid_amount-{{ $entry->id }}" class="form-control bg-dark text-white border-secondary" placeholder="0.00" value="0.00">
                                    <small class="text-muted d-block mt-1">{{ __('Enter how much of this cost has already been paid/refunded to the staff member.') }}</small>
                                </div>
                            </div>
                            <div class="modal-footer border-secondary">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" class="btn btn-success btn-sm">{{ __('Approve & Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Record Payment Modal -->
        @if($entry->status === 'approved' || $entry->status === 'partially_paid')
            <div class="modal fade" id="payModal-{{ $entry->id }}" tabindex="-1" role="dialog" aria-labelledby="payModalLabel-{{ $entry->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content bg-dark text-white border-secondary">
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title font-weight-bold" id="payModalLabel-{{ $entry->id }}">{{ __('Record Payment') }}</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.staff.ledger.pay', [$staff->id, $entry->id]) }}" method="POST">
                            @csrf
                            <div class="modal-body text-left">
                                @php
                                    $outstanding = $entry->amount - $entry->paid_amount;
                                @endphp
                                <div class="bg-secondary p-3 rounded mb-3 text-white">
                                    <strong>{{ __('Description:') }}</strong> {{ $entry->description }}<br>
                                    <strong>{{ __('Total Amount:') }}</strong> ${{ number_format($entry->amount, 2) }}<br>
                                    <strong>{{ __('Amount Paid to Date:') }}</strong> ${{ number_format($entry->paid_amount, 2) }}<br>
                                    <strong>{{ __('Outstanding Balance:') }}</strong> <span class="text-warning">${{ number_format($outstanding, 2) }}</span>
                                </div>
                                <div class="form-group mb-0">
                                    <label for="payment_amount-{{ $entry->id }}" class="font-weight-bold small text-muted text-uppercase mb-2 text-white">{{ __('Payment Amount ($)') }} <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0.01" max="{{ $outstanding }}" name="payment_amount" id="payment_amount-{{ $entry->id }}" class="form-control bg-dark text-white border-secondary" placeholder="0.00" value="{{ $outstanding }}" required>
                                    <small class="text-muted d-block mt-1">{{ __('Enter the payment amount to record towards this entry.') }}</small>
                                </div>
                            </div>
                            <div class="modal-footer border-secondary">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" class="btn btn-primary btn-sm">{{ __('Record Payment') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection

