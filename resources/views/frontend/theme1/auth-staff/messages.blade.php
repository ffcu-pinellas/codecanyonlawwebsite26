@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .chat-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
        height: 600px;
        overflow: hidden;
    }
    .chat-header {
        background: #f8f9fa;
        border-bottom: 1px solid #eaeded;
        padding: 18px 25px;
    }
    .chat-messages {
        flex: 1;
        padding: 25px;
        overflow-y: auto;
        background: #f4f6f7;
    }
    .chat-bubble {
        max-width: 70%;
        padding: 12px 18px;
        border-radius: 16px;
        margin-bottom: 15px;
        position: relative;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .chat-bubble-sent {
        background: #3498db;
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }
    .chat-bubble-received {
        background: white;
        color: #2c3e50;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
        border: 1px solid #e2e8f0;
    }
    .chat-time {
        font-size: 0.65rem;
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
        color: #7f8c8d;
    }
    .chat-footer {
        background: white;
        border-top: 1px solid #eaeded;
        padding: 20px 25px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="chat-card">
        <!-- Chat Header -->
        <div class="chat-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary btn-sm mr-3" style="border-radius: 50%; width: 32px; height: 32px; padding: 4px;"><i class="fas fa-arrow-left"></i></a>
                <div>
                    <h5 class="mb-0" style="font-weight: 700; color: #2c3e50;">{{ $officer->name }}</h5>
                    <span class="badge badge-success small" style="font-size: 0.7rem;">{{ __('Assigned Officer') }}</span>
                </div>
            </div>
            <div class="text-muted small">
                <i class="fas fa-circle text-success mr-1"></i> {{ __('Online Secure Connection') }}
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
                    <i class="far fa-comments fa-3x mb-3 text-white-50"></i>
                    <p class="mb-0">{{ __('No messages exchanged yet.') }}</p>
                    <small>{{ __('Send a message below to start the conversation with your officer.') }}</small>
                </div>
            @endforelse
        </div>

        <!-- Chat Input Footer -->
        <div class="chat-footer">
            <form action="{{ route('staff.messages') }}" method="POST" id="chat-form">
                @csrf
                <div class="input-group">
                    <textarea class="form-control" name="message" rows="2" placeholder="{{ __('Type your message here for the officer...') }}" required style="resize: none; border-radius: 8px 0 0 8px; border-color: #bdc3c7;"></textarea>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary px-4" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none; border-radius: 0 8px 8px 0;">
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
                url: '{{ route("staff.messages.poll") }}',
                method: 'GET',
                data: { last_id: lastId },
                success: function(response) {
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

        // jQuery AJAX form submission
        $('#chat-form').on('submit', function(e) {
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
                        // Remove empty state
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
