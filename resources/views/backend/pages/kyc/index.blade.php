@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .kyc-main-card {
        background: #161a24;
        border: 1px solid #283244;
        border-radius: 12px;
        overflow: hidden;
    }
    .kyc-main-header {
        background: #11151e;
        border-bottom: 1px solid #283244;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
    }
    .btn-configure-fields {
        background: transparent;
        border: 1px solid #fecc56;
        color: #fecc56;
        font-weight: 700;
        font-size: 13px;
        padding: 8px 18px;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .btn-configure-fields:hover {
        background: rgba(254, 204, 86, 0.15);
        color: #ffffff;
    }
    .kyc-table {
        width: 100%;
        border-collapse: collapse;
        color: #cbd5e1;
        font-size: 13px;
    }
    .kyc-table th {
        background: #0d1017;
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        padding: 14px 18px;
        border-bottom: 1px solid #283244;
    }
    .kyc-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #1e2636;
        vertical-align: middle;
    }
    .kyc-table tr:hover {
        background: rgba(255, 255, 255, 0.02);
    }
    .badge-approved { background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); font-weight: bold; padding: 4px 10px; border-radius: 5px; font-size: 11px; text-transform: uppercase; }
    .badge-pending { background: rgba(245, 158, 11, 0.15); color: #fecc56; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: bold; padding: 4px 10px; border-radius: 5px; font-size: 11px; text-transform: uppercase; }
    .badge-rejected { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: bold; padding: 4px 10px; border-radius: 5px; font-size: 11px; text-transform: uppercase; }

    /* IFW Configuration Modal Styling */
    .ifw-modal-content {
        background: #141720 !important;
        border: 1px solid #374358 !important;
        border-radius: 12px !important;
        color: #f1f5f9;
        box-shadow: 0 16px 50px rgba(0,0,0,0.85);
    }
    .ifw-modal-header {
        background: #0f121a;
        border-bottom: 1px solid #283244;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .ifw-modal-title {
        font-size: 14.5px;
        font-weight: 800;
        color: #f97316;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .db-name-badge {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
        font-family: monospace;
        font-size: 11.5px;
        padding: 2px 6px;
        border-radius: 4px;
    }
    .type-badge {
        background: #1e2533;
        color: #94a3b8;
        font-size: 10.5px;
        font-weight: 700;
        padding: 3px 6px;
        border-radius: 4px;
    }
    .req-badge {
        background: #dc2626;
        color: #ffffff;
        font-size: 10.5px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 4px;
    }
    .opt-badge {
        background: #374151;
        color: #9ca3af;
        font-size: 10.5px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 4px;
    }
    .btn-edit-field {
        background: transparent;
        border: 1px solid #374151;
        color: #94a3b8;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-edit-field:hover {
        border-color: #fecc56;
        color: #fecc56;
    }
    .btn-del-field {
        background: transparent;
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #f87171;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-del-field:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #ffffff;
    }
    .add-field-box {
        background: #0e1118;
        border: 1px solid #283244;
        border-radius: 8px;
        padding: 16px;
        margin-top: 20px;
    }
    .input-ifw-dark {
        background: #161a24 !important;
        border: 1px solid #283244 !important;
        color: #ffffff !important;
        border-radius: 6px !important;
        font-size: 12.5px !important;
    }
    .btn-orange-submit {
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 13px;
        border: none;
        border-radius: 6px;
        padding: 8px 18px;
        cursor: pointer;
        transition: opacity 0.15s;
    }
    .btn-orange-submit:hover {
        opacity: 0.9;
    }
</style>
@endsection

@section('content')
<div id="wrapper-content">
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark">
                <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
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

    <div class="kyc-main-card">
        <div class="kyc-main-header">
            <div>
                <h5 class="mb-1 text-white font-weight-bold">
                    <i class="fas fa-id-card text-warning mr-2"></i> {{ __('KYC VERIFICATION') }}
                </h5>
                <p class="text-muted small mb-0">{{ __('Review submitted verification documents and manage custom client fields.') }}</p>
            </div>
            <div>
                <button type="button" class="btn-configure-fields" data-toggle="modal" data-target="#kycConfigModal">
                    <i class="fas fa-sliders-h"></i> {{ __('Configure KYC Form Fields') }}
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="kyc-table">
                <thead>
                    <tr>
                        <th>{{ __('Sub ID') }}</th>
                        <th>{{ __('Client Name') }}</th>
                        <th>{{ __('Document / ID Name') }}</th>
                        <th>{{ __('Submitted Date') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $sub)
                    <tr>
                        <td><strong class="text-warning">#{{ $sub->id }}</strong></td>
                        <td>
                            <strong class="text-white">{{ $sub->client->name ?? 'Client #' . $sub->client_id }}</strong>
                            <small class="text-muted d-block">{{ $sub->client->email ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <span class="text-white">{{ $sub->file_title ?: ($sub->document_name ?: $sub->document_type) }}</span>
                            <small class="text-muted d-block">{{ $sub->document_type }}</small>
                        </td>
                        <td>{{ $sub->created_at ? $sub->created_at->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            @if(strtolower($sub->status) === 'approved')
                                <span class="badge-approved"><i class="fas fa-check-circle mr-1"></i> {{ __('Approved') }}</span>
                            @elseif(strtolower($sub->status) === 'rejected')
                                <span class="badge-rejected"><i class="fas fa-times-circle mr-1"></i> {{ __('Rejected') }}</span>
                            @else
                                <span class="badge-pending"><i class="fas fa-clock mr-1"></i> {{ __('Pending') }}</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-dark text-light border border-secondary font-weight-bold" onclick="openReviewModal('{{ $sub->id }}', '{{ addslashes($sub->client->name ?? 'Client') }}', '{{ addslashes($sub->file_title ?: $sub->document_type) }}', '{{ asset($sub->file_path) }}', '{{ $sub->status }}', '{{ addslashes($sub->admin_notes ?? '') }}')">
                                <i class="fas fa-eye mr-1"></i> {{ __('Review Documents') }}
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 d-block text-secondary"></i>
                            <h6>{{ __('No KYC Submissions Found') }}</h6>
                            <p class="small text-muted mb-0">{{ __('Client identity submissions will appear here for review.') }}</p>
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

<!-- ═══════════════════════════════════════════════════════════════════ -->
<!-- MODAL 1: DYNAMIC KYC FIELDS CONFIGURATION (EXACT IFW REPLICA)      -->
<!-- ═══════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="kycConfigModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content ifw-modal-content">
            <div class="ifw-modal-header">
                <h6 class="ifw-modal-title">
                    <i class="fas fa-sliders-h"></i> {{ __('DYNAMIC KYC FIELDS CONFIGURATION') }}
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body p-4">
                <p class="text-muted small mb-4">
                    {{ __('Add or remove fields that clients must fill out during Identity Verification. The client portal will automatically generate the form based on these fields. Clients can modify their submissions as long as admin or assigned staff hasn\'t approved. If rejected, client has to resubmit.') }}
                </p>

                <!-- Fields Table -->
                <div class="table-responsive">
                    <table class="table table-dark table-sm mb-0" style="background: transparent;">
                        <thead>
                            <tr style="border-bottom: 1px solid #283244; color: #fecc56; font-size: 11.5px; text-transform: uppercase;">
                                <th style="width: 30px;">#</th>
                                <th>{{ __('Field Label') }}</th>
                                <th>{{ __('DB Name') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Options') }}</th>
                                <th>{{ __('Required') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fields as $f)
                            <tr style="border-bottom: 1px solid #1e2636; font-size: 12.5px;">
                                <td><span class="text-muted">{{ $f['order'] ?? $loop->iteration }}</span></td>
                                <td><strong class="text-white">{{ $f['label'] }}</strong></td>
                                <td><span class="db-name-badge">{{ $f['db_name'] }}</span></td>
                                <td><span class="type-badge">{{ $f['type'] }}</span></td>
                                <td><span class="text-muted small">{{ $f['options'] ?: '—' }}</span></td>
                                <td>
                                    @if(!empty($f['required']))
                                        <span class="req-badge">{{ __('Required') }}</span>
                                    @else
                                        <span class="opt-badge">{{ __('Optional') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex" style="gap: 5px;">
                                        <button type="button" class="btn-edit-field" onclick="openEditFieldModal('{{ addslashes($f['label']) }}', '{{ $f['db_name'] }}', '{{ $f['type'] }}', '{{ $f['order'] ?? $loop->iteration }}', {{ !empty($f['required']) ? 'true' : 'false' }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.kyc.field.delete', $f['db_name']) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this KYC field?');">
                                            @csrf
                                            <button type="submit" class="btn-del-field">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ADD NEW KYC FIELD -->
                <div class="add-field-box">
                    <h6 class="text-warning font-weight-bold small text-uppercase mb-3">
                        <i class="fas fa-plus-circle mr-1"></i> {{ __('ADD NEW KYC FIELD') }}
                    </h6>
                    <form action="{{ route('admin.kyc.field.add') }}" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-3 form-group mb-2">
                                <label class="small text-muted mb-1">{{ __('Field Label *') }}</label>
                                <input type="text" name="label" class="form-control input-ifw-dark" placeholder="e.g. Utility Bill" required>
                            </div>
                            <div class="col-md-3 form-group mb-2">
                                <label class="small text-muted mb-1">{{ __('DB Name *') }}</label>
                                <input type="text" name="db_name" class="form-control input-ifw-dark" placeholder="e.g. util_bill" required>
                            </div>
                            <div class="col-md-2 form-group mb-2">
                                <label class="small text-muted mb-1">{{ __('Type') }}</label>
                                <select name="type" class="form-control input-ifw-dark">
                                    <option value="TEXT">Text</option>
                                    <option value="FILE">File</option>
                                    <option value="DATE">Date</option>
                                    <option value="NUMBER">Number</option>
                                    <option value="TEXTAREA">Textarea</option>
                                </select>
                            </div>
                            <div class="col-md-2 form-group mb-2">
                                <label class="small text-muted mb-1">{{ __('Order') }}</label>
                                <input type="number" name="order" class="form-control input-ifw-dark" value="{{ count($fields) + 1 }}">
                            </div>
                            <div class="col-md-2 form-group mb-2">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" name="required" id="addReqCheck" class="custom-control-input" value="1" checked>
                                    <label class="custom-control-label text-warning small font-weight-bold" for="addReqCheck">{{ __('Required') }}</label>
                                </div>
                                <button type="submit" class="btn-orange-submit btn-block">
                                    <i class="fas fa-plus mr-1"></i> {{ __('Add Field') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════════ -->
<!-- MODAL 2: EDIT KYC FIELD (EXACT IFW REPLICA)                         -->
<!-- ═══════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="editKycFieldModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ifw-modal-content">
            <div class="ifw-modal-header">
                <h6 class="ifw-modal-title">
                    <i class="fas fa-edit"></i> {{ __('EDIT KYC FIELD') }}
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{ route('admin.kyc.field.update') }}" method="POST">
                @csrf
                <input type="hidden" name="original_db_name" id="editOrigDbName">

                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted">{{ __('Field Label') }} <span class="text-danger">*</span></label>
                        <input type="text" name="label" id="editFieldLabel" class="form-control input-ifw-dark" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted">{{ __('Field Identifier (DB Name)') }} <span class="text-danger">*</span></label>
                        <input type="text" name="db_name" id="editFieldDbName" class="form-control input-ifw-dark" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted">{{ __('Field Type') }}</label>
                        <select name="type" id="editFieldType" class="form-control input-ifw-dark">
                            <option value="TEXT">Text</option>
                            <option value="FILE">File</option>
                            <option value="DATE">Date</option>
                            <option value="NUMBER">Number</option>
                            <option value="TEXTAREA">Textarea</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted">{{ __('Display Order') }}</label>
                        <input type="number" name="order" id="editFieldOrder" class="form-control input-ifw-dark">
                    </div>

                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" name="required" id="editFieldRequired" class="custom-control-input" value="1">
                        <label class="custom-control-label text-warning font-weight-bold small" for="editFieldRequired">
                            {{ __('Mandatory Field') }}
                        </label>
                    </div>
                </div>

                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm font-weight-bold px-3">{{ __('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════════ -->
<!-- MODAL 3: REVIEW KYC SUBMISSION & APPROVE / REJECT                   -->
<!-- ═══════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="reviewKycModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ifw-modal-content">
            <div class="ifw-modal-header">
                <h6 class="ifw-modal-title">
                    <i class="fas fa-file-signature"></i> {{ __('REVIEW KYC SUBMISSION') }}
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body p-4">
                <div class="mb-3">
                    <div class="small text-muted">{{ __('Client Account:') }}</div>
                    <strong class="text-white" id="revClientName"></strong>
                </div>

                <div class="mb-3">
                    <div class="small text-muted">{{ __('Document Title:') }}</div>
                    <span class="text-warning font-weight-bold" id="revDocTitle"></span>
                </div>

                <div class="mb-3">
                    <div class="small text-muted mb-1">{{ __('Uploaded Document File:') }}</div>
                    <a href="#" id="revFileLink" target="_blank" class="btn btn-sm btn-dark text-warning border border-secondary">
                        <i class="fas fa-file-download mr-1"></i> {{ __('View / Download Uploaded File') }}
                    </a>
                </div>

                <hr class="border-secondary">

                <!-- Update Status Form -->
                <form id="kycStatusForm" action="" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted">{{ __('Verification Decision') }}</label>
                        <select name="status" id="revStatusSelect" class="form-control input-ifw-dark" required>
                            <option value="approved">{{ __('Approve KYC Verification') }}</option>
                            <option value="rejected">{{ __('Reject (Require Resubmission)') }}</option>
                            <option value="pending">{{ __('Pending Additional Review') }}</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted">{{ __('Review Notes / Rejection Reason') }}</label>
                        <textarea name="admin_notes" id="revNotesArea" rows="3" class="form-control input-ifw-dark" placeholder="Add feedback or notes for the client..."></textarea>
                    </div>

                    <div class="d-flex justify-content-end" style="gap: 8px;">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-warning btn-sm font-weight-bold text-dark px-3">{{ __('Save Status Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')
<script>
function openEditFieldModal(label, dbName, type, order, isReq) {
    document.getElementById('editOrigDbName').value = dbName;
    document.getElementById('editFieldLabel').value = label;
    document.getElementById('editFieldDbName').value = dbName;
    document.getElementById('editFieldType').value = type;
    document.getElementById('editFieldOrder').value = order;
    document.getElementById('editFieldRequired').checked = isReq;

    $('#kycConfigModal').modal('hide');
    $('#editKycFieldModal').modal('show');
}

$('#editKycFieldModal').on('hidden.bs.modal', function () {
    $('#kycConfigModal').modal('show');
});

function openReviewModal(id, clientName, docTitle, fileUrl, status, notes) {
    document.getElementById('kycStatusForm').action = "{{ url('admin/kyc/status') }}/" + id;
    document.getElementById('revClientName').textContent = clientName;
    document.getElementById('revDocTitle').textContent = docTitle;
    document.getElementById('revFileLink').href = fileUrl;
    document.getElementById('revStatusSelect').value = status;
    document.getElementById('revNotesArea').value = notes;

    $('#reviewKycModal').modal('show');
}
</script>
@endsection
