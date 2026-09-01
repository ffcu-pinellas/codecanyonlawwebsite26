@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .vault-card {
        background: #161a23;
        border-radius: 12px;
        border: 1px solid #28303f;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        margin-bottom: 25px;
        color: #f1f5f9;
        overflow: hidden;
    }
    .vault-card .card-header {
        background: #1f2533;
        border-bottom: 1px solid #2e3849;
        color: #fecc56;
        font-weight: 700;
    }
    .status-badge-pending { background-color: rgba(245, 158, 11, 0.15); color: #fecc56; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    .status-badge-active { background-color: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    .status-badge-suspended { background-color: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    .status-badge-resolved { background-color: rgba(14, 165, 233, 0.15); color: #38bdf8; border: 1px solid rgba(14, 165, 233, 0.3); font-weight: 600; font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    
    .attorney-info {
        background: #11151e;
        border-radius: 12px;
        padding: 15px;
        border: 1px solid #28303f;
    }
    .attorney-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
        border: 2px solid #fecc56;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    .file-icon {
        font-size: 1.5rem;
        margin-right: 12px;
    }
    .upload-box {
        border: 2px dashed #334155;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        background: #11151e;
        transition: border-color 0.3s;
    }
    .upload-box:hover {
        border-color: #fecc56;
    }
    .lifecycle-step {
        flex: 1;
        text-align: center;
        position: relative;
        padding: 10px 5px;
    }
    .lifecycle-step .step-number {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #1c212c;
        border: 2px solid #374151;
        color: #94a3b8;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-size: 13px;
    }
    .lifecycle-step.completed .step-number {
        background: #22c55e;
        border-color: #22c55e;
        color: white;
    }
    .lifecycle-step.active .step-number {
        background: #fecc56;
        border-color: #fecc56;
        color: #0f172a;
        box-shadow: 0 0 12px rgba(254, 204, 86, 0.5);
    }
    .lifecycle-step .step-title {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .lifecycle-step.active .step-title {
        color: #fecc56;
        font-weight: 700;
    }
    .lifecycle-step.completed .step-title {
        color: #22c55e;
    }
    .settlement-stat-box {
        background: #11151e;
        border: 1px solid #28303f;
        border-radius: 8px;
        padding: 14px;
        text-align: center;
    }
    .form-dark .form-control, .vault-card .form-control {
        background: #0f172a !important;
        border: 1px solid #334155 !important;
        color: #ffffff !important;
    }
    .form-dark .form-control:focus, .vault-card .form-control:focus {
        border-color: #fecc56 !important;
        box-shadow: 0 0 0 2px rgba(254, 204, 86, 0.2) !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Back Navigation -->
    <div class="mb-4">
        <a href="{{ route('client.cases.index') }}" class="text-primary font-weight-bold"><i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Case Directory') }}</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Case Lifecycle Stages & Progress Bar -->
    <div class="card vault-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-tasks text-warning mr-2"></i> {{ __('Case Progression Lifecycle') }}</h5>
                <small class="text-muted">{{ __('Current procedural phase for Case Reference') }} #{{ $case->case_number }}</small>
            </div>
            <span class="badge badge-warning px-3 py-2 font-weight-bold" style="font-size: 13px;">
                {{ $case->progress_percent ?: 25 }}% {{ __('Completed') }}
            </span>
        </div>

        <div class="progress mb-4" style="height: 8px; border-radius: 4px; background: #e2e8f0;">
            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $case->progress_percent ?: 25 }}%;" aria-valuenow="{{ $case->progress_percent ?: 25 }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        @php $currentStage = $case->lifecycle_stage ?: 1; @endphp
        <div class="d-flex justify-content-between flex-wrap">
            <div class="lifecycle-step {{ $currentStage > 1 ? 'completed' : ($currentStage == 1 ? 'active' : '') }}">
                <div class="step-number">{{ $currentStage > 1 ? '✓' : '1' }}</div>
                <div class="step-title">{{ __('Intake & File Review') }}</div>
            </div>
            <div class="lifecycle-step {{ $currentStage > 2 ? 'completed' : ($currentStage == 2 ? 'active' : '') }}">
                <div class="step-number">{{ $currentStage > 2 ? '✓' : '2' }}</div>
                <div class="step-title">{{ __('Audit & Research') }}</div>
            </div>
            <div class="lifecycle-step {{ $currentStage > 3 ? 'completed' : ($currentStage == 3 ? 'active' : '') }}">
                <div class="step-number">{{ $currentStage > 3 ? '✓' : '3' }}</div>
                <div class="step-title">{{ __('Court & Tax Filings') }}</div>
            </div>
            <div class="lifecycle-step {{ $currentStage > 4 ? 'completed' : ($currentStage == 4 ? 'active' : '') }}">
                <div class="step-number">{{ $currentStage > 4 ? '✓' : '4' }}</div>
                <div class="step-title">{{ __('Settlement & Escrow') }}</div>
            </div>
            <div class="lifecycle-step {{ $currentStage >= 5 ? 'completed' : '' }}">
                <div class="step-number">{{ $currentStage >= 5 ? '✓' : '5' }}</div>
                <div class="step-title">{{ __('Final Resolution') }}</div>
            </div>
        </div>
    </div>

    <!-- Retainer & Trust Settlement Hub (If configured) -->
    @if($case->show_settlement_escrow || $case->settlement)
        @php $settle = $case->settlement; @endphp
        <div class="card vault-card p-4 mb-4" style="border-left: 4px solid #10b981;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-university text-success mr-2"></i> {{ $case->settlement_title ?: __('Retainer & Trust Settlement Hub') }}
                    </h5>
                    <small class="text-muted">{{ __('Verified legal trust depository, retainer funds, and settlement disbursement schedule.') }}</small>
                </div>
                <span class="badge badge-success px-3 py-2 font-weight-bold">{{ $settle?->status ?: __('Funds Secured in Trust') }}</span>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 mb-2">
                    <div class="settlement-stat-box">
                        <small class="text-muted text-uppercase d-block font-weight-bold" style="font-size: 11px;">{{ __('Gross Settlement / Retainer') }}</small>
                        <h4 class="font-weight-bold text-dark mb-0 mt-1">${{ number_format($settle?->gross_amount ?? $case->settled_amount ?? 0, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="settlement-stat-box">
                        <small class="text-muted text-uppercase d-block font-weight-bold" style="font-size: 11px;">{{ __('Legal & Advisory Fee') }} ({{ $settle?->legal_fee_percent ?? 10 }}%)</small>
                        <h4 class="font-weight-bold text-danger mb-0 mt-1">-${{ number_format($settle?->legal_fee_amount ?? 0, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="settlement-stat-box">
                        <small class="text-muted text-uppercase d-block font-weight-bold" style="font-size: 11px;">{{ __('Court & Filing Expenses') }}</small>
                        <h4 class="font-weight-bold text-muted mb-0 mt-1">-${{ number_format($settle?->expenses_amount ?? 0, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="settlement-stat-box" style="background: #ecfdf5; border-color: #a7f3d0;">
                        <small class="text-success text-uppercase d-block font-weight-bold" style="font-size: 11px;">{{ __('Net Client Disbursement') }}</small>
                        <h4 class="font-weight-bold text-success mb-0 mt-1">${{ number_format($settle?->net_client_payout ?? ($settle?->gross_amount ?? 0), 2) }}</h4>
                    </div>
                </div>
            </div>

            <!-- Confirmation Action Form if not confirmed -->
            @if($settle && !$settle->client_confirmed_at)
                <div class="p-3 bg-light rounded mt-2 border">
                    <h6 class="font-weight-bold text-dark mb-2"><i class="fas fa-lock mr-1 text-warning"></i> {{ __('Authorize Settlement Disbursement (Requires 4-Digit Security PIN)') }}</h6>
                    <form action="{{ route('client.cases.confirm-settlement', $case->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 form-group mb-2">
                                <label class="small font-weight-bold">{{ __('Disbursement Method') }} <span class="text-danger">*</span></label>
                                <select name="payout_method" class="form-control form-control-sm" required>
                                    <option value="Wire Transfer / ACH (Direct Deposit)">{{ __('Wire Transfer / ACH Direct Deposit') }}</option>
                                    <option value="Certified Trust Check">{{ __('Certified Trust Check (Mail)') }}</option>
                                    <option value="Client Escrow Depository">{{ __('Client Escrow Depository Account') }}</option>
                                </select>
                            </div>
                            <div class="col-md-5 form-group mb-2">
                                <label class="small font-weight-bold">{{ __('Destination Account / Routing Details') }} <span class="text-danger">*</span></label>
                                <input type="text" name="payout_destination_details" class="form-control form-control-sm" required placeholder="Bank Name, Routing #, Account #, Beneficial Owner Name">
                            </div>
                            <div class="col-md-3 form-group mb-2">
                                <label class="small font-weight-bold">{{ __('4-Digit PIN') }} <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="password" name="pin" maxlength="4" class="form-control" required placeholder="••••" inputmode="numeric">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-success font-weight-bold px-3">
                                            <i class="fas fa-check mr-1"></i> {{ __('Confirm') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @elseif($settle && $settle->client_confirmed_at)
                <div class="alert alert-success mb-0 py-2" style="font-size: 13px;">
                    <i class="fas fa-check-circle mr-1"></i> {{ __('Disbursement instructions confirmed by client on') }} {{ $settle->client_confirmed_at->format('M d, Y H:i') }}. (Digital Signature Hash: <code>{{ substr($settle->client_signature_hash, 0, 16) }}...</code>)
                </div>
            @endif
        </div>
    @endif

    <!-- Audit & Financial Schedules Ledger (If configured) -->
    @if($case->show_financial_schedule || $case->financialSchedules->isNotEmpty())
        <div class="card vault-card p-4 mb-4">
            <h5 class="font-weight-bold text-dark mb-1">
                <i class="fas fa-file-invoice text-info mr-2"></i> {{ $case->schedule_title ?: __('Audit & Financial Schedule Ledger') }}
            </h5>
            <small class="text-muted d-block mb-3">{{ __('Itemized record of audited assets, liabilities, tax filings, and claimed ledger items.') }}</small>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="small font-weight-bold">{{ __('Item Description') }}</th>
                            <th class="small font-weight-bold">{{ __('Category') }}</th>
                            <th class="small font-weight-bold">{{ __('Ref Code') }}</th>
                            <th class="small font-weight-bold">{{ __('Amount') }}</th>
                            <th class="small font-weight-bold">{{ __('Audit Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($case->financialSchedules as $sched)
                            <tr>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $sched->item_description }}</div>
                                    @if($sched->notes)<small class="text-muted">{{ $sched->notes }}</small>@endif
                                </td>
                                <td><span class="badge badge-secondary">{{ $sched->item_category }}</span></td>
                                <td><code>{{ $sched->reference_code ?: '-' }}</code></td>
                                <td class="font-weight-bold text-dark">${{ number_format($sched->amount, 2) }} {{ $sched->currency }}</td>
                                <td><span class="badge badge-success px-2 py-1">{{ $sched->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3 small">{{ __('No financial schedule items registered for this case yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Court & Regulatory Jurisdictions Tracker (If configured) -->
    @if($case->show_jurisdiction_tracker || $case->jurisdictions->isNotEmpty())
        <div class="card vault-card p-4 mb-4">
            <h5 class="font-weight-bold text-dark mb-1">
                <i class="fas fa-gavel text-primary mr-2"></i> {{ $case->jurisdiction_title ?: __('Court & Regulatory Jurisdictions') }}
            </h5>
            <small class="text-muted d-block mb-3">{{ __('Official legal filings, court venues, and active proceedings on record.') }}</small>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="small font-weight-bold">{{ __('Jurisdiction / Court') }}</th>
                            <th class="small font-weight-bold">{{ __('Action / Petition Type') }}</th>
                            <th class="small font-weight-bold">{{ __('Docket #') }}</th>
                            <th class="small font-weight-bold">{{ __('Filing Date') }}</th>
                            <th class="small font-weight-bold">{{ __('Filing Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($case->jurisdictions as $juris)
                            <tr>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $juris->jurisdiction_name }}</div>
                                    @if($juris->court_venue)<small class="text-muted">{{ $juris->court_venue }}</small>@endif
                                </td>
                                <td>{{ $juris->action_type }}</td>
                                <td><code>{{ $juris->docket_number ?: '-' }}</code></td>
                                <td>{{ $juris->filing_date ? $juris->filing_date->format('M d, Y') : '-' }}</td>
                                <td><span class="badge badge-info px-2 py-1">{{ $juris->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3 small">{{ __('No jurisdictional filings recorded yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Case Information details -->
        <div class="col-lg-5 mb-4">
            <div class="card vault-card p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="text-primary font-weight-bold" style="font-size: 0.85rem;">{{ $case->case_number }}</span>
                    <span class="status-badge-{{ $case->status }}">{{ ucfirst($case->status) }}</span>
                </div>
                <h4 class="font-weight-bold text-dark mb-3">{{ $case->title }}</h4>
                <p class="text-muted small mb-4" style="line-height: 1.6;">
                    {{ $case->description ?: __('No detailed description has been provided for this case representation.') }}
                </p>

                <div class="attorney-info d-flex align-items-center mb-4">
                    @if($case->attorney)
                        @if($case->attorney->attorney && $case->attorney->attorney->image)
                            <img src="{{ asset('upload/attorneys/' . $case->attorney->attorney->image) }}" class="attorney-avatar">
                        @else
                            <div class="attorney-avatar bg-primary text-white d-flex align-items-center justify-content-center font-weight-bold" style="width:45px; height:45px;">
                                {{ substr($case->attorney->name, 0, 2) }}
                            </div>
                        @endif
                        <div>
                            <small class="text-muted d-block uppercase font-weight-bold" style="font-size: 0.6rem;">{{ __('Assigned Legal Officer') }}</small>
                            <span class="font-weight-bold text-dark">{{ $case->attorney->name }}</span>
                            <small class="text-muted d-block">{{ $case->attorney->email }}</small>
                        </div>
                    @else
                        <div class="attorney-avatar bg-secondary text-white d-flex align-items-center justify-content-center font-weight-bold" style="width:45px; height:45px;">
                            ?
                        </div>
                        <div>
                            <small class="text-muted d-block uppercase font-weight-bold" style="font-size: 0.6rem;">{{ __('Assigned Legal Officer') }}</small>
                            <span class="text-muted font-weight-bold">{{ __('No Attorney Assigned') }}</span>
                        </div>
                    @endif
                </div>

                <div class="row pt-2 border-top border-light">
                    <div class="col-6">
                        <small class="text-muted d-block uppercase font-weight-bold" style="font-size: 0.6rem;">{{ __('Court/Due Date') }}</small>
                        <span class="text-dark font-weight-semibold small">
                            @if($case->court_date)
                                <i class="far fa-calendar-alt text-primary mr-1"></i> {{ $case->court_date->format('M d, Y h:i A') }}
                            @else
                                {{ __('Not Scheduled') }}
                            @endif
                        </span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block uppercase font-weight-bold" style="font-size: 0.6rem;">{{ __('Created Date') }}</small>
                        <span class="text-dark font-weight-semibold small">
                            <i class="far fa-clock text-primary mr-1"></i> {{ $case->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Case Timeline Feed -->
            <div class="card vault-card p-4">
                <h5 class="font-weight-bold text-dark mb-3" style="font-size: 1.05rem;"><i class="fas fa-route text-info mr-2"></i> {{ __('Case Progress Timeline') }}</h5>
                @if($case->milestones->isEmpty())
                    <div class="text-center py-4 text-muted small">
                        <i class="fas fa-info-circle mb-1"></i>
                        <p class="mb-0">{{ __('No progress milestones registered for this case yet.') }}</p>
                    </div>
                @else
                    <div class="timeline-container pl-3 pt-2">
                        @foreach($case->milestones as $milestone)
                            <div class="timeline-item mb-4 position-relative" style="border-left: 2px solid {{ $milestone->status === 'completed' ? '#2ecc71' : ($milestone->status === 'active' ? '#3498db' : '#dee2e6') }}; padding-left: 20px; margin-left: 8px;">
                                <div class="timeline-dot" style="position: absolute; left: -7px; top: 0; width: 12px; height: 12px; border-radius: 50%; background-color: {{ $milestone->status === 'completed' ? '#2ecc71' : ($milestone->status === 'active' ? '#3498db' : '#95a5a6') }}; border: 2px solid white; box-shadow: 0 0 0 2px {{ $milestone->status === 'completed' ? '#e8f5e9' : ($milestone->status === 'active' ? '#dff9fb' : '#f1f3f5') }}; {{ $milestone->status === 'active' ? 'animation: pulse-active 2s infinite;' : '' }}"></div>
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="font-weight-bold text-dark mb-0 small" style="font-size: 0.85rem;">{{ $milestone->title }}</h6>
                                    @if($milestone->milestone_date)
                                        <span class="text-muted" style="font-size: 0.7rem;">{{ $milestone->milestone_date->format('M d, Y') }}</span>
                                    @endif
                                </div>
                                @if($milestone->description)
                                    <p class="text-muted mb-0 mt-1" style="font-size: 0.75rem; line-height: 1.4;">{{ $milestone->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Upload Box Form -->
            <div class="card vault-card p-4">
                <h5 class="font-weight-bold text-dark mb-3" style="font-size: 1.05rem;"><i class="fas fa-cloud-upload-alt text-primary mr-2"></i> {{ __('Upload Documents to Vault') }}</h5>
                <p class="text-muted small mb-4">{{ __('Upload tax documents, pay stubs, identity proof or contract agreements directly and securely to this case vault.') }}</p>
                
                <form action="{{ route('client.cases.upload-document', $case->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="title" class="font-weight-semibold text-dark small">{{ __('Document Label/Title (Optional)') }}</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Identity Proof / Form W-2 (Defaults to filename if blank)">
                        @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-semibold text-dark small d-block">{{ __('Select Documents') }} <span class="text-danger">*</span></label>
                        <div class="upload-box">
                            <input type="file" name="files[]" id="file" class="form-control-file d-none" multiple required onchange="let count = this.files.length; if(count > 1) { $('#file-selected-name').text(count + ' files selected'); } else { $('#file-selected-name').text(this.files[0].name); }">
                            <label for="file" style="cursor:pointer;" class="mb-0">
                                <i class="fas fa-file-upload fa-2x text-muted mb-2"></i>
                                <span class="d-block font-weight-semibold text-primary small">{{ __('Click to browse files') }}</span>
                                <span class="d-block text-muted small mt-1" style="font-size: 0.75rem;">{{ __('Supported: PDF, PNG, JPG, DOCX, XLSX (Max 20MB per file, can select multiple)') }}</span>
                            </label>
                            <span id="file-selected-name" class="d-block text-success font-weight-semibold small mt-2"></span>
                        </div>
                        @error('files') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2"><i class="fas fa-upload mr-1"></i> {{ __('Upload & Lock Files') }}</button>
                </form>
            </div>
        </div>

        <!-- Document Vault List Table -->
        <div class="col-lg-7 mb-4">
            <div class="card vault-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-shield-alt text-success mr-2"></i> {{ __('Secure Document Vault') }}</h5>
                    <span class="badge badge-pill badge-success font-weight-bold">{{ $case->documents->count() }} {{ __('Files') }}</span>
                </div>

                @if($case->documents->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                        <h6 class="text-dark font-weight-bold">{{ __('Your secure vault is empty.') }}</h6>
                        <p class="text-muted small px-3">{{ __('Admins, legal officers, and you can upload filings or proof of identity to this secure container.') }}</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Document') }}</th>
                                    <th>{{ __('Uploaded By') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th class="text-right">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($case->documents as $doc)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if(in_array(strtolower($doc->file_type), ['pdf']))
                                                    <i class="far fa-file-pdf text-danger file-icon"></i>
                                                @elseif(in_array(strtolower($doc->file_type), ['png', 'jpg', 'jpeg']))
                                                    <i class="far fa-file-image text-info file-icon"></i>
                                                @elseif(in_array(strtolower($doc->file_type), ['doc', 'docx']))
                                                    <i class="far fa-file-word text-primary file-icon"></i>
                                                @else
                                                    <i class="far fa-file-alt text-secondary file-icon"></i>
                                                @endif
                                                <div>
                                                    <span class="font-weight-semibold text-dark d-block" style="font-size: 0.9rem;">{{ $doc->title }}</span>
                                                    <small class="text-muted">{{ number_format($doc->file_size / 1024, 1) }} KB</small>
                                                    @if($doc->is_client_uploaded)
                                                        <span class="badge badge-warning ml-1" style="font-size:0.6rem;">{{ __('My Upload') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="small font-weight-medium text-dark">{{ $doc->uploader->name }}</span>
                                        </td>
                                        <td>
                                            <span class="small text-muted">{{ $doc->created_at->format('M d, Y') }}</span>
                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                @if(in_array(strtolower($doc->file_type), ['pdf', 'jpg', 'jpeg', 'png']))
                                                    <button type="button" class="btn btn-outline-success btn-sm preview-btn mr-1" data-url="{{ route('client.documents.preview', $doc->id) }}" data-title="{{ $doc->title }}"><i class="fas fa-eye"></i></button>
                                                @endif
                                                <a href="{{ route('client.documents.download', $doc->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-download"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Document Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content bg-white text-dark">
            <div class="modal-header border-light">
                <h5 class="modal-title font-weight-bold text-dark" id="previewModalLabel">{{ __('Document Preview') }}</h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" style="height: 70vh;">
                <iframe id="previewFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    (function($) {
        "use strict";
        $('.preview-btn').on('click', function() {
            const url = $(this).data('url');
            const title = $(this).data('title');
            $('#previewModalLabel').text(title);
            $('#previewFrame').attr('src', url);
            $('#previewModal').modal('show');
        });

        // Clean frame on close
        $('#previewModal').on('hidden.bs.modal', function () {
            $('#previewFrame').attr('src', '');
        });
    })(jQuery);
</script>
@endsection
