@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('content')
    <div id="wrapper-content">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                    <span class="breadcrumb-item active">{{ __($title) }}</span>
                </nav>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-dark bg-dark">
                    <div class="card-header">
                        <h6 class="card-title"><i class="fas fa-file-invoice mr-2"></i> {{ __('Legal Document Builder & Form Populator') }}</h6>
                    </div>

                    <div class="card-body">
                        <p class="text-light mb-4">
                            {{ __('Choose a legal template, select a client, and automatically populate document placeholders with their name, email, and address info for print ready layout.') }}
                        </p>

                        <form action="{{ route('admin.document-generator.generate') }}" method="POST" target="_blank">
                            @csrf
                            
                            <div class="form-group">
                                <label for="template_type"><strong>{{ __('Select Document Template') }} <span class="text-danger">*</span></strong></label>
                                <select name="template_type" id="template_type" class="form-control form-control-lg bg-dark text-white border-secondary" required>
                                    <option value="">-- {{ __('Choose Template') }} --</option>
                                    <option value="retainer">{{ __('Legal Representation Retainer Agreement') }}</option>
                                    <option value="power_of_attorney">{{ __('General Power of Attorney (POA)') }}</option>
                                    <option value="cpa_auth">{{ __('IRS Form CPA Representation Authorization') }}</option>
                                </select>
                                @error('template_type') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="client_id"><strong>{{ __('Associate Client Profile') }} <span class="text-danger">*</span></strong></label>
                                <select name="client_id" id="client_id" class="form-control form-control-lg bg-dark text-white border-secondary" required>
                                    <option value="">-- {{ __('Choose Client') }} --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->email }})</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">{{ __('Make sure the client has address information populated in their user profile.') }}</small>
                                @error('client_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mt-5 pt-3 border-top border-secondary">
                                <button type="submit" class="btn btn-primary btn-lg px-5"><i class="fas fa-print mr-2"></i> {{ __('Generate & Print Document') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
