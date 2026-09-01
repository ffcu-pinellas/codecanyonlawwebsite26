@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .ifw-vault-container {
        background: #11141c;
        border: 1px solid #283244;
        border-radius: 12px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.6);
        overflow: hidden;
        margin-bottom: 30px;
    }
    .ifw-vault-header {
        background: #0b0d13;
        border-bottom: 1px solid #232c3d;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .ifw-vault-title {
        font-size: 15px;
        font-weight: 800;
        color: #f97316;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ifw-vault-tabs {
        display: flex;
        gap: 10px;
        padding: 16px 22px 0 22px;
        background: #0f121a;
        border-bottom: 1px solid #232c3d;
    }
    .ifw-tab-btn {
        background: transparent;
        border: 1px solid #283244;
        border-bottom: none;
        color: #94a3b8;
        padding: 10px 22px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .ifw-tab-btn:hover {
        color: #f1f5f9;
        background: #161a24;
    }
    .ifw-tab-btn.active {
        background: #161a24;
        border-color: #f97316;
        color: #f97316;
        box-shadow: 0 -2px 10px rgba(249, 115, 22, 0.15);
    }
    .ifw-vault-body {
        padding: 24px;
        background: #141721;
    }
    .ifw-section-heading {
        font-size: 13.5px;
        font-weight: 800;
        color: #f97316;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .ifw-box-dark {
        background: #0b0d13;
        border: 1px solid #232c3d;
        border-radius: 8px;
        padding: 18px;
        margin-bottom: 24px;
    }
    .ifw-input {
        background: #10131b !important;
        border: 1px solid #283244 !important;
        color: #f1f5f9 !important;
        border-radius: 6px !important;
        font-size: 13px !important;
    }
    .ifw-input:focus {
        border-color: #f97316 !important;
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.2) !important;
    }
    .btn-ifw-orange {
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 13px;
        border: none;
        border-radius: 6px;
        padding: 9px 24px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-ifw-orange:hover {
        opacity: 0.92;
        transform: translateY(-1px);
    }
    .btn-outline-orange {
        background: transparent;
        border: 1.5px solid #f97316;
        color: #f97316 !important;
        font-weight: 700;
        font-size: 13px;
        border-radius: 6px;
        padding: 8px 20px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-outline-orange:hover {
        background: rgba(249, 115, 22, 0.15);
        color: #ffffff !important;
    }
    .ifw-table {
        width: 100%;
        border-collapse: collapse;
        color: #cbd5e1;
        font-size: 13px;
    }
    .ifw-table th {
        background: #0b0d13;
        color: #fecc56;
        font-weight: 700;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 1px solid #283244;
    }
    .ifw-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #1e2636;
        vertical-align: middle;
    }
    .ifw-table tr:hover {
        background: rgba(255, 255, 255, 0.02);
    }
    .type-pill {
        background: #2a3447;
        color: #e2e8f0;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 4px;
        text-transform: uppercase;
        display: inline-block;
    }
    .status-signed {
        background: rgba(34, 197, 94, 0.15);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.3);
        font-weight: bold;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
    }
    .status-standard {
        background: #1e2533;
        color: #94a3b8;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
    }
    .btn-red-del {
        background: transparent;
        border: none;
        color: #f87171;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-red-del:hover {
        color: #ef4444;
        text-decoration: underline;
    }
    .tag-pill {
        display: inline-block;
        background: #1e2533;
        color: #fecc56;
        border: 1px solid #2d3748;
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 11px;
        cursor: pointer;
        margin-right: 4px;
        margin-bottom: 4px;
        font-family: monospace;
        transition: all 0.15s;
    }
    .tag-pill:hover {
        background: #fecc56;
        color: #000;
    }

    /* Live Preview Parchment Paper */
    .parchment-paper-preview {
        background: #fffdfa;
        color: #1a1a1a;
        padding: 40px;
        border-radius: 6px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        font-family: 'Times New Roman', Georgia, serif;
        min-height: 500px;
        line-height: 1.6;
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

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- EXACT IFW CASE DOCUMENT VAULT CONTAINER WITH 2 TABS                -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <div class="ifw-vault-container">
        <!-- Vault Top Header -->
        <div class="ifw-vault-header">
            <h6 class="ifw-vault-title">
                <i class="fas fa-folder-open"></i> {{ __('CASE DOCUMENT VAULT') }}
            </h6>
        </div>

        <!-- Two Navigation Tabs (Exact IFW replica) -->
        <div class="ifw-vault-tabs">
            <button type="button" class="ifw-tab-btn {{ request()->get('tab') !== 'compose' ? 'active' : '' }}" onclick="switchVaultTab('uploadTab', this)">
                <i class="fas fa-upload"></i> {{ __('Upload File') }}
            </button>
            <button type="button" class="ifw-tab-btn {{ request()->get('tab') === 'compose' ? 'active' : '' }}" onclick="switchVaultTab('composeTab', this)">
                <i class="fas fa-edit"></i> {{ __('Create Custom Document') }}
            </button>
        </div>

        <!-- Tab 1: Upload File -->
        <div id="uploadTab" class="ifw-vault-body" style="{{ request()->get('tab') === 'compose' ? 'display: none;' : '' }}">
            <!-- Upload Box -->
            <div class="ifw-box-dark">
                <h6 class="ifw-section-heading">
                    <i class="fas fa-upload"></i> {{ __('UPLOAD DOCUMENT TO VAULT') }}
                </h6>

                <form action="{{ route('admin.document-generator.generate') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="action_type" value="upload">

                    <div class="row align-items-center">
                        <div class="col-md-4 form-group mb-2">
                            <label class="small text-muted mb-1">{{ __('Select File') }} <span class="text-danger">*</span></label>
                            <input type="file" name="vault_file" class="form-control-file text-light" required style="font-size: 12.5px;">
                        </div>

                        <div class="col-md-3 form-group mb-2">
                            <label class="small text-muted mb-1">{{ __('Document Type') }}</label>
                            <select name="doc_type" class="form-control ifw-input">
                                <option value="Standard / General Document">Standard / General Document</option>
                                <option value="Client Evidence">Client Evidence</option>
                                <option value="Writ of Mandamus">Writ of Mandamus</option>
                                <option value="Retainer & Representation Agreement">Retainer & Representation Agreement</option>
                                <option value="Proof of Claim & Banking Records">Proof of Claim & Banking Records</option>
                                <option value="Court Affidavit & Formal Notice">Court Affidavit & Formal Notice</option>
                            </select>
                        </div>

                        <div class="col-md-3 form-group mb-2">
                            <label class="small text-muted mb-1">{{ __('Associate Client Profile') }}</label>
                            <select name="client_id" class="form-control ifw-input" required>
                                @foreach($clients as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 form-group mb-2">
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" name="requires_signature" id="uploadReqSig" class="custom-control-input" value="1">
                                <label class="custom-control-label text-light small font-weight-bold" for="uploadReqSig">{{ __('Requires Signature') }}</label>
                            </div>
                            <button type="submit" class="btn-ifw-orange btn-block">
                                <i class="fas fa-upload mr-1"></i> {{ __('Upload') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Vaulted Files & Agreements List -->
            <h6 class="ifw-section-heading">
                <i class="fas fa-file-alt"></i> {{ __('VAULTED FILES & AGREEMENTS') }}
            </h6>

            <div class="table-responsive">
                <table class="ifw-table">
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
                        @forelse($vaultedDocs ?? [] as $doc)
                        <tr>
                            <td>
                                <a href="{{ asset($doc->file_path) }}" target="_blank" class="text-warning font-weight-bold text-decoration-none">
                                    <i class="fas fa-file-pdf text-warning mr-2"></i> {{ $doc->document_title ?: basename($doc->file_path) }}
                                </a>
                                @if(!empty($doc->client))
                                    <small class="text-muted d-block">{{ $doc->client->name }} ({{ $doc->client->email }})</small>
                                @endif
                            </td>
                            <td>
                                <span class="type-pill">{{ $doc->document_type ?? 'Client Evidence' }}</span>
                            </td>
                            <td>
                                @if(!empty($doc->is_signed))
                                    <span class="status-signed"><i class="fas fa-check-circle mr-1"></i> {{ __('Signed') }}</span>
                                @else
                                    <span class="status-standard">{{ __('Standard View') }}</span>
                                @endif
                            </td>
                            <td style="font-size: 12px; color: #94a3b8;">
                                {{ $doc->created_at ? $doc->created_at->format('M d, Y H:i') : 'N/A' }}
                            </td>
                            <td>
                                <form action="{{ route('admin.document-templates.destroy', $doc->id ?? 0) }}" method="POST" onsubmit="return confirm('Delete this vaulted document?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-red-del">
                                        <i class="fas fa-trash-alt"></i> {{ __('Delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block text-secondary"></i>
                                <span class="small">{{ __('No vaulted documents found.') }}</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 2: Create Custom Document (Exact IFW Compose Screen) -->
        <div id="composeTab" class="ifw-vault-body" style="{{ request()->get('tab') !== 'compose' ? 'display: none;' : '' }}">
            <h6 class="ifw-section-heading">
                <i class="fas fa-edit"></i> {{ __('COMPOSE CUSTOM DOCUMENT') }}
            </h6>

            <form action="{{ route('admin.document-generator.generate') }}" method="POST" id="composeDocForm" target="_blank">
                @csrf
                <input type="hidden" name="action_type" value="compose">

                <!-- 1. Select Template -->
                <div class="form-group mb-3">
                    <label class="small text-muted font-weight-bold">{{ __('Select Document Template (Optional)') }}</label>
                    <select name="template_key" id="templateKeySelector" class="form-control ifw-input" onchange="onTemplateChange(this)">
                        <option value="">-- {{ __('Choose a standard template') }} --</option>
                        @foreach($templates as $tmpl)
                            <option value="{{ $tmpl->key }}" data-title="{{ $tmpl->title }}" data-content="{{ addslashes($tmpl->content) }}">{{ $tmpl->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 2. Client Profile -->
                <div class="form-group mb-3">
                    <label class="small text-muted font-weight-bold">{{ __('Associate Client Profile') }} <span class="text-danger">*</span></label>
                    <select name="client_id" id="clientSelector" class="form-control ifw-input" required onchange="onClientChange(this)">
                        <option value="">-- {{ __('Choose Client') }} --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" data-name="{{ $client->name }}" data-email="{{ $client->email }}" data-phone="{{ $client->phone ?? '' }}" data-address="{{ $client->address ?? '' }}">
                                {{ $client->name }} ({{ $client->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Document Title -->
                <div class="form-group mb-3">
                    <label class="small text-muted font-weight-bold">{{ __('Document Title / Name') }} <span class="text-danger">*</span></label>
                    <input type="text" name="doc_title" id="docTitleInput" class="form-control ifw-input" placeholder="e.g. Asset Recovery Agreement - Jane Doe" required>
                </div>

                <!-- 4. Row: Dynamic Document Type & Signature Checkbox -->
                <div class="row align-items-center mb-3">
                    <div class="col-md-8">
                        <label class="small text-muted font-weight-bold">{{ __('Document Type (Dynamic)') }}</label>
                        <input type="text" name="doc_type" id="docTypeInput" class="form-control ifw-input" placeholder="e.g. Service Agreement, Custom NDA, Recovery Mandate">
                    </div>
                    <div class="col-md-4 pt-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="requires_signature" id="composeReqSig" class="custom-control-input" value="1" checked>
                            <label class="custom-control-label text-light small font-weight-bold" for="composeReqSig">{{ __('Requires Client Signature') }}</label>
                        </div>
                    </div>
                </div>

                <!-- 5. Content Editor with Quick Insert Tags -->
                <div class="form-group mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="small text-muted font-weight-bold mb-0">{{ __('Document Content (HTML / Text Allowed)') }}</label>
                        <small class="text-muted">{{ __('Click tags to insert') }}:</small>
                    </div>
                    <div class="mb-2">
                        <span class="tag-pill" onclick="insertTag('@{{client_name}}')">@{{client_name}}</span>
                        <span class="tag-pill" onclick="insertTag('@{{client_email}}')">@{{client_email}}</span>
                        <span class="tag-pill" onclick="insertTag('@{{client_address}}')">@{{client_address}}</span>
                        <span class="tag-pill" onclick="insertTag('@{{company_name}}')">@{{company_name}}</span>
                        <span class="tag-pill" onclick="insertTag('@{{case_number}}')">@{{case_number}}</span>
                        <span class="tag-pill" onclick="insertTag('@{{date}}')">@{{date}}</span>
                    </div>
                    <textarea name="document_content" id="documentContentArea" rows="8" class="form-control ifw-input" placeholder="Write your document content here. You can use standard HTML formatting tags like <b>, <i>, <ul>, <p>, etc."></textarea>
                </div>

                <!-- Action Buttons: Live Preview Document (Outline) + Create & Send (Solid) -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <button type="button" class="btn-outline-orange" onclick="openLivePreviewModal()">
                        <i class="fas fa-eye"></i> {{ __('Live Preview Document') }}
                    </button>

                    <button type="submit" name="generate_pdf" value="1" class="btn-ifw-orange">
                        {{ __('Create & Send to Client') }}
                    </button>
                </div>
            </form>

            <hr class="border-secondary my-4">

            <!-- Bottom Section: VAULTED FILES & AGREEMENTS on this tab as well (Exact IFW layout) -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="ifw-section-heading mb-0">
                    <i class="fas fa-file-alt"></i> {{ __('VAULTED FILES & AGREEMENTS') }}
                </h6>
            </div>

            <div class="table-responsive mb-4">
                <table class="ifw-table">
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
                        @forelse($vaultedDocs ?? [] as $doc)
                        <tr>
                            <td>
                                <a href="{{ asset($doc->file_path) }}" target="_blank" class="text-warning font-weight-bold text-decoration-none">
                                    <i class="fas fa-file-pdf text-warning mr-2"></i> {{ $doc->document_title ?: basename($doc->file_path) }}
                                </a>
                                @if(!empty($doc->client))
                                    <small class="text-muted d-block">{{ $doc->client->name }} ({{ $doc->client->email }})</small>
                                @endif
                            </td>
                            <td>
                                <span class="type-pill">{{ $doc->document_type ?? 'Client Evidence' }}</span>
                            </td>
                            <td>
                                @if(!empty($doc->is_signed))
                                    <span class="status-signed"><i class="fas fa-check-circle mr-1"></i> {{ __('Signed') }}</span>
                                @else
                                    <span class="status-standard">{{ __('Standard View') }}</span>
                                @endif
                            </td>
                            <td style="font-size: 12px; color: #94a3b8;">
                                {{ $doc->created_at ? $doc->created_at->format('M d, Y H:i') : 'N/A' }}
                            </td>
                            <td>
                                <form action="{{ route('admin.document-templates.destroy', $doc->id ?? 0) }}" method="POST" onsubmit="return confirm('Delete this vaulted document?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-red-del">
                                        <i class="fas fa-trash-alt"></i> {{ __('Delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <span class="small">{{ __('No documents vaulted for this client.') }}</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="text-right">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm px-4">
                    {{ __('Close') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════════ -->
<!-- LIVE PREVIEW PARCHMENT PAPER MODAL                                  -->
<!-- ═══════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="liveDocPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-weight-bold text-warning">
                    <i class="fas fa-eye mr-2"></i> {{ __('Live Parchment Document Preview') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4" style="background: #11141c;">
                <div class="parchment-paper-preview" id="modalParchmentPaper">
                    <!-- Letterhead -->
                    <div class="text-center border-bottom pb-3 mb-4" style="border-color: #d1c7b7 !important;">
                        <h3 style="margin: 0; font-weight: 800; color: #1e293b; letter-spacing: 1px;">{{ strtoupper($companyName) }}</h3>
                        <div style="font-size: 13px; color: #64748b; font-style: italic;">Legal Counsel & Forensic Financial Practice</div>
                    </div>

                    <!-- Title & Reference -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 style="margin: 0; font-weight: bold; color: #0f172a;" id="prevModalDocTitle">LEGAL REPRESENTATION AGREEMENT</h4>
                        <span style="font-size: 12px; color: #64748b;" id="prevModalDocDate">{{ date('F d, Y') }}</span>
                    </div>

                    <!-- Client Details Box -->
                    <div style="background: #f8f6f0; border-left: 3px solid #f97316; padding: 12px 16px; margin-bottom: 20px; font-size: 13.5px;">
                        <div><strong>Prepared For:</strong> <span id="prevModalClientName">[Client Name]</span></div>
                        <div><strong>Email:</strong> <span id="prevModalClientEmail">[Client Email]</span></div>
                        <div><strong>Address:</strong> <span id="prevModalClientAddress">[Client Address]</span></div>
                    </div>

                    <!-- Body Clauses -->
                    <div id="prevModalContent" style="font-size: 14px; margin-bottom: 30px;">
                        <p class="text-muted">Enter document content to preview...</p>
                    </div>

                    <!-- Signatures -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 40px; padding-top: 20px; border-top: 1px dashed #d1c7b7;">
                        <div>
                            <div style="border-bottom: 1px solid #000; height: 35px; margin-bottom: 6px;"></div>
                            <small style="color: #64748b;">Authorized Legal/CPA Signer</small>
                            <div style="font-weight: bold; font-size: 13px;">{{ $companyName }}</div>
                        </div>
                        <div>
                            <div style="border-bottom: 1px solid #000; height: 35px; margin-bottom: 6px;"></div>
                            <small style="color: #64748b;">Client Signature / Acceptance</small>
                            <div style="font-weight: bold; font-size: 13px;" id="prevModalClientSigner">[Client Name]</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close Preview') }}</button>
                <button type="button" class="btn btn-warning btn-sm font-weight-bold text-dark" onclick="window.printModalPreview()">
                    <i class="fas fa-print mr-1"></i> {{ __('Print Draft') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
var selectedClientData = {};

function switchVaultTab(tabId, btn) {
    document.getElementById('uploadTab').style.display = (tabId === 'uploadTab') ? 'block' : 'none';
    document.getElementById('composeTab').style.display = (tabId === 'composeTab') ? 'block' : 'none';
    document.querySelectorAll('.ifw-tab-btn').forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');
}

function onTemplateChange(select) {
    var opt = select.options[select.selectedIndex];
    if (opt.value) {
        document.getElementById('docTitleInput').value = opt.getAttribute('data-title') || '';
        document.getElementById('documentContentArea').value = opt.getAttribute('data-content') || '';
    }
}

function onClientChange(select) {
    var opt = select.options[select.selectedIndex];
    if (opt.value) {
        selectedClientData = {
            name: opt.getAttribute('data-name') || '',
            email: opt.getAttribute('data-email') || '',
            phone: opt.getAttribute('data-phone') || '',
            address: opt.getAttribute('data-address') || ''
        };
    } else {
        selectedClientData = {};
    }
}

function insertTag(tag) {
    var textarea = document.getElementById('documentContentArea');
    var start = textarea.selectionStart;
    var end = textarea.selectionEnd;
    var val = textarea.value;
    textarea.value = val.substring(0, start) + tag + val.substring(end);
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + tag.length;
}

function openLivePreviewModal() {
    var title = document.getElementById('docTitleInput').value || 'Legal Representation Document';
    var content = document.getElementById('documentContentArea').value || '';
    var clientName = selectedClientData.name || '[Client Name]';
    var clientEmail = selectedClientData.email || '[Client Email]';
    var clientAddress = selectedClientData.address || '[Client Address]';
    var attorney = '{{ $companyName }}';

    document.getElementById('prevModalDocTitle').textContent = title.toUpperCase();
    document.getElementById('prevModalClientName').textContent = clientName;
    document.getElementById('prevModalClientEmail').textContent = clientEmail;
    document.getElementById('prevModalClientAddress').textContent = clientAddress;
    document.getElementById('prevModalClientSigner').textContent = clientName;

    var previewHtml = content
        .replace(/@?\{\{client_name\}\}/g, clientName)
        .replace(/@?\{\{client_email\}\}/g, clientEmail)
        .replace(/@?\{\{client_phone\}\}/g, selectedClientData.phone || 'N/A')
        .replace(/@?\{\{client_address\}\}/g, clientAddress)
        .replace(/@?\{\{company_name\}\}/g, attorney)
        .replace(/@?\{\{attorney_name\}\}/g, attorney)
        .replace(/@?\{\{case_number\}\}/g, 'CASE-001')
        .replace(/@?\{\{date\}\}/g, '{{ date('F d, Y') }}');

    document.getElementById('prevModalContent').innerHTML = previewHtml ? previewHtml.replace(/\n/g, '<br>') : '<p class="text-muted">No content written yet.</p>';

    $('#liveDocPreviewModal').modal('show');
}

window.printModalPreview = function() {
    var printContents = document.getElementById('modalParchmentPaper').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    window.location.reload();
};
</script>
@endsection
