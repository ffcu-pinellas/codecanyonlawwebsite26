@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    .chat-top-row {
        background: #161a23;
        border: 1px solid #28303f;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    .client-chat-card {
        background: #161a23;
        border: 1px solid #28303f;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
    .chatwoot-docked-container {
        position: relative !important;
        width: 100% !important;
        height: 680px !important;
        min-height: 600px !important;
        background: #0e1117;
        overflow: hidden !important;
    }

    /* Force Chatwoot Holder into 100% Full-Width / Full-Height Card Area */
    body .woot-widget-holder,
    .woot-widget-holder,
    .woot-widget-holder.has-unread-view,
    .woot-widget-holder.woot-widget-holder--expanded {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        height: 100% !important;
        min-height: 100% !important;
        max-height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        border: none !important;
        transform: none !important;
        z-index: 5 !important;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        background: transparent !important;
    }

    body .woot-widget-holder iframe,
    .woot-widget-holder iframe,
    #chatwoot_live_chat_widget {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        height: 100% !important;
        min-height: 100% !important;
        max-height: 100% !important;
        border: none !important;
        border-radius: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        display: block !important;
        background: #0e1117;
    }

    .woot--bubble-holder,
    .woot-widget-bubble {
        display: none !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Top Header Row -->
    <div class="chat-top-row d-flex justify-content-between align-items-center">
        <div>
            <h4 class="text-warning font-weight-bold mb-1" style="font-size: 1.25rem;">
                <i class="fas fa-comments mr-2"></i>{{ __('Live Chat & Counsel Support') }}
            </h4>
            <p class="text-muted small mb-0">{{ __('Send a message or request assistance. Your dedicated legal & CPA team is here to assist.') }}</p>
        </div>
        <div class="d-flex" style="gap: 8px;">
            <button type="button" onclick="launchLiveChatPopup()" class="btn btn-warning btn-sm font-weight-bold text-dark px-3" style="background: linear-gradient(135deg, #fecc56, #f0a500); border: none;">
                <i class="fas fa-comment-dots mr-1"></i> {{ __('Open Live Chat Window') }}
            </button>
            <a href="{{ route('client.dashboard') }}" class="btn btn-outline-secondary btn-sm font-weight-bold px-3 text-light">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Dashboard') }}
            </a>
        </div>
    </div>

    <!-- Live In-Page Chat Frame -->
    <div class="client-chat-card">
        <div class="card-header border-bottom py-2 px-3 d-flex justify-content-between align-items-center" style="background: #1f2533; border-color: #28303f !important;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px; background: rgba(254,204,86,0.15); color: #fecc56; border: 1px solid #fecc56;">
                    <i class="fas fa-headset"></i>
                </div>
                <div>
                    <strong class="text-white d-block small" style="line-height: 1.2;">{{ __('Case Representation & CPA Advisory Line') }}</strong>
                    <small class="text-success font-weight-bold" style="font-size: 10px;"><i class="fas fa-circle mr-1" style="font-size: 7px;"></i> {{ __('Active Channel &bull; Direct Case Line') }}</small>
                </div>
            </div>
            <div>
                <span class="badge" style="background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3); font-size: 11px;">
                    <i class="fas fa-shield-alt mr-1"></i> {{ __('256-Bit SSL Encrypted') }}
                </span>
            </div>
        </div>

        <div class="card-body p-0 chatwoot-docked-container" id="chatwoot-mount-frame">
            <!-- Loading Placeholder / Fallback Interface -->
            <div id="chatwoot-loading-ph" class="p-5 text-center text-white d-flex flex-column align-items-center justify-content-center h-100" style="min-height: 600px; background: #0e1117;">
                <div style="width: 70px; height: 70px; border-radius: 50%; background: rgba(254,204,86,0.1); border: 2px solid rgba(254,204,86,0.3); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-headset fa-2x text-warning"></i>
                </div>
                <h5 class="text-warning font-weight-bold mb-2">{{ __('Connecting to Secure Case Line...') }}</h5>
                <p class="text-muted small mb-4" style="max-width: 480px;">
                    {{ __('Synchronizing client credentials & loading conversation history with assigned Attorney & CPA.') }}
                </p>
                <button type="button" onclick="launchLiveChatPopup()" class="btn btn-warning font-weight-bold text-dark px-4 py-2" style="background: linear-gradient(135deg, #fecc56, #f0a500); border: none;">
                    <i class="fas fa-comments mr-2"></i> {{ __('Click Here to Open Live Chat') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    @php
        $chatSettings = \App\Services\ChatwootService::getSettings();
        $client = Auth::user();
        $token = $chatSettings['website_token'] ?? 'uHR3DJPM8AZ2Lpo8tDdJ5tei';
        $baseUrl = rtrim($chatSettings['base_url'] ?? 'https://app.chatwoot.com', '/');
        $identifier = 'client_' . $client->id;
        $hmacHash = !empty($chatSettings['hmac_key']) ? hash_hmac('sha256', $identifier, $chatSettings['hmac_key']) : '';
    @endphp

    window.chatwootSettings = {
        hideMessageBubble: true,
        position: 'right',
        locale: 'en',
        type: 'expanded_bubble',
        darkMode: 'dark'
    };

    function enforceChatwootDocked() {
        var mount = document.getElementById('chatwoot-mount-frame');
        var holder = document.querySelector('.woot-widget-holder');
        var bubble = document.querySelector('.woot--bubble-holder') || document.querySelector('.woot-widget-bubble');
        
        if (bubble) {
            bubble.style.setProperty('display', 'none', 'important');
        }
        
        if (mount && holder) {
            if (holder.parentElement !== mount) {
                mount.appendChild(holder);
            }
            holder.style.setProperty('position', 'absolute', 'important');
            holder.style.setProperty('top', '0', 'important');
            holder.style.setProperty('left', '0', 'important');
            holder.style.setProperty('right', '0', 'important');
            holder.style.setProperty('bottom', '0', 'important');
            holder.style.setProperty('width', '100%', 'important');
            holder.style.setProperty('max-width', '100%', 'important');
            holder.style.setProperty('min-width', '100%', 'important');
            holder.style.setProperty('height', '100%', 'important');
            holder.style.setProperty('max-height', '100%', 'important');
            holder.style.setProperty('min-height', '100%', 'important');
            holder.style.setProperty('margin', '0', 'important');
            holder.style.setProperty('border-radius', '0', 'important');
            holder.style.setProperty('box-shadow', 'none', 'important');
            holder.style.setProperty('transform', 'none', 'important');
            holder.style.setProperty('display', 'block', 'important');
            holder.style.setProperty('visibility', 'visible', 'important');
            holder.style.setProperty('opacity', '1', 'important');
            holder.style.setProperty('z-index', '5', 'important');
            holder.style.setProperty('background', '#0e1117', 'important');
            
            var iframe = holder.querySelector('iframe') || document.getElementById('chatwoot_live_chat_widget');
            if (iframe) {
                iframe.style.setProperty('position', 'absolute', 'important');
                iframe.style.setProperty('top', '0', 'important');
                iframe.style.setProperty('left', '0', 'important');
                iframe.style.setProperty('width', '100%', 'important');
                iframe.style.setProperty('height', '100%', 'important');
                iframe.style.setProperty('border', 'none', 'important');
                iframe.style.setProperty('background', '#0e1117', 'important');
            }
            
            var ph = document.getElementById('chatwoot-loading-ph');
            if (ph) {
                ph.style.display = 'none';
            }
        }
    }

    function launchLiveChatPopup() {
        if (window.$chatwoot) {
            window.$chatwoot.toggle('open');
        } else {
            alert('Live counsel chat is initializing. Please wait a moment...');
        }
    }

    (function(d,t) {
        var BASE_URL = "{{ $baseUrl }}";
        var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
        g.src=BASE_URL+"/packs/js/sdk.js";
        g.async = true;
        g.defer = true;
        s.parentNode.insertBefore(g,s);
        g.onload=function(){
            window.chatwootSDK.run({
                websiteToken: '{{ $token }}',
                baseUrl: BASE_URL
            });
        }
    })(document,"script");

    // Monitor and dock continuously
    var dockInterval = setInterval(enforceChatwootDocked, 150);
    setTimeout(function() { clearInterval(dockInterval); setInterval(enforceChatwootDocked, 1000); }, 8000);

    window.addEventListener("chatwoot:ready", function () {
        if (window.$chatwoot) {
            window.$chatwoot.setUser('{{ $identifier }}', {
                name: '{{ addslashes($client->name) }}',
                email: '{{ addslashes($client->email) }}',
                phone_number: '{{ addslashes($client->phone ?? '') }}',
                @if(!empty($hmacHash))
                identifier_hash: '{{ $hmacHash }}',
                @endif
            });

            window.$chatwoot.setCustomAttributes({
                client_id: '{{ (int)$client->id }}',
                portal: 'Legal & CPA Client Portal'
            });

            window.$chatwoot.toggle("open");
            enforceChatwootDocked();
        }
    });
</script>
@endsection
