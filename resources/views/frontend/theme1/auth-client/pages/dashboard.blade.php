@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | Client Portal & Case Dashboard')

@section('page-css')
<style>
    .portal-hero {
        background: linear-gradient(135deg, #181d27 0%, #11151e 100%);
        border: 1px solid #28303f;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .portal-hero::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(254,204,86,0.12) 0%, rgba(0,0,0,0) 70%);
        pointer-events: none;
    }
    .portal-hero-badge {
        display: inline-block;
        background: rgba(254,204,86,0.15);
        color: #fecc56;
        border: 1px solid rgba(254,204,86,0.3);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    
    .stat-card-luxury {
        background: #161a23;
        border: 1px solid #28303f;
        border-radius: 12px;
        padding: 18px 20px;
        transition: all 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .stat-card-luxury:hover {
        border-color: rgba(254,204,86,0.4);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    }
    .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .stat-icon-wrap {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(254,204,86,0.1);
        color: #fecc56;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    .stat-label {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.8px;
        color: #94a3b8;
    }
    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: #f1f5f9;
        line-height: 1.2;
    }
    .stat-badge-verified {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10.5px;
        color: #22c55e;
        font-weight: 600;
        margin-top: 4px;
    }
    
    .progress-track-container {
        background: #161a23;
        border: 1px solid #28303f;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }
    .progress-track {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-top: 14px;
    }
    .progress-track::before {
        content: '';
        position: absolute;
        top: 14px;
        left: 30px;
        right: 30px;
        height: 3px;
        background: #262e3d;
        z-index: 1;
    }
    .progress-bar-fill {
        position: absolute;
        top: 14px;
        left: 30px;
        height: 3px;
        background: linear-gradient(90deg, #22c55e, #fecc56);
        z-index: 2;
        transition: width 0.5s ease;
    }
    .step-item {
        position: relative;
        z-index: 3;
        text-align: center;
        width: 18%;
    }
    .step-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #1c212c;
        border: 2px solid #374151;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 6px;
        font-size: 12px;
        font-weight: 700;
        color: #94a3b8;
        transition: all 0.3s;
    }
    .step-item.active .step-icon {
        background: #fecc56;
        border-color: #fecc56;
        color: #000;
        box-shadow: 0 0 14px rgba(254,204,86,0.6);
    }
    .step-item.completed .step-icon {
        background: #22c55e;
        border-color: #22c55e;
        color: #fff;
        box-shadow: 0 0 10px rgba(34,197,94,0.4);
    }
    .step-title {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        line-height: 1.2;
    }
    .step-item.active .step-title { color: #fecc56; font-weight: 700; }
    .step-item.completed .step-title { color: #22c55e; }

    .portal-card {
        background: #161a23;
        border: 1px solid #28303f;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .portal-card-header {
        background: #1f2533;
        border-bottom: 1px solid #2e3849;
        padding: 16px 20px;
        color: #fecc56;
        font-weight: 700;
        font-size: 14px;
    }
    .table-portal {
        width: 100%;
        color: #f1f5f9;
        margin-bottom: 0;
        border-collapse: collapse;
    }
    .table-portal thead th {
        background: #191f2c;
        color: #fecc56;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-top: none;
        border-bottom: 2px solid #2e3849;
        padding: 12px 16px;
    }
    .table-portal tbody tr {
        border-bottom: 1px solid #222936;
        transition: background 0.2s;
    }
    .table-portal tbody tr:hover {
        background: rgba(254,204,86,0.03);
    }
    .table-portal td {
        padding: 12px 16px;
        vertical-align: middle;
        font-size: 12.5px;
        color: #e2e8f0;
    }
    .btn-gold {
        background: linear-gradient(135deg, #fecc56, #f0a500);
        color: #000 !important;
        border: none;
        font-weight: 700;
        border-radius: 6px;
        padding: 6px 14px;
        font-size: 12px;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }
    .btn-gold:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(254,204,86,0.4);
    }
    .btn-portal-secondary {
        background: #1f2533;
        color: #e2e8f0;
        border: 1px solid #374151;
        font-weight: 600;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 12px;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .btn-portal-secondary:hover {
        background: #28303f;
        color: #fff;
    }

    .counsel-badge-card {
        background: #11151e;
        border: 1px solid #28303f;
        min-width: 260px;
        border-radius: 8px;
        color: #f1f5f9;
    }

    /* EXPLICIT LIGHT MODE RULES (ZERO CONFLICTS) */
    body.light-mode .stat-card-luxury, html.light-mode .stat-card-luxury {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06) !important;
    }
    body.light-mode .stat-card-luxury .stat-value, html.light-mode .stat-card-luxury .stat-value {
        color: #0f172a !important;
    }
    body.light-mode .stat-card-luxury .stat-label, html.light-mode .stat-card-luxury .stat-label {
        color: #64748b !important;
    }
    body.light-mode .stat-card-luxury .stat-icon-wrap, html.light-mode .stat-card-luxury .stat-icon-wrap {
        background: rgba(180,83,9,0.1) !important;
        color: #b45309 !important;
    }
    body.light-mode .stat-card-luxury .text-white, html.light-mode .stat-card-luxury .text-white {
        color: #0f172a !important;
    }
    body.light-mode .stat-card-luxury .text-muted, html.light-mode .stat-card-luxury .text-muted {
        color: #64748b !important;
    }
    body.light-mode .portal-card, html.light-mode .portal-card {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06) !important;
    }
    body.light-mode .portal-card-header, html.light-mode .portal-card-header {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
        color: #b45309 !important;
    }
    body.light-mode .portal-hero, html.light-mode .portal-hero {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        color: #0f172a !important;
    }
    body.light-mode .portal-hero h3, html.light-mode .portal-hero h3 {
        color: #0f172a !important;
    }
    body.light-mode .portal-hero-badge, html.light-mode .portal-hero-badge {
        background: rgba(180,83,9,0.1) !important;
        border-color: rgba(180,83,9,0.25) !important;
        color: #b45309 !important;
    }
    body.light-mode .counsel-badge-card, html.light-mode .counsel-badge-card {
        background: #f8fafc !important;
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
    }
    body.light-mode .counsel-badge-card strong, html.light-mode .counsel-badge-card strong {
        color: #0f172a !important;
    }
    body.light-mode .counsel-badge-card small, html.light-mode .counsel-badge-card small {
        color: #64748b !important;
    }
    body.light-mode .progress-track-container, html.light-mode .progress-track-container {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .progress-track-container h6, html.light-mode .progress-track-container h6 {
        color: #0f172a !important;
    }
    body.light-mode .table-portal, html.light-mode .table-portal {
        background: #ffffff !important;
        color: #0f172a !important;
    }
    body.light-mode .table-portal thead th, html.light-mode .table-portal thead th {
        background: #f8fafc !important;
        color: #b45309 !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .table-portal tbody tr, html.light-mode .table-portal tbody tr {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .table-portal td, html.light-mode .table-portal td {
        color: #334155 !important;
        border-color: #e2e8f0 !important;
        background: #ffffff !important;
    }
    body.light-mode .table-portal td strong, html.light-mode .table-portal td strong { color: #0f172a !important; }
    body.light-mode .table-portal td .text-white, html.light-mode .table-portal td .text-white { color: #0f172a !important; }
    body.light-mode .table-portal td .text-muted, html.light-mode .table-portal td .text-muted { color: #64748b !important; }
    body.light-mode .btn-portal-secondary, html.light-mode .btn-portal-secondary {
        background: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
        color: #334155 !important;
    }

    /* Modal light mode */
    body.light-mode #payNowModal .modal-content, html.light-mode #payNowModal .modal-content { background: #ffffff !important; color: #0f172a !important; border-color: #fecc56 !important; }
    body.light-mode #payNowModal .modal-header, html.light-mode #payNowModal .modal-header { background: #f8fafc !important; border-color: #e2e8f0 !important; }
    body.light-mode #payNowModal .bg-black, html.light-mode #payNowModal .bg-black { background: #f8fafc !important; color: #0f172a !important; border-color: #e2e8f0 !important; }
    body.light-mode #payNowModal .bg-dark, html.light-mode #payNowModal .bg-dark { background: #ffffff !important; color: #0f172a !important; }
    body.light-mode #payNowModal .form-control, html.light-mode #payNowModal .form-control { background: #ffffff !important; border-color: #cbd5e1 !important; color: #0f172a !important; }
    body.light-mode #payNowModal #paymentInfoBlock, html.light-mode #payNowModal #paymentInfoBlock { background: #f1f5f9 !important; color: #0f172a !important; border-color: #cbd5e1 !important; }
    body.light-mode #payNowModal #cryptoPaymentDetailsBox, html.light-mode #payNowModal #cryptoPaymentDetailsBox { background: #f8fafc !important; border-color: #fecc56 !important; }

    /* Mobile Zero Horizontal Scrolling & Responsive Overrides */
    @media (max-width: 768px) {
        .portal-hero { padding: 16px !important; }
        .portal-hero h3 { font-size: 20px !important; }
        .counsel-badge-card { min-width: 100% !important; margin-top: 10px !important; }
        .progress-track-container { padding: 14px 10px !important; overflow-x: auto !important; }
        .progress-track { min-width: 480px !important; }
        .table-responsive { overflow-x: auto !important; -webkit-overflow-scrolling: touch !important; width: 100% !important; }
        .table-portal td:last-child {
            display: flex !important;
            justify-content: flex-end !important;
            padding-right: 12px !important;
        }
    }
</style>
@endsection

@section('content')
@php
    use App\Helpers\CurrencyHelper;
    $clientCurr = CurrencyHelper::clientCurrency();

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

    $totalOutstandingUsd = 0;
    $activePenaltyInvoices = 0;
    $totalAccumulatedPenaltyUsd = 0;
    $primaryPenaltyInvoice = null;
    $firstUnpaid = null;

    if (!empty($invoices)) {
        foreach ($invoices as $inv) {
            $lateDetails = $inv->late_fee_details;
            $isPaid = strtolower($inv->status) === 'paid';
            if (!$isPaid && strtolower($inv->status) !== 'cancelled') {
                $totalOutstandingUsd += $lateDetails->total_billed;
                if (!$firstUnpaid) {
                    $firstUnpaid = $inv;
                }
                if ($lateDetails->is_active && $lateDetails->late_fee > 0) {
                    $activePenaltyInvoices++;
                    $totalAccumulatedPenaltyUsd += $lateDetails->late_fee;
                    if (!$primaryPenaltyInvoice) {
                        $primaryPenaltyInvoice = $inv;
                    }
                } elseif ($lateDetails->is_active && !$primaryPenaltyInvoice) {
                    $primaryPenaltyInvoice = $inv;
                }
            }
        }
    }
@endphp

<div class="container-fluid px-0">

    <!-- Top Welcome Hero Row -->
    <div class="portal-hero">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="portal-hero-badge"><i class="fas fa-shield-alt mr-1"></i> {{ __('Client Portal & Case Management') }}</span>
                <h3 class="font-weight-bold mb-1" style="font-size: 24px;">Welcome, {{ Auth::user()->name }}</h3>
                <p class="text-muted mb-0 small">
                    Account: <strong class="text-warning">{{ Auth::user()->email }}</strong> &bull; 
                    Status: <span class="text-success font-weight-bold"><i class="fas fa-check-circle"></i> Active & Protected</span>
                </p>
            </div>
            <div class="col-lg-5 text-lg-right mt-3 mt-lg-0">
                @php
                    $attorney = Auth::user()->assignedAttorney;
                    $attorneyName = $attorney ? $attorney->name : 'Gary Livingston, Senior CPA & Legal Counsel';
                    $attorneyEmail = $attorney ? $attorney->email : 'cpa.advisory@yourcpaexpert.com';
                @endphp
                <div class="d-inline-block text-left p-3 rounded counsel-badge-card">
                    <small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">Assigned Legal & CPA Counsel</small>
                    <strong class="d-block" style="font-size: 13px;">{{ $attorneyName }}</strong>
                    <div class="mt-2 d-flex gap-2" style="gap: 8px;">
                        <a href="{{ route('client.conversation.index') }}" class="btn-gold d-inline-flex align-items-center" style="font-size: 11px; padding: 4px 10px;">
                            <i class="fas fa-comment-dots mr-1"></i> Live Chat
                        </a>
                        <a href="{{ route('client.kyc.index') }}" class="btn-portal-secondary d-inline-flex align-items-center" style="font-size: 11px; padding: 4px 10px;">
                            <i class="fas fa-file-upload mr-1"></i> Upload Files
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- OVERDUE PENALTY / RETAINER DUE NOTICE BANNER (IFW EXACT REPLICA) -->
    @if($totalOutstandingUsd > 0)
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
                            {{ __('Total Accumulated Overdue Penalties:') }} <strong class="text-danger">${{ number_format($totalAccumulatedPenaltyUsd, 2) }} USD</strong> {{ __('across') }} {{ $activePenaltyInvoices }} {{ __('invoice(s).') }}
                        </p>
                    @else
                        <p class="mb-0 text-white font-weight-bold" style="font-size: 13.5px;">
                            {{ __('You have') }} <span class="text-warning font-weight-bold">{!! CurrencyHelper::format($totalOutstandingUsd) !!}</span> {{ __('pending legal retainer settlement.') }}
                        </p>
                        <p class="mb-0 text-muted small mt-1">
                            {{ __('Prompt settlement ensures uninterrupted forensic intelligence, court filings, and regulatory representation.') }}
                        </p>
                    @endif
                </div>
                <div class="text-md-right" style="min-width: 200px;">
                    <span class="small font-weight-bold text-uppercase d-block text-muted">{{ $hasActivePenalty ? __('Next Surcharge In:') : __('Recommended Window:') }}</span>
                    <div id="dashPenaltyCountdown" class="font-weight-bold text-danger mt-1" style="font-size: 1.35rem; letter-spacing: 1.5px; font-family: monospace; color: #ef4444 !important; text-shadow: 0 0 10px rgba(239,68,68,0.4);">
                        24h 00m 00s
                    </div>
                    <button type="button" class="btn btn-warning btn-sm font-weight-bold text-dark mt-2" onclick="openQuickPaymentDashboard()">
                        <i class="fas fa-credit-card mr-1"></i> {{ __('Settle Invoice Now') }}
                    </button>
                </div>
            </div>
        </div>
        <script>
        (function() {
            var remainingSec = {{ $primDetails && $primDetails->time_remaining_sec > 0 ? $primDetails->time_remaining_sec : 86400 }};
            function updateCountdown() {
                if (remainingSec <= 0) {
                    var el = document.getElementById('dashPenaltyCountdown');
                    if (el) el.innerHTML = "IMMEDIATE ATTENTION";
                    return;
                }
                var h = Math.floor(remainingSec / 3600);
                var m = Math.floor((remainingSec % 3600) / 60);
                var s = remainingSec % 60;
                var el = document.getElementById('dashPenaltyCountdown');
                if (el) {
                    el.innerHTML = (h < 10 ? '0' : '') + h + 'h ' + (m < 10 ? '0' : '') + m + 'm ' + (s < 10 ? '0' : '') + s + 's';
                }
                remainingSec--;
            }
            updateCountdown();
            setInterval(updateCountdown, 1000);
        })();
        </script>
    @endif

    <!-- Executive Stat Cards (Desktop Only) -->
    <div class="row mb-4 d-none d-md-flex">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card-luxury">
                <div class="stat-top">
                    <span class="stat-label">{{ __('Active Cases') }}</span>
                    <div class="stat-icon-wrap"><i class="fas fa-briefcase"></i></div>
                </div>
                <div>
                    <div class="stat-value">{{ $casesCount ?? 0 }}</div>
                    <span class="stat-badge-verified"><i class="fas fa-check"></i> {{ __('Under Representation') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card-luxury">
                <div class="stat-top">
                    <span class="stat-label">{{ __('Invoices & Retainers') }}</span>
                    <div class="stat-icon-wrap"><i class="fas fa-file-invoice-dollar"></i></div>
                </div>
                <div>
                    @php
                        $totInvDisplay = $invoicesTotalAmount ?? (!empty($invoices) ? $invoices->sum(fn($i) => $i->late_fee_details->total_billed) : 0);
                    @endphp
                    <div class="stat-value text-warning">{!! CurrencyHelper::format($totInvDisplay) !!}</div>
                    <small class="text-muted" style="font-size: 11px;">{{ $invoicesCount ?? 0 }} {{ __('Statements Logged') }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card-luxury">
                <div class="stat-top">
                    <span class="stat-label">{{ __('Document Vault') }}</span>
                    <div class="stat-icon-wrap"><i class="fas fa-folder-open"></i></div>
                </div>
                <div>
                    <div class="stat-value">{{ $documentsCount ?? 0 }}</div>
                    <span class="stat-badge-verified"><i class="fas fa-lock"></i> {{ __('256-Bit Encrypted') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card-luxury">
                <div class="stat-top">
                    <span class="stat-label">{{ __('Security Status') }}</span>
                    <div class="stat-icon-wrap"><i class="fas fa-shield-alt"></i></div>
                </div>
                <div>
                    @if(Auth::user()->pin_hash)
                        <div class="stat-value text-success" style="font-size: 1.2rem; padding-top: 6px;"><i class="fas fa-check-circle mr-1"></i> PIN ACTIVE</div>
                        <small class="text-muted" style="font-size: 11px;">4-Digit PIN Configured</small>
                    @else
                        <div class="stat-value text-warning" style="font-size: 1.2rem; padding-top: 6px;">PENDING</div>
                        <a href="{{ route('client.profile') }}" class="text-warning small font-weight-bold">Configure PIN &rarr;</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Active Case Progression Tracker -->
    @php
        $latestCase = !empty($cases) ? $cases->first() : null;
        $progressPct = 40;
        $currentStage = 2;
        if ($latestCase) {
            $progressPct = $latestCase->progress_percentage ?: 40;
            if ($progressPct >= 100) $currentStage = 5;
            elseif ($progressPct >= 75) $currentStage = 4;
            elseif ($progressPct >= 50) $currentStage = 3;
            elseif ($progressPct >= 25) $currentStage = 2;
            else $currentStage = 1;
        }
    @endphp
    <div class="progress-track-container">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="font-weight-bold mb-0" style="font-size: 13px;">
                <i class="fas fa-tasks text-warning mr-2"></i> {{ __('Active Case Lifecycle Progression') }}
                @if($latestCase)
                    <span class="text-muted font-weight-normal ml-2">({{ $latestCase->case_number }} - {{ $latestCase->title }})</span>
                @endif
            </h6>
            <span class="badge badge-warning text-dark font-weight-bold px-2 py-1">{{ $progressPct }}% {{ __('Completed') }}</span>
        </div>
        <div class="progress-track">
            <div class="progress-bar-fill" style="width: {{ $progressPct }}%;"></div>
            
            <div class="step-item {{ $currentStage > 1 ? 'completed' : ($currentStage == 1 ? 'active' : '') }}">
                <div class="step-icon">
                    @if($currentStage > 1) <i class="fas fa-check"></i> @else 1 @endif
                </div>
                <div class="step-title">{{ __('1. Case Intake & Retainer') }}</div>
            </div>

            <div class="step-item {{ $currentStage > 2 ? 'completed' : ($currentStage == 2 ? 'active' : '') }}">
                <div class="step-icon">
                    @if($currentStage > 2) <i class="fas fa-check"></i> @else 2 @endif
                </div>
                <div class="step-title">{{ __('2. Forensic Audit & Analysis') }}</div>
            </div>

            <div class="step-item {{ $currentStage > 3 ? 'completed' : ($currentStage == 3 ? 'active' : '') }}">
                <div class="step-icon">
                    @if($currentStage > 3) <i class="fas fa-check"></i> @else 3 @endif
                </div>
                <div class="step-title">{{ __('3. Legal & Regulatory Filings') }}</div>
            </div>

            <div class="step-item {{ $currentStage > 4 ? 'completed' : ($currentStage == 4 ? 'active' : '') }}">
                <div class="step-icon">
                    @if($currentStage > 4) <i class="fas fa-check"></i> @else 4 @endif
                </div>
                <div class="step-title">{{ __('4. Settlement Negotiations') }}</div>
            </div>

            <div class="step-item {{ $currentStage == 5 ? 'completed' : '' }}">
                <div class="step-icon">
                    @if($currentStage == 5) <i class="fas fa-check"></i> @else 5 @endif
                </div>
                <div class="step-title">{{ __('5. Final Resolution & Release') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Active Cases Table -->
        <div class="col-lg-7 mb-4">
            <div class="portal-card h-100">
                <div class="portal-card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-briefcase mr-2"></i> {{ __('My Active Legal & CPA Cases') }}</span>
                    <a href="{{ route('client.cases.index') }}" class="btn-portal-secondary" style="font-size: 11px;">{{ __('View All') }}</a>
                </div>
                <div class="table-responsive">
                    <table class="table-portal">
                        <thead>
                            <tr>
                                <th>{{ __('Case #') }}</th>
                                <th>{{ __('Subject / Title') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($cases) && $cases->count() > 0)
                                @foreach($cases->take(4) as $c)
                                    <tr>
                                        <td><strong class="text-warning">{{ $c->case_number }}</strong></td>
                                        <td>
                                            <div class="font-weight-bold">{{ \Illuminate\Support\Str::limit($c->title, 35) }}</div>
                                            <small class="text-muted">{{ $c->created_at ? $c->created_at->format('M d, Y') : '' }}</small>
                                        </td>
                                        <td>
                                            @if(strtolower($c->status) === 'pending')
                                                <span class="badge badge-warning text-dark font-weight-bold px-2 py-1" style="background: #fecc56; color: #000;">{{ __('Pending') }}</span>
                                            @elseif(strtolower($c->status) === 'active' || strtolower($c->status) === 'in_progress')
                                                <span class="badge badge-success px-2 py-1">{{ __('Active') }}</span>
                                            @elseif(strtolower($c->status) === 'closed' || strtolower($c->status) === 'resolved')
                                                <span class="badge badge-secondary px-2 py-1">{{ ucfirst($c->status) }}</span>
                                            @else
                                                <span class="badge badge-info px-2 py-1">{{ ucfirst($c->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('client.cases.details', $c->id) }}" class="btn-gold" style="font-size: 11px;">
                                                <i class="fas fa-eye mr-1"></i> {{ __('View') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-folder-open fa-2x mb-2 d-block text-secondary"></i>
                                        {{ __('No active cases on file.') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Invoices & Billing Table -->
        <div class="col-lg-5 mb-4">
            <div class="portal-card h-100">
                <div class="portal-card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-file-invoice mr-2"></i> {{ __('Invoices & Retainers') }}</span>
                    <a href="{{ route('client.invoices.index') }}" class="btn-portal-secondary" style="font-size: 11px;">{{ __('View All') }}</a>
                </div>
                <div class="table-responsive">
                    <table class="table-portal">
                        <thead>
                            <tr>
                                <th>{{ __('Invoice #') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($invoices) && $invoices->count() > 0)
                                @foreach($invoices->take(4) as $inv)
                                    @php
                                        $lateDetails = $inv->late_fee_details;
                                        $isPaid = strtolower($inv->status) === 'paid';
                                        $customPayInfo = $inv->payment_info ?: $defaultPaymentInfo;
                                        $prefAmount = CurrencyHelper::convert($lateDetails->total_billed, $clientCurr);
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $inv->invoice_number }}</strong>
                                            <small class="text-muted d-block">{{ $inv->due_date ? date('M d, Y', strtotime($inv->due_date)) : '' }}</small>
                                        </td>
                                        <td>
                                            {!! CurrencyHelper::format($lateDetails->total_billed) !!}
                                            @if($lateDetails->late_fee > 0 && !$isPaid)
                                                <small class="text-danger font-weight-bold d-block" style="font-size: 10px;">+${{ number_format($lateDetails->late_fee, 2) }} fee</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($isPaid)
                                                <span class="badge badge-success px-2 py-1">{{ __('Paid') }}</span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1">{{ __('Due') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$isPaid)
                                                <button type="button" class="btn-gold" style="font-size: 11px; padding: 4px 10px;" onclick="showPayModalDashboard({{ $inv->id }}, '{{ addslashes($inv->invoice_number) }}', {{ $lateDetails->total_billed }}, 'USD', {{ json_encode($customPayInfo) }}, '{{ $clientCurr }}', {{ $prefAmount }})">
                                                    {{ __('Pay') }}
                                                </button>
                                            @else
                                                <a href="{{ route('client.invoices.show', $inv->id) }}" class="btn-portal-secondary" style="font-size: 11px; padding: 4px 8px;">
                                                    {{ __('View') }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-receipt fa-2x mb-2 d-block text-secondary"></i>
                                        {{ __('No invoices pending.') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PAY NOW MODAL ON DASHBOARD (IFW REPLICA) -->
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
                            <div class="small text-muted" id="payPrefEquivalentDashboard" style="font-size: 12px;"></div>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-danger px-3 py-2 font-weight-bold" style="font-size: 12px;">{{ __('Action Required') }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-dark">
                    <h6 class="font-weight-bold mb-3 text-warning"><i class="fas fa-university mr-2"></i>{{ __('Official Wire & Escrow Instructions') }}</h6>
                    <div class="bg-black border border-secondary rounded p-3 mb-4 text-light font-monospace" id="paymentInfoBlock" style="white-space: pre-wrap; font-size: 12.5px; line-height: 1.7;"></div>
                    
                    <form method="POST" id="payNowSubmitFormDashboard" action="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="invoice_id" id="payInvoiceIdDashboard">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold text-light small">{{ __('Amount Paid (USD)') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="amount_paid" id="payAmountInputDashboard" class="form-control bg-black text-white border-secondary font-weight-bold text-warning" required placeholder="0.00">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold text-light small">{{ __('Payment Channel') }} <span class="text-danger">*</span></label>
                                <select name="payment_method" id="dashboardPaymentMethodSelect" class="form-control bg-black text-white border-secondary" required onchange="handlePaymentMethodChangeDashboard(this.value)">
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
                                <input type="text" name="payment_reference" class="form-control bg-black text-white border-secondary font-monospace" placeholder="Wire Ref # or TXID (Optional)">
                            </div>
                        </div>

                        <!-- DYNAMIC CRYPTO DETAILS & QR BOX -->
                        <div id="cryptoPaymentDetailsBoxDashboard" class="p-3 mb-3 rounded border border-warning" style="display: none; background: #12151e;">
                            <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 12px;">
                                <div class="mr-3 mb-2 text-center" style="min-width: 130px;">
                                    <img id="cryptoQrImgDashboard" src="" alt="Crypto QR" class="img-fluid rounded border border-secondary p-1 bg-white" style="width: 120px; height: 120px;">
                                    <div class="text-muted small mt-1 font-weight-bold" style="font-size: 10px;" id="cryptoNetworkLabelDashboard">TRC-20 Network</div>
                                </div>
                                <div class="flex-grow-1 mb-2">
                                    <div class="font-weight-bold text-warning mb-1" id="cryptoNameLabelDashboard">USDT TRC-20 Wallet Address</div>
                                    <p class="text-muted small mb-2">{{ __('Send only the exact asset on this network. Funds will be credited after 1 network confirmation.') }}</p>
                                    <div class="input-group">
                                        <input type="text" id="cryptoWalletInputDashboard" class="form-control bg-dark text-white border-secondary font-weight-bold font-monospace" style="font-size: 12px;" readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-warning text-dark font-weight-bold" onclick="copyCryptoAddressDashboard()"><i class="fas fa-copy mr-1"></i> <span id="copyCryptoBtnTextDashboard">{{ __('Copy') }}</span></button>
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
var clientCurrency = "{{ $clientCurr }}";

function showPayModalDashboard(invoiceId, ref, balanceDueUsd, currency, paymentInfo, prefCurrency, prefBalance) {
    document.getElementById('payInvoiceIdDashboard').value = invoiceId;
    document.getElementById('payInvoiceRef').textContent = ref;
    document.getElementById('payAmount').textContent = '$' + parseFloat(balanceDueUsd).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' USD';
    document.getElementById('payAmountInputDashboard').value = parseFloat(balanceDueUsd).toFixed(2);
    
    if (prefCurrency && prefCurrency !== 'USD' && prefBalance) {
        document.getElementById('payPrefEquivalentDashboard').textContent = '≈ ' + parseFloat(prefBalance).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ' + prefCurrency + ' (Exchange Rate Estimate)';
    } else {
        document.getElementById('payPrefEquivalentDashboard').textContent = '';
    }

    document.getElementById('paymentInfoBlock').textContent = paymentInfo || 'Please contact your assigned counsel for payment details.';
    
    document.getElementById('payNowSubmitFormDashboard').action = "/client/invoices/" + invoiceId + "/submit-proof";
    document.getElementById('dashboardPaymentMethodSelect').value = '';
    document.getElementById('cryptoPaymentDetailsBoxDashboard').style.display = 'none';
    
    $('#payNowModal').modal('show');
}

function handlePaymentMethodChangeDashboard(val) {
    var box = document.getElementById('cryptoPaymentDetailsBoxDashboard');
    var walletInp = document.getElementById('cryptoWalletInputDashboard');
    var qrImg = document.getElementById('cryptoQrImgDashboard');
    var nameLbl = document.getElementById('cryptoNameLabelDashboard');
    var netLbl = document.getElementById('cryptoNetworkLabelDashboard');
    
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

function copyCryptoAddressDashboard() {
    var copyText = document.getElementById('cryptoWalletInputDashboard');
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    document.getElementById('copyCryptoBtnTextDashboard').textContent = 'Copied!';
    setTimeout(function() {
        document.getElementById('copyCryptoBtnTextDashboard').textContent = 'Copy';
    }, 2500);
}

function openQuickPaymentDashboard() {
    @if($firstUnpaid)
        @php
            $fDetails = $firstUnpaid->late_fee_details;
            $fInfo = $firstUnpaid->payment_info ?: $defaultPaymentInfo;
            $fPref = CurrencyHelper::convert($fDetails->total_billed, $clientCurr);
        @endphp
        showPayModalDashboard(
            {{ $firstUnpaid->id }},
            '{{ addslashes($firstUnpaid->invoice_number) }}',
            {{ $fDetails->total_billed }},
            'USD',
            {!! json_encode($fInfo) !!},
            '{{ $clientCurr }}',
            {{ $fPref }}
        );
    @else
        showPayModalDashboard(
            0,
            'Direct Retainer / Settlement Wire',
            0.00,
            'USD',
            {!! json_encode($defaultPaymentInfo) !!},
            '{{ $clientCurr }}',
            0.00
        );
    @endif
}
</script>
@endsection
