@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .chat-card {
        display: flex;
        flex-direction: column;
        height: 550px;
        overflow: hidden;
        border-radius: 8px;
    }
    .chat-header {
        background: #2a2d32;
        padding: 15px 20px;
        border-bottom: 1px solid #3e444c;
        position: sticky;
        top: 0;
        z-index: 20;
    }
    .chat-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #1e2124;
    }
    .chat-bubble {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 12px;
        margin-bottom: 12px;
        position: relative;
    }
    .chat-bubble-sent {
        background: #007bff;
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 2px;
    }
    .chat-bubble-received {
        background: #2f3136;
        color: #dcddde;
        align-self: flex-start;
        border-bottom-left-radius: 2px;
        border: 1px solid #3f4248;
    }
    .chat-time {
        font-size: 0.65rem;
        display: block;
        margin-top: 5px;
        opacity: 0.7;
    }
    .chat-bubble-sent .chat-time {
        text-align: right;
        color: rgba(255, 255, 255, 0.7);
    }
    .chat-bubble-received .chat-time {
        text-align: left;
        color: #9f1;
    }
    .chat-footer {
        background: #2f3136;
        padding: 15px 20px;
        border-top: 1px solid #3e444c;
    }
</style>
@endsection

@section('content')
    <div id="wrapper-content">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                    <a class="breadcrumb-item text-white" href="{{ route('admin.staff.index') }}">{{ __('Staff Directory') }}</a>
                    <span class="breadcrumb-item active">{{ __($title) }}</span>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card card-dark bg-dark chat-card">
                    <!-- Chat Header -->
                    <div class="chat-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary btn-sm mr-3" style="border-radius: 50%; width: 32px; height: 32px; padding: 4px;"><i class="fas fa-arrow-left text-white"></i></a>
                            <div>
                                <h6 class="card-title mb-0">{{ $staff->name }}</h6>
                                <span class="badge badge-info small" style="font-size: 0.65rem;">{{ __('Staff Member') }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-outline-info btn-sm font-weight-bold mr-2" onclick="launchStaffCall('audio')">
                                <i class="fas fa-phone-alt mr-1"></i> {{ __('Voice Call') }}
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm font-weight-bold mr-3" onclick="launchStaffCall('video')">
                                <i class="fas fa-video mr-1"></i> {{ __('HD Video Call') }}
                            </button>
                            <div class="small text-success d-none d-md-block">
                                <i class="fas fa-shield-alt mr-1"></i> {{ __('Audit Secured Session') }}
                            </div>
                        </div>
                    </div>

                    <!-- Chat Message Area -->
                    <div class="chat-messages d-flex flex-column" id="chat-messages-container">
                        @forelse($messages as $msg)
                            @if($msg->sender_id === Auth::id())
                                <!-- Sent by Admin -->
                                <div class="chat-bubble chat-bubble-sent align-self-end" data-msg-id="{{ $msg->id }}">
                                    @if(!empty($msg->message))
                                        <div class="chat-text">{{ $msg->message }}</div>
                                    @endif
                                    @if(!empty($msg->file))
                                        <div class="mt-1">
                                            <a href="{{ asset($msg->file) }}" target="_blank" class="text-warning font-weight-bold small text-decoration-none">
                                                <i class="fas fa-file-alt mr-1"></i> {{ $msg->file_name ?: __('Attached Document') }}
                                            </a>
                                        </div>
                                    @endif
                                    <span class="chat-time">{{ $msg->created_at->format('M d, h:i A') }}</span>
                                </div>
                            @else
                                <!-- Received from Staff -->
                                <div class="chat-bubble chat-bubble-received align-self-start" data-msg-id="{{ $msg->id }}">
                                    @if(!empty($msg->message))
                                        <div class="chat-text">{{ $msg->message }}</div>
                                    @endif
                                    @if(!empty($msg->file))
                                        <div class="mt-1">
                                            <a href="{{ asset($msg->file) }}" target="_blank" class="text-warning font-weight-bold small text-decoration-none">
                                                <i class="fas fa-file-alt mr-1"></i> {{ $msg->file_name ?: __('Attached Document') }}
                                            </a>
                                        </div>
                                    @endif
                                    <span class="chat-time" style="color: #a5b4fc;">{{ $msg->created_at->format('M d, h:i A') }}</span>
                                </div>
                            @endif
                        @empty
                            <div class="my-auto text-center text-muted py-5" id="chat-empty-state">
                                <i class="far fa-comments fa-3x mb-3 text-white-50"></i>
                                <p class="mb-0">{{ __('No messages exchanged yet.') }}</p>
                                <small>{{ __('Type a message below to start chatting with ') . $staff->name }}</small>
                            </div>
                        @endforelse
                    </div>

                    <!-- Chat Footer -->
                    <div class="chat-footer">
                        <!-- Magic Keyboard Staff Live Typing Sneak-Peek Indicator -->
                        <div id="adminStaffTypingBox" class="p-2 mb-2 rounded small text-warning font-weight-bold d-none" style="background: rgba(254, 204, 86, 0.1); border: 1px dashed rgba(254, 204, 86, 0.35);">
                            <i class="fas fa-pen-nib mr-1"></i> <span>{{ $staff->name }}</span> {{ __('is typing live:') }} "<span id="adminStaffTypingText" class="text-white"></span>"
                        </div>

                        <form action="{{ route('admin.staff.send-message', $staff->id) }}" method="POST" id="admin-chat-form" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <textarea class="form-control bg-dark text-white border-secondary" name="message" id="adminStaffChatInput" rows="2" placeholder="{{ __('Type your message here to send to ') . $staff->name }}..." style="resize: none;"></textarea>
                                <label for="adminStaffFileInput" class="btn btn-outline-secondary mb-0 d-flex align-items-center justify-content-center text-light px-3" style="border-radius: 0; border: 1px solid #6c757d; border-left: none; cursor: pointer;" title="Attach File">
                                    <i class="fas fa-paperclip"></i>
                                    <input type="file" name="file" id="adminStaffFileInput" class="d-none" onchange="previewAdminStaffFile(this)">
                                </label>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-paper-plane mr-2"></i> {{ __('Send') }}
                                    </button>
                                </div>
                            </div>
                            <div id="adminStaffFileBadge" class="small text-warning mt-1 font-weight-bold d-none">
                                <i class="fas fa-file-alt mr-1"></i> <span id="adminStaffFileName"></span>
                                <a href="javascript:void(0)" onclick="clearAdminStaffFile()" class="text-danger ml-2"><i class="fas fa-times-circle"></i></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
<script>
    function previewAdminStaffFile(input) {
        if (input.files && input.files[0]) {
            $('#adminStaffFileName').text(input.files[0].name);
            $('#adminStaffFileBadge').removeClass('d-none');
        }
    }

    function clearAdminStaffFile() {
        $('#adminStaffFileInput').val('');
        $('#adminStaffFileBadge').addClass('d-none');
    }

    window.launchStaffCall = function(type) {
        if (typeof window.initiateWebRtcCall === 'function') {
            window.initiateWebRtcCall({{ $staff->id }}, @json($staff->name), type, null);
        } else {
            alert('Calling system initializing. Please try again.');
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('chat-messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }

        // Web Audio Chime generator
        function playAdminChime() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const ctx = new AudioContext();
                const now = ctx.currentTime;
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, now);
                osc.frequency.exponentialRampToValueAtTime(880, now + 0.15);
                gain.gain.setValueAtTime(0.18, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.4);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.4);
            } catch(e) {}
        }

        function triggerAdminDesktopAlert(title, text) {
            playAdminChime();

            let origTitle = document.title;
            let isBlinking = true;
            let blinkInterval = setInterval(() => {
                if (!isBlinking) return;
                document.title = (document.title === origTitle) ? '🔔 New Staff Message!' : origTitle;
            }, 1000);

            const stopBlink = () => {
                isBlinking = false;
                clearInterval(blinkInterval);
                document.title = origTitle;
                window.removeEventListener('focus', stopBlink);
            };
            window.addEventListener('focus', stopBlink);

            if ('Notification' in window) {
                if (Notification.permission === 'granted') {
                    const notif = new Notification(title, {
                        body: text,
                        icon: '/favicon.ico',
                        tag: 'admin-staff-chat'
                    });
                    notif.onclick = function() {
                        window.focus();
                        this.close();
                    };
                } else if (Notification.permission !== 'denied') {
                    Notification.requestPermission();
                }
            }
        }

        if ('Notification' in window && Notification.permission === 'default') {
            document.addEventListener('click', function reqNotif() {
                Notification.requestPermission();
                document.removeEventListener('click', reqNotif);
            }, { once: true });
        }

        function escapeHtml(text) {
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function getLastMessageId() {
            let lastId = 0;
            $('.chat-bubble').each(function() {
                let id = parseInt($(this).attr('data-msg-id'));
                if (id > lastId) lastId = id;
            });
            return lastId;
        }

        function pollMessages() {
            let lastId = getLastMessageId();
            $.ajax({
                url: '{{ route("admin.staff.messages.poll", $staff->id) }}',
                method: 'GET',
                data: { last_id: lastId },
                success: function(response) {
                    // Live Typing Preview of Staff member
                    if (response.staff_typing && response.staff_typing.draft) {
                        $('#adminStaffTypingText').text(response.staff_typing.draft);
                        $('#adminStaffTypingBox').removeClass('d-none');
                    } else {
                        $('#adminStaffTypingBox').addClass('d-none');
                    }

                    if (response.success && response.messages.length > 0) {
                        $('#chat-empty-state').remove();
                        let newBubbles = false;
                        response.messages.forEach(function(msg) {
                            if ($(`[data-msg-id="${msg.id}"]`).length === 0) {
                                let bubbleClass = msg.is_sent ? 'chat-bubble-sent align-self-end' : 'chat-bubble-received align-self-start';
                                let timeStyle = msg.is_sent ? '' : 'style="color: #a5b4fc;"';
                                let fileHtml = '';
                                if (msg.file) {
                                    fileHtml = `<div class="mt-1"><a href="${msg.file}" target="_blank" class="text-warning font-weight-bold small text-decoration-none"><i class="fas fa-file-alt mr-1"></i> ${escapeHtml(msg.file_name || 'Attached Document')}</a></div>`;
                                }
                                let bubble = `
                                    <div class="chat-bubble ${bubbleClass}" data-msg-id="${msg.id}">
                                        ${msg.message ? `<div class="chat-text">${escapeHtml(msg.message)}</div>` : ''}
                                        ${fileHtml}
                                        <span class="chat-time" ${timeStyle}>${msg.created_at}</span>
                                    </div>
                                `;
                                $('#chat-messages-container').append(bubble);
                                newBubbles = true;
                                if (!msg.is_sent) {
                                    triggerAdminDesktopAlert('New Message from {{ $staff->name }}', msg.message || 'Sent an attachment');
                                }
                            }
                        });
                        if (newBubbles && container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    }
                }
            });
        }

        // Poll every 3 seconds
        setInterval(pollMessages, 3000);

        // jQuery AJAX message sending
        let isAdminSubmitting = false;

        // Enter key to send (Shift+Enter for newline)
        $('#adminStaffChatInput').on('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (!isAdminSubmitting) {
                    $('#admin-chat-form').trigger('submit');
                }
            }
        });

        $('#admin-chat-form').on('submit', function(e) {
            e.preventDefault();
            if (isAdminSubmitting) return;

            var form = document.getElementById('admin-chat-form');
            var textarea = $('#adminStaffChatInput');
            var sendBtn = $(this).find('button[type="submit"]');
            var message = textarea.val().trim();
            var fileInput = document.getElementById('adminStaffFileInput');
            var hasFile = fileInput && fileInput.files && fileInput.files.length > 0;

            if (message === '' && !hasFile) return;

            isAdminSubmitting = true;
            sendBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>');

            var formData = new FormData(form);
            textarea.val('');
            clearAdminStaffFile();

            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('#chat-empty-state').remove();

                        if ($(`[data-msg-id="${response.id}"]`).length === 0) {
                            var fileHtml = '';
                            if (response.file) {
                                fileHtml = `<div class="mt-1"><a href="${response.file}" target="_blank" class="text-warning font-weight-bold small text-decoration-none"><i class="fas fa-file-alt mr-1"></i> ${escapeHtml(response.file_name || 'Attached Document')}</a></div>`;
                            }
                            var bubble = `
                                <div class="chat-bubble chat-bubble-sent align-self-end" data-msg-id="${response.id}">
                                    ${response.message ? `<div class="chat-text">${escapeHtml(response.message)}</div>` : ''}
                                    ${fileHtml}
                                    <span class="chat-time">${response.created_at}</span>
                                </div>
                            `;
                            $('#chat-messages-container').append(bubble);
                        }
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    }
                },
                error: function(err) {
                    textarea.val(message);
                    console.error("Admin chat submission failed:", err);
                },
                complete: function() {
                    isAdminSubmitting = false;
                    sendBtn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-2"></i> {{ __("Send") }}');
                    textarea.focus();
                }
            });
        });
    });
</script>
@endsection
