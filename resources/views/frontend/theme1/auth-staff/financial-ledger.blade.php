@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .ledger-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        margin-bottom: 25px;
    }
    .wage-indicator {
        font-size: 2.2rem;
        font-weight: 800;
        color: #27ae60;
        font-family: 'Montserrat', sans-serif;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Main Ledger Details -->
        <div class="col-lg-7">
            <!-- Reimbursement Ledger Card -->
            <div class="card ledger-card">
                <div class="card-body">
                    <h5 class="mb-4" style="font-weight: 700; color: #2c3e50;"><i class="fas fa-wallet mr-2 text-info"></i>{{ __('Reimbursement Ledger') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted font-weight-medium">{{ __('Funds Owed by Employer (Out-of-Pocket Expenses)') }}</td>
                                    <td class="font-weight-bold text-dark text-right">${{ number_format($staffDetail->reimbursement, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-medium">{{ __('Debts/Funds Owed by Employee') }}</td>
                                    <td class="font-weight-bold text-danger text-right">-${{ number_format($staffDetail->debt, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-medium">{{ __('Corporate Bonuses') }}</td>
                                    <td class="font-weight-bold text-success text-right">+${{ number_format($staffDetail->bonus, 2) }}</td>
                                </tr>
                                <tr class="bg-light font-weight-bold">
                                    <td>{{ __('Net Owed to Employee') }}</td>
                                    <td class="text-right @if($netOwed >= 0) text-success @else text-danger @endif">
                                        ${{ number_format($netOwed, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Ledger Transactions Card -->
            <div class="card ledger-card">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600; color: #34495e;"><i class="fas fa-list-alt mr-2 text-primary"></i>{{ __('Itemized Ledger History') }}</h6>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                            <thead>
                                <tr class="text-muted small">
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th class="text-right">{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->staffLedgerEntries()->orderBy('entry_date', 'desc')->orderBy('id', 'desc')->get() as $entry)
                                    <tr>
                                        <td>{{ $entry->entry_date ? $entry->entry_date->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $entry->description }}</td>
                                        <td>
                                            @if($entry->type === 'reimbursement')
                                                <span class="badge badge-info status-badge">{{ __('Employer Owed') }}</span>
                                            @elseif($entry->type === 'bonus')
                                                <span class="badge badge-success status-badge">{{ __('Bonus') }}</span>
                                            @elseif($entry->type === 'debt')
                                                <span class="badge badge-danger status-badge">{{ __('Employee Debt') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right font-weight-bold @if($entry->type === 'debt') text-danger @else text-success @endif">
                                            @if($entry->type === 'debt')-@else+@endif${{ number_format($entry->amount, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3 small">{{ __('No transactions recorded.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Earnings & Wage Calculator Card -->
            <div class="card ledger-card">
                <div class="card-body">
                    <h5 class="mb-4" style="font-weight: 700; color: #2c3e50;"><i class="fas fa-calculator mr-2 text-success"></i>{{ __('Earnings & Wage Calculator') }}</h5>
                    
                    <div class="row mb-4 bg-light p-3 rounded">
                        <div class="col-sm-6 text-center border-right">
                            <span class="text-muted small text-uppercase font-weight-bold d-block">{{ __('Accumulated Wages') }}</span>
                            <div class="wage-indicator">${{ number_format($totalEarned, 2) }}</div>
                            <small class="text-muted">{{ __('Total completed shifts') }}</small>
                        </div>
                        <div class="col-sm-6 text-center">
                            <span class="text-muted small text-uppercase font-weight-bold d-block">{{ __('Hourly Rate') }}</span>
                            <div class="wage-indicator text-primary">${{ number_format($staffDetail->hourly_rate, 2) }}<span style="font-size: 0.9rem; font-weight: 500;">/hr</span></div>
                            <small class="text-muted">{{ __('Pay Schedule: ') }} <strong>{{ ucfirst($staffDetail->pay_schedule) }}</strong></small>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-6">
                            <h6 class="mb-0" style="font-weight: 600; color: #34495e;">{{ __('Next Payday') }}</h6>
                        </div>
                        <div class="col-6 text-right font-weight-bold text-dark">
                            {{ $staffDetail->next_pay_date ? $staffDetail->next_pay_date->format('F d, Y') : __('Not Scheduled') }}
                        </div>
                    </div>

                    <h6 class="mb-3 mt-4" style="font-weight: 600; color: #34495e;">{{ __('Recent Shifts') }}</h6>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="text-muted small">
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('Rate') }}</th>
                                    <th class="text-right">{{ __('Wages') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->staffTimeLogs()->whereNotNull('clocked_out_at')->latest()->take(5)->get() as $log)
                                    <tr>
                                        <td>{{ $log->clocked_in_at->format('M d, Y') }}</td>
                                        <td>{{ round($log->duration_seconds / 3600, 2) }} hrs</td>
                                        <td>${{ number_format($log->hourly_rate_at_time, 2) }}/hr</td>
                                        <td class="text-right text-success font-weight-bold">${{ number_format($log->earned_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3 small">{{ __('No shifts recorded yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Payout Request -->
        <div class="col-lg-5">
            <!-- Request Payout Card -->
            <div class="card ledger-card">
                <div class="card-body">
                    <h5 class="mb-4" style="font-weight: 700; color: #2c3e50;"><i class="fas fa-hand-holding-usd mr-2 text-warning"></i>{{ __('Request Payout') }}</h5>
                    
                    <div class="alert alert-info small mb-4">
                        <i class="fas fa-info-circle mr-2"></i> {{ __('Use this section to request a payment once your pay schedule cycle is complete.') }}
                        <div class="mt-2 font-weight-bold">
                            {{ __('Total Claimable Balance: ') }} <span class="text-success">${{ number_format($claimableAmount, 2) }}</span>
                        </div>
                    </div>

                    <form action="{{ route('staff.request-payout') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-dark">{{ __('Payout Request Amount ($)') }}</label>
                            <input type="number" step="0.01" min="1" max="{{ $claimableAmount }}" name="amount" class="form-control" placeholder="e.g. 500.00" required value="{{ old('amount', $claimableAmount > 0 ? $claimableAmount : '') }}">
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-dark">{{ __('Notes / Comments') }}</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="{{ __('Optional notes for supervisor...') }}"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block text-uppercase font-weight-bold" @if($claimableAmount <= 0) disabled @endif style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none;">
                            {{ __('Submit Payout Request') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Payout Request History Card -->
            <div class="card ledger-card">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600; color: #34495e;">{{ __('Request History') }}</h6>
                    
                    <div class="list-group list-group-flush">
                        @forelse($payoutRequests as $request)
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="font-weight-bold text-dark">${{ number_format($request->amount, 2) }}</span>
                                        <small class="text-muted d-block">{{ $request->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <div>
                                        @if($request->status === 'pending')
                                            <span class="badge badge-warning status-badge">{{ __('Pending') }}</span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge badge-info status-badge">{{ __('Approved') }}</span>
                                        @elseif($request->status === 'paid')
                                            <span class="badge badge-success status-badge">{{ __('Paid') }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($request->notes)
                                    <div class="mt-2 small text-muted font-italic bg-light p-2 rounded">
                                        "{{ $request->notes }}"
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-center text-muted small py-3 mb-0">{{ __('No payout requests submitted yet.') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
