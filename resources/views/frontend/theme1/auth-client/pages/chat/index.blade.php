@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    .chat-card {
        background: #11151e;
        border: 1px solid #28303f;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: calc(100vh - 170px);
        min-height: 560px;
    }
    .chat-header {
        background: #161a23;
        border-bottom: 1px solid #28303f;
        padding: 14px 20px;
    }
    .chat-messages-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #0a0c10;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .chat-bubble {
        max-width: 75%;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 13.5px;
        line-height: 1.45;
        position: relative;
        word-break: break-word;
    }
    .chat-bubble-in {
        align-self: flex-start;
        background: #181d27;
        color: #f1f5f9;
        border: 1px solid #28303f;
        border-bottom-left-radius: 2px;
    }
    .chat-bubble-out {
        align-self: flex-end;
        background: linear-gradient(135deg, #1e293b 0%, #172033 100%);
        color: #ffffff;
        border: 1px solid #3b4559;
        border-bottom-right-radius: 2px;
    }
    .chat-bubble-time {
        font-size: 10.5px;
        color: #94a3b8;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .chat-bubble-out .chat-bubble-time {
        justify-content: flex-end;
        color: #fecc56;
    }
    .chat-attachment-box {
        background: rgba(0,0,0,0.25);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 6px;
        padding: 6px 10px;
        margin-top: 6px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .chat-footer {
        background: #161a23;
        border-top: 1px solid #28303f;
        padding: 14px 20px;
    }
    .chat-input-control {
        background: #0f131a !important;
        border: 1px solid #28303f !important;
        color: #ffffff !important;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 13.5px;
    }
    .chat-input-control:focus {
        border-color: #fecc56 !important;
        box-shadow: 0 0 0 2px rgba(254,204,86,0.2) !important;
    }
    .btn-gold {
        background: linear-gradient(135deg, #fecc56, #f0a500);
        color: #000 !important;
        font-weight: 700;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        transition: all 0.2s;
    }
    .btn-gold:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(254,204,86,0.45);
    }
    @media (max-width: 768px) {
        .chat-bubble { max-width: 90%; }
        .chat-card { height: calc(100vh - 140px); }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="chat-card">
        <!-- Header -->
        <div class="chat-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mr-3" style="width: 44px; height: 44px; background: rgba(254,204,86,0.15); color: #fecc56; font-size: 18px; font-weight: bold; border: 2px solid #fecc56;">
                    {{ strtoupper(substr($counsel->name ?? 'C', 0, 1)) }}
                </div>
                <div>
                    <h6 class="font-weight-bold text-white mb-0" style="font-size: 15px;">{{ $counsel->name ?? 'Gary Livingston, Senior CPA & Legal Counsel' }}</h6>
                    <div class="d-flex align-items-center small" style="gap: 8px;">
                        <span class="text-success font-weight-bold" style="font-size: 11px;"><i class="fas fa-circle mr-1" style="font-size: 8px;"></i> {{ __('Counsel Online & Active') }}</span>
                        <span class="text-muted">&bull;</span>
                        <span class="text-warning font-weight-bold" style="font-size: 11px;">CLI-{{ sprintf('%05d', Auth::user()->id) }}</span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <span class="badge d-none d-sm-inline-block px-3 py-2" style="background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3); font-size: 11px;">
                    <i class="fas fa-shield-alt mr-1"></i> {{ __('256-Bit Encrypted Case Line') }}
                </span>
                <a href="{{ route('client.dashboard') }}" class="btn btn-sm btn-outline-secondary text-light px-3 font-weight-bold" style="border-color: #3b4252;">
                    <i class="fas fa-arrow-left mr-1"></i> {{ __('Dashboard') }}
                </a>
            </div>
        </div>

        <!-- Chat Stream Body -->
        <div class="chat-messages-body" id="chatMessagesStream">
            @forelse($messages as $msg)
                @php $isSender = ($msg->user_id === Auth::id()); @endphp
                <div class="chat-bubble {{ $isSender ? 'chat-bubble-out' : 'chat-bubble-in' }}" id="msg-item-{{ $msg->id }}" data-msg-id="{{ $msg->id }}">
                    @if(!$isSender)
                        <div class="font-weight-bold text-warning small mb-1" style="font-size: 11px;">{{ $msg->user->name ?? 'Assigned Counsel' }}</div>
                    @endif
                    
                    @if(!empty($msg->text))
                        <div>{{ $msg->text }}</div>
                    @endif

                    @if(!empty($msg->file))
                        <div class="chat-attachment-box">
                            <i class="fas fa-file-alt text-warning"></i>
                            <a href="{{ asset($msg->file) }}" target="_blank" class="text-white small font-weight-bold text-decoration-none">
                                {{ $msg->file_name ?: __('Attached Document') }}
                            </a>
                        </div>
                    @endif

                    <div class="chat-bubble-time">
                        <span>{{ $msg->created_at->format('h:i A') }}</span>
                        @if($isSender)
                            <i class="fas fa-check-double text-warning ml-1" style="font-size: 9px;"></i>
                        @endif
                    </div>
                </div>
            @empty
                <div id="noMessagesPlaceholder" class="p-5 text-center text-muted my-auto">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(254,204,86,0.1); border: 2px solid rgba(254,204,86,0.25); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i class="fas fa-comments fa-2x text-warning"></i>
                    </div>
                    <h6 class="text-white font-weight-bold mb-1">{{ __('Direct Case Representation Line') }}</h6>
                    <p class="small text-muted mb-0" style="max-width: 420px; margin: 0 auto;">
                        {{ __('Type your message or inquiry below to communicate directly with your assigned Attorney & CPA. All messages and attachments are encrypted and preserved in your case file.') }}
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Chat Input Footer -->
        <div class="chat-footer">
            <form id="clientChatForm" enctype="multipart/form-data" onsubmit="handleSendChat(event)">
                @csrf
                <!-- Selected File Preview Badge -->
                <div id="chatFileBadge" class="mb-2" style="display: none;">
                    <span class="badge badge-dark p-2 text-warning border border-secondary font-weight-normal d-inline-flex align-items-center">
                        <i class="fas fa-paperclip mr-1"></i> <span id="chatFileName"></span>
                        <button type="button" class="btn btn-link text-danger p-0 ml-2" onclick="clearSelectedFile()" style="font-size: 14px; line-height: 1;">&times;</button>
                    </span>
                </div>

                <div class="d-flex align-items-center" style="gap: 8px;">
                    <!-- Attachment Button -->
                    <button type="button" class="btn btn-dark text-warning p-2" onclick="document.getElementById('chatAttachmentInput').click();" title="{{ __('Attach Document or Photo') }}" style="background: #0f131a; border: 1px solid #28303f; border-radius: 8px; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-paperclip fa-lg"></i>
                    </button>
                    <input type="file" name="file" id="chatAttachmentInput" class="d-none" onchange="handleFileSelected(event)" accept=".pdf,.docx,.doc,.jpg,.jpeg,.png,.xlsx">

                    <!-- Text Input -->
                    <input type="text" name="text" id="chatTextInput" class="form-control chat-input-control" placeholder="{{ __('Type your message to counsel here...') }}" autocomplete="off">

                    <!-- Send Button -->
                    <button type="submit" id="chatSendBtn" class="btn btn-gold d-inline-flex align-items-center" style="height: 44px;">
                        <i class="fas fa-paper-plane mr-1" id="sendBtnIcon"></i> <span class="d-none d-sm-inline">{{ __('Send') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    var conversationSlug = '{{ $conversation->slug ?? '' }}';
    var lastMessageId = {{ $messages->last() ? $messages->last()->id : 0 }};

    function scrollChatToBottom() {
        var stream = document.getElementById('chatMessagesStream');
        if (stream) {
            stream.scrollTop = stream.scrollHeight;
        }
    }

    function handleFileSelected(e) {
        var file = e.target.files[0];
        if (file) {
            document.getElementById('chatFileName').innerText = file.name;
            document.getElementById('chatFileBadge').style.display = 'block';
        }
    }

    function clearSelectedFile() {
        document.getElementById('chatAttachmentInput').value = '';
        document.getElementById('chatFileBadge').style.display = 'none';
    }

    function handleSendChat(e) {
        e.preventDefault();
        if (!conversationSlug) return;

        var textInput = document.getElementById('chatTextInput');
        var text = textInput.value.trim();
        var fileInput = document.getElementById('chatAttachmentInput');
        var file = fileInput.files[0];

        if (!text && !file) return;

        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('text', text);
        if (file) formData.append('file', file);

        var sendBtn = document.getElementById('chatSendBtn');
        var icon = document.getElementById('sendBtnIcon');
        sendBtn.disabled = true;
        icon.className = 'fas fa-spinner fa-spin';

        $.ajax({
            url: "/client/conversation/send-chat/" + conversationSlug,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success && res.message) {
                    appendMessageBubble(res.message);
                    textInput.value = '';
                    clearSelectedFile();
                    lastMessageId = res.message.id;
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON ? xhr.responseJSON.error : 'Error sending message. Please try again.');
            },
            complete: function() {
                sendBtn.disabled = false;
                icon.className = 'fas fa-paper-plane mr-1';
                scrollChatToBottom();
            }
        });
    }

    function appendMessageBubble(msg) {
        var ph = document.getElementById('noMessagesPlaceholder');
        if (ph) ph.remove();

        var stream = document.getElementById('chatMessagesStream');
        if (!stream) return;

        // Prevent duplicate append
        if (document.getElementById('msg-item-' + msg.id)) return;

        var isSender = msg.is_sender;
        var bubble = document.createElement('div');
        bubble.className = 'chat-bubble ' + (isSender ? 'chat-bubble-out' : 'chat-bubble-in');
        bubble.id = 'msg-item-' + msg.id;
        bubble.setAttribute('data-msg-id', msg.id);

        var html = '';
        if (!isSender) {
            html += '<div class="font-weight-bold text-warning small mb-1" style="font-size: 11px;">' + (msg.user_name || 'Counsel') + '</div>';
        }
        if (msg.text) {
            html += '<div>' + $('<div>').text(msg.text).html() + '</div>';
        }
        if (msg.file) {
            html += '<div class="chat-attachment-box"><i class="fas fa-file-alt text-warning"></i><a href="' + msg.file + '" target="_blank" class="text-white small font-weight-bold text-decoration-none">' + (msg.file_name || 'Attached Document') + '</a></div>';
        }
        html += '<div class="chat-bubble-time"><span>' + msg.time + '</span>' + (isSender ? '<i class="fas fa-check-double text-warning ml-1" style="font-size: 9px;"></i>' : '') + '</div>';

        bubble.innerHTML = html;
        stream.appendChild(bubble);
        scrollChatToBottom();
    }

    // Auto-polling for new incoming messages every 3 seconds
    function pollIncomingMessages() {
        if (!conversationSlug) return;
        $.get("/client/conversation/poll/" + conversationSlug, { last_id: lastMessageId }, function(res) {
            if (res && res.messages && res.messages.length > 0) {
                res.messages.forEach(function(msg) {
                    appendMessageBubble(msg);
                    if (msg.id > lastMessageId) lastMessageId = msg.id;
                });
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        scrollChatToBottom();
        setInterval(pollIncomingMessages, 3000);
    });
</script>
@endsection
