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
        height: calc(100vh - 170px);
        min-height: 580px;
    }
    .chat-portal-header {
        background: #161a23;
        border-bottom: 1px solid #28303f;
        padding: 14px 20px;
        flex-shrink: 0;
    }
    .chatwoot-docked-container {
        position: relative !important;
        flex: 1;
        width: 100% !important;
        height: 100% !important;
        overflow: hidden !important;
        background: #000;
    }
    .chatwoot-docked-container iframe {
        width: 100% !important;
        height: 100% !important;
        min-height: 100% !important;
        border: none !important;
        display: block !important;
        background: #000 !important;
    }
    .woot--bubble-holder, .woot-widget-bubble {
        display: none !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }
    .woot-widget-holder {
        display: none !important;
    }
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
    
    /* Light Mode Overrides */
    body.light-mode .chat-portal-card, html.light-mode .chat-portal-card {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .chat-portal-header, html.light-mode .chat-portal-header {
        background: #f8fafc !important;
        border-bottom-color: #e2e8f0 !important;
    }
    body.light-mode .chatwoot-docked-container, html.light-mode .chatwoot-docked-container {
        background: #ffffff !important;
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
        .chat-portal-card {
            height: calc(100dvh - 140px);
            border-radius: 0 !important;
            min-height: 0;
        }
        .chat-bubble { max-width: 92%; }
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
    $clientPhone    = $u->phone ?? '';
    $avatarUrl      = $u->profile_photo_url ?? '';
    if ($avatarUrl && !str_starts_with($avatarUrl, 'http')) { $avatarUrl = url($avatarUrl); }
    $cwId           = 'client_'.$u->id;
    $cwHmac         = $hmacHash ?? (!empty($chatSettings['hmac_key']) ? hash_hmac('sha256', $cwId, $chatSettings['hmac_key']) : '');
    $counselName    = ($counsel->name ?? null) ?: 'Gary Livingston, Senior CPA & Legal Counsel';

    $directChatwootUrl = $chatwootBase . '/widget?website_token=' . urlencode($chatwootToken) . '&locale=en';
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
            @if($provider==='chatwoot')
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

    {{-- CHATWOOT DIRECT IFRAME EMBED (RELIABLE & INSTANT) --}}
    @if($provider==='chatwoot')
      @if(!empty($chatwootToken))
        <div class="chatwoot-docked-container" id="chatwootMountFrame">
          <iframe id="chatwootIframe"
                  src="{{ $directChatwootUrl }}"
                  allow="camera;microphone;autoplay;encrypted-media;fullscreen"
                  title="Live Case Support"></iframe>
        </div>
        
        {{-- SDK Identity Sync Script in Background --}}
        <script>
        (function(d,t) {
          var BASE = @json($chatwootBase);
          var TOK  = @json($chatwootToken);
          var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
          g.src=BASE+'/packs/js/sdk.js'; g.async=true; g.defer=true;
          s.parentNode.insertBefore(g,s);
          g.onload=function(){
            if(window.chatwootSDK){
              window.chatwootSDK.run({websiteToken: TOK, baseUrl: BASE});
            }
          };
        })(document,'script');

        window.addEventListener('chatwoot:ready', function() {
          if(!window.$chatwoot) return;
          var ud = {
            name: @json($clientName),
            email: @json($clientEmail),
            phone_number: @json($clientPhone),
            avatar_url: @json($avatarUrl)
          };
          @if(!empty($cwHmac))
            ud.identifier_hash = @json($cwHmac);
          @endif
          window.$chatwoot.setUser(@json($cwId), ud);
          window.$chatwoot.setCustomAttributes({
            client_id: '{{ $u->id }}',
            portal: '{{ config("app.name", "Your CPA Expert") }}'
          });
        });
        </script>
      @else
        <div class="flex-fill d-flex align-items-center justify-content-center p-5 text-center">
          <div>
            <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
            <h5 class="text-warning">{{ __('Chatwoot Not Configured') }}</h5>
            <p class="text-muted small">{{ __('Go to Admin → Settings → Live Chat to enter your Chatwoot Website Token.') }}</p>
          </div>
        </div>
      @endif

    {{-- TAWK.TO --}}
    @elseif(in_array($provider,['tawkto','tawk']))
      @php
        $tk=trim(preg_replace(['/^.*tawk\.to\/chat\//i','/^.*embed\.tawk\.to\//i'],['',$tawktoId],''), " \t\n\r;'\"/");
        $tParts=explode('/',$tk); $tProp=$tParts[0]??''; $tHash=$tParts[1]??'default';
      @endphp
      @if($tProp)
        <div class="flex-fill" style="position:relative;">
          <iframe src="https://tawk.to/chat/{{ $tProp }}/{{ $tHash }}"
                  style="width:100%;height:100%;min-height:580px;border:none;display:block;background:#000;"
                  allow="camera;microphone;autoplay;encrypted-media;" title="{{ __('Live Support') }}"></iframe>
        </div>
      @else
        <div class="flex-fill d-flex align-items-center justify-content-center p-5 text-center">
          <div><i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i><h5 class="text-warning">{{ __('Tawk.to Not Configured') }}</h5></div>
        </div>
      @endif

    {{-- NATIVE FALLBACK --}}
    @else
      <div class="chat-messages-body" id="chatMessagesStream">
        @forelse($messages??[] as $msg)
          @php $mine=($msg->user_id===Auth::id()); @endphp
          <div class="chat-bubble {{ $mine?'chat-bubble-out':'chat-bubble-in' }}" id="msg-item-{{ $msg->id }}" data-msg-id="{{ $msg->id }}">
            @if(!$mine)<div class="font-weight-bold text-warning small mb-1" style="font-size:11px;">{{ $msg->user->name??'Assigned Counsel' }}</div>@endif
            @if(!empty($msg->text))<div>{{ $msg->text }}</div>@endif
            @if(!empty($msg->file))
              <div class="chat-attachment-box"><i class="fas fa-file-alt text-warning"></i><a href="{{ asset($msg->file) }}" target="_blank" class="text-white small font-weight-bold text-decoration-none">{{ $msg->file_name??__('Attachment') }}</a></div>
            @endif
            <div class="chat-bubble-time"><span>{{ $msg->created_at->format('h:i A') }}</span>@if($mine)<i class="fas fa-check-double text-warning ml-1" style="font-size:9px;"></i>@endif</div>
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
          <label class="btn btn-portal-secondary mb-0" style="cursor:pointer;padding:10px 14px;" title="{{ __('Attach File') }}">
            <i class="fas fa-paperclip"></i>
            <input type="file" name="attachment" class="d-none" onchange="this.form.submit()">
          </label>
          <button type="submit" class="btn btn-gold"><i class="fas fa-paper-plane mr-1"></i> {{ __('Send') }}</button>
        </form>
      </div>
    @endif

  </div>
</div>
@endsection
