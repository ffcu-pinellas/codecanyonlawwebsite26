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

.pay-btn { background: linear-gradient(135deg,#fecc56,#f0a500); color:#000 !important; border:none; font-weight:700; border-radius: 6px; padding: 6px 14px; transition:all .2s; box-shadow: 0 2px 8px rgba(254,204,86,0.3); font-size: 12px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; cursor: pointer; }
.pay-btn:hover { transform:translateY(-1px); box-shadow:0 4px 16px rgba(254,204,86,.5); color:#000 !important; }
.btn-portal-secondary { background: #262e3d; border: 1px solid #374151; color: #e2e8f0; font-weight: 600; border-radius: 6px; font-size: 12px; padding: 6px 12px; text-decoration: none; display: inline-flex; align-items: center; }
.btn-portal-secondary:hover { background: #333d4e; color: #fff; }

.status-badge-unpaid { background-color: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 700; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; display: inline-block; }
.status-badge-paid { background-color: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); font-weight: 700; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; display: inline-block; }
.status-badge-pending { background-color: rgba(245, 158, 11, 0.15); color: #fecc56; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: 700; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; display: inline-block; }
.status-badge-cancelled { background-color: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3); font-weight: 700; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; display: inline-block; }

/* LIGHT MODE OVERRIDES (DESKTOP + MOBILE) */
body.light-mode .billing-stat-card, html.light-mode .billing-stat-card { background: #ffffff !important; border-color: #e2e8f0 !important; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
body.light-mode .billing-stat-card .stat-num, html.light-mode .billing-stat-card .stat-num { color: #0f172a !important; }
body.light-mode .portal-card, html.light-mode .portal-card { background: #ffffff !important; border-color: #e2e8f0 !important; }
body.light-mode .portal-card-header, html.light-mode .portal-card-header { background: #f8fafc !important; border-color: #e2e8f0 !important; color: #b45309 !important; }
body.light-mode .table-portal-wrap, html.light-mode .table-portal-wrap { background: #ffffff !important; border-color: #e2e8f0 !important; }
body.light-mode .table-portal thead th, html.light-mode .table-portal thead th { background: #f8fafc !important; color: #b45309 !important; border-color: #e2e8f0 !important; }
body.light-mode .table-portal tbody tr, html.light-mode .table-portal tbody tr { background: #ffffff !important; border-color: #e2e8f0 !important; }
body.light-mode .table-portal td, html.light-mode .table-portal td { border-color: #e2e8f0 !important; color: #334155 !important; background: #ffffff !important; }
body.light-mode .table-portal td strong, html.light-mode .table-portal td strong { color: #0f172a !important; }
body.light-mode .table-portal td .text-white, html.light-mode .table-portal td .text-white { color: #0f172a !important; }
body.light-mode .btn-portal-secondary, html.light-mode .btn-portal-secondary { background: #f1f5f9 !important; border-color: #cbd5e1 !important; color: #334155 !important; }

/* MODAL LIGHT MODE OVERRIDES */
body.light-mode #payNowModal .modal-content, html.light-mode #payNowModal .modal-content { background: #ffffff !important; color: #0f172a !important; border-color: #fecc56 !important; }
body.light-mode #payNowModal .modal-header, html.light-mode #payNowModal .modal-header { background: #f8fafc !important; border-color: #e2e8f0 !important; }
body.light-mode #payNowModal .bg-black, html.light-mode #payNowModal .bg-black { background: #f8fafc !important; color: #0f172a !important; border-color: #e2e8f0 !important; }
body.light-mode #payNowModal .bg-dark, html.light-mode #payNowModal .bg-dark { background: #ffffff !important; color: #0f172a !important; }
body.light-mode #payNowModal .form-control, html.light-mode #payNowModal .form-control { background: #ffffff !important; border-color: #cbd5e1 !important; color: #0f172a !important; }
body.light-mode #payNowModal #paymentInfoBlock, html.light-mode #payNowModal #paymentInfoBlock { background: #f1f5f9 !important; color: #0f172a !important; border-color: #cbd5e1 !important; }
body.light-mode #payNowModal #cryptoPaymentDetailsBox, html.light-mode #payNowModal #cryptoPaymentDetailsBox { background: #f8fafc !important; border-color: #fecc56 !important; }
body.light-mode #payNowModal .text-light, html.light-mode #payNowModal .text-light { color: #334155 !important; }

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
        background: #161a23 !important;
    }
    body.light-mode .table-portal tbody tr, html.light-mode .table-portal tbody tr {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
    }
    .table-portal td {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 8px 0 !important;
        border-top: none !important;
        border-bottom: 1px solid #232a38 !important;
        text-align: right !important;
        background: transparent !important;
    }
    body.light-mode .table-portal td, html.light-mode .table-portal td {
        border-bottom-color: #f1f5f9 !important;
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
    body.light-mode .table-portal td[data-label]::before, html.light-mode .table-portal td[data-label]::before {
        color: #64748b !important;
    }
}
</style>
@endsection

@section('content')
@php
    $settPath = storage_path('settings.json');
    $paySettings = [];
    if (file_exists($settPath)) {
        $allS = json_decode(file_get_contents($settPath), true);
        $paySettings = $allS['payment'] ?? [];
    }
    $globalBankName = $paySettings['bank_name'] ?? 'JPMorgan Chase Bank, N.A.';
    $globalBeneficiary = $paySettings['beneficiary'] ?? (config('app.name', 'Your CPA Expert') . ' Trust & Escrow LLC');
    $globalAccount = $paySettings['account_number'] ?? '987654321098';
    $globalRouting = $paySettings['routing_number'] ?? '021000021';
    $globalSwift = $paySettings['swift_code'] ?? 'CHASUS33';
    $globalWireMemo = $paySettings['wire_instructions'] ?? 'Please include invoice number in wire memo.';
    $globalUsdt = $paySettings['crypto_usdt_address'] ?? 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE';
    $globalBtc = $paySettings['crypto_btc_address'] ?? 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh';

    $defaultPaymentInfo = "Beneficiary Name: " . $globalBeneficiary . "\n"
        . "Bank Name: " . $globalBankName . "\n"
        . "Account Number: " . $globalAccount . "\n"
        . "Routing Number (ABA): " . $globalRouting . "\n"
        . "SWIFT/BIC: " . $globalSwift . "\n"
        . "Wire Memo: " . $globalWireMemo . "\n\n"
        . "USDT (TRC-20): " . $globalUsdt . "\n"
        . "Bitcoin (BTC): " . $globalBtc;

    $totalInvoiced = 0;
    $totalPaid = 0;
    $totalOutstanding = 0;
    $activePenaltyInvoices = 0;
    $totalAccumulatedPenalty = 0;
    $primaryPenaltyInvoice = null;
    $firstUnpaid = null;

    foreach ($invoices as $inv) {
        $lateDetails = $inv->late_fee_details;
        $totalInvoiced += $lateDetails->total_billed;
        $isPaid = strtolower($inv->status) === 'paid';
        if ($isPaid) {
            $totalPaid += $lateDetails->total_billed;
        } else {
            $totalOutstanding += $lateDetails->total_billed;
            if (!$firstUnpaid) {
                $firstUnpaid = $inv;
            }
            if ($lateDetails->is_active && $lateDetails->late_fee > 0) {
                $activePenaltyInvoices++;
                $totalAccumulatedPenalty += $lateDetails->late_fee;
                if (!$primaryPenaltyInvoice) {
                    $primaryPenaltyInvoice = $inv;
                }
            } elseif ($lateDetails->is_active && !$primaryPenaltyInvoice) {
                $primaryPenaltyInvoice = $inv;
            }
        }
    }
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
        <button type="button" class="btn btn-sm btn-warning text-dark font-weight-bold px-3" onclick="openQuickPayment()">
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

<!-- DUE COUNTDOWN / OVERDUE PENALTY BANNER (IFW EXACT REPLICA) -->
@if($totalOutstanding > 0)
    @php
        $hasActivePenalty = ($primaryPenaltyInvoice && $primaryPenaltyInvoice->late_fee_details->is_active);
        $primDetails = $primaryPenaltyInvoice ? $primaryPenaltyInvoice->late_fee_details : null;
    @endphp
    <div class="portal-card mb-4 p-4 shadow-sm" style="border-left: 5px solid {{ $hasActivePenalty ? '#ef4444' : '#fecc56' }} !important; background: #1c1811; border-color: #4a3818;">
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:14px;">
            <div>
                <h5 class="font-weight-bold mb-1 {{ $hasActivePenalty ? 'text-danger' : 'text-warning' }}">
                    <i class="fas {{ $hasActivePenalty ? 'fa-exclamation-triangle' : 'fa-hourglass-half' }} mr-2"></i> 
                    {{ $hasActivePenalty ? __('Overdue Penalty / Penalty Interest Active') : __('Retainer & Settlement Due Notice') }}
                </h5>
                @if($hasActivePenalty)
                    <p class="mb-0 text-white font-weight-bold" style="font-size: 13.5px;">
                        {{ __('An automated late fee penalty of') }} 
                        @if($primDetails->is_percentage)
                            <span class="text-danger font-weight-bold">{{ number_format($primDetails->fee_amount, 2) }}%</span> {{ __('of invoice balance') }}
                        @else
                            <span class="text-danger font-weight-bold">${{ number_format($primDetails->fee_amount, 2) }}</span>
                        @endif
                        {{ __('is accumulating') }} <span class="badge badge-danger text-uppercase px-2">{{ $primDetails->fee_type }}</span>.
                    </p>
                    <p class="mb-0 text-muted small mt-1">
                        {{ __('Total Accumulated Overdue Penalties:') }} <strong class="text-danger">${{ number_format($totalAccumulatedPenalty, 2) }} USD</strong> {{ __('across') }} {{ $activePenaltyInvoices }} {{ __('invoice(s).') }}
                    </p>
                @else
                    <p class="mb-0 text-white font-weight-bold" style="font-size: 13.5px;">
                        {{ __('You have') }} <span class="text-warning font-weight-bold">${{ number_format($totalOutstanding, 2) }}</span> {{ __('pending legal retainer settlement across') }} {{ $invoices->whereNotIn('status', ['paid', 'cancelled'])->count() }} {{ __('invoice(s).') }}
                    </p>
                    <p class="mb-0 text-muted small mt-1">
                        {{ __('Prompt settlement ensures uninterrupted forensic intelligence, attorney court filings, and regulatory representation.') }}
                    </p>
                @endif
            </div>
            <div class="text-md-right" style="min-width: 220px;">
                <span class="small font-weight-bold text-uppercase d-block text-muted">{{ $hasActivePenalty ? __('Next Surcharge In:') : __('Recommended Settlement Window:') }}</span>
                <div id="penaltyCountdownInvoicesPage" class="font-weight-bold text-danger mt-1" style="font-size: 1.35rem; letter-spacing: 1.5px; font-family: monospace; color: #ef4444 !important; text-shadow: 0 0 10px rgba(239,68,68,0.4);">
                    24h 00m 00s
                </div>
                <button type="button" class="btn btn-warning btn-sm font-weight-bold text-dark mt-2" onclick="openQuickPayment()">
                    <i class="fas fa-credit-card mr-1"></i> {{ __('Settle Balance Now') }}
                </button>
            </div>
        </div>
    </div>
    <script>
    (function() {
        var remainingSec = {{ $primDetails && $primDetails->time_remaining_sec > 0 ? $primDetails->time_remaining_sec : 86400 }};
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

<!-- INVOICES TABLE (IFW REPLICA) -->
<div class="portal-card mb-4 shadow-sm">
    <div class="portal-card-header py-3 px-4 d-flex justify-content-between align-items-center flex-wrap" style="gap: 8px;">
        <h5 class="mb-0 font-weight-bold text-warning"><i class="fas fa-file-invoice mr-2"></i>{{ __('Official Invoices & Retainers') }}</h5>
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
                            <th>{{ __('Invoice #') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Amount & Due') }}</th>
                            <th>{{ __('Due Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $inv)
                            @php
                                $lateDetails = $inv->late_fee_details;
                                $isPaid = strtolower($inv->status) === 'paid';
                                $isPending = strtolower($inv->status) === 'pending';
                                $isCancelled = strtolower($inv->status) === 'cancelled';
                                $customPayInfo = $inv->payment_info ?: $defaultPaymentInfo;
                            @endphp
                            <tr>
                                <td data-label="{{ __('Invoice #') }}">
                                    <strong class="text-white d-block">{{ $inv->invoice_number }}</strong>
                                    <small class="text-muted">{{ $inv->created_at ? $inv->created_at->format('M d, Y') : '-' }}</small>
                                </td>
                                <td data-label="{{ __('Description') }}">
                                    <span class="text-light font-weight-bold">{{ $inv->description ?: ($inv->clientCase ? ('Representation for Case #' . $inv->clientCase->case_number) : __('Professional Legal & CPA Retainer Statement')) }}</span>
                                    @if($lateDetails->late_fee > 0 && !$isPaid)
                                        <br><small class="text-danger font-weight-bold"><i class="fas fa-exclamation-circle mr-1"></i>{{ __('Late fee: +$') }}{{ number_format($lateDetails->late_fee, 2) }}</small>
                                    @endif
                                </td>
                                <td data-label="{{ __('Amount & Due') }}">
                                    <strong class="text-warning" style="font-size: 1.05rem;">${{ number_format($lateDetails->total_billed, 2) }}</strong>
                                    @if($lateDetails->late_fee > 0 && !$isPaid)
                                        <br><small class="text-muted" style="font-size: 10.5px;">(Base: ${{ number_format($lateDetails->base_amount, 2) }} + Fee: ${{ number_format($lateDetails->late_fee, 2) }})</small>
                                    @endif
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
                                        <a href="{{ route('client.invoices.show', $inv->id) }}" class="btn btn-sm btn-portal-secondary" title="{{ __('View & Print Invoice') }}">
                                            <i class="fas fa-eye mr-1"></i> {{ __('View') }}
                                        </a>
                                        @if(!$isPaid)
                                            <button type="button" class="pay-btn" onclick="showPayModal({{ $inv->id }}, '{{ addslashes($inv->invoice_number) }}', {{ $lateDetails->total_billed }}, 'USD', {{ json_encode($customPayInfo) }})">
                                                <i class="fas fa-credit-card mr-1"></i> {{ __('Pay Now') }}
                                            </button>
                                        @endif
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

<!-- PAY NOW MODAL (IFW EXACT REPLICA) -->
<div class="modal fade" id="payNowModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content bg-dark text-white border-warning" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header border-secondary py-3 px-4" style="background: #1f2533;">
                <h5 class="modal-title text-warning font-weight-bold">
                    <i class="fas fa-lock mr-2"></i>{{ __('Secure Payment & Escrow Settlement') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0">
                <div class="bg-black text-white p-4 border-bottom border-secondary">
                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
                        <div>
                            <div class="small text-muted font-weight-bold text-uppercase">{{ __('Invoice Reference:') }} <span id="payInvoiceRef" class="text-white"></span></div>
                            <div class="font-weight-bold text-warning" style="font-size: 1.75rem;" id="payAmount"></div>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-danger px-3 py-2 font-weight-bold" style="font-size: 12px;">{{ __('Action Required') }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-dark">
                    <h6 class="font-weight-bold mb-3 text-warning"><i class="fas fa-university mr-2"></i>{{ __('Official Wire & Escrow Instructions') }}</h6>
                    <div class="bg-black border border-secondary rounded p-3 mb-4 text-light font-monospace" id="paymentInfoBlock" style="white-space: pre-wrap; font-size: 12.5px; line-height: 1.7;"></div>
                    
                    <form method="POST" id="payNowSubmitForm" action="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="invoice_id" id="payInvoiceId">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold text-light small">{{ __('Amount Paid (USD)') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="amount_paid" id="payAmountInput" class="form-control bg-black text-white border-secondary font-weight-bold text-warning" required placeholder="0.00">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold text-light small">{{ __('Payment Channel') }} <span class="text-danger">*</span></label>
                                <select name="payment_method" id="dashboardPaymentMethodSelect" class="form-control bg-black text-white border-secondary" required onchange="handlePaymentMethodChange(this.value)">
                                    <option value="">-- {{ __('Choose Method') }} --</option>
                                    <option value="bank_transfer">{{ __('International Bank Wire / SWIFT') }}</option>
                                    <option value="crypto_usdt_trc20">{{ __('Tether USDT (TRC-20 Tron)') }}</option>
                                    <option value="crypto_usdt_erc20">{{ __('Tether USDT (ERC-20 Ethereum)') }}</option>
                                    <option value="crypto_btc">{{ __('Bitcoin (BTC Mainnet)') }}</option>
                                    <option value="direct_deposit">{{ __('Direct ACH / Escrow Deposit') }}</option>
                                    <option value="check_deposit">{{ __('Check Deposit') }}</option>
                                    <option value="other">{{ __('Other / Alternative Channel') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold text-light small">{{ __('Transaction Hash / Ref #') }}</label>
                                <input type="text" name="payment_reference" id="dashboardRefNumberInput" class="form-control bg-black text-white border-secondary font-monospace" placeholder="Wire Ref # or TXID (Optional)">
                            </div>
                        </div>

                        <!-- DYNAMIC CRYPTO DETAILS & QR BOX (IFW REPLICA) -->
                        <div id="cryptoPaymentDetailsBox" class="p-3 mb-3 rounded border border-warning" style="display: none; background: #12151e;">
                            <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 12px;">
                                <div class="mr-3 mb-2 text-center" style="min-width: 130px;">
                                    <img id="cryptoQrImg" src="" alt="Crypto QR" class="img-fluid rounded border border-secondary p-1 bg-white" style="width: 120px; height: 120px;">
                                    <div class="text-muted small mt-1 font-weight-bold" style="font-size: 10px;" id="cryptoNetworkLabel">TRC-20 Network</div>
                                </div>
                                <div class="flex-grow-1 mb-2">
                                    <div class="font-weight-bold text-warning mb-1" id="cryptoNameLabel">USDT TRC-20 Wallet Address</div>
                                    <p class="text-muted small mb-2">{{ __('Send only the exact asset on this network. Funds will be credited after 1 network confirmation.') }}</p>
                                    <div class="input-group">
                                        <input type="text" id="cryptoWalletInput" class="form-control bg-dark text-white border-secondary font-weight-bold font-monospace" style="font-size: 12px;" readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-warning text-dark font-weight-bold" onclick="copyCryptoAddress()"><i class="fas fa-copy mr-1"></i> <span id="copyCryptoBtnText">{{ __('Copy') }}</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FILE UPLOAD FOR RECEIPT SLIP -->
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-light small">{{ __('Upload Bank Receipt / Deposit Slip / TX Screenshot') }} <span class="text-danger">*</span></label>
                            <input type="file" name="payment_slip" class="form-control-file text-white" required>
                            <small class="text-muted d-block mt-1">{{ __('Supported formats: PDF, PNG, JPG, JPEG (Max 10MB)') }}</small>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-light small">{{ __('Additional Remarks (Optional)') }}</label>
                            <textarea name="payment_notes" rows="2" class="form-control bg-black text-white border-secondary" placeholder="Remitting bank name, branch, or date details..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-warning btn-block font-weight-bold text-dark py-2" style="font-size: 14px;">
                            <i class="fas fa-shield-alt mr-1"></i> {{ __('Submit Official Payment Proof for Verification') }}
                        </button>
                    </form>
                </div>
            </div>
            <div class="modal-footer border-secondary py-2 px-4" style="background: #1f2533;">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
var globalUsdtAddress = "{{ $globalUsdt }}";
var globalBtcAddress = "{{ $globalBtc }}";

function showPayModal(invoiceId, ref, balanceDue, currency, paymentInfo) {
    document.getElementById('payInvoiceId').value = invoiceId;
    document.getElementById('payInvoiceRef').textContent = ref;
    document.getElementById('payAmount').textContent = '$' + parseFloat(balanceDue).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('payAmountInput').value = parseFloat(balanceDue).toFixed(2);
    document.getElementById('paymentInfoBlock').textContent = paymentInfo || 'Please contact your assigned counsel for payment details.';
    
    document.getElementById('payNowSubmitForm').action = "/client/invoices/" + invoiceId + "/submit-proof";
    document.getElementById('dashboardPaymentMethodSelect').value = '';
    document.getElementById('cryptoPaymentDetailsBox').style.display = 'none';
    
    $('#payNowModal').modal('show');
}

function handlePaymentMethodChange(val) {
    var box = document.getElementById('cryptoPaymentDetailsBox');
    var walletInp = document.getElementById('cryptoWalletInput');
    var qrImg = document.getElementById('cryptoQrImg');
    var nameLbl = document.getElementById('cryptoNameLabel');
    var netLbl = document.getElementById('cryptoNetworkLabel');
    
    if (val === 'crypto_usdt_trc20' || val === 'crypto_usdt_erc20') {
        box.style.display = 'block';
        walletInp.value = globalUsdtAddress;
        qrImg.src = 'https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=' + encodeURIComponent(globalUsdtAddress);
        nameLbl.textContent = (val === 'crypto_usdt_trc20') ? 'Tether USDT (TRC-20 Tron) Wallet' : 'Tether USDT (ERC-20) Wallet';
        netLbl.textContent = (val === 'crypto_usdt_trc20') ? 'TRC-20 Network' : 'ERC-20 Network';
    } else if (val === 'crypto_btc') {
        box.style.display = 'block';
        walletInp.value = globalBtcAddress;
        qrImg.src = 'https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=' + encodeURIComponent(globalBtcAddress);
        nameLbl.textContent = 'Bitcoin (BTC Mainnet) Wallet';
        netLbl.textContent = 'BTC Network';
    } else {
        box.style.display = 'none';
    }
}

function copyCryptoAddress() {
    var copyText = document.getElementById('cryptoWalletInput');
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    document.getElementById('copyCryptoBtnText').textContent = 'Copied!';
    setTimeout(function() {
        document.getElementById('copyCryptoBtnText').textContent = 'Copy';
    }, 2500);
}

function openQuickPayment() {
    @if($firstUnpaid)
        @php
            $fDetails = $firstUnpaid->late_fee_details;
            $fInfo = $firstUnpaid->payment_info ?: $defaultPaymentInfo;
        @endphp
        showPayModal(
            {{ $firstUnpaid->id }},
            '{{ addslashes($firstUnpaid->invoice_number) }}',
            {{ $fDetails->total_billed }},
            'USD',
            {!! json_encode($fInfo) !!}
        );
    @else
        showPayModal(
            0,
            'Direct Retainer / Settlement Wire',
            0.00,
            'USD',
            {!! json_encode($defaultPaymentInfo) !!}
        );
    @endif
}
</script>
@endsection
