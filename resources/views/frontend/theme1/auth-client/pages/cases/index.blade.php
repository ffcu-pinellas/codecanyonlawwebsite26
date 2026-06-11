@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .case-card {
        background: white;
        border-radius: 15px;
        padding: 24px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    .case-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    }
    .status-badge-pending { background-color: #ffeaa7; color: #d63031; font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    .status-badge-active { background-color: #dff9fb; color: #0984e3; font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    .status-badge-suspended { background-color: #ffcccc; color: #ff0000; font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    .status-badge-resolved { background-color: #e3fafc; color: #0ca678; font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    
    .section-title {
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 25px;
        font-family: 'Montserrat', sans-serif;
    }
    .attorney-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
        border: 2px solid #e9ecef;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="section-title mb-0">{{ __($title) }}</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @php
        $pendingCases = $cases->where('status', 'pending');
        $activeCases = $cases->where('status', '!=', 'pending');
    @endphp

    @if($pendingCases->count() > 0)
        <div class="mb-5">
            <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-clock text-warning mr-2"></i> {{ __('Pending Case Requests (Awaiting Review)') }}</h5>
            <div class="row">
                @foreach($pendingCases as $case)
                    <div class="col-md-6 col-lg-6 mb-4">
                        <div class="card case-card mb-0" style="border-left: 4px solid #f1c40f;">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="text-warning font-weight-bold" style="font-size: 0.85rem;">{{ $case->case_number }}</span>
                                    <h5 class="font-weight-bold text-dark mt-1 mb-0" style="font-size: 1.15rem; line-height: 1.4;">{{ $case->title }}</h5>
                                </div>
                                <span class="badge badge-warning text-dark font-weight-bold px-3 py-1" style="border-radius: 20px; font-size: 0.75rem;">
                                    <i class="fas fa-hourglass-half mr-1"></i> {{ __('Awaiting Verification') }}
                                </span>
                            </div>

                            <p class="text-muted small mb-3 text-truncate" style="max-height: 45px;">
                                {{ $case->description ?: __('No detailed description provided.') }}
                            </p>

                            @if($case->documents->count() > 0)
                                <div class="mb-3">
                                    <small class="text-muted d-block font-weight-bold mb-1" style="font-size: 0.65rem;">{{ __('SUPPORT DOCUMENT') }}</small>
                                    @foreach($case->documents as $doc)
                                        <a href="{{ route('client.documents.preview', $doc->id) }}" class="btn btn-outline-secondary btn-sm mr-2 mb-1" target="_blank">
                                            <i class="fas fa-file-pdf text-danger mr-1"></i> {{ $doc->title ?: __('Document') }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            <div class="border-top border-light pt-3 mt-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block font-weight-bold" style="font-size: 0.65rem;">{{ __('SUBMITTED ON') }}</small>
                                    <span class="text-dark small font-weight-semibold mt-1 d-inline-block">
                                        <i class="far fa-clock mr-1 text-secondary"></i> {{ $case->created_at->format('M d, Y H:i A') }}
                                    </span>
                                </div>
                                
                                <div>
                                    <a href="{{ route('client.cases.details', $case->id) }}" class="btn btn-outline-primary btn-sm font-weight-bold py-1 px-3">
                                        <i class="fas fa-folder-open mr-1"></i> {{ __('Open Vault') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-briefcase text-primary mr-2"></i> {{ __('Active Representation Cases') }}</h5>

    <div class="row">
        @forelse($activeCases as $case)
            <div class="col-md-6 col-lg-6 mb-4">
                <div class="card case-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="text-primary font-weight-bold" style="font-size: 0.85rem;">{{ $case->case_number }}</span>
                            <h5 class="font-weight-bold text-dark mt-1 mb-0" style="font-size: 1.15rem; line-height: 1.4;">{{ $case->title }}</h5>
                        </div>
                        <span class="status-badge-{{ $case->status }}">{{ ucfirst($case->status) }}</span>
                    </div>

                    <p class="text-muted small mb-4 text-truncate" style="max-height: 45px;">
                        {{ $case->description ?: __('No detailed description provided for this case.') }}
                    </p>

                    <div class="border-top border-light pt-3 d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block uppercase font-weight-bold" style="font-size: 0.65rem;">{{ __('Assigned Attorney') }}</small>
                            @if($case->attorney)
                                <div class="d-flex align-items-center mt-1">
                                    @if($case->attorney->attorney && $case->attorney->attorney->image)
                                        <img src="{{ asset('upload/attorneys/' . $case->attorney->attorney->image) }}" class="attorney-avatar">
                                    @else
                                        <div class="attorney-avatar bg-primary text-white d-flex align-items-center justify-content-center font-weight-bold" style="font-size: 0.8rem;">
                                            {{ substr($case->attorney->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <span class="font-weight-semibold text-dark small">{{ $case->attorney->name }}</span>
                                </div>
                            @else
                                <span class="text-muted small mt-1 d-inline-block">{{ __('Unassigned') }}</span>
                            @endif
                        </div>

                        <div>
                            <small class="text-muted d-block text-right uppercase font-weight-bold" style="font-size: 0.65rem;">{{ __('Court Date / Deadline') }}</small>
                            <span class="text-dark small font-weight-semibold d-block text-right mt-1">
                                @if($case->court_date)
                                    <i class="far fa-calendar-alt text-primary mr-1"></i> {{ $case->court_date->format('M d, Y') }}
                                @else
                                    {{ __('Not Scheduled') }}
                                </span>
                                @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('client.cases.details', $case->id) }}" class="btn btn-primary btn-sm btn-block py-2 rounded-lg font-weight-bold">
                            <i class="fas fa-folder-open mr-1"></i> {{ __('Open Secure Document Vault') }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card case-card text-center py-5">
                    <div class="py-4">
                        <i class="fas fa-briefcase fa-4x text-light mb-3"></i>
                        <h5 class="text-dark font-weight-bold">{{ __('No active cases found.') }}</h5>
                        <p class="text-muted px-4">{{ __('You currently do not have any active cases assigned to your profile. Please contact support or your attorney if this is an error.') }}</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
