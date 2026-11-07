@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert').' | '.$title)

@section('page-css')
    <style>
        .msg_card_body {
            max-height: 550px;
            overflow-y: auto;
            padding: 20px;
            background: #0f172a;
        }
        .msg_cotainer_send {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: #ffffff;
            border-radius: 16px 16px 0 16px;
            padding: 12px 18px;
            max-width: 70%;
            position: relative;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .msg_cotainer {
            background: #1e293b;
            color: #f1f5f9;
            border-radius: 16px 16px 16px 0;
            padding: 12px 18px;
            max-width: 70%;
            position: relative;
            border: 1px solid #334155;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .msg_time_send {
            display: block;
            font-size: 10px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 4px;
            text-align: right;
        }
        .msg_time {
            display: block;
            font-size: 10px;
            color: #94a3b8;
            margin-top: 4px;
        }
        .user_img_msg {
            width: 38px;
            height: 38px;
            object-fit: cover;
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
                        <a class="breadcrumb-item text-white" href="{{ route('admin.conversation.index') }}">{{ __('Client Conversations') }}</a>
                        <span class="breadcrumb-item active">{{ __($title) }}</span>
                        <span class="breadcrumb-info" id="time"></span>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-dark bg-dark border-secondary">
                        <div class="card-header d-flex justify-content-between align-items-center py-3 border-secondary flex-wrap" style="gap: 10px;">
                            <div>
                                <h5 class="card-title text-white font-weight-bold mb-0">
                                    <i class="fas fa-comments text-warning mr-2"></i>{{ $title }}
                                </h5>
                                <small class="text-muted">Direct secure case communication channel</small>
                            </div>
                            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                <button type="button" class="btn btn-warning btn-sm font-weight-bold text-dark shadow-sm px-3" data-toggle="modal" data-target="#initiateCallModal">
                                    <i class="fas fa-video mr-1"></i> {{ __('Start Consultation Call') }}
                                </button>
                                <a href="{{ route('admin.conversation.index') }}" class="btn btn-outline-secondary btn-sm text-light">
                                    <i class="fas fa-arrow-left mr-1"></i> Back to All Chats
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0 messages-content">
                            <div class="msg_card_body" id="chatBox">
                                @forelse($conversation->messages as $message)
                                    @php
                                        $isMine = ($message->user_id === Auth::id());
                                        $msgBody = $message->text ?: $message->message;
                                        $userAvatar = $message->user?->profile_photo_url ? asset($message->user->profile_photo_url) : asset('backend/assets/img/profile/other.png');
                                        $senderName = $message->user ? $message->user->name : ($isMine ? 'Admin Counsel' : 'Client');
                                    @endphp

                                    @if($isMine)
                                        <div class="d-flex justify-content-end mb-3 msg-item" data-msg-id="{{ $message->id }}">
                                            <div class="msg_cotainer_send">
                                                @if(!empty($message->file))
                                                    <p class="mb-1"><a href="{{ route('download.chatting-file', $message->id) }}" class="text-warning font-weight-bold" target="_blank"><i class="fas fa-paperclip mr-1"></i> {{ $message->file_name ?: 'Attachment' }}</a></p>
                                                @endif
                                                @if(!empty($msgBody))
                                                    <div>{!! nl2br(e($msgBody)) !!}</div>
                                                @endif
                                                <span class="msg_time_send">{{ $message->created_at->format('M d, h:i A') }}</span>
                                            </div>
                                            <div class="img_cont_msg ml-2">
                                                <img src="{{ $userAvatar }}" class="rounded-circle user_img_msg" title="{{ $senderName }}">
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-start mb-3 msg-item" data-msg-id="{{ $message->id }}">
                                            <div class="img_cont_msg mr-2">
                                                <img src="{{ $userAvatar }}" class="rounded-circle user_img_msg" title="{{ $senderName }}">
                                            </div>
                                            <div class="msg_cotainer">
                                                <div class="font-weight-bold text-warning small mb-1">{{ $senderName }}</div>
                                                @if(!empty($message->file))
                                                    <p class="mb-1"><a href="{{ route('download.chatting-file', $message->id) }}" class="text-warning font-weight-bold" target="_blank"><i class="fas fa-paperclip mr-1"></i> {{ $message->file_name ?: 'Attachment' }}</a></p>
                                                @endif
                                                @if(!empty($msgBody))
                                                    <div>{!! nl2br(e($msgBody)) !!}</div>
                                                @endif
                                                <span class="msg_time">{{ $message->created_at->format('M d, h:i A') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center py-5 text-muted" id="chatEmptyPlaceholder">
                                        <i class="fas fa-comments fa-3x mb-3 text-secondary d-block"></i>
                                        <h5 class="text-white font-weight-bold">No Messages in this Thread Yet</h5>
                                        <p class="small text-muted">Type below to begin case representation conversation with {{ $title }}.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="card-footer bg-dark border-secondary p-3">
                            <!-- Magic Keyboard Client Live Typing Sneak-Peek Indicator -->
                            <div id="adminLiveTypingBox" class="p-2 mb-2 rounded small text-warning font-weight-bold d-none" style="background: rgba(254, 204, 86, 0.1); border: 1px dashed rgba(254, 204, 86, 0.35);">
                                <i class="fas fa-pen-nib mr-1"></i> <span id="adminTypingClientName">{{ $client ? $client->name : 'Client' }}</span> {{ __('is typing live:') }} "<span id="adminTypingDraftText" class="text-white"></span>"
                            </div>

                            <!-- Professional Quick Emoji Toolbar -->
                            <div class="d-flex flex-wrap align-items-center mb-2" style="gap: 6px;">
                                <small class="text-muted mr-1" style="font-size: 11px;"><i class="far fa-smile text-warning mr-1"></i>{{ __('Quick Emojis:') }}</small>
                                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #0f172a; border: 1px solid #334155; font-size: 13px;" onclick="insertAdminEmoji('😊')" title="Smile">😊</span>
                                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #0f172a; border: 1px solid #334155; font-size: 13px;" onclick="insertAdminEmoji('👍')" title="Thumbs up">👍</span>
                                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #0f172a; border: 1px solid #334155; font-size: 13px;" onclick="insertAdminEmoji('📁')" title="Folder">📁</span>
                                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #0f172a; border: 1px solid #334155; font-size: 13px;" onclick="insertAdminEmoji('⚖️')" title="Legal Scales">⚖️</span>
                                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #0f172a; border: 1px solid #334155; font-size: 13px;" onclick="insertAdminEmoji('💼')" title="Case Briefcase">💼</span>
                                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #0f172a; border: 1px solid #334155; font-size: 13px;" onclick="insertAdminEmoji('✅')" title="Approved">✅</span>
                                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #0f172a; border: 1px solid #334155; font-size: 13px;" onclick="insertAdminEmoji('🔒')" title="Encrypted">🔒</span>
                                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #0f172a; border: 1px solid #334155; font-size: 13px;" onclick="insertAdminEmoji('📝')" title="Document">📝</span>
                                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #0f172a; border: 1px solid #334155; font-size: 13px;" onclick="insertAdminEmoji('🤝')" title="Agreement">🤝</span>
                                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #0f172a; border: 1px solid #334155; font-size: 13px;" onclick="insertAdminEmoji('📎')" title="Attachment">📎</span>
                                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #0f172a; border: 1px solid #334155; font-size: 13px;" onclick="insertAdminEmoji('💡')" title="Idea">💡</span>
                                <span class="badge badge-dark px-2 py-1" style="cursor: pointer; background: #0f172a; border: 1px solid #334155; font-size: 13px;" onclick="insertAdminEmoji('❓')" title="Question">❓</span>
                            </div>

                            <form action="{{ route('admin.conversation.send-message', $conversation->slug) }}" method="post" enctype="multipart/form-data" id="chatForm" class="d-flex align-items-center" style="gap:10px;">
                                @csrf
                                <input type="text" name="text" id="chatTaxtInput" class="form-control bg-dark text-white border-secondary flex-grow-1" placeholder="Type message and press Enter to send..." autocomplete="off" required>
                                <label for="chatFileInput" class="btn btn-outline-secondary mb-0 text-light" style="cursor:pointer;" title="Attach File">
                                    <i class="fas fa-paperclip"></i>
                                    <input type="file" hidden name="file" id="chatFileInput" onchange="previewAdminAttachment(this)">
                                </label>
                                <button type="submit" id="adminSendBtn" class="btn btn-warning font-weight-bold text-dark px-4"><i class="fas fa-paper-plane mr-1"></i> {{ __('Send') }}</button>
                            </form>
                            <div id="adminFilePreviewBox" class="small text-warning mt-1 font-weight-bold d-none">
                                <i class="fas fa-file-alt mr-1"></i> <span id="adminFilePreviewName"></span>
                                <a href="javascript:void(0)" onclick="clearAdminAttachment()" class="text-danger ml-2"><i class="fas fa-times-circle"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Initiate Direct Voice / Video Call -->
    <div class="modal fade" id="initiateCallModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title font-weight-bold text-warning">
                        <i class="fas fa-phone-alt mr-2"></i> {{ __('Start Voice or Video Call') }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="text-muted small mb-4">
                        {{ __('Choose direct calling mode to begin live peer-to-peer call with ') }} <strong class="text-white">{{ $title }}</strong>:
                    </p>
                    <div class="row">
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-info btn-block py-3 font-weight-bold" onclick="launchCall('audio')">
                                <i class="fas fa-phone-alt fa-2x d-block mb-2"></i>
                                {{ __('Voice Call') }}
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-warning btn-block py-3 font-weight-bold" onclick="launchCall('video')">
                                <i class="fas fa-video fa-2x d-block mb-2"></i>
                                {{ __('HD Video Call') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    @include('backend.pages.appointments.internal-assets.js.delete-warning')
    @include('backend.layouts.message')
    <script src="{{ asset('backend/assets/js/file-name-view.js') }}"></script>
    <script>
        function insertAdminEmoji(emoji) {
            const input = document.getElementById('chatTaxtInput');
            if (!input) return;
            const start = input.selectionStart || input.value.length;
            const end = input.selectionEnd || input.value.length;
            input.value = input.value.substring(0, start) + emoji + input.value.substring(end);
            input.focus();
            input.selectionStart = input.selectionEnd = start + emoji.length;
        }

        function previewAdminAttachment(input) {
            if (input.files && input.files[0]) {
                document.getElementById('adminFilePreviewName').innerText = input.files[0].name;
                document.getElementById('adminFilePreviewBox').classList.remove('d-none');
            }
        }

        function clearAdminAttachment() {
            const fileInput = document.getElementById('chatFileInput');
            if (fileInput) fileInput.value = '';
            document.getElementById('adminFilePreviewBox').classList.add('d-none');
        }

        function launchCall(type) {
            $('#initiateCallModal').modal('hide');
            if (typeof window.initiateWebRtcCall === 'function') {
                window.initiateWebRtcCall({{ $client ? $client->id : 0 }}, @json($title), type, @json($conversation->slug));
            } else {
                alert('Calling engine is initializing. Please try again in a moment.');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('chatTaxtInput');
            const form = document.getElementById('chatForm');
            const box = document.getElementById('chatBox');

            if (box) {
                box.scrollTop = box.scrollHeight;
            }



            let isAdminSending = false;
            let lastAdminSendTs = 0;

            function submitAdminForm() {
                const now = Date.now();
                if (isAdminSending || (now - lastAdminSendTs < 1200)) return false;
                if (!input.value.trim() && !document.getElementById('chatFileInput').files.length) return false;

                isAdminSending = true;
                lastAdminSendTs = now;
                const btn = document.getElementById('adminSendBtn');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>';
                }
                form.submit();
            }

            if (form) {
                form.addEventListener('submit', function(e) {
                    const now = Date.now();
                    if (isAdminSending || (now - lastAdminSendTs < 1200)) {
                        e.preventDefault();
                        return false;
                    }
                    isAdminSending = true;
                    lastAdminSendTs = now;
                    const btn = document.getElementById('adminSendBtn');
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>';
                    }
                });
            }

            if (input) {
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        submitAdminForm();
                    }
                });
            }

            // Live State Polling (Every 3.5s)
            let lastAdminMsgId = 0;
            const existingItems = box ? box.querySelectorAll('.msg-item') : [];
            if (existingItems.length > 0) {
                lastAdminMsgId = parseInt(existingItems[existingItems.length - 1].getAttribute('data-msg-id')) || 0;
            }

            setInterval(function pollAdminLive() {
                fetch("{{ route('admin.conversation.poll-live', $conversation->slug) }}?last_id=" + lastAdminMsgId, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(res => {
                    // 1. Live Typing Preview Sneak-Peek
                    const typingBox = document.getElementById('adminLiveTypingBox');
                    const typingText = document.getElementById('adminTypingDraftText');
                    if (res.client_typing && res.client_typing.draft) {
                        typingText.innerText = res.client_typing.draft;
                        typingBox.classList.remove('d-none');
                    } else {
                        typingBox.classList.add('d-none');
                    }

                    // 2. Append new incoming messages
                    if (res.messages && res.messages.length > 0) {
                        const placeholder = document.getElementById('chatEmptyPlaceholder');
                        if (placeholder) placeholder.remove();

                        res.messages.forEach(msg => {
                            if (msg.id > lastAdminMsgId) lastAdminMsgId = msg.id;
                            if (document.querySelector(`[data-msg-id="${msg.id}"]`)) return;

                            const isMine = msg.is_mine;
                            const itemDiv = document.createElement('div');
                            itemDiv.className = isMine ? 'd-flex justify-content-end mb-3 msg-item' : 'd-flex justify-content-start mb-3 msg-item';
                            itemDiv.setAttribute('data-msg-id', msg.id);

                            let fileHtml = msg.file ? `<p class="mb-1"><a href="${msg.file}" class="text-warning font-weight-bold" target="_blank"><i class="fas fa-paperclip mr-1"></i> ${msg.file_name || 'Attachment'}</a></p>` : '';
                            let textHtml = msg.text ? `<div>${escapeAdminHtml(msg.text).replace(/\\n/g, '<br>')}</div>` : '';

                            if (isMine) {
                                itemDiv.innerHTML = `
                                    <div class="msg_cotainer_send">
                                        ${fileHtml}
                                        ${textHtml}
                                        <span class="msg_time_send">${msg.time}</span>
                                    </div>
                                    <div class="img_cont_msg ml-2">
                                        <img src="{{ asset('backend/assets/img/profile/other.png') }}" class="rounded-circle user_img_msg" title="Admin Counsel">
                                    </div>
                                `;
                            } else {
                                itemDiv.innerHTML = `
                                    <div class="img_cont_msg mr-2">
                                        <img src="{{ asset('backend/assets/img/profile/other.png') }}" class="rounded-circle user_img_msg" title="${msg.user_name}">
                                    </div>
                                    <div class="msg_cotainer">
                                        <div class="font-weight-bold text-warning small mb-1">${msg.user_name}</div>
                                        ${fileHtml}
                                        ${textHtml}
                                        <span class="msg_time">${msg.time}</span>
                                    </div>
                                `;
                            }
                            box.appendChild(itemDiv);
                            box.scrollTop = box.scrollHeight;
                        });
                    }
                })
                .catch(() => {});
            }, 3500);

            function escapeAdminHtml(str) {
                return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }
        });
    </script>
@endsection
