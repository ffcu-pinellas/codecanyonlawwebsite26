@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    /* EXECUTIVE IFW RECOVERY LUXURY THEME */
    .portal-hero {
        background: linear-gradient(135deg, #161a23 0%, #0e1117 100%);
        border: 1px solid #28303f;
        border-radius: 12px;
        padding: 24px 28px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
    .portal-hero-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(254, 204, 86, 0.12);
        color: #fecc56;
        border: 1px solid rgba(254, 204, 86, 0.3);
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 10px;
    }
    .stat-card-luxury {
        background: linear-gradient(145deg, #181d27 0%, #11151e 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 18px 20px;
        transition: all 0.25s ease;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .stat-card-luxury:hover {
        border-color: rgba(254, 204, 86, 0.35);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.4);
    }
    .stat-card-luxury .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .stat-card-luxury .stat-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8 !important;
        margin-bottom: 0;
    }
    .stat-card-luxury .stat-icon-wrap {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(254, 204, 86, 0.1);
        color: #fecc56;
        border: 1px solid rgba(254, 204, 86, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
    .stat-card-luxury .stat-value {
        font-size: 1.65rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.2;
        margin-bottom: 4px;
    }
    .stat-badge-verified {
        background: rgba(34, 197, 94, 0.12);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.25);
        border-radius: 6px;
        padding: 3px 10px;
        font-size: 11.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .progress-track-container {
        background: #161a23;
        border: 1px solid #28303f;
        border-radius: 12px;
        padding: 22px 20px;
        margin-bottom: 24px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    }
    .progress-track {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-top: 15px;
        margin-bottom: 5px;
    }
    .progress-track::before {
        content: '';
        position: absolute;
        top: 18px;
        left: 20px;
        right: 20px;
        height: 4px;
        background: #262e3d;
        z-index: 1;
        border-radius: 2px;
    }
    .progress-bar-fill {
        position: absolute;
        top: 18px;
        left: 20px;
        height: 4px;
        background: linear-gradient(90deg, #fecc56, #22c55e);
        z-index: 1;
        border-radius: 2px;
        transition: width 0.6s ease;
    }
    .step-item {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
        min-width: 0;
        padding: 0 4px;
    }
    .step-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #1c212c;
        border: 2px solid #374151;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 6px;
        font-size: 12px;
        font-weight: 700;
        color: #94a3b8;
        transition: all 0.3s;
    }
    .step-item.active .step-icon {
        background: #fecc56;
        border-color: #fecc56;
        color: #000;
        box-shadow: 0 0 14px rgba(254,204,86,0.6);
    }
    .step-item.completed .step-icon {
        background: #22c55e;
        border-color: #22c55e;
        color: #fff;
        box-shadow: 0 0 10px rgba(34,197,94,0.4);
    }
    .step-title {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        line-height: 1.2;
    }
    .step-item.active .step-title { color: #fecc56; font-weight: 700; }
    .step-item.completed .step-title { color: #22c55e; }

    .portal-card {
        background: #161a23;
        border: 1px solid #28303f;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .portal-card-header {
        background: #1f2533;
        border-bottom: 1px solid #2e3849;
        padding: 16px 20px;
        color: #fecc56;
        font-weight: 700;
        font-size: 14px;
    }
    .table-portal {
        width: 100%;
        color: #f1f5f9;
        margin-bottom: 0;
        border-collapse: collapse;
    }
    .table-portal thead th {
        background: #191f2c;
        color: #fecc56;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 2px solid #28303f;
    }
    .table-portal tbody tr {
        border-bottom: 1px solid #232a38;
        transition: background 0.15s;
    }
    .table-portal tbody tr:hover {
        background: #1a202c;
    }
    .table-portal td {
        padding: 14px 16px;
        font-size: 13px;
        vertical-align: middle;
    }
    .btn-gold {
        background: linear-gradient(135deg, #fecc56, #f0a500);
        color: #000 !important;
        border: none;
        font-weight: 700;
        border-radius: 6px;
        padding: 6px 14px;
        font-size: 12px;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(254,204,86,0.25);
    }
    .btn-gold:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(254,204,86,0.45);
    }
    /* MOBILE RESPONSIVENESS (100% FLUID - ZERO HORIZONTAL SCROLL) */
    @media (max-width: 991px) {
        .portal-hero { padding: 18px 16px; margin-bottom: 16px; }
        .stat-card-luxury { padding: 14px 16px; margin-bottom: 12px; }
        .stat-card-luxury .stat-value { font-size: 1.4rem; }
        .progress-track-container { padding: 16px 12px; overflow-x: auto; }
        .progress-track { min-width: 540px; padding-bottom: 6px; }
        
        .table-portal thead { display: none; }
        .table-portal, .table-portal tbody, .table-portal tr, .table-portal td { display: block; width: 100%; }
        .table-portal tbody tr {
            margin-bottom: 14px;
            border: 1px solid #28303f;
            border-radius: 10px;
            padding: 12px 14px;
            background: #161a23;
        }
        .table-portal td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #1f2533;
            text-align: right;
        }
        .table-portal td:last-child {
            border-bottom: none;
            padding-top: 10px;
            justify-content: flex-end;
        }
        .table-portal td[data-label]::before {
            content: attr(data-label);
            font-weight: 700;
            color: #94a3b8;
            font-size: 11px;
            text-transform: uppercase;
            text-align: left;
            margin-right: 12px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">

    <!-- Top Welcome Hero Row -->
    <div class="portal-hero">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="portal-hero-badge"><i class="fas fa-shield-alt mr-1"></i> Privileged Legal & CPA Client Portal</span>
                <h3 class="font-weight-bold text-white mb-1" style="font-size: 24px;">Welcome, {{ Auth::user()->name }}</h3>
                <p class="text-muted mb-0 small">
                    Client File: <strong class="text-warning">CLI-{{ sprintf('%05d', Auth::user()->id) }}</strong> &bull; 
                    Status: <span class="text-success font-weight-bold"><i class="fas fa-check-circle"></i> Active & Protected</span>
                </p>
            </div>
            <div class="col-lg-5 text-lg-right mt-3 mt-lg-0">
                @php
                    $attorney = Auth::user()->assignedAttorney;
                    $attorneyName = $attorney ? $attorney->name : 'Gary Livingston, Senior CPA & Legal Counsel';
                    $attorneyEmail = $attorney ? $attorney->email : 'cpa.advisory@yourcpaexpert.com';
                    $attorneyPhone = $attorney ? $attorney->phone : '+1 (800) 459-2311';
                @endphp
                <div class="d-inline-block text-left p-3 rounded" style="background: #11151e; border: 1px solid #28303f; min-width: 260px;">
                    <small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">Assigned Legal & CPA Counsel</small>
                    <strong class="text-white d-block" style="font-size: 13px;">{{ $attorneyName }}</strong>
                    <div class="mt-2 d-flex gap-2" style="gap: 8px;">
                        <a href="{{ route('client.conversation.index') }}" class="btn-gold d-inline-flex align-items-center" style="font-size: 11px; padding: 4px 10px;">
                            <i class="fas fa-comment-dots mr-1"></i> Live Chat
                        </a>
                        <a href="{{ route('client.kyc.index') }}" class="btn-portal-secondary d-inline-flex align-items-center" style="font-size: 11px; padding: 4px 10px;">
                            <i class="fas fa-file-upload mr-1"></i> Upload Files
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Executive Stat Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card-luxury">
                <div class="stat-top">
                    <span class="stat-label">{{ __('Active Cases') }}</span>
                    <div class="stat-icon-wrap"><i class="fas fa-briefcase"></i></div>
                </div>
                <div>
                    <div class="stat-value">{{ $casesCount ?? 0 }}</div>
                    <span class="stat-badge-verified"><i class="fas fa-check"></i> {{ __('Under Representation') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card-luxury">
                <div class="stat-top">
                    <span class="stat-label">{{ __('Invoices & Retainers') }}</span>
                    <div class="stat-icon-wrap"><i class="fas fa-file-invoice-dollar"></i></div>
                </div>
                <div>
                    <div class="stat-value">${{ number_format(!empty($invoices) ? ($invoices->sum('total_amount') ?: 0) : 0, 2) }}</div>
                    <small class="text-muted" style="font-size: 11px;">{{ $invoicesCount ?? 0 }} {{ __('Statements Logged') }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card-luxury">
                <div class="stat-top">
                    <span class="stat-label">{{ __('Document Vault') }}</span>
                    <div class="stat-icon-wrap"><i class="fas fa-folder-open"></i></div>
                </div>
                <div>
                    <div class="stat-value">{{ $documentsCount ?? 0 }}</div>
                    <span class="stat-badge-verified"><i class="fas fa-lock"></i> {{ __('256-Bit Encrypted') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card-luxury">
                <div class="stat-top">
                    <span class="stat-label">{{ __('Security Status') }}</span>
                    <div class="stat-icon-wrap"><i class="fas fa-shield-alt"></i></div>
                </div>
                <div>
                    @if(Auth::user()->pin_hash)
                        <div class="stat-value text-success" style="font-size: 1.2rem; padding-top: 6px;"><i class="fas fa-check-circle mr-1"></i> PIN ACTIVE</div>
                        <small class="text-muted" style="font-size: 11px;">4-Digit PIN Configured</small>
                    @else
                        <div class="stat-value text-warning" style="font-size: 1.2rem; padding-top: 6px;">PENDING</div>
                        <a href="{{ route('client.profile') }}" class="text-warning small font-weight-bold">Configure PIN &rarr;</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Active Case Progression Tracker -->
    @php
        $latestCase = !empty($cases) ? $cases->first() : null;
        $progressPct = 40;
        $currentStage = 2;
        if ($latestCase) {
            $progressPct = $latestCase->progress_percentage ?: 40;
            if ($progressPct >= 100) $currentStage = 5;
            elseif ($progressPct >= 75) $currentStage = 4;
            elseif ($progressPct >= 50) $currentStage = 3;
            elseif ($progressPct >= 25) $currentStage = 2;
            else $currentStage = 1;
        }
    @endphp
    <div class="progress-track-container">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="font-weight-bold text-white mb-0" style="font-size: 13px;">
                <i class="fas fa-tasks text-warning mr-2"></i> {{ __('Active Case Lifecycle Progression') }}
                @if($latestCase)
                    <span class="text-muted font-weight-normal ml-2">({{ $latestCase->case_number }} - {{ $latestCase->title }})</span>
                @endif
            </h6>
            <span class="badge badge-warning text-dark font-weight-bold px-2 py-1">{{ $progressPct }}% {{ __('Completed') }}</span>
        </div>
        <div class="progress-track">
            <div class="progress-bar-fill" style="width: {{ $progressPct }}%;"></div>
            
            <div class="step-item {{ $currentStage > 1 ? 'completed' : ($currentStage == 1 ? 'active' : '') }}">
                <div class="step-icon">
                    @if($currentStage > 1) <i class="fas fa-check"></i> @else 1 @endif
                </div>
                <div class="step-title">{{ __('1. Case Intake & Retainer') }}</div>
            </div>

            <div class="step-item {{ $currentStage > 2 ? 'completed' : ($currentStage == 2 ? 'active' : '') }}">
                <div class="step-icon">
                    @if($currentStage > 2) <i class="fas fa-check"></i> @else 2 @endif
                </div>
                <div class="step-title">{{ __('2. Forensic Audit & Analysis') }}</div>
            </div>

            <div class="step-item {{ $currentStage > 3 ? 'completed' : ($currentStage == 3 ? 'active' : '') }}">
                <div class="step-icon">
                    @if($currentStage > 3) <i class="fas fa-check"></i> @else 3 @endif
                </div>
                <div class="step-title">{{ __('3. Legal & Regulatory Filings') }}</div>
            </div>

            <div class="step-item {{ $currentStage > 4 ? 'completed' : ($currentStage == 4 ? 'active' : '') }}">
                <div class="step-icon">
                    @if($currentStage > 4) <i class="fas fa-check"></i> @else 4 @endif
                </div>
                <div class="step-title">{{ __('4. Settlement Negotiations') }}</div>
            </div>

            <div class="step-item {{ $currentStage == 5 ? 'completed' : '' }}">
                <div class="step-icon">
                    @if($currentStage == 5) <i class="fas fa-check"></i> @else 5 @endif
                </div>
                <div class="step-title">{{ __('5. Final Resolution & Release') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Active Cases Table -->
        <div class="col-lg-7 mb-4">
            <div class="portal-card h-100">
                <div class="portal-card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-briefcase mr-2"></i> {{ __('My Active Legal & CPA Cases') }}</span>
                    <a href="{{ route('client.cases.index') }}" class="btn-portal-secondary" style="font-size: 11px;">{{ __('View All') }}</a>
                </div>
                <div class="table-responsive">
                    <table class="table-portal">
                        <thead>
                            <tr>
                                <th>{{ __('Case #') }}</th>
                                <th>{{ __('Subject / Title') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($cases) && $cases->count() > 0)
                                @foreach($cases->take(4) as $c)
                                    <tr>
                                        <td><strong class="text-warning">{{ $c->case_number }}</strong></td>
                                        <td>
                                            <div class="font-weight-bold text-white">{{ \Illuminate\Support\Str::limit($c->title, 35) }}</div>
                                            <small class="text-muted">{{ $c->created_at ? $c->created_at->format('M d, Y') : '' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-success px-2 py-1">{{ ucfirst($c->status) }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('client.cases.details', $c->id) }}" class="btn-gold" style="font-size: 11px;">
                                                <i class="fas fa-eye mr-1"></i> {{ __('View') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-folder-open fa-2x mb-2 d-block text-secondary"></i>
                                        {{ __('No active cases on file.') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Invoices & Billing Table -->
        <div class="col-lg-5 mb-4">
            <div class="portal-card h-100">
                <div class="portal-card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-file-invoice mr-2"></i> {{ __('Invoices & Retainers') }}</span>
                    <a href="{{ route('client.invoices.index') }}" class="btn-portal-secondary" style="font-size: 11px;">{{ __('View All') }}</a>
                </div>
                <div class="table-responsive">
                    <table class="table-portal">
                        <thead>
                            <tr>
                                <th>{{ __('Invoice #') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($invoices) && $invoices->count() > 0)
                                @foreach($invoices->take(4) as $inv)
                                    <tr>
                                        <td>
                                            <strong class="text-white">{{ $inv->invoice_number }}</strong>
                                            <small class="text-muted d-block">{{ $inv->due_date ? date('M d, Y', strtotime($inv->due_date)) : '' }}</small>
                                        </td>
                                        <td>
                                            <strong class="text-warning">${{ number_format($inv->total_amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if(strtolower($inv->status) === 'paid')
                                                <span class="badge badge-success px-2 py-1">{{ __('Paid') }}</span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1">{{ __('Due') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('client.invoices.show', $inv->id) }}" class="btn-portal-secondary" style="font-size: 11px;">
                                                {{ __('Details') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-receipt fa-2x mb-2 d-block text-secondary"></i>
                                        {{ __('No invoices pending.') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
