@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    .case-card-item {
        background: #161a23;
        border: 1px solid #28303f;
        border-left: 4px solid #fecc56;
        border-radius: 10px;
        padding: 22px;
        transition: all 0.2s ease;
        margin-bottom: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    }
    .case-card-item:hover {
        border-color: #fecc56;
        box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        background: #1c212c;
    }
    .status-badge-pending { background-color: rgba(245, 158, 11, 0.15); color: #fecc56; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: bold; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; }
    .status-badge-active { background-color: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); font-weight: bold; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; }
    .status-badge-suspended { background-color: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: bold; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; }
    .status-badge-resolved { background-color: rgba(14, 165, 233, 0.15); color: #38bdf8; border: 1px solid rgba(14, 165, 233, 0.3); font-weight: bold; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; }
    
    .btn-gold {
        background: linear-gradient(135deg, #fecc56, #f0a500);
        color: #000 !important;
        border: none;
        font-weight: 700;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 13px;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(254,204,86,0.25);
    }
    .btn-gold:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(254,204,86,0.45);
    }
    .attorney-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
        border: 2px solid #fecc56;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0 py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="font-weight-bold text-white mb-1">
                <i class="fas fa-briefcase text-warning mr-2"></i> {{ __('My Legal & CPA Cases') }}
            </h4>
            <p class="text-muted small mb-0">{{ __('Access privileged case files, financial schedules, court dockets, and document vaults.') }}</p>
        </div>
        <div>
            <a href="{{ route('client.financial-relief') }}" class="btn btn-gold">
                <i class="fas fa-folder-plus mr-1"></i> {{ __('Open New Case') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success font-weight-bold mb-4" style="background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80;">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    @php
        $pendingCases = $cases->where('status', 'pending');
        $activeCases = $cases->where('status', '!=', 'pending');
    @endphp

    @if($pendingCases->count() > 0)
        <div class="mb-4">
            <h6 class="font-weight-bold text-warning mb-3 text-uppercase" style="letter-spacing: 0.5px;">
                <i class="fas fa-clock mr-1"></i> {{ __('Pending Intake Cases') }}
            </h6>
            <div class="row">
                @foreach($pendingCases as $case)
                    <div class="col-md-6 mb-3">
                        <div class="case-card-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="text-warning font-weight-bold" style="font-size: 0.85rem;">{{ $case->case_number }}</span>
                                    <h5 class="font-weight-bold text-white mt-1 mb-0" style="font-size: 1.1rem;">{{ $case->title }}</h5>
                                </div>
                                <span class="badge badge-warning text-dark font-weight-bold px-2 py-1">{{ __('Awaiting Intake') }}</span>
                            </div>
                            <p class="text-muted small mb-3 text-truncate">{{ $case->description ?: __('Intake notice under review.') }}</p>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top" style="border-color: #28303f !important;">
                                <small class="text-muted">{{ $case->created_at ? $case->created_at->format('M d, Y') : '' }}</small>
                                <a href="{{ route('client.cases.details', $case->id) }}" class="btn-gold" style="font-size: 11px; padding: 4px 10px;">
                                    <i class="fas fa-folder-open mr-1"></i> {{ __('View Case File') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <h6 class="font-weight-bold text-white mb-3 text-uppercase" style="letter-spacing: 0.5px;">
        <i class="fas fa-gavel text-warning mr-1"></i> {{ __('Active Representation Cases') }}
    </h6>

    <div class="row">
        @forelse($activeCases as $case)
            <div class="col-md-6 mb-4">
                <div class="case-card-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="text-warning font-weight-bold" style="font-size: 0.85rem;">{{ $case->case_number }}</span>
                            <h5 class="font-weight-bold text-white mt-1 mb-0" style="font-size: 1.15rem;">{{ $case->title }}</h5>
                        </div>
                        <span class="status-badge-{{ $case->status }}">{{ ucfirst($case->status) }}</span>
                    </div>

                    <p class="text-muted small mb-3 text-truncate">{{ $case->description ?: __('Case representation active.') }}</p>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: #28303f !important;">
                        <div>
                            <small class="text-muted d-block font-weight-bold" style="font-size: 10px; text-transform: uppercase;">{{ __('Assigned Legal Counsel') }}</small>
                            <span class="text-white small font-weight-bold">{{ $case->attorney ? $case->attorney->name : 'Gary Livingston, Senior CPA' }}</span>
                        </div>
                        <div>
                            <a href="{{ route('client.cases.details', $case->id) }}" class="btn-gold" style="font-size: 12px;">
                                <i class="fas fa-folder-open mr-1"></i> {{ __('Access Case File & Vault') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="p-5 text-center rounded" style="background: #161a23; border: 1px solid #28303f;">
                    <i class="fas fa-briefcase fa-3x text-secondary mb-3 d-block"></i>
                    <h5 class="text-white font-weight-bold">{{ __('No active cases on file.') }}</h5>
                    <p class="text-muted small">{{ __('You currently do not have any active representation cases assigned to your profile.') }}</p>
                    <a href="{{ route('client.financial-relief') }}" class="btn-gold mt-2">
                        <i class="fas fa-folder-plus mr-1"></i> {{ __('Submit a Case Inquiry') }}
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
