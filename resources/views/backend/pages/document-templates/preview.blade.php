@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    .preview-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 30px rgba(0,0,0,0.15);
        overflow: hidden;
    }
    .email-preview-window {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        color: #1f2937;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }
    .email-header {
        background: #ffffff;
        padding: 15px 20px;
        border-bottom: 1px solid #e5e7eb;
    }
    .email-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #e0f2fe;
        color: #0369a1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        margin-right: 12px;
    }
    .email-subject-line {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
    }
    .email-meta-info {
        font-size: 12px;
        color: #6b7280;
    }
    .email-body-container {
        padding: 25px;
        background: #f3f4f6;
    }
    .email-envelope {
        background: #ffffff;
        max-width: 550px;
        margin: 0 auto;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    .email-brand-bar {
        height: 4px;
        background: linear-gradient(90deg, #1e3c72 0%, #2a5298 100%);
    }
    .email-logo-section {
        padding: 20px 30px;
        text-align: center;
        border-bottom: 1px solid #f3f4f6;
    }
    .email-logo-section img {
        max-height: 40px;
        width: auto;
        object-fit: contain;
    }
    .email-logo-section .company-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e3c72;
        margin-top: 5px;
    }
    .email-logo-section .company-meta {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 3px;
        line-height: 1.3;
    }
    .email-content-td {
        padding: 30px;
        line-height: 1.5;
        font-size: 14px;
        color: #374151;
    }
    .email-footer-td {
        background-color: #f9fafb;
        padding: 20px 30px;
        text-align: center;
        font-size: 11px;
        color: #6b7280;
        border-top: 1px solid #f3f4f6;
    }
    .attachment-badge {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        margin-top: 15px;
        font-size: 13px;
        color: #4b5563;
    }
    .attachment-badge i {
        font-size: 18px;
        color: #ef4444;
        margin-right: 8px;
    }
</style>
@endsection

@section('content')
<div id="wrapper-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                    <a class="breadcrumb-item text-white" href="{{ route('admin.document-templates.index') }}">{{ __('Document Templates') }}</a>
                    <span class="breadcrumb-item active">{{ __('Preview & Process') }}</span>
                    <span class="breadcrumb-info" id="time"></span>
                </nav>
            </div>
        </div>

        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="material-icons align-middle mr-1">check_circle</i> {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="material-icons align-middle mr-1">error</i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="{{ route('admin.document-templates.process', $template->id) }}" method="POST" id="process-form">
            @csrf
            <input type="hidden" name="action" id="form-action" value="email">

            <div class="row">
                <!-- Left Column: Settings & Live Email Preview -->
                <div class="col-lg-5">
                    <div class="card preview-card bg-dark text-white mb-4">
                        <div class="card-header bg-secondary">
                            <h6 class="card-title mb-0"><i class="material-icons align-middle mr-1">settings</i> {{ __('Process Settings') }}</h6>
                        </div>
                        <div class="card-body">
                            <!-- Recipient Config -->
                            <div class="form-group mb-3">
                                <label for="user_id" class="small text-white font-weight-bold">{{ __('Select Recipient Profile') }} <span class="text-danger">*</span></label>
                                <select name="user_id" id="user_id" class="form-control bg-dark text-white border-secondary" required>
                                    <option value="" data-email="">-- {{ __('Choose Profile') }} --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" data-email="{{ $user->email }}" {{ (request('user_id') == $user->id || (isset($selectedUser) && $selectedUser->id == $user->id)) ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1">{{ __('Selecting a profile reloads details & populates placeholders.') }}</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="recipient_email" class="small text-white font-weight-bold">{{ __('Recipient Email Address') }}</label>
                                <input type="email" name="recipient_email" id="recipient_email" class="form-control bg-dark text-white border-secondary" placeholder="e.g. recipient@example.com" value="{{ old('recipient_email', $recipientEmail) }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="action_required" class="small text-white font-weight-bold">{{ __('Action Required by Recipient') }}</label>
                                <select name="action_required" id="action_required" class="form-control bg-dark text-white border-secondary">
                                    <option value="none" {{ old('action_required') == 'none' ? 'selected' : '' }}>For Records Only (No Action Required)</option>
                                    <option value="approve" {{ old('action_required') == 'approve' ? 'selected' : '' }}>Review and Approve Document</option>
                                    <option value="sign_upload" {{ old('action_required') == 'sign_upload' ? 'selected' : '' }}>Review, Sign, and Upload Signed Copy</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="admin_notes" class="small text-white font-weight-bold">{{ __('Instructions / Notes for Recipient') }}</label>
                                <textarea name="admin_notes" id="admin_notes" class="form-control bg-dark text-white border-secondary" rows="2" placeholder="e.g. Please check the date on page 2 before signing.">{{ old('admin_notes') }}</textarea>
                            </div>

                            <hr class="bg-secondary">

                            <!-- Email Configurations -->
                            <h6 class="font-weight-bold text-warning mb-3"><i class="material-icons align-middle mr-1">mail</i> {{ __('Email Settings') }}</h6>

                            <div class="form-group mb-3">
                                <label for="email_subject" class="small text-white font-weight-bold">{{ __('Email Subject Line') }}</label>
                                <input type="text" name="email_subject" id="email_subject" class="form-control bg-dark text-white border-secondary" value="{{ old('email_subject', $emailSubject) }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="email_body" class="small text-white font-weight-bold">{{ __('Email Intro Text') }}</label>
                                <textarea name="email_body" id="email_body" class="form-control bg-dark text-white border-secondary" rows="5" required>{{ old('email_body', $emailBody) }}</textarea>
                                <small class="text-muted">{{ __('This intro text will wrap within our standard professional corporate email template.') }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Email Live Preview Window -->
                    <div class="card preview-card bg-dark text-white mb-4">
                        <div class="card-header bg-secondary">
                            <h6 class="card-title mb-0"><i class="material-icons align-middle mr-1">visibility</i> {{ __('Email Client Preview') }}</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="email-preview-window">
                                <div class="email-header d-flex align-items-center">
                                    <div class="email-avatar">CS</div>
                                    <div class="flex-grow-1">
                                        <div class="email-subject-line" id="email-preview-subject">Action Required: Document review</div>
                                        <div class="email-meta-info">
                                            From: <strong>{{ $companyName }} Operations Team</strong> &lt;{{ $companyEmail }}&gt;<br>
                                            To: <span id="email-preview-to" class="font-weight-bold">recipient@example.com</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="email-body-container">
                                    <div class="email-envelope">
                                        <div class="email-brand-bar"></div>
                                        <div class="email-logo-section">
                                            @php
                                                $logoSetting = \App\Models\GeneralSettings::first();
                                                $logoPath = $logoSetting && $logoSetting->logo ? asset('uploads/settings/'.$logoSetting->logo) : asset('frontend/theme1/assets/images/logo.png');
                                            @endphp
                                            <img src="{{ $logoPath }}" alt="{{ $companyName }}" onerror="this.style.display='none';">
                                            <div class="company-title">{{ $companyName }}</div>
                                            <div class="company-meta">
                                                <strong>Corporate Office</strong> &bull; Address: {{ $companyAddress }}<br>
                                                Phone: {{ $companyPhone }} | Email: {{ $companyEmail }}
                                            </div>
                                        </div>
                                        <div class="email-content-td" id="email-preview-body">
                                            <!-- Managed dynamically by Javascript -->
                                        </div>
                                        <div class="email-footer-td">
                                            <p><strong>&copy; {{ date('Y') }} {{ $companyName }}</strong>. All Rights Reserved.</p>
                                            <p style="font-style: italic; font-size: 10px;">This is an automated notification. Please do not reply directly to this email.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Document Populated Content & Actions -->
                <div class="col-lg-7">
                    <div class="card preview-card bg-dark text-white mb-4">
                        <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0"><i class="material-icons align-middle mr-1">edit</i> {{ __('Populated Draft Editor') }}</h6>
                            <span class="badge badge-warning text-uppercase">{{ $template->type }} {{ __('Template') }}</span>
                        </div>
                        <div class="card-body bg-secondary p-3">
                            <div class="form-group mb-0">
                                <label class="small text-white font-weight-bold mb-2">{{ __('Customize Document Content for Selected Recipient') }}</label>
                                <textarea class="form-control bapric_edittor" name="document_content" id="document_content" rows="20">{!! clean($content) !!}</textarea>
                            </div>
                        </div>
                        <div class="card-footer bg-dark border-top border-secondary d-flex justify-content-between p-3">
                            <a href="{{ route('admin.document-templates.index') }}" class="btn btn-secondary font-weight-bold px-4 py-2">
                                <i class="material-icons align-middle mr-1">cancel</i> {{ __('Cancel') }}
                            </a>
                            <div class="d-flex">
                                <button type="button" id="btn-download" class="btn btn-info font-weight-bold px-4 py-2 mr-2">
                                    <i class="material-icons align-middle mr-1">file_download</i> {{ __('Download PDF') }}
                                </button>
                                <button type="button" id="btn-send-email" class="btn btn-warning font-weight-bold px-4 py-2">
                                    <i class="material-icons align-middle mr-1">send</i> {{ __('Send Email Notification') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card preview-card bg-dark text-white">
                        <div class="card-body">
                            <h6 class="font-weight-bold text-info"><i class="material-icons align-middle mr-1">info</i> {{ __('Dynamic Populated Drafts') }}</h6>
                            <p class="small text-muted mb-0">
                                {{ __('Modifying the content above allows making corrections, adding special details, or customizing formatting specifically for this recipient. These edits are saved to the recipient\'s records but DO NOT alter the core template structure for future operations.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('page-script')
<script src="{{ asset('backend/assets/js/form-summerNote.js') }}"></script>
<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            var companyName = "{{ $companyName }}";

            // Escape HTML helper
            function escapeHtml(text) {
                if (!text) return '';
                return text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // Real-time live email body formatting
            function updateEmailPreview() {
                var subject = $('#email_subject').val() || 'No Subject';
                var bodyText = $('#email_body').val() || '';
                var adminNotes = $('#admin_notes').val() || '';
                
                $('#email-preview-subject').text(subject);
                
                var recipientEmail = $('#recipient_email').val() || 'recipient@example.com';
                $('#email-preview-to').text(recipientEmail);

                // Greeting Name resolution
                var recipientName = "Valued Member";
                var selectedOption = $('#user_id').find('option:selected');
                if (selectedOption && selectedOption.val() !== "") {
                    // Extract name from "John Doe (john@example.com)"
                    recipientName = selectedOption.text().replace(/\s*\(.*\)\s*/g, '').trim();
                }

                var formattedHtml = '<p style="font-size: 15px; font-weight: 600; color: #1e3c72; margin-top: 0; margin-bottom: 15px;">Dear ' + escapeHtml(recipientName) + ',</p>';
                
                var lines = bodyText.split('\n');
                var inTable = false;
                
                for (var i = 0; i < lines.length; i++) {
                    var line = lines[i].trim();
                    if (line === '') {
                        if (inTable) {
                            formattedHtml += '</table>';
                            inTable = false;
                        }
                        formattedHtml += '<div style="height: 8px;"></div>';
                        continue;
                    }
                    
                    // Match key: value patterns
                    var match = line.match(/^([^*:]+)\s*:\s*(.+)$/);
                    if (match) {
                        var key = match[1].replace(/\*/g, '').trim();
                        var val = match[2].trim();
                        
                        if (!inTable) {
                            formattedHtml += '<table cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 0; border: 1px solid #e2e8f0; border-collapse: separate; border-spacing: 0; border-radius: 6px; overflow: hidden; background-color: #fafbfc;">';
                            inTable = true;
                        }
                        formattedHtml += '<tr>'
                            + '<td style="padding: 8px 12px; width: 35%; font-weight: 600; color: #2d3748; border-bottom: 1px solid #edf2f7; font-size: 13px; background-color: #edf2f7;">' + escapeHtml(key) + '</td>'
                            + '<td style="padding: 8px 12px; color: #4a5568; border-bottom: 1px solid #edf2f7; font-size: 13px;">' + escapeHtml(val) + '</td>'
                            + '</tr>';
                    } else {
                        if (inTable) {
                            formattedHtml += '</table>';
                            inTable = false;
                        }
                        var cleanLine = line.replace(/\*\*/g, '<strong>').replace(/\*/g, '<em>');
                        formattedHtml += '<p style="margin: 8px 0; font-size: 14px; color: #4a5568; line-height: 1.5;">' + cleanLine + '</p>';
                    }
                }
                if (inTable) {
                    formattedHtml += '</table>';
                }
                
                // Add Attachment Badge
                formattedHtml += '<div class="attachment-badge">'
                    + '<i class="material-icons align-middle mr-2">picture_as_pdf</i>'
                    + '<span><strong>' + escapeHtml("{{ $template->title }}") + '.pdf</strong> (Attachment - PDF)</span>'
                    + '</div>';

                // Add Admin Notes/Instructions
                if (adminNotes.trim() !== '') {
                    formattedHtml += '<div style="margin-top: 15px; padding: 12px; background-color: #fffbeb; border-left: 4px solid #d97706; border-radius: 4px; font-size: 13px; color: #b45309;">'
                        + '<strong style="color: #d97706;">Instructions from Administrator:</strong><br>'
                        + escapeHtml(adminNotes).replace(/\n/g, '<br>')
                        + '</div>';
                }
                
                // Add Action Badge
                var actionReq = $('#action_required').val();
                if (actionReq !== 'none') {
                    var badgeText = actionReq === 'approve' ? 'Review & Approve' : 'Review, Sign & Upload';
                    var badgeColor = actionReq === 'approve' ? '#2563eb' : '#db2777';
                    formattedHtml += '<div style="margin-top: 20px; text-align: center;">'
                        + '<span style="display: inline-block; padding: 6px 16px; background-color: ' + badgeColor + '; color: white; font-weight: bold; border-radius: 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">'
                        + badgeText + ' REQUIRED</span>'
                        + '</div>';
                }
                
                formattedHtml += '<p style="margin-top: 25px; margin-bottom: 0; font-size: 14px; color: #718096;">Best Regards,<br><strong style="color: #1e3c72;">' + escapeHtml(companyName) + ' Team</strong></p>';
                
                $('#email-preview-body').html(formattedHtml);
            }

            // Recipient Profile Dropdown Switch (GET Page Reload)
            $('#user_id').on('change', function() {
                var selectedVal = $(this).val();
                var url = new URL(window.location.href);
                if (selectedVal) {
                    url.searchParams.set('user_id', selectedVal);
                } else {
                    url.searchParams.delete('user_id');
                }
                window.location.href = url.toString();
            });

            // Recipient Email input sync with select
            $('#recipient_email').on('input', function() {
                $('#email-preview-to').text($(this).val() || 'recipient@example.com');
            });

            // Keyup listeners for live preview updates
            $('#email_subject, #email_body, #admin_notes').on('keyup input change', updateEmailPreview);
            $('#action_required').on('change', updateEmailPreview);

            // Set up actions
            $('#btn-send-email').on('click', function(e) {
                e.preventDefault();
                $('#form-action').val('email');
                $('#process-form').submit();
            });

            $('#btn-download').on('click', function(e) {
                e.preventDefault();
                $('#form-action').val('download');
                $('#process-form').submit();
            });

            // Initialize preview on load
            updateEmailPreview();
        });
    })(jQuery);
</script>
@include('backend.layouts.message')
@endsection
