@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .kyc-config-card {
        background: #161a24;
        border: 1px solid #283244;
        border-radius: 12px;
        overflow: hidden;
    }
    .kyc-config-header {
        background: #11151e;
        border-bottom: 1px solid #283244;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .kyc-config-header h6 {
        margin: 0;
        font-weight: 700;
        color: #f1f5f9;
        font-size: 14.5px;
    }
    .kyc-config-body {
        padding: 24px;
    }
    .kyc-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 6px;
        display: block;
    }
    .kyc-input {
        background: #0d1017 !important;
        border: 1px solid #283244 !important;
        color: #f1f5f9 !important;
        border-radius: 8px !important;
        font-size: 13px !important;
    }
    .kyc-input:focus {
        border-color: #fecc56 !important;
        box-shadow: 0 0 0 2px rgba(254, 204, 86, 0.18) !important;
    }
    .doc-row-card {
        background: #10131b;
        border: 1px solid #232c3d;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 14px;
        position: relative;
        transition: all 0.2s ease;
    }
    .doc-row-card:hover {
        border-color: #374358;
    }
    .btn-gold-save {
        background: linear-gradient(135deg, #e8820c, #c46e08);
        color: #ffffff !important;
        border: none;
        border-radius: 8px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-gold-save:hover {
        opacity: 0.92;
        transform: translateY(-1px);
    }
</style>
@endsection

@section('content')
<div id="wrapper-content">
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark">
                <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                <a class="breadcrumb-item text-white" href="{{ route('admin.kyc.submissions') }}">{{ __('KYC Submissions') }}</a>
                <span class="breadcrumb-item active">{{ __($title) }}</span>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <form action="{{ route('admin.kyc.config.save') }}" method="POST">
        @csrf

        <div class="row">
            <!-- ── Left Column: Form Branding & General Requirements ── -->
            <div class="col-lg-5">
                <div class="kyc-config-card mb-4">
                    <div class="kyc-config-header">
                        <h6><i class="fas fa-sliders-h text-warning mr-2"></i> {{ __('General KYC Portal Settings') }}</h6>
                    </div>
                    <div class="kyc-config-body">
                        <div class="form-group mb-3">
                            <label class="kyc-label">{{ __('Portal KYC Hub Title') }} <span class="text-danger">*</span></label>
                            <input type="text" name="kyc_title" class="form-control kyc-input" value="{{ old('kyc_title', $kycSettings['kyc_title'] ?? '') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="kyc-label">{{ __('Portal KYC Subtitle / Tagline') }}</label>
                            <input type="text" name="kyc_subtitle" class="form-control kyc-input" value="{{ old('kyc_subtitle', $kycSettings['kyc_subtitle'] ?? '') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label class="kyc-label">{{ __('Client Guidance Instructions') }}</label>
                            <textarea name="general_instructions" rows="4" class="form-control kyc-input">{{ old('general_instructions', $kycSettings['general_instructions'] ?? '') }}</textarea>
                            <small class="text-muted">{{ __('Displayed to clients at the top of their verification hub.') }}</small>
                        </div>

                        <div class="form-group mb-0 pt-3 border-top border-secondary">
                            <label class="kyc-label mb-2">{{ __('Required Client Profile Fields') }}</label>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" name="require_address" id="require_address" class="custom-control-input" value="1" @checked($kycSettings['require_address'] ?? true)>
                                <label class="custom-control-label text-light" for="require_address">{{ __('Mandatory Physical Residence Address') }}</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" name="require_phone" id="require_phone" class="custom-control-input" value="1" @checked($kycSettings['require_phone'] ?? true)>
                                <label class="custom-control-label text-light" for="require_phone">{{ __('Mandatory Phone Verification') }}</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="require_dob" id="require_dob" class="custom-control-input" value="1" @checked($kycSettings['require_dob'] ?? true)>
                                <label class="custom-control-label text-light" for="require_dob">{{ __('Mandatory Date of Birth / Entity Registration') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Right Column: Document Requirement Definitions ── -->
            <div class="col-lg-7">
                <div class="kyc-config-card mb-4">
                    <div class="kyc-config-header">
                        <h6><i class="fas fa-file-invoice text-warning mr-2"></i> {{ __('Custom Document Requests & Rules') }}</h6>
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="addDocumentRow()">
                            <i class="fas fa-plus mr-1"></i> {{ __('+ Add Document Requirement') }}
                        </button>
                    </div>

                    <div class="kyc-config-body">
                        <p class="text-muted small mb-3">
                            {{ __('Define which documents are requested from clients based on case type or jurisdiction. Clients will see upload boxes corresponding to these items.') }}
                        </p>

                        <div id="documentRowsContainer">
                            @foreach($kycSettings['document_types'] ?? [] as $idx => $doc)
                            <div class="doc-row-card" id="docRow_{{ $idx }}">
                                <input type="hidden" name="doc_keys[]" value="{{ $doc['key'] ?? 'doc_'.$idx }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge badge-secondary font-weight-bold" style="font-size: 11px;">#{{ $idx + 1 }} {{ __('Document Requirement') }}</span>
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeDocRow('docRow_{{ $idx }}')" title="{{ __('Remove') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-7 form-group mb-2">
                                        <label class="kyc-label">{{ __('Document Title / Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="doc_names[]" class="form-control kyc-input" value="{{ $doc['name'] }}" required placeholder="e.g. Government Passport, Proof of Address">
                                    </div>
                                    <div class="col-md-5 form-group mb-2">
                                        <label class="kyc-label">{{ __('Applicable Case Types') }}</label>
                                        <input type="text" name="doc_case_types[]" class="form-control kyc-input" value="{{ $doc['case_types'] ?? 'All Cases' }}" placeholder="e.g. All Cases, Tax Dispute, Asset Recovery">
                                    </div>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="kyc-label">{{ __('Instructions for Client') }}</label>
                                    <input type="text" name="doc_descriptions[]" class="form-control kyc-input" value="{{ $doc['description'] ?? '' }}" placeholder="e.g. Must be unexpired and show full 4 corners...">
                                </div>

                                <div class="custom-control custom-checkbox mt-2">
                                    <input type="checkbox" name="doc_required[{{ $idx }}]" id="docReq_{{ $idx }}" class="custom-control-input" value="1" @checked($doc['required'] ?? false)>
                                    <label class="custom-control-label text-warning font-weight-semibold" for="docReq_{{ $idx }}" style="font-size: 12.5px;">
                                        <i class="fas fa-asterisk text-danger mr-1" style="font-size: 9px;"></i> {{ __('Mandatory to Complete Verification') }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="text-right mb-5">
                    <button type="submit" class="btn-gold-save">
                        <i class="fas fa-save"></i> {{ __('Save KYC Configuration') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('page-script')
<script>
var rowCounter = {{ count($kycSettings['document_types'] ?? []) }};

function addDocumentRow() {
    rowCounter++;
    var c = rowCounter;
    var html = `
    <div class="doc-row-card" id="docRow_${c}">
        <input type="hidden" name="doc_keys[]" value="doc_${c}">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge badge-warning text-dark font-weight-bold" style="font-size: 11px;">#${c} {{ __('New Requirement') }}</span>
            <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeDocRow('docRow_${c}')">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>

        <div class="row mb-2">
            <div class="col-md-7 form-group mb-2">
                <label class="kyc-label">{{ __('Document Title / Name') }} <span class="text-danger">*</span></label>
                <input type="text" name="doc_names[]" class="form-control kyc-input" required placeholder="e.g. Bank Statement / Proof of Funds">
            </div>
            <div class="col-md-5 form-group mb-2">
                <label class="kyc-label">{{ __('Applicable Case Types') }}</label>
                <input type="text" name="doc_case_types[]" class="form-control kyc-input" value="All Cases" placeholder="e.g. Asset Recovery, Tax Dispute">
            </div>
        </div>

        <div class="form-group mb-2">
            <label class="kyc-label">{{ __('Instructions for Client') }}</label>
            <input type="text" name="doc_descriptions[]" class="form-control kyc-input" placeholder="e.g. PDF statement issued within last 90 days...">
        </div>

        <div class="custom-control custom-checkbox mt-2">
            <input type="checkbox" name="doc_required[${c}]" id="docReq_${c}" class="custom-control-input" value="1" checked>
            <label class="custom-control-label text-warning font-weight-semibold" for="docReq_${c}" style="font-size: 12.5px;">
                <i class="fas fa-asterisk text-danger mr-1" style="font-size: 9px;"></i> {{ __('Mandatory to Complete Verification') }}
            </label>
        </div>
    </div>
    `;
    document.getElementById('documentRowsContainer').insertAdjacentHTML('beforeend', html);
}

function removeDocRow(id) {
    var el = document.getElementById(id);
    if (el) el.remove();
}
</script>
@endsection
