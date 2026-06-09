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
                        <div class="small text-success">
                            <i class="fas fa-shield-alt mr-1"></i> {{ __('Audit Secured Session') }}
                        </div>
                    </div>

                    <!-- Chat Message Area -->
                    <div class="chat-messages d-flex flex-column" id="chat-messages-container">
                        @forelse($messages as $msg)
                            @if($msg->sender_id === Auth::id())
                                <!-- Sent by Admin -->
                                <div class="chat-bubble chat-bubble-sent align-self-end" data-msg-id="{{ $msg->id }}">
                                    <div class="chat-text">{{ $msg->message }}</div>
                                    <span class="chat-time">{{ $msg->created_at->format('M d, h:i A') }}</span>
                                </div>
                            @else
                                <!-- Received from Staff -->
                                <div class="chat-bubble chat-bubble-received align-self-start" data-msg-id="{{ $msg->id }}">
                                    <div class="chat-text">{{ $msg->message }}</div>
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
                        <form action="{{ route('admin.staff.send-message', $staff->id) }}" method="POST" id="admin-chat-form">
                            @csrf
                            <div class="input-group">
                                <textarea class="form-control bg-dark text-white border-secondary" name="message" rows="2" placeholder="{{ __('Type your message here to send to ') . $staff->name }}..." required style="resize: none;"></textarea>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-paper-plane mr-2"></i> {{ __('Send') }}
                                    </button>
                                </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('chat-messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
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
                    if (response.success && response.messages.length > 0) {
                        $('#chat-empty-state').remove();
                        let newBubbles = false;
                        response.messages.forEach(function(msg) {
                            if ($(`[data-msg-id="${msg.id}"]`).length === 0) {
                                let bubbleClass = msg.is_sent ? 'chat-bubble-sent align-self-end' : 'chat-bubble-received align-self-start';
                                let timeStyle = msg.is_sent ? '' : 'style="color: #a5b4fc;"';
                                let bubble = `
                                    <div class="chat-bubble ${bubbleClass}" data-msg-id="${msg.id}">
                                        <div class="chat-text">${escapeHtml(msg.message)}</div>
                                        <span class="chat-time" ${timeStyle}>${msg.created_at}</span>
                                    </div>
                                `;
                                $('#chat-messages-container').append(bubble);
                                newBubbles = true;
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
        $('#admin-chat-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var textarea = form.find('textarea[name="message"]');
            var message = textarea.val().trim();
            if (message === '') return;

            $.ajax({
                url: url,
                method: 'POST',
                data: form.serialize(),
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
                        textarea.val('');
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    }
                }
            });
        });
    });
</script>
@endsection
