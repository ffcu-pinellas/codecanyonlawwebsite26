@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .invoice-card {
        background: #11151e;
        border: 1px solid #28303f;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        margin-bottom: 25px;
    }
    body.light-mode .invoice-card, html.light-mode .invoice-card {
        background: #ffffff;
        border-color: #e2e8f0;
        box-shadow: 0 4px 16px rgba(0,0,0,0.05);
    }
    .status-badge-unpaid { background-color: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    .status-badge-paid { background-color: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    .status-badge-cancelled { background-color: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3); font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    
    .section-title {
        font-weight: 700;
        color: #f1f5f9;
        margin-bottom: 25px;
        font-family: 'Montserrat', sans-serif;
    }
    body.light-mode .section-title, html.light-mode .section-title {
        color: #0f172a;
    }
    .table-adaptive thead th {
        background: #1a202c;
        color: #fecc56;
        border-color: #28303f;
        font-size: 12.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    body.light-mode .table-adaptive thead th, html.light-mode .table-adaptive thead th {
        background: #f8fafc;
        color: #0f172a;
        border-color: #e2e8f0;
    }
    .table-adaptive tbody tr {
        border-color: #28303f;
    }
    body.light-mode .table-adaptive tbody tr, html.light-mode .table-adaptive tbody tr {
        border-color: #e2e8f0;
    }
    .table-adaptive tbody td {
        border-color: #28303f;
        color: #e2e8f0;
    }
    body.light-mode .table-adaptive tbody td, html.light-mode .table-adaptive tbody td {
        border-color: #e2e8f0;
        color: #1e293b;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="section-title mb-0">{{ __($title) }}</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card invoice-card">
                @if($invoices->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-file-invoice-dollar fa-4x text-muted mb-3"></i>
                        <h5 class="font-weight-bold" style="color: inherit;">{{ __('No invoices found.') }}</h5>
                        <p class="text-muted">{{ __('There are currently no generated or pending client invoices in the system.') }}</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-adaptive align-middle">
                            <thead>
                                <tr>
                                    <th>{{ __('Invoice Number') }}</th>
                                    <th>{{ __('Client') }}</th>
                                    <th>{{ __('Linked Case') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-right">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold" style="color: #fecc56;">{{ $invoice->invoice_number }}</span>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold" style="color: inherit;">{{ $invoice->client->name }}</span>
                                            <small class="text-muted d-block">{{ $invoice->client->email }}</small>
                                        </td>
                                        <td>
                                            @if($invoice->clientCase)
                                                <span class="font-weight-medium" style="color: inherit;">{{ $invoice->clientCase->case_number }}</span>
                                                <small class="text-muted d-block">{{ $invoice->clientCase->title }}</small>
                                            @else
                                                <span class="text-muted small">{{ __('General Account Representation') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="font-weight-bold" style="color: inherit;">${{ number_format($invoice->amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="small text-muted">{{ $invoice->due_date->format('M d, Y') }}</span>
                                        </td>
                                        <td>
                                            <span class="status-badge-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('staff.invoices.show', $invoice->id) }}" class="btn btn-outline-primary btn-sm rounded-lg py-1 px-3 font-weight-bold">
                                                <i class="fas fa-eye mr-1"></i> {{ __('View Details') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
