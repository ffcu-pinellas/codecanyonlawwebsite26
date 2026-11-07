@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .chat-card {
        background: #11151e;
        border-radius: 12px;
        border: 1px solid #28303f;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        display: flex;
        flex-direction: column;
        height: 640px;
        overflow: hidden;
    }
    .chat-header {
        background: #161a23;
        border-bottom: 1px solid #28303f;
        padding: 16px 20px;
    }
    .chat-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #0a0c10;
    }
    .chat-bubble {
        max-width: 72%;
        padding: 12px 18px;
        border-radius: 14px;
        margin-bottom: 12px;
        position: relative;
        font-size: 13.5px;
        line-height: 1.45;
    }
    .chat-bubble-sent {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: #ffffff;
        align-self: flex-end;
        border-bottom-right-radius: 3px;
        box-shadow: 0 2px 8px rgba(30, 60, 114, 0.35);
    }
    .chat-bubble-received {
        background: #161a23;
        color: #f1f5f9;
        align-self: flex-start;
        border-bottom-left-radius: 3px;
        border: 1px solid #28303f;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .chat-bubble-received .chat-text {
        color: #f1f5f9;
    }
    .chat-bubble-sent .chat-text {
        color: #ffffff;
    }
    .chat-time {
        font-size: 0.68rem;
        display: block;
        margin-top: 5px;
        opacity: 0.8;
    }
    .chat-bubble-sent .chat-time {
        text-align: right;
        color: rgba(255, 255, 255, 0.7);
    }
    .chat-bubble-received .chat-time {
        text-align: left;
        color: #94a3b8;
    }
    .chat-footer {
        background: #161a23;
        border-top: 1px solid #28303f;
        padding: 16px 20px;
    }

    /* Light Mode Overrides */
    body.light-mode .chat-card, html.light-mode .chat-card {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06) !important;
    }
    body.light-mode .chat-header, html.light-mode .chat-header {
        background: #f8fafc !important;
        border-bottom-color: #e2e8f0 !important;
    }
    body.light-mode .chat-header h5, html.light-mode .chat-header h5 {
        color: #0f172a !important;
    }
    body.light-mode .chat-messages, html.light-mode .chat-messages {
        background: #f8fafc !important;
    }
    body.light-mode .chat-bubble-received, html.light-mode .chat-bubble-received {
        background: #ffffff !important;
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04) !important;
    }
    body.light-mode .chat-bubble-received .chat-text, html.light-mode .chat-bubble-received .chat-text {
        color: #0f172a !important;
    }
    body.light-mode .chat-bubble-received .chat-time, html.light-mode .chat-bubble-received .chat-time {
        color: #64748b !important;
    }
    body.light-mode .chat-footer, html.light-mode .chat-footer {
        background: #ffffff !important;
        border-top-color: #e2e8f0 !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="font-weight-bold text-white mb-1"><i class="fas fa-comments text-warning mr-2"></i>{{ __('Internal Team Chat') }}</h4>
            <p class="text-muted small mb-0">{{ __('Confidential internal practice communications channel with your supervising officer.') }}</p>
        </div>
        <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary btn-sm text-light font-weight-bold px-3">
            <i class="fas fa-arrow-left mr-1"></i> {{ __('Dashboard') }}
        </a>
    </div>

    <div class="chat-card">
        <!-- Chat Header -->
        <div class="chat-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center text-dark font-weight-bold" style="width: 40px; height: 40px; background: #fecc56; font-size: 15px;">
                    {{ strtoupper(substr($officer->name ?? 'S', 0, 1)) }}
                </div>
                <div>
                    <h5 class="mb-0 text-white font-weight-bold" style="font-size: 15px;">{{ $officer->name }}</h5>
                    <span class="badge" style="background: rgba(254,204,86,0.15); color: #fecc56; border: 1px solid rgba(254,204,86,0.3); font-size: 10px;">
                        <i class="fas fa-user-shield mr-1"></i> {{ __('Assigned Practice Supervisor') }}
                    </span>
                </div>
            </div>
            <div class="text-muted small">
                <span class="badge" style="background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3); font-size: 10.5px;">
                    <i class="fas fa-shield-alt mr-1"></i> {{ __('Encrypted Channel') }}
                </span>
            </div>
        </div>

        <!-- Chat Messages Panel -->
        <div class="chat-messages d-flex flex-column" id="chat-messages-container">
            @forelse($messages as $msg)
                @if($msg->sender_id === Auth::id())
                    <!-- Sent message -->
                    <div class="chat-bubble chat-bubble-sent align-self-end" data-msg-id="{{ $msg->id }}">
                        <div class="chat-text">{{ $msg->message }}</div>
                        <span class="chat-time">{{ $msg->created_at->format('M d, h:i A') }}</span>
                    </div>
                @else
                    <!-- Received message -->
                    <div class="chat-bubble chat-bubble-received align-self-start" data-msg-id="{{ $msg->id }}">
                        <div class="chat-text">{{ $msg->message }}</div>
                        <span class="chat-time">{{ $msg->created_at->format('M d, h:i A') }}</span>
                    </div>
                @endif
            @empty
                <div class="my-auto text-center text-muted py-5" id="chat-empty-state">
                    <i class="fas fa-comments fa-3x mb-3 text-warning" style="opacity: 0.5;"></i>
                    <h6 class="text-white font-weight-bold mb-1">{{ __('No messages exchanged yet.') }}</h6>
                    <small class="text-muted">{{ __('Type below to send a message to your supervising officer.') }}</small>
                </div>
            @endforelse
        </div>

        <!-- Chat Input Footer -->
        <div class="chat-footer">
            <!-- Magic Keyboard Supervisor Live Typing Sneak-Peek Indicator -->
            <div id="staffTypingSneakPeek" class="p-2 mb-2 rounded small text-warning font-weight-bold d-none" style="background: rgba(254, 204, 86, 0.1); border: 1px dashed rgba(254, 204, 86, 0.35);">
                <i class="fas fa-pen-nib mr-1"></i> <span id="staffTypingOfficerName">{{ $officer->name ?? 'Supervisor' }}</span> {{ __('is typing live:') }} "<span id="staffTypingDraftText" class="text-white"></span>"
            </div>

            <!-- Quick Emoji Bar -->
            <div class="d-flex flex-wrap align-items-center mb-2" style="gap: 6px;">
                <small class="text-muted mr-1" style="font-size: 11px;"><i class="far fa-smile text-warning mr-1"></i>{{ __('Quick Emojis:') }}</small>
                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #11151e; border: 1px solid #28303f; font-size: 13px;" onclick="insertStaffEmoji('😊')">😊</span>
                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #11151e; border: 1px solid #28303f; font-size: 13px;" onclick="insertStaffEmoji('👍')">👍</span>
                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #11151e; border: 1px solid #28303f; font-size: 13px;" onclick="insertStaffEmoji('📁')">📁</span>
                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #11151e; border: 1px solid #28303f; font-size: 13px;" onclick="insertStaffEmoji('⚖️')">⚖️</span>
                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #11151e; border: 1px solid #28303f; font-size: 13px;" onclick="insertStaffEmoji('💼')">💼</span>
                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #11151e; border: 1px solid #28303f; font-size: 13px;" onclick="insertStaffEmoji('✅')">✅</span>
                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #11151e; border: 1px solid #28303f; font-size: 13px;" onclick="insertStaffEmoji('🔒')">🔒</span>
                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #11151e; border: 1px solid #28303f; font-size: 13px;" onclick="insertStaffEmoji('📝')">📝</span>
                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #11151e; border: 1px solid #28303f; font-size: 13px;" onclick="insertStaffEmoji('🤝')">🤝</span>
                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #11151e; border: 1px solid #28303f; font-size: 13px;" onclick="insertStaffEmoji('📎')">📎</span>
                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #11151e; border: 1px solid #28303f; font-size: 13px;" onclick="insertStaffEmoji('💡')">💡</span>
                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #11151e; border: 1px solid #28303f; font-size: 13px;" onclick="insertStaffEmoji('❓')">❓</span>
            </div>

            <form action="{{ route('staff.messages') }}" method="POST" id="chat-form">
                @csrf
                <div class="input-group">
                    <textarea class="form-control" name="message" id="staffChatMessageInput" rows="2" placeholder="{{ __('Type your message here and press Enter to send...') }}" required style="resize: none; border-radius: 8px 0 0 8px; background: #11151e; border: 1px solid #28303f; color: #f1f5f9;"></textarea>
                    <div class="input-group-append">
                        <button type="submit" id="staffChatSendBtn" class="btn font-weight-bold px-4" style="background: linear-gradient(135deg, #fecc56, #f0a500); color: #000; border: none; border-radius: 0 8px 8px 0;">
                            <i class="fas fa-paper-plane mr-2"></i> {{ __('Send') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    function insertStaffEmoji(emoji) {
        const input = document.getElementById('staffChatMessageInput');
        if (!input) return;
        const start = input.selectionStart || input.value.length;
        const end = input.selectionEnd || input.value.length;
        input.value = input.value.substring(0, start) + emoji + input.value.substring(end);
        input.focus();
        input.selectionStart = input.selectionEnd = start + emoji.length;
        triggerStaffTypingStream(input.value);
    }

    let staffTypingTimeout = null;
    function triggerStaffTypingStream(draftText) {
        clearTimeout(staffTypingTimeout);
        staffTypingTimeout = setTimeout(function() {
            $.ajax({
                url: '{{ route("staff.messages.typing-draft") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    draft: draftText
                }
            });
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('chat-messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }

        // Web Audio Chime generator
        function playStaffChime() {
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

        function triggerStaffDesktopAlert(title, text) {
            playStaffChime();

            let origTitle = document.title;
            let isBlinking = true;
            let blinkInterval = setInterval(() => {
                if (!isBlinking) return;
                document.title = (document.title === origTitle) ? '🔔 New Firm Message!' : origTitle;
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
                        tag: 'staff-chat-msg'
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
                url: '{{ route("staff.messages.poll") }}',
                method: 'GET',
                data: { last_id: lastId },
                success: function(response) {
                    // 1. Live Typing Preview Sneak-Peek
                    if (response.supervisor_typing && response.supervisor_typing.draft) {
                        $('#staffTypingDraftText').text(response.supervisor_typing.draft);
                        $('#staffTypingSneakPeek').removeClass('d-none');
                    } else {
                        $('#staffTypingSneakPeek').addClass('d-none');
                    }

                    // 2. Incoming messages
                    if (response.success && response.messages.length > 0) {
                        $('#chat-empty-state').remove();
                        let newBubbles = false;
                        response.messages.forEach(function(msg) {
                            if ($(`[data-msg-id="${msg.id}"]`).length === 0) {
                                let bubbleClass = msg.is_sent ? 'chat-bubble-sent align-self-end' : 'chat-bubble-received align-self-start';
                                let timeColor = msg.is_sent ? 'rgba(255, 255, 255, 0.7)' : '#7f8c8d';
                                let bubble = `
                                    <div class="chat-bubble ${bubbleClass}" data-msg-id="${msg.id}">
                                        <div class="chat-text">${escapeHtml(msg.message)}</div>
                                        <span class="chat-time" style="color: ${timeColor}">${msg.created_at}</span>
                                    </div>
                                `;
                                $('#chat-messages-container').append(bubble);
                                newBubbles = true;
                                if (!msg.is_sent) {
                                    triggerStaffDesktopAlert('New Message from ' + (response.officer_name || 'Counsel / Admin'), msg.message);
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

        // Keystroke listener for live typing preview
        $('#staffChatMessageInput').on('input', function() {
            triggerStaffTypingStream($(this).val());
        });

        // Strong in-flight submission lock & debounce
        let isSendingStaffMsg = false;
        let lastStaffSendTs = 0;

        function submitStaffChat() {
            const now = Date.now();
            if (isSendingStaffMsg || (now - lastStaffSendTs < 1200)) return false;

            const textarea = $('#staffChatMessageInput');
            const message = textarea.val().trim();
            if (message === '') return false;

            isSendingStaffMsg = true;
            lastStaffSendTs = now;
            const sendBtn = $('#staffChatSendBtn');
            sendBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>');
            textarea.val('');

            $.ajax({
                url: $('#chat-form').attr('action'),
                method: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(),
                    message: message
                },
                success: function(response) {
                    if (response.success) {
                        $('#chat-empty-state').remove();

                        if ($(`[data-msg-id="${response.id}"]`).length === 0) {
                            var bubble = `
                                <div class="chat-bubble chat-bubble-sent align-self-end" data-msg-id="${response.id}">
                                    <div class="chat-text">${escapeHtml(response.message)}</div>
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
                    console.error("Staff chat submission failed:", err);
                },
                complete: function() {
                    isSendingStaffMsg = false;
                    sendBtn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-2"></i> {{ __("Send") }}');
                    textarea.focus();
                }
            });
        }

        // Enter key to send (Shift+Enter for newline)
        $('#staffChatMessageInput').on('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                e.stopPropagation();
                submitStaffChat();
                return false;
            }
        });

        $('#chat-form').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            submitStaffChat();
            return false;
        });
    });
</script>
@endsection
