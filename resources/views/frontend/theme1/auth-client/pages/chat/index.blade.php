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
        z-index: 10 !important;
    }
    .woot-widget-bubble {
        display: none !important;
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
        <a href="{{ route('client.dashboard') }}" class="btn btn-outline-warning btn-sm font-weight-bold px-3">
            <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Dashboard') }}
        </a>
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
                    <small class="text-success font-weight-bold" style="font-size: 10px;"><i class="fas fa-circle mr-1" style="font-size: 7px;"></i> {{ __('Encrypted Active Channel &bull; Direct Case Line') }}</small>
                </div>
            </div>
            <div>
                <span class="badge" style="background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3); font-size: 11px;">
                    <i class="fas fa-shield-alt mr-1"></i> {{ __('256-Bit SSL Encrypted') }}
                </span>
            </div>
        </div>

        <div class="card-body p-0 chatwoot-docked-container" id="chatwoot-inpage-mount">
            <div id="chatwoot-mount-loader" class="d-flex flex-column align-items-center justify-content-center h-100 p-5 text-center" style="min-height: 600px; background: #0e1117;">
                <div class="spinner-border text-warning mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
                <h5 class="text-white font-weight-bold mb-1">{{ __('Connecting to Live Counsel...') }}</h5>
                <p class="text-muted small mb-3">{{ __('Initializing 256-bit encrypted messaging channel with complete chat retention.') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function attachChatwootToContainer() {
            var holder = document.querySelector('.woot-widget-holder');
            var container = document.getElementById('chatwoot-inpage-mount');
            var loader = document.getElementById('chatwoot-mount-loader');
            
            if (holder && container) {
                if (holder.parentElement !== container) {
                    container.appendChild(holder);
                }
                holder.style.position = 'absolute';
                holder.style.top = '0';
                holder.style.left = '0';
                holder.style.width = '100%';
                holder.style.height = '100%';
                holder.style.display = 'block';
                if (loader) loader.style.display = 'none';

                if (window.$chatwoot) {
                    window.$chatwoot.toggle('open');
                }
            } else {
                setTimeout(attachChatwootToContainer, 300);
            }
        }

        // Trigger open immediately
        setTimeout(attachChatwootToContainer, 400);
        setTimeout(attachChatwootToContainer, 1200);
    });
</script>
@endsection
