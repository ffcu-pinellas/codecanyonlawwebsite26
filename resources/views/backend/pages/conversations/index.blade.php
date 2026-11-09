@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert').' | '.$title)

@section('page-css')
<style>
    .chat-nav-tabs .nav-link {
        color: #94a3b8;
        background: #1e293b;
        border: 1px solid #334155;
        border-bottom: none;
        font-weight: 600;
        padding: 10px 20px;
        margin-right: 4px;
        border-radius: 6px 6px 0 0;
    }
    .chat-nav-tabs .nav-link.active {
        color: #ffffff !important;
        background: #0f172a !important;
        border-color: #f59e0b #334155 #0f172a #334155;
        border-top: 3px solid #f59e0b;
    }
    .chat-hero-card {
        background: #0f172a;
        border: 1px solid #334155;
        border-radius: 8px;
        padding: 24px;
    }
    .feature-pill {
        display: inline-flex;
        align-items: center;
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.25);
        color: #f59e0b;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        margin-right: 8px;
        margin-bottom: 8px;
    }
    @media (max-width: 768px) {
        .chat-nav-tabs .nav-link {
            padding: 8px 12px;
            font-size: 13px;
        }
    }
</style>
@endsection

@section('content')
<div id="wrapper-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark">
                    <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                    <span class="breadcrumb-item active">{{ __($title) }}</span>
                    <span class="breadcrumb-info" id="time"></span>
                </nav>
            </div>
        </div>

        @php
            $provider = $chatSettings['provider'] ?? 'chatwoot';
            $isInternal = ($provider === 'internal');
        @endphp

        <!-- Unified Navigation Tabs: Clients, Staff, and Live Support -->
        <ul class="nav nav-tabs chat-nav-tabs border-bottom-0" id="chatTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="clients-tab" data-toggle="tab" href="#tab-clients" role="tab" aria-controls="tab-clients" aria-selected="true">
                    <i class="fas fa-users text-warning mr-1"></i> {{ __('Clients & Accounts') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="staff-tab" data-toggle="tab" href="#tab-staff" role="tab" aria-controls="tab-staff" aria-selected="false">
                    <i class="fas fa-user-tie text-warning mr-1"></i> {{ __('Staff & Legal Practitioners') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="chatwoot-tab" data-toggle="tab" href="#tab-chatwoot" role="tab" aria-controls="tab-chatwoot" aria-selected="false">
                    <i class="fas fa-headset text-warning mr-1"></i> {{ __('Live Support (Chatwoot)') }}
                </a>
            </li>
        </ul>

        <div class="tab-content" id="chatTabsContent">
            <!-- TAB 1: Clients & Cases Communication Hub -->
            <div class="tab-pane fade show active" id="tab-clients" role="tabpanel" aria-labelledby="clients-tab">
                <div class="card card-dark bg-dark border-secondary">
                    <div class="card-header d-block">
                        <div class="row align-items-center">
                            <div class="col-md-6 col-sm-12">
                                <h6 class="card-title text-white font-weight-bold mb-0">
                                    <i class="fas fa-comments text-warning mr-2"></i> {{ __('Client Channels, Encrypted Chat & Direct Calling') }}
                                </h6>
                            </div>
                            <div class="col-md-6 col-sm-12 text-md-right mt-2 mt-md-0 d-flex justify-content-md-end align-items-center flex-wrap" style="gap: 10px;">
                                <button type="button" class="btn btn-warning font-weight-bold text-dark btn-sm px-3 shadow-sm" data-toggle="modal" data-target="#newChatModal">
                                    <i class="fas fa-plus-circle mr-1"></i> {{ __('Start New Client Chat') }}
                                </button>
                                <span class="badge badge-warning px-3 py-2 font-weight-bold">
                                    <i class="fas fa-lock mr-1"></i> {{ __('256-Bit Cryptographic Security') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="slider" class="table bapric_table table-striped table-bordered miw-500 text-white mb-0" cellspacing="0" width="100%">
                                <thead style="background: #161a23;">
                                <tr>
                                    <th scope="col" width="5%" class="text-center">{{ __('Serial') }}</th>
                                    <th scope="col" width="8%" class="text-center">{{ __('Avatar') }}</th>
                                    <th scope="col" width="22%">{{ __('Client / Account') }}</th>
                                    <th scope="col">{{ __('Latest Message Preview') }}</th>
                                    <th scope="col" width="14%" class="text-center">{{ __('Timestamp') }}</th>
                                    <th scope="col" width="16%" class="text-center">{{ __('Quick Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                {!! $conversations !!}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: Staff & Practitioners Directory & Quick Calling -->
            <div class="tab-pane fade" id="tab-staff" role="tabpanel" aria-labelledby="staff-tab">
                <div class="card card-dark bg-dark border-secondary">
                    <div class="card-header d-flex justify-content-between align-items-center py-3 border-secondary flex-wrap" style="gap: 10px;">
                        <div>
                            <h6 class="card-title text-white font-weight-bold mb-0">
                                <i class="fas fa-user-tie text-warning mr-2"></i> {{ __('Staff & Attorney Communication Directory') }}
                            </h6>
                            <small class="text-muted">{{ __('Direct internal communications, attendance monitoring, and live voice/video calls with practice staff.') }}</small>
                        </div>
                        <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                            <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary btn-sm text-light">
                                <i class="fas fa-users-cog mr-1"></i> {{ __('Full Staff Directory') }}
                            </a>
                            <span class="badge badge-success px-3 py-2 font-weight-bold">
                                <i class="fas fa-shield-alt mr-1"></i> {{ __('Practice Encryption') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-white mb-0 miw-500" cellspacing="0" width="100%">
                                <thead style="background: #161a23;">
                                    <tr>
                                        <th scope="col" width="5%" class="text-center">{{ __('Serial') }}</th>
                                        <th scope="col" width="8%" class="text-center">{{ __('Avatar') }}</th>
                                        <th scope="col" width="24%">{{ __('Practitioner / Role') }}</th>
                                        <th scope="col" width="15%" class="text-center">{{ __('Shift Attendance') }}</th>
                                        <th scope="col">{{ __('Recent Internal Message') }}</th>
                                        <th scope="col" width="18%" class="text-center">{{ __('Direct Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($staffMembers ?? [] as $idx => $s)
                                        @php
                                            $sAvatar = !empty($s->profile_photo_url) ? $s->profile_photo_url : asset('backend/assets/img/profile/other.png');
                                            $sRole = $s->staffDetail ? ($s->staffDetail->designation ?: 'Staff Specialist') : 'Staff Specialist';
                                            $chatUrl = route('admin.staff.messages', $s->id);
                                            $lastMsgText = $s->last_msg ? htmlspecialchars($s->last_msg->message) : '<em class="text-muted">No messages exchanged yet</em>';
                                            $lastMsgTime = $s->last_msg ? $s->last_msg->created_at->format('M d, h:i A') : '—';
                                            $unreadCount = $s->unread_count ?? 0;
                                        @endphp
                                        <tr style="cursor: pointer;" onclick="if (!event.target.closest('a') && !event.target.closest('button')) { window.location.href='{{ $chatUrl }}'; }">
                                            <td class="align-middle text-center">{{ $idx + 1 }}</td>
                                            <td class="align-middle text-center">
                                                <img src="{{ $sAvatar }}" alt="" class="rounded-circle" style="width: 42px; height: 42px; object-fit: cover; border: 2px solid {{ $s->is_clocked_in ? '#22c55e' : '#64748b' }};" onerror="this.onerror=null;this.src='{{ asset('backend/assets/img/profile/other.png') }}';">
                                            </td>
                                            <td class="align-middle">
                                                <div class="font-weight-bold text-white d-flex align-items-center">
                                                    {{ $s->name }}
                                                    @if($unreadCount > 0)
                                                        <span class="badge badge-warning ml-2 font-weight-bold">{{ $unreadCount }} Unread</span>
                                                    @endif
                                                </div>
                                                <small class="text-muted d-block">{{ $sRole }} &bull; {{ $s->email }}</small>
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($s->is_clocked_in)
                                                    <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 11px;">
                                                        <i class="fas fa-circle mr-1" style="font-size: 8px;"></i> {{ __('Clocked In') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary px-2 py-1 font-weight-bold" style="font-size: 11px;">
                                                        <i class="far fa-circle mr-1" style="font-size: 8px;"></i> {{ __('Off Shift') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ $chatUrl }}" class="text-decoration-none text-light d-block">
                                                    <p class="px-0 pb-0 my-0">{!! $lastMsgText !!}</p>
                                                    <small class="text-muted">{{ $lastMsgTime }}</small>
                                                </a>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex align-items-center justify-content-center flex-wrap" style="gap: 5px;">
                                                    <a href="{{ $chatUrl }}" class="btn btn-sm btn-warning font-weight-bold text-dark px-2 shadow-sm" title="Open Staff Chat">
                                                        <i class="fas fa-comment-dots mr-1"></i> {{ __('Chat') }}
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-warning px-2" onclick="event.stopPropagation(); launchDirectCall({{ $s->id }}, @json($s->name), 'audio', null)" title="Direct Voice Call">
                                                        <i class="fas fa-phone-alt"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-info px-2" onclick="event.stopPropagation(); launchDirectCall({{ $s->id }}, @json($s->name), 'video', null)" title="Direct HD Video Call">
                                                        <i class="fas fa-video"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                {{ __('No staff members found in the directory.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: Chatwoot Live Support Hub -->
            <div class="tab-pane fade" id="tab-chatwoot" role="tabpanel" aria-labelledby="chatwoot-tab">
                <div class="card card-dark bg-dark border-secondary">
                    <div class="card-body p-4">
                        @php
                            $token = $chatSettings['website_token'] ?? '';
                            $baseUrl = rtrim($chatSettings['base_url'] ?? 'https://app.chatwoot.com', '/');
                        @endphp

                        <div class="chat-hero-card mb-4">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="d-flex align-items-center mb-3">
                                        <div style="background: #1e293b; padding: 12px; border-radius: 10px; margin-right: 16px; border: 1px solid #334155;">
                                            <i class="fas fa-headset fa-2x text-warning"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-weight-bold text-white mb-1">{{ __('Real-Time Client Live Chat & Messaging') }}</h4>
                                            <p class="text-muted mb-0 small">{{ __('Connected to secure omnichannel suite with 100% conversation retention, cryptographic verification, and multi-agent support.') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap mb-2">
                                        <span class="feature-pill"><i class="fas fa-lock mr-1"></i> {{ __('HMAC Cryptographic Signatures') }}</span>
                                        <span class="feature-pill"><i class="fas fa-user-check mr-1"></i> {{ __('Deterministic Client ID (client_{id})') }}</span>
                                        <span class="feature-pill"><i class="fas fa-database mr-1"></i> {{ __('100% Multi-Session Retention') }}</span>
                                        <span class="feature-pill"><i class="fas fa-mobile-alt mr-1"></i> {{ __('Mobile & Desktop Agent App') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                                    <a href="{{ $baseUrl }}" target="_blank" class="btn btn-warning font-weight-bold text-dark px-4 py-3 shadow-sm btn-block text-uppercase" style="letter-spacing: 0.5px;">
                                        <i class="fas fa-external-link-alt mr-1"></i> {{ __('Launch Chatwoot Inbox') }}
                                    </a>
                                    <a href="{{ route('admin.settings.chat') }}" class="btn btn-outline-secondary btn-sm btn-block mt-2 text-light">
                                        <i class="fas fa-cog mr-1"></i> {{ __('Configure Chat Settings') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Chatwoot Agent Command Center -->
                        <div class="card bg-dark border-secondary">
                            <div class="card-header bg-dark border-secondary d-flex justify-content-between align-items-center py-3">
                                <span class="font-weight-bold text-white" style="font-size: 15px;">
                                    <i class="fas fa-desktop text-warning mr-2"></i> {{ __('Chatwoot Live Agent Command Center') }}
                                </span>
                                <div>
                                    <span class="badge badge-success px-3 py-2 font-weight-bold" style="font-size: 12px;">
                                        <i class="fas fa-circle mr-1" style="font-size: 8px;"></i> {{ __('Chat Engine Online') }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-4 text-center" style="background: #0f172a; min-height: 380px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(254, 204, 86, 0.1); border: 2px solid rgba(254, 204, 86, 0.3); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                    <i class="fas fa-headset fa-3x text-warning"></i>
                                </div>
                                <h4 class="text-white font-weight-bold mb-2">{{ __('Real-Time Client Inquiries & Live Chatwoot Inbox') }}</h4>
                                <p class="text-muted small mb-4" style="max-width: 580px;">
                                    {{ __('Client conversations are instantly synchronized across your dedicated agent inbox. Click below to launch the multi-agent console in a dedicated high-performance workspace.') }}
                                </p>

                                <div class="d-flex flex-wrap justify-content-center" style="gap: 12px;">
                                    <a href="{{ $baseUrl }}" target="_blank" class="btn btn-warning font-weight-bold text-dark px-4 py-3 shadow-lg text-uppercase" style="background: linear-gradient(135deg, #fecc56, #f0a500); border: none; letter-spacing: 0.5px; border-radius: 8px;">
                                        <i class="fas fa-external-link-alt mr-2"></i> {{ __('Open Live Agent Console (Chatwoot)') }}
                                    </a>
                                    <a href="{{ route('admin.settings.chat') }}" class="btn btn-outline-secondary font-weight-bold text-white px-4 py-3" style="border-radius: 8px;">
                                        <i class="fas fa-cog mr-2"></i> {{ __('Chat Settings & Credentials') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Start New Client Chat -->
<div class="modal fade" id="newChatModal" tabindex="-1" role="dialog" aria-labelledby="newChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-weight-bold text-warning" id="newChatModalLabel">
                    <i class="fas fa-comment-medical mr-2"></i> {{ __('Start New Client Conversation') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <p class="small text-muted mb-3">{{ __('Select a client to initiate a secure practice consultation channel:') }}</p>
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-white small">{{ __('Select Client Account') }}</label>
                    <select id="selectClientChat" class="form-control bg-dark text-white border-secondary">
                        <option value="">-- {{ __('Choose Client') }} --</option>
                        @if(!empty($clients))
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->name }} ({{ $c->email }}) [Ref: #CLI-{{ sprintf('%05d', $c->id) }}]
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-warning font-weight-bold text-dark px-4" onclick="proceedToClientChat()">
                        <i class="fas fa-comments mr-1"></i> {{ __('Open Direct Chat') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function proceedToClientChat() {
        var clientId = document.getElementById('selectClientChat').value;
        if (!clientId) {
            alert('Please select a client account.');
            return;
        }
        window.location.href = "{{ url('admin/conversation/start-chat') }}/" + clientId;
    }

    function launchDirectCall(userId, userName, callType, conversationSlug) {
        if (typeof window.initiateWebRtcCall === 'function') {
            window.initiateWebRtcCall(userId, userName, callType, conversationSlug || null);
            return;
        }
        let attempts = 0;
        const checkInterval = setInterval(function() {
            attempts++;
            if (typeof window.initiateWebRtcCall === 'function') {
                clearInterval(checkInterval);
                window.initiateWebRtcCall(userId, userName, callType, conversationSlug || null);
            } else if (attempts >= 40) {
                clearInterval(checkInterval);
                alert('Calling system initializing. Please allow microphone permissions and try again.');
            }
        }, 150);
    }
</script>
@endsection

@section('page-script')
    @include('backend.pages.appointments.internal-assets.js.delete-warning')
    @include('backend.layouts.message')
@endsection
