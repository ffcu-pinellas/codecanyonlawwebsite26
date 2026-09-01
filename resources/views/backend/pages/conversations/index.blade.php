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

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs chat-nav-tabs border-bottom-0" id="chatTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="chatwoot-tab" data-toggle="tab" href="#tab-chatwoot" role="tab" aria-controls="tab-chatwoot" aria-selected="true">
                    <i class="fas fa-comments text-warning mr-1"></i> {{ __('Live Support') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="archive-tab" data-toggle="tab" href="#tab-archive" role="tab" aria-controls="tab-archive" aria-selected="false">
                    <i class="fas fa-history mr-1"></i> {{ __('Internal Message') }}
                </a>
            </li>
        </ul>

        <div class="tab-content" id="chatTabsContent">
            <!-- TAB 1: Chatwoot Live Support Hub -->
            <div class="tab-pane fade show active" id="tab-chatwoot" role="tabpanel" aria-labelledby="chatwoot-tab">
                <div class="card card-dark bg-dark border-secondary">
                    <div class="card-body p-4">
                        @php
                            $provider = $chatSettings['provider'] ?? 'chatwoot';
                            $token = $chatSettings['website_token'] ?? '';
                            $baseUrl = rtrim($chatSettings['base_url'] ?? 'https://app.chatwoot.com', '/');
                            $isConfigured = !empty($token) || $provider === 'chatwoot';
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

                                <div class="d-flex flex-wrap justify-content-center gap-3" style="gap: 12px;">
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

            <!-- TAB 2: Internal Message Archive -->
            <div class="tab-pane fade" id="tab-archive" role="tabpanel" aria-labelledby="archive-tab">
                <div class="card card-dark bg-dark">
                    <div class="card-header d-block">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <h6 class="card-title text-white font-weight-bold"><i class="fas fa-archive text-warning mr-1"></i> {{ __('Legacy Database Message Logs') }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="slider" class="table bapric_table table-striped table-bordered miw-500 text-white" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th scope="col">{{ __('Serial') }}</th>
                                    <th scope="col">{{ __('Avatar') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Message') }}</th>
                                    <th scope="col">{{ __('Time') }}</th>
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
        </div>
    </div>
</div>
@endsection

@section('page-script')
    @include('backend.pages.appointments.internal-assets.js.delete-warning')
    @include('backend.layouts.message')
@endsection
