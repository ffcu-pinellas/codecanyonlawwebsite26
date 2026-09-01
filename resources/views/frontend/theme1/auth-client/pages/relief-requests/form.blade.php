@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert'). ' | '.$title)

@section('page-css')
<style>
    .case-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        margin-bottom: 30px;
        overflow: hidden;
    }
    .case-card-header {
        background: linear-gradient(135deg, #11151e 0%, #1e293b 100%);
        color: #ffffff;
        padding: 20px 24px;
        border-bottom: 2px solid #fecc56;
    }
    .tip-panel {
        background-color: #f8fafc;
        border-left: 4px solid #fecc56;
        border-radius: 0 8px 8px 0;
        padding: 14px 18px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        color: #0f172a !important;
        font-size: 13.5px;
        line-height: 1.5;
    }
    .field-tip {
        font-size: 12px;
        color: #475569;
        margin-top: 5px;
        display: block;
        font-weight: 500;
    }
    .custom-file-upload {
        border: 2px dashed #94a3b8;
        border-radius: 10px;
        padding: 24px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s;
    }
    .custom-file-upload:hover {
        border-color: #f59e0b;
        background-color: #fefce8;
    }
    .section-num {
        background-color: #0f172a;
        color: #fecc56;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        margin-right: 10px;
    }
    .card-title-text {
        font-weight: 700;
        font-size: 1.05rem;
        color: #0f172a;
        display: inline-flex;
        align-items: center;
    }
    .btn-gold {
        background: linear-gradient(135deg, #fecc56, #f0a500);
        color: #000000 !important;
        font-weight: 700;
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(254,204,86,0.35);
    }
    .btn-gold:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(254,204,86,0.5);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Back to Dashboard Navigation -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('client.dashboard') }}" class="btn btn-sm btn-outline-secondary font-weight-bold text-light px-3" style="border-color: #334155;">
            <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Dashboard') }}
        </a>
        <span class="badge badge-warning text-dark font-weight-bold px-3 py-2" style="font-size: 11.5px;">
            <i class="fas fa-shield-alt mr-1"></i> {{ __('Privileged Legal Representation Intake') }}
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row justify-content-center">
        <!-- Main Form Column -->
        <div class="col-lg-10 col-xl-9">
            <div class="card case-card">
                <div class="card-header case-card-header">
                    <h4 class="font-weight-bold text-white mb-1" style="font-size: 1.25rem;">
                        <i class="fas fa-folder-plus text-warning mr-2"></i> {{ __('Open Secure Legal & CPA Representation Case') }}
                    </h4>
                    <p class="text-light mb-0 small" style="opacity: 0.9;">{{ __('Submit tax audit notices, IRS letters, or business dispute records for immediate CPA & Attorney representation.') }}</p>
                </div>

                <div class="card-body p-4 text-dark" style="color: #0f172a !important;">
                    <form action="{{ route('client.financial-relief') }}" method="post" enctype="multipart/form-data" id="caseRequestForm">
                        @csrf

                        <!-- Section 1: Contact Details -->
                        <div class="mb-4 border-bottom pb-4" style="border-color: #e2e8f0 !important;">
                            <h5 class="card-title-text mb-3"><span class="section-num">1</span> {{ __('Contact & Account Information') }}</h5>
                            <div class="tip-panel">
                                <strong class="text-dark"><i class="fas fa-info-circle text-warning mr-1"></i> {{ __('Quick Check:') }}</strong> 
                                <span style="color: #0f172a !important; font-weight: 500;">{{ __('These details are loaded from your secure account registry. If you need to make corrections, update your phone or mailing address in your Account Profile before continuing.') }}</span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="name" class="font-weight-bold text-dark small" style="color: #0f172a !important;">{{ __('Full Legal Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" class="form-control text-dark font-weight-bold" style="background: #f1f5f9; border: 1px solid #cbd5e1;" readonly>
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="email" class="font-weight-bold text-dark small" style="color: #0f172a !important;">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" class="form-control text-dark font-weight-bold" style="background: #f1f5f9; border: 1px solid #cbd5e1;" readonly>
                                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="phone" class="font-weight-bold text-dark small" style="color: #0f172a !important;">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="phone" value="{{ Auth::user()->phone }}" class="form-control text-dark font-weight-bold" style="background: #f1f5f9; border: 1px solid #cbd5e1;" readonly>
                                    @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="address" class="font-weight-bold text-dark small" style="color: #0f172a !important;">{{ __('Current Mailing Address') }} <span class="text-danger">*</span></label>
                                <input type="text" name="address" id="address" value="{{ Auth::user()->address }}" class="form-control text-dark font-weight-bold" style="background: #f1f5f9; border: 1px solid #cbd5e1;" readonly>
                                @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Section 2: Request Scope -->
                        <div class="mb-4 border-bottom pb-4" style="border-color: #e2e8f0 !important;">
                            <h5 class="card-title-text mb-3"><span class="section-num">2</span> {{ __('Case Scope & Notice Details') }}</h5>
                            
                            <div class="form-group mb-3">
                                <label for="reason" class="font-weight-bold text-dark small" style="color: #0f172a !important;">{{ __('Primary Subject / Matter of Case') }} <span class="text-danger">*</span></label>
                                <input type="text" name="reason" id="reason" value="{{ old('reason') }}" class="form-control text-dark bg-white" style="border: 1px solid #94a3b8;" placeholder="e.g. IRS Notice CP2000 Audit Representation for Tax Year 2024" required>
                                <span class="field-tip"><i class="fas fa-info-circle text-warning"></i> {{ __('Provide a brief header describing your notice, audit, or legal filing.') }}</span>
                                @error('reason') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label for="details" class="font-weight-bold text-dark small" style="color: #0f172a !important;">{{ __('Additional Background & Incident Narrative (Optional)') }}</label>
                                <textarea name="details" id="details" class="form-control text-dark bg-white" style="border: 1px solid #94a3b8;" rows="4" placeholder="Detail notices received, amounts claimed, taxing authorities involved, or timeline...">{{ old('details') }}</textarea>
                                <span class="field-tip"><i class="fas fa-info-circle text-warning"></i> {{ __('Include letter codes, tax periods, agency names, or specific deadlines.') }}</span>
                                @error('details') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Section 3: Target Resolution -->
                        <div class="mb-4 border-bottom pb-4" style="border-color: #e2e8f0 !important;">
                            <h5 class="card-title-text mb-3"><span class="section-num">3</span> {{ __('Proposed Settlement / Representation Goal') }}</h5>
                            
                            <div class="form-group mb-0">
                                <label for="offer" class="font-weight-bold text-dark small" style="color: #0f172a !important;">{{ __('Desired Outcome / Target Settlement Goal') }} <span class="text-danger">*</span></label>
                                <input type="text" name="offer" id="offer" value="{{ old('offer') }}" class="form-control text-dark bg-white" style="border: 1px solid #94a3b8;" placeholder="e.g. Penalty Abatement, Offer in Compromise, or Formal Dismissal" required>
                                <span class="field-tip"><i class="fas fa-info-circle text-warning"></i> {{ __('Specify your desired legal/financial resolution for our advisory team.') }}</span>
                                @error('offer') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Section 4: Document Vault Upload -->
                        <div class="mb-4">
                            <h5 class="card-title-text mb-3"><span class="section-num">4</span> {{ __('Document Evidence & Notice Vault') }}</h5>
                            
                            <div class="tip-panel">
                                <strong class="text-dark"><i class="fas fa-shield-alt text-warning mr-1"></i> {{ __('Vault Security:') }}</strong> 
                                <span style="color: #0f172a !important; font-weight: 500;">{{ __('All documents uploaded here are encrypted in transit and stored inside our bank-grade secure server container. Only assigned CPA professionals and attorneys can access them.') }}</span>
                            </div>

                            <label class="font-weight-bold text-dark small d-block mb-2" style="color: #0f172a !important;">{{ __('Upload Case Documents / Notice Copies') }} <span class="text-danger">*</span></label>
                            
                            <div class="custom-file-upload mb-3" onclick="document.getElementById('case_files').click();">
                                <i class="fas fa-cloud-upload-alt fa-3x text-warning mb-2 d-block"></i>
                                <span class="font-weight-bold text-dark d-block">{{ __('Click or Drag & Drop Documents Here') }}</span>
                                <small class="text-muted">{{ __('Supported formats: PDF, DOCX, DOC, JPG, PNG, XLSX (Max 20MB per file)') }}</small>
                                <input type="file" name="files[]" id="case_files" class="d-none" multiple required onchange="handleFileSelect(event)">
                            </div>
                            
                            <!-- File List Preview -->
                            <div id="fileListPreview" class="d-flex flex-wrap gap-2 mb-3"></div>
                            @error('files') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                            @error('files.*') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center pt-2">
                            <button type="submit" class="btn btn-gold btn-lg px-5 py-3 font-weight-bold">
                                <i class="fas fa-paper-plane mr-2"></i> {{ __('Submit Case File to Senior Counsel') }}
                            </button>
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
    function handleFileSelect(e) {
        var files = e.target.files;
        var preview = document.getElementById('fileListPreview');
        preview.innerHTML = '';
        if (files.length > 0) {
            for (var i = 0; i < files.length; i++) {
                var badge = document.createElement('span');
                badge.className = 'badge badge-dark p-2 text-warning border border-secondary mr-2 mb-2 font-weight-normal';
                badge.innerHTML = '<i class="fas fa-file-alt mr-1"></i> ' + files[i].name + ' (' + (files[i].size / 1024 / 1024).toFixed(2) + ' MB)';
                preview.appendChild(badge);
            }
        }
    }
</script>
@endsection
