@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .invoice-card {
        background: white;
        border-radius: 15px;
        padding: 24px;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    .status-badge-unpaid { background-color: #ffeef0; color: #f84f5a; font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    .status-badge-paid { background-color: #e8f5e9; color: #2e7d32; font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    .status-badge-cancelled { background-color: #f1f3f5; color: #868e96; font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    
    .section-title {
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 25px;
        font-family: 'Montserrat', sans-serif;
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
                        <i class="fas fa-file-invoice-dollar fa-4x text-light mb-3"></i>
                        <h5 class="text-dark font-weight-bold">{{ __('No invoices found.') }}</h5>
                        <p class="text-muted">{{ __('There are currently no generated or pending client invoices in the system.') }}</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light">
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
                                            <span class="font-weight-bold text-dark">{{ $invoice->invoice_number }}</span>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-dark">{{ $invoice->client->name }}</span>
                                            <small class="text-muted d-block">{{ $invoice->client->email }}</small>
                                        </td>
                                        <td>
                                            @if($invoice->clientCase)
                                                <span class="font-weight-medium text-dark">{{ $invoice->clientCase->case_number }}</span>
                                                <small class="text-muted d-block">{{ $invoice->clientCase->title }}</small>
                                            @else
                                                <span class="text-muted small">{{ __('General Account Representation') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-dark">${{ number_format($invoice->amount, 2) }}</span>
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
