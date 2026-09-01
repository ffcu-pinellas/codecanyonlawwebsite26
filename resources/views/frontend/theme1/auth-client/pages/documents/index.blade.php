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
        padding: 18px 20px;
        transition: background 0.15s;
    }
    .doc-item:hover {
        background: #1a202c;
    }
    .doc-item:last-child {
        border-bottom: none;
    }
    .icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        background: rgba(254, 204, 86, 0.12);
        color: #fecc56;
        border: 1px solid rgba(254, 204, 86, 0.25);
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
    }
    .badge-action {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
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

    <div class="portal-card">
        <div class="portal-card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-file-contract mr-2"></i> {{ __('Contracts, Retainers & Legal Agreements') }}</span>
            <span class="badge badge-warning text-dark font-weight-bold">{{ $documents->count() }} {{ __('Total Agreements') }}</span>
        </div>

        <div class="p-3 border-bottom" style="background: #11151e; border-color: #28303f !important;">
            <p class="text-muted mb-0 small">
                {{ __('Review, preview, sign, or approve personalized engagement agreements, retainer schedules, and legal authorizations prepared by your legal team.') }}
            </p>
        </div>

        <div class="p-0">
            @forelse($documents as $doc)
                <div class="doc-item">
                    <div class="row align-items-start">
                        <!-- Icon Column -->
                        <div class="col-md-1 text-center d-none d-md-block">
                            <div class="icon-box mx-auto">
                                <i class="fas fa-file-signature"></i>
                            </div>
                        </div>
                        <!-- Info Column -->
                        <div class="col-md-7 col-sm-12">
                            <div class="d-flex flex-wrap align-items-center mb-2" style="gap: 8px;">
                                <h6 class="font-weight-bold text-white mb-0">{{ $doc->template_title }}</h6>
                                
                                <!-- Action Required Badge -->
                                @if($doc->action_required === 'approve')
                                    <span class="badge badge-info badge-action" style="background: rgba(14,165,233,0.15); color: #38bdf8; border: 1px solid rgba(14,165,233,0.3);"><i class="fas fa-check-circle mr-1"></i>Approval Required</span>
                                @elseif($doc->action_required === 'sign_upload')
                                    <span class="badge badge-danger badge-action" style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3);"><i class="fas fa-pen-fancy mr-1"></i>Signature Required</span>
                                @else
                                    <span class="badge badge-secondary badge-action" style="background: rgba(148,163,184,0.15); color: #94a3b8; border: 1px solid rgba(148,163,184,0.3);">Records Only</span>
                                @endif

                                <!-- Status Badge -->
                                @if($doc->status === 'sent')
                                    <span class="badge badge-secondary badge-status">Sent</span>
                                @elseif($doc->status === 'viewed')
                                    <span class="badge badge-warning text-dark badge-status">Viewed</span>
                                @elseif($doc->status === 'approved')
                                    <span class="badge badge-success badge-status">Approved</span>
                                @elseif($doc->status === 'signed')
                                    <span class="badge badge-success badge-status">Signed</span>
                                @elseif($doc->status === 'rejected')
                                    <span class="badge badge-danger badge-status">Rejected</span>
                                @endif
                            </div>

                            <p class="text-muted mb-0 small">
                                {{ __('Issued on:') }} <strong class="text-white">{{ $doc->created_at->format('F d, Y h:i A') }}</strong> &bull; 
                                {{ __('Ref:') }} <code class="text-warning">{{ $doc->template_key }}</code>
                            </p>

                            <!-- Admin Notes Callout -->
                            @if($doc->admin_notes)
                                <div class="mt-2 text-white small p-2 rounded" style="background: #11151e; border: 1px solid #28303f; border-left: 3px solid #fecc56;">
                                    <strong class="text-warning">Administrator Instructions:</strong> {{ $doc->admin_notes }}
                                </div>
                            @endif

                            <!-- Recipient Notes Callout -->
                            @if($doc->recipient_notes)
                                <div class="mt-2 text-white small p-2 rounded" style="background: #11151e; border: 1px solid #28303f; border-left: 3px solid #22c55e;">
                                    <strong class="text-success">Your Response:</strong> {{ $doc->recipient_notes }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Interaction Column -->
                        <div class="col-md-4 col-sm-12 text-md-right mt-3 mt-md-0">
                            <div class="d-flex flex-column align-items-md-end w-100">
                                <!-- View & Print -->
                                <a href="{{ route('client.documents.print', $doc->id) }}" target="_blank" class="btn btn-gold btn-sm px-3 mb-2">
                                    <i class="fas fa-print mr-1"></i> {{ __('View & Print') }}
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

                                <!-- Action Section: Upload Signed Copy -->
                                @if($doc->action_required === 'sign_upload' && $doc->status !== 'rejected')
                                    @if($doc->status !== 'signed')
                                        <div class="w-100 border rounded p-3 text-left mt-2" style="background: #11151e; border-color: #28303f !important;">
                                            <form action="{{ route('client.documents.upload-signed', $doc->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <span class="small font-weight-bold text-warning d-block mb-2"><i class="fas fa-file-upload mr-1"></i> Upload Signed PDF/Image:</span>
                                                <input type="file" name="signed_file" class="form-control-file form-control-sm mb-2 text-white" required>
                                                
                                                <div class="form-group mb-2">
                                                    <label class="small font-weight-bold text-white mb-1">Add Note / Comment (Optional):</label>
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

                                            <div id="reject-form-{{ $doc->id }}" style="display:none;" class="mt-2 pt-2 border-top" style="border-color: #28303f !important;">
                                                <form action="{{ route('client.documents.reject', $doc->id) }}" method="POST">
                                                    @csrf
                                                    <span class="small font-weight-bold text-danger d-block mb-1">Reason for Rejection (Required):</span>
                                                    <textarea name="recipient_notes" class="form-control form-control-sm mb-2" rows="2" style="background: #161a23; border: 1px solid #28303f; color: #ffffff;" placeholder="Explain rejection reason..." required></textarea>
                                                    <button type="submit" class="btn btn-danger btn-sm btn-block">Confirm Rejection</button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex flex-wrap justify-content-md-end mt-1 w-100" style="gap: 6px;">
                                            <a href="{{ asset($doc->signed_path) }}" target="_blank" class="btn btn-success btn-sm">
                                                <i class="fas fa-file-download mr-1"></i> {{ __('Download Signed') }}
                                            </a>
                                            <button type="button" class="btn btn-portal-secondary btn-sm" onclick="$('#reupload-form-{{ $doc->id }}').toggle();">
                                                <i class="fas fa-sync mr-1"></i> {{ __('Replace') }}
                                            </button>
                                        </div>
                                        <div id="reupload-form-{{ $doc->id }}" style="display:none;" class="w-100 border rounded p-3 text-left mt-2" style="background: #11151e; border-color: #28303f !important;">
                                            <form action="{{ route('client.documents.upload-signed', $doc->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <span class="small font-weight-bold text-warning d-block mb-2">{{ __('Upload New Signed Version:') }}</span>
                                                <input type="file" name="signed_file" class="form-control-file form-control-sm mb-2 text-white" required>
                                                <button type="submit" class="btn btn-gold btn-sm btn-block">{{ __('Submit Replacement') }}</button>
                                            </form>
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
