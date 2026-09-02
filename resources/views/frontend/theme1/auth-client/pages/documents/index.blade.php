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
                                                            <div class="d-flex justify-content-end mb-2">
                                                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 11px;" onclick="clearSignatureCanvas({{ $doc->id }})">
                                                                    <i class="fas fa-eraser mr-1"></i> {{ __('Clear Signature') }}
                                                                </button>
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
var isDrawingMap = {};
var hasDrawnMap = {};
var modeMap = {};

function initSignatureCanvas(docId) {
    var canvas = document.getElementById('sigCanvas-' + docId);
    if (!canvas || canvasMap[docId]) return;

    var ctx = canvas.getContext('2d');
    var rect = canvas.getBoundingClientRect();
    canvas.width = canvas.offsetWidth || 360;
    canvas.height = 160;

    ctx.strokeStyle = '#0f172a';
    ctx.lineWidth = 2.5;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    canvasMap[docId] = { canvas: canvas, ctx: ctx };
    isDrawingMap[docId] = false;
    hasDrawnMap[docId] = false;
    modeMap[docId] = 'draw';

    function getPos(e) {
        var cRect = canvas.getBoundingClientRect();
        var clientX = e.touches ? e.touches[0].clientX : e.clientX;
        var clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return {
            x: (clientX - cRect.left) * (canvas.width / cRect.width),
            y: (clientY - cRect.top) * (canvas.height / cRect.height)
        };
    }

    function startDraw(e) {
        e.preventDefault();
        isDrawingMap[docId] = true;
        hasDrawnMap[docId] = true;
        var placeholder = document.getElementById('sigPlaceholder-' + docId);
        if (placeholder) placeholder.style.display = 'none';

        var pos = getPos(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    }

    function moveDraw(e) {
        if (!isDrawingMap[docId]) return;
        e.preventDefault();
        var pos = getPos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
    }

    function endDraw(e) {
        if (isDrawingMap[docId]) {
            isDrawingMap[docId] = false;
            ctx.closePath();
        }
    }

    canvas.addEventListener('mousedown', startDraw);
    canvas.addEventListener('mousemove', moveDraw);
    window.addEventListener('mouseup', endDraw);

    canvas.addEventListener('touchstart', startDraw, { passive: false });
    canvas.addEventListener('touchmove', moveDraw, { passive: false });
    canvas.addEventListener('touchend', endDraw);
}

function clearSignatureCanvas(docId) {
    if (canvasMap[docId]) {
        var c = canvasMap[docId].canvas;
        var ctx = canvasMap[docId].ctx;
        ctx.clearRect(0, 0, c.width, c.height);
        hasDrawnMap[docId] = false;
        var placeholder = document.getElementById('sigPlaceholder-' + docId);
        if (placeholder) placeholder.style.display = 'block';
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
        initSignatureCanvas(docId);
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

    if (mode === 'draw' && canvasMap[docId] && hasDrawnMap[docId]) {
        dataInput.value = canvasMap[docId].canvas.toDataURL('image/png');
    } else {
        dataInput.value = '';
    }
    return true;
}

$(document).ready(function() {
    @foreach($documents as $doc)
        @if(($doc->action_required === 'sign_upload' || $doc->action_required === 'sign_pin') && $doc->status !== 'signed')
            initSignatureCanvas({{ $doc->id }});
        @endif
    @endforeach
});
</script>
@endsection
