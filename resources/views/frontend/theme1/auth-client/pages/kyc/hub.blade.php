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
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .file-drop-zone {
        border: 2px dashed #3b4252;
        border-radius: 10px;
        padding: 18px;
        text-align: center;
        background: #11151e;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 8px;
    }
    .file-drop-zone:hover {
        border-color: #fecc56;
        background: rgba(254,204,86,0.05);
    }
    .input-kyc-dark {
        background: #11151e !important;
        border: 1px solid #28303f !important;
        color: #ffffff !important;
        border-radius: 6px !important;
        font-size: 13px !important;
    }
    .input-kyc-dark:focus {
        border-color: #fecc56 !important;
        box-shadow: 0 0 0 2px rgba(254,204,86,0.2) !important;
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
        padding: 10px 22px;
        font-size: 13.5px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-gold:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(254,204,86,0.45);
    }
    .badge-approved { background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); font-weight: bold; padding: 4px 10px; border-radius: 6px; font-size: 11px; }
    .badge-pending { background: rgba(245, 158, 11, 0.15); color: #fecc56; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: bold; padding: 4px 10px; border-radius: 6px; font-size: 11px; }
    .badge-resubmit { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: bold; padding: 4px 10px; border-radius: 6px; font-size: 11px; }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Top Status Hero Banner -->
    <div class="kyc-hero">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h4 class="font-weight-bold text-white mb-2">
                    <i class="fas fa-shield-alt text-warning mr-2"></i> {{ __('Identity Verification & Compliance') }}
                </h4>
                <p class="text-muted small mb-0">
                    {{ __('Verify your identity in compliance with federal anti-money laundering (AML) and financial regulatory standards.') }}
                </p>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                @if($verifiedCount > 0 && $pendingCount == 0)
                    <span class="badge-approved" style="font-size: 13px; padding: 8px 16px;">
                        <i class="fas fa-check-circle mr-1"></i> {{ __('Verified Client') }}
                    </span>
                @elseif($pendingCount > 0)
                    <span class="badge-pending" style="font-size: 13px; padding: 8px 16px;">
                        <i class="fas fa-clock mr-1"></i> {{ __('Verification Under Review') }}
                    </span>
                @else
                    <span class="badge-resubmit" style="font-size: 13px; padding: 8px 16px;">
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
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 font-weight-bold" style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3);">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <!-- Dynamic KYC Form (Exact IFW replica driven by admin fields) -->
        <div class="col-lg-6 mb-4">
            <div class="portal-card h-100">
                <div class="portal-card-header">
                    <span><i class="fas fa-id-card mr-2"></i> {{ __('Identity Verification Form') }}</span>
                    <span class="badge badge-warning text-dark font-weight-bold" style="font-size: 11px;">{{ count($kycFields) }} {{ __('Configured Fields') }}</span>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('client.kyc.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Render All Configured Fields Dynamically -->
                        @foreach($kycFields as $f)
                            @php
                                $type = strtoupper($f['type'] ?? 'TEXT');
                                $dbName = $f['db_name'];
                                $label = $f['label'];
                                $isReq = !empty($f['required']);
                                $defaultVal = '';
                                if ($dbName === 'full_name') $defaultVal = $client->name ?? '';
                                elseif ($dbName === 'email') $defaultVal = $client->email ?? '';
                                elseif ($dbName === 'phone') $defaultVal = $client->phone ?? '';
                                elseif ($dbName === 'address') $defaultVal = $client->address ?? '';
                            @endphp

                            @if($type === 'FILE')
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-white small">
                                        {{ $label }} @if($isReq) <span class="text-danger">*</span> @endif
                                    </label>
                                    <div class="file-drop-zone" onclick="document.getElementById('file_{{ $dbName }}').click();">
                                        <i class="fas fa-file-upload fa-2x text-warning mb-2 d-block"></i>
                                        <span class="font-weight-bold text-white d-block small" id="display_{{ $dbName }}">{{ __('Click to Browse & Select Document') }}</span>
                                        <small class="text-muted">{{ __('Supported: PDF, JPG, PNG, DOCX (Max 25MB)') }}</small>
                                        <input type="file" name="{{ $dbName }}" id="file_{{ $dbName }}" class="d-none" @if($isReq && $kycDocs->count() == 0) required @endif onchange="document.getElementById('display_{{ $dbName }}').innerText = this.files[0] ? this.files[0].name : 'Click to Browse';">
                                    </div>
                                </div>
                            @elseif($type === 'DATE')
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-white small">
                                        {{ $label }} @if($isReq) <span class="text-danger">*</span> @endif
                                    </label>
                                    <input type="date" name="{{ $dbName }}" class="form-control input-kyc-dark" value="{{ old($dbName, $defaultVal) }}" @if($isReq) required @endif>
                                </div>
                            @elseif($type === 'NUMBER')
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-white small">
                                        {{ $label }} @if($isReq) <span class="text-danger">*</span> @endif
                                    </label>
                                    <input type="number" name="{{ $dbName }}" class="form-control input-kyc-dark" value="{{ old($dbName, $defaultVal) }}" placeholder="Enter {{ $label }}" @if($isReq) required @endif>
                                </div>
                            @elseif($type === 'TEXTAREA')
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-white small">
                                        {{ $label }} @if($isReq) <span class="text-danger">*</span> @endif
                                    </label>
                                    <textarea name="{{ $dbName }}" rows="3" class="form-control input-kyc-dark" placeholder="Enter {{ $label }}" @if($isReq) required @endif>{{ old($dbName, $defaultVal) }}</textarea>
                                </div>
                            @else
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-white small">
                                        {{ $label }} @if($isReq) <span class="text-danger">*</span> @endif
                                    </label>
                                    <input type="text" name="{{ $dbName }}" class="form-control input-kyc-dark" value="{{ old($dbName, $defaultVal) }}" placeholder="Enter {{ $label }}" @if($isReq) required @endif>
                                </div>
                            @endif
                        @endforeach

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-white small">{{ __('Additional Remarks (Optional)') }}</label>
                            <textarea name="notes" class="form-control input-kyc-dark" rows="2" placeholder="Any additional comments or notes for compliance officers..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-gold btn-block py-2">
                            <i class="fas fa-shield-alt"></i> {{ __('Submit Identity Verification Package') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Verification Documents On File -->
        <div class="col-lg-6 mb-4">
            <div class="portal-card h-100">
                <div class="portal-card-header">
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
                                        <div class="font-weight-bold text-white">
                                            <i class="fas fa-file-alt text-warning mr-1"></i> {{ $doc->file_title ?: ($doc->document_name ?: $doc->document_type) }}
                                        </div>
                                        @if($doc->notes)
                                            <small class="text-muted">{{ $doc->notes }}</small>
                                        @endif
                                        @if($doc->admin_notes)
                                            <small class="text-danger d-block mt-1"><i class="fas fa-info-circle mr-1"></i> {{ $doc->admin_notes }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-light small">{{ $doc->created_at ? $doc->created_at->format('M d, Y') : '' }}</span>
                                    </td>
                                    <td>
                                        @if(strtolower($doc->status) === 'approved')
                                            <span class="badge-approved"><i class="fas fa-check-circle mr-1"></i> {{ __('Approved') }}</span>
                                        @elseif(strtolower($doc->status) === 'rejected')
                                            <span class="badge-resubmit"><i class="fas fa-times-circle mr-1"></i> {{ __('Rejected') }}</span>
                                        @else
                                            <span class="badge-pending"><i class="fas fa-clock mr-1"></i> {{ __('Pending') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($doc->file_path)
                                            <a href="{{ asset($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <span class="text-muted small">--</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-id-card fa-3x mb-3 text-secondary d-block"></i>
                                        <h6 class="text-white">{{ __('No Identity Documents On File') }}</h6>
                                        <p class="small text-muted">{{ __('Please complete the verification form on the left to activate full compliance status.') }}</p>
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
