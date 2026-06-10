@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .vault-card {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    .status-badge-pending { background-color: #ffeaa7; color: #d63031; font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    .status-badge-active { background-color: #dff9fb; color: #0984e3; font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    .status-badge-suspended { background-color: #ffcccc; color: #ff0000; font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    .status-badge-resolved { background-color: #e3fafc; color: #0ca678; font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    
    .section-title {
        font-weight: 700;
        color: #1a1a2e;
        font-family: 'Montserrat', sans-serif;
    }
    .attorney-info {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        border: 1px solid #e9ecef;
    }
    .attorney-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .file-icon {
        font-size: 1.5rem;
        margin-right: 12px;
    }
    .upload-box {
        border: 2px dashed #ccd1d9;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        background: #fdfdfd;
        transition: border-color 0.3s;
    }
    .upload-box:hover {
        border-color: #007bff;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Back Navigation -->
    <div class="mb-4">
        <a href="{{ route('client.cases.index') }}" class="text-primary font-weight-bold"><i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Case Directory') }}</a>
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

    <div class="row">
        <!-- Case Information details -->
        <div class="col-lg-5 mb-4">
            <div class="card vault-card p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="text-primary font-weight-bold" style="font-size: 0.85rem;">{{ $case->case_number }}</span>
                    <span class="status-badge-{{ $case->status }}">{{ ucfirst($case->status) }}</span>
                </div>
                <h4 class="font-weight-bold text-dark mb-3">{{ $case->title }}</h4>
                <p class="text-muted small mb-4" style="line-height: 1.6;">
                    {{ $case->description ?: __('No detailed description has been provided for this case representation.') }}
                </p>

                <div class="attorney-info d-flex align-items-center mb-4">
                    @if($case->attorney)
                        @if($case->attorney->attorney && $case->attorney->attorney->image)
                            <img src="{{ asset('upload/attorneys/' . $case->attorney->attorney->image) }}" class="attorney-avatar">
                        @else
                            <div class="attorney-avatar bg-primary text-white d-flex align-items-center justify-content-center font-weight-bold" style="width:45px; height:45px;">
                                {{ substr($case->attorney->name, 0, 2) }}
                            </div>
                        @endif
                        <div>
                            <small class="text-muted d-block uppercase font-weight-bold" style="font-size: 0.6rem;">{{ __('Assigned Legal Officer') }}</small>
                            <span class="font-weight-bold text-dark">{{ $case->attorney->name }}</span>
                            <small class="text-muted d-block">{{ $case->attorney->email }}</small>
                        </div>
                    @else
                        <div class="attorney-avatar bg-secondary text-white d-flex align-items-center justify-content-center font-weight-bold" style="width:45px; height:45px;">
                            ?
                        </div>
                        <div>
                            <small class="text-muted d-block uppercase font-weight-bold" style="font-size: 0.6rem;">{{ __('Assigned Legal Officer') }}</small>
                            <span class="text-muted font-weight-bold">{{ __('No Attorney Assigned') }}</span>
                        </div>
                    @endif
                </div>

                <div class="row pt-2 border-top border-light">
                    <div class="col-6">
                        <small class="text-muted d-block uppercase font-weight-bold" style="font-size: 0.6rem;">{{ __('Court/Due Date') }}</small>
                        <span class="text-dark font-weight-semibold small">
                            @if($case->court_date)
                                <i class="far fa-calendar-alt text-primary mr-1"></i> {{ $case->court_date->format('M d, Y h:i A') }}
                            @else
                                {{ __('Not Scheduled') }}
                            @endif
                        </span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block uppercase font-weight-bold" style="font-size: 0.6rem;">{{ __('Created Date') }}</small>
                        <span class="text-dark font-weight-semibold small">
                            <i class="far fa-clock text-primary mr-1"></i> {{ $case->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Upload Box Form -->
            <div class="card vault-card p-4">
                <h5 class="font-weight-bold text-dark mb-3" style="font-size: 1.05rem;"><i class="fas fa-cloud-upload-alt text-primary mr-2"></i> {{ __('Upload Documents to Vault') }}</h5>
                <p class="text-muted small mb-4">{{ __('Upload tax documents, pay stubs, identity proof or contract agreements directly and securely to this case vault.') }}</p>
                
                <form action="{{ route('client.cases.upload-document', $case->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="title" class="font-weight-semibold text-dark small">{{ __('Document Label/Title') }} <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" required placeholder="e.g. Identity Proof / Form W-2 / Signed Contract">
                        @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-semibold text-dark small d-block">{{ __('Select Document') }} <span class="text-danger">*</span></label>
                        <div class="upload-box">
                            <input type="file" name="file" id="file" class="form-control-file d-none" required onchange="$('#file-selected-name').text(this.files[0].name)">
                            <label for="file" style="cursor:pointer;" class="mb-0">
                                <i class="fas fa-file-upload fa-2x text-muted mb-2"></i>
                                <span class="d-block font-weight-semibold text-primary small">{{ __('Click to browse files') }}</span>
                                <span class="d-block text-muted small mt-1" style="font-size: 0.75rem;">{{ __('Supported: PDF, PNG, JPG, DOCX, XLSX (Max 20MB)') }}</span>
                            </label>
                            <span id="file-selected-name" class="d-block text-success font-weight-semibold small mt-2"></span>
                        </div>
                        @error('file') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2"><i class="fas fa-upload mr-1"></i> {{ __('Upload & Lock File') }}</button>
                </form>
            </div>
        </div>

        <!-- Document Vault List Table -->
        <div class="col-lg-7 mb-4">
            <div class="card vault-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-shield-alt text-success mr-2"></i> {{ __('Secure Document Vault') }}</h5>
                    <span class="badge badge-pill badge-success font-weight-bold">{{ $case->documents->count() }} {{ __('Files') }}</span>
                </div>

                @if($case->documents->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                        <h6 class="text-dark font-weight-bold">{{ __('Your secure vault is empty.') }}</h6>
                        <p class="text-muted small px-3">{{ __('Admins, legal officers, and you can upload filings or proof of identity to this secure container.') }}</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Document') }}</th>
                                    <th>{{ __('Uploaded By') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th class="text-right">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($case->documents as $doc)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if(in_array(strtolower($doc->file_type), ['pdf']))
                                                    <i class="far fa-file-pdf text-danger file-icon"></i>
                                                @elseif(in_array(strtolower($doc->file_type), ['png', 'jpg', 'jpeg']))
                                                    <i class="far fa-file-image text-info file-icon"></i>
                                                @elseif(in_array(strtolower($doc->file_type), ['doc', 'docx']))
                                                    <i class="far fa-file-word text-primary file-icon"></i>
                                                @else
                                                    <i class="far fa-file-alt text-secondary file-icon"></i>
                                                @endif
                                                <div>
                                                    <span class="font-weight-semibold text-dark d-block" style="font-size: 0.9rem;">{{ $doc->title }}</span>
                                                    <small class="text-muted">{{ number_format($doc->file_size / 1024, 1) }} KB</small>
                                                    @if($doc->is_client_uploaded)
                                                        <span class="badge badge-warning ml-1" style="font-size:0.6rem;">{{ __('My Upload') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="small font-weight-medium text-dark">{{ $doc->uploader->name }}</span>
                                        </td>
                                        <td>
                                            <span class="small text-muted">{{ $doc->created_at->format('M d, Y') }}</span>
                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                @if(in_array(strtolower($doc->file_type), ['pdf', 'jpg', 'jpeg', 'png']))
                                                    <button type="button" class="btn btn-outline-success btn-sm preview-btn mr-1" data-url="{{ route('client.documents.preview', $doc->id) }}" data-title="{{ $doc->title }}"><i class="fas fa-eye"></i></button>
                                                @endif
                                                <a href="{{ route('client.documents.download', $doc->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-download"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Document Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content bg-white text-dark">
            <div class="modal-header border-light">
                <h5 class="modal-title font-weight-bold text-dark" id="previewModalLabel">{{ __('Document Preview') }}</h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" style="height: 70vh;">
                <iframe id="previewFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    (function($) {
        "use strict";
        $('.preview-btn').on('click', function() {
            const url = $(this).data('url');
            const title = $(this).data('title');
            $('#previewModalLabel').text(title);
            $('#previewFrame').attr('src', url);
            $('#previewModal').modal('show');
        });

        // Clean frame on close
        $('#previewModal').on('hidden.bs.modal', function () {
            $('#previewFrame').attr('src', '');
        });
    })(jQuery);
</script>
@endsection
