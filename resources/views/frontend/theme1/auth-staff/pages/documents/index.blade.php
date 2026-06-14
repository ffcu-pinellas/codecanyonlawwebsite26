@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    .doc-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        margin-bottom: 25px;
    }
    .doc-item {
        transition: background-color 0.2s ease;
    }
    .doc-item:hover {
        background-color: #fafbfc;
    }
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .bg-light-primary {
        background-color: #eef2f6;
        color: #2c5364;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="card doc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h5 class="mb-0" style="font-weight: 700; color: #2c3e50;">
                            <i class="fas fa-folder-open mr-2 text-primary"></i>{{ __('Staff Document Center') }}
                        </h5>
                        <span class="badge badge-primary font-weight-bold">{{ $templates->count() }} {{ __('Available Documents') }}</span>
                    </div>

                    <p class="text-muted mb-4">
                        {{ __('Access your personalized staff documents, employment agreements, direct deposit authorizations, and informational forms. You can print or download pre-populated versions containing your profile metadata.') }}
                    </p>

                    <div class="list-group list-group-flush">
                        @forelse($templates as $tmpl)
                            <div class="list-group-item px-0 py-3 doc-item">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center d-none d-md-block">
                                        <div class="icon-box bg-light-primary mx-auto">
                                            <i class="far fa-file-pdf"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-sm-12">
                                        <h6 class="font-weight-bold text-dark mb-1">{{ $tmpl->title }}</h6>
                                        <p class="text-muted mb-0 small">
                                            {{ __('Template Identifier:') }} <code>{{ $tmpl->key }}</code> &bull; {{ __('Category: Staff Action Form') }}
                                        </p>
                                    </div>
                                    <div class="col-md-3 col-sm-12 text-md-right mt-3 mt-md-0">
                                        <a href="{{ route('staff.documents.print', $tmpl->key) }}" target="_blank" class="btn btn-outline-primary btn-sm px-4">
                                            <i class="fas fa-print mr-1"></i> {{ __('View & Print') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                                <p class="text-muted mb-0">{{ __('No staff templates configured.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
