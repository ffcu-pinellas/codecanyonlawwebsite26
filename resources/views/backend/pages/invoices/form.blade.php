@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('content')
    <div id="wrapper-content">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                    <a class="breadcrumb-item text-white" href="{{ route('admin.invoices.index') }}">{{ __('Client Invoices') }}</a>
                    <span class="breadcrumb-item active">{{ __($title) }}</span>
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

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card card-dark bg-dark">
                    <div class="card-header">
                        <h6 class="card-title">{{ $invoice ? __('Edit Invoice #') . $invoice->invoice_number : __('Generate New Invoice') }}</h6>
                    </div>

                    <div class="card-body">
                        <!-- Tips & Quick Help Panel -->
                        <div class="alert alert-info border-info bg-dark text-white rounded mb-4" role="alert" style="border-left: 5px solid #17a2b8;">
                            <h6 class="alert-heading text-info font-weight-bold mb-2"><i class="fas fa-info-circle mr-2"></i>{{ __('Invoicing System Guide & Suggested Tips') }}</h6>
                            <ul class="mb-0 pl-3 small text-muted">
                                <li class="mb-1"><strong class="text-white">{{ __('Target Recipient:') }}</strong> {{ __('You can issue invoices to either external Clients or internal Staff members. Select them using the dropdown menu below (annotated with [Client] or [Staff]).') }}</li>
                                <li class="mb-1"><strong class="text-white">{{ __('Configurable Items Builder:') }}</strong> {{ __('Add itemized list entries using the "Add Custom Item Row" button. Quantities and rates are dynamically calculated and summed.') }}</li>
                                <li class="mb-1"><strong class="text-white">{{ __('Time-saving Templates:') }}</strong> {{ __('Use the "Suggested Templates" selector at the top of the items builder to instantly populate standard fees like CPA consultations, retainer representation, hourly logs, or tax compliance filing.') }}</li>
                                <li><strong class="text-white">{{ __('Professional Standard Email:') }}</strong> {{ __('Check the "Email styled invoice statement" option at the bottom. The system will compile the items into a clean, professional, branded HTML statement table sent directly to the recipient\'s email.') }}</li>
                            </ul>
                        </div>

                        <form action="{{ $invoice ? route('admin.invoices.update', $invoice->id) : route('admin.invoices.store') }}" method="POST" id="invoiceForm">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="client_id">{{ __('Client / Staff Recipient') }} <span class="text-danger">*</span></label>
                                    <select name="client_id" id="client_id" class="form-control bg-dark text-white border-secondary" required>
                                        <option value="">-- {{ __('Select Recipient') }} --</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" @if(old('client_id', $invoice ? $invoice->client_id : '') == $client->id) selected @endif>
                                                {{ $client->hasRole('staff') ? '[Staff] ' : '[Client] ' }}{{ $client->name }} ({{ $client->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="case_id">{{ __('Linked Case') }}</label>
                                    <select name="case_id" id="case_id" class="form-control bg-dark text-white border-secondary">
                                        <option value="">-- {{ __('No Case Linked') }} --</option>
                                        @foreach($cases as $caseItem)
                                            <option value="{{ $caseItem->id }}" @if(old('case_id', $invoice ? $invoice->case_id : '') == $caseItem->id) selected @endif>{{ $caseItem->case_number }} - {{ $caseItem->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('case_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Interactive Line Item Builder Section -->
                            <div class="border border-secondary rounded p-3 mb-4 mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-info font-weight-bold mb-0"><i class="fas fa-list-ol mr-2"></i>{{ __('Invoice Line Items Builder') }}</h6>
                                    
                                    <!-- Item Template Selector Dropdown -->
                                    <div class="form-inline">
                                        <label for="template_selector" class="mr-2 text-muted small">{{ __('Suggested Templates') }}</label>
                                        <select id="template_selector" class="form-control form-control-sm bg-dark text-white border-secondary">
                                            <option value="">-- {{ __('Select Suggested Option') }} --</option>
                                            <option value="consultation" data-desc="Professional CPA Consultation Call & Legal Advising" data-rate="150" data-qty="1">{{ __('CPA Consultation Call ($150)') }}</option>
                                            <option value="retainer" data-desc="Legal Representation Retainer Downpayment Fee" data-rate="1500" data-qty="1">{{ __('Representation Retainer ($1,500)') }}</option>
                                            <option value="hourly" data-desc="Hourly Accounting & Case File Services" data-rate="120" data-qty="5">{{ __('Hourly Accounting/Legal Services ($120/hr)') }}</option>
                                            <option value="tax_prep" data-desc="IRS Corporate & Individual Tax Compliance Form Prep" data-rate="650" data-qty="1">{{ __('Tax Form Prep & Filing ($650)') }}</option>
                                            <option value="poa_doc" data-desc="Durable General Power of Attorney Drafting Fee" data-rate="250" data-qty="1">{{ __('Power of Attorney Prep ($250)') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-dark small mb-2" id="lineItemsTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Item Description') }}</th>
                                                <th style="width: 100px;">{{ __('Quantity') }}</th>
                                                <th style="width: 130px;">{{ __('Rate ($)') }}</th>
                                                <th style="width: 130px;">{{ __('Total ($)') }}</th>
                                                <th style="width: 60px;">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="lineItemsBody">
                                            <!-- Rows added dynamically via Javascript -->
                                        </tbody>
                                    </table>
                                </div>

                                <button type="button" class="btn btn-outline-info btn-xs" id="addLineItemBtn"><i class="fas fa-plus mr-1"></i> {{ __('Add Custom Item Row') }}</button>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="amount">{{ __('Calculated Total Amount ($)') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="amount" step="0.01" min="0.01" class="form-control bg-dark text-white border-secondary font-weight-bold text-info" required value="{{ old('amount', $invoice ? $invoice->amount : '0.00') }}" readonly>
                                    @error('amount') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="due_date">{{ __('Due Date') }} <span class="text-danger">*</span></label>
                                    <input type="date" name="due_date" id="due_date" class="form-control bg-dark text-white border-secondary" required value="{{ old('due_date', $invoice ? ($invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') : '') }}">
                                    @error('due_date') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="status">{{ __('Payment Status') }} <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control bg-dark text-white border-secondary" required>
                                        <option value="unpaid" @if(old('status', $invoice ? $invoice->status : 'unpaid') == 'unpaid') selected @endif>{{ __('Unpaid') }}</option>
                                        <option value="paid" @if(old('status', $invoice ? $invoice->status : 'unpaid') == 'paid') selected @endif>{{ __('Paid') }}</option>
                                        <option value="cancelled" @if(old('status', $invoice ? $invoice->status : 'unpaid') == 'cancelled') selected @endif>{{ __('Cancelled') }}</option>
                                    </select>
                                    @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Penalty Settings / Late Fees (IFW EXACT REPLICA) -->
                            <div class="bg-dark p-3 rounded mb-4 border border-warning">
                                <h6 class="text-warning font-weight-bold mb-3"><i class="fas fa-exclamation-triangle mr-2"></i>{{ __('Overdue Penalty / Late Fee Settings') }}</h6>
                                
                                <div class="mb-3 d-flex align-items-center" style="gap: 8px;">
                                    <input type="checkbox" id="lateFeeEnabled" name="late_fee_enabled" value="1" onchange="toggleLateFeeSection(this)" style="width: 18px; height: 18px; cursor: pointer; accent-color: #fecc56;" {{ old('late_fee_enabled', $invoice ? $invoice->late_fee_enabled : 0) ? 'checked' : '' }}>
                                    <label class="text-white font-weight-bold mb-0" for="lateFeeEnabled" style="cursor: pointer;">{{ __('Enable Automated Late Fee Penalty') }}</label>
                                </div>
                                
                                <div id="lateFeeOptions" style="{{ old('late_fee_enabled', $invoice ? $invoice->late_fee_enabled : 0) ? '' : 'display: none;' }}">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <label class="small text-light font-weight-bold">{{ __('Interval Type') }}</label>
                                            <select name="late_fee_type" class="form-control form-control-sm bg-dark text-white border-secondary">
                                                <option value="hourly" {{ old('late_fee_type', $invoice ? $invoice->late_fee_type : 'daily') === 'hourly' ? 'selected' : '' }}>{{ __('Hourly Penalty') }}</option>
                                                <option value="daily" {{ old('late_fee_type', $invoice ? $invoice->late_fee_type : 'daily') === 'daily' ? 'selected' : '' }}>{{ __('Daily Penalty') }}</option>
                                                <option value="weekly" {{ old('late_fee_type', $invoice ? $invoice->late_fee_type : 'daily') === 'weekly' ? 'selected' : '' }}>{{ __('Weekly Penalty') }}</option>
                                                <option value="monthly" {{ old('late_fee_type', $invoice ? $invoice->late_fee_type : 'daily') === 'monthly' ? 'selected' : '' }}>{{ __('Monthly Penalty') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="small text-light font-weight-bold">{{ __('Value Type') }}</label>
                                            <select name="late_fee_is_percentage" class="form-control form-control-sm bg-dark text-white border-secondary">
                                                <option value="0" {{ old('late_fee_is_percentage', $invoice ? $invoice->late_fee_is_percentage : 0) == 0 ? 'selected' : '' }}>{{ __('Fixed Amount ($)') }}</option>
                                                <option value="1" {{ old('late_fee_is_percentage', $invoice ? $invoice->late_fee_is_percentage : 0) == 1 ? 'selected' : '' }}>{{ __('Percentage (%)') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="small text-light font-weight-bold">{{ __('Penalty Value (Rate)') }}</label>
                                            <input type="number" name="late_fee_amount" step="0.01" value="{{ old('late_fee_amount', $invoice ? $invoice->late_fee_amount : 50.00) }}" class="form-control form-control-sm bg-dark text-white border-secondary">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="small text-light font-weight-bold">{{ __('Penalty Start Date') }}</label>
                                            <input type="date" name="late_fee_start_date" class="form-control form-control-sm bg-dark text-white border-secondary" value="{{ old('late_fee_start_date', $invoice && $invoice->late_fee_start_date ? $invoice->late_fee_start_date->format('Y-m-d') : ($invoice && $invoice->due_date ? $invoice->due_date->format('Y-m-d') : date('Y-m-d', strtotime('+14 days')))) }}">
                                            <small class="text-muted" style="font-size:9px;">{{ __('Defaults to due date if left blank.') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 pt-2 border-top border-secondary">
                                    <label class="small text-light font-weight-bold">{{ __('Custom Wire / Crypto Payment Instructions (Optional Override):') }}</label>
                                    <textarea name="payment_info" class="form-control form-control-sm bg-dark text-white border-secondary" rows="2" placeholder="Leave blank to use default Escrow Depository, or specify custom Wire / Crypto details...">{{ old('payment_info', $invoice ? ($invoice->payment_info ?? '') : '') }}</textarea>
                                </div>
                            </div>
                            <script>
                            function toggleLateFeeSection(el) {
                                document.getElementById('lateFeeOptions').style.display = el.checked ? 'block' : 'none';
                            }
                            </script>

                            <!-- Hidden Textarea that stores formatted description -->
                            <input type="hidden" name="description" id="description" value="{{ old('description', $invoice ? $invoice->description : '') }}">

                            <div class="form-group mb-4">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" name="send_email" id="send_email" class="custom-control-input" value="1" checked>
                                    <label class="custom-control-label text-warning font-weight-semibold" for="send_email">
                                        <i class="fas fa-paper-plane mr-1"></i> {{ __('Email styled invoice statement directly to client upon save') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mt-4 pt-2 border-top border-secondary">
                                <button type="submit" class="btn btn-primary btn-sm px-4" id="saveInvoiceBtn"><i class="fas fa-save mr-1"></i> {{ __('Save & Process Invoice') }}</button>
                                <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-sm ml-2">{{ __('Cancel') }}</a>
                            </div>
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

        // Parse pre-existing items if editing
        const initialDescription = $('#description').val();
        
        // Setup Line Item Builder
        function addRow(desc = '', qty = 1, rate = 0) {
            const rowId = 'item_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5);
            const rateFormatted = parseFloat(rate).toFixed(2);
            const totalFormatted = (qty * rate).toFixed(2);

            const html = `
                <tr id="${rowId}" class="item-row">
                    <td>
                        <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary item-desc" value="${desc}" required placeholder="e.g. Consultation Services">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm bg-dark text-white border-secondary text-center item-qty" value="${qty}" min="1" step="1" required>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm bg-dark text-white border-secondary text-right item-rate" value="${rateFormatted}" min="0" step="0.01" required>
                    </td>
                    <td class="text-right align-middle font-weight-bold text-info item-total-cell">$${totalFormatted}</td>
                    <td class="text-center align-middle">
                        <button type="button" class="btn btn-outline-danger btn-xs delete-row-btn"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;

            $('#lineItemsBody').append(html);
            calculateTotals();
        }

        // Calculate and sum all rows
        function calculateTotals() {
            let grandTotal = 0;
            $('.item-row').each(function() {
                const qty = parseFloat($(this).find('.item-qty').val()) || 0;
                const rate = parseFloat($(this).find('.item-rate').val()) || 0;
                const total = qty * rate;
                $(this).find('.item-total-cell').text('$' + total.toFixed(2));
                grandTotal += total;
            });
            $('#amount').val(grandTotal.toFixed(2));
        }

        // Compile items into plain text table representation for saving in description field
        function compileDescription() {
            let lines = [];
            $('.item-row').each(function() {
                const desc = $(this).find('.item-desc').val().trim();
                const qty = $(this).find('.item-qty').val();
                const rate = parseFloat($(this).find('.item-rate').val()).toFixed(2);
                const total = (qty * rate).toFixed(2);

                if (desc) {
                    lines.push(`${desc} (Qty: ${qty} @ $${rate}/ea) : $${total}`);
                }
            });
            $('#description').val(lines.join("\n"));
        }

        // Initialize rows
        if (initialDescription) {
            // Try to parse existing text representation
            const lines = initialDescription.split("\n");
            let parsed = false;
            lines.forEach(line => {
                // Regex matches: Description (Qty: X @ $Y/ea) : $Z
                const match = line.match(/^(.+?)\s*\(Qty:\s*(\d+)\s*@\s*\$(.+?)\/ea\)\s*:\s*\$(.+)$/);
                if (match) {
                    addRow(match[1], parseInt(match[2]), parseFloat(match[3]));
                    parsed = true;
                }
            });
            if (!parsed && initialDescription.trim()) {
                // Fallback: put the whole description in one row
                addRow(initialDescription.trim(), 1, parseFloat($('#amount').val()) || 0);
            }
        } else {
            // Add one default empty row
            addRow('', 1, 0);
        }

        // Event listeners
        $('#addLineItemBtn').on('click', function() {
            addRow('', 1, 0);
        });

        $(document).on('click', '.delete-row-btn', function() {
            $(this).closest('tr').remove();
            calculateTotals();
            if ($('.item-row').length === 0) {
                addRow('', 1, 0);
            }
        });

        $(document).on('input change', '.item-qty, .item-rate', function() {
            calculateTotals();
        });

        // Add from Suggested Templates
        $('#template_selector').on('change', function() {
            const selected = $(this).find('option:selected');
            if (selected.val()) {
                const desc = selected.data('desc');
                const rate = parseFloat(selected.data('rate'));
                const qty = parseInt(selected.data('qty'));
                
                // If the first row is empty, replace it, otherwise add new
                const firstRow = $('.item-row').first();
                const firstDesc = firstRow.find('.item-desc').val().trim();
                const firstRate = parseFloat(firstRow.find('.item-rate').val());

                if (!firstDesc && firstRate === 0) {
                    firstRow.find('.item-desc').val(desc);
                    firstRow.find('.item-qty').val(qty);
                    firstRow.find('.item-rate').val(rate.toFixed(2));
                    calculateTotals();
                } else {
                    addRow(desc, qty, rate);
                }
                $(this).val(''); // reset selector
            }
        });

        // Intercept form submit to compile items
        $('#invoiceForm').on('submit', function() {
            compileDescription();
            return true;
        });

    })(jQuery);
</script>
@endsection
