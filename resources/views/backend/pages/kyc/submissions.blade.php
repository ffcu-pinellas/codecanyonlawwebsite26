@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .kyc-sub-card {
        background: #161a24;
        border: 1px solid #283244;
        border-radius: 12px;
        overflow: hidden;
    }
    .kyc-sub-header {
        background: #11151e;
        border-bottom: 1px solid #283244;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .kyc-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        color: #cbd5e1;
    }
    .kyc-table th {
        background: #0d1017;
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 1px solid #283244;
    }
    .kyc-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #1e2636;
        vertical-align: middle;
    }
    .kyc-table tr:hover {
        background: rgba(255, 255, 255, 0.02);
    }
    .kyc-status-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 5px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .badge-approved {
        background: rgba(34, 197, 94, 0.15);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    .badge-pending {
        background: rgba(234, 179, 8, 0.15);
        color: #facc15;
        border: 1px solid rgba(234, 179, 8, 0.3);
    }
    .badge-rejected {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    .action-btn-sm {
        padding: 4px 10px;
        border-radius: 5px;
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
</style>
@endsection

@section('content')
<div id="wrapper-content">
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark">
                <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                <a class="breadcrumb-item text-white" href="{{ route('admin.kyc.config') }}">{{ __('KYC Configuration') }}</a>
                <span class="breadcrumb-item active">{{ __($title) }}</span>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <!-- Status Tabs -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap: 12px;">
        <div class="btn-group">
            <a href="{{ route('admin.kyc.submissions', ['status' => 'all']) }}" class="btn btn-sm {{ $status === 'all' ? 'btn-warning font-weight-bold text-dark' : 'btn-dark text-light' }}">
                {{ __('All Documents') }}
            </a>
            <a href="{{ route('admin.kyc.submissions', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-warning font-weight-bold text-dark' : 'btn-dark text-light' }}">
                {{ __('Pending Review') }} <span class="badge badge-danger ml-1">{{ $pendingCount }}</span>
            </a>
            <a href="{{ route('admin.kyc.submissions', ['status' => 'approved']) }}" class="btn btn-sm {{ $status === 'approved' ? 'btn-warning font-weight-bold text-dark' : 'btn-dark text-light' }}">
                {{ __('Approved') }} <span class="badge badge-success ml-1">{{ $approvedCount }}</span>
            </a>
            <a href="{{ route('admin.kyc.submissions', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status === 'rejected' ? 'btn-warning font-weight-bold text-dark' : 'btn-dark text-light' }}">
                {{ __('Rejected') }} <span class="badge badge-secondary ml-1">{{ $rejectedCount }}</span>
            </a>
        </div>

        <a href="{{ route('admin.kyc.config') }}" class="btn btn-sm btn-outline-warning">
            <i class="fas fa-cog mr-1"></i> {{ __('Configure KYC Form & Fields') }}
        </a>
    </div>

    <!-- Table Card -->
    <div class="kyc-sub-card">
        <div class="kyc-sub-header">
            <h6 class="mb-0 text-white font-weight-bold">
                <i class="fas fa-id-card text-warning mr-2"></i> {{ __('Client Verification Submissions') }}
            </h6>
        </div>

        <div class="table-responsive">
            <table class="kyc-table">
                <thead>
                    <tr>
                        <th>{{ __('Client / Account') }}</th>
                        <th>{{ __('Document Name / Type') }}</th>
                        <th>{{ __('File') }}</th>
                        <th>{{ __('Submitted Date') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions & Review') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $sub)
                    <tr>
                        <td>
                            <strong class="text-white">{{ $sub->client->name ?? 'Client #' . $sub->client_id }}</strong>
                            <small class="text-muted d-block">{{ $sub->client->email ?? 'N/A' }}</small>
                            @if($sub->clientCase)
                                <small class="text-warning d-block font-weight-bold">Case #{{ $sub->clientCase->case_number }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="text-white font-weight-bold">{{ $sub->document_name }}</span>
                            <small class="text-muted d-block">{{ $sub->document_type }}</small>
                        </td>
                        <td>
                            @if(!empty($sub->file_path))
                                <a href="{{ asset($sub->file_path) }}" target="_blank" class="action-btn-sm btn-dark text-warning border border-secondary">
                                    <i class="fas fa-file-download mr-1"></i> {{ __('View File') }}
                                </a>
                            @else
                                <span class="text-muted small">No file</span>
                            @endif
                        </td>
                        <td style="font-size: 12px; color: #94a3b8;">
                            {{ $sub->created_at ? $sub->created_at->format('M d, Y H:i') : 'N/A' }}
                        </td>
                        <td>
                            @if($sub->status === 'approved')
                                <span class="kyc-status-badge badge-approved"><i class="fas fa-check-circle"></i> {{ __('Approved') }}</span>
                            @elseif($sub->status === 'rejected')
                                <span class="kyc-status-badge badge-rejected"><i class="fas fa-times-circle"></i> {{ __('Rejected') }}</span>
                            @else
                                <span class="kyc-status-badge badge-pending"><i class="fas fa-clock"></i> {{ __('Pending') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex" style="gap: 6px;">
                                @if($sub->status !== 'approved')
                                    <form action="{{ route('admin.kyc.status', $sub->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="action-btn-sm btn-success border-0 text-white" title="{{ __('Approve Verification') }}">
                                            <i class="fas fa-check"></i> {{ __('Approve') }}
                                        </button>
                                    </form>
                                @endif

                                @if($sub->status !== 'rejected')
                                    <button type="button" class="action-btn-sm btn-danger border-0 text-white" onclick="openRejectModal('{{ $sub->id }}', '{{ addslashes($sub->document_name) }}')" title="{{ __('Reject') }}">
                                        <i class="fas fa-times"></i> {{ __('Reject') }}
                                    </button>
                                @endif
                            </div>
                            @if(!empty($sub->admin_notes))
                                <small class="text-danger d-block mt-1">Note: {{ $sub->admin_notes }}</small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 d-block text-secondary"></i>
                            <h5>{{ __('No KYC Submissions Found') }}</h5>
                            <p class="small text-muted">{{ __('Client uploaded identity documents will appear here for legal and compliance review.') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($submissions->hasPages())
            <div class="p-3">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal with Reason Note -->
<div class="modal fade" id="rejectKycModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark text-white border-danger">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-danger"><i class="fas fa-times-circle mr-2"></i> {{ __('Reject KYC Document') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="rejectKycForm" action="" method="POST">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <div class="modal-body">
                    <p class="small text-muted mb-3">{{ __('Please provide a reason for rejecting') }} <strong class="text-white" id="rejectDocTitle"></strong>. {{ __('The client will be notified to re-upload.') }}</p>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-light small">{{ __('Rejection Reason / Notes') }}</label>
                        <textarea name="admin_notes" class="form-control bg-dark text-white border-secondary" rows="3" required placeholder="e.g. Document image is blurry, expired date, or missing proof of address."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger btn-sm">{{ __('Confirm Rejection') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
function openRejectModal(id, title) {
    var form = document.getElementById('rejectKycForm');
    form.action = "{{ url('admin/kyc/status') }}/" + id;
    document.getElementById('rejectDocTitle').textContent = title;
    $('#rejectKycModal').modal('show');
}
</script>
@endsection
