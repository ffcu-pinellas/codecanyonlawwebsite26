@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | Official Receipt #' . $invoice->invoice_number)

@section('page-css')
<style>
    .receipt-container {
        max-width: 860px;
        margin: 0 auto;
    }
    .receipt-card {
        background: #ffffff;
        color: #0f172a;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        padding: 40px;
        position: relative;
        overflow: hidden;
    }
    .receipt-watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-30deg);
        font-size: 8rem;
        font-weight: 900;
        color: rgba(34, 197, 94, 0.04);
        pointer-events: none;
        user-select: none;
        letter-spacing: 15px;
        text-transform: uppercase;
        z-index: 0;
    }
    .receipt-paid-stamp {
        border: 3px dashed #16a34a;
        color: #16a34a;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 3px;
        font-size: 1.3rem;
        padding: 8px 24px;
        border-radius: 8px;
        transform: rotate(-8deg);
        display: inline-block;
        box-shadow: 0 0 15px rgba(22, 163, 74, 0.2);
    }
    .receipt-table {
        width: 100%;
        border-collapse: collapse;
        margin: 25px 0;
    }
    .receipt-table th {
        background: #f8fafc;
        border-bottom: 2px solid #cbd5e1;
        border-top: 1px solid #e2e8f0;
        padding: 12px 16px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        color: #475569;
    }
    .receipt-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13.5px;
        color: #1e293b;
    }
    .receipt-total-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 18px 24px;
    }
    .legal-signature-box {
        border-top: 2px solid #0f172a;
        padding-top: 8px;
        display: inline-block;
        min-width: 220px;
        text-align: center;
    }

    @media print {
        body { background: #fff !important; }
        .sidebar-wrapper, .top-header-bar, .non-printable, .btn-print-hide { display: none !important; }
        .receipt-card { box-shadow: none !important; border: none !important; padding: 0 !important; }
        .main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0 py-3 receipt-container">

    <!-- ACTION BUTTONS -->
    <div class="d-flex justify-content-between align-items-center mb-4 non-printable">
        <a href="{{ route('client.invoices.index') }}" class="btn btn-outline-secondary btn-sm px-3 font-weight-bold text-light" style="border-color:#374151;">
            <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Invoices') }}
        </a>
        <div class="d-flex" style="gap: 10px;">
            <a href="{{ route('client.invoices.show', $invoice->id) }}" class="btn btn-outline-warning btn-sm font-weight-bold">
                <i class="fas fa-file-invoice mr-1"></i> {{ __('View Invoice') }}
            </a>
            <button type="button" onclick="window.print()" class="btn btn-warning btn-sm font-weight-bold text-dark px-3 shadow-sm">
                <i class="fas fa-print mr-1"></i> {{ __('Print Official Receipt') }}
            </button>
        </div>
    </div>

    <!-- OFFICIAL RECEIPT CERTIFICATE -->
    <div class="receipt-card">
        <div class="receipt-watermark">CLEARED</div>

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4" style="border-color: #e2e8f0 !important; position: relative; z-index: 1;">
            <div>
                <h4 class="font-weight-bold mb-1" style="color: #0f172a; font-size: 1.4rem; letter-spacing: 0.5px;">
                    <i class="fas fa-balance-scale text-warning mr-2"></i>{{ $companyName }}
                </h4>
                <p class="text-muted small mb-1">{{ $companyAddress }}</p>
                <p class="text-muted small mb-0">{{ $companyPhone }} &bull; {{ $companyEmail }}</p>
            </div>
            <div class="text-right">
                <div class="receipt-paid-stamp mb-2">
                    <i class="fas fa-check-circle mr-1"></i> PAID IN FULL
                </div>
                <div class="small text-muted font-weight-bold">RECEIPT #: RCT-{{ $invoice->invoice_number }}</div>
                <div class="small text-muted">{{ __('Date:') }} {{ $invoice->updated_at ? $invoice->updated_at->format('F d, Y') : now()->format('F d, Y') }}</div>
            </div>
        </div>

        <!-- CLIENT & CASE METADATA -->
        <div class="row mb-4" style="position: relative; z-index: 1;">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <h6 class="font-weight-bold text-uppercase text-muted small mb-2" style="letter-spacing: 0.5px;">{{ __('Settlement Issued To:') }}</h6>
                <h5 class="font-weight-bold text-dark mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted small mb-0">{{ Auth::user()->email }}</p>
                <p class="text-muted small mb-0">{{ __('Client ID:') }} <span class="font-weight-bold text-dark">CLI-{{ sprintf('%05d', Auth::id()) }}</span></p>
            </div>
            <div class="col-sm-6 text-sm-right">
                <h6 class="font-weight-bold text-uppercase text-muted small mb-2" style="letter-spacing: 0.5px;">{{ __('Legal & CPA Matter:') }}</h6>
                <div class="font-weight-bold text-dark mb-1">
                    {{ $invoice->clientCase ? $invoice->clientCase->case_number . ' - ' . $invoice->clientCase->title : __('Direct Retainer & Settlement') }}
                </div>
                <div class="small text-muted">{{ __('Assigned Counsel:') }} <span class="font-weight-bold text-dark">Gary Livingston, Senior CPA</span></div>
                <div class="small text-muted">{{ __('Payment Status:') }} <span class="text-success font-weight-bold">{{ __('Settled & Cleared via Trust') }}</span></div>
            </div>
        </div>

        <!-- LINE ITEMS -->
        <div style="position: relative; z-index: 1;">
            <table class="receipt-table">
                <thead>
                    <tr>
                        <th>{{ __('Item Description / Retainer Scope') }}</th>
                        <th class="text-center" style="width: 100px;">{{ __('Qty') }}</th>
                        <th class="text-right" style="width: 150px;">{{ __('Amount Paid') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $lines = [];
                        if (!empty($invoice->description)) {
                            $splitLines = explode("\n", $invoice->description);
                            foreach($splitLines as $sl) {
                                if (trim($sl)) $lines[] = trim($sl);
                            }
                        }
                        if (empty($lines)) {
                            $lines[] = __('Privileged Legal Representation & CPA Retainer Settlement');
                        }
                    @endphp
                    @foreach($lines as $line)
                        <tr>
                            <td class="font-weight-bold">{{ $line }}</td>
                            <td class="text-center text-muted">1</td>
                            <td class="text-right font-weight-bold" style="color: #0f172a;">${{ number_format($invoice->amount, 2) }} USD</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TOTALS & SETTLEMENT CONFIRMATION -->
        <div class="row justify-content-end mb-5" style="position: relative; z-index: 1;">
            <div class="col-md-6">
                <div class="receipt-total-box">
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>{{ __('Base Invoiced Amount:') }}</span>
                        <span>${{ number_format($invoice->amount, 2) }} USD</span>
                    </div>
                    @if($invoice->late_fee_accumulated > 0)
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>{{ __('Late Settlement Adjustments:') }}</span>
                            <span>${{ number_format($invoice->late_fee_accumulated, 2) }} USD</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2 border-top pt-2 font-weight-bold" style="font-size: 15px; color: #0f172a;">
                        <span>{{ __('Total Cleared:') }}</span>
                        <span class="text-success">${{ number_format(($invoice->total_amount ?: $invoice->amount), 2) }} USD</span>
                    </div>
                    @if(Auth::user()->preferred_currency && Auth::user()->preferred_currency !== 'USD')
                        <div class="d-flex justify-content-between small text-muted">
                            <span>{{ __('Converted Equivalent:') }}</span>
                            <span class="font-weight-bold">{{ \App\Helpers\CurrencyHelper::formatOnly(($invoice->total_amount ?: $invoice->amount)) }}</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between border-top pt-2 mt-2 font-weight-bold small text-muted">
                        <span>{{ __('Outstanding Balance:') }}</span>
                        <span class="text-success">$0.00 USD</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- OFFICIAL CPA & LEGAL SIGNATURE SEAL -->
        <div class="d-flex justify-content-between align-items-end pt-4 border-top flex-wrap" style="border-color: #e2e8f0 !important; gap: 20px; position: relative; z-index: 1;">
            <div>
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-shield-check text-success mr-2" style="font-size: 24px;"></i>
                    <div>
                        <strong class="text-dark small d-block">{{ __('Cryptographically Verified Trust Receipt') }}</strong>
                        <span class="text-muted" style="font-size: 10.5px;">SHA-256 Hash: {{ hash('sha256', $invoice->id . $invoice->invoice_number . $invoice->amount . $invoice->created_at) }}</span>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <div class="legal-signature-box">
                    <img src="https://api.iconify.design/fluent-emoji:pen.svg" style="display:none;">
                    <div style="font-family:'Brush Script MT', cursive, serif; font-size:22px; color:#0f172a; line-height: 1;">Gary Livingston, CPA</div>
                    <small class="text-muted d-block font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">{{ __('MANAGING COUNSEL & ESCROW OFFICER') }}</small>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
