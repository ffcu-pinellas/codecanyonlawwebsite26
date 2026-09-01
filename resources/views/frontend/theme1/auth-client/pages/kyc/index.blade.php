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
    .table-portal tbody tr:hover {
        background: #1a202c;
    }
    .table-portal td {
        padding: 14px 16px;
        font-size: 13px;
        vertical-align: middle;
    }
    .badge-approved { background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); font-weight: bold; padding: 4px 8px; border-radius: 4px; }
    .badge-pending { background: rgba(245, 158, 11, 0.15); color: #fecc56; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: bold; padding: 4px 8px; border-radius: 4px; }
    .badge-resubmit { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: bold; padding: 4px 8px; border-radius: 4px; }
    
    .form-dark .form-control {
        background: #0f172a !important;
        border: 1px solid #334155 !important;
        color: #ffffff !important;
    }
    .form-dark .form-control:focus {
        border-color: #fecc56 !important;
        box-shadow: 0 0 0 2px rgba(254, 204, 86, 0.2) !important;
    }
    @media (max-width: 991px) {
        .table-portal thead { display: none; }
        .table-portal, .table-portal tbody, .table-portal tr, .table-portal td { display: block; width: 100%; }
        .table-portal tbody tr {
            margin-bottom: 14px;
            border: 1px solid #28303f;
            border-radius: 10px;
            padding: 12px 14px;
            background: #161a23;
        }
        .table-portal td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #1f2533;
            text-align: right;
        }
        .table-portal td:last-child {
            border-bottom: none;
            padding-top: 10px;
            justify-content: flex-end;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0 py-3">
    <!-- Header Row -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="font-weight-bold text-white mb-1">
                <i class="fas fa-file-upload text-warning mr-2"></i> {{ __('Upload Documents') }}
            </h4>
            <p class="text-muted small mb-0">{{ __('Securely upload your tax forms, identification, or financial statements for your Attorney & CPA.') }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success font-weight-bold mb-4" style="background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80;">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Upload Form Card -->
        <div class="col-lg-5 mb-4">
            <div class="portal-card form-dark">
                <div class="portal-card-header">
                    <i class="fas fa-cloud-upload-alt mr-1"></i> {{ __('Select File to Upload') }}
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('client.kyc.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="document_type" class="font-weight-bold text-light small">{{ __('Document Type') }} <span class="text-danger">*</span></label>
                            <select name="document_type" id="document_type" class="form-control" required>
                                <option value="Tax Return (W-2 / 1099 / 1040)">{{ __('Tax Return (W-2 / 1099 / 1040)') }}</option>
                                <option value="Corporate Articles / Operating Agreement">{{ __('Corporate Articles / Business Documents') }}</option>
                                <option value="Government-Issued Identification">{{ __('Government ID (Passport / Driver License)') }}</option>
                                <option value="Bank / Brokerage Statement">{{ __('Bank or Financial Statement') }}</option>
                                <option value="Proof of Address / Utility">{{ __('Proof of Address') }}</option>
                                <option value="IRS / State Tax Notice">{{ __('Tax Notice / IRS Letter') }}</option>
                                <option value="Other Privileged Document">{{ __('Other Document') }}</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="file_title" class="font-weight-bold text-light small">{{ __('File Name / Description') }} <span class="text-danger">*</span></label>
                            <input type="text" name="file_title" id="file_title" class="form-control" required placeholder="e.g. 2024 Tax Return">
                        </div>

                        @if(count($cases) > 0)
                            <div class="form-group mb-3">
                                <label for="case_id" class="font-weight-bold text-light small">{{ __('Related Case (Optional)') }}</label>
                                <select name="case_id" id="case_id" class="form-control">
                                    <option value="">-- {{ __('General Client Records') }} --</option>
                                    @foreach($cases as $c)
                                        <option value="{{ $c->id }}">{{ $c->case_number }} - {{ $c->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="form-group mb-4">
                            <label for="document_file" class="font-weight-bold text-light small">{{ __('Choose File') }} (PDF, DOCX, JPG, PNG, XLSX) <span class="text-danger">*</span></label>
                            <input type="file" name="document_file" id="document_file" class="form-control-file text-white" required>
                        </div>

                        <button type="submit" class="btn btn-warning btn-block font-weight-bold text-dark py-2 shadow-sm" style="background: linear-gradient(135deg, #fecc56, #f0a500); border: none;">
                            <i class="fas fa-upload mr-1"></i> {{ __('Upload Document') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Uploaded Documents History Table -->
        <div class="col-lg-7 mb-4">
            <div class="portal-card">
                <div class="portal-card-header">
                    <i class="fas fa-folder-open mr-1"></i> {{ __('Uploaded Documents & Verification Status') }}
                </div>
                <div class="table-responsive">
                    <table class="table-portal">
                        <thead>
                            <tr>
                                <th>{{ __('Document') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $doc)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-white">{{ $doc->file_title }}</div>
                                        <small class="text-muted">{{ $doc->file_size ?: 'Encrypted' }}</small>
                                    </td>
                                    <td><span class="text-warning small font-weight-bold">{{ $doc->document_type }}</span></td>
                                    <td>
                                        @if($doc->status === 'Approved' || $doc->status === 'Verified')
                                            <span class="badge-approved"><i class="fas fa-check-circle mr-1"></i> {{ __('Verified') }}</span>
                                        @elseif($doc->status === 'Needs Resubmission')
                                            <span class="badge-resubmit"><i class="fas fa-redo mr-1"></i> {{ __('Resubmit') }}</span>
                                        @else
                                            <span class="badge-pending"><i class="fas fa-clock mr-1"></i> {{ __('In Review') }}</span>
                                        @endif
                                    </td>
                                    <td><small class="text-muted">{{ $doc->created_at ? $doc->created_at->format('M d, Y') : '' }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-folder-open fa-2x mb-2 d-block text-secondary"></i>
                                        {{ __('No documents uploaded yet.') }}
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
