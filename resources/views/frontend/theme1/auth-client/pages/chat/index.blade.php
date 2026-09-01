@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | Live Support & Chat')

@section('page-css')
<style>
    /* IFW-REPLICA FULL-PAGE CHAT SYSTEM */
    .chat-portal-card {
        background: #11151e;
        border: 1px solid #28303f;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        min-height: 560px;
        position: relative;
    }
    .chat-portal-header {
        background: #161a23;
        border-bottom: 1px solid #28303f;
        padding: 14px 20px;
        flex-shrink: 0;
        z-index: 20;
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
    .chat-live-cta {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 50px 30px;
        text-align: center;
        background: radial-gradient(ellipse at center, rgba(254,204,86,0.04) 0%, transparent 70%);
    }
    .chat-pulsing-ring {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(254,204,86,0.12);
        border: 2px solid #fecc56;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 38px;
        color: #fecc56;
        margin: 0 auto 24px;
        position: relative;
        animation: pulseGlow 2s ease-in-out infinite;
    }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(254,204,86,0.4); }
        50% { box-shadow: 0 0 0 20px rgba(254,204,86,0); }
    }
    .status-live-badge {
        background: rgba(34,197,94,0.15);
        color: #4ade80;
        border: 1px solid rgba(34,197,94,0.3);
        font-weight: 700;
        font-size: 11px;
        padding: 5px 14px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 20px;
    }
    .status-live-badge::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #4ade80;
        animation: blink 1.2s ease-in-out infinite;
    }
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.25; }
    }
    .chat-feature-row {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 30px;
        max-width: 560px;
    }
    .chat-feature-item {
        background: #161a23;
        border: 1px solid #28303f;
        border-radius: 10px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12.5px;
        color: #94a3b8;
        flex: 1;
        min-width: 160px;
    }
    .chat-feature-item i {
        color: #fecc56;
        font-size: 16px;
    }

    /* Native Fallback Messages */
    .chat-messages-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #0a0c10;
        display: flex;
        flex-direction: column;
        gap: 14px;
        scroll-behavior: smooth;
    }
    .chat-bubble {
        max-width: 75%;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 13.5px;
        line-height: 1.45;
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
        background: linear-gradient(135deg, #fecc56, #f0a500);
        color: #000 !important;
        border-bottom-right-radius: 2px;
        font-weight: 500;
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
        color: #5a4200;
    }
    .chat-footer-native {
        background: #161a23;
        border-top: 1px solid #28303f;
        padding: 14px 20px;
        flex-shrink: 0;
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

    /* Light Mode */
    body.light-mode .chat-portal-card, html.light-mode .chat-portal-card {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .chat-portal-header, html.light-mode .chat-portal-header {
        background: #f8fafc !important;
        border-bottom-color: #e2e8f0 !important;
    }
    body.light-mode .chat-live-cta, html.light-mode .chat-live-cta {
        background: radial-gradient(ellipse at center, rgba(245,158,11,0.06) 0%, transparent 70%) !important;
    }
    body.light-mode .chat-feature-item, html.light-mode .chat-feature-item {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
        color: #475569 !important;
    }
    body.light-mode .chat-messages-body, html.light-mode .chat-messages-body {
        background: #f8fafc !important;
    }
    body.light-mode .chat-bubble-in, html.light-mode .chat-bubble-in {
        background: #ffffff !important;
        color: #0f172a !important;
        border-color: #cbd5e1 !important;
    }
    body.light-mode .chat-footer-native, html.light-mode .chat-footer-native {
        background: #ffffff !important;
        border-top-color: #e2e8f0 !important;
    }
    body.light-mode .chat-input-control, html.light-mode .chat-input-control {
        background: #f1f5f9 !important;
        color: #0f172a !important;
        border-color: #cbd5e1 !important;
    }
    @media (max-width: 768px) {
        .chat-feature-item { min-width: 130px; }
        .chat-pulsing-ring { width: 80px; height: 80px; font-size: 28px; }
    }
</style>
@endsection

@section('content')
@php
    $chatSettings   = $chatSettings ?? \App\Services\ChatwootService::getSettings();
    $provider       = $chatSettings['provider'] ?? 'chatwoot';
    $chatwootToken  = $chatSettings['website_token'] ?? '';
    $chatwootBase   = rtrim($chatSettings['base_url'] ?? 'https://app.chatwoot.com', '/');
    $tawktoId       = $chatSettings['tawkto_property_id'] ?? '';
    $u              = Auth::user();
    $clientName     = $u->name ?: 'Client #'.$u->id;
    $clientEmail    = $u->email;
    $counselName    = ($counsel->name ?? null) ?: 'Gary Livingston, Senior CPA & Legal Counsel';
@endphp

<div class="container-fluid px-0">
  <div class="chat-portal-card">

    {{-- HEADER --}}
    <div class="chat-portal-header d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mr-3 flex-shrink-0"
             style="width:44px;height:44px;background:rgba(254,204,86,0.15);color:#fecc56;font-size:18px;font-weight:bold;border:2px solid #fecc56;">
          {{ strtoupper(substr($counselName, 0, 1)) }}
        </div>
        <div>
          <h6 class="font-weight-bold text-white mb-0" style="font-size:15px;">{{ $counselName }}</h6>
          <div class="d-flex align-items-center small flex-wrap" style="gap:8px;">
            <span class="text-success font-weight-bold" style="font-size:11px;"><i class="fas fa-circle mr-1" style="font-size:8px;"></i>{{ __('Counsel Online') }}</span>
            <span class="text-muted">&bull;</span>
            <span class="text-warning font-weight-bold" style="font-size:11px;">CLI-{{ sprintf('%05d', $u->id) }}</span>
            @if($provider==='chatwoot' && !empty($chatwootToken))
              <span class="text-muted">&bull;</span>
              <span class="text-muted" style="font-size:11px;"><i class="fas fa-shield-alt text-success mr-1"></i>Chatwoot Live</span>
            @endif
          </div>
        </div>
      </div>
      <div class="d-flex align-items-center" style="gap:8px;">
        <span class="badge d-none d-sm-inline-block px-3 py-2"
              style="background:rgba(34,197,94,0.15);color:#4ade80;border:1px solid rgba(34,197,94,0.3);font-size:11px;">
          <i class="fas fa-lock mr-1"></i>{{ __('256-Bit Encrypted') }}
        </span>
        <a href="{{ route('client.dashboard') }}" class="btn btn-sm btn-outline-secondary text-light px-3 font-weight-bold" style="border-color:#3b4252;">
          <i class="fas fa-arrow-left mr-1"></i>{{ __('Dashboard') }}
        </a>
      </div>
    </div>

    {{-- CHATWOOT: Open floating bubble --}}
    @if($provider==='chatwoot' && !empty($chatwootToken))
      <div class="chat-live-cta">
        <div class="chat-pulsing-ring">
          <i class="fas fa-headset"></i>
        </div>
        <div class="status-live-badge">
          {{ __('Live Support Activated') }}
        </div>
        <h4 class="font-weight-bold text-white mb-2" style="font-size: 1.4rem;">{{ __('Your Secure Live Case Line is Ready') }}</h4>
        <p class="text-muted mb-4" style="max-width: 440px; line-height: 1.65; font-size: 13.5px;">
          {{ __('Your dedicated legal counsel is available to discuss your case, review documents, and provide immediate secure legal advice through our Chatwoot-encrypted messaging system.') }}
        </p>

        <button id="openChatwootBtn" type="button" class="btn-gold px-5 py-3" style="font-size:15px; border-radius: 10px;" onclick="launchChatwootWidget()">
          <i class="fas fa-comments mr-2"></i>{{ __('Launch Live Counsel Chat') }}
        </button>

        <div class="chat-feature-row">
          <div class="chat-feature-item">
            <i class="fas fa-clock"></i>
            <div>
              <strong class="text-white d-block" style="font-size: 12.5px;">{{ __('24/7 Case Line') }}</strong>
              <span style="font-size: 11px;">{{ __('Always available') }}</span>
            </div>
          </div>
          <div class="chat-feature-item">
            <i class="fas fa-shield-alt"></i>
            <div>
              <strong class="text-white d-block" style="font-size: 12.5px;">{{ __('256-Bit Encrypted') }}</strong>
              <span style="font-size: 11px;">{{ __('End-to-end secure') }}</span>
            </div>
          </div>
          <div class="chat-feature-item">
            <i class="fas fa-file-contract"></i>
            <div>
              <strong class="text-white d-block" style="font-size: 12.5px;">{{ __('Case-Linked') }}</strong>
              <span style="font-size: 11px;">{{ __('Full case context') }}</span>
            </div>
          </div>
        </div>
      </div>

      <script>
        function launchChatwootWidget() {
          // Make sure SDK is loaded
          if (window.$chatwoot) {
            window.$chatwoot.toggle('open');
          } else {
            // SDK not yet ready — wait for ready event then open
            window.addEventListener('chatwoot:ready', function() {
              if (window.$chatwoot) {
                window.$chatwoot.toggle('open');
              }
            }, { once: true });
          }
        }

        // Auto-open the chat widget when this page loads
        document.addEventListener('DOMContentLoaded', function() {
          // Give the SDK a moment to initialize if not yet ready
          var attempts = 0;
          var autoOpen = setInterval(function() {
            attempts++;
            if (window.$chatwoot) {
              window.$chatwoot.toggle('open');
              clearInterval(autoOpen);
            }
            if (attempts >= 25) {
              clearInterval(autoOpen); // give up after 5s
            }
          }, 200);
        });
      </script>

    {{-- TAWK.TO --}}
    @elseif(in_array($provider, ['tawkto','tawk']))
      @php
        $tk = trim(preg_replace(['/^.*tawk\.to\/chat\//i','/^.*embed\.tawk\.to\//i'],['',$tawktoId],''), " \t\n\r;'\"/");
        $tParts = explode('/', $tk); $tProp = $tParts[0] ?? ''; $tHash = $tParts[1] ?? 'default';
      @endphp
      @if($tProp)
        <div class="flex-fill" style="position:relative;">
          <iframe src="https://tawk.to/chat/{{ $tProp }}/{{ $tHash }}"
                  style="width:100%;height:100%;min-height:520px;border:none;display:block;"
                  allow="camera;microphone;autoplay;encrypted-media;" title="{{ __('Live Support') }}"></iframe>
        </div>
      @else
        <div class="chat-live-cta">
          <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
          <h5 class="text-warning">{{ __('Tawk.to Not Configured') }}</h5>
        </div>
      @endif

    {{-- NATIVE FALLBACK --}}
    @else
      <div class="chat-messages-body" id="chatMessagesStream">
        @forelse($messages??[] as $msg)
          @php $mine=($msg->user_id===Auth::id()); @endphp
          <div class="chat-bubble {{ $mine?'chat-bubble-out':'chat-bubble-in' }}">
            @if(!$mine)<div class="font-weight-bold text-warning small mb-1" style="font-size:11px;">{{ $msg->user->name??'Assigned Counsel' }}</div>@endif
            @if(!empty($msg->text))<div>{{ $msg->text }}</div>@endif
            @if(!empty($msg->file))
              <div class="d-flex align-items-center mt-1">
                <i class="fas fa-file-alt text-warning mr-2"></i>
                <a href="{{ asset($msg->file) }}" target="_blank" class="text-white small font-weight-bold text-decoration-none">{{ $msg->file_name??__('Attachment') }}</a>
              </div>
            @endif
            <div class="chat-bubble-time">
              <span>{{ $msg->created_at->format('h:i A') }}</span>
              @if($mine)<i class="fas fa-check-double text-warning ml-1" style="font-size:9px;"></i>@endif
            </div>
          </div>
        @empty
          <div class="text-center py-5 my-auto text-muted">
            <i class="fas fa-comments fa-3x text-secondary mb-3 d-block"></i>
            <h5 class="text-white font-weight-bold">{{ __('Encrypted Case Channel') }}</h5>
            <p class="small text-muted">{{ __('Type below to send an instant secure message to your assigned legal counsel.') }}</p>
          </div>
        @endforelse
      </div>

      <div class="chat-footer-native">
        <form action="{{ route('client.conversation.store') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center" style="gap:10px;">
          @csrf
          <input type="text" name="message" class="form-control chat-input-control flex-grow-1" placeholder="{{ __('Type your message here...') }}" required autocomplete="off">
          <label class="btn btn-sm btn-outline-secondary text-light mb-0" style="cursor:pointer;padding:10px 14px;border-color:#374151;" title="{{ __('Attach File') }}">
            <i class="fas fa-paperclip"></i>
            <input type="file" name="attachment" class="d-none" onchange="this.form.submit()">
          </label>
          <button type="submit" class="btn-gold"><i class="fas fa-paper-plane mr-1"></i> {{ __('Send') }}</button>
        </form>
      </div>
    @endif

  </div>
</div>
@endsection
