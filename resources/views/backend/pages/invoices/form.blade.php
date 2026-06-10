@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('content')
    <div id="wrapper-content">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                    <a class="breadcrumb-item text-white" href="{{ route('admin.invoices.index') }}">{{ __('Client Invoices') }}</a>
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
                        <h6 class="card-title">{{ $invoice ? __('Edit Invoice #') . $invoice->invoice_number : __('Generate New Invoice') }}</h6>
                    </div>

                    <div class="card-body">
                        <form action="{{ $invoice ? route('admin.invoices.update', $invoice->id) : route('admin.invoices.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="client_id">{{ __('Client') }} <span class="text-danger">*</span></label>
                                    <select name="client_id" id="client_id" class="form-control" required>
                                        <option value="">-- {{ __('Select Client') }} --</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" @if(old('client_id', $invoice ? $invoice->client_id : '') == $client->id) selected @endif>{{ $client->name }} ({{ $client->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('client_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="case_id">{{ __('Linked Case') }}</label>
                                    <select name="case_id" id="case_id" class="form-control">
                                        <option value="">-- {{ __('No Case Linked') }} --</option>
                                        @foreach($cases as $caseItem)
                                            <option value="{{ $caseItem->id }}" @if(old('case_id', $invoice ? $invoice->case_id : '') == $caseItem->id) selected @endif>{{ $caseItem->case_number }} - {{ $caseItem->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('case_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="amount">{{ __('Invoice Amount ($)') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="amount" step="0.01" min="0.01" class="form-control" required value="{{ old('amount', $invoice ? $invoice->amount : '') }}" placeholder="0.00">
                                    @error('amount') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="due_date">{{ __('Due Date') }} <span class="text-danger">*</span></label>
                                    <input type="date" name="due_date" id="due_date" class="form-control" required value="{{ old('due_date', $invoice ? ($invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') : '') }}">
                                    @error('due_date') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="status">{{ __('Status') }} <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="unpaid" @if(old('status', $invoice ? $invoice->status : 'unpaid') == 'unpaid') selected @endif>{{ __('Unpaid') }}</option>
                                        <option value="paid" @if(old('status', $invoice ? $invoice->status : 'unpaid') == 'paid') selected @endif>{{ __('Paid') }}</option>
                                        <option value="cancelled" @if(old('status', $invoice ? $invoice->status : 'unpaid') == 'cancelled') selected @endif>{{ __('Cancelled') }}</option>
                                    </select>
                                    @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">{{ __('Invoice Description / Line Items') }}</label>
                                <textarea name="description" id="description" rows="5" class="form-control" placeholder="Describe the invoice items, retainer fees or specific service hours...">{{ old('description', $invoice ? $invoice->description : '') }}</textarea>
                                @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mt-4 pt-2 border-top border-secondary">
                                <button type="submit" class="btn btn-primary btn-sm px-4"><i class="fas fa-save mr-1"></i> {{ __('Save Invoice') }}</button>
                                <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-sm ml-2">{{ __('Cancel') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
