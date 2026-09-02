@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    .invoice-container {
        background: #161a23;
        border-radius: 12px;
        padding: 35px;
        border: 1px solid #28303f;
        box-shadow: 0 4px 16px rgba(0,0,0,0.3);
        margin-bottom: 30px;
        position: relative;
        color: #f1f5f9;
    }
    .invoice-header {
        border-bottom: 2px solid #28303f;
        padding-bottom: 25px;
        margin-bottom: 25px;
    }
    .status-badge-unpaid { background-color: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 700; font-size: 0.8rem; padding: 5px 14px; border-radius: 6px; text-transform: uppercase; }
    .status-badge-pending { background-color: rgba(245, 158, 11, 0.15); color: #fecc56; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: 700; font-size: 0.8rem; padding: 5px 14px; border-radius: 6px; text-transform: uppercase; }
    .status-badge-paid { background-color: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); font-weight: 700; font-size: 0.8rem; padding: 5px 14px; border-radius: 6px; text-transform: uppercase; }
    .status-badge-cancelled { background-color: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3); font-weight: 700; font-size: 0.8rem; padding: 5px 14px; border-radius: 6px; text-transform: uppercase; }
    
    .company-logo-text {
        font-size: 1.5rem;
        font-weight: 800;
        color: #fecc56;
        letter-spacing: 0.5px;
    }
    .btn-gold {
        background: linear-gradient(135deg, #fecc56, #f0a500);
        color: #000 !important;
        border: none;
        font-weight: 700;
        border-radius: 6px;
        padding: 8px 18px;
        font-size: 13px;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(254,204,86,0.25);
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        gap: 6px;
    }
    .btn-gold:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(254,204,86,0.45);
    }
    .invoice-container .table {
        color: #f1f5f9;
        margin-bottom: 0;
    }
    .invoice-container .table thead th {
        background: #1f2533;
        color: #fecc56;
        border-color: #28303f;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
    }
    .invoice-container .table td {
        border-color: #28303f;
    }
    .portal-card {
        background: #161a23;
        border: 1px solid #28303f;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        margin-bottom: 24px;
        overflow: hidden;
    }

    /* Interactive Copy Box */
    .copy-data-box {
        background: #11151e;
        border: 1px solid #28303f;
        border-radius: 8px;
        padding: 12px 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        transition: all 0.15s;
    }
    .copy-data-box:hover {
        border-color: #fecc56;
        background: #141926;
    }
    .copy-trigger-btn {
        background: #1f2636;
        border: 1px solid #334155;
        color: #fecc56;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .copy-trigger-btn:hover {
        background: #fecc56;
        color: #000;
    }
    .copy-trigger-btn.copied {
        background: #22c55e !important;
        border-color: #22c55e !important;
        color: #ffffff !important;
    }

    /* Mobile Camera Dropzone */
    .camera-upload-dropzone {
        border: 2px dashed #334155;
        border-radius: 10px;
        padding: 24px 20px;
        text-align: center;
        background: #11151e;
        transition: all 0.2s;
        cursor: pointer;
    }
    .camera-upload-dropzone:hover {
        border-color: #fecc56;
        background: rgba(254, 204, 86, 0.04);
    }
    .proof-preview-card {
        background: #141926;
        border: 1px solid #22c55e;
        border-radius: 8px;
        padding: 14px 16px;
        display: none;
        align-items: center;
        justify-content: space-between;
        margin-top: 12px;
    }

    /* Light Mode */
    body.light-mode .invoice-container, html.light-mode .invoice-container {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        color: #0f172a !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06) !important;
    }
    body.light-mode .invoice-header, html.light-mode .invoice-header {
        border-color: #e2e8f0 !important;
    }
    body.light-mode .invoice-container .table thead th {
        background: #f8fafc !important;
        color: #b45309 !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .invoice-container .table td {
        border-color: #e2e8f0 !important;
        color: #334155 !important;
    }
    body.light-mode .portal-card, html.light-mode .portal-card {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
    }
    
    @media print {
        body * { visibility: hidden; }
        .invoice-container, .invoice-container * { visibility: visible; }
        .invoice-container { position: absolute; left: 0; top: 0; width: 100%; padding: 0; background: #ffffff !important; color: #000000 !important; }
        .d-print-none, header, footer, .ifw-client-sidebar { display: none !important; }
    }
</style>
@endsection

@section('content')
@php
    $invAmount = $invoice->total_amount ?: $invoice->amount;
    $isPaid = strtolower($invoice->status) === 'paid';
    $isPending = strtolower($invoice->status) === 'pending';
@endphp

<div class="container-fluid px-0">
    <!-- Back, Print & Pay Action Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap d-print-none" style="gap:10px;">
        <a href="{{ route('client.invoices.index') }}" class="btn btn-sm btn-outline-secondary text-light font-weight-bold px-3">
            <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Invoices') }}
        </a>
        <div class="d-flex flex-wrap" style="gap: 8px;">
            <button class="btn btn-outline-secondary btn-sm px-3 text-light font-weight-bold" onclick="window.print()">
                <i class="fas fa-print mr-1"></i> {{ __('Print / PDF') }}
            </button>
            @if(!$isPaid)
                <a href="#payment-section" class="btn btn-gold btn-sm px-4">
                    <i class="fas fa-credit-card mr-1"></i> {{ __('Pay Balance Due ($' . number_format($invAmount, 2) . ')') }}
                </a>
            @else
                <a href="{{ route('client.invoices.receipt', $invoice->id) }}" class="btn btn-success btn-sm px-3 font-weight-bold">
                    <i class="fas fa-receipt mr-1"></i> {{ __('Official Receipt') }}
                </a>
                <span class="badge badge-success font-weight-bold px-3 py-2" style="font-size:13px;">
                    <i class="fas fa-check-circle mr-1"></i> {{ __('Paid in Full ($0.00 Due)') }}
                </span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 font-weight-bold" style="background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3);">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="invoice-container">
        <!-- Header Info -->
        <div class="invoice-header">
            <div class="row align-items-center">
                <div class="col-md-7 mb-3 mb-md-0">
                    <span class="company-logo-text d-block">{{ $companyName }}</span>
                    <small class="text-muted d-block">{{ __('Licensed CPA & Legal Counsel Practice') }}</small>
                    <p class="text-muted small mt-2 mb-0">
                        {{ $companyAddress }}<br>
                        {{ __('Phone:') }} {{ $companyPhone }} | {{ __('Email:') }} {{ $companyEmail }}
                    </p>
                </div>
                <div class="col-md-5 text-md-right">
                    <h3 class="font-weight-bold mb-1 text-white">{{ __('INVOICE') }}</h3>
                    <h5 class="text-warning font-weight-bold mb-2">#{{ $invoice->invoice_number }}</h5>
                    <div>
                        @if($invoice->status == 'paid')
                            <span class="status-badge-paid"><i class="fas fa-check-circle mr-1"></i> {{ __('PAID') }}</span>
                        @elseif($invoice->status == 'pending')
                            <span class="status-badge-pending"><i class="fas fa-clock mr-1"></i> {{ __('PENDING CLEARANCE') }}</span>
                        @elseif($invoice->status == 'cancelled')
                            <span class="status-badge-cancelled">{{ __('CANCELLED') }}</span>
                        @else
                            <span class="status-badge-unpaid"><i class="fas fa-exclamation-circle mr-1"></i> {{ __('UNPAID') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Bill To & Meta -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <span class="text-muted text-uppercase small font-weight-bold d-block mb-1">{{ __('Billed To:') }}</span>
                <h6 class="font-weight-bold text-white mb-1">{{ Auth::user()->name }}</h6>
                <p class="text-muted small mb-0">
                    {{ Auth::user()->email }}<br>
                    @if(Auth::user()->phone) {{ Auth::user()->phone }}<br> @endif
                    @if(Auth::user()->address) {{ Auth::user()->address }}<br> @endif
                    <span class="text-warning font-weight-bold">{{ __('Client Reference: #CLI-') . sprintf('%05d', Auth::id()) }}</span>
                </p>
            </div>
            <div class="col-md-6 text-md-right">
                <div class="mb-1">
                    <span class="text-muted small">{{ __('Invoice Date:') }}</span>
                    <strong class="text-white ml-2">{{ $invoice->created_at->format('M d, Y') }}</strong>
                </div>
                <div class="mb-1">
                    <span class="text-muted small">{{ __('Payment Due:') }}</span>
                    <strong class="text-warning ml-2">{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') : __('Upon Receipt') }}</strong>
                </div>
                @if($invoice->clientCase)
                    <div class="mb-1">
                        <span class="text-muted small">{{ __('Associated Matter / Case:') }}</span>
                        <strong class="text-white ml-2">#{{ $invoice->clientCase->case_number }} - {{ $invoice->clientCase->title }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <div class="table-responsive mb-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Service / Description') }}</th>
                        <th class="text-center" style="width: 100px;">{{ __('Qty / Hrs') }}</th>
                        <th class="text-right" style="width: 140px;">{{ __('Rate') }}</th>
                        <th class="text-right" style="width: 140px;">{{ __('Amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong class="text-white">{{ $invoice->title ?: __('Professional Legal & CPA Representation Services') }}</strong>
                            @if($invoice->description)
                                <p class="text-muted small mb-0 mt-1">{{ $invoice->description }}</p>
                            @endif
                        </td>
                        <td class="text-center font-weight-bold">1</td>
                        <td class="text-right font-weight-bold">${{ number_format($invAmount, 2) }}</td>
                        <td class="text-right font-weight-bold text-warning">${{ number_format($invAmount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary Totals -->
        <div class="row justify-content-end">
            <div class="col-md-5">
                <div class="p-3 rounded" style="background: #11151e; border: 1px solid #28303f;">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">{{ __('Subtotal:') }}</span>
                        <strong class="text-white">${{ number_format($invAmount, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">{{ __('Retainer Trust Offset:') }}</span>
                        <strong class="text-white">$0.00</strong>
                    </div>
                    <hr class="border-secondary my-2">
                    <div class="d-flex justify-content-between font-weight-bold">
                        <span class="text-white">{{ __('Total Due:') }}</span>
                        <span class="text-warning h5 mb-0 font-weight-bold">${{ number_format($invAmount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer terms -->
        <div class="mt-5 pt-4 border-top text-center text-muted small" style="border-color: #28303f !important;">
            <p class="mb-1 text-white"><strong>{{ __('Thank you for your business.') }}</strong></p>
            <p class="mb-0 text-muted">{{ __('For inquiries regarding retainers or statements, reach out to our accounts department at') }} <span class="text-warning">{{ $companyEmail }}</span>.</p>
        </div>
    </div>

    <!-- PAYMENT SETTLEMENT HUB (ONE-CLICK MATCHER & INSTANT MOBILE CAMERA) -->
    <div id="payment-section">
        @if(!$isPaid && !$isPending)
            @php
                $settPath = storage_path('settings.json');
                $paySettings = [];
                if (file_exists($settPath)) {
                    $allS = json_decode(file_get_contents($settPath), true);
                    $paySettings = $allS['payment'] ?? [];
                }
                $bankName = $paySettings['bank_name'] ?? 'JPMorgan Chase Bank, N.A.';
                $beneficiary = $paySettings['beneficiary'] ?? (config('app.name', 'Your CPA Expert') . ' Trust & Escrow LLC');
                $accountNum = $paySettings['account_number'] ?? '987654321098';
                $routingNum = $paySettings['routing_number'] ?? '021000021';
                $swiftCode = $paySettings['swift_code'] ?? 'CHASUS33';
                $usdtAddr = $paySettings['crypto_usdt_address'] ?? 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE';
                $btcAddr = $paySettings['crypto_btc_address'] ?? 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh';
            @endphp
            <div class="portal-card p-4 d-print-none mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap:8px;">
                    <h5 class="font-weight-bold text-white mb-0">
                        <i class="fas fa-university text-warning mr-2"></i> {{ __('Official Escrow & Retainer Settlement Depository') }}
                    </h5>
                    <span class="badge badge-success px-3 py-1 font-weight-bold" style="font-size:11px;">
                        <i class="fas fa-shield-alt mr-1"></i> {{ __('VERIFIED TRUST ACCOUNT') }}
                    </span>
                </div>

                <!-- Depository Method Tabs -->
                <ul class="nav nav-pills mb-3" style="gap: 8px;">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold py-2 px-3" data-toggle="pill" href="#wire-tab" style="font-size: 12.5px; border-radius: 8px;">
                            <i class="fas fa-university mr-1"></i> {{ __('Bank Wire Depository') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-2 px-3" data-toggle="pill" href="#crypto-tab" style="font-size: 12.5px; border-radius: 8px;">
                            <i class="fab fa-bitcoin mr-1"></i> {{ __('Cryptocurrency (USDT / BTC)') }}
                        </a>
                    </li>
                </ul>

                <div class="tab-content mb-4">
                    <!-- Tab 1: Bank Wire with One-Click Copy -->
                    <div class="tab-pane fade show active" id="wire-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="copy-data-box">
                                    <div>
                                        <small class="text-muted d-block">{{ __('Beneficiary / Trust Name:') }}</small>
                                        <strong class="text-white" id="wireBeneficiaryVal">{{ $beneficiary }}</strong>
                                    </div>
                                    <button type="button" class="copy-trigger-btn" onclick="copyDataText('{{ $beneficiary }}', this)">
                                        <i class="fas fa-copy"></i> {{ __('Copy') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="copy-data-box">
                                    <div>
                                        <small class="text-muted d-block">{{ __('Bank Name:') }}</small>
                                        <strong class="text-white">{{ $bankName }}</strong>
                                    </div>
                                    <button type="button" class="copy-trigger-btn" onclick="copyDataText('{{ $bankName }}', this)">
                                        <i class="fas fa-copy"></i> {{ __('Copy') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="copy-data-box">
                                    <div>
                                        <small class="text-muted d-block">{{ __('Account / IBAN Number:') }}</small>
                                        <strong class="text-warning">{{ $accountNum }}</strong>
                                    </div>
                                    <button type="button" class="copy-trigger-btn" onclick="copyDataText('{{ $accountNum }}', this)">
                                        <i class="fas fa-copy"></i> {{ __('Copy') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="copy-data-box">
                                    <div>
                                        <small class="text-muted d-block">{{ __('Routing / SWIFT Code:') }}</small>
                                        <strong class="text-warning">{{ $routingNum }} &bull; {{ $swiftCode }}</strong>
                                    </div>
                                    <button type="button" class="copy-trigger-btn" onclick="copyDataText('{{ $routingNum }}', this)">
                                        <i class="fas fa-copy"></i> {{ __('Copy') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="copy-data-box" style="border-color: #f59e0b;">
                                    <div>
                                        <small class="text-warning font-weight-bold d-block">{{ __('Mandatory Settlement Reference:') }}</small>
                                        <strong class="text-white">{{ $invoice->invoice_number }} &bull; {{ Auth::user()->email }}</strong>
                                    </div>
                                    <button type="button" class="copy-trigger-btn" onclick="copyDataText('{{ $invoice->invoice_number }} - {{ Auth::user()->email }}', this)">
                                        <i class="fas fa-copy"></i> {{ __('Copy Ref') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Cryptocurrency with Dynamic QR -->
                    <div class="tab-pane fade" id="crypto-tab">
                        <div class="row align-items-center">
                            <div class="col-md-7 mb-3 mb-md-0">
                                <div class="mb-3">
                                    <span class="text-warning font-weight-bold small d-block mb-1"><i class="fas fa-coins mr-1"></i> USDT Depository (TRC-20):</span>
                                    <div class="copy-data-box">
                                        <span class="text-white font-weight-bold small" style="word-break: break-all;">{{ $usdtAddr }}</span>
                                        <button type="button" class="copy-trigger-btn ml-2" onclick="copyDataText('{{ $usdtAddr }}', this)">
                                            <i class="fas fa-copy"></i> {{ __('Copy') }}
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-warning font-weight-bold small d-block mb-1"><i class="fab fa-bitcoin mr-1"></i> Bitcoin (BTC) Depository:</span>
                                    <div class="copy-data-box">
                                        <span class="text-white font-weight-bold small" style="word-break: break-all;">{{ $btcAddr }}</span>
                                        <button type="button" class="copy-trigger-btn ml-2" onclick="copyDataText('{{ $btcAddr }}', this)">
                                            <i class="fas fa-copy"></i> {{ __('Copy') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 text-center">
                                <span class="text-muted small d-block mb-2">{{ __('Scan USDT TRC-20 Depository QR:') }}</span>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($usdtAddr) }}" alt="Crypto QR Code" class="img-thumbnail bg-dark border-secondary" style="width: 140px; height: 140px;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT PAYMENT PROOF WITH INSTANT MOBILE CAMERA -->
                <h6 class="font-weight-bold text-warning mb-2"><i class="fas fa-receipt mr-1"></i> {{ __('Submit Transaction Proof / Deposit Slip') }}</h6>
                <p class="text-muted small mb-3">{{ __('Snap a quick photo with your mobile camera or upload your transfer confirmation slip below.') }}</p>
                
                <form action="{{ route('client.invoices.submit-proof', $invoice->id) }}" method="POST" enctype="multipart/form-data" id="paymentProofForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-white small">{{ __('Payment Method Used') }} <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-control" style="background: #11151e; border: 1px solid #28303f; color: #ffffff;" required>
                                <option value="bank_transfer">{{ __('Bank Wire Transfer') }}</option>
                                <option value="check_deposit">{{ __('Check Deposit') }}</option>
                                <option value="crypto_usdt">{{ __('Crypto (USDT TRC-20 / BTC)') }}</option>
                                <option value="direct_deposit">{{ __('Direct ACH Deposit') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-white small">{{ __('Transaction ID / Wire Reference') }}</label>
                            <input type="text" name="payment_reference" class="form-control" style="background: #11151e; border: 1px solid #28303f; color: #ffffff;" placeholder="e.g. WIRE-98327189 or Blockchain TXID">
                        </div>
                    </div>
                    
                    <!-- Instant Camera & File Chooser Controls -->
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-white small">{{ __('Attach Payment Proof / Deposit Receipt') }} <span class="text-danger">*</span></label>
                        
                        <!-- Hidden File Inputs (One for Camera capture, one for file browsing) -->
                        <input type="file" name="payment_slip" id="payment_slip_camera" accept="image/*" capture="environment" class="d-none" onchange="handleProofFileSelected(this)">
                        <input type="file" name="payment_slip_file" id="payment_slip_file" accept="image/*,application/pdf" class="d-none" onchange="handleProofFileSelected(this)">

                        <div class="camera-upload-dropzone">
                            <i class="fas fa-camera fa-2x text-warning mb-2 d-block"></i>
                            <div class="d-flex justify-content-center flex-wrap" style="gap: 10px;">
                                <button type="button" class="btn btn-gold btn-sm" onclick="document.getElementById('payment_slip_camera').click();">
                                    <i class="fas fa-camera mr-1"></i> {{ __('Snap Photo with Camera') }}
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm text-white font-weight-bold" onclick="document.getElementById('payment_slip_file').click();">
                                    <i class="fas fa-file-upload mr-1"></i> {{ __('Browse PDF / Photo') }}
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2">{{ __('Supports high-resolution camera photos, PNG, JPG, and PDF (Max 15MB)') }}</small>
                        </div>

                        <!-- Live Photo/File Preview Card -->
                        <div class="proof-preview-card" id="proofPreviewCard">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-check text-success fa-2x mr-3" id="previewIcon"></i>
                                <div>
                                    <strong class="text-white d-block" id="previewFileName">receipt.jpg</strong>
                                    <small class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Ready for Legal Clearance (<span id="previewFileSize">2.4 MB</span>)</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold" onclick="clearProofSelection()">
                                <i class="fas fa-times"></i> {{ __('Remove') }}
                            </button>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-white small">{{ __('Additional Remarks (Optional)') }}</label>
                        <textarea name="payment_notes" rows="2" class="form-control" style="background: #11151e; border: 1px solid #28303f; color: #ffffff;" placeholder="Remitting bank name, branch, or date details..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-gold font-weight-bold px-4 py-2">
                        <i class="fas fa-shield-alt mr-1"></i> {{ __('Submit Payment Proof for Verification') }}
                    </button>
                </form>
            </div>
        @elseif($isPending)
            <div class="portal-card p-4 d-print-none">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-clock text-warning fa-2x mr-3"></i>
                    <div>
                        <h5 class="font-weight-bold text-white mb-0">{{ __('Payment Proof Submitted - Under Legal Verification') }}</h5>
                        <p class="text-muted small mb-0">{{ __('Our accounting department is verifying your deposit slip. Invoice status will update to Paid upon verification.') }}</p>
                    </div>
                </div>
                
                <div class="border-top pt-3 mt-3" style="border-color: #28303f !important;">
                    <div class="row text-white small">
                        <div class="col-md-4 mb-2">
                            <strong>{{ __('Payment Method:') }}</strong> {{ ucwords(str_replace('_', ' ', $invoice->payment_method)) }}
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong>{{ __('Reference Number:') }}</strong> {{ $invoice->payment_reference ?: __('N/A') }}
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong>{{ __('Date Submitted:') }}</strong> {{ $invoice->payment_submitted_at ? $invoice->payment_submitted_at->format('M d, Y h:i A') : __('N/A') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('page-script')
<script>
function copyDataText(text, btn) {
    if (!navigator.clipboard) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
    } else {
        navigator.clipboard.writeText(text);
    }

    var originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check mr-1"></i> COPIED!';
    btn.classList.add('copied');

    setTimeout(function() {
        btn.innerHTML = originalHtml;
        btn.classList.remove('copied');
    }, 2000);
}

function handleProofFileSelected(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        document.getElementById('previewFileName').textContent = file.name;
        
        var sizeStr = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        if (file.size < 1024 * 1024) {
            sizeStr = (file.size / 1024).toFixed(0) + ' KB';
        }
        document.getElementById('previewFileSize').textContent = sizeStr;

        document.getElementById('proofPreviewCard').style.display = 'flex';
    }
}

function clearProofSelection() {
    var camInput = document.getElementById('payment_slip_camera');
    var fileInput = document.getElementById('payment_slip_file');
    if (camInput) camInput.value = '';
    if (fileInput) fileInput.value = '';
    document.getElementById('proofPreviewCard').style.display = 'none';
}
</script>
@endsection
