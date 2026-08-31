@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')
<style>
    .chat-settings-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 24px;
    }
    .provider-radio-card {
        background: #0f172a;
        border: 2px solid #334155;
        border-radius: 8px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .provider-radio-card:hover, .provider-radio-card.active {
        border-color: #f59e0b;
        background: #1e293b;
    }
</style>
@endsection

@section('content')
<div id="wrapper-content">
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark">
                <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                <a class="breadcrumb-item text-white" href="{{ route('admin.settings.general') }}">{{ __('Settings') }}</a>
                <span class="breadcrumb-item active">{{ __($title) }}</span>
                <span class="breadcrumb-info" id="time"></span>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-dark bg-dark">
                <div class="card-header">
                    <h6 class="card-title font-weight-bold text-warning mb-0">
                        <i class="fas fa-comments mr-2"></i> {{ __('Live Chat & Chatwoot Integration Settings') }}
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.chat-save') }}" method="POST">
                        @csrf

                        <!-- Provider Selection -->
                        <div class="chat-settings-card">
                            <h6 class="text-white font-weight-bold mb-3">
                                <i class="fas fa-toggle-on text-warning mr-1"></i> {{ __('Select Primary Messaging Provider') }}
                            </h6>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label class="provider-radio-card d-block {{ ($chatSettings['provider'] ?? '') == 'chatwoot' ? 'active' : '' }}">
                                        <input type="radio" name="provider" value="chatwoot" {{ ($chatSettings['provider'] ?? '') == 'chatwoot' ? 'checked' : '' }} onchange="toggleProviderSettings(this.value)">
                                        <strong class="text-white ml-2">Chatwoot Live Support</strong>
                                        <small class="text-muted d-block mt-1">Recommended: Secure HMAC user sync with 100% conversation history retention across client devices.</small>
                                    </label>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="provider-radio-card d-block {{ ($chatSettings['provider'] ?? '') == 'tawkto' ? 'active' : '' }}">
                                        <input type="radio" name="provider" value="tawkto" {{ ($chatSettings['provider'] ?? '') == 'tawkto' ? 'checked' : '' }} onchange="toggleProviderSettings(this.value)">
                                        <strong class="text-white ml-2">Tawk.to Widget</strong>
                                        <small class="text-muted d-block mt-1">Embed a standard Tawk.to live chat property badge across the website & portal.</small>
                                    </label>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="provider-radio-card d-block {{ ($chatSettings['provider'] ?? '') == 'internal' ? 'active' : '' }}">
                                        <input type="radio" name="provider" value="internal" {{ ($chatSettings['provider'] ?? '') == 'internal' ? 'checked' : '' }} onchange="toggleProviderSettings(this.value)">
                                        <strong class="text-white ml-2">Internal Direct Messaging</strong>
                                        <small class="text-muted d-block mt-1">Use built-in portal messaging without external third-party integrations.</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Chatwoot Credentials -->
                        <div id="chatwoot_fields" class="chat-settings-card" style="{{ ($chatSettings['provider'] ?? '') == 'chatwoot' ? '' : 'display:none;' }}">
                            <h6 class="text-warning font-weight-bold mb-2">
                                <i class="fas fa-shield-alt mr-1"></i> {{ __('Chatwoot API & HMAC Configuration') }}
                            </h6>
                            <p class="text-muted small mb-4">
                                Chatwoot HMAC enables cryptographic user verification so each client's conversation history is permanently synced and protected.
                            </p>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="text-white font-weight-bold">{{ __('Chatwoot Website Token') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="website_token" class="form-control bg-dark text-white border-secondary" value="{{ $chatSettings['website_token'] ?? '' }}" placeholder="e.g. uHR3DJPM8AZ2Lpo8tDdJ5tei">
                                    <small class="text-muted">Found in Chatwoot > Settings > Inboxes > Website Inbox > Configuration</small>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="text-white font-weight-bold">{{ __('Chatwoot Base URL') }} <span class="text-danger">*</span></label>
                                    <input type="url" name="base_url" class="form-control bg-dark text-white border-secondary" value="{{ $chatSettings['base_url'] ?? 'https://app.chatwoot.com' }}" placeholder="https://app.chatwoot.com">
                                    <small class="text-muted">Use https://app.chatwoot.com for cloud or your self-hosted domain</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="text-white font-weight-bold">{{ __('Chatwoot Account ID') }}</label>
                                    <input type="text" name="account_id" class="form-control bg-dark text-white border-secondary" value="{{ $chatSettings['account_id'] ?? '' }}" placeholder="e.g. 180927">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="text-white font-weight-bold">{{ __('Chatwoot Identity Validation HMAC Secret Key') }}</label>
                                    <input type="text" name="hmac_key" class="form-control bg-dark text-white border-secondary" value="{{ $chatSettings['hmac_key'] ?? '' }}" placeholder="e.g. 6q99KLZgjCtHCd1fvQpQTp2F">
                                    <small class="text-muted">Found in Chatwoot Inboxes > Settings > Identity Validation</small>
                                </div>
                            </div>
                        </div>

                        <!-- Tawk.to Fields -->
                        <div id="tawkto_fields" class="chat-settings-card" style="{{ ($chatSettings['provider'] ?? '') == 'tawkto' ? '' : 'display:none;' }}">
                            <h6 class="text-warning font-weight-bold mb-2">
                                <i class="fas fa-headset mr-1"></i> {{ __('Tawk.to Property Configuration') }}
                            </h6>
                            <div class="form-group">
                                <label class="text-white font-weight-bold">{{ __('Tawk.to Property ID / Direct Embed Code') }}</label>
                                <input type="text" name="tawkto_property_id" class="form-control bg-dark text-white border-secondary" value="{{ $chatSettings['tawkto_property_id'] ?? '' }}" placeholder="e.g. 6a742dd38875351d455643d1/default">
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-warning font-weight-bold text-dark px-5 py-2">
                                <i class="fas fa-save mr-1"></i> {{ __('Save Messaging Settings') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script>
    function toggleProviderSettings(val) {
        document.querySelectorAll('.provider-radio-card').forEach(function(card) {
            card.classList.remove('active');
        });
        event.target.closest('.provider-radio-card').classList.add('active');

        document.getElementById('chatwoot_fields').style.display = (val === 'chatwoot') ? 'block' : 'none';
        document.getElementById('tawkto_fields').style.display = (val === 'tawkto') ? 'block' : 'none';
    }
</script>
@endsection
