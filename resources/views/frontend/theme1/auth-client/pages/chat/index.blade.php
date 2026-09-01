@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    .chat-hero-box {
        background: linear-gradient(135deg, #161a23 0%, #0e1117 100%);
        border: 1px solid #28303f;
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    }
    .chat-console-card {
        background: #161a23;
        border: 1px solid #28303f;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 24px rgba(0,0,0,0.3);
    }
    .chat-console-header {
        background: #1f2533;
        border-bottom: 1px solid #2e3849;
        padding: 16px 20px;
        color: #fecc56;
        font-weight: 700;
    }
    .chat-live-badge {
        background: rgba(34, 197, 94, 0.15);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 20px;
        padding: 3px 10px;
        font-size: 11.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .chat-info-pill {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #94a3b8;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 12px;
        margin-right: 8px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0 py-3">

    <!-- Top Chat Banner -->
    <div class="chat-hero-box">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-2">
                    <span class="chat-live-badge mr-2"><i class="fas fa-circle text-success small"></i> {{ __('Counsel Online') }}</span>
                    <span class="chat-info-pill"><i class="fas fa-lock text-warning mr-1"></i> {{ __('256-Bit SSL Encrypted') }}</span>
                    <span class="chat-info-pill"><i class="fas fa-history mr-1"></i> {{ __('100% Chat History Retained') }}</span>
                </div>
                <h4 class="font-weight-bold text-white mb-1">{{ __('Live Support & Direct Counsel Communications') }}</h4>
                <p class="text-muted small mb-0">{{ __('Connect directly with your assigned Attorney, CPA, and dedicated case manager in real-time.') }}</p>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                <button type="button" onclick="openChatwootWidget()" class="btn btn-warning font-weight-bold text-dark px-4 py-2 shadow-sm" style="background: linear-gradient(135deg, #fecc56, #f0a500); border: none; border-radius: 8px;">
                    <i class="fas fa-comment-dots mr-1"></i> {{ __('Open Live Chat Window') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Main Chatwoot Interface Card -->
    <div class="chat-console-card mb-4">
        <div class="chat-console-header d-flex justify-content-between align-items-center">
            <span>
                <i class="fas fa-comments text-warning mr-2"></i> {{ __('Encrypted Live Client Communications') }}
            </span>
            <span class="small text-muted">
                {{ __('Client Reference:') }} <strong class="text-warning">CLI-{{ sprintf('%05d', Auth::user()->id) }}</strong>
            </span>
        </div>
        <div class="card-body p-4 text-center" style="background: #11151e; min-height: 480px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <div style="background: rgba(254, 204, 86, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; border: 2px solid rgba(254, 204, 86, 0.25);">
                <i class="fas fa-headset fa-3x text-warning"></i>
            </div>
            <h5 class="font-weight-bold text-white mb-2">{{ __('Start a Privileged Conversation') }}</h5>
            <p class="text-muted small mb-4" style="max-width: 520px;">
                {{ __('Your communications are confidential and automatically synchronized with your case file. Click below to launch the live messaging assistant.') }}
            </p>

            <button type="button" onclick="openChatwootWidget()" class="btn btn-warning font-weight-bold text-dark px-5 py-3 shadow-lg" style="background: linear-gradient(135deg, #fecc56, #f0a500); border: none; border-radius: 8px; font-size: 15px; text-transform: uppercase; letter-spacing: 0.5px;">
                <i class="fas fa-comments mr-2"></i> {{ __('Launch Live Chat Support') }}
            </button>
        </div>
    </div>

</div>
@endsection

@section('page-script')
<script>
    function openChatwootWidget() {
        if (window.$chatwoot) {
            window.$chatwoot.toggle();
        } else {
            alert('Connecting to live counsel... Please ensure live chat is enabled.');
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Auto pop open the widget if directed
        setTimeout(function() {
            if (window.$chatwoot && !window.location.hash.includes('no-auto')) {
                window.$chatwoot.toggle('open');
            }
        }, 800);
    });
</script>
@endsection
