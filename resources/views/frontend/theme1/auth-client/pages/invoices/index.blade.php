@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | Invoices & Retainers')

@section('page-css')
<style>
/* BILLING STAT CARDS */
.billing-stat-card {
    background: linear-gradient(145deg, #181d27 0%, #11151e 100%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 20px;
    position: relative;
    overflow: hidden;
    transition: all 0.25s ease;
    box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    height: 100%;
}
.billing-stat-card:hover {
    border-color: rgba(254, 204, 86, 0.35);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.4);
}
.billing-stat-card .stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: rgba(254, 204, 86, 0.1);
    color: #fecc56;
    border: 1px solid rgba(254, 204, 86, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
}
.billing-stat-card .stat-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #94a3b8;
    margin-bottom: 4px;
}
.billing-stat-card .stat-num {
    font-size: 1.65rem;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.2;
}

/* PORTAL CARD CONTAINER */
.portal-card { background: #161a23; border: 1px solid #28303f; border-radius: 12px; }
.portal-card-header { background: #1f2533; border-bottom: 1px solid #2e3849; color: #fecc56; font-weight: 700; border-radius: 12px 12px 0 0 !important; }

/* TABLE & MOBILE CARDS */
.table-portal-wrap { border: 1px solid #28303f; border-radius: 10px; width: 100%; background: #161a23; overflow-x: auto; }
.table-portal { width: 100%; border-collapse: separate; border-spacing: 0; color: #f1f5f9; margin-bottom: 0; }
.table-portal thead th { background: #1f2533 !important; color: #fecc56 !important; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-top: none; border-bottom: 2px solid #333d4e !important; padding: 12px 14px; white-space: nowrap; }
.table-portal tbody tr { background: #161a23; transition: background 0.15s; }
.table-portal tbody tr:hover { background: #1e2430 !important; }
.table-portal td { padding: 12px 14px; border-top: 1px solid #262e3d; vertical-align: middle; color: #f1f5f9; font-size: 13px; }
.table-portal td strong { color: #ffffff !important; font-weight: 700; }
.table-portal td:last-child, .table-portal th:last-child { text-align: right; white-space: nowrap; }

.pay-btn { background: linear-gradient(135deg,#fecc56,#f0a500); color:#000 !important; border:none; font-weight:700; border-radius: 6px; padding: 6px 14px; transition:all .2s; box-shadow: 0 2px 8px rgba(254,204,86,0.3); font-size: 12px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; }
.pay-btn:hover { transform:translateY(-1px); box-shadow:0 4px 16px rgba(254,204,86,.5); color:#000 !important; }
.btn-portal-secondary { background: #262e3d; border: 1px solid #374151; color: #e2e8f0; font-weight: 600; border-radius: 6px; font-size: 12px; padding: 6px 12px; text-decoration: none; display: inline-flex; align-items: center; }
.btn-portal-secondary:hover { background: #333d4e; color: #fff; }

.status-badge-unpaid { background-color: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 700; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; display: inline-block; }
.status-badge-paid { background-color: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); font-weight: 700; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; display: inline-block; }
.status-badge-pending { background-color: rgba(245, 158, 11, 0.15); color: #fecc56; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: 700; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; display: inline-block; }
.status-badge-cancelled { background-color: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3); font-weight: 700; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; display: inline-block; }

/* Light mode overrides */
body.light-mode .billing-stat-card { background: #ffffff !important; border-color: #e2e8f0 !important; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
body.light-mode .billing-stat-card .stat-num { color: #0f172a !important; }
body.light-mode .table-portal-wrap, body.light-mode .table-portal tbody tr { background: #ffffff !important; }
body.light-mode .table-portal thead th { background: #f8fafc !important; color: #b45309 !important; border-color: #e2e8f0 !important; }
body.light-mode .table-portal td { border-color: #e2e8f0 !important; color: #334155 !important; }
body.light-mode .table-portal td strong { color: #0f172a !important; }
body.light-mode .btn-portal-secondary { background: #f1f5f9; border-color: #cbd5e1; color: #334155; }

@media (max-width: 991px) {
    .table-portal-wrap { border: none !important; background: transparent !important; }
    .table-portal { min-width: 0 !important; width: 100% !important; display: block !important; }
    .table-portal thead { display: none !important; }
    .table-portal tbody { display: block !important; width: 100% !important; }
    .table-portal tbody tr { 
        display: block !important; 
        width: 100% !important; 
        margin-bottom: 14px !important; 
        border: 1px solid #28303f !important; 
        border-radius: 10px !important; 
        padding: 14px 16px !important; 
    }
    .table-portal td {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 8px 0 !important;
        border-top: none !important;
        border-bottom: 1px solid #232a38 !important;
        text-align: right !important;
    }
    .table-portal td:last-child {
        border-bottom: none !important;
        padding-top: 12px !important;
        justify-content: flex-end !important;
    }
    .table-portal td[data-label]::before {
        content: attr(data-label);
        font-weight: 700;
        color: #94a3b8;
        font-size: 11px;
        text-transform: uppercase;
        text-align: left;
    }
}
</style>
@endsection

@section('content')
@php
    $totalInvoiced = $invoices->sum(fn($i) => $i->total_amount ?: $i->amount);
    $totalPaid = $invoices->where('status', 'paid')->sum(fn($i) => $i->total_amount ?: $i->amount);
    $totalOutstanding = $invoices->whereNotIn('status', ['paid', 'cancelled'])->sum(fn($i) => $i->total_amount ?: $i->amount);
    $unpaidInvoices = $invoices->whereNotIn('status', ['paid', 'cancelled']);
    $firstUnpaid = $unpaidInvoices->first();
    $hasOverdue = $unpaidInvoices->filter(fn($i) => $i->due_date && strtotime($i->due_date) < time())->isNotEmpty();
@endphp

<!-- PAGE HEADER -->
<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h3 class="font-weight-bold mb-1 text-white" style="letter-spacing: -0.5px;">
            <i class="fas fa-file-invoice-dollar text-warning mr-2"></i> {{ __('Billing, Invoices & Escrow Hub') }}
        </h3>
        <p class="small mb-0 text-muted">
            {{ __('Certified legal invoices, retainer statements, banking details, and payment verification proofs.') }}
        </p>
    </div>
    <div class="col-md-5 text-md-right mt-3 mt-md-0 d-flex flex-wrap justify-content-md-end" style="gap: 8px;">
        <a href="{{ route('client.dashboard') }}" class="btn btn-sm btn-outline-secondary text-light font-weight-bold px-3">
            <i class="fas fa-arrow-left mr-1"></i> {{ __('Dashboard') }}
        </a>
        <button type="button" class="btn btn-sm btn-warning text-dark font-weight-bold px-3" onclick="openQuickPaymentModal()">
            <i class="fas fa-credit-card mr-1"></i> {{ __('Make a Payment') }}
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4 font-weight-bold" style="background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3);">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    </div>
@endif

<!-- FINANCIAL METRICS OVERVIEW -->
<div class="row mb-4">
    <div class="col-6 col-lg-3 mb-3">
        <div class="billing-stat-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="stat-title">{{ __('Total Invoiced') }}</div>
                <div class="stat-icon"><i class="fas fa-file-invoice"></i></div>
            </div>
            <div class="stat-num text-white">${{ number_format($totalInvoiced, 2) }}</div>
            <small class="text-muted" style="font-size:11px;">{{ $invoices->count() }} {{ __('Total Issued Records') }}</small>
        </div>
    </div>
    <div class="col-6 col-lg-3 mb-3">
        <div class="billing-stat-card" style="{{ $totalOutstanding > 0 ? 'border-color: rgba(239, 68, 68, 0.4);' : '' }}">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="stat-title">{{ __('Balance Outstanding') }}</div>
                <div class="stat-icon" style="background:rgba(239,68,68,0.1); color:#ef4444; border-color:rgba(239,68,68,0.2);"><i class="fas fa-clock"></i></div>
            </div>
            <div class="stat-num {{ $totalOutstanding > 0 ? 'text-danger' : 'text-success' }}">
                ${{ number_format($totalOutstanding, 2) }}
            </div>
            <small class="{{ $totalOutstanding > 0 ? 'text-danger font-weight-bold' : 'text-success' }}" style="font-size:11px;">
                {{ $totalOutstanding > 0 ? __('Payment Required') : __('Account in Good Standing') }}
            </small>
        </div>
    </div>
    <div class="col-6 col-lg-3 mb-3">
        <div class="billing-stat-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="stat-title">{{ __('Confirmed Cleared') }}</div>
                <div class="stat-icon" style="background:rgba(34,197,94,0.1); color:#22c55e; border-color:rgba(34,197,94,0.2);"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="stat-num text-success">${{ number_format($totalPaid, 2) }}</div>
            <small class="text-muted" style="font-size:11px;">{{ __('Verified Disbursements') }}</small>
        </div>
    </div>
    <div class="col-6 col-lg-3 mb-3">
        <div class="billing-stat-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="stat-title">{{ __('Currency') }}</div>
                <div class="stat-icon"><i class="fas fa-globe"></i></div>
            </div>
            <div class="stat-num text-warning">{{ Auth::user()->preferred_currency ?: 'USD ($)' }}</div>
            <small class="text-muted" style="font-size:11px;">{{ __('Primary settlement unit') }}</small>
        </div>
    </div>
</div>

<!-- DUE COUNTDOWN BANNER (IF ACTIVE BALANCE) -->
@if($totalOutstanding > 0)
<div class="portal-card mb-4 p-4 shadow-sm" style="border-left: 5px solid #fecc56 !important; background: #1c1811; border-color: #4a3818;">
    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:14px;">
        <div>
            <h5 class="font-weight-bold mb-1 text-warning">
                <i class="fas fa-hourglass-half mr-2 text-warning"></i> 
                {{ __('Retainer & Settlement Due Notice') }}
            </h5>
            <p class="mb-0 text-white font-weight-bold" style="font-size: 13.5px;">
                {{ __('You have') }} <span class="text-warning font-weight-bold">${{ number_format($totalOutstanding, 2) }}</span> {{ __('pending legal retainer settlement across') }} {{ $unpaidInvoices->count() }} {{ __('invoice(s).') }}
            </p>
            <p class="mb-0 text-muted small mt-1">
                {{ __('Prompt settlement ensures uninterrupted forensic intelligence, attorney court filings, and regulatory representation.') }}
            </p>
        </div>
        <div class="text-md-right" style="min-width: 220px;">
            <span class="small font-weight-bold text-uppercase d-block text-muted">{{ __('Recommended Settlement Window:') }}</span>
            <div id="penaltyCountdownInvoicesPage" class="font-weight-bold text-warning mt-1" style="font-size: 1.35rem; letter-spacing: 1px; font-family: monospace;">
                24h 00m 00s
            </div>
            <button type="button" class="btn btn-warning btn-sm font-weight-bold text-dark mt-2" onclick="openQuickPaymentModal()">
                <i class="fas fa-bolt mr-1"></i> {{ __('Settle Balance Now') }}
            </button>
        </div>
    </div>
</div>
<script>
(function() {
    var remainingSec = 86400;
    function updateCountdown() {
        if (remainingSec <= 0) {
            document.getElementById('penaltyCountdownInvoicesPage').innerHTML = "IMMEDIATE ATTENTION";
            return;
        }
        var h = Math.floor(remainingSec / 3600);
        var m = Math.floor((remainingSec % 3600) / 60);
        var s = remainingSec % 60;
        document.getElementById('penaltyCountdownInvoicesPage').innerHTML = 
            (h < 10 ? '0' : '') + h + 'h ' + 
            (m < 10 ? '0' : '') + m + 'm ' + 
            (s < 10 ? '0' : '') + s + 's';
        remainingSec--;
    }
    updateCountdown();
    setInterval(updateCountdown, 1000);
})();
</script>
@endif

<!-- INVOICES TABLE -->
<div class="portal-card mb-4 shadow-sm">
    <div class="portal-card-header py-3 px-4 d-flex justify-content-between align-items-center flex-wrap" style="gap: 8px;">
        <h5 class="mb-0 font-weight-bold text-warning"><i class="fas fa-file-invoice mr-2"></i>{{ __('Official Invoices & Retainer Statements') }}</h5>
        <span class="badge badge-dark px-3 py-1 font-weight-bold text-muted border border-secondary" style="font-size: 11px;">
            <i class="fas fa-shield-alt text-success mr-1"></i> {{ __('Encrypted Financial Records') }}
        </span>
    </div>

    <div class="p-3 p-md-4">
        @if($invoices->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-file-invoice-dollar fa-4x text-secondary mb-3 d-block"></i>
                <h5 class="text-white font-weight-bold">{{ __('No invoices found.') }}</h5>
                <p class="text-muted small">{{ __('You currently have no generated or pending invoices on your profile.') }}</p>
            </div>
        @else
            <div class="table-portal-wrap">
                <table class="table-portal">
                    <thead>
                        <tr>
                            <th>{{ __('Invoice Ref') }}</th>
                            <th>{{ __('Linked Case / Matter') }}</th>
                            <th>{{ __('Total Billed') }}</th>
                            <th>{{ __('Due Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $inv)
                            @php
                                $invAmt = $inv->total_amount ?: $inv->amount;
                                $isPaid = strtolower($inv->status) === 'paid';
                                $isPending = strtolower($inv->status) === 'pending';
                                $isCancelled = strtolower($inv->status) === 'cancelled';
                            @endphp
                            <tr>
                                <td data-label="{{ __('Invoice Ref') }}">
                                    <strong class="text-white d-block">{{ $inv->invoice_number }}</strong>
                                    <small class="text-muted">{{ $inv->created_at ? $inv->created_at->format('M d, Y') : '-' }}</small>
                                </td>
                                <td data-label="{{ __('Linked Case') }}">
                                    @if($inv->clientCase)
                                        <span class="text-warning font-weight-bold">{{ $inv->clientCase->case_number }}</span>
                                        <small class="text-muted d-block">{{ Str::limit($inv->clientCase->title, 35) }}</small>
                                    @else
                                        <span class="text-light">{{ __('General Case Retainer & Advisory') }}</span>
                                    @endif
                                </td>
                                <td data-label="{{ __('Total Billed') }}">
                                    <strong class="text-warning" style="font-size: 14px;">${{ number_format($invAmt, 2) }}</strong>
                                </td>
                                <td data-label="{{ __('Due Date') }}">
                                    <small class="text-light">{{ $inv->due_date ? date('M d, Y', strtotime($inv->due_date)) : __('Upon Receipt') }}</small>
                                </td>
                                <td data-label="{{ __('Status') }}">
                                    @if($isPaid)
                                        <span class="status-badge-paid"><i class="fas fa-check-circle mr-1"></i> {{ __('Paid') }}</span>
                                    @elseif($isPending)
                                        <span class="status-badge-pending"><i class="fas fa-clock mr-1"></i> {{ __('Under Review') }}</span>
                                    @elseif($isCancelled)
                                        <span class="status-badge-cancelled">{{ __('Cancelled') }}</span>
                                    @else
                                        <span class="status-badge-unpaid"><i class="fas fa-exclamation-circle mr-1"></i> {{ __('Payment Due') }}</span>
                                    @endif
                                </td>
                                <td data-label="{{ __('Action') }}">
                                    <div class="d-inline-flex" style="gap: 6px;">
                                        <a href="{{ route('client.invoices.show', $inv->id) }}" class="pay-btn">
                                            <i class="fas fa-receipt mr-1"></i> {{ $isPaid ? __('Statement') : __('View & Settle') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- QUICK PAYMENT MODAL -->
<div class="modal fade" id="quickPaymentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content text-white" style="background: #161a23; border: 1px solid #fecc56; border-radius: 12px;">
            <div class="modal-header border-secondary py-3 px-4" style="background: #1f2533;">
                <h5 class="modal-title text-warning font-weight-bold">
                    <i class="fas fa-university mr-2"></i> {{ __('Client Retainer & Fee Depository') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">
                    {{ __('To settle your legal retainer or case invoice, use the verified escrow account instructions below. After remitting funds, upload your transfer confirmation slip.') }}
                </p>

                <!-- Banking Wire Box -->
                <div class="p-3 rounded mb-3" style="background: #11151e; border: 1px solid #28303f;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="font-weight-bold text-warning small text-uppercase"><i class="fas fa-building mr-1"></i> {{ __('Primary Legal Trust Wire Account') }}</span>
                        <span class="badge badge-success px-2 py-1" style="font-size: 10px;">{{ __('VERIFIED TRUST ACCOUNT') }}</span>
                    </div>
                    <div class="row small text-light">
                        <div class="col-sm-6 mb-2">
                            <span class="text-muted d-block">{{ __('Beneficiary Name:') }}</span>
                            <strong class="text-white">{{ config('app.name', 'Your CPA Expert') }} Trust & Escrow LLC</strong>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <span class="text-muted d-block">{{ __('Bank Name:') }}</span>
                            <strong class="text-white">JPMorgan Chase Bank, N.A.</strong>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <span class="text-muted d-block">{{ __('Routing (ABA) / SWIFT:') }}</span>
                            <strong class="text-warning">CHASUS33 / 021000021</strong>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <span class="text-muted d-block">{{ __('Payment Reference:') }}</span>
                            <strong class="text-warning">{{ Auth::user()->email }}</strong>
                        </div>
                    </div>
                </div>

                @if($firstUnpaid)
                    <div class="p-3 rounded" style="background: rgba(254, 204, 86, 0.08); border: 1px solid rgba(254, 204, 86, 0.25);">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <small class="text-muted d-block">{{ __('Next Unpaid Invoice:') }}</small>
                                <strong class="text-white">{{ $firstUnpaid->invoice_number }} &bull; ${{ number_format($firstUnpaid->total_amount ?: $firstUnpaid->amount, 2) }}</strong>
                            </div>
                            <a href="{{ route('client.invoices.show', $firstUnpaid->id) }}" class="btn btn-warning btn-sm font-weight-bold text-dark mt-2 mt-sm-0">
                                <i class="fas fa-upload mr-1"></i> {{ __('Upload Payment Proof for This Invoice') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer border-secondary py-2 px-4">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
function openQuickPaymentModal() {
    $('#quickPaymentModal').modal('show');
}
</script>
@endsection

