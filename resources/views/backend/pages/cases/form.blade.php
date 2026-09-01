@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
/* ── IFW-Style Case Vault ─────────────────────────────── */
.ifw-vault-modal {
    background: #1a1e2e;
    border: 1px solid #2d3449;
    border-radius: 10px;
    overflow: hidden;
}
.ifw-vault-header {
    background: linear-gradient(135deg, #e8820c, #c46e08);
    padding: 14px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.ifw-vault-header h5 {
    color: #fff;
    font-weight: 700;
    font-size: 15px;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}
/* Tab Nav – matches IFW exactly */
.vault-tabs .nav-link {
    background: #13172a;
    color: #94a3b8;
    border: 1px solid #2d3449;
    border-radius: 6px 6px 0 0;
    padding: 10px 22px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
    margin-right: 4px;
}
.vault-tabs .nav-link:hover {
    color: #fecc56;
    background: #1e2338;
}
.vault-tabs .nav-link.active {
    background: #1e2338;
    color: #fecc56;
    border-color: #fecc56 #fecc56 #1e2338;
}
.vault-tab-content {
    background: #1e2338;
    border: 1px solid #fecc56;
    border-radius: 0 6px 6px 6px;
    padding: 22px;
}
.vault-section-title {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: #e8820c;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.vault-form-row {
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: 12px;
    align-items: end;
}
.vault-form-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
.vault-label {
    font-size: 11.5px;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}
.vault-input {
    background: #13172a !important;
    border: 1px solid #2d3449 !important;
    color: #e2e8f0 !important;
    border-radius: 6px !important;
    font-size: 13px !important;
}
.vault-input:focus {
    border-color: #fecc56 !important;
    box-shadow: 0 0 0 2px rgba(254,204,86,0.15) !important;
}
.vault-input::placeholder { color: #475569 !important; }
.vault-textarea {
    background: #13172a !important;
    border: 1px solid #2d3449 !important;
    color: #e2e8f0 !important;
    border-radius: 6px !important;
    font-size: 13px !important;
    min-height: 160px;
    resize: vertical;
}
.vault-btn-upload {
    background: linear-gradient(135deg, #e8820c, #c46e08);
    color: #fff !important;
    border: none;
    border-radius: 6px;
    padding: 9px 22px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.vault-btn-upload:hover { opacity: 0.88; transform: translateY(-1px); }
.vault-btn-secondary {
    background: #252c42;
    color: #e2e8f0 !important;
    border: 1px solid #374151;
    border-radius: 6px;
    padding: 9px 18px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.vault-btn-secondary:hover { background: #2d3449; }
.vault-checkbox-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #94a3b8;
    white-space: nowrap;
    cursor: pointer;
    margin-top: 6px;
}
.vault-checkbox-row input[type="checkbox"] {
    width: 15px;
    height: 15px;
    accent-color: #e8820c;
    cursor: pointer;
}

/* Vault Table */
.vault-table-section {
    margin-top: 28px;
}
.vault-table-title {
    font-size: 13px;
    font-weight: 700;
    color: #e8820c;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 7px;
}
.vault-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
}
.vault-table thead th {
    background: #13172a;
    color: #94a3b8;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    font-size: 11px;
    padding: 10px 14px;
    border-bottom: 1px solid #2d3449;
}
.vault-table tbody td {
    padding: 10px 14px;
    border-bottom: 1px solid #1e2a3a;
    color: #cbd5e1;
    vertical-align: middle;
}
.vault-table tbody tr:hover { background: rgba(255,255,255,0.03); }
.vault-table .doc-name {
    color: #fecc56;
    font-weight: 600;
    font-size: 12.5px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.vault-badge {
    display: inline-block;
    font-size: 10.5px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 4px;
    white-space: nowrap;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.badge-type {
    background: rgba(100,116,139,0.25);
    color: #94a3b8;
    border: 1px solid rgba(100,116,139,0.4);
}
.badge-type-writ {
    background: rgba(232,130,12,0.2);
    color: #fb923c;
    border: 1px solid rgba(232,130,12,0.35);
}
.badge-standard-view {
    background: rgba(99,102,241,0.2);
    color: #a5b4fc;
    border: 1px solid rgba(99,102,241,0.3);
}
.badge-signed {
    background: rgba(34,197,94,0.15);
    color: #4ade80;
    border: 1px solid rgba(34,197,94,0.3);
}
.badge-sig-req {
    background: rgba(239,68,68,0.15);
    color: #f87171;
    border: 1px solid rgba(239,68,68,0.3);
}
.badge-custom-doc {
    background: rgba(168,85,247,0.15);
    color: #c084fc;
    border: 1px solid rgba(168,85,247,0.3);
}
.vault-action-btn {
    background: transparent;
    border: 1px solid #374151;
    color: #94a3b8;
    padding: 4px 10px;
    border-radius: 5px;
    font-size: 11.5px;
    cursor: pointer;
    transition: all 0.15s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.vault-action-btn:hover { border-color: #fecc56; color: #fecc56; text-decoration: none; }
.vault-action-btn.danger:hover { border-color: #ef4444; color: #f87171; }

/* ── Milestones Timeline ──────────────────────────────── */
.milestone-section {
    background: #1a1e2e;
    border: 1px solid #2d3449;
    border-radius: 10px;
    overflow: hidden;
}
.milestone-header {
    background: linear-gradient(135deg, #e8820c, #c46e08);
    padding: 12px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.milestone-header h5 {
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.milestone-body { padding: 20px; }
.milestone-add-form {
    background: #13172a;
    border: 1px solid #2d3449;
    border-radius: 8px;
    padding: 18px;
    margin-bottom: 24px;
}
.milestone-timeline {
    position: relative;
    padding-left: 28px;
}
.milestone-timeline::before {
    content: '';
    position: absolute;
    left: 9px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #4ade80, #fecc56, #60a5fa);
}
.milestone-item {
    position: relative;
    margin-bottom: 18px;
    padding: 14px 16px;
    background: #13172a;
    border: 1px solid #1e2a3a;
    border-radius: 8px;
}
.milestone-item::before {
    content: '';
    position: absolute;
    left: -22px;
    top: 18px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid currentColor;
}
.milestone-item.dot-green::before  { color: #4ade80;  background: rgba(74,222,128,0.25); }
.milestone-item.dot-yellow::before { color: #fecc56;  background: rgba(254,204,86,0.25); }
.milestone-item.dot-blue::before   { color: #60a5fa;  background: rgba(96,165,250,0.25); }
.milestone-title {
    font-weight: 700;
    color: #e2e8f0;
    font-size: 13.5px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 4px;
}
.milestone-meta {
    font-size: 11.5px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.milestone-desc { font-size: 12px; color: #94a3b8; margin-top: 6px; }
.milestone-delete-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(239,68,68,0.12);
    border: 1px solid rgba(239,68,68,0.25);
    color: #f87171;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.15s;
}
.milestone-delete-btn:hover { background: rgba(239,68,68,0.25); }

/* ── Case Form ────────────────────────────────────────── */
.case-form-card {
    background: #1a1e2e;
    border: 1px solid #2d3449;
    border-radius: 10px;
    overflow: hidden;
}
.case-form-header {
    background: #13172a;
    border-bottom: 1px solid #2d3449;
    padding: 14px 18px;
}
.case-form-header h6 {
    color: #e2e8f0;
    font-weight: 700;
    font-size: 14px;
    margin: 0;
}
.case-form-body { padding: 22px; }
.cf-label { font-size: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
.cf-input {
    background: #13172a !important;
    border: 1px solid #2d3449 !important;
    color: #e2e8f0 !important;
    border-radius: 6px !important;
}
.cf-input:focus { border-color: #e8820c !important; box-shadow: 0 0 0 2px rgba(232,130,12,0.18) !important; }
</style>
@endsection

@section('content')
<div id="wrapper-content">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark">
                <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                <a class="breadcrumb-item text-white" href="{{ route('admin.cases.index') }}">{{ __('Case Directory') }}</a>
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

    <div class="row">
        <!-- ── Case Information Form ── -->
        <div class="col-lg-4">
            <div class="case-form-card mb-4">
                <div class="case-form-header">
                    <h6><i class="fas fa-folder-open mr-2 text-warning"></i>{{ $case ? __('Edit Case #') . $case->case_number : __('Create New Case') }}</h6>
                </div>
                <div class="case-form-body">
                    <form action="{{ $case ? route('admin.cases.update', $case->id) : route('admin.cases.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="cf-label">{{ __('Case Title') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control cf-input" required value="{{ old('title', $case?->title) }}" placeholder="e.g. Tax Audit 2026 Representation">
                            @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-12 form-group">
                                <label class="cf-label">{{ __('Client') }} <span class="text-danger">*</span></label>
                                <select name="client_id" class="form-control cf-input" required>
                                    <option value="">-- {{ __('Select Client') }} --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" @selected(old('client_id', $case?->client_id) == $client->id)>{{ $client->name }} ({{ $client->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 form-group">
                                <label class="cf-label">{{ __('Assigned Attorney') }}</label>
                                <select name="attorney_id" class="form-control cf-input">
                                    <option value="">-- {{ __('Unassigned') }} --</option>
                                    @foreach($attorneys as $atty)
                                        <option value="{{ $atty->id }}" @selected(old('attorney_id', $case?->attorney_id) == $atty->id)>{{ $atty->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 form-group">
                                <label class="cf-label">{{ __('Court / Due Date') }}</label>
                                <input type="datetime-local" name="court_date" class="form-control cf-input" value="{{ old('court_date', ($case && $case->court_date) ? $case->court_date->format('Y-m-d\TH:i') : '') }}">
                            </div>
                            <div class="col-6 form-group">
                                <label class="cf-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="form-control cf-input" required>
                                    @foreach(['pending'=>'Pending','active'=>'Active','suspended'=>'Suspended','resolved'=>'Resolved'] as $val=>$label)
                                        <option value="{{ $val }}" @selected(old('status', $case?->status ?? 'pending') == $val)>{{ __($label) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="cf-label">{{ __('Case Description') }}</label>
                            <textarea name="description" rows="4" class="form-control cf-input" placeholder="Describe the case goals and timeline...">{{ old('description', $case?->description) }}</textarea>
                        </div>

                        <div class="form-group mt-3 pt-2 border-top" style="border-color:#2d3449!important;">
                            <button type="submit" class="vault-btn-upload"><i class="fas fa-save mr-1"></i> {{ __('Save Case Details') }}</button>
                            <a href="{{ route('admin.cases.index') }}" class="vault-btn-secondary ml-2">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ── Document Vault + Milestones ── -->
        @if($case)
        <div class="col-lg-8">

            {{-- ════════════════════════════════════════════════════ --}}
            {{-- CASE DOCUMENT VAULT                                  --}}
            {{-- ════════════════════════════════════════════════════ --}}
            <div class="ifw-vault-modal mb-4">
                <div class="ifw-vault-header">
                    <h5><i class="fas fa-archive mr-2"></i>{{ __('Case Document Vault') }}</h5>
                </div>

                <div style="padding: 18px 20px 0;">
                    <ul class="nav vault-tabs" id="vaultTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-upload" data-toggle="tab" href="#vault-upload" role="tab">
                                <i class="fas fa-upload mr-1"></i> {{ __('Upload File') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-create" data-toggle="tab" href="#vault-create" role="tab">
                                <i class="fas fa-pen-fancy mr-1"></i> {{ __('Create Custom Document') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <div style="padding: 0 20px 20px;">
                    <div class="tab-content" id="vaultTabContent">

                        {{-- ── Upload File Tab ── --}}
                        <div class="tab-pane fade show active" id="vault-upload" role="tabpanel">
                            <div class="vault-tab-content">
                                <div class="vault-section-title">
                                    <i class="fas fa-cloud-upload-alt"></i> {{ __('Upload Document to Vault') }}
                                </div>
                                <form action="{{ route('admin.cases.upload-document', $case->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="vault-form-row" style="grid-template-columns: 1fr 1.2fr auto;">
                                        <div>
                                            <div class="vault-label">{{ __('Select File') }}</div>
                                            <input type="file" name="files[]" id="vaultFile" multiple required class="form-control vault-input" style="padding:6px;">
                                        </div>
                                        <div>
                                            <div class="vault-label">{{ __('Document Type') }}</div>
                                            <select name="document_type" class="form-control vault-input">
                                                <option>Standard / General Document</option>
                                                <option>Client Evidence</option>
                                                <option>Writ of Mandamus</option>
                                                <option>Engagement Letter</option>
                                                <option>Power of Attorney</option>
                                                <option>Tax Filing Document</option>
                                                <option>Court Pleading</option>
                                                <option>Settlement Agreement</option>
                                                <option>Retainer Agreement</option>
                                                <option>Supporting Exhibit</option>
                                                <option>Correspondence</option>
                                            </select>
                                        </div>
                                        <div class="d-flex flex-column justify-content-end">
                                            <label class="vault-checkbox-row mb-2">
                                                <input type="checkbox" name="requires_signature" value="1">
                                                {{ __('Requires Signature') }}
                                            </label>
                                            <button type="submit" class="vault-btn-upload">
                                                <i class="fas fa-upload mr-1"></i> {{ __('Upload') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- ── Create Custom Document Tab ── --}}
                        <div class="tab-pane fade" id="vault-create" role="tabpanel">
                            <div class="vault-tab-content">
                                <div class="vault-section-title">
                                    <i class="fas fa-file-signature"></i> {{ __('Compose Custom Document') }}
                                </div>
                                <form action="{{ route('admin.cases.generate-document', $case->id) }}" method="POST" id="customDocForm">
                                    @csrf
                                    @php $templates = $templates ?? collect(); @endphp
                                    <div class="form-group mb-3">
                                        <div class="vault-label">{{ __('Select Document Template (Optional)') }}</div>
                                        <select id="templateSelector" class="form-control vault-input">
                                            <option value="">-- {{ __('Choose a standard template') }} --</option>
                                            @foreach($templates as $tmpl)
                                                <option value="{{ $tmpl->key }}" data-content="{{ addslashes($tmpl->content) }}" data-title="{{ addslashes($tmpl->title) }}">{{ $tmpl->title }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="template_key" id="templateKeyHidden">
                                    </div>
                                    <div class="vault-form-row-2 mb-3">
                                        <div>
                                            <div class="vault-label">{{ __('Document Title / Name') }} <span class="text-danger">*</span></div>
                                            <input type="text" name="doc_title" id="docTitleInput" class="form-control vault-input" required placeholder="e.g. Asset Recovery Agreement – {{ $case->client->name ?? 'Client' }}">
                                        </div>
                                        <div>
                                            <div class="vault-label">{{ __('Document Type (Dynamic)') }}</div>
                                            <input type="text" name="doc_type_custom" class="form-control vault-input" placeholder="e.g. Service Agreement, Custom NOA, Recovery Mandate">
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="vault-label">{{ __('Document Content (HTML / Text Allowed)') }}</div>
                                        <textarea name="doc_content" id="docContentArea" class="form-control vault-textarea" required placeholder="Write your document content here. You can use standard HTML formatting tags like &lt;b&gt;, &lt;i&gt;, &lt;ul&gt;, &lt;p&gt;, etc.&#10;&#10;Available placeholders: {{client_name}}, {{client_email}}, {{client_phone}}, {{client_address}}, {{company_name}}, {{date}}, {{case_number}}"></textarea>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:12px;">
                                        <label class="vault-checkbox-row">
                                            <input type="checkbox" name="requires_signature" value="1">
                                            {{ __('Requires Client Signature') }}
                                        </label>
                                        <div class="d-flex" style="gap:10px;">
                                            <button type="button" id="previewDocBtn" class="vault-btn-secondary">
                                                <i class="fas fa-eye mr-1"></i> {{ __('Live Preview Document') }}
                                            </button>
                                            <button type="submit" class="vault-btn-upload">
                                                <i class="fas fa-check mr-1"></i> {{ __('Create & Send to Client') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Vaulted Files & Agreements Table ── --}}
                <div class="vault-table-section px-4 pb-4">
                    <div class="vault-table-title">
                        <i class="fas fa-file-contract"></i> {{ __('Vaulted Files & Agreements') }}
                    </div>

                    @if($case->documents->isEmpty())
                        <div class="text-center py-4 text-muted small">
                            <i class="fas fa-folder-open fa-2x mb-2 d-block" style="color:#2d3449;"></i>
                            {{ __('No documents in this vault yet.') }}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="vault-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('File Name') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Uploaded') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($case->documents as $doc)
                                    <tr>
                                        <td>
                                            <div class="doc-name">
                                                @if($doc->file_type === 'custom')
                                                    <i class="fas fa-file-alt" style="color:#c084fc;"></i>
                                                @elseif($doc->file_type === 'pdf')
                                                    <i class="fas fa-file-pdf" style="color:#ef4444;"></i>
                                                @else
                                                    <i class="fas fa-file" style="color:#fecc56;"></i>
                                                @endif
                                                {{ $doc->title }}
                                                @if($doc->is_client_uploaded)
                                                    <span class="vault-badge" style="background:rgba(234,179,8,0.15);color:#facc15;border-color:rgba(234,179,8,0.3);font-size:9.5px;">Client Upload</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $dt = $doc->document_type ?: 'Standard / General Document';
                                                $isWrit = stripos($dt, 'writ') !== false;
                                            @endphp
                                            <span class="vault-badge {{ $isWrit ? 'badge-type-writ' : 'badge-type' }}">{{ $dt }}</span>
                                        </td>
                                        <td>
                                            @if($doc->requires_signature)
                                                @if($doc->is_signed)
                                                    <span class="vault-badge badge-signed"><i class="fas fa-check-circle mr-1"></i>{{ __('Signed') }}</span>
                                                @else
                                                    <span class="vault-badge badge-sig-req"><i class="fas fa-exclamation-circle mr-1"></i>{{ __('Sig. Required') }}</span>
                                                @endif
                                            @elseif($doc->file_type === 'custom')
                                                <span class="vault-badge badge-custom-doc">{{ __('Custom Doc') }}</span>
                                            @else
                                                <span class="vault-badge badge-standard-view">{{ __('Standard View') }}</span>
                                            @endif
                                        </td>
                                        <td style="font-size:11.5px;white-space:nowrap;color:#64748b;">
                                            {{ $doc->created_at->format('M d, Y') }}<br>
                                            <span style="font-size:10.5px;">{{ $doc->created_at->format('H:i') }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex" style="gap:6px;flex-wrap:wrap;">
                                                @if($doc->file_type === 'custom')
                                                    <a href="{{ route('admin.cases.document.view', $doc->id) }}" target="_blank" class="vault-action-btn">
                                                        <i class="fas fa-eye"></i> {{ __('View') }}
                                                    </a>
                                                @elseif(in_array(strtolower($doc->file_type), ['pdf','jpg','jpeg','png']))
                                                    <button type="button" class="vault-action-btn preview-btn" data-url="{{ route('admin.cases.document.preview', $doc->id) }}" data-title="{{ $doc->title }}">
                                                        <i class="fas fa-eye"></i> {{ __('View') }}
                                                    </button>
                                                @endif
                                                <form action="{{ route('admin.cases.destroy-document', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this document?') }}')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="vault-action-btn danger">
                                                        <i class="fas fa-trash"></i> {{ __('Delete') }}
                                                    </button>
                                                </form>
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

            {{-- ════════════════════════════════════════════════════ --}}
            {{-- CASE TIMELINE & MILESTONES                           --}}
            {{-- ════════════════════════════════════════════════════ --}}
            <div class="milestone-section">
                <div class="milestone-header">
                    <h5><i class="fas fa-chart-line mr-2"></i>{{ __('Case Timeline & Milestones') }}</h5>
                    <button type="button" class="vault-btn-secondary btn-sm" data-toggle="collapse" data-target="#milestoneAddForm">
                        <i class="fas fa-plus mr-1"></i> {{ __('+ Add Milestone') }}
                    </button>
                </div>

                <div class="milestone-body">
                    {{-- Add Milestone Form (Collapsible) --}}
                    <div class="collapse show" id="milestoneAddForm">
                        <div class="milestone-add-form mb-4">
                            <div class="vault-section-title mb-3" style="font-size:12px;">
                                <i class="fas fa-flag-checkered"></i> {{ __('Add New Milestone') }}
                            </div>
                            <form action="{{ route('admin.cases.add-milestone', $case->id) }}" method="POST">
                                @csrf
                                <div class="vault-form-row-2 mb-3">
                                    <div>
                                        <div class="vault-label">{{ __('Milestone Title') }} <span class="text-danger">*</span></div>
                                        <input type="text" name="title" class="form-control vault-input" required placeholder="e.g. Scammer Arrested, Court Filing Submitted">
                                    </div>
                                    <div>
                                        <div class="vault-label">{{ __('Target / Event Date') }}</div>
                                        <input type="date" name="milestone_date" class="form-control vault-input">
                                    </div>
                                </div>
                                <div class="vault-form-row-2 mb-3">
                                    <div>
                                        <div class="vault-label">{{ __('Status') }} <span class="text-danger">*</span></div>
                                        <select name="status" class="form-control vault-input" required>
                                            <option value="completed">✅ {{ __('Completed') }}</option>
                                            <option value="active">🟡 {{ __('In Progress') }}</option>
                                            <option value="pending">🔵 {{ __('Pending') }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <div class="vault-label">{{ __('Visibility') }}</div>
                                        <select name="visibility" class="form-control vault-input">
                                            <option value="client_visible">👁 {{ __('Client Visible') }}</option>
                                            <option value="internal">🔒 {{ __('Internal Only') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="vault-label">{{ __('Brief Notes (Optional)') }}</div>
                                    <input type="text" name="description" class="form-control vault-input" placeholder="e.g. After proper investigation and forensics...">
                                </div>
                                <button type="submit" class="vault-btn-upload btn-sm px-4">
                                    <i class="fas fa-plus mr-1"></i> {{ __('Add Milestone') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    @if($case->milestones->isEmpty())
                        <div class="text-center py-4 text-muted small">
                            <i class="fas fa-route fa-2x mb-2 d-block" style="color:#2d3449;"></i>
                            {{ __('No milestones recorded yet.') }}
                        </div>
                    @else
                        <div class="milestone-timeline">
                            @foreach($case->milestones as $ms)
                                @php
                                    $dotClass = $ms->status === 'completed' ? 'dot-green' : ($ms->status === 'active' ? 'dot-yellow' : 'dot-blue');
                                @endphp
                                <div class="milestone-item {{ $dotClass }}">
                                    <div class="milestone-title">{{ $ms->title }}</div>
                                    <div class="milestone-meta">
                                        @if($ms->milestone_date)
                                            <span><i class="fas fa-calendar-alt mr-1" style="color:#64748b;"></i>{{ $ms->milestone_date->format('M d, Y') }}</span>
                                        @endif
                                        <span>· Visibility:
                                            <strong style="color:{{ ($ms->visibility ?? 'client_visible') === 'client_visible' ? '#4ade80' : '#f87171' }};">
                                                {{ ($ms->visibility ?? 'client_visible') === 'client_visible' ? __('Client Visible') : __('Internal') }}
                                            </strong>
                                        </span>
                                    </div>
                                    @if($ms->description)
                                        <div class="milestone-desc">{{ strtoupper($ms->description) }}</div>
                                    @endif
                                    <form action="{{ route('admin.cases.destroy-milestone', $ms->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this milestone?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="milestone-delete-btn">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
        @endif
    </div>
</div>

{{-- Document Preview Modal --}}
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content" style="background:#1a1e2e;border:1px solid #fecc56;">
            <div class="modal-header" style="border-color:#2d3449;">
                <h5 class="modal-title text-white" id="previewModalLabel">{{ __('Document Preview') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0" style="height:78vh;">
                <iframe id="previewFrame" src="" style="width:100%;height:100%;border:none;"></iframe>
            </div>
        </div>
    </div>
</div>

{{-- Live Preview Modal for Custom Doc --}}
<div class="modal fade" id="livePreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content" style="background:#fff;border:1px solid #fecc56;">
            <div class="modal-header" style="background:#1a1e2e;border-color:#2d3449;">
                <h5 class="modal-title text-white"><i class="fas fa-eye mr-2 text-warning"></i>{{ __('Live Document Preview') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0" style="height:80vh;">
                <iframe id="livePreviewFrame" style="width:100%;height:100%;border:none;background:#fff;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
(function($) {
    "use strict";

    // Preview existing file doc
    $(document).on('click', '.preview-btn', function() {
        var url   = $(this).data('url');
        var title = $(this).data('title');
        $('#previewModalLabel').text(title);
        $('#previewFrame').attr('src', url);
        $('#previewModal').modal('show');
    });
    $('#previewModal').on('hidden.bs.modal', function() {
        $('#previewFrame').attr('src', '');
    });

    // Template selector → fill title + content
    $('#templateSelector').on('change', function() {
        var opt = $(this).find('option:selected');
        var key = $(this).val();
        $('#templateKeyHidden').val(key);
        if (key) {
            var content = opt.data('content') || '';
            var title   = opt.data('title') || '';
            if (title) $('#docTitleInput').val(title + ' – {{ $case->client->name ?? "Client" }}');
            // unescape
            content = content.replace(/\\n/g, "\n").replace(/\\t/g, "\t").replace(/\\"/g, '"').replace(/\\'/g, "'");
            $('#docContentArea').val(content);
        }
    });

    // Live preview custom document
    $('#previewDocBtn').on('click', function() {
        var title   = $('#docTitleInput').val() || 'Document Preview';
        var content = $('#docContentArea').val() || '';
        var html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>body{font-family:Georgia,serif;padding:40px;color:#222;max-width:820px;margin:0 auto;}h1{text-align:center;text-transform:uppercase;font-size:20px;border-bottom:2px solid #b8860b;padding-bottom:16px;margin-bottom:22px;}p{margin-bottom:12px;line-height:1.8;}ul,ol{margin:10px 0 10px 20px;}</style></head><body>';
        html += '<h1>' + $('<div>').text(title).html() + '</h1>';
        html += content;
        html += '</body></html>';
        var iframe = document.getElementById('livePreviewFrame');
        iframe.contentDocument.open();
        iframe.contentDocument.write(html);
        iframe.contentDocument.close();
        $('#livePreviewModal').modal('show');
    });

})(jQuery);
</script>
@endsection
