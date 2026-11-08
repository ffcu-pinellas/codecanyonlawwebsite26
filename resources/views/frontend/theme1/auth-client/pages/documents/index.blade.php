@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
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
    .doc-item {
        background: #161a23;
        border-bottom: 1px solid #232a38;
        padding: 22px 24px;
        transition: background 0.15s;
    }
    .doc-item:hover {
        background: #1a202c;
    }
    .doc-item:last-child {
        border-bottom: none;
    }
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        background: rgba(254, 204, 86, 0.12);
        color: #fecc56;
        border: 1px solid rgba(254, 204, 86, 0.25);
        flex-shrink: 0;
    }
    .btn-gold {
        background: linear-gradient(135deg, #fecc56, #f0a500);
        color: #000 !important;
        font-weight: 700;
        border: none;
        border-radius: 6px;
        padding: 7px 16px;
        font-size: 12.5px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        gap: 6px;
    }
    .btn-gold:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(254,204,86,0.45);
    }
    .btn-portal-secondary {
        background: #262e3d;
        border: 1px solid #374151;
        color: #e2e8f0;
        font-weight: 600;
        border-radius: 6px;
        font-size: 12px;
        padding: 6px 12px;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-portal-secondary:hover {
        background: #333d4e;
        color: #fff;
    }
    .badge-status {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
    }
    .badge-action {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
    }

    /* DocuSign Style Signature Pad */
    .signature-pad-container {
        background: #ffffff;
        border: 2px dashed #94a3b8;
        border-radius: 8px;
        position: relative;
        touch-action: none;
        cursor: crosshair;
        margin-bottom: 8px;
    }
    .signature-canvas {
        width: 100%;
        height: 160px;
        display: block;
        border-radius: 6px;
    }
    .signature-pad-placeholder {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
        user-select: none;
        font-style: italic;
    }
    .sig-type-preview {
        font-family: 'Caveat', 'Dancing Script', cursive;
        font-size: 38px;
        color: #0f172a;
        min-height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
        letter-spacing: 1px;
    }

    body.light-mode .doc-item { background: #ffffff !important; border-color: #e2e8f0 !important; }
    body.light-mode .doc-item:hover { background: #f8fafc !important; }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap" style="gap:10px;">
        <div>
            <h4 class="font-weight-bold text-white mb-1">
                <i class="fas fa-file-contract text-warning mr-2"></i> {{ __('Documents & Contracts') }}
            </h4>
            <p class="text-muted small mb-0">{{ __('Official case retainers, fee agreements, powers of attorney, custom deliverables, and client document vault.') }}</p>
        </div>
        <div class="d-flex align-items-center" style="gap: 8px;">
            <button type="button" class="btn btn-gold btn-sm px-3 font-weight-bold" data-toggle="modal" data-target="#uploadVaultDocModal">
                <i class="fas fa-cloud-upload-alt mr-1"></i> {{ __('Upload Document') }}
            </button>
            <a href="{{ route('client.dashboard') }}" class="btn btn-sm btn-outline-secondary text-light font-weight-bold px-3">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Dashboard') }}
            </a>
        </div>
    </div>

    <!-- Upload Document to Vault Modal -->
    <div class="modal fade" id="uploadVaultDocModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: #161a23; border: 1px solid #28303f; border-radius: 12px; color: #fff;">
                <div class="modal-header border-bottom" style="border-color: #28303f !important;">
                    <h5 class="modal-title font-weight-bold text-warning">
                        <i class="fas fa-cloud-upload-alt mr-2"></i>{{ __('Upload Document to Vault') }}
                    </h5>
                    <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('client.documents.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-white mb-1">{{ __('Select File (PDF, DOCX, PNG, JPG - Max 20MB)') }} <span class="text-danger">*</span></label>
                            <input type="file" name="document_file" class="form-control-file p-2 rounded" style="background: #11151e; border: 1px solid #28303f; color: #cbd5e1; width: 100%;" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-white mb-1">{{ __('Document Title') }}</label>
                            <input type="text" name="document_title" class="form-control text-white" style="background: #11151e; border: 1px solid #28303f;" placeholder="e.g. 2023 W2 Form, Tax Return, Bank Statement">
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-white mb-1">{{ __('Document Category / Type') }}</label>
                            <select name="document_type" class="form-control text-white" style="background: #11151e; border: 1px solid #28303f;">
                                <option value="Tax Return & Schedule">{{ __('Tax Return & Schedule') }}</option>
                                <option value="IRS / State Notice">{{ __('IRS / State Notice') }}</option>
                                <option value="Financial & Bank Statements">{{ __('Financial & Bank Statements') }}</option>
                                <option value="Identity & KYC Verification">{{ __('Identity & Verification File') }}</option>
                                <option value="Power of Attorney / Legal">{{ __('Power of Attorney / Legal File') }}</option>
                                <option value="Other Client Documentation" selected>{{ __('Other Client Documentation') }}</option>
                            </select>
                        </div>
                        @if(isset($userCases) && count($userCases) > 0)
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-white mb-1">{{ __('Attach to Case (Optional)') }}</label>
                            <select name="case_id" class="form-control text-white" style="background: #11151e; border: 1px solid #28303f;">
                                <option value="">-- {{ __('General Client Vault (No specific case)') }} --</option>
                                @foreach($userCases as $c)
                                    <option value="{{ $c->id }}">{{ $c->case_number }} - {{ $c->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer border-top" style="border-color: #28303f !important;">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-gold btn-sm px-4 font-weight-bold">
                            <i class="fas fa-upload mr-1"></i> {{ __('Upload & Encrypt') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show mb-4 font-weight-bold" style="background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3);">
            <i class="fas fa-check-circle mr-1"></i> {{ session('message') }}
            <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 font-weight-bold" style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3);">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- VAULTED CASE DOCUMENTS & GENERATOR DELIVERABLES -->
    <div class="portal-card mb-4">
        <div class="portal-card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:8px;">
            <div>
                <i class="fas fa-file-invoice text-warning mr-2"></i> {{ __('Case Document Vault & Legal Deliverables') }}
            </div>
            <span class="badge badge-warning text-dark px-3 py-1 font-weight-bold" style="font-size:11px;">
                <i class="fas fa-lock mr-1"></i> {{ count($vaultDocuments ?? []) }} {{ __('Document(s) Vaulted') }}
            </span>
        </div>

        <div class="doc-list">
            @forelse($vaultDocuments ?? [] as $vDoc)
                <div class="doc-item">
                    <div class="row align-items-center">
                        <div class="col-md-7 d-flex align-items-start mb-3 mb-md-0">
                            <div class="icon-box mr-3">
                                @if($vDoc->requires_signature && !$vDoc->is_signed)
                                    <i class="fas fa-file-signature text-warning"></i>
                                @elseif($vDoc->is_signed)
                                    <i class="fas fa-file-shield text-success"></i>
                                @else
                                    <i class="fas fa-file-pdf"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-white mb-1" style="font-size: 15px;">
                                    {{ $vDoc->document_title ?: $vDoc->title ?: __('Case Legal Document') }}
                                </h6>
                                <p class="text-muted small mb-2" style="line-height: 1.4;">
                                    {{ $vDoc->document_type ?: __('Standard / General Document') }}
                                    @if($vDoc->clientCase)
                                        &bull; <strong class="text-warning">{{ $vDoc->clientCase->case_number }}</strong> - {{ $vDoc->clientCase->title }}
                                    @else
                                        &bull; <span class="text-muted">{{ __('General Client Representation Vault') }}</span>
                                    @endif
                                </p>
                                <div class="d-flex flex-wrap align-items-center small" style="gap: 10px;">
                                    <span class="text-muted">
                                        <i class="far fa-calendar-alt text-warning mr-1"></i> {{ $vDoc->created_at ? $vDoc->created_at->format('M d, Y') : 'Recent' }}
                                    </span>
                                    <span class="text-muted">&bull;</span>
                                    @if($vDoc->is_signed)
                                        <span class="badge-status text-success border border-success" style="background: rgba(34,197,94,0.15);">
                                            <i class="fas fa-check-circle mr-1"></i> {{ __('Digitally Executed') }}
                                            @if($vDoc->signed_at)
                                                ({{ \Carbon\Carbon::parse($vDoc->signed_at)->format('M d, Y') }})
                                            @endif
                                        </span>
                                    @elseif($vDoc->requires_signature)
                                        <span class="badge-action text-warning border border-warning" style="background: rgba(254,204,86,0.1);">
                                            <i class="fas fa-pen-nib mr-1"></i> {{ __('Client Signature Required') }}
                                        </span>
                                    @else
                                        <span class="badge-status text-info border border-info" style="background: rgba(56,189,248,0.1);">
                                            <i class="fas fa-shield-alt mr-1"></i> {{ __('Vaulted & Certified') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 text-md-right">
                            <div class="d-flex flex-wrap justify-content-md-end align-items-center" style="gap: 8px;">
                                <a href="{{ route('client.documents.preview', $vDoc->id) }}" target="_blank" class="btn btn-gold btn-sm px-3 font-weight-bold">
                                    <i class="fas fa-eye mr-1"></i> {{ __('View Document') }}
                                </a>
                                <a href="{{ route('client.documents.download', $vDoc->id) }}" class="btn btn-portal-secondary btn-sm px-3">
                                    <i class="fas fa-download mr-1"></i> {{ __('Download') }}
                                </a>
                                @if($vDoc->requires_signature && !$vDoc->is_signed)
                                    <button type="button" class="btn btn-warning btn-sm font-weight-bold text-dark px-3" data-toggle="collapse" data-target="#signCollapse-{{ $vDoc->id }}">
                                        <i class="fas fa-signature mr-1"></i> {{ __('E-Sign Now') }}
                                    </button>
                                @endif
                            </div>

                            @if($vDoc->requires_signature && !$vDoc->is_signed)
                                <div class="collapse mt-3 text-left" id="signCollapse-{{ $vDoc->id }}">
                                    <div class="p-3 rounded border border-warning" style="background: #11151e;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="text-warning font-weight-bold small mb-0">
                                                <i class="fas fa-file-signature mr-1"></i> {{ __('Adopt Electronic Signature') }}
                                            </h6>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-warning active btn-sm font-weight-bold" id="drawModeBtn-case_{{ $vDoc->id }}" onclick="setSignatureMode('case_{{ $vDoc->id }}', 'draw')">
                                                    <i class="fas fa-pen-nib mr-1"></i> {{ __('Draw') }}
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm font-weight-bold" id="typeModeBtn-case_{{ $vDoc->id }}" onclick="setSignatureMode('case_{{ $vDoc->id }}', 'type')">
                                                    <i class="fas fa-keyboard mr-1"></i> {{ __('Type') }}
                                                </button>
                                            </div>
                                        </div>

                                        <form action="{{ route('client.documents.sign-case-doc', $vDoc->id) }}" method="POST" id="sigForm-case_{{ $vDoc->id }}" onsubmit="return handleSignatureSubmit('case_{{ $vDoc->id }}');">
                                            @csrf
                                            <input type="hidden" name="signature_data" id="sigDataInput-case_{{ $vDoc->id }}">

                                            <!-- Draw Mode Canvas -->
                                            <div id="drawSection-case_{{ $vDoc->id }}">
                                                <div class="signature-pad-container" id="sigContainer-case_{{ $vDoc->id }}">
                                                    <canvas id="sigCanvas-case_{{ $vDoc->id }}" class="signature-canvas"></canvas>
                                                    <span class="signature-pad-placeholder" id="sigPlaceholder-case_{{ $vDoc->id }}">{{ __('Sign here with finger or mouse') }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="d-flex align-items-center" style="gap: 6px;">
                                                        <span class="small text-muted font-weight-bold" style="font-size: 11px;">Ink:</span>
                                                        <button type="button" class="btn btn-xs rounded-circle p-0" id="inkBlue-case_{{ $vDoc->id }}" style="width: 18px; height: 18px; background: #002060; border: 2px solid #fecc56; cursor: pointer;" title="Legal Navy Blue" onclick="setPenColor('case_{{ $vDoc->id }}', '#002060')"></button>
                                                        <button type="button" class="btn btn-xs rounded-circle p-0" id="inkBlack-case_{{ $vDoc->id }}" style="width: 18px; height: 18px; background: #0f172a; border: 1px solid #475569; cursor: pointer;" title="Classic Black" onclick="setPenColor('case_{{ $vDoc->id }}', '#0f172a')"></button>
                                                    </div>
                                                    <div class="d-flex align-items-center" style="gap: 6px;">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 font-weight-bold" style="font-size: 11px;" onclick="undoSignatureStroke('case_{{ $vDoc->id }}')">
                                                            <i class="fas fa-undo mr-1"></i> {{ __('Undo') }}
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 font-weight-bold" style="font-size: 11px;" onclick="clearSignatureCanvas('case_{{ $vDoc->id }}')">
                                                            <i class="fas fa-eraser mr-1"></i> {{ __('Clear') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Type Mode Input & Live Cursive Preview -->
                                            <div id="typeSection-case_{{ $vDoc->id }}" style="display: none;">
                                                <div class="sig-type-preview" id="typeSigPreview-case_{{ $vDoc->id }}">
                                                    {{ Auth::user()->name }}
                                                </div>
                                            </div>

                                            <div class="form-group mb-2">
                                                <label class="small text-white font-weight-bold">{{ __('Full Legal Name (Electronic Signature):') }}</label>
                                                <input type="text" name="signature_text" id="sigTextInput-case_{{ $vDoc->id }}" class="form-control form-control-sm text-white font-weight-bold" value="{{ Auth::user()->name }}" required style="background: #161a23; border: 1px solid #28303f;" oninput="updateTypePreview('case_{{ $vDoc->id }}', this.value)">
                                            </div>

                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" class="custom-control-input" id="caseCheck-{{ $vDoc->id }}" name="agreement_accepted" value="1" required>
                                                <label class="custom-control-label small text-muted font-weight-semibold" for="caseCheck-{{ $vDoc->id }}">
                                                    {{ __('I understand that submitting this electronic signature constitutes an authorized, legally binding execution under the federal ESIGN & UETA Acts.') }}
                                                </label>
                                            </div>

                                            <button type="submit" class="btn btn-success btn-sm btn-block font-weight-bold">
                                                <i class="fas fa-check mr-1"></i> {{ __('Confirm & Execute Electronic Signature') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-folder-open fa-2x mb-2 text-warning" style="opacity: 0.5;"></i>
                    <p class="small mb-0">{{ __('No case documents currently uploaded to your vault.') }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="portal-card mb-4">
        <div class="portal-card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:8px;">
            <div>
                <i class="fas fa-folder-open mr-1"></i> {{ __('Assigned Legal Agreements & Templates') }}
            </div>
            <span class="badge badge-success px-3 py-1 font-weight-bold" style="font-size:11px;">
                <i class="fas fa-shield-alt mr-1"></i> {{ __('ISO/IEC 27037 Tamper-Evident Vault') }}
            </span>
        </div>

        <div class="doc-list">
            @forelse($documents as $doc)
                <div class="doc-item">
                    <div class="row align-items-center">
                        <!-- Icon & Document Details -->
                        <div class="col-md-7 d-flex align-items-start mb-3 mb-md-0">
                            <div class="icon-box mr-3">
                                @if($doc->action_required === 'sign_upload' || $doc->action_required === 'sign_pin')
                                    <i class="fas fa-file-signature"></i>
                                @elseif($doc->action_required === 'approve')
                                    <i class="fas fa-file-check"></i>
                                @else
                                    <i class="fas fa-file-alt"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-white mb-1" style="font-size: 15px;">{{ $doc->template_title }}</h6>
                                <p class="text-muted small mb-2" style="line-height: 1.4;">
                                    {{ __('Standard legal representation agreement requiring review and execution.') }}
                                </p>
                                <div class="d-flex flex-wrap align-items-center small" style="gap: 10px;">
                                    <span class="text-muted">
                                        <i class="far fa-calendar-alt text-warning mr-1"></i> {{ $doc->created_at->format('M d, Y') }}
                                    </span>
                                    <span class="text-muted">&bull;</span>
                                    <!-- Action Required Badge -->
                                    @if($doc->action_required === 'sign_upload' || $doc->action_required === 'sign_pin')
                                        <span class="badge-action text-warning border border-warning" style="background: rgba(254,204,86,0.1);">
                                            <i class="fas fa-signature mr-1"></i> {{ __('E-Signature Required') }}
                                        </span>
                                    @elseif($doc->action_required === 'approve')
                                        <span class="badge-action text-info border border-info" style="background: rgba(56,189,248,0.1);">
                                            <i class="fas fa-user-check mr-1"></i> {{ __('Approval Required') }}
                                        </span>
                                    @else
                                        <span class="badge-action text-secondary border border-secondary" style="background: rgba(148,163,184,0.1);">
                                            <i class="fas fa-eye mr-1"></i> {{ __('Review Only') }}
                                        </span>
                                    @endif

                                    <!-- Status Badge -->
                                    @if($doc->status === 'approved' || $doc->status === 'signed')
                                        <span class="badge-status text-success border border-success" style="background: rgba(34,197,94,0.15);">
                                            <i class="fas fa-check-circle mr-1"></i> {{ ucfirst($doc->status) }}
                                        </span>
                                    @elseif($doc->status === 'rejected')
                                        <span class="badge-status text-danger border border-danger" style="background: rgba(239,68,68,0.15);">
                                            <i class="fas fa-times-circle mr-1"></i> {{ __('Rejected') }}
                                        </span>
                                    @elseif($doc->status === 'under_review')
                                        <span class="badge-status text-warning border border-warning" style="background: rgba(254,204,86,0.15);">
                                            <i class="fas fa-clock mr-1"></i> {{ __('Under Review') }}
                                        </span>
                                    @else
                                        <span class="badge-status text-warning border border-warning" style="background: rgba(245,158,11,0.15);">
                                            <i class="fas fa-hourglass-start mr-1"></i> {{ __('Pending Action') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actions Column -->
                        <div class="col-md-5 text-md-right">
                            <div class="d-flex flex-column align-items-md-end" style="gap: 8px;">
                                <!-- View & Print -->
                                <a href="{{ route('client.documents.print', $doc->id) }}" target="_blank" class="btn btn-gold btn-sm px-3">
                                    <i class="fas fa-print mr-1"></i> {{ __('View & Print Document') }}
                                </a>

                                <!-- Action Section: Approve -->
                                @if($doc->action_required === 'approve' && $doc->status !== 'approved' && $doc->status !== 'rejected')
                                    <div class="w-100 border rounded p-3 text-left mt-2" style="background: #11151e; border-color: #28303f !important;">
                                        <form action="{{ route('client.documents.approve', $doc->id) }}" method="POST" class="mb-2">
                                            @csrf
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-white mb-1">Add Note / Approval Comment:</label>
                                                <textarea name="recipient_notes" class="form-control form-control-sm" rows="2" style="background: #161a23; border: 1px solid #28303f; color: #ffffff;" placeholder="e.g. Reviewed and accepted terms."></textarea>
                                            </div>
                                            <div class="d-flex" style="gap: 8px;">
                                                <button type="submit" class="btn btn-success btn-sm flex-grow-1 font-weight-bold">
                                                    <i class="fas fa-check mr-1"></i> {{ __('Accept & Approve') }}
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="$('#reject-form-{{ $doc->id }}').toggle();">
                                                    <i class="fas fa-times mr-1"></i> {{ __('Reject') }}
                                                </button>
                                            </div>
                                        </form>

                                        <div id="reject-form-{{ $doc->id }}" style="display:none;" class="mt-2 pt-2 border-top" style="border-color: #28303f !important;">
                                            <form action="{{ route('client.documents.reject', $doc->id) }}" method="POST">
                                                @csrf
                                                <span class="small font-weight-bold text-danger d-block mb-1">Reason for Rejection (Required):</span>
                                                <textarea name="recipient_notes" class="form-control form-control-sm mb-2" rows="2" style="background: #161a23; border: 1px solid #28303f; color: #ffffff;" placeholder="Explain rejection reason..." required></textarea>
                                                <button type="submit" class="btn btn-danger btn-sm btn-block">Confirm Rejection</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Section: DocuSign In-Portal Touch/Mouse E-Signature -->
                                @if(($doc->action_required === 'sign_upload' || $doc->action_required === 'sign_pin') && $doc->status !== 'rejected')
                                    @if($doc->status !== 'signed')
                                        <div class="w-100 border rounded p-3 text-left mt-2" style="background: #11151e; border-color: #28303f !important;">
                                            <!-- Tab switcher between Touch/Mouse E-Sign and File Upload -->
                                            <ul class="nav nav-pills nav-fill mb-3" style="gap: 6px;">
                                                <li class="nav-item">
                                                    <a class="nav-link active py-1 px-2 font-weight-bold" id="tab-esign-{{ $doc->id }}" data-toggle="pill" href="#pane-esign-{{ $doc->id }}" style="font-size: 11.5px; border-radius: 6px;">
                                                        <i class="fas fa-signature mr-1"></i> {{ __('DocuSign Touch/Mouse E-Sign') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 px-2 font-weight-bold" id="tab-upload-{{ $doc->id }}" data-toggle="pill" href="#pane-upload-{{ $doc->id }}" style="font-size: 11.5px; border-radius: 6px;">
                                                        <i class="fas fa-file-upload mr-1"></i> {{ __('Upload Signed File') }}
                                                    </a>
                                                </li>
                                            </ul>

                                            <div class="tab-content">
                                                <!-- PANE 1: DOCUSIGN REPLICA E-SIGNATURE (TOUCH / MOUSE DRAW OR CURSIVE TYPE) -->
                                                <div class="tab-pane fade show active" id="pane-esign-{{ $doc->id }}">
                                                    <form action="{{ route('client.documents.sign-electronic', $doc->id) }}" method="POST" id="sigForm-{{ $doc->id }}" onsubmit="return handleSignatureSubmit({{ $doc->id }});">
                                                        @csrf
                                                        <input type="hidden" name="signature_data" id="sigDataInput-{{ $doc->id }}">

                                                        <!-- Mode Selector: Draw vs Type -->
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <label class="small font-weight-bold text-white mb-0">{{ __('Adopt Signature:') }}</label>
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <button type="button" class="btn btn-outline-warning active btn-sm font-weight-bold" id="drawModeBtn-{{ $doc->id }}" onclick="setSignatureMode({{ $doc->id }}, 'draw')">
                                                                    <i class="fas fa-pen-nib mr-1"></i> {{ __('Draw') }}
                                                                </button>
                                                                <button type="button" class="btn btn-outline-warning btn-sm font-weight-bold" id="typeModeBtn-{{ $doc->id }}" onclick="setSignatureMode({{ $doc->id }}, 'type')">
                                                                    <i class="fas fa-keyboard mr-1"></i> {{ __('Type') }}
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <!-- Draw Mode Canvas -->
                                                        <div id="drawSection-{{ $doc->id }}">
                                                            <div class="signature-pad-container" id="sigContainer-{{ $doc->id }}">
                                                                <canvas id="sigCanvas-{{ $doc->id }}" class="signature-canvas"></canvas>
                                                                <span class="signature-pad-placeholder" id="sigPlaceholder-{{ $doc->id }}">{{ __('Sign here with finger or mouse') }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <div class="d-flex align-items-center" style="gap: 6px;">
                                                                    <span class="small text-muted font-weight-bold" style="font-size: 11px;">Ink:</span>
                                                                    <button type="button" class="btn btn-xs rounded-circle p-0" id="inkBlue-{{ $doc->id }}" style="width: 18px; height: 18px; background: #002060; border: 2px solid #fecc56; cursor: pointer;" title="Legal Navy Blue" onclick="setPenColor({{ $doc->id }}, '#002060')"></button>
                                                                    <button type="button" class="btn btn-xs rounded-circle p-0" id="inkBlack-{{ $doc->id }}" style="width: 18px; height: 18px; background: #0f172a; border: 1px solid #475569; cursor: pointer;" title="Classic Black" onclick="setPenColor({{ $doc->id }}, '#0f172a')"></button>
                                                                </div>
                                                                <div class="d-flex align-items-center" style="gap: 6px;">
                                                                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 font-weight-bold" style="font-size: 11px;" onclick="undoSignatureStroke({{ $doc->id }})">
                                                                        <i class="fas fa-undo mr-1"></i> {{ __('Undo') }}
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 font-weight-bold" style="font-size: 11px;" onclick="clearSignatureCanvas({{ $doc->id }})">
                                                                        <i class="fas fa-eraser mr-1"></i> {{ __('Clear') }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Type Mode Input & Live Cursive Preview -->
                                                        <div id="typeSection-{{ $doc->id }}" style="display: none;">
                                                            <div class="sig-type-preview" id="typeSigPreview-{{ $doc->id }}">
                                                                {{ Auth::user()->name }}
                                                            </div>
                                                        </div>

                                                        <div class="form-group mb-2">
                                                            <label class="small font-weight-bold text-white mb-1">{{ __('Full Legal Signer Name:') }} <span class="text-danger">*</span></label>
                                                            <input type="text" name="signature_text" id="sigTextInput-{{ $doc->id }}" class="form-control form-control-sm font-weight-bold" style="background: #161a23; border: 1px solid #28303f; color: #fecc56;" value="{{ Auth::user()->name }}" placeholder="Type your full legal name" required oninput="updateTypePreview({{ $doc->id }}, this.value)">
                                                        </div>

                                                        <div class="custom-control custom-checkbox mb-3">
                                                            <input type="checkbox" name="agreement_accepted" class="custom-control-input" id="agree-{{ $doc->id }}" value="1" required>
                                                            <label class="custom-control-label small text-muted font-weight-semibold" for="agree-{{ $doc->id }}">
                                                                {{ __('I certify that my signature above constitutes an authorized, legally binding electronic execution under the federal ESIGN & UETA Acts.') }}
                                                            </label>
                                                        </div>

                                                        <div class="d-flex" style="gap: 8px;">
                                                            <button type="submit" class="btn btn-warning btn-sm flex-grow-1 font-weight-bold text-dark" style="background: linear-gradient(135deg, #fecc56, #f0a500); border: none;">
                                                                <i class="fas fa-signature mr-1"></i> {{ __('Adopt & Execute Document') }}
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="$('#reject-form-{{ $doc->id }}').toggle();">
                                                                <i class="fas fa-times mr-1"></i> {{ __('Reject') }}
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!-- PANE 2: FILE UPLOAD OPTION -->
                                                <div class="tab-pane fade" id="pane-upload-{{ $doc->id }}">
                                                    <form action="{{ route('client.documents.upload-signed', $doc->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <span class="small font-weight-bold text-warning d-block mb-2"><i class="fas fa-file-upload mr-1"></i> {{ __('Upload Signed PDF/Image:') }}</span>
                                                        <input type="file" name="signed_file" class="form-control-file form-control-sm mb-2 text-white" required>
                                                        
                                                        <div class="form-group mb-2">
                                                            <label class="small font-weight-bold text-white mb-1">{{ __('Add Note / Comment (Optional):') }}</label>
                                                            <textarea name="recipient_notes" class="form-control form-control-sm" rows="2" style="background: #161a23; border: 1px solid #28303f; color: #ffffff;" placeholder="e.g. Uploaded executed copy."></textarea>
                                                        </div>
                                                        
                                                        <div class="d-flex" style="gap: 8px;">
                                                            <button type="submit" class="btn btn-gold btn-sm flex-grow-1 font-weight-bold">
                                                                <i class="fas fa-upload mr-1"></i> {{ __('Upload Signed Copy') }}
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="$('#reject-form-{{ $doc->id }}').toggle();">
                                                                <i class="fas fa-times mr-1"></i> {{ __('Reject') }}
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <div id="reject-form-{{ $doc->id }}" style="display:none;" class="mt-2 pt-2 border-top" style="border-color: #28303f !important;">
                                                <form action="{{ route('client.documents.reject', $doc->id) }}" method="POST">
                                                    @csrf
                                                    <span class="small font-weight-bold text-danger d-block mb-1">{{ __('Reason for Rejection (Required):') }}</span>
                                                    <textarea name="recipient_notes" class="form-control form-control-sm mb-2" rows="2" style="background: #161a23; border: 1px solid #28303f; color: #ffffff;" placeholder="Explain rejection reason..." required></textarea>
                                                    <button type="submit" class="btn btn-danger btn-sm btn-block">{{ __('Confirm Rejection') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex flex-wrap justify-content-md-end mt-1 w-100" style="gap: 6px;">
                                            @if($doc->signed_path)
                                                <a href="{{ asset($doc->signed_path) }}" target="_blank" class="btn btn-success btn-sm">
                                                    <i class="fas fa-file-download mr-1"></i> {{ __('Download Signed Copy') }}
                                                </a>
                                            @else
                                                <span class="badge badge-success px-3 py-2 font-weight-bold">
                                                    <i class="fas fa-check-circle mr-1"></i> {{ __('Digitally Executed') }}
                                                </span>
                                            @endif
                                            <a href="{{ route('client.documents.print', $doc->id) }}" target="_blank" class="btn btn-portal-secondary btn-sm">
                                                <i class="fas fa-certificate mr-1"></i> {{ __('View Certificate') }}
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-folder-open fa-3x mb-3 d-block text-secondary"></i>
                    <h6 class="text-white">{{ __('No agreements or document templates assigned yet.') }}</h6>
                    <p class="small text-muted">{{ __('When your legal counsel generates an engagement letter or authorization, it will appear here.') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
var canvasMap = {};
var strokesMap = {};
var penColorMap = {};
var modeMap = {};
var isDrawingMap = {};

function initSignatureCanvas(docId) {
    var canvas = document.getElementById('sigCanvas-' + docId);
    if (!canvas) return;

    if (!penColorMap[docId]) penColorMap[docId] = '#002060';
    if (!strokesMap[docId]) strokesMap[docId] = [];
    modeMap[docId] = 'draw';

    var container = canvas.parentElement;
    var rect = container.getBoundingClientRect();
    var cssWidth = rect.width > 20 ? rect.width : (canvas.offsetWidth || 380);
    var cssHeight = 160;

    // High-DPI scaling for crisp lines on mobile & retina screens
    var dpr = window.devicePixelRatio || 1;
    canvas.width = Math.floor(cssWidth * dpr);
    canvas.height = Math.floor(cssHeight * dpr);
    canvas.style.width = cssWidth + 'px';
    canvas.style.height = cssHeight + 'px';

    var ctx = canvas.getContext('2d');
    ctx.scale(dpr, dpr);
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    canvasMap[docId] = { canvas: canvas, ctx: ctx, dpr: dpr, cssWidth: cssWidth, cssHeight: cssHeight };
    redrawAllStrokes(docId);

    if (canvas._boundEvents) return;
    canvas._boundEvents = true;

    var currentStroke = null;

    function getCanvasCoordinates(e) {
        var cRect = canvas.getBoundingClientRect();
        var clientX = e.touches ? e.touches[0].clientX : e.clientX;
        var clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return {
            x: clientX - cRect.left,
            y: clientY - cRect.top
        };
    }

    function onPointerDown(e) {
        e.preventDefault();
        isDrawingMap[docId] = true;
        var pt = getCanvasCoordinates(e);
        currentStroke = {
            color: penColorMap[docId] || '#002060',
            width: 2.8,
            points: [pt]
        };

        var placeholder = document.getElementById('sigPlaceholder-' + docId);
        if (placeholder) placeholder.style.display = 'none';

        ctx.strokeStyle = currentStroke.color;
        ctx.lineWidth = currentStroke.width;
        ctx.beginPath();
        ctx.arc(pt.x, pt.y, currentStroke.width / 2, 0, Math.PI * 2, !0);
        ctx.fillStyle = currentStroke.color;
        ctx.fill();
        ctx.beginPath();
        ctx.moveTo(pt.x, pt.y);
    }

    function onPointerMove(e) {
        if (!isDrawingMap[docId] || !currentStroke) return;
        e.preventDefault();
        var pt = getCanvasCoordinates(e);
        currentStroke.points.push(pt);

        var pts = currentStroke.points;
        var len = pts.length;
        if (len >= 3) {
            var xc = (pts[len - 2].x + pts[len - 1].x) / 2;
            var yc = (pts[len - 2].y + pts[len - 1].y) / 2;
            ctx.quadraticCurveTo(pts[len - 2].x, pts[len - 2].y, xc, yc);
            ctx.stroke();
        } else {
            ctx.lineTo(pt.x, pt.y);
            ctx.stroke();
        }
    }

    function onPointerUp(e) {
        if (isDrawingMap[docId]) {
            isDrawingMap[docId] = false;
            if (currentStroke && currentStroke.points.length > 0) {
                strokesMap[docId].push(currentStroke);
            }
            currentStroke = null;
            ctx.closePath();
        }
    }

    canvas.addEventListener('mousedown', onPointerDown);
    canvas.addEventListener('mousemove', onPointerMove);
    window.addEventListener('mouseup', onPointerUp);

    canvas.addEventListener('touchstart', onPointerDown, { passive: false });
    canvas.addEventListener('touchmove', onPointerMove, { passive: false });
    canvas.addEventListener('touchend', onPointerUp);
}

function redrawAllStrokes(docId) {
    if (!canvasMap[docId]) return;
    var c = canvasMap[docId].canvas;
    var ctx = canvasMap[docId].ctx;
    var strokes = strokesMap[docId] || [];

    ctx.clearRect(0, 0, canvasMap[docId].cssWidth, canvasMap[docId].cssHeight);

    var placeholder = document.getElementById('sigPlaceholder-' + docId);
    if (placeholder) {
        placeholder.style.display = (strokes.length === 0) ? 'block' : 'none';
    }

    for (var i = 0; i < strokes.length; i++) {
        var strk = strokes[i];
        var pts = strk.points;
        if (!pts || pts.length === 0) continue;

        ctx.strokeStyle = strk.color || '#002060';
        ctx.lineWidth = strk.width || 2.8;
        ctx.fillStyle = strk.color || '#002060';

        if (pts.length === 1) {
            ctx.beginPath();
            ctx.arc(pts[0].x, pts[0].y, (strk.width || 2.8) / 2, 0, Math.PI * 2);
            ctx.fill();
        } else {
            ctx.beginPath();
            ctx.moveTo(pts[0].x, pts[0].y);
            for (var j = 1; j < pts.length - 1; j++) {
                var xc = (pts[j].x + pts[j + 1].x) / 2;
                var yc = (pts[j].y + pts[j + 1].y) / 2;
                ctx.quadraticCurveTo(pts[j].x, pts[j].y, xc, yc);
            }
            ctx.lineTo(pts[pts.length - 1].x, pts[pts.length - 1].y);
            ctx.stroke();
            ctx.closePath();
        }
    }
}

function clearSignatureCanvas(docId) {
    strokesMap[docId] = [];
    redrawAllStrokes(docId);
}

function undoSignatureStroke(docId) {
    if (strokesMap[docId] && strokesMap[docId].length > 0) {
        strokesMap[docId].pop();
        redrawAllStrokes(docId);
    }
}

function setPenColor(docId, color) {
    penColorMap[docId] = color;
    var blueBtn = document.getElementById('inkBlue-' + docId);
    var blackBtn = document.getElementById('inkBlack-' + docId);
    if (color === '#002060') {
        if (blueBtn) blueBtn.style.border = '2px solid #fecc56';
        if (blackBtn) blackBtn.style.border = '1px solid #475569';
    } else {
        if (blueBtn) blueBtn.style.border = '1px solid #475569';
        if (blackBtn) blackBtn.style.border = '2px solid #fecc56';
    }
}

function setSignatureMode(docId, mode) {
    modeMap[docId] = mode;
    var drawSec = document.getElementById('drawSection-' + docId);
    var typeSec = document.getElementById('typeSection-' + docId);
    var drawBtn = document.getElementById('drawModeBtn-' + docId);
    var typeBtn = document.getElementById('typeModeBtn-' + docId);

    if (mode === 'draw') {
        if (drawSec) drawSec.style.display = 'block';
        if (typeSec) typeSec.style.display = 'none';
        if (drawBtn) drawBtn.classList.add('active');
        if (typeBtn) typeBtn.classList.remove('active');
        setTimeout(function() { initSignatureCanvas(docId); }, 60);
    } else {
        if (drawSec) drawSec.style.display = 'none';
        if (typeSec) typeSec.style.display = 'block';
        if (drawBtn) drawBtn.classList.remove('active');
        if (typeBtn) typeBtn.classList.add('active');
    }
}

function updateTypePreview(docId, val) {
    var preview = document.getElementById('typeSigPreview-' + docId);
    if (preview) {
        preview.textContent = val || 'Your Signature';
    }
}

function handleSignatureSubmit(docId) {
    var mode = modeMap[docId] || 'draw';
    var dataInput = document.getElementById('sigDataInput-' + docId);
    var strokes = strokesMap[docId] || [];

    if (mode === 'draw') {
        if (strokes.length === 0) {
            alert('Please sign on the canvas with your finger or mouse before submitting.');
            return false;
        }
        if (canvasMap[docId]) {
            dataInput.value = canvasMap[docId].canvas.toDataURL('image/png');
        }
    } else {
        dataInput.value = '';
    }
    return true;
}

$(document).ready(function() {
    @foreach($documents as $doc)
        @if(($doc->action_required === 'sign_upload' || $doc->action_required === 'sign_pin') && $doc->status !== 'signed')
            setTimeout(function() { initSignatureCanvas({{ $doc->id }}); }, 120);
        @endif
    @endforeach

    @foreach($vaultDocuments ?? [] as $vDoc)
        @if($vDoc->requires_signature && !$vDoc->is_signed)
            setTimeout(function() { initSignatureCanvas('case_{{ $vDoc->id }}'); }, 150);
        @endif
    @endforeach

    // Re-initialize when accordion collapse or modal opens
    $('.collapse').on('shown.bs.collapse', function() {
        var id = $(this).attr('id');
        if (id && id.indexOf('signDocCollapse-') === 0) {
            var docId = id.replace('signDocCollapse-', '');
            initSignatureCanvas(docId);
        } else if (id && id.indexOf('signCollapse-') === 0) {
            var vDocId = id.replace('signCollapse-', '');
            initSignatureCanvas('case_' + vDocId);
        }
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var href = $(e.target).attr('href');
        if (href && href.indexOf('#pane-esign-') === 0) {
            var docId = href.replace('#pane-esign-', '');
            initSignatureCanvas(docId);
        }
    });
});
</script>
@endsection
