@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .status-badge-unpaid { background-color: #e74c3c; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
    .status-badge-paid { background-color: #2ecc71; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
    .status-badge-cancelled { background-color: #95a5a6; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
</style>
@endsection

@section('content')
    <div id="wrapper-content">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                    <span class="breadcrumb-item active">{{ __($title) }}</span>
                    <span class="breadcrumb-info" id="time"></span>
                </nav>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card card-dark bg-dark">
                    <div class="card-header d-block">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <h6 class="card-title lh-35">{{ __($title) }}</h6>
                            </div>
                            <div class="col-md-6 col-sm-12 text-md-right text-left">
                                <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> {{ __('Generate New Invoice') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive style-scroll">
                            <table class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Invoice Number') }}</th>
                                        <th>{{ __('Client') }}</th>
                                        <th>{{ __('Linked Case') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Due Date') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                            <td>
                                                <strong>{{ $invoice->client->name }}</strong>
                                                <div class="text-muted small">{{ $invoice->client->email }}</div>
                                            </td>
                                            <td>{{ $invoice->clientCase ? $invoice->clientCase->case_number . ' - ' . $invoice->clientCase->title : __('No Case Linked') }}</td>
                                            <td>${{ number_format($invoice->amount, 2) }}</td>
                                            <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="status-badge-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <form action="{{ route('admin.invoices.mark-paid', $invoice->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs {{ $invoice->status === 'paid' ? 'btn-warning' : 'btn-success' }} m-1" title="{{ $invoice->status === 'paid' ? __('Mark Unpaid') : __('Mark Paid') }}">
                                                            <i class="fas {{ $invoice->status === 'paid' ? 'fa-times' : 'fa-check' }}"></i>
                                                        </button>
                                                    </form>

                                                    <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="btn btn-xs btn-info m-1" title="{{ __('Edit Invoice') }}"><i class="fas fa-edit"></i></a>
                                                    
                                                    <form action="{{ route('admin.invoices.send-email', $invoice->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to send this invoice statement to the client\'s email address?') }}')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-primary m-1" title="{{ __('Email Statement to Client') }}">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    @if(Auth::user()->hasRole('admin'))
                                                        <form action="{{ route('admin.invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this invoice?') }}');" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-danger m-1" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
