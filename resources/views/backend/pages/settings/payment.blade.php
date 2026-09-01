@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('content')
<div id="wrapper-content">
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark">
                <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                <a class="breadcrumb-item text-white" href="{{ route('admin.settings.general') }}">{{ __('Settings') }}</a>
                <span class="breadcrumb-item active">{{ __($title) }}</span>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show font-weight-bold" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card card-dark bg-dark border border-secondary shadow-lg">
                <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center">
                    <h6 class="card-title text-warning mb-0 font-weight-bold">
                        <i class="fas fa-university mr-2"></i> {{ __('Client Retainer & Settlement Depository Settings') }}
                    </h6>
                    <span class="badge badge-success px-2 py-1">{{ __('Active Depository') }}</span>
                </div>

                <form action="{{ route('admin.settings.payment-save') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="alert alert-info bg-dark border-info text-white small mb-4">
                            <i class="fas fa-info-circle mr-1"></i> {{ __('These depository payment details and late fee policies will automatically apply to client retainer invoices and the client payment settlement hub. Individual invoices can also override these defaults if specific case escrow instructions are required.') }}
                        </div>

                        <!-- 1. BANK WIRE INSTRUCTIONS -->
                        <h6 class="text-warning font-weight-bold border-bottom border-secondary pb-2 mb-3">
                            <i class="fas fa-money-check-alt mr-2"></i> {{ __('1. Bank Wire & Escrow Trust Account') }}
                        </h6>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="text-light small font-weight-bold">{{ __('Beneficiary / Trust Account Name:') }} <span class="text-danger">*</span></label>
                                <input type="text" name="beneficiary" class="form-control bg-dark text-white border-secondary" value="{{ old('beneficiary', $paymentSettings['beneficiary'] ?? '') }}" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="text-light small font-weight-bold">{{ __('Receiving Bank Name:') }} <span class="text-danger">*</span></label>
                                <input type="text" name="bank_name" class="form-control bg-dark text-white border-secondary" value="{{ old('bank_name', $paymentSettings['bank_name'] ?? '') }}" required>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="text-light small font-weight-bold">{{ __('Routing Number (ABA / FedWire):') }} <span class="text-danger">*</span></label>
                                <input type="text" name="routing_number" class="form-control bg-dark text-white border-secondary font-weight-bold text-warning" value="{{ old('routing_number', $paymentSettings['routing_number'] ?? '') }}" required>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="text-light small font-weight-bold">{{ __('SWIFT / BIC Code:') }} <span class="text-danger">*</span></label>
                                <input type="text" name="swift_code" class="form-control bg-dark text-white border-secondary font-weight-bold text-warning" value="{{ old('swift_code', $paymentSettings['swift_code'] ?? '') }}" required>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="text-light small font-weight-bold">{{ __('Account Number / IBAN:') }} <span class="text-danger">*</span></label>
                                <input type="text" name="account_number" class="form-control bg-dark text-white border-secondary font-weight-bold" value="{{ old('account_number', $paymentSettings['account_number'] ?? '') }}" required>
                            </div>
                            <div class="col-12 form-group mb-4">
                                <label class="text-light small font-weight-bold">{{ __('Special Wire Instructions & Memo Notice:') }}</label>
                                <textarea name="wire_instructions" class="form-control bg-dark text-white border-secondary" rows="2">{{ old('wire_instructions', $paymentSettings['wire_instructions'] ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- 2. CRYPTOCURRENCY SETTLEMENT DEPOSITORY -->
                        <h6 class="text-warning font-weight-bold border-bottom border-secondary pb-2 mb-3 mt-2">
                            <i class="fab fa-bitcoin mr-2"></i> {{ __('2. Cryptocurrency Settlement Depository (USDT & Bitcoin)') }}
                        </h6>
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label class="text-light small font-weight-bold">{{ __('USDT Depository Address (TRC-20):') }}</label>
                                    <input type="text" name="crypto_usdt_address" id="admin_usdt_input" class="form-control bg-dark text-white border-secondary font-weight-bold" value="{{ old('crypto_usdt_address', $paymentSettings['crypto_usdt_address'] ?? '') }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="text-light small font-weight-bold">{{ __('Bitcoin (BTC) Depository Address:') }}</label>
                                    <input type="text" name="crypto_btc_address" id="admin_btc_input" class="form-control bg-dark text-white border-secondary font-weight-bold" value="{{ old('crypto_btc_address', $paymentSettings['crypto_btc_address'] ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <small class="text-muted d-block mb-2 font-weight-bold">{{ __('Auto-Generated QR Code Preview:') }}</small>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=130x130&data={{ urlencode($paymentSettings['crypto_usdt_address'] ?? '') }}" id="admin_qr_preview" class="img-thumbnail bg-dark border-secondary p-1" style="width: 130px; height: 130px;">
                            </div>
                        </div>

                        <!-- 3. ACH & DIRECT DEPOSIT -->
                        <h6 class="text-warning font-weight-bold border-bottom border-secondary pb-2 mb-3 mt-4">
                            <i class="fas fa-university mr-2"></i> {{ __('3. ACH & Domestic Direct Deposit') }}
                        </h6>
                        <div class="form-group mb-4">
                            <label class="text-light small font-weight-bold">{{ __('ACH / Check Deposit Information:') }}</label>
                            <input type="text" name="ach_details" class="form-control bg-dark text-white border-secondary" value="{{ old('ach_details', $paymentSettings['ach_details'] ?? '') }}" placeholder="e.g. JPMorgan Chase ACH - Routing: 021000021">
                        </div>

                        <!-- 4. LATE FEE POLICY & SCHEDULE -->
                        <h6 class="text-warning font-weight-bold border-bottom border-secondary pb-2 mb-3 mt-4">
                            <i class="fas fa-hourglass-half mr-2"></i> {{ __('4. Retainer Late Fee Policy & Grace Schedule') }}
                        </h6>
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="late_fee_enabled" class="custom-control-input" id="late_fee_enabled" value="1" {{ !empty($paymentSettings['late_fee_enabled']) ? 'checked' : '' }}>
                                    <label class="custom-control-label text-white font-weight-bold" for="late_fee_enabled">{{ __('Enable Late Fee Charges') }}</label>
                                </div>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="text-light small font-weight-bold">{{ __('Late Fee Surcharge (%):') }}</label>
                                <div class="input-group">
                                    <input type="number" name="late_fee_percent" class="form-control bg-dark text-white border-secondary text-right font-weight-bold" value="{{ old('late_fee_percent', $paymentSettings['late_fee_percent'] ?? 5) }}" min="0" max="100" step="0.5">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-secondary text-white border-secondary">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="text-light small font-weight-bold">{{ __('Grace Period (Days):') }}</label>
                                <div class="input-group">
                                    <input type="number" name="grace_period_days" class="form-control bg-dark text-white border-secondary text-right font-weight-bold" value="{{ old('grace_period_days', $paymentSettings['grace_period_days'] ?? 7) }}" min="0" max="60">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-secondary text-white border-secondary">{{ __('Days') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4 pt-3 border-top border-secondary">
                            <button type="submit" class="btn btn-warning font-weight-bold text-dark px-4 py-2">
                                <i class="fas fa-save mr-1"></i> {{ __('Save Payment & Depository Settings') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
