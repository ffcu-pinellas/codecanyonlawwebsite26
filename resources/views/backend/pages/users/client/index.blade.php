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
                            <label class="text-warning font-weight-bold mb-1"><i class="fas fa-bolt mr-1"></i> {{ __('Auto-Fill from Website Inquiries / Leads') }}</label>
                            <select id="lead_selector" class="form-control bg-secondary text-white border-0" onchange="autoFillFromLead(this)">
                                <option value="">-- {{ __('Select an inquiry to auto-populate fields') }} --</option>
                                @foreach($recentLeads as $lead)
                                    <option value="{{ $lead->id }}" data-name="{{ $lead->name }}" data-email="{{ $lead->email }}" data-phone="{{ $lead->phone }}" data-message="{{ $lead->message }}">
                                        {{ $lead->name }} ({{ $lead->email }}) &bull; {{ $lead->created_at ? $lead->created_at->format('M d, Y') : '' }}
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

<!-- Modal: Send Custom Welcome Email -->
<div class="modal fade" id="welcomeEmailModal" tabindex="-1" role="dialog" aria-labelledby="welcomeEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-weight-bold text-warning" id="welcomeEmailModalLabel">
                    <i class="fas fa-paper-plane mr-2"></i> {{ __('Send Customized Welcome Email & Portal Access') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.user.client.send-welcome-email') }}" method="POST">
                @csrf
                <input type="hidden" name="client_id" id="modal_email_client_id">

                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('Recipient') }}</label>
                        <input type="text" id="modal_email_recipient_display" class="form-control bg-secondary text-white border-0 font-weight-bold" readonly disabled>
                    </div>

                    <div class="form-group">
                        <label for="modal_email_subject" class="font-weight-bold">{{ __('Email Subject Line') }} <span class="text-danger">*</span></label>
                        <input type="text" name="email_subject" id="modal_email_subject" class="form-control bg-secondary text-white border-0" required value="Welcome to {{ config('app.name', 'Your CPA Expert') }} – Confidential Legal & CPA Portal Access">
                    </div>

                    <div class="form-group">
                        <label for="modal_email_intro" class="font-weight-bold">{{ __('Introduction Statement') }}</label>
                        <textarea name="email_intro" id="modal_email_intro" rows="2" class="form-control bg-secondary text-white border-0">Your confidential legal & CPA file has been officially opened with our practice. You can access our secure Client Portal 24/7.</textarea>
                    </div>

                    <div class="form-group">
                        <label for="modal_custom_note" class="font-weight-bold text-info">
                            <i class="fas fa-comment-alt mr-1"></i> {{ __('Attorney & CPA Case Briefing Note (Optional Highlight Callout)') }}
                        </label>
                        <textarea name="custom_note" id="modal_custom_note" rows="3" class="form-control bg-secondary text-white border-0" placeholder="e.g. We have reviewed your 2024-2025 tax schedules and drafted the initial petition. Please log in to inspect the uploaded documents."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="modal_email_portal_msg" class="font-weight-bold">{{ __('Portal Access Instructions') }}</label>
                        <textarea name="email_portal_msg" id="modal_email_portal_msg" rows="2" class="form-control bg-secondary text-white border-0">You can access our 256-bit encrypted Client Portal 24/7 to review tax and case filings, inspect invoices, upload documents, and communicate directly with your assigned Attorney & CPA.</textarea>
                    </div>

                    <div class="form-check p-3 rounded bg-secondary mb-0">
                        <input type="checkbox" name="include_credentials" id="include_credentials" value="1" class="form-check-input" checked>
                        <label for="include_credentials" class="form-check-label font-weight-bold text-warning ml-2">
                            {{ __('Generate & Include Temporary Login Credentials & Default PIN in this Email') }}
                        </label>
                    </div>
                </div>

                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-warning font-weight-bold text-dark px-4">
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

@section('page-js')
<script>
    function autoFillFromLead(select) {
        var opt = select.options[select.selectedIndex];
        if (opt && opt.value) {
            document.getElementById('client_name').value = opt.getAttribute('data-name') || '';
            document.getElementById('client_email').value = opt.getAttribute('data-email') || '';
            document.getElementById('client_phone').value = opt.getAttribute('data-phone') || '';
        }
    }

    function openWelcomeEmailModal(clientId, name, email) {
        document.getElementById('modal_email_client_id').value = clientId;
        document.getElementById('modal_email_recipient_display').value = name + ' (' + email + ')';
        $('#welcomeEmailModal').modal('show');
    }

    function copyCredentialsText() {
        var email = document.getElementById('cred-email').innerText;
        var pwd = document.getElementById('cred-pwd').innerText;
        var pin = document.getElementById('cred-pin').innerText;
        var text = "Portal Login: " + window.location.origin + "/login\nUsername: " + email + "\nTemporary Password: " + pwd + "\nDefault PIN: " + pin;
        navigator.clipboard.writeText(text).then(function() {
            alert('Credentials copied to clipboard!');
        });
    }

    $(document).ready(function() {
        $('.user_btn').on('click', function() {
            var userId = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.user.index') }}",
                type: 'GET',
                data: { id: userId },
                success: function(response) {
                    $('#modal_data').html(response.data);
                }
            });
        });
    });
</script>
@endsection
