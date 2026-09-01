@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
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
        padding: 12px 16px;
        border-bottom: 2px solid #28303f;
    }
    .table-portal tbody tr {
        border-bottom: 1px solid #232a38;
        transition: background 0.15s;
    }
    .table-portal tbody tr:hover {
        background: #1a202c;
    }
    .table-portal td {
        padding: 14px 16px;
        font-size: 13px;
        vertical-align: middle;
    }
    .status-badge-unpaid { background-color: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: bold; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; }
    .status-badge-paid { background-color: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); font-weight: bold; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; }
    .status-badge-cancelled { background-color: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3); font-weight: bold; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; }
    
    .btn-gold {
        background: linear-gradient(135deg, #fecc56, #f0a500);
        color: #000 !important;
        border: none;
        font-weight: 700;
        border-radius: 6px;
        padding: 6px 14px;
        font-size: 12px;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(254,204,86,0.25);
    }
    .btn-gold:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(254,204,86,0.45);
    }
    @media (max-width: 991px) {
        .table-portal thead { display: none; }
        .table-portal, .table-portal tbody, .table-portal tr, .table-portal td { display: block; width: 100%; }
        .table-portal tbody tr {
            margin-bottom: 14px;
            border: 1px solid #28303f;
            border-radius: 10px;
            padding: 12px 14px;
            background: #161a23;
        }
        .table-portal td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #1f2533;
            text-align: right;
        }
        .table-portal td:last-child {
            border-bottom: none;
            padding-top: 10px;
            justify-content: flex-end;
        }
        .table-portal td[data-label]::before {
            content: attr(data-label);
            font-weight: 700;
            color: #94a3b8;
            font-size: 11px;
            text-transform: uppercase;
            text-align: left;
            margin-right: 12px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0 py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="font-weight-bold text-white mb-1">
                <i class="fas fa-file-invoice-dollar text-warning mr-2"></i> {{ __('Invoices & Retainers') }}
            </h4>
            <p class="text-muted small mb-0">{{ __('Review statements, retainer invoices, and payment receipts.') }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success font-weight-bold mb-4" style="background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80;">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="portal-card">
        <div class="portal-card-header">
            <i class="fas fa-receipt mr-1"></i> {{ __('Billing & Retainer History') }}
        </div>
        @if($invoices->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-file-invoice-dollar fa-4x text-secondary mb-3 d-block"></i>
                <h5 class="text-white font-weight-bold">{{ __('No invoices found.') }}</h5>
                <p class="text-muted">{{ __('You currently have no generated or pending invoices on your profile.') }}</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table-portal">
                    <thead>
                        <tr>
                            <th>{{ __('Invoice #') }}</th>
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
                                    <strong class="text-white">{{ $invoice->invoice_number }}</strong>
                                    <small class="text-muted d-block">{{ $invoice->created_at ? $invoice->created_at->format('M d, Y') : '' }}</small>
                                </td>
                                <td>
                                    @if($invoice->clientCase)
                                        <span class="text-warning font-weight-bold">{{ $invoice->clientCase->case_number }}</span>
                                    @else
                                        <span class="text-muted">{{ __('General Retainer') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-warning">${{ number_format($invoice->total_amount, 2) }}</strong>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $invoice->due_date ? date('M d, Y', strtotime($invoice->due_date)) : 'Upon Receipt' }}</small>
                                </td>
                                <td>
                                    @if($invoice->status === 'paid')
                                        <span class="status-badge-paid"><i class="fas fa-check-circle mr-1"></i> {{ __('Paid') }}</span>
                                    @elseif($invoice->status === 'cancelled')
                                        <span class="status-badge-cancelled">{{ __('Cancelled') }}</span>
                                    @else
                                        <span class="status-badge-unpaid"><i class="fas fa-exclamation-circle mr-1"></i> {{ __('Payment Due') }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('client.invoices.show', $invoice->id) }}" class="btn-gold">
                                        <i class="fas fa-file-invoice mr-1"></i> {{ __('View & Pay') }}
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
@endsection
