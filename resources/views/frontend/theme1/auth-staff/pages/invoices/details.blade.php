@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .invoice-container {
        background: #11151e;
        border: 1px solid #28303f;
        border-radius: 14px;
        padding: 40px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        margin-bottom: 30px;
        position: relative;
        color: #f1f5f9;
    }
    body.light-mode .invoice-container, html.light-mode .invoice-container {
        background: #ffffff;
        border-color: #e2e8f0;
        box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        color: #0f172a;
    }
    .invoice-header {
        border-bottom: 2px solid #28303f;
        padding-bottom: 25px;
        margin-bottom: 25px;
    }
    body.light-mode .invoice-header, html.light-mode .invoice-header {
        border-bottom-color: #f1f5f9;
    }
    .status-badge-unpaid { background-color: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 600; font-size: 0.8rem; padding: 6px 15px; border-radius: 20px; text-transform: uppercase; }
    .status-badge-paid { background-color: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); font-weight: 600; font-size: 0.8rem; padding: 6px 15px; border-radius: 20px; text-transform: uppercase; }
    .status-badge-cancelled { background-color: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3); font-weight: 600; font-size: 0.8rem; padding: 6px 15px; border-radius: 20px; text-transform: uppercase; }
    
    .section-title {
        font-weight: 700;
        color: #f1f5f9;
        font-family: 'Montserrat', sans-serif;
    }
    body.light-mode .section-title, html.light-mode .section-title {
        color: #0f172a;
    }
    .company-logo-text {
        font-size: 1.6rem;
        font-weight: 800;
        color: #fecc56;
        letter-spacing: 0.5px;
    }
    body.light-mode .company-logo-text, html.light-mode .company-logo-text {
        color: #0f172a;
    }
    .invoice-table thead th {
        background: #1a202c;
        color: #fecc56;
        border-color: #28303f;
    }
    body.light-mode .invoice-table thead th, html.light-mode .invoice-table thead th {
        background: #f8fafc;
        color: #0f172a;
        border-color: #e2e8f0;
    }
    .invoice-table td, .invoice-table th {
        border-color: #28303f;
        color: inherit;
    }
    body.light-mode .invoice-table td, body.light-mode .invoice-table th {
        border-color: #e2e8f0;
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
            background: white !important;
            color: black !important;
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
        <a href="{{ route('staff.invoices.index') }}" class="text-primary font-weight-bold"><i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Invoices') }}</a>
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
                    <span class="company-logo-text">{{ $companyName }}</span>
                    <div class="text-muted small mt-2">
                        <p class="mb-1"><strong>{{ __('Corporate Office Address:') }}</strong> {{ $companyAddress }}</p>
                        <p class="mb-1"><strong>{{ __('Phone:') }}</strong> {{ $companyPhone }} &nbsp;|&nbsp; <strong>{{ __('Email:') }}</strong> {{ $companyEmail }}</p>
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-right">
                    <h3 class="font-weight-bold uppercase mb-2" style="font-size: 1.4rem; color: inherit;">{{ __('Invoice') }}</h3>
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
                <h5 class="font-weight-bold mb-1" style="color: inherit;">{{ $invoice->client->name }}</h5>
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
            <table class="table table-bordered invoice-table">
                <thead>
                    <tr>
                        <th>{{ __('Description of Services Rendered') }}</th>
                        <th class="text-right" style="width: 150px;">{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="py-2">
                                <h6 class="font-weight-bold mb-1" style="color: inherit;">
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
                        <td class="text-right align-middle font-weight-bold" style="color: inherit;">${{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment calculation details summary -->
        <div class="row justify-content-end">
            <div class="col-md-5 text-right">
                <div class="py-2 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                    <span class="text-muted uppercase font-weight-bold small mr-4">{{ __('Subtotal:') }}</span>
                    <span class="font-weight-semibold" style="color: inherit;">${{ number_format($invoice->amount, 2) }}</span>
                </div>
                <div class="py-2 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                    <span class="text-muted uppercase font-weight-bold small mr-4">{{ __('Tax / Surcharges (0%):') }}</span>
                    <span class="font-weight-semibold" style="color: inherit;">$0.00</span>
                </div>
                <div class="py-3 border-top mt-2" style="font-size: 1.25rem; border-color: rgba(254, 204, 86, 0.4) !important;">
                    <span class="font-weight-bold mr-4" style="color: inherit;">{{ __('Amount Due:') }}</span>
                    <span class="font-weight-bold text-warning">${{ number_format($invoice->amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer terms -->
        <div class="mt-5 pt-4 border-top border-light text-center text-muted small">
            <p class="mb-1"><strong>{{ __('Thank you for your business!') }}</strong></p>
            <p class="mb-0">{{ __('If you have any questions about this statement, please contact our financial billing department at') }} {{ $companyEmail }}.</p>
        </div>
    </div>
</div>
@endsection
