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
                        <div class="card-header d-flex justify-content-between align-items-center py-3 border-secondary">
                            <div>
                                <h5 class="card-title text-white font-weight-bold mb-0">
                                    <i class="fas fa-comments text-warning mr-2"></i>{{ $title }}
                                </h5>
                                <small class="text-muted">Direct secure case communication channel</small>
                            </div>
                            <div>
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
                                        <div class="d-flex justify-content-end mb-3">
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
                                        <div class="d-flex justify-content-start mb-3">
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
                                    <div class="text-center py-5 text-muted">
                                        <i class="fas fa-comments fa-3x mb-3 text-secondary d-block"></i>
                                        <h5 class="text-white font-weight-bold">No Messages in this Thread Yet</h5>
                                        <p class="small text-muted">Type below to begin case representation conversation with {{ $title }}.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="card-footer bg-dark border-secondary p-3">
                            <form action="{{ route('admin.conversation.send-message', $conversation->slug) }}" method="post" enctype="multipart/form-data" id="chatForm" class="d-flex align-items-center" style="gap:10px;">
                                @csrf
                                <input type="text" name="text" id="chatTaxtInput" class="form-control bg-dark text-white border-secondary flex-grow-1" placeholder="Type message and press Enter to send..." autocomplete="off" required>
                                <label for="chatFileInput" class="btn btn-outline-secondary mb-0 text-light" style="cursor:pointer;" title="Attach File">
                                    <i class="fas fa-paperclip"></i>
                                    <input type="file" hidden name="file" id="chatFileInput" onchange="document.getElementById('showSelectedFile').innerText = this.files[0]?.name || ''">
                                </label>
                                <button type="submit" class="btn btn-warning font-weight-bold text-dark px-4"><i class="fas fa-paper-plane mr-1"></i> {{ __('Send') }}</button>
                            </form>
                            <small class="text-warning d-block mt-1 font-weight-bold" id="showSelectedFile"></small>
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
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('chatTaxtInput');
            const form = document.getElementById('chatForm');
            const box = document.getElementById('chatBox');

            if (box) {
                box.scrollTop = box.scrollHeight;
            }

            let isAdminSending = false;
            form.addEventListener('submit', function(e) {
                if (isAdminSending) {
                    e.preventDefault();
                    return false;
                }
                isAdminSending = true;
                const btn = form.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Sending...';
                }
            });

            if (input && form) {
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        if (!isAdminSending && input.value.trim() !== '') {
                            form.requestSubmit ? form.requestSubmit() : form.submit();
                        }
                    }
                });
            }
        });
    </script>
@endsection
