@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .ledger-card {
        background: #11151e;
        border: 1px solid #28303f;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.3);
        margin-bottom: 25px;
        color: #f1f5f9;
    }
    .wage-indicator {
        font-size: 2.2rem;
        font-weight: 800;
        color: #10b981;
        font-family: 'Montserrat', sans-serif;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 600;
    }
    .ledger-summary-box {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid #28303f;
    }

    body.light-mode .ledger-card {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06) !important;
        color: #0f172a !important;
    }
    body.light-mode .ledger-card [style*="color: #fecc56"],
    body.light-mode .ledger-card [style*="color:#fecc56"],
    body.light-mode .ledger-card h5,
    body.light-mode .ledger-card h6 {
        color: #b45309 !important;
    }
    body.light-mode .ledger-card input.form-control,
    body.light-mode .ledger-card textarea.form-control {
        background: #ffffff !important;
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
    }
    body.light-mode .ledger-card .list-group-item {
        border-color: #e2e8f0 !important;
    }
    body.light-mode .ledger-card table {
        color: #0f172a !important;
    }
    body.light-mode .ledger-card table th {
        color: #475569 !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .ledger-card table td {
        color: #0f172a !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .ledger-card .list-group-item .font-italic {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
        color: #475569 !important;
    }
    body.light-mode .ledger-summary-box {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }
</style>
@endsection

@section('content')
@php
    $ledgerTotals = $user->staffLedgerEntries()
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

    $origReim = $ledgerTotals->orig_reim ?: 0.00;
    $paidReim = $ledgerTotals->paid_reim ?: 0.00;
    $remReim = max(0, $origReim - $paidReim);

    $origDebt = $ledgerTotals->orig_debt ?: 0.00;
    $paidDebt = $ledgerTotals->paid_debt ?: 0.00;
    $remDebt = max(0, $origDebt - $paidDebt);

    $origBonus = $ledgerTotals->orig_bonus ?: 0.00;
    $paidBonus = $ledgerTotals->paid_bonus ?: 0.00;
    $remBonus = max(0, $origBonus - $paidBonus);

    $netRemaining = $remReim + $remBonus - $remDebt;
@endphp
<div class="container-fluid py-4">
    <div class="row">
        <!-- Main Ledger Details -->
        <div class="col-lg-7">
            <!-- Reimbursement Ledger Card -->
            <div class="card ledger-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 text-white font-weight-bold"><i class="fas fa-wallet mr-2 text-info"></i>{{ __('Reimbursement Ledger') }}</h5>
                        <button type="button" class="btn btn-outline-info btn-sm font-weight-bold" data-toggle="modal" data-target="#requestReimbursementModal">
                            <i class="fas fa-plus mr-1"></i> {{ __('Request Reimbursement') }}
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr class="small font-weight-bold text-muted" style="background: rgba(255,255,255,0.03);">
                                    <th>{{ __('Sub-Ledger Category') }}</th>
                                    <th class="text-right">{{ __('Original') }}</th>
                                    <th class="text-right">{{ __('Paid') }}</th>
                                    <th class="text-right">{{ __('Remaining') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-muted font-weight-medium">{{ __('Funds Owed by Employer (Out-of-Pocket)') }}</td>
                                    <td class="font-weight-bold text-white text-right">${{ number_format($origReim, 2) }}</td>
                                    <td class="font-weight-bold text-success text-right">${{ number_format($paidReim, 2) }}</td>
                                    <td class="font-weight-bold text-info text-right">${{ number_format($remReim, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-medium">{{ __('Debts/Funds Owed by Employee') }}</td>
                                    <td class="font-weight-bold text-white text-right">${{ number_format($origDebt, 2) }}</td>
                                    <td class="font-weight-bold text-success text-right">${{ number_format($paidDebt, 2) }}</td>
                                    <td class="font-weight-bold text-danger text-right">${{ number_format($remDebt, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-medium">{{ __('Corporate Bonuses') }}</td>
                                    <td class="font-weight-bold text-white text-right">${{ number_format($origBonus, 2) }}</td>
                                    <td class="font-weight-bold text-success text-right">${{ number_format($paidBonus, 2) }}</td>
                                    <td class="font-weight-bold text-success text-right">${{ number_format($remBonus, 2) }}</td>
                                </tr>
                                <tr class="font-weight-bold" style="background: rgba(255,255,255,0.03);">
                                    <td colspan="3" class="text-white">{{ __('Net Remaining Balance Owed alongside next paycheck/payday') }}</td>
                                    <td class="text-right @if($netRemaining >= 0) text-success @else text-danger @endif">
                                        ${{ number_format($netRemaining, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Ledger Transactions Card -->
            <div class="card ledger-card">
                <div class="card-body">
                    <h6 class="mb-3 text-white font-weight-bold"><i class="fas fa-list-alt mr-2 text-primary"></i>{{ __('Itemized Ledger History') }}</h6>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                            <thead>
                                <tr class="text-muted small">
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-right">{{ __('Original') }}</th>
                                    <th class="text-right">{{ __('Paid') }}</th>
                                    <th class="text-right">{{ __('Remaining') }}</th>
                                    <th class="text-center">{{ __('Proof & Paystub') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->staffLedgerEntries()->orderBy('entry_date', 'desc')->orderBy('id', 'desc')->get() as $entry)
                                    <tr>
                                        <td>{{ $entry->entry_date ? $entry->entry_date->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $entry->description }}</td>
                                        <td>
                                            @if($entry->type === 'reimbursement')
                                                <span class="badge badge-info status-badge">{{ __('Employer Owed') }}</span>
                                            @elseif($entry->type === 'bonus')
                                                <span class="badge badge-success status-badge">{{ __('Bonus') }}</span>
                                            @elseif($entry->type === 'debt')
                                                <span class="badge badge-danger status-badge">{{ __('Employee Debt') }}</span>
                                            @else
                                                <span class="badge badge-primary status-badge">{{ ucfirst($entry->type) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($entry->status === 'pending')
                                                <span class="badge badge-warning status-badge">{{ __('Pending') }}</span>
                                            @elseif($entry->status === 'approved')
                                                <span class="badge badge-info status-badge">{{ __('Approved') }}</span>
                                            @elseif($entry->status === 'partially_paid')
                                                <span class="badge badge-primary status-badge">{{ __('Partially Paid') }}</span>
                                            @elseif($entry->status === 'paid')
                                                <span class="badge badge-success status-badge">{{ __('Paid') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right font-weight-bold text-dark">
                                            ${{ number_format($entry->amount, 2) }}
                                        </td>
                                        <td class="text-right font-weight-bold text-success">
                                            ${{ number_format($entry->paid_amount, 2) }}
                                        </td>
                                        <td class="text-right font-weight-bold @if($entry->type === 'debt') text-danger @else text-info @endif">
                                            ${{ number_format(max(0, $entry->amount - $entry->paid_amount), 2) }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center" style="gap: 4px;">
                                                @if($entry->attachment_path)
                                                    <a href="{{ route('staff.ledger.proof', $entry->id) }}" target="_blank" class="btn btn-xs btn-outline-info" title="{{ __('Download Proof') }}">
                                                        <i class="fas fa-file-download"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('staff.paystub.download', $entry->id) }}" class="btn btn-xs btn-outline-success font-weight-bold" title="{{ __('Download Official Paystub (PDF)') }}">
                                                    <i class="fas fa-file-invoice-dollar mr-1"></i>Paystub
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-3 small">{{ __('No transactions recorded.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Earnings & Wage Calculator Card -->
            <div class="card ledger-card">
                <div class="card-body">
                    <h5 class="mb-4 text-white font-weight-bold"><i class="fas fa-calculator mr-2 text-success"></i>{{ __('Earnings & Wage Calculator') }}</h5>
                    
                    <div class="row mb-4 p-3 rounded ledger-summary-box">
                        <div class="col-sm-6 text-center border-right border-secondary">
                            <span class="text-muted small text-uppercase font-weight-bold d-block">{{ __('Accumulated Wages') }}</span>
                            <div class="wage-indicator">${{ number_format($totalEarned, 2) }}</div>
                            <small class="text-muted">{{ __('Total completed shifts') }}</small>
                        </div>
                        <div class="col-sm-6 text-center">
                            <span class="text-muted small text-uppercase font-weight-bold d-block">{{ __('Hourly Rate') }}</span>
                            <div class="wage-indicator text-primary">${{ number_format($staffDetail->hourly_rate, 2) }}<span style="font-size: 0.9rem; font-weight: 500;">/hr</span></div>
                            <small class="text-muted">{{ __('Pay Schedule: ') }} <strong class="text-white">{{ ucfirst($staffDetail->pay_schedule) }}</strong></small>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-6">
                            <h6 class="mb-0 text-white font-weight-bold">{{ __('Next Payday') }}</h6>
                        </div>
                        <div class="col-6 text-right font-weight-bold text-warning">
                            {{ $staffDetail->next_pay_date ? $staffDetail->next_pay_date->format('F d, Y') : __('Not Scheduled') }}
                        </div>
                    </div>

                    <h6 class="mb-3 mt-4 text-white font-weight-bold">{{ __('Recent Shifts') }}</h6>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="text-muted small">
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('Rate') }}</th>
                                    <th class="text-right">{{ __('Wages') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->staffTimeLogs()->whereNotNull('clocked_out_at')->latest()->take(5)->get() as $log)
                                    <tr>
                                        <td>{{ $log->clocked_in_at->format('M d, Y') }}</td>
                                        <td>{{ round($log->duration_seconds / 3600, 2) }} hrs</td>
                                        <td>${{ number_format($log->hourly_rate_at_time, 2) }}/hr</td>
                                        <td class="text-right text-success font-weight-bold">${{ number_format($log->earned_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3 small">{{ __('No shifts recorded yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Payout Request -->
        <div class="col-lg-5">
            <!-- Request Payout Card -->
            <div class="card ledger-card mb-4">
                <div class="card-body">
                    <h5 class="mb-4 font-weight-bold" style="color: #fecc56;"><i class="fas fa-hand-holding-usd mr-2 text-warning"></i>{{ __('Request Payout') }}</h5>
                    
                    <div class="alert alert-info small mb-4" style="background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.3); color: #38bdf8;">
                        <i class="fas fa-info-circle mr-2"></i> {{ __('Use this section to request a payout. Requested funds are deducted immediately upon submission.') }}
                        <div class="mt-2 font-weight-bold" style="color: inherit;">
                            {{ __('Available Claimable Balance: ') }} <span class="text-success font-weight-bold" style="font-size: 15px;">${{ number_format($claimableAmount, 2) }}</span>
                            @if(!empty($pendingPayouts) && $pendingPayouts > 0)
                                <div class="text-warning small mt-1 font-weight-bold"><i class="fas fa-hourglass-half mr-1"></i> Pending Payout Hold: ${{ number_format($pendingPayouts, 2) }}</div>
                            @endif
                            @if(!empty($totalPaidOut) && $totalPaidOut > 0)
                                <div class="text-info small mt-1"><i class="fas fa-check-circle mr-1"></i> Total Disbursed to Date: ${{ number_format($totalPaidOut, 2) }}</div>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('staff.request-payout') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold" style="color: inherit;">{{ __('Payout Request Amount ($)') }}</label>
                            <input type="number" step="0.01" min="1" max="{{ $claimableAmount }}" name="amount" class="form-control" placeholder="e.g. 500.00" required value="{{ old('amount', $claimableAmount > 0 ? $claimableAmount : '') }}" style="background: #161a23; border: 1px solid #28303f; color: #f1f5f9;">
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold" style="color: inherit;">{{ __('Notes / Comments') }}</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="{{ __('Optional notes for supervisor...') }}" style="background: #161a23; border: 1px solid #28303f; color: #f1f5f9;"></textarea>
                        </div>

                        <button type="submit" class="btn btn-warning btn-block text-uppercase font-weight-bold text-dark" @if($claimableAmount <= 0) disabled @endif style="letter-spacing: 0.5px; border-radius: 8px; padding: 12px;">
                            <i class="fas fa-paper-plane mr-1"></i> {{ __('Submit Payout Request') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Payout Request History Card -->
            <div class="card ledger-card">
                <div class="card-body">
                    <h6 class="mb-3 font-weight-bold" style="color: #fecc56;"><i class="fas fa-history mr-2"></i>{{ __('Request History') }}</h6>
                    
                    <div class="list-group list-group-flush">
                        @forelse($payoutRequests as $request)
                            <div class="list-group-item px-0 py-3" style="background: transparent; border-color: rgba(255,255,255,0.08);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="font-weight-bold" style="color: #fecc56; font-size: 15px;">${{ number_format($request->amount, 2) }}</span>
                                        <small class="text-muted d-block">{{ $request->created_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap: 6px;">
                                        @if($request->status === 'pending')
                                            <span class="badge badge-warning status-badge"><i class="fas fa-clock mr-1"></i> {{ __('Pending') }}</span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge badge-info status-badge"><i class="fas fa-check-circle mr-1"></i> {{ __('Approved') }}</span>
                                        @elseif($request->status === 'paid')
                                            <span class="badge badge-success status-badge"><i class="fas fa-badge-check mr-1"></i> {{ __('Paid') }}</span>
                                        @elseif($request->status === 'rejected' || $request->status === 'declined')
                                            <span class="badge badge-danger status-badge"><i class="fas fa-times-circle mr-1"></i> {{ __('Declined') }}</span>
                                        @endif
                                        <a href="{{ route('staff.payout.stub.download', $request->id) }}" class="btn btn-xs btn-outline-success font-weight-bold" title="{{ __('Download Official Paystub (PDF)') }}" target="_blank">
                                            <i class="fas fa-file-invoice-dollar mr-1"></i>{{ __('Paystub') }}
                                        </a>
                                    </div>
                                </div>
                                @if($request->notes)
                                    <div class="mt-2 small text-muted font-italic p-2 rounded" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                                        "{{ $request->notes }}"
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-center text-muted small py-3 mb-0">{{ __('No payout requests submitted yet.') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<!-- Reimbursement Request Modal -->
<div class="modal fade" id="requestReimbursementModal" tabindex="-1" role="dialog" aria-labelledby="reimbursementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-dark" id="reimbursementModalLabel">{{ __('New Reimbursement Request') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('staff.reimbursement.request') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-left">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-muted text-uppercase mb-2">{{ __('Reimbursement Description') }} <span class="text-danger">*</span></label>
                        <input type="text" name="description" class="form-control" placeholder="{{ __('e.g. Fuel expenses for travel to client site') }}" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-muted text-uppercase mb-2">{{ __('Amount ($)') }} <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="amount" class="form-control" placeholder="0.00" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-muted text-uppercase mb-2">{{ __('Transaction Date') }} <span class="text-danger">*</span></label>
                        <input type="date" name="entry_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold small text-muted text-uppercase mb-2"><i class="fas fa-camera mr-1 text-primary"></i> {{ __('Upload Receipt / Snap Photo') }}</label>
                        <input type="file" name="attachment" id="reimbursementReceiptInput" accept="image/*,application/pdf" capture="environment" class="form-control-file" onchange="previewAndCompressReceipt(this)">
                        <small class="text-muted d-block mt-1">{{ __('Snap receipt using phone camera or choose file (PDF, PNG, JPG). Auto-compressed for high speed.') }}</small>
                        <div id="receiptPreviewBox" class="mt-2 text-center" style="display:none;">
                            <img id="receiptPreviewImg" src="" style="max-height: 120px; border-radius: 6px; border: 1px solid #cbd5e1; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                            <small class="text-success d-block mt-1 font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Receipt ready for upload</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Submit Request') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewAndCompressReceipt(input) {
        if (input.files && input.files[0]) {
            var file = input.files[0];
            if (file.type.match('image.*')) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var previewBox = document.getElementById('receiptPreviewBox');
                    var previewImg = document.getElementById('receiptPreviewImg');
                    if (previewBox && previewImg) {
                        previewImg.src = e.target.result;
                        previewBox.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    }
</script>
@endsection

