@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .payment-preference-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        padding: 30px;
    }
    .custom-control-label {
        font-weight: 600;
        color: #2c3e50;
    }
    .upload-container {
        border: 2px dashed #bdc3c7;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.2s;
    }
    .upload-container:hover {
        border-color: #3498db;
        background: #f0f8ff;
    }
    .file-preview {
        font-size: 0.8rem;
        color: #7f8c8d;
        margin-top: 5px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="payment-preference-card">
                <div class="d-flex align-items-center mb-4 border-bottom pb-3">
                    <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary btn-sm mr-3" style="border-radius: 50%; width: 32px; height: 32px; padding: 4px;"><i class="fas fa-arrow-left"></i></a>
                    <h5 class="mb-0" style="font-weight: 700; color: #2c3e50;">{{ __('Payment Preferences & Direct Deposit Setup') }}</h5>
                </div>

                <form action="{{ route('staff.payment-method') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark d-block mb-3">{{ __('Preferred Payment Method') }}</label>
                        
                        <div class="custom-control custom-radio mb-3">
                            <input type="radio" id="method_paycheck" name="payment_method" value="paycheck" class="custom-control-input" @if($staffDetail->payment_method === 'paycheck') checked @endif onclick="toggleMethodFields()">
                            <label class="custom-control-label" for="method_paycheck">
                                <i class="fas fa-money-check text-muted mr-2"></i> {{ __('Paper Check') }}
                                <small class="d-block text-muted font-weight-normal">{{ __('Wages paid via standard check delivered to your address.') }}</small>
                            </label>
                        </div>

                        <div class="custom-control custom-radio">
                            <input type="radio" id="method_direct" name="payment_method" value="direct_deposit" class="custom-control-input" @if($staffDetail->payment_method === 'direct_deposit') checked @endif onclick="toggleMethodFields()">
                            <label class="custom-control-label" for="method_direct">
                                <i class="fas fa-university text-muted mr-2"></i> {{ __('Direct Deposit (Recommended)') }}
                                <small class="d-block text-muted font-weight-normal">{{ __('Wages electronically deposited directly into your bank account.') }}</small>
                            </label>
                        </div>
                    </div>

                    <!-- Direct Deposit Upload Fields (Collapsible) -->
                    <div id="direct_deposit_fields" style="display: @if($staffDetail->payment_method === 'direct_deposit') block @else none @endif;">
                        <div class="alert alert-info small mb-4 d-flex justify-content-between align-items-center flex-wrap">
                            <div class="mb-2 mb-md-0" style="max-width: 65%;">
                                <i class="fas fa-info-circle mr-2"></i> {{ __('To activate Direct Deposit, please upload a scanned copy of a voided check and/or your completed Direct Deposit Authorization form.') }}
                            </div>
                            <div>
                                <a href="{{ route('staff.direct-deposit-form.download') }}" target="_blank" class="btn btn-sm btn-light font-weight-bold text-primary shadow-sm">
                                    <i class="fas fa-download mr-1"></i> {{ __('Download Printable Authorization Form') }}
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Voided Check Upload -->
                            <div class="col-md-6 mb-4">
                                <label class="font-weight-bold small text-muted text-uppercase mb-2">{{ __('Upload Voided Check') }}</label>
                                <div class="upload-container" onclick="document.getElementById('void_check').click()">
                                    <i class="fas fa-file-invoice fa-2x text-muted mb-2"></i>
                                    <h6 class="mb-1 text-dark" style="font-size: 0.9rem;">{{ __('Select File') }}</h6>
                                    <p class="text-muted small mb-0">{{ __('PDF or Image, max 5MB') }}</p>
                                    <input type="file" id="void_check" name="void_check" class="d-none" onchange="updateFileLabel(this, 'void-check-preview')">
                                    <div class="file-preview font-weight-bold text-success" id="void-check-preview"></div>
                                </div>
                                @if($staffDetail->void_check_path)
                                    <div class="mt-2 text-center">
                                        <span class="text-success small"><i class="fas fa-check-circle"></i> {{ __('Void Check Uploaded') }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Direct Deposit Form Upload -->
                            <div class="col-md-6 mb-4">
                                <label class="font-weight-bold small text-muted text-uppercase mb-2">{{ __('Upload Direct Deposit Form') }}</label>
                                <div class="upload-container" onclick="document.getElementById('direct_deposit_form').click()">
                                    <i class="fas fa-file-signature fa-2x text-muted mb-2"></i>
                                    <h6 class="mb-1 text-dark" style="font-size: 0.9rem;">{{ __('Select File') }}</h6>
                                    <p class="text-muted small mb-0">{{ __('PDF or Image, max 5MB') }}</p>
                                    <input type="file" id="direct_deposit_form" name="direct_deposit_form" class="d-none" onchange="updateFileLabel(this, 'dd-form-preview')">
                                    <div class="file-preview font-weight-bold text-success" id="dd-form-preview"></div>
                                </div>
                                @if($staffDetail->direct_deposit_form_path)
                                    <div class="mt-2 text-center">
                                        <span class="text-success small"><i class="fas fa-check-circle"></i> {{ __('Deposit Form Uploaded') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-4 mt-2">
                        <button type="submit" class="btn btn-primary btn-lg px-4" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none;">
                            {{ __('Save Preferences') }}
                        </button>
                        <a href="{{ route('staff.dashboard') }}" class="btn btn-light btn-lg ml-2">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    function toggleMethodFields() {
        const directRadio = document.getElementById('method_direct');
        const fieldsDiv = document.getElementById('direct_deposit_fields');
        if (directRadio.checked) {
            fieldsDiv.style.display = 'block';
        } else {
            fieldsDiv.style.display = 'none';
        }
    }

    function updateFileLabel(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            preview.textContent = `Selected: ${input.files[0].name}`;
        } else {
            preview.textContent = '';
        }
    }
</script>
@endsection
