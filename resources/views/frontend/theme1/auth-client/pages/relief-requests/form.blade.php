@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'laravel'). ' | '.$title)

@section('page-css')
<style>
    .case-card {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        overflow: hidden;
    }
    .case-card-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        padding: 20px 25px;
        border: none;
    }
    .tip-panel {
        background-color: #fafbfd;
        border-left: 4px solid #1e3c72;
        border-radius: 0 8px 8px 0;
        padding: 15px 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.01);
    }
    .field-tip {
        font-size: 0.8rem;
        color: #5a6b82;
        margin-top: 5px;
        display: block;
    }
    .field-tip i {
        color: #1e3c72;
        margin-right: 4px;
    }
    .custom-file-upload {
        border: 2px dashed #ccd1d9;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        background: #fdfdfd;
        cursor: pointer;
        transition: all 0.3s;
    }
    .custom-file-upload:hover {
        border-color: #1e3c72;
        background-color: #f4f7fa;
    }
    .custom-file-upload i {
        font-size: 2.5rem;
        color: #6c757d;
        margin-bottom: 12px;
        transition: color 0.3s;
    }
    .custom-file-upload:hover i {
        color: #1e3c72;
    }
    .section-num {
        background-color: #e2e8f0;
        color: #4a5568;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        margin-right: 10px;
    }
    .active-section-num {
        background-color: #1e3c72;
        color: white;
    }
    .card-title-text {
        font-weight: 700;
        font-size: 1rem;
        color: #2d3748;
        display: inline-flex;
        align-items: center;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Back to Dashboard -->
    <div class="mb-4">
        <a href="{{ route('client.dashboard') }}" class="text-primary font-weight-bold"><i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Dashboard') }}</a>
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

    <div class="row justify-content-center">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            <div class="card case-card">
                <div class="card-header case-card-header">
                    <h4 class="font-weight-bold text-white mb-1"><i class="fas fa-folder-plus mr-2"></i> {{ __('Open Secure Legal & CPA Representation Case') }}</h4>
                    <p class="text-white-50 mb-0 small">{{ __('Submit notice papers, audit requests, or financial records to start formal CPA / Legal attorney representation.') }}</p>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('client.financial-relief') }}" method="post" enctype="multipart/form-data" id="caseRequestForm">
                        @csrf

                        <!-- Section 1: Contact Details -->
                        <div class="mb-4 border-bottom pb-4">
                            <h5 class="card-title-text mb-3"><span class="section-num active-section-num">1</span> {{ __('Contact & Account Information') }}</h5>
                            <div class="tip-panel small">
                                <strong><i class="fas fa-info-circle text-primary"></i> {{ __('Quick Check:') }}</strong> 
                                {{ __('These details are loaded from your secure account registry. If you need to make corrections, update your phone or mailing address in your Account Profile before continuing.') }}
                            </div>

                            <div class="form-group mb-3">
                                <label for="name" class="font-weight-semibold text-dark small">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" class="form-control text-white bg-dark border-secondary" readonly>
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="email" class="font-weight-semibold text-dark small">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" class="form-control text-white bg-dark border-secondary" readonly>
                                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="phone" class="font-weight-semibold text-dark small">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="phone" value="{{ Auth::user()->phone }}" class="form-control text-white bg-dark border-secondary" readonly>
                                    @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="address" class="font-weight-semibold text-dark small">{{ __('Current Mailing Address') }} <span class="text-danger">*</span></label>
                                <input type="text" name="address" id="address" value="{{ Auth::user()->address }}" class="form-control text-white bg-dark border-secondary" readonly>
                                @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Section 2: Request Scope -->
                        <div class="mb-4 border-bottom pb-4">
                            <h5 class="card-title-text mb-3"><span class="section-num active-section-num">2</span> {{ __('Case Scope & Incident Reason') }}</h5>
                            
                            <div class="form-group mb-3">
                                <label for="reason" class="font-weight-semibold text-dark small">{{ __('Primary Reason for Representation') }} <span class="text-danger">*</span></label>
                                <input type="text" name="reason" id="reason" value="{{ old('reason') }}" class="form-control text-dark bg-white" placeholder="e.g. IRS Tax Audit Representation for Tax Year 2024" required>
                                <span class="field-tip"><i class="fas fa-question-circle"></i> {{ __('Provide a brief header describing your case notice or audit letter.') }}</span>
                                @error('reason') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label for="details" class="font-weight-semibold text-dark small">{{ __('Additional Background & Case Details (Optional)') }}</label>
                                <textarea name="details" id="details" class="form-control text-dark bg-white" rows="4" placeholder="E.g., Received Notice CP2000 on June 2nd, claiming unreported dividend income...">{{ old('details') }}</textarea>
                                <span class="field-tip"><i class="fas fa-question-circle"></i> {{ __('Detail notices, audits, IRS letter codes, tax periods, or court timelines.') }}</span>
                                @error('details') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Section 3: Target Resolution -->
                        <div class="mb-4 border-bottom pb-4">
                            <h5 class="card-title-text mb-3"><span class="section-num active-section-num">3</span> {{ __('Proposed Target Resolution') }}</h5>
                            
                            <div class="form-group mb-0">
                                <label for="offer" class="font-weight-semibold text-dark small">{{ __('Proposed Resolution / Your Target Goal') }} <span class="text-danger">*</span></label>
                                <textarea name="offer" id="offer" class="form-control text-dark bg-white" rows="3" placeholder="E.g., Complete audit defense and reduce liability; or Negotiate Offer in Compromise settlement." required>{{ old('offer') }}</textarea>
                                <span class="field-tip"><i class="fas fa-question-circle"></i> {{ __('Describe what outcome you want us to accomplish (e.g. tax settlement, audit dismissal, contract execution).') }}</span>
                                @error('offer') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Section 4: Notice Vault Upload -->
                        <div class="mb-4">
                            <h5 class="card-title-text mb-3"><span class="section-num active-section-num">4</span> {{ __('Upload Support Documents & IRS Notices') }} <span class="text-danger">*</span></h5>
                            
                            <div class="tip-panel small">
                                <strong><i class="fas fa-shield-alt text-success"></i> {{ __('Vault Security:') }}</strong> 
                                {{ __('All documents uploaded here are encrypted in transit and stored inside our bank-grade secure server container. Only assigned CPA professionals and attorneys can access them.') }}
                            </div>

                             <div class="form-group mb-0">
                                <input type="file" name="files[]" id="caseFile" class="d-none" multiple required onchange="let count = this.files.length; if(count > 1) { $('#file-name-display').text(count + ' files selected'); } else if(count === 1) { $('#file-name-display').text(this.files[0].name); } $('#upload-placeholder').hide(); $('#upload-selected').show();">
                                <label for="caseFile" class="custom-file-upload d-block mb-0">
                                    <div id="upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt text-primary"></i>
                                        <h6 class="font-weight-bold text-dark mb-1">{{ __('Click to browse and upload documents') }}</h6>
                                        <p class="text-muted small mb-0">{{ __('Supported: PDF, Word, Excel, Images (Max 10MB per file, can select multiple)') }}</p>
                                    </div>
                                    <div id="upload-selected" style="display:none;">
                                        <i class="fas fa-file-pdf text-success"></i>
                                        <h6 class="font-weight-bold text-success mb-1" id="file-name-display"></h6>
                                        <p class="text-muted small mb-0">{{ __('Click to select different files') }}</p>
                                    </div>
                                </label>
                                <span class="field-tip text-muted"><i class="fas fa-exclamation-triangle"></i> {{ __('You must upload at least one supporting document (e.g., notice copy, letter, W2, audit details).') }}</span>
                                @error('files') <span class="text-danger d-block small mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="form-group text-center pt-3 border-top">
                            <button type="submit" class="btn btn-primary font-weight-bold btn-lg w-75 py-2"><i class="fas fa-folder-plus mr-1"></i> {{ __('Submit Case & Request Representation') }}</button>
                            <p class="text-muted small mt-2 mb-0"><i class="fas fa-clock"></i> {{ __('Our intake legal/CPA officers review requests within 24 business hours.') }}</p>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Guidelines Sidebar -->
        <div class="col-lg-4">
            <div class="card case-card bg-light">
                <div class="card-body">
                    <h5 class="font-weight-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-info-circle text-info"></i> {{ __('Client Guidelines') }}</h5>
                    
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark mb-1"><i class="fas fa-user-tie text-primary mr-1"></i> {{ __('What is Case Representation?') }}</h6>
                        <p class="text-muted small" style="line-height: 1.5;">{{ __('Submitting this form creates a pending matter request. A certified CPA or legal counselor will review your materials to configure formal representation documents.') }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark mb-1"><i class="fas fa-file-invoice-dollar text-success mr-1"></i> {{ __('Supporting Documents') }}</h6>
                        <p class="text-muted small" style="line-height: 1.5;">{{ __('Always upload complete notice files. Notice letter codes (e.g., Notice CP2000, Letter 525) help our professionals pinpoint your issues immediately.') }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark mb-1"><i class="fas fa-lock text-warning mr-1"></i> {{ __('Notice Vault Security') }}</h6>
                        <p class="text-muted small" style="line-height: 1.5;">{{ $generalSetting && $generalSetting->site_name ? $generalSetting->site_name : config('app.name', 'Your CPA Expert') }} {{ __('ensures zero-disclosure secure file transmissions. Your documents are isolated and shielded against unauthenticated threats.') }}</p>
                    </div>

                    <div class="alert alert-info mb-0 small">
                        <strong>{{ __('Need Urgent Assistance?') }}</strong><br>
                        {{ __('If your court date or IRS audit compliance deadline is under 48 hours, please submit this request and initiate a direct message to support staff immediately.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
