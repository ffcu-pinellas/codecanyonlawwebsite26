@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'laravel'). ' | '.$title)

@section('page-css')
<style>
    .kyc-upload-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .badge-approved { background: #dcfce7; color: #166534; font-weight: bold; padding: 4px 8px; border-radius: 4px; }
    .badge-pending { background: #fef3c7; color: #92400e; font-weight: bold; padding: 4px 8px; border-radius: 4px; }
    .badge-resubmit { background: #fee2e2; color: #991b1b; font-weight: bold; padding: 4px 8px; border-radius: 4px; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h4 class="font-weight-bold text-dark mb-1">
                <i class="fas fa-file-invoice-dollar text-warning mr-2"></i> {{ __('Client Financial & Legal Document Intake') }}
            </h4>
            <p class="text-muted small mb-0">{{ __('Upload required tax schedules, corporate entity records, and verification documents for Attorney & CPA review.') }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success font-weight-bold mb-4">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Upload Form Card -->
        <div class="col-lg-5 mb-4">
            <div class="kyc-upload-card">
                <h5 class="font-weight-bold text-dark mb-3">
                    <i class="fas fa-cloud-upload-alt text-primary mr-1"></i> {{ __('Submit Financial & Legal Files') }}
                </h5>

                <form action="{{ route('client.kyc.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="document_type" class="font-weight-bold text-dark small">{{ __('Document Category') }} <span class="text-danger">*</span></label>
                        <select name="document_type" id="document_type" class="form-control" required>
                            <option value="Tax Return (W-2 / 1099 / 1040)">{{ __('Tax Return (W-2 / 1099 / 1040)') }}</option>
                            <option value="Corporate Articles / Operating Agreement">{{ __('Corporate Articles / Operating Agreement') }}</option>
                            <option value="Government-Issued Identification">{{ __('Government-Issued Identification (Passport / DL)') }}</option>
                            <option value="Bank / Brokerage Statement">{{ __('Bank / Brokerage Financial Statement') }}</option>
                            <option value="Proof of Address / Utility">{{ __('Proof of Address / Utility Bill') }}</option>
                            <option value="IRS / State Tax Notice">{{ __('IRS / State Tax Notice') }}</option>
                            <option value="Other Privileged Document">{{ __('Other Privileged Document') }}</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="file_title" class="font-weight-bold text-dark small">{{ __('Document Title / Description') }} <span class="text-danger">*</span></label>
                        <input type="text" name="file_title" id="file_title" class="form-control" required placeholder="e.g. 2024 Form 1040 Final Filing">
                    </div>

                    @if(count($cases) > 0)
                        <div class="form-group mb-3">
                            <label for="case_id" class="font-weight-bold text-dark small">{{ __('Associate with Active Case (Optional)') }}</label>
                            <select name="case_id" id="case_id" class="form-control">
                                <option value="">-- {{ __('General Client File') }} --</option>
                                @foreach($cases as $c)
                                    <option value="{{ $c->id }}">{{ $c->case_number }} - {{ $c->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group mb-4">
                        <label for="document_file" class="font-weight-bold text-dark small">{{ __('Select File') }} (PDF, DOCX, JPG, PNG, XLSX - Max 15MB) <span class="text-danger">*</span></label>
                        <input type="file" name="document_file" id="document_file" class="form-control-file" required>
                    </div>

                    <button type="submit" class="btn btn-warning btn-block font-weight-bold text-dark py-2 shadow-sm">
                        <i class="fas fa-upload mr-1"></i> {{ __('Upload for Confidential Review') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Uploaded Documents History Table -->
        <div class="col-lg-7 mb-4">
            <div class="kyc-upload-card">
                <h5 class="font-weight-bold text-dark mb-3">
                    <i class="fas fa-folder-open text-warning mr-1"></i> {{ __('Submitted Documents & Review Status') }}
                </h5>

                @if(count($documents) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="small font-weight-bold">{{ __('Document') }}</th>
                                    <th class="small font-weight-bold">{{ __('Category') }}</th>
                                    <th class="small font-weight-bold">{{ __('Status') }}</th>
                                    <th class="small font-weight-bold">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $doc)
                                    <tr>
                                        <td>
                                            <div class="font-weight-bold text-dark">{{ $doc->file_title }}</div>
                                            <small class="text-muted">{{ $doc->file_size }} &bull; {{ $doc->created_at ? $doc->created_at->format('M d, Y') : '' }}</small>
                                            @if($doc->reviewer_notes)
                                                <div class="small text-info mt-1"><i class="fas fa-comment mr-1"></i> {{ $doc->reviewer_notes }}</div>
                                            @endif
                                        </td>
                                        <td><span class="badge badge-secondary">{{ $doc->document_type }}</span></td>
                                        <td>
                                            @if($doc->status === 'Approved')
                                                <span class="badge-approved"><i class="fas fa-check-circle mr-1"></i> {{ __('Approved') }}</span>
                                            @elseif($doc->status === 'Needs Resubmission')
                                                <span class="badge-resubmit"><i class="fas fa-exclamation-circle mr-1"></i> {{ __('Needs Resubmission') }}</span>
                                            @else
                                                <span class="badge-pending"><i class="fas fa-hourglass-half mr-1"></i> {{ __('Pending Review') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ asset($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary" download>
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-file-upload fa-3x mb-3 text-secondary"></i>
                        <h6>{{ __('No Documents Uploaded Yet') }}</h6>
                        <p class="small mb-0">{{ __('Use the form on the left to submit your financial, tax, and legal identification files.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
