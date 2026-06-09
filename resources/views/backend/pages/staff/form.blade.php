@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card card-dark bg-dark">
                    <div class="card-header">
                        <h6 class="card-title">{{ __($title) }}</h6>
                    </div>

                    <div class="card-body">
                        <form action="{{ $staff ? route('admin.staff.update', $staff->id) : route('admin.staff.store') }}" method="POST">
                            @csrf
                            
                            <h5 class="text-info mb-3 pb-2 border-bottom border-secondary">{{ __('Account & Authentication') }}</h5>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="name">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $staff ? $staff->name : '') }}">
                                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="email">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" required value="{{ old('email', $staff ? $staff->email : '') }}">
                                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="password">{{ __('Password') }} @if(!$staff) <span class="text-danger">*</span> @else <small class="text-muted">({{ __('Leave blank to keep current') }})</small> @endif</label>
                                    <input type="password" name="password" id="password" class="form-control" @if(!$staff) required @endif>
                                    @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" @if(!$staff) required @endif>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="phone">{{ __('Phone Number') }}</label>
                                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $staff ? $staff->phone : '') }}">
                                    @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="address">{{ __('Residential Address') }}</label>
                                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $staff ? $staff->address : '') }}">
                                    @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <h5 class="text-info mt-4 mb-3 pb-2 border-bottom border-secondary">{{ __('Employment Details') }}</h5>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="position">{{ __('Job Position') }}</label>
                                    <input type="text" name="position" id="position" class="form-control" placeholder="e.g. Senior CPA" value="{{ old('position', ($staff && $staff->staffDetail) ? $staff->staffDetail->position : '') }}">
                                    @error('position') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="hourly_rate">{{ __('Hourly Wage Rate ($)') }} <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="hourly_rate" id="hourly_rate" class="form-control" required value="{{ old('hourly_rate', ($staff && $staff->staffDetail) ? $staff->staffDetail->hourly_rate : '0.00') }}">
                                    @error('hourly_rate') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="hired_at">{{ __('Date Hired') }} <span class="text-danger">*</span></label>
                                    <input type="date" name="hired_at" id="hired_at" class="form-control" required value="{{ old('hired_at', ($staff && $staff->staffDetail && $staff->staffDetail->hired_at) ? $staff->staffDetail->hired_at->format('Y-m-d') : date('Y-m-d')) }}">
                                    @error('hired_at') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="pay_schedule">{{ __('Pay Schedule') }} <span class="text-danger">*</span></label>
                                    <select name="pay_schedule" id="pay_schedule" class="form-control" required>
                                        <option value="weekly" @if(old('pay_schedule', ($staff && $staff->staffDetail) ? $staff->staffDetail->pay_schedule : 'bi-weekly') == 'weekly') selected @endif>{{ __('Weekly') }}</option>
                                        <option value="bi-weekly" @if(old('pay_schedule', ($staff && $staff->staffDetail) ? $staff->staffDetail->pay_schedule : 'bi-weekly') == 'bi-weekly') selected @endif>{{ __('Bi-weekly') }}</option>
                                        <option value="monthly" @if(old('pay_schedule', ($staff && $staff->staffDetail) ? $staff->staffDetail->pay_schedule : 'bi-weekly') == 'monthly') selected @endif>{{ __('Monthly') }}</option>
                                        <option value="semi-monthly" @if(old('pay_schedule', ($staff && $staff->staffDetail) ? $staff->staffDetail->pay_schedule : 'bi-weekly') == 'semi-monthly') selected @endif>{{ __('Semi-monthly') }}</option>
                                    </select>
                                    @error('pay_schedule') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="next_pay_date">{{ __('Next Payday') }} <small class="text-muted">({{ __('Optional') }})</small></label>
                                    <input type="date" name="next_pay_date" id="next_pay_date" class="form-control" value="{{ old('next_pay_date', ($staff && $staff->staffDetail && $staff->staffDetail->next_pay_date) ? $staff->staffDetail->next_pay_date->format('Y-m-d') : '') }}">
                                    <small class="text-muted small">{{ __('Auto-calculated from hire date if blank') }}</small>
                                    @error('next_pay_date') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="assigned_officer_id">{{ __('Assigned Officer (Administrator)') }}</label>
                                    <select name="assigned_officer_id" id="assigned_officer_id" class="form-control">
                                        <option value="">-- {{ __('Select Officer') }} --</option>
                                        @foreach($officers as $officer)
                                            <option value="{{ $officer->id }}" @if(old('assigned_officer_id', ($staff && $staff->staffDetail) ? $staff->staffDetail->assigned_officer_id : '') == $officer->id) selected @endif>{{ $officer->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('assigned_officer_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="is_active">{{ __('Employment Status') }} <span class="text-danger">*</span></label>
                                    <select name="is_active" id="is_active" class="form-control" required>
                                        <option value="1" @if(old('is_active', ($staff && $staff->staffDetail) ? $staff->staffDetail->is_active : 1) == 1) selected @endif>{{ __('Active') }}</option>
                                        <option value="0" @if(old('is_active', ($staff && $staff->staffDetail) ? $staff->staffDetail->is_active : 1) == 0) selected @endif>{{ __('Inactive') }}</option>
                                    </select>
                                    @error('is_active') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <h5 class="text-info mt-4 mb-3 pb-2 border-bottom border-secondary">{{ __('Financial Ledger Settings') }}</h5>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="reimbursement">{{ __('Out of Pocket Reimbursements ($)') }}</label>
                                    <input type="number" step="0.01" name="reimbursement" id="reimbursement" class="form-control" value="{{ old('reimbursement', ($staff && $staff->staffDetail) ? $staff->staffDetail->reimbursement : '0.00') }}">
                                    @error('reimbursement') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="debt">{{ __('Debts/Funds Owed by Employee ($)') }}</label>
                                    <input type="number" step="0.01" name="debt" id="debt" class="form-control" value="{{ old('debt', ($staff && $staff->staffDetail) ? $staff->staffDetail->debt : '0.00') }}">
                                    @error('debt') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="bonus">{{ __('Current Cycle Bonuses ($)') }}</label>
                                    <input type="number" step="0.01" name="bonus" id="bonus" class="form-control" value="{{ old('bonus', ($staff && $staff->staffDetail) ? $staff->staffDetail->bonus : '0.00') }}">
                                    @error('bonus') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group mt-4 pt-2 border-top border-secondary">
                                <button type="submit" class="btn btn-primary btn-sm px-4"><i class="fas fa-save mr-1"></i> {{ __('Save Settings') }}</button>
                                <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary btn-sm ml-2">{{ __('Cancel') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
