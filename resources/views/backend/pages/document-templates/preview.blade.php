@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    .preview-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 30px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .preview-pane {
        background: #f4f6f9;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 40px;
        min-height: 500px;
        box-shadow: inset 0 2px 10px rgba(0,0,0,0.05);
    }
    .document-body {
        background: #ffffff;
        padding: 50px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        color: #333;
        font-family: 'Georgia', serif;
        line-height: 1.6;
        font-size: 15px;
    }
    .document-body h2 {
        font-family: sans-serif;
        text-transform: uppercase;
        font-size: 20px;
        border-bottom: 2px solid #222;
        padding-bottom: 8px;
        margin-bottom: 25px;
        color: #111;
        letter-spacing: 0.5px;
    }
    .document-body h3, .document-body h4 {
        font-family: sans-serif;
        text-transform: uppercase;
        margin-top: 30px;
        font-size: 16px;
        color: #111;
    }
    .placeholder-badge {
        background-color: #ffe8cc;
        color: #d97706;
        padding: 2px 6px;
        border-radius: 4px;
        font-family: monospace;
        font-size: 13px;
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
                    <a class="breadcrumb-item text-white" href="{{ route('admin.document-templates.index') }}">{{ __('Document Templates') }}</a>
                    <span class="breadcrumb-item active">{{ __('Preview & Test') }}</span>
                    <span class="breadcrumb-info" id="time"></span>
                </nav>
            </div>
        </div>

        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="material-icons align-middle mr-1">check_circle</i> {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="material-icons align-middle mr-1">error</i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <!-- Left Panel: Settings & Test Email Dispatcher -->
            <div class="col-lg-4">
                <div class="card preview-card bg-dark text-white mb-4">
                    <div class="card-header bg-secondary">
                        <h6 class="card-title mb-0"><i class="material-icons align-middle mr-1">settings</i> {{ __('Document Options') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted d-block small mb-1">{{ __('Template Name') }}</label>
                            <span class="font-weight-bold d-block">{{ $template->title }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted d-block small mb-1">{{ __('Key Identifier') }}</label>
                            <code>{{ $template->key }}</code>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted d-block small mb-1">{{ __('Category') }}</label>
                            <span class="badge {{ $template->type === 'client' ? 'badge-primary' : 'badge-info' }} text-uppercase">
                                {{ $template->type }}
                            </span>
                        </div>
                        <hr class="bg-secondary">

                        <h6 class="font-weight-bold mb-3 text-warning"><i class="material-icons align-middle mr-1">mail</i> {{ __('Email Document Draft') }}</h6>
                        <p class="small text-muted mb-4">
                            {{ __('Choose a recipient profile to populate this template with their custom data and email a professionally styled HTML notice + attachment PDF to them.') }}
                        </p>

                        <form action="{{ route('admin.document-templates.send-test', $template->id) }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="user_id" class="small text-white font-weight-bold">{{ __('Select Recipient Profile (Optional)') }}</label>
                                <select name="user_id" id="user_id" class="form-control bg-dark text-white border-secondary">
                                    <option value="" data-email="">-- {{ __('Choose Profile') }} --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" data-email="{{ $user->email }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">{{ __('Selecting a user resolves their case parameters and metadata.') }}</small>
                            </div>

                            <div class="form-group mb-4">
                                <label for="recipient_email" class="small text-white font-weight-bold">{{ __('Recipient Email Address') }} <span class="text-danger">*</span></label>
                                <input type="email" name="recipient_email" id="recipient_email" class="form-control bg-dark text-white border-secondary" placeholder="e.g. client@example.com" required>
                                @error('recipient_email') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="btn btn-warning btn-block font-weight-bold py-2 shadow">
                                <i class="material-icons align-middle mr-1">send</i> {{ __('Send Document Email') }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card preview-card bg-dark text-white">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-info"><i class="material-icons align-middle mr-1">info</i> {{ __('About Tracking Logs') }}</h6>
                        <p class="small text-muted mb-0">
                            {{ __('Every dispatch adds a tracking token and 1x1 tracking pixel to the email body. You can inspect the email open events and logs under ') }} <a href="{{ route('admin.document-templates.history') }}" class="text-info font-weight-bold">{{ __('Sent & Tracking History') }}</a>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Panel: HTML Print & PDF Layout Preview -->
            <div class="col-lg-8">
                <div class="card preview-card bg-dark text-white mb-4">
                    <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0"><i class="material-icons align-middle mr-1">visibility</i> {{ __('Print & PDF Representation Preview') }}</h6>
                        <span class="small text-white-50"><i class="material-icons align-middle mr-1">print</i> {{ __('Scale: 100% US Letter') }}</span>
                    </div>
                    <div class="card-body bg-secondary p-4">
                        <div class="preview-pane">
                            <div class="document-body">
                                <!-- Render corporate letterhead dynamically -->
                                @include('backend.layouts.letterhead')

                                <div style="text-align: center; margin: 35px 0;">
                                    <h2>{{ $template->title }}</h2>
                                </div>

                                <div class="document-content">
                                    {!! $content !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            $('#user_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var email = selectedOption.data('email');
                if (email) {
                    $('#recipient_email').val(email);
                } else {
                    $('#recipient_email').val('');
                }
            });
        });
    })(jQuery);
</script>
@endsection
