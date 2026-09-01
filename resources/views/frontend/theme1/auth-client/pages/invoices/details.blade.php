@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

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
    .status-badge-unpaid { background-color: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 600; font-size: 0.8rem; padding: 6px 15px; border-radius: 20px; text-transform: uppercase; }
    .status-badge-pending { background-color: rgba(245, 158, 11, 0.15); color: #fecc56; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: 600; font-size: 0.8rem; padding: 6px 15px; border-radius: 20px; text-transform: uppercase; }
    .status-badge-paid { background-color: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); font-weight: 600; font-size: 0.8rem; padding: 6px 15px; border-radius: 20px; text-transform: uppercase; }
    .status-badge-cancelled { background-color: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3); font-weight: 600; font-size: 0.8rem; padding: 6px 15px; border-radius: 20px; text-transform: uppercase; }
    
    .section-title {
        font-weight: 700;
        color: #ffffff;
        font-family: 'Montserrat', sans-serif;
    }
    .company-logo-text {
        font-size: 1.6rem;
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
        padding: 6px 14px;
        font-size: 12px;
        box-shadow: 0 2px 8px rgba(254,204,86,0.25);
    }
    .invoice-container .table-hover tbody tr:hover {
        background-color: #1a202c;
    }
    .invoice-container .table {
        color: #f1f5f9;
    }
    .invoice-container .table thead th {
        background: #1f2533;
        color: #fecc56;
        border-color: #28303f;
    }
    .invoice-container .table td {
        border-color: #28303f;
    }
    .form-dark .form-control, .invoice-container .form-control {
        background: #0f172a !important;
        border: 1px solid #334155 !important;
        color: #ffffff !important;
    }
    .form-dark .form-control:focus, .invoice-container .form-control:focus {
        border-color: #fecc56 !important;
        box-shadow: 0 0 0 2px rgba(254, 204, 86, 0.2) !important;
    }
    @media print {
        body * {
            visibility: hidden;
        }
        .invoice-container, .invoice-container * {
            visibility: visible;
        }
        .invoice-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0;
            box-shadow: none;
            background: #ffffff !important;
            color: #000000 !important;
        }
        .print-btn-container {
            display: none !important;
        }
        .client-navigation, .top-bar-wrapper, .mobile-close-btn {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Back & Print Actions -->
    <div class="d-flex justify-content-between align-items-center mb-4 print-btn-container">
        <a href="{{ route('client.invoices.index') }}" class="text-primary font-weight-bold"><i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Invoices') }}</a>
        <button class="btn btn-primary btn-sm px-4 font-weight-bold" onclick="window.print()"><i class="fas fa-print mr-1"></i> {{ __('Print Invoice') }}</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="invoice-container">
        <!-- Header Info -->
        <div class="invoice-header">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0 text-center text-md-left">
                    @if(!empty($logoFavicon) && !empty($logoFavicon->logo))
                        <img src="{{ asset($logoFavicon->logo) }}" alt="{{ $companyName }}" style="max-height: 50px; margin-bottom: 10px;">
                    @endif
                    <span class="company-logo-text" style="display: block;">{{ $companyName }}</span>
                    <div class="text-muted small mt-2">
                        <p class="mb-1"><strong>{{ __('Corporate Office Address:') }}</strong> {{ $companyAddress }}</p>
                        <p class="mb-1"><strong>{{ __('Phone:') }}</strong> {{ $companyPhone }} &nbsp;|&nbsp; <strong>{{ __('Email:') }}</strong> {{ $companyEmail }}</p>
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-right">
                    <h3 class="font-weight-bold text-dark uppercase mb-2" style="font-size: 1.4rem;">{{ __('Invoice') }}</h3>
                    <span class="status-badge-{{ $invoice->status }}">{{ $invoice->status }}</span>
                    <div class="mt-2 text-muted small">
                        <strong>{{ __('Invoice #:') }}</strong> {{ $invoice->invoice_number }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing details info -->
        <div class="row mb-5">
            <div class="col-md-6 mb-4 mb-md-0">
                <h6 class="text-uppercase font-weight-bold text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('Billed To') }}</h6>
                <h5 class="font-weight-bold text-dark mb-1">{{ $invoice->client->name }}</h5>
                <p class="text-muted small mb-1">{{ $invoice->client->email }}</p>
                <p class="text-muted small mb-0">{{ $invoice->client->address ?: __('No Address Registered') }}</p>
            </div>
            <div class="col-md-6 text-md-right">
                <h6 class="text-uppercase font-weight-bold text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('Invoice Information') }}</h6>
                <p class="text-muted small mb-1"><strong>{{ __('Issue Date:') }}</strong> {{ $invoice->created_at->format('M d, Y') }}</p>
                <p class="text-muted small mb-1"><strong>{{ __('Due Date:') }}</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
                @if($invoice->clientCase)
                    <p class="text-muted small mb-0"><strong>{{ __('Matter Ref:') }}</strong> {{ $invoice->clientCase->case_number }} - {{ $invoice->clientCase->title }}</p>
                @endif
            </div>
        </div>

        <!-- Line items table -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th>{{ __('Description of Services Rendered') }}</th>
                        <th class="text-right" style="width: 150px;">{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="py-2">
                                <h6 class="font-weight-bold text-dark mb-1">
                                    @if($invoice->clientCase)
                                        {{ __('Legal/CPA Representation for Case #') }}{{ $invoice->clientCase->case_number }}
                                    @else
                                        {{ __('Professional Account Consulting & Retainer Services') }}
                                    @endif
                                </h6>
                                <p class="text-muted small mb-0" style="white-space: pre-line; line-height: 1.5;">
                                    {{ $invoice->description ?: __('Retainer fees and professional consulting representation statement.') }}
                                </p>
                            </div>
                        </td>
                        <td class="text-right align-middle font-weight-bold text-dark">${{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment calculation details summary -->
        <div class="row justify-content-end">
            <div class="col-md-5 text-right">
                <div class="py-2 border-top">
                    <span class="text-muted uppercase font-weight-bold small mr-4">{{ __('Subtotal:') }}</span>
                    <span class="font-weight-semibold text-dark">${{ number_format($invoice->amount, 2) }}</span>
                </div>
                <div class="py-2 border-top">
                    <span class="text-muted uppercase font-weight-bold small mr-4">{{ __('Tax / Surcharges (0%):') }}</span>
                    <span class="font-weight-semibold text-dark">$0.00</span>
                </div>
                <div class="py-3 border-top border-dark mt-2" style="font-size: 1.25rem;">
                    <span class="font-weight-bold text-dark mr-4">{{ __('Amount Due:') }}</span>
                    <span class="font-weight-bold text-primary">${{ number_format($invoice->amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer terms -->
        <div class="mt-5 pt-4 border-top border-light text-center text-muted small">
            <p class="mb-1"><strong>{{ __('Thank you for your business!') }}</strong></p>
            <p class="mb-0">{{ __('If you have any questions about this statement, please contact our financial billing department at') }} {{ $companyEmail }}.</p>
        </div>
    </div>

    @if($invoice->status === 'unpaid')
        <div class="card p-4 mt-4 d-print-none" style="border-radius: 15px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-money-bill-wave text-success mr-2"></i> {{ __('Offline Payment Slip Submission') }}</h5>
            <p class="text-muted small mb-4">{{ __('If you have paid this invoice via bank wire transfer or by depositing a check, please upload the receipt/deposit slip copy and enter reference details below to verify your payment.') }}</p>
            
            <form action="{{ route('client.invoices.submit-proof', $invoice->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="payment_method" class="font-weight-semibold text-dark small">{{ __('Payment Method Used') }} <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value="bank_transfer">{{ __('Bank Wire Transfer') }}</option>
                            <option value="check_deposit">{{ __('Check Deposit') }}</option>
                            <option value="direct_deposit">{{ __('Direct Bank Transfer') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="payment_reference" class="font-weight-semibold text-dark small">{{ __('Transaction ID / Reference Number') }}</label>
                        <input type="text" name="payment_reference" id="payment_reference" class="form-control" placeholder="e.g. TXN98327189 or Check #1204">
                    </div>
                </div>
                
                <div class="form-group mb-4">
                    <label class="font-weight-semibold text-dark small d-block">{{ __('Upload Bank Receipt / Deposit Slip') }} <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" name="payment_slip" id="payment_slip" class="custom-file-input" required onchange="$('#slip-file-name').text(this.files[0].name)">
                        <label class="custom-file-label" for="payment_slip" id="slip-file-name">{{ __('Choose file...') }}</label>
                    </div>
                    <small class="text-muted d-block mt-1">{{ __('Supported formats: PDF, PNG, JPG, JPEG (Max 10MB)') }}</small>
                </div>

                <div class="form-group">
                    <label for="payment_notes" class="font-weight-semibold text-dark small">{{ __('Additional Notes (Optional)') }}</label>
                    <textarea name="payment_notes" id="payment_notes" rows="3" class="form-control" placeholder="Any details about the sender account, bank branch, or date..."></textarea>
                </div>

                <button type="submit" class="btn btn-success font-weight-bold px-4"><i class="fas fa-upload mr-1"></i> {{ __('Submit Payment Proof') }}</button>
            </form>
        </div>
    @elseif($invoice->status === 'pending')
        <div class="card p-4 mt-4 d-print-none text-white bg-dark" style="border-radius: 15px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <div class="d-flex align-items-center mb-3">
                <i class="fas fa-clock text-warning fa-2x mr-3"></i>
                <div>
                    <h5 class="font-weight-bold text-white mb-0">{{ __('Payment Proof Submitted - Pending Review') }}</h5>
                    <p class="text-white-50 small mb-0">{{ __('Our financial department is currently verifying your offline deposit. Invoice status will be updated upon approval.') }}</p>
                </div>
            </div>
            
            <div class="border-top border-secondary pt-3 mt-3">
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
                @if($invoice->payment_notes)
                    <div class="mt-2 bg-secondary p-3 rounded text-white small">
                        <strong>{{ __('My Notes:') }}</strong> {{ $invoice->payment_notes }}
                    </div>
                @endif
                @if($invoice->payment_slip_path)
                    <div class="mt-3">
                        <strong>{{ __('Uploaded Slip File:') }}</strong>
                        <a href="{{ asset($invoice->payment_slip_path) }}" target="_blank" class="btn btn-sm btn-outline-warning ml-2"><i class="fas fa-external-link-alt mr-1"></i> {{ __('View Attachment') }}</a>
                    </div>
                @endif
            </div>
        </div>
    @elseif($invoice->status === 'paid' && $invoice->payment_method)
        <div class="card p-4 mt-4 d-print-none bg-light" style="border-radius: 15px; border: none; border-left: 5px solid #2ecc71; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle text-success fa-2x mr-3"></i>
                <div>
                    <h5 class="font-weight-bold text-dark mb-0">{{ __('Verified Offline Payment') }}</h5>
                    <p class="text-muted small mb-0">{{ __('This invoice has been settled via offline manual payment verification.') }}</p>
                </div>
            </div>
            <div class="border-top pt-3 mt-3 small text-muted">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <strong>{{ __('Payment Method:') }}</strong> {{ ucwords(str_replace('_', ' ', $invoice->payment_method)) }}
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>{{ __('Reference Number:') }}</strong> {{ $invoice->payment_reference ?: __('N/A') }}
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>{{ __('Settled Date:') }}</strong> {{ $invoice->payment_submitted_at ? $invoice->payment_submitted_at->format('M d, Y') : $invoice->updated_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
