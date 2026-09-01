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
    .doc-item {
        background: #161a23;
        border-bottom: 1px solid #232a38;
        padding: 20px 22px;
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
        padding: 6px 14px;
        font-size: 12px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
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
                <i class="fas fa-file-contract text-warning mr-2"></i> {{ __('Document Center & Executed Agreements') }}
            </h4>
            <p class="text-muted small mb-0">{{ __('Official case retainers, fee agreements, powers of attorney, and cryptographic authorizations.') }}</p>
        </div>
        <a href="{{ route('client.dashboard') }}" class="btn btn-sm btn-outline-secondary text-light font-weight-bold px-3">
            <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Dashboard') }}
        </a>
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
                                    @if($doc->action_required === 'sign_upload')
                                        <span class="badge-action text-warning border border-warning" style="background: rgba(254,204,86,0.1);">
                                            <i class="fas fa-signature mr-1"></i> {{ __('Signature & Upload Required') }}
                                        </span>
                                    @elseif($doc->action_required === 'sign_pin')
                                        <span class="badge-action text-warning border border-warning" style="background: rgba(254,204,86,0.1);">
                                            <i class="fas fa-fingerprint mr-1"></i> {{ __('PIN e-Signature Required') }}
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

                                <!-- Action Section: Electronic E-Signature / File Upload -->
                                @if(($doc->action_required === 'sign_upload' || $doc->action_required === 'sign_pin') && $doc->status !== 'rejected')
                                    @if($doc->status !== 'signed')
                                        <div class="w-100 border rounded p-3 text-left mt-2" style="background: #11151e; border-color: #28303f !important;">
                                            <!-- Tab switcher between Electronic Sign and File Upload -->
                                            <ul class="nav nav-pills nav-fill mb-3" style="gap: 6px;">
                                                <li class="nav-item">
                                                    <a class="nav-link active py-1 px-2 font-weight-bold" id="tab-esign-{{ $doc->id }}" data-toggle="pill" href="#pane-esign-{{ $doc->id }}" style="font-size: 11.5px; border-radius: 6px;">
                                                        <i class="fas fa-fingerprint mr-1"></i> {{ __('Quick E-Sign Now') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 px-2 font-weight-bold" id="tab-upload-{{ $doc->id }}" data-toggle="pill" href="#pane-upload-{{ $doc->id }}" style="font-size: 11.5px; border-radius: 6px;">
                                                        <i class="fas fa-file-upload mr-1"></i> {{ __('Upload Signed File') }}
                                                    </a>
                                                </li>
                                            </ul>

                                            <div class="tab-content">
                                                <!-- PANE 1: DIRECT ELECTRONIC E-SIGNATURE -->
                                                <div class="tab-pane fade show active" id="pane-esign-{{ $doc->id }}">
                                                    <form action="{{ route('client.documents.sign-electronic', $doc->id) }}" method="POST">
                                                        @csrf
                                                        <div class="form-group mb-2">
                                                            <label class="small font-weight-bold text-white mb-1">{{ __('Full Legal Name (Electronic Signature):') }} <span class="text-danger">*</span></label>
                                                            <input type="text" name="signature_text" class="form-control form-control-sm font-weight-bold" style="background: #161a23; border: 1px solid #28303f; color: #fecc56;" value="{{ Auth::user()->name }}" placeholder="Type your full legal name" required>
                                                        </div>

                                                        <div class="form-group mb-2">
                                                            <label class="small font-weight-bold text-white mb-1">{{ __('4-Digit Security PIN:') }} <span class="text-danger">*</span></label>
                                                            <input type="password" name="pin" maxlength="4" class="form-control form-control-sm text-center font-weight-bold" style="background: #161a23; border: 1px solid #28303f; color: #fecc56; letter-spacing: 4px;" placeholder="••••" required inputmode="numeric">
                                                        </div>

                                                        <div class="custom-control custom-checkbox mb-3">
                                                            <input type="checkbox" name="agreement_accepted" class="custom-control-input" id="agree-{{ $doc->id }}" value="1" required>
                                                            <label class="custom-control-label small text-muted font-weight-semibold" for="agree-{{ $doc->id }}">
                                                                {{ __('I certify that I have reviewed this agreement and agree that my typed signature and PIN constitute a legally binding execution.') }}
                                                            </label>
                                                        </div>

                                                        <div class="d-flex" style="gap: 8px;">
                                                            <button type="submit" class="btn btn-warning btn-sm flex-grow-1 font-weight-bold text-dark" style="background: linear-gradient(135deg, #fecc56, #f0a500); border: none;">
                                                                <i class="fas fa-file-signature mr-1"></i> {{ __('Authorize & Sign Electronically') }}
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
