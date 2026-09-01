@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    .kyc-hero {
        background: linear-gradient(135deg, #161a23 0%, #0e1117 100%);
        border: 1px solid #28303f;
        border-radius: 12px;
        padding: 22px 26px;
        margin-bottom: 24px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    }
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
    .file-drop-zone {
        border: 2px dashed #3b4252;
        border-radius: 10px;
        padding: 26px;
        text-align: center;
        background: #11151e;
        cursor: pointer;
        transition: all 0.2s;
    }
    .file-drop-zone:hover {
        border-color: #fecc56;
        background: rgba(254,204,86,0.05);
    }
    .table-portal {
        width: 100%;
        color: #f1f5f9;
        margin-bottom: 0;
        border-collapse: collapse;
    }
    .table-portal thead th {
        background: #191f2c;
        color: #fecc56;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 2px solid #28303f;
    }
    .table-portal tbody tr {
        border-bottom: 1px solid #232a38;
        transition: background 0.15s;
    }
    .table-portal td {
        padding: 14px 16px;
        font-size: 13px;
        vertical-align: middle;
    }
    .btn-gold {
        background: linear-gradient(135deg, #fecc56, #f0a500);
        color: #000 !important;
        font-weight: 700;
        border: none;
        border-radius: 6px;
        padding: 8px 18px;
        font-size: 13px;
        transition: all 0.2s;
    }
    .btn-gold:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(254,204,86,0.45);
    }
    .badge-approved { background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); font-weight: bold; padding: 4px 10px; border-radius: 6px; font-size: 11px; }
    .badge-pending { background: rgba(245, 158, 11, 0.15); color: #fecc56; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: bold; padding: 4px 10px; border-radius: 6px; font-size: 11px; }
    .badge-resubmit { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: bold; padding: 4px 10px; border-radius: 6px; font-size: 11px; }
    @media (max-width: 991px) {
        .table-portal thead { display: none; }
        .table-portal, .table-portal tbody, .table-portal tr, .table-portal td { display: block; width: 100%; }
        .table-portal tbody tr { margin-bottom: 12px; border: 1px solid #28303f; border-radius: 10px; padding: 12px; background: #161a23; }
        .table-portal td { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #1f2533; }
        .table-portal td:last-child { border-bottom: none; }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Top Hero Row -->
    <div class="kyc-hero">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge mb-2 font-weight-bold" style="background: rgba(254,204,86,0.15); color: #fecc56; border: 1px solid rgba(254,204,86,0.3); padding: 4px 12px; font-size: 11px;">
                    <i class="fas fa-id-card mr-1"></i> {{ __('Regulatory Compliance & Identity Verification') }}
                </span>
                <h4 class="font-weight-bold text-white mb-1">{{ __('Client Identity & KYC Verification Hub') }}</h4>
                <p class="text-muted small mb-0">{{ __('Verify your identity in compliance with federal anti-money laundering (AML) and financial regulatory standards.') }}</p>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                @if($verifiedCount > 0)
                    <span class="badge-approved px-3 py-2 d-inline-flex align-items-center" style="font-size: 13px;">
                        <i class="fas fa-check-circle mr-1"></i> {{ __('Identity Verified') }}
                    </span>
                @elseif($pendingCount > 0)
                    <span class="badge-pending px-3 py-2 d-inline-flex align-items-center" style="font-size: 13px;">
                        <i class="fas fa-clock mr-1"></i> {{ __('Under Legal Review') }}
                    </span>
                @else
                    <span class="badge-resubmit px-3 py-2 d-inline-flex align-items-center" style="font-size: 13px;">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ __('Verification Required') }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 font-weight-bold" style="background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3);">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <!-- Submission Form -->
        <div class="col-lg-5 mb-4">
            <div class="portal-card h-100">
                <div class="portal-card-header">
                    <i class="fas fa-upload mr-2"></i> {{ __('Submit Identity Verification Document') }}
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('client.kyc.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-white small">{{ __('Government Document Type') }} <span class="text-danger">*</span></label>
                            <select name="document_type" class="form-control" style="background: #11151e; border: 1px solid #28303f; color: #ffffff;" required>
                                <option value="Passport">{{ __('International Passport') }}</option>
                                <option value="Driver License">{{ __('State Driver\'s License (Front & Back)') }}</option>
                                <option value="National Identity Card">{{ __('National ID Card') }}</option>
                                <option value="Residence Permit">{{ __('Permanent Residence Card') }}</option>
                                <option value="Proof of Address">{{ __('Proof of Address (Utility Bill / Bank Statement)') }}</option>
                            </select>
                            @error('document_type') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-white small">{{ __('Document / ID Number (Optional)') }}</label>
                            <input type="text" name="document_number" class="form-control" style="background: #11151e; border: 1px solid #28303f; color: #ffffff;" placeholder="e.g. A12345678">
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-white small">{{ __('Upload Document File / Photo') }} <span class="text-danger">*</span></label>
                            <div class="file-drop-zone" onclick="document.getElementById('kycFileInput').click();">
                                <i class="fas fa-id-card fa-2x text-warning mb-2 d-block"></i>
                                <span class="font-weight-bold text-white d-block small" id="kycFileNameDisplay">{{ __('Click to Browse & Select File') }}</span>
                                <small class="text-muted">{{ __('Supported: PDF, JPG, PNG, DOCX (Max 20MB)') }}</small>
                                <input type="file" name="file" id="kycFileInput" class="d-none" required onchange="document.getElementById('kycFileNameDisplay').innerText = this.files[0] ? this.files[0].name : 'Click to Browse';">
                            </div>
                            @error('file') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-white small">{{ __('Additional Remarks (Optional)') }}</label>
                            <textarea name="notes" class="form-control" rows="2" style="background: #11151e; border: 1px solid #28303f; color: #ffffff;" placeholder="Issuing authority, state, or comments..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-gold btn-block py-2 font-weight-bold">
                            <i class="fas fa-shield-alt mr-1"></i> {{ __('Submit for Verification') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Submission History Table -->
        <div class="col-lg-7 mb-4">
            <div class="portal-card h-100">
                <div class="portal-card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-history mr-2"></i> {{ __('Verification Documents On File') }}</span>
                    <span class="badge badge-dark text-warning">{{ $kycDocs->count() }} {{ __('Records') }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table-portal">
                        <thead>
                            <tr>
                                <th>{{ __('Document Name') }}</th>
                                <th>{{ __('Submitted Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kycDocs as $doc)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-white"><i class="fas fa-file-alt text-warning mr-1"></i> {{ $doc->document_name }}</div>
                                        @if($doc->notes)
                                            <small class="text-muted">{{ $doc->notes }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-light small">{{ $doc->created_at ? $doc->created_at->format('M d, Y') : '' }}</span>
                                    </td>
                                    <td>
                                        @if(strtolower($doc->status) === 'approved')
                                            <span class="badge-approved"><i class="fas fa-check"></i> {{ __('Verified') }}</span>
                                        @elseif(strtolower($doc->status) === 'rejected')
                                            <span class="badge-resubmit"><i class="fas fa-times"></i> {{ __('Resubmit') }}</span>
                                        @else
                                            <span class="badge-pending"><i class="fas fa-clock"></i> {{ __('Under Review') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ asset($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary text-warning" style="border-color: #3b4252; font-size: 11px; padding: 4px 10px;">
                                            <i class="fas fa-eye mr-1"></i> {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-id-card fa-3x mb-3 d-block text-secondary"></i>
                                        {{ __('No identity verification documents submitted yet.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
