@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .ledger-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .badge-reimbursement { background-color: #3498db; color: white; }
    .badge-bonus { background-color: #2ecc71; color: white; }
    .badge-debt { background-color: #e74c3c; color: white; }
    .ledger-summary-val {
        font-size: 1.5rem;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
    }
</style>
@endsection

@section('content')
    <div id="wrapper-content">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                    <a class="breadcrumb-item text-white" href="{{ route('admin.staff.index') }}">{{ __('Staff Directory') }}</a>
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

        <!-- Summary Statistics Row -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-dark text-white border-secondary">
                    <div class="card-body py-3">
                        <small class="text-muted text-uppercase font-weight-bold">{{ __('Out-of-Pocket Reimbursements') }}</small>
                        <div class="ledger-summary-val text-info">${{ number_format($staff->staffDetail ? $staff->staffDetail->reimbursement : 0, 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white border-secondary">
                    <div class="card-body py-3">
                        <small class="text-muted text-uppercase font-weight-bold">{{ __('Employee Debts / Advances') }}</small>
                        <div class="ledger-summary-val text-danger">-${{ number_format($staff->staffDetail ? $staff->staffDetail->debt : 0, 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white border-secondary">
                    <div class="card-body py-3">
                        <small class="text-muted text-uppercase font-weight-bold">{{ __('Net Balance Owed') }}</small>
                        @php
                            $reim = $staff->staffDetail ? $staff->staffDetail->reimbursement : 0;
                            $bonus = $staff->staffDetail ? $staff->staffDetail->bonus : 0;
                            $debt = $staff->staffDetail ? $staff->staffDetail->debt : 0;
                            $net = $reim + $bonus - $debt;
                        @endphp
                        <div class="ledger-summary-val @if($net >= 0) text-success @else text-danger @endif">
                            ${{ number_format($net, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Side: Add Ledger Entry Form -->
            <div class="col-lg-4 mb-4">
                <div class="card card-dark bg-dark">
                    <div class="card-header">
                        <h6 class="card-title">{{ __('Add Ledger Entry') }}</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.staff.ledger.store', $staff->id) }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="type">{{ __('Entry Type') }} <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="reimbursement">{{ __('Funds Owed by Employer (Out-of-Pocket Costs)') }}</option>
                                    <option value="debt">{{ __('Debts/Funds Owed by Employee (Damages, Advances)') }}</option>
                                    <option value="bonus">{{ __('Corporate Bonus') }}</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="amount">{{ __('Amount ($)') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="description">{{ __('Description / Reference') }} <span class="text-danger">*</span></label>
                                <input type="text" name="description" id="description" class="form-control" placeholder="e.g. Client site parking cost reimbursement" required>
                            </div>

                            <div class="form-group mb-4">
                                <label for="entry_date">{{ __('Transaction Date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="entry_date" id="entry_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm btn-block"><i class="fas fa-plus mr-1"></i> {{ __('Add to Ledger') }}</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side: Ledger Table -->
            <div class="col-lg-8">
                <div class="card card-dark bg-dark">
                    <div class="card-header">
                        <h6 class="card-title">{{ __('Transaction Ledger History') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive style-scroll">
                            <table class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th class="text-right">{{ __('Amount') }}</th>
                                        <th class="text-center">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($entries as $entry)
                                        <tr>
                                            <td>{{ $entry->entry_date ? $entry->entry_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>{{ $entry->description }}</td>
                                            <td>
                                                @if($entry->type === 'reimbursement')
                                                    <span class="ledger-badge badge-reimbursement">{{ __('Employer Owed') }}</span>
                                                @elseif($entry->type === 'bonus')
                                                    <span class="ledger-badge badge-bonus">{{ __('Bonus') }}</span>
                                                @elseif($entry->type === 'debt')
                                                    <span class="ledger-badge badge-debt">{{ __('Employee Debt') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-right font-weight-bold @if($entry->type === 'debt') text-danger @else text-success @endif">
                                                @if($entry->type === 'debt')-@else+@endif${{ number_format($entry->amount, 2) }}
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('admin.staff.ledger.destroy', [$staff->id, $entry->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ledger entry?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-danger" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4 small">{{ __('No ledger transactions recorded yet.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
