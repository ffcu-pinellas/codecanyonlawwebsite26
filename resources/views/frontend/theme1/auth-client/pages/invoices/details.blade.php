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
    @media print {
        body * { visibility: hidden; }
        .invoice-container, .invoice-container * { visibility: visible; }
        .invoice-container { position: absolute; left: 0; top: 0; width: 100%; padding: 0; background: #ffffff !important; color: #000000 !important; }
        .d-print-none, header, footer, .ifw-client-sidebar { display: none !important; }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Back & Print Navigation Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <a href="{{ route('client.invoices.index') }}" class="btn btn-sm btn-outline-secondary text-light font-weight-bold px-3" style="border-color: #3b4252;">
            <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Invoices') }}
        </a>
        <button class="btn btn-gold btn-sm px-4" onclick="window.print()">
            <i class="fas fa-print mr-1"></i> {{ __('Print Official Statement') }}
        </button>
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
                <div class="col-md-6 mb-3 mb-md-0 text-center text-md-left">
                    @if(!empty($logoFavicon) && !empty($logoFavicon->logo))
                        <img src="{{ asset($logoFavicon->logo) }}" alt="{{ $companyName }}" style="max-height: 48px; margin-bottom: 10px;">
                    @endif
                    <span class="company-logo-text d-block">{{ $companyName }}</span>
                    <div class="text-muted small mt-2">
                        <p class="mb-1 text-light"><strong>{{ __('Practice Office:') }}</strong> {{ $companyAddress }}</p>
                        <p class="mb-0 text-light"><strong>{{ __('Phone:') }}</strong> {{ $companyPhone }} &bull; <strong>{{ __('Email:') }}</strong> {{ $companyEmail }}</p>
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-right">
                    <h3 class="font-weight-bold text-white mb-2" style="font-size: 1.4rem;">{{ __('STATEMENT OF ACCOUNT') }}</h3>
                    <span class="status-badge-{{ strtolower($invoice->status) }}">{{ strtoupper($invoice->status) }}</span>
                    <div class="mt-2 text-muted small">
                        <strong>{{ __('Invoice Ref:') }}</strong> <span class="text-warning font-weight-bold">{{ $invoice->invoice_number }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing details info -->
        <div class="row mb-4">
            <div class="col-md-6 mb-4 mb-md-0">
                <h6 class="text-uppercase font-weight-bold text-warning mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('Billed To Client') }}</h6>
                <h5 class="font-weight-bold text-white mb-1">{{ $invoice->client->name }}</h5>
                <p class="text-muted small mb-1">{{ $invoice->client->email }}</p>
                <p class="text-muted small mb-0">{{ $invoice->client->address ?: __('No Mailing Address Registered') }}</p>
            </div>
            <div class="col-md-6 text-md-right">
                <h6 class="text-uppercase font-weight-bold text-warning mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('Statement Details') }}</h6>
                <p class="text-muted small mb-1"><strong>{{ __('Issue Date:') }}</strong> <span class="text-white">{{ $invoice->created_at->format('M d, Y') }}</span></p>
                <p class="text-muted small mb-1"><strong>{{ __('Due Date:') }}</strong> <span class="text-warning font-weight-bold">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'Due on Receipt' }}</span></p>
                @if($invoice->clientCase)
                    <p class="text-muted small mb-0"><strong>{{ __('Matter Ref:') }}</strong> <span class="text-white">{{ $invoice->clientCase->case_number }} ({{ $invoice->clientCase->title }})</span></p>
                @endif
            </div>
        </div>

        <!-- Line items table -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('Description of Services & Legal Representation') }}</th>
                        <th class="text-right" style="width: 160px;">{{ __('Amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="py-2">
                                <h6 class="font-weight-bold text-white mb-1">
                                    @if($invoice->clientCase)
                                        {{ __('Legal/CPA Representation for Matter #') }}{{ $invoice->clientCase->case_number }}
                                    @else
                                        {{ __('Professional Legal & CPA Representation Statement') }}
                                    @endif
                                </h6>
                                <p class="text-muted small mb-0" style="white-space: pre-line; line-height: 1.5;">
                                    {{ $invoice->description ?: __('Retainer advisory and statutory accounting representation statement.') }}
                                </p>
                            </div>
                        </td>
                        <td class="text-right align-middle font-weight-bold text-warning" style="font-size: 1.15rem;">
                            ${{ number_format($invoice->total_amount ?: $invoice->amount, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment calculation details summary -->
        <div class="row justify-content-end">
            <div class="col-md-5 text-right">
                <div class="py-2 border-top" style="border-color: #28303f !important;">
                    <span class="text-muted font-weight-bold small mr-4">{{ __('Subtotal:') }}</span>
                    <span class="font-weight-bold text-white">${{ number_format($invoice->total_amount ?: $invoice->amount, 2) }}</span>
                </div>
                <div class="py-2 border-top" style="border-color: #28303f !important;">
                    <span class="text-muted font-weight-bold small mr-4">{{ __('Taxes / Surcharges (0%):') }}</span>
                    <span class="font-weight-bold text-white">$0.00</span>
                </div>
                <div class="py-3 border-top mt-2" style="border-color: #fecc56 !important; font-size: 1.25rem;">
                    <span class="font-weight-bold text-white mr-4">{{ __('Total Due:') }}</span>
                    <span class="font-weight-bold text-warning">${{ number_format($invoice->total_amount ?: $invoice->amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer terms -->
        <div class="mt-5 pt-4 border-top text-center text-muted small" style="border-color: #28303f !important;">
            <p class="mb-1 text-white"><strong>{{ __('Thank you for your business.') }}</strong></p>
            <p class="mb-0 text-muted">{{ __('For inquiries regarding retainers or statements, reach out to our accounts department at') }} <span class="text-warning">{{ $companyEmail }}</span>.</p>
        </div>
    </div>

    <!-- Offline Payment / Proof Section -->
    @if(strtolower($invoice->status) === 'unpaid' || strtolower($invoice->status) === 'due')
        <div class="portal-card p-4 d-print-none">
            <h5 class="font-weight-bold text-white mb-2"><i class="fas fa-receipt text-warning mr-2"></i> {{ __('Bank Wire / Payment Proof Submission') }}</h5>
            <p class="text-muted small mb-4">{{ __('If you have settled this retainer statement via bank wire transfer or direct check deposit, submit your transaction receipt below for immediate attorney confirmation.') }}</p>
            
            <form action="{{ route('client.invoices.submit-proof', $invoice->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold text-white small">{{ __('Payment Method Used') }} <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-control" style="background: #11151e; border: 1px solid #28303f; color: #ffffff;" required>
                            <option value="bank_transfer">{{ __('Bank Wire Transfer') }}</option>
                            <option value="check_deposit">{{ __('Check Deposit') }}</option>
                            <option value="direct_deposit">{{ __('Direct ACH Deposit') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold text-white small">{{ __('Transaction ID / Reference Number') }}</label>
                        <input type="text" name="payment_reference" class="form-control" style="background: #11151e; border: 1px solid #28303f; color: #ffffff;" placeholder="e.g. WIRE-98327189 or Check #1204">
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-white small">{{ __('Upload Bank Receipt / Deposit Slip') }} <span class="text-danger">*</span></label>
                    <input type="file" name="payment_slip" class="form-control-file text-white" required>
                    <small class="text-muted d-block mt-1">{{ __('Supported formats: PDF, PNG, JPG, JPEG (Max 10MB)') }}</small>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-white small">{{ __('Additional Remarks (Optional)') }}</label>
                    <textarea name="payment_notes" rows="2" class="form-control" style="background: #11151e; border: 1px solid #28303f; color: #ffffff;" placeholder="Remitting bank name, branch, or date details..."></textarea>
                </div>

                <button type="submit" class="btn btn-gold font-weight-bold px-4"><i class="fas fa-upload mr-1"></i> {{ __('Submit Payment Proof') }}</button>
            </form>
        </div>
    @elseif(strtolower($invoice->status) === 'pending')
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
@endsection
