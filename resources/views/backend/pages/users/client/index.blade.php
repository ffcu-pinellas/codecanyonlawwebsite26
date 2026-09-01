@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')
<style>
    .client-card-stat {
        background: #1e293b;
        border-radius: 8px;
        padding: 16px 20px;
        border: 1px solid #334155;
    }
    .badge-pin-active {
        background: rgba(34, 197, 94, 0.15);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-pin-pending {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
    .cred-box {
        background: #0f172a;
        border: 1px solid #f59e0b;
        border-radius: 8px;
        padding: 18px 20px;
        color: #f8fafc;
    }
</style>
@endsection

@section('content')
<div id="wrapper-content">
    <div class="row mb-3">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark">
                <a class="breadcrumb-item text-white" href="{{route('admin.dashboard')}}">{{__('Home')}}</a>
                <span class="breadcrumb-item active">{{ __($title) }}</span>
                <span class="breadcrumb-info" id="time"></span>
            </nav>
        </div>
    </div>

    <!-- Generated Credentials Flash Banner -->
    @if(session('generated_credentials'))
        @php $creds = session('generated_credentials'); @endphp
        <div class="row mb-4">
            <div class="col-12">
                <div class="cred-box shadow">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-warning mb-0 font-weight-bold">
                            <i class="fas fa-key mr-2"></i> {{ __('Temporary Portal Credentials Generated') }}
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-light" onclick="copyCredentialsText()">
                            <i class="fas fa-copy mr-1"></i> {{ __('Copy Credentials') }}
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <small class="text-muted d-block">{{ __('Client Name') }}:</small>
                            <strong>{{ $creds['name'] }}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">{{ __('Portal Email / Login') }}:</small>
                            <code class="text-info font-weight-bold" id="cred-email">{{ $creds['email'] }}</code>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">{{ __('Temporary Password') }}:</small>
                            <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" id="cred-pwd" style="font-size: 14px; font-family: monospace;">{{ $creds['temp_password'] }}</span>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted d-block">{{ __('Default PIN') }}:</small>
                            <span class="badge badge-secondary px-2 py-1 font-weight-bold" id="cred-pin" style="font-size: 14px; font-family: monospace;">{{ $creds['default_pin'] }}</span>
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-info-circle mr-1"></i> {{ __('Client will be prompted to establish a permanent password and private 4-digit PIN upon first login.') }}
                    </small>
                </div>
            </div>
        </div>
    @endif

    <!-- Header Actions & Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-2">
            <div class="client-card-stat">
                <small class="text-muted text-uppercase font-weight-bold">{{ __('Total Registered Clients') }}</small>
                <h3 class="text-white font-weight-bold mb-0 mt-1">{{ count($clients) }}</h3>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="client-card-stat">
                <small class="text-muted text-uppercase font-weight-bold">{{ __('Inquiries / Prospective Leads') }}</small>
                <h3 class="text-warning font-weight-bold mb-0 mt-1">{{ count($recentLeads) }}</h3>
            </div>
        </div>
        <div class="col-md-4 mb-2 text-md-right d-flex align-items-center justify-content-md-end">
            <button type="button" class="btn btn-warning font-weight-bold text-dark px-4 py-2 shadow" data-toggle="modal" data-target="#addClientModal">
                <i class="fas fa-user-plus mr-1"></i> {{ __('Add New Client Account') }}
            </button>
        </div>
    </div>

    <!-- Main Client Directory Table -->
    <div class="row">
        <div class="col-12">
            <div class="card card-dark bg-dark">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0 font-weight-bold text-white"><i class="fas fa-users mr-2 text-warning"></i>{{ __('Legal & CPA Client Directory') }}</h6>
                </div>

                <div class="card-body">
                    <div class="table-responsive style-scroll">
                        <table id="slider" class="table bapric_table table-striped table-bordered text-white" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>{{ __('Client Details') }}</th>
                                    <th>{{ __('Contact Info') }}</th>
                                    <th>{{ __('Assigned Attorney / CPA') }}</th>
                                    <th>{{ __('Active Cases') }}</th>
                                    <th>{{ __('Security Status') }}</th>
                                    <th width="20%">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <div class="font-weight-bold text-white" style="font-size: 15px;">{{ $client->name }}</div>
                                        <small class="badge badge-dark border border-secondary text-warning">ID #CLI-{{ sprintf('%05d', $client->id) }}</small>
                                    </td>
                                    <td>
                                        <div><i class="fas fa-envelope mr-1 text-muted"></i> <a href="mailto:{{ $client->email }}" class="text-info">{{ $client->email }}</a></div>
                                        @if($client->phone)
                                            <div><i class="fas fa-phone mr-1 text-muted"></i> <span class="text-light small">{{ $client->phone }}</span></div>
                                        @endif
                                        <small class="text-muted">{{ $client->preferred_currency ?: 'USD' }}</small>
                                    </td>
                                    <td>
                                        @if($client->assignedAttorney)
                                            <span class="badge badge-info px-2 py-1"><i class="fas fa-user-tie mr-1"></i> {{ $client->assignedAttorney->name }}</span>
                                        @else
                                            <span class="text-muted small"><em>{{ __('Unassigned') }}</em></span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary px-2 py-1 font-weight-bold">
                                            {{ $client->clientCases ? $client->clientCases->count() : 0 }} {{ __('Cases') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($client->pin_hash && !$client->is_first_login)
                                            <span class="badge-pin-active"><i class="fas fa-check-circle mr-1"></i> {{ __('PIN Configured') }}</span>
                                        @else
                                            <span class="badge-pin-pending"><i class="fas fa-clock mr-1"></i> {{ __('Setup Pending') }}</span>
                                        @endif
                                        @if($client->is_temp_password)
                                            <div class="small text-warning mt-1"><i class="fas fa-key mr-1"></i> {{ __('Temp Password') }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap align-items-center" style="gap: 4px;">
                                            <!-- Send Welcome Email -->
                                            <button type="button" class="btn btn-xs btn-primary font-weight-bold" title="{{ __('Send Welcome Email') }}" onclick="openWelcomeEmailModal({{ $client->id }}, '{{ addslashes($client->name) }}', '{{ addslashes($client->email) }}')">
                                                <i class="fas fa-paper-plane mr-1"></i> {{ __('Email') }}
                                            </button>

                                            <!-- Generate Credentials -->
                                            <form action="{{ route('admin.user.client.generate-credentials', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Generate new temporary login credentials for this client?') }}');">
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-warning font-weight-bold text-dark" title="{{ __('Regenerate Credentials') }}">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                            </form>

                                            <!-- Impersonate Client -->
                                            <a href="{{ route('admin.user.client.impersonate', $client->id) }}" class="btn btn-xs btn-info font-weight-bold" title="{{ __('View Portal as Client') }}" target="_blank">
                                                <i class="fas fa-user-secret"></i> {{ __('View Portal') }}
                                            </a>

                                            <!-- Edit Modal Button -->
                                            <button type="button" data-toggle="modal" data-target="#usermodal" class="btn btn-xs btn-success user_btn" data-id="{{ $client->id }}" title="{{ __('Edit Client') }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Delete -->
                                            @if($client->id !== auth()->id())
                                                <form action="{{ route('admin.user.destroy', $client->id) }}" method="POST" onsubmit="return confirm('{{ __('Delete client record?') }}');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-danger" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add New Client Account -->
<div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-weight-bold text-warning" id="addClientModalLabel">
                    <i class="fas fa-user-plus mr-2"></i> {{ __('Provision New Client Account') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.user.client.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Prepopulate from Leads -->
                    @if(count($recentLeads) > 0)
                        <div class="form-group bg-dark border border-secondary p-3 rounded mb-3">
                            <label class="text-warning font-weight-bold mb-1"><i class="fas fa-bolt mr-1"></i> {{ __('Auto-Fill from Leads (Appointments, Inquiries & Consultations)') }}</label>
                            <select id="lead_selector" class="form-control bg-secondary text-white border-0" onchange="autoFillFromLead(this)">
                                <option value="">-- {{ __('Select a prospective lead to auto-populate client fields') }} --</option>
                                @foreach($recentLeads as $lead)
                                    <option value="{{ $lead->id }}" data-name="{{ $lead->name }}" data-email="{{ $lead->email }}" data-phone="{{ $lead->phone }}" data-message="{{ $lead->message }}">
                                        [{{ $lead->source }}] {{ $lead->name }} ({{ $lead->email }}) &bull; {{ $lead->created_at ? $lead->created_at->format('M d, Y') : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="client_name" class="font-weight-bold">{{ __('Full Legal Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="client_name" class="form-control bg-secondary text-white border-0" required placeholder="e.g. John Doe">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="client_email" class="font-weight-bold">{{ __('Primary Email Address') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="client_email" class="form-control bg-secondary text-white border-0" required placeholder="client@example.com">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="client_phone" class="font-weight-bold">{{ __('Direct Phone Number') }}</label>
                            <input type="text" name="phone" id="client_phone" class="form-control bg-secondary text-white border-0" placeholder="+1 (555) 000-0000">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="client_attorney" class="font-weight-bold">{{ __('Assign Attorney / CPA Lead') }}</label>
                            <select name="assigned_attorney_id" id="client_attorney" class="form-control bg-secondary text-white border-0">
                                <option value="">-- {{ __('Select Assigned Attorney') }} --</option>
                                @foreach($attorneys as $att)
                                    <option value="{{ $att->id }}">{{ $att->name }} ({{ $att->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="client_address" class="font-weight-bold">{{ __('Residential / Business Address') }}</label>
                            <input type="text" name="address" id="client_address" class="form-control bg-secondary text-white border-0" placeholder="Street, City, State, ZIP">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="client_currency" class="font-weight-bold">{{ __('Portal Currency') }}</label>
                            <select name="preferred_currency" id="client_currency" class="form-control bg-secondary text-white border-0">
                                <option value="USD">USD - $</option>
                                <option value="EUR">EUR - €</option>
                                <option value="GBP">GBP - £</option>
                                <option value="CAD">CAD - $</option>
                                <option value="AUD">AUD - $</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 mb-0 mt-2" style="background: rgba(37, 99, 235, 0.15); color: #93c5fd; font-size: 13px;">
                        <i class="fas fa-shield-alt mr-1"></i> <strong>Automated Provisioning:</strong> A secure temporary password and default PIN <code>1234</code> will be generated. The client will establish their own permanent credentials on first login.
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-warning font-weight-bold text-dark px-4">{{ __('Create Client Account') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Send Custom Welcome Email (Exact IFW 2-Column Live Preview Modal) -->
<div class="modal fade" id="welcomeEmailModal" tabindex="-1" role="dialog" aria-labelledby="welcomeEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content bg-dark text-white border-warning" style="border-radius: 14px; overflow: hidden; border: 2px solid #fecc56; box-shadow: 0 10px 40px rgba(0,0,0,0.6);">
            <div class="modal-header border-secondary py-3 px-4 d-flex justify-content-between align-items-center" style="background: #11151e;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-envelope-open-text text-warning fa-lg mr-3"></i>
                    <div>
                        <h5 class="modal-title text-warning font-weight-bold mb-0">{{ __('Send Official Welcome Email & Portal Credentials') }}</h5>
                        <small class="text-muted">{{ __('Live visual preview with official branding and cryptographic security credentials.') }}</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{ route('admin.user.client.send-welcome-email') }}" method="POST" id="welcomeEmailForm" onsubmit="document.getElementById('sendWelcomeBtn').innerHTML='<i class=\'fas fa-spinner fa-spin mr-2\'></i>Dispatching Email...'; document.getElementById('sendWelcomeBtn').disabled=true;">
                @csrf
                <input type="hidden" name="client_id" id="modal_email_client_id">

                <div class="modal-body p-4" style="background: #0f172a;">
                    <div class="row">
                        <!-- Left Column: Form Controls -->
                        <div class="col-lg-5 mb-4 mb-lg-0">
                            <div class="p-3 rounded border border-secondary mb-3" style="background: #1a202c;">
                                <h6 class="text-warning font-weight-bold mb-2 small text-uppercase" style="letter-spacing: 0.5px;"><i class="fas fa-user mr-2"></i>{{ __('Recipient Details') }}</h6>
                                <div class="mb-1 d-flex justify-content-between small">
                                    <span class="text-muted">{{ __('Client Name:') }}</span>
                                    <strong class="text-white" id="modal_display_name">Client</strong>
                                </div>
                                <div class="mb-1 d-flex justify-content-between small">
                                    <span class="text-muted">{{ __('Recipient Email:') }}</span>
                                    <strong class="text-warning" id="modal_display_email">client@example.com</strong>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">{{ __('Client Ref:') }}</span>
                                    <span class="badge badge-secondary font-weight-bold" id="modal_display_ref">#CLI-00000</span>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-white small"><i class="fas fa-heading mr-1 text-warning"></i> {{ __('Email Subject Line') }} <span class="text-danger">*</span></label>
                                <input type="text" name="email_subject" id="modal_email_subject" class="form-control bg-dark text-white border-secondary" value="Welcome to {{ config('app.name', 'Your CPA Expert') }} — Confidential Legal & CPA Portal Access" required oninput="updateLiveEmailPreview()">
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-white small"><i class="fas fa-paragraph mr-1 text-warning"></i> {{ __('Introduction Statement') }}</label>
                                <textarea name="email_intro" id="modal_email_intro" rows="2" class="form-control bg-dark text-white border-secondary" oninput="updateLiveEmailPreview()">Your confidential legal & CPA client file has been formally opened with our practice.</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-info small"><i class="fas fa-comment-alt mr-1"></i> {{ __('Attorney & CPA Case Briefing Note (Callout)') }}</label>
                                <textarea name="custom_note" id="modal_custom_note" rows="3" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Your case has been formally assigned to our Senior Lead Counsel. Initial tax analysis and regulatory filings are in progress under strict confidentiality." oninput="updateLiveEmailPreview()">Your case has been formally assigned to our Senior Lead Counsel. Initial tax analysis and regulatory filings are in progress under strict confidentiality.</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-white small"><i class="fas fa-info-circle mr-1 text-warning"></i> {{ __('Portal Instructions') }}</label>
                                <textarea name="email_portal_msg" id="modal_email_portal_msg" rows="2" class="form-control bg-dark text-white border-secondary" oninput="updateLiveEmailPreview()">You can access our 256-bit encrypted Client Portal 24/7 to review filings, inspect statements, upload documents, and communicate directly with your assigned Attorney & CPA.</textarea>
                            </div>

                            <div class="p-3 rounded border border-secondary mb-3" style="background: #1a202c;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="include_credentials" value="1" class="custom-control-input" id="includeCredsCheckbox" checked onchange="updateLiveEmailPreview()">
                                    <label class="custom-control-label font-weight-bold text-warning small" for="includeCredsCheckbox">
                                        {{ __('Include Temporary Password & PIN Credentials') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Interactive Live Email Preview -->
                        <div class="col-lg-7">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold text-warning small text-uppercase" style="letter-spacing: 0.5px;"><i class="fas fa-eye mr-1"></i> {{ __('Live Email Visual Preview') }}</span>
                                <small class="text-muted">{{ __('Exact rendering sent to client inbox') }}</small>
                            </div>

                            <!-- Styled Email Container Preview -->
                            <div class="border rounded shadow-sm" style="background: #ffffff; color: #1e293b; font-family: 'Montserrat', sans-serif; overflow: hidden; border-color: #cbd5e1 !important;">
                                <!-- Header with Logo -->
                                <div style="background: #111827; padding: 20px; text-align: center; border-bottom: 2px solid #fecc56;">
                                    <h4 class="text-white font-weight-bold mb-0" style="letter-spacing: 0.5px;">{{ config('app.name', 'Your CPA Expert') }}</h4>
                                    <div style="color: #fecc56; font-size: 10px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase;">{{ __('Privileged Legal & CPA Advisory Services') }}</div>
                                </div>

                                <!-- Email Body -->
                                <div style="padding: 24px; font-size: 13px; line-height: 1.6; color: #334155;">
                                    <p style="margin-top: 0; font-size: 14px;">Dear <strong id="previewClientName" style="color: #0f172a;">Client</strong>,</p>
                                    <p style="margin-bottom: 14px;">Welcome to <strong>{{ config('app.name', 'Your CPA Expert') }}</strong>. <span id="previewIntroText">Your confidential legal & CPA client file has been formally opened with our practice.</span></p>

                                    <!-- Highlight Note Block -->
                                    <div id="previewCustomNoteBlock" style="background: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 4px; padding: 12px 14px; margin: 14px 0; font-size: 12.5px; color: #1e3a8a;">
                                        <strong>Counsel Case Briefing:</strong><br>
                                        <span id="previewCustomNoteText">Your case has been formally assigned to our Senior Lead Counsel. Initial tax analysis and regulatory filings are in progress under strict confidentiality.</span>
                                    </div>

                                    <p id="previewPortalMsgText" style="margin-bottom: 14px;">You can access our 256-bit encrypted Client Portal 24/7 to review filings, inspect statements, upload documents, and communicate directly with your assigned Attorney & CPA.</p>

                                    <!-- Credentials Box -->
                                    <div id="previewCredsBlock" style="background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #fecc56; border-radius: 6px; padding: 14px 16px; margin: 16px 0;">
                                        <h6 style="margin: 0 0 10px 0; color: #1e293b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold;">Your Confidential Portal Credentials</h6>
                                        <table style="width: 100%; border-collapse: collapse; font-size: 12.5px;">
                                            <tr>
                                                <td style="padding: 4px 0; color: #64748b; width: 140px;"><strong>Username / Email:</strong></td>
                                                <td style="padding: 4px 0; color: #0f172a; font-weight: bold;" id="previewEmail">client@example.com</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; color: #64748b;"><strong>Temporary Password:</strong></td>
                                                <td style="padding: 4px 0;"><span style="background: #1f1b1c; color: #fecc56; font-family: monospace; font-size: 12px; font-weight: bold; padding: 2px 8px; border-radius: 4px; display: inline-block;">•••••••• (Auto-generated)</span></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; color: #64748b;"><strong>Default Security PIN:</strong></td>
                                                <td style="padding: 4px 0;"><span style="background: #1f1b1c; color: #fecc56; font-family: monospace; font-size: 12px; font-weight: bold; padding: 2px 8px; border-radius: 4px; display: inline-block;">1234</span></td>
                                            </tr>
                                        </table>
                                    </div>

                                    <!-- CTA Button -->
                                    <div style="text-align: center; margin: 20px 0;">
                                        <span style="background: #fecc56; color: #1f1b1c; font-weight: bold; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 24px; border-radius: 4px; display: inline-block; box-shadow: 0 4px 12px rgba(254, 204, 86, 0.4);">
                                            ACCESS CLIENT PORTAL &rarr;
                                        </span>
                                    </div>

                                    <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 6px; padding: 10px 12px; margin: 14px 0; font-size: 11px; color: #92400e;">
                                        <strong>Security Protocol:</strong> Upon first login, you will configure your permanent credentials and 4-digit Security PIN.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-secondary py-3 px-4" style="background: #11151e;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" id="sendWelcomeBtn" class="btn btn-warning font-weight-bold text-dark px-4" style="background: linear-gradient(135deg, #fecc56, #f0a500); border: none;">
                        <i class="fas fa-paper-plane mr-1"></i> {{ __('Dispatch Welcome Email') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal (Existing) -->
<div class="modal fade" id="usermodal" tabindex="-1" role="dialog" aria-labelledby="usermodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-weight-bold text-warning" id="usermodalLabel">{{ __('Edit Client Profile') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.user.save') }}" method="POST">
                @csrf
                <div class="modal-body" id="modal_data">
                    <div class="text-center p-3"><i class="fas fa-spinner fa-spin fa-2x text-warning"></i></div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-success font-weight-bold">{{ __('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    window.autoFillFromLead = function(select) {
        var opt = select.options[select.selectedIndex];
        if (opt && opt.value) {
            var name = opt.getAttribute('data-name') || '';
            var email = opt.getAttribute('data-email') || '';
            var phone = opt.getAttribute('data-phone') || '';
            
            var nameInput = document.getElementById('client_name');
            var emailInput = document.getElementById('client_email');
            var phoneInput = document.getElementById('client_phone');
            
            if (nameInput) nameInput.value = name;
            if (emailInput) emailInput.value = email;
            if (phoneInput) phoneInput.value = phone;
        }
    };

    window.openWelcomeEmailModal = function(clientId, name, email) {
        var idInput = document.getElementById('modal_email_client_id');
        var dispName = document.getElementById('modal_display_name');
        var dispEmail = document.getElementById('modal_display_email');
        var dispRef = document.getElementById('modal_display_ref');
        
        if (idInput) idInput.value = clientId;
        if (dispName) dispName.textContent = name;
        if (dispEmail) dispEmail.textContent = email;
        if (dispRef) dispRef.textContent = '#CLI-' + String(clientId).padStart(5, '0');
        
        var prevName = document.getElementById('previewClientName');
        var prevEmail = document.getElementById('previewEmail');
        if (prevName) prevName.textContent = name || 'Client';
        if (prevEmail) prevEmail.textContent = email || '';
        
        window.updateLiveEmailPreview();
        $('#welcomeEmailModal').modal('show');
    };

    window.updateLiveEmailPreview = function() {
        var introInput = document.getElementById('modal_email_intro');
        var introText = introInput ? introInput.value.trim() : '';
        var prevIntro = document.getElementById('previewIntroText');
        if (prevIntro) {
            prevIntro.textContent = introText || 'Your confidential legal & CPA client file has been formally opened with our practice.';
        }

        var noteInput = document.getElementById('modal_custom_note');
        var noteText = noteInput ? noteInput.value.trim() : '';
        var noteBlock = document.getElementById('previewCustomNoteBlock');
        var prevNote = document.getElementById('previewCustomNoteText');
        if (noteBlock && prevNote) {
            if (noteText) {
                prevNote.textContent = noteText;
                noteBlock.style.display = 'block';
            } else {
                noteBlock.style.display = 'none';
            }
        }

        var portalInput = document.getElementById('modal_email_portal_msg');
        var portalText = portalInput ? portalInput.value.trim() : '';
        var prevPortal = document.getElementById('previewPortalMsgText');
        if (prevPortal) {
            prevPortal.textContent = portalText || 'You can access our 256-bit encrypted Client Portal 24/7 to review filings, inspect statements, upload documents, and communicate directly with your assigned Attorney & CPA.';
        }

        var credsCheckbox = document.getElementById('includeCredsCheckbox');
        var credsBlock = document.getElementById('previewCredsBlock');
        if (credsBlock && credsCheckbox) {
            credsBlock.style.display = credsCheckbox.checked ? 'block' : 'none';
        }
    };

    window.copyCredentialsText = function() {
        var emailElem = document.getElementById('cred-email');
        var pwdElem = document.getElementById('cred-pwd');
        var pinElem = document.getElementById('cred-pin');
        
        var email = emailElem ? emailElem.innerText.trim() : '';
        var pwd = pwdElem ? pwdElem.innerText.trim() : '';
        var pin = pinElem ? pinElem.innerText.trim() : '';
        
        var text = "Portal Login: " + window.location.origin + "/login\nUsername: " + email + "\nTemporary Password: " + pwd + "\nDefault PIN: " + pin;
        navigator.clipboard.writeText(text).then(function() {
            alert('Credentials copied to clipboard!');
        });
    };

    $(document).ready(function() {
        $(document).on('click', '.user_btn', function() {
            var userId = $(this).data('id');
            $('#modal_data').html('<div class="text-center p-3"><i class="fas fa-spinner fa-spin fa-2x text-warning"></i></div>');
            $.ajax({
                url: "{{ route('admin.user.index') }}",
                type: 'GET',
                data: { id: userId },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response && response.data) {
                        $('#modal_data').html(response.data);
                    }
                },
                error: function(xhr) {
                    $('#modal_data').html('<div class="alert alert-danger p-2 m-2">Error loading user profile details.</div>');
                }
            });
        });
    });
</script>
@endsection
