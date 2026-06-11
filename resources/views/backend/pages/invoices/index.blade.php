@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .status-badge-unpaid { background-color: #e74c3c; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
    .status-badge-pending { background-color: #f39c12; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
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
                                                    @if($invoice->status === 'pending')
                                                        <button type="button" class="btn btn-xs btn-warning m-1 verify-slip-btn text-white" 
                                                            data-id="{{ $invoice->id }}"
                                                            data-number="{{ $invoice->invoice_number }}"
                                                            data-client="{{ $invoice->client->name }}"
                                                            data-amount="${{ number_format($invoice->amount, 2) }}"
                                                            data-method="{{ ucwords(str_replace('_', ' ', $invoice->payment_method)) }}"
                                                            data-reference="{{ $invoice->payment_reference ?: __('N/A') }}"
                                                            data-notes="{{ $invoice->payment_notes ?: __('No notes provided') }}"
                                                            data-slip="{{ asset($invoice->payment_slip_path) }}"
                                                            data-filetype="{{ strtolower(pathinfo($invoice->payment_slip_path, PATHINFO_EXTENSION)) }}"
                                                            data-approve-url="{{ route('admin.invoices.approve-proof', $invoice->id) }}"
                                                            data-reject-url="{{ route('admin.invoices.reject-proof', $invoice->id) }}"
                                                            title="{{ __('Verify Payment Proof') }}">
                                                            <i class="fas fa-file-invoice-dollar"></i>
                                                        </button>
                                                    @endif

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

    <!-- Verify Slip Modal -->
    <div class="modal fade" id="verifySlipModal" tabindex="-1" role="dialog" aria-labelledby="verifySlipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="verifySlipModalLabel"><i class="fas fa-shield-alt text-warning mr-2"></i> {{ __('Verify Offline Payment Slip') }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Details -->
                        <div class="col-md-6 border-right border-secondary">
                            <h6 class="font-weight-bold text-info border-bottom border-secondary pb-2 mb-3">{{ __('Transaction Information') }}</h6>
                            <table class="table table-borderless table-dark table-sm small">
                                <tr>
                                    <td style="width: 35%;"><strong>{{ __('Invoice:') }}</strong></td>
                                    <td id="modal-invoice-number"></td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('Client:') }}</strong></td>
                                    <td id="modal-client-name"></td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('Amount:') }}</strong></td>
                                    <td id="modal-invoice-amount" class="text-success font-weight-bold"></td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('Method:') }}</strong></td>
                                    <td id="modal-payment-method"></td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('Reference:') }}</strong></td>
                                    <td id="modal-payment-reference" class="text-warning"></td>
                                </tr>
                            </table>
                            <div class="mt-3">
                                <strong>{{ __('Client Notes:') }}</strong>
                                <p id="modal-payment-notes" class="bg-secondary p-2 rounded text-white small mt-1" style="white-space: pre-line;"></p>
                            </div>

                            <!-- Reject Reason Form (Collapsed by default) -->
                            <div class="collapse mt-4" id="rejectReasonCollapse">
                                <form id="reject-proof-form" method="POST" action="">
                                    @csrf
                                    <div class="form-group mb-2">
                                        <label for="rejection_reason" class="small text-danger"><strong>{{ __('Reason for Rejection') }} <span class="text-white">*</span></strong></label>
                                        <textarea name="rejection_reason" id="rejection_reason" rows="2" class="form-control form-control-sm bg-dark text-white border-danger" required placeholder="e.g. Transaction reference not found in bank ledger."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-xs btn-block font-weight-bold py-2"><i class="fas fa-times-circle mr-1"></i> {{ __('Confirm Rejection & Reset Invoice') }}</button>
                                </form>
                            </div>
                        </div>

                        <!-- Image/PDF Document Preview -->
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-info border-bottom border-secondary pb-2 mb-3">{{ __('Receipt Document Preview') }}</h6>
                            <div id="modal-slip-preview-container" class="bg-secondary d-flex align-items-center justify-content-center rounded overflow-hidden" style="height: 250px;">
                                <iframe id="modal-slip-iframe" src="" style="width: 100%; height: 100%; border: none; display: none;"></iframe>
                                <img id="modal-slip-img" src="" class="img-fluid" style="max-height: 100%; display: none; object-fit: contain;">
                            </div>
                            <div class="text-center mt-2">
                                <a id="modal-slip-download" href="" target="_blank" class="btn btn-outline-info btn-xs px-3"><i class="fas fa-download mr-1"></i> {{ __('Open Document in New Tab') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close') }}</button>
                    <div class="d-flex">
                        <button type="button" class="btn btn-danger btn-sm mr-2 font-weight-bold" id="reject-btn-trigger"><i class="fas fa-ban mr-1"></i> {{ __('Reject Proof') }}</button>
                        <form id="approve-proof-form" method="POST" action="" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm font-weight-bold"><i class="fas fa-check-circle mr-1"></i> {{ __('Approve & Mark Paid') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
<script>
    (function($) {
        "use strict";
        
        $('.verify-slip-btn').on('click', function() {
            const id = $(this).data('id');
            const number = $(this).data('number');
            const client = $(this).data('client');
            const amount = $(this).data('amount');
            const method = $(this).data('method');
            const reference = $(this).data('reference');
            const notes = $(this).data('notes');
            const slip = $(this).data('slip');
            const filetype = $(this).data('filetype');
            const approveUrl = $(this).data('approve-url');
            const rejectUrl = $(this).data('reject-url');
            
            // Set details
            $('#modal-invoice-number').text(number);
            $('#modal-client-name').text(client);
            $('#modal-invoice-amount').text(amount);
            $('#modal-payment-method').text(method);
            $('#modal-payment-reference').text(reference);
            $('#modal-payment-notes').text(notes);
            
            // Set forms
            $('#approve-proof-form').attr('action', approveUrl);
            $('#reject-proof-form').attr('action', rejectUrl);
            
            // Set preview
            $('#modal-slip-download').attr('href', slip);
            if (filetype === 'pdf') {
                $('#modal-slip-img').hide();
                $('#modal-slip-iframe').attr('src', slip).show();
            } else {
                $('#modal-slip-iframe').hide();
                $('#modal-slip-img').attr('src', slip).show();
            }
            
            // Reset collapse
            $('#rejectReasonCollapse').collapse('hide');
            $('#rejection_reason').val('');
            
            // Show modal
            $('#verifySlipModal').modal('show');
        });
        
        $('#reject-btn-trigger').on('click', function() {
            $('#rejectReasonCollapse').collapse('toggle');
        });
        
        // Clean on close
        $('#verifySlipModal').on('hidden.bs.modal', function () {
            $('#modal-slip-iframe').attr('src', '');
            $('#modal-slip-img').attr('src', '');
        });
        
    })(jQuery);
</script>
@endsection
