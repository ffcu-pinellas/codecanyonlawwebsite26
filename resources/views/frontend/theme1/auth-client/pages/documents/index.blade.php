@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    .doc-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 25px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    .doc-item {
        transition: background-color 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }
    .doc-item:hover {
        background-color: #fafbfc;
    }
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .bg-light-primary {
        background-color: #eef2f6;
        color: #1a1a2e;
    }
    .badge-status {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-action {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-12">
            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card doc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h5 class="mb-0" style="font-weight: 700; color: #2c3e50;">
                            <i class="fas fa-folder-open mr-2 text-primary"></i>{{ __('Document Center') }}
                        </h5>
                        <span class="badge badge-primary font-weight-bold" style="padding: 6px 12px; font-size: 12px;">
                            {{ $documents->count() }} {{ __('Total Records') }}
                        </span>
                    </div>

                    <p class="text-muted mb-4">
                        {{ __('Review, preview, and complete actions on your personalized agreements and CPA templates. These documents have been generated and sent to you by the administrator.') }}
                    </p>

                    <div class="list-group list-group-flush">
                        @forelse($documents as $doc)
                            <div class="list-group-item px-0 py-4 doc-item">
                                <div class="row align-items-start">
                                    <!-- Icon Column -->
                                    <div class="col-md-1 text-center d-none d-md-block">
                                        <div class="icon-box bg-light-primary mx-auto">
                                            <i class="far fa-file-pdf"></i>
                                        </div>
                                    </div>
                                    <!-- Info Column -->
                                    <div class="col-md-7 col-sm-12">
                                        <div class="d-flex flex-wrap align-items-center mb-2">
                                            <h6 class="font-weight-bold text-dark mb-0 mr-3">{{ $doc->template_title }}</h6>
                                            
                                            <!-- Action Required Badge -->
                                            @if($doc->action_required === 'approve')
                                                <span class="badge badge-info badge-action mr-2"><i class="fas fa-check-circle mr-1"></i>Approval Required</span>
                                            @elseif($doc->action_required === 'sign_upload')
                                                <span class="badge badge-danger badge-action mr-2"><i class="fas fa-pen-fancy mr-1"></i>Signature Required</span>
                                            @else
                                                <span class="badge badge-light badge-action mr-2 text-muted">Records Only</span>
                                            @endif

                                            <!-- Status Badge -->
                                            @if($doc->status === 'sent')
                                                <span class="badge badge-secondary badge-status">Sent</span>
                                            @elseif($doc->status === 'viewed')
                                                <span class="badge badge-warning badge-status">Viewed</span>
                                            @elseif($doc->status === 'approved')
                                                <span class="badge badge-success badge-status">Approved</span>
                                            @elseif($doc->status === 'signed')
                                                <span class="badge badge-success badge-status">Signed</span>
                                            @elseif($doc->status === 'rejected')
                                                <span class="badge badge-danger badge-status">Rejected</span>
                                            @endif
                                        </div>

                                        <p class="text-muted mb-0 small">
                                            {{ __('Issued on:') }} <strong>{{ $doc->created_at->format('F d, Y h:i A') }}</strong> &bull; 
                                            {{ __('Ref:') }} <code>{{ $doc->template_key }}</code>
                                        </p>

                                        <!-- Admin Notes Callout -->
                                        @if($doc->admin_notes)
                                            <div class="mt-3 text-muted small alert alert-light py-2 px-3 border mb-0" style="font-size: 13px; border-left: 3px solid #6c757d !important; background-color: #fafbfc;">
                                                <strong>Administrator Instructions:</strong> {{ $doc->admin_notes }}
                                            </div>
                                        @endif

                                        <!-- Recipient Notes Callout -->
                                        @if($doc->recipient_notes)
                                            <div class="mt-2 text-muted small alert alert-success py-2 px-3 border mb-0" style="font-size: 13px; border-left: 3px solid #28a745 !important; background-color: #f8fff9;">
                                                <strong>Your Response Notes:</strong> {{ $doc->recipient_notes }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Interaction / Button Column -->
                                    <div class="col-md-4 col-sm-12 text-md-right mt-3 mt-md-0">
                                        <div class="d-flex flex-column align-items-md-end w-100">
                                            <!-- Always Show Print/View -->
                                            <a href="{{ route('client.documents.print', $doc->id) }}" target="_blank" class="btn btn-outline-primary btn-sm px-4 mb-2">
                                                <i class="fas fa-print mr-1"></i> {{ __('View & Print') }}
                                            </a>

                                            <!-- Action Section: Approve -->
                                            @if($doc->action_required === 'approve' && $doc->status !== 'approved' && $doc->status !== 'rejected')
                                                <div class="w-100 border rounded p-3 bg-light text-left">
                                                    <form action="{{ route('client.documents.approve', $doc->id) }}" method="POST" class="mb-2">
                                                        @csrf
                                                        <div class="form-group mb-2">
                                                            <label class="small font-weight-bold text-dark mb-1">Add Note/Comment (Optional):</label>
                                                            <textarea name="recipient_notes" class="form-control form-control-sm" rows="2" placeholder="e.g. Reviewed and approved."></textarea>
                                                        </div>
                                                        <div class="d-flex">
                                                            <button type="submit" class="btn btn-success btn-sm flex-grow-1 mr-2">
                                                                <i class="fas fa-check mr-1"></i> {{ __('Approve') }}
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="$('#reject-form-{{ $doc->id }}').toggle();">
                                                                <i class="fas fa-times mr-1"></i> {{ __('Reject') }}
                                                            </button>
                                                        </div>
                                                    </form>

                                                    <!-- Reject form block -->
                                                    <div id="reject-form-{{ $doc->id }}" style="display:none;" class="mt-2 pt-2 border-top">
                                                        <form action="{{ route('client.documents.reject', $doc->id) }}" method="POST">
                                                            @csrf
                                                            <span class="small font-weight-bold text-danger d-block mb-1">Reason for Rejection (Required):</span>
                                                            <textarea name="recipient_notes" class="form-control form-control-sm mb-2" rows="2" placeholder="Explain rejection reason..." required></textarea>
                                                            <button type="submit" class="btn btn-danger btn-sm btn-block">Confirm Rejection</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Action Section: Upload Signed Copy -->
                                            @if($doc->action_required === 'sign_upload' && $doc->status !== 'rejected')
                                                @if($doc->status !== 'signed')
                                                    <div class="card bg-light border p-3 text-left w-100 mt-1">
                                                        <form action="{{ route('client.documents.upload-signed', $doc->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <span class="small font-weight-bold text-dark d-block mb-1"><i class="fas fa-file-upload mr-1"></i> Upload Signed PDF/Image:</span>
                                                            <input type="file" name="signed_file" class="form-control-file form-control-sm mb-2" required>
                                                            
                                                            <div class="form-group mb-2">
                                                                <label class="small font-weight-bold text-dark mb-1">Add Note/Comment (Optional):</label>
                                                                <textarea name="recipient_notes" class="form-control form-control-sm" rows="2" placeholder="e.g. Attached the signed version."></textarea>
                                                            </div>
                                                            
                                                            <div class="d-flex">
                                                                <button type="submit" class="btn btn-danger btn-sm flex-grow-1 mr-2">
                                                                    <i class="fas fa-upload mr-1"></i> {{ __('Upload') }}
                                                                </button>
                                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="$('#reject-form-{{ $doc->id }}').toggle();">
                                                                    <i class="fas fa-times mr-1"></i> {{ __('Reject') }}
                                                                </button>
                                                            </div>
                                                        </form>

                                                        <!-- Reject form block -->
                                                        <div id="reject-form-{{ $doc->id }}" style="display:none;" class="mt-2 pt-2 border-top">
                                                            <form action="{{ route('client.documents.reject', $doc->id) }}" method="POST">
                                                                @csrf
                                                                <span class="small font-weight-bold text-danger d-block mb-1">Reason for Rejection (Required):</span>
                                                                <textarea name="recipient_notes" class="form-control form-control-sm mb-2" rows="2" placeholder="Explain rejection reason..." required></textarea>
                                                                <button type="submit" class="btn btn-danger btn-sm btn-block">Confirm Rejection</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Signed Copy Download / Reupload options -->
                                                    <div class="d-flex flex-wrap justify-content-md-end mt-1 w-100">
                                                        <a href="{{ asset($doc->signed_path) }}" target="_blank" class="btn btn-success btn-sm mr-1 mb-1">
                                                            <i class="fas fa-file-download mr-1"></i> {{ __('Download Signed') }}
                                                        </a>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm mb-1" onclick="$('#reupload-form-{{ $doc->id }}').toggle();">
                                                            <i class="fas fa-sync mr-1"></i> {{ __('Replace') }}
                                                        </button>
                                                    </div>
                                                    <div id="reupload-form-{{ $doc->id }}" style="display:none;" class="card bg-light border p-3 text-left w-100 mt-1">
                                                        <form action="{{ route('client.documents.upload-signed', $doc->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <span class="small font-weight-bold text-dark d-block mb-1">{{ __('Upload New Signed Version:') }}</span>
                                                            <input type="file" name="signed_file" class="form-control-file form-control-sm mb-2" required>
                                                            
                                                            <div class="form-group mb-2">
                                                                <label class="small font-weight-bold text-dark mb-1">Add Note/Comment (Optional):</label>
                                                                <textarea name="recipient_notes" class="form-control form-control-sm" rows="2" placeholder="e.g. Updated signed version."></textarea>
                                                            </div>

                                                            <button type="submit" class="btn btn-danger btn-sm btn-block">{{ __('Submit Replacement') }}</button>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                                <p class="text-muted mb-0">{{ __('No secure documents or agreement records logged at the moment.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
