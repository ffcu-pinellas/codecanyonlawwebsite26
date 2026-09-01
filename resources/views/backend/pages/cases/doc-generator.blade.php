@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .builder-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 1200px) {
        .builder-container {
            grid-template-columns: 1fr;
        }
    }

    .builder-card {
        background: #161a24;
        border: 1px solid #283244;
        border-radius: 12px;
        overflow: hidden;
    }

    .builder-card-header {
        background: #11151e;
        border-bottom: 1px solid #283244;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .builder-card-header h6 {
        margin: 0;
        font-weight: 700;
        color: #f1f5f9;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .builder-card-body {
        padding: 22px;
    }

    .form-label-custom {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 6px;
        display: block;
    }

    .form-control-custom {
        background: #0d1017 !important;
        border: 1px solid #283244 !important;
        color: #f1f5f9 !important;
        border-radius: 8px !important;
        font-size: 13px !important;
    }

    .form-control-custom:focus {
        border-color: #fecc56 !important;
        box-shadow: 0 0 0 2px rgba(254, 204, 86, 0.18) !important;
    }

    .tag-badge-pill {
        background: #1e2533;
        border: 1px solid #374358;
        color: #fecc56;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-block;
        margin: 2px;
        transition: all 0.15s ease;
    }

    .tag-badge-pill:hover {
        background: #2a354a;
        border-color: #fecc56;
        transform: translateY(-1px);
    }

    /* Live Paper Preview styling */
    .paper-preview-container {
        background: #ffffff;
        color: #1a1a1a;
        font-family: 'Georgia', serif;
        padding: 40px 45px;
        border-radius: 8px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.5);
        min-height: 600px;
        position: relative;
        font-size: 13.5px;
        line-height: 1.8;
    }

    .paper-header {
        text-align: center;
        border-bottom: 2px solid #b8860b;
        padding-bottom: 20px;
        margin-bottom: 24px;
    }

    .paper-header h3 {
        font-size: 20px;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #111;
        margin: 0 0 6px 0;
        font-weight: 700;
    }

    .paper-meta {
        font-size: 12px;
        color: #666;
    }

    .paper-body {
        margin-bottom: 30px;
    }

    .paper-signatures {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-top: 40px;
        border-top: 1px solid #ddd;
        padding-top: 24px;
    }

    .sig-line-block {
        border-bottom: 1px solid #333;
        height: 38px;
        margin-bottom: 6px;
    }

    .btn-gold-action {
        background: linear-gradient(135deg, #e8820c, #c46e08);
        color: #ffffff !important;
        border: none;
        border-radius: 8px;
        padding: 10px 22px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-gold-action:hover {
        opacity: 0.9;
        transform: translateY(-1px);
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

    <div class="builder-container">
        <!-- ── Left Column: Builder Controls ── -->
        <div class="builder-card">
            <div class="builder-card-header">
                <h6><i class="fas fa-file-signature text-warning"></i> {{ __('Legal Document Builder & Form Populator') }}</h6>
                <span class="badge badge-warning text-dark font-weight-bold px-2 py-1">{{ __('IFW Auto-Populator') }}</span>
            </div>

            <div class="builder-card-body">
                <form action="{{ route('admin.document-generator.generate') }}" method="POST" id="mainDocGenForm" target="_blank">
                    @csrf

                    <!-- 1. Select Template -->
                    <div class="form-group mb-3">
                        <label class="form-label-custom">{{ __('Select Document Template') }} <span class="text-danger">*</span></label>
                        <select name="template_key" id="templateKeySelector" class="form-control form-control-custom" required onchange="onTemplateChange(this)">
                            <option value="">-- {{ __('Choose Template') }} --</option>
                            @foreach($templates as $tmpl)
                                <option value="{{ $tmpl->key }}" data-title="{{ $tmpl->title }}" data-content="{{ addslashes($tmpl->content) }}">{{ $tmpl->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Select Client Profile -->
                    <div class="form-group mb-3">
                        <label class="form-label-custom">{{ __('Associate Client Profile') }} <span class="text-danger">*</span></label>
                        <select name="client_id" id="clientSelector" class="form-control form-control-custom" required onchange="onClientChange(this)">
                            <option value="">-- {{ __('Choose Client') }} --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" data-name="{{ $client->name }}" data-email="{{ $client->email }}" data-phone="{{ $client->phone ?? '' }}" data-address="{{ $client->address ?? '' }}">
                                    {{ $client->name }} ({{ $client->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. Document Details Row -->
                    <div class="row mb-3">
                        <div class="col-md-6 form-group mb-0">
                            <label class="form-label-custom">{{ __('Document Title') }} <span class="text-danger">*</span></label>
                            <input type="text" name="doc_title" id="docTitleInput" class="form-control form-control-custom" value="Legal Representation Agreement" required oninput="updateLivePreview()">
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label class="form-label-custom">{{ __('Effective Date') }}</label>
                            <input type="date" name="effective_date" id="effectiveDateInput" class="form-control form-control-custom" value="{{ date('Y-m-d') }}" oninput="updateLivePreview()">
                        </div>
                    </div>

                    <!-- 4. Attorney / Officer Name -->
                    <div class="form-group mb-3">
                        <label class="form-label-custom">{{ __('Attorney / Authorized Officer') }}</label>
                        <input type="text" name="attorney_name" id="attorneyNameInput" class="form-control form-control-custom" value="{{ $companyName }}" placeholder="e.g. Gary Livingston, Senior CPA & Legal Counsel" oninput="updateLivePreview()">
                    </div>

                    <!-- 5. Content / Clauses Editor -->
                    <div class="form-group mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label-custom mb-0">{{ __('Document Content & Clauses (HTML/Text)') }}</label>
                            <small class="text-muted">{{ __('Click tags to insert') }}:</small>
                        </div>
                        <!-- Placeholders clickable tags -->
                        <div class="mb-2">
                            <span class="tag-badge-pill" onclick="insertTag('@{{client_name}}')">@{{client_name}}</span>
                            <span class="tag-badge-pill" onclick="insertTag('@{{client_email}}')">@{{client_email}}</span>
                            <span class="tag-badge-pill" onclick="insertTag('@{{client_phone}}')">@{{client_phone}}</span>
                            <span class="tag-badge-pill" onclick="insertTag('@{{client_address}}')">@{{client_address}}</span>
                            <span class="tag-badge-pill" onclick="insertTag('@{{company_name}}')">@{{company_name}}</span>
                            <span class="tag-badge-pill" onclick="insertTag('@{{date}}')">@{{date}}</span>
                            <span class="tag-badge-pill" onclick="insertTag('@{{case_number}}')">@{{case_number}}</span>
                        </div>
                        <textarea name="custom_clauses" id="documentContentArea" rows="7" class="form-control form-control-custom" placeholder="Document body content and legal provisions..." oninput="updateLivePreview()"></textarea>
                    </div>

                    <!-- 6. Options Checkboxes -->
                    <div class="mb-4 pt-2 border-top border-secondary">
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" name="requires_signature" id="requires_signature" class="custom-control-input" value="1" checked onchange="updateLivePreview()">
                            <label class="custom-control-label text-light font-weight-semibold" for="requires_signature">
                                <i class="fas fa-signature text-warning mr-1"></i> {{ __('Requires Client Digital Signature Block') }}
                            </label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="send_email" id="send_email" class="custom-control-input" value="1" checked>
                            <label class="custom-control-label text-warning font-weight-semibold" for="send_email">
                                <i class="fas fa-paper-plane mr-1"></i> {{ __('Dispatch Certified PDF Email Copy to Client') }}
                            </label>
                        </div>
                    </div>

                    <!-- 7. Action Button -->
                    <div class="d-flex" style="gap: 12px;">
                        <button type="submit" class="btn-gold-action flex-grow-1 justify-content-center">
                            <i class="fas fa-print"></i> {{ __('Generate, Print & Send Document') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ── Right Column: Live Parchment Paper Preview ── -->
        <div class="builder-card">
            <div class="builder-card-header">
                <h6><i class="fas fa-eye text-warning"></i> {{ __('Live Parchment Document Preview') }}</h6>
                <button type="button" class="btn btn-sm btn-outline-warning" onclick="window.print()">
                    <i class="fas fa-print mr-1"></i> {{ __('Print Preview') }}
                </button>
            </div>

            <div class="builder-card-body" style="background: #10131b;">
                <div class="paper-preview-container" id="paperPreviewArea">
                    <!-- Header -->
                    <div class="paper-header">
                        <h3 id="prevTitle">LEGAL REPRESENTATION AGREEMENT</h3>
                        <div class="paper-meta">
                            <span><strong>{{ $companyName }}</strong></span> &bull;
                            <span>Ref: <strong id="prevRef">DOC-{{ date('Ymd') }}</strong></span> &bull;
                            <span>Date: <strong id="prevDate">{{ date('F d, Y') }}</strong></span>
                        </div>
                    </div>

                    <!-- Client Summary Banner -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; font-size: 12.5px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div><strong>Client:</strong> <span id="prevClientName">Select a client profile</span></div>
                            <div><strong>Email:</strong> <span id="prevClientEmail">N/A</span></div>
                            <div><strong>Phone:</strong> <span id="prevClientPhone">N/A</span></div>
                            <div><strong>Address:</strong> <span id="prevClientAddress">N/A</span></div>
                        </div>
                    </div>

                    <!-- Body Content -->
                    <div class="paper-body" id="prevContent">
                        <p>Select a legal document template on the left to load the standard legal stipulations, clauses, and terms of representation.</p>
                    </div>

                    <!-- Signature Blocks -->
                    <div class="paper-signatures" id="prevSignatures">
                        <div>
                            <div style="font-size: 11px; text-transform: uppercase; color: #666; margin-bottom: 6px;">Authorized Legal Counsel</div>
                            <div class="sig-line-block"></div>
                            <div style="font-size: 12px; font-weight: bold;" id="prevAttorneySigner">{{ $companyName }}</div>
                        </div>
                        <div>
                            <div style="font-size: 11px; text-transform: uppercase; color: #666; margin-bottom: 6px;">Client / Grantor Signature</div>
                            <div class="sig-line-block"></div>
                            <div style="font-size: 12px; font-weight: bold;" id="prevClientSigner">Client Name</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
var clientData = {};
var currentTemplateContent = '';

function onTemplateChange(sel) {
    var opt = sel.options[sel.selectedIndex];
    if (opt && opt.value) {
        var rawContent = opt.getAttribute('data-content') || '';
        rawContent = rawContent.replace(/\\n/g, "\n").replace(/\\t/g, "\t").replace(/\\"/g, '"').replace(/\\'/g, "'");
        document.getElementById('documentContentArea').value = rawContent;
        currentTemplateContent = rawContent;

        var title = opt.getAttribute('data-title') || opt.text;
        document.getElementById('docTitleInput').value = title;
    }
    updateLivePreview();
}

function onClientChange(sel) {
    var opt = sel.options[sel.selectedIndex];
    if (opt && opt.value) {
        clientData = {
            name: opt.getAttribute('data-name') || 'Client',
            email: opt.getAttribute('data-email') || 'N/A',
            phone: opt.getAttribute('data-phone') || 'N/A',
            address: opt.getAttribute('data-address') || 'N/A'
        };
    } else {
        clientData = {};
    }
    updateLivePreview();
}

function insertTag(tag) {
    var textarea = document.getElementById('documentContentArea');
    var start = textarea.selectionStart;
    var end = textarea.selectionEnd;
    var val = textarea.value;
    textarea.value = val.substring(0, start) + tag + val.substring(end);
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + tag.length;
    updateLivePreview();
}

function updateLivePreview() {
    var title = document.getElementById('docTitleInput').value || 'Legal Representation Document';
    var attorney = document.getElementById('attorneyNameInput').value || '{{ $companyName }}';
    var dateVal = document.getElementById('effectiveDateInput').value;
    var content = document.getElementById('documentContentArea').value;
    var reqSig = document.getElementById('requires_signature').checked;

    document.getElementById('prevTitle').textContent = title.toUpperCase();
    document.getElementById('prevAttorneySigner').textContent = attorney;
    if (dateVal) {
        var d = new Date(dateVal);
        document.getElementById('prevDate').textContent = d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    }

    if (clientData.name) {
        document.getElementById('prevClientName').textContent = clientData.name;
        document.getElementById('prevClientEmail').textContent = clientData.email;
        document.getElementById('prevClientPhone').textContent = clientData.phone;
        document.getElementById('prevClientAddress').textContent = clientData.address;
        document.getElementById('prevClientSigner').textContent = clientData.name;
    }

    // Replace live placeholders in content
    var cName = clientData.name || '{{client_name}}';
    var cEmail = clientData.email || '{{client_email}}';
    var cPhone = clientData.phone || '{{client_phone}}';
    var cAddr = clientData.address || '{{client_address}}';

    var previewHtml = content
        .replace(/@?\{\{client_name\}\}/g, cName)
        .replace(/@?\{\{client_email\}\}/g, cEmail)
        .replace(/@?\{\{client_phone\}\}/g, cPhone)
        .replace(/@?\{\{client_address\}\}/g, cAddr)
        .replace(/@?\{\{company_name\}\}/g, attorney)
        .replace(/@?\{\{attorney_name\}\}/g, attorney)
        .replace(/@?\{\{date\}\}/g, document.getElementById('prevDate').textContent);

    document.getElementById('prevContent').innerHTML = previewHtml ? previewHtml.replace(/\n/g, '<br>') : '<p class="text-muted">Type or select a template to preview content...</p>';
    document.getElementById('prevSignatures').style.display = reqSig ? 'grid' : 'none';
}
</script>
@endsection
