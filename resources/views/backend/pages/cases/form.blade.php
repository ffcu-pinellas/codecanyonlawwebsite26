@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('content')
    <div id="wrapper-content">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                    <a class="breadcrumb-item text-white" href="{{ route('admin.cases.index') }}">{{ __('Case Directory') }}</a>
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

        <div class="row">
            <!-- Case Information Form -->
            <div class="col-lg-6">
                <div class="card card-dark bg-dark">
                    <div class="card-header">
                        <h6 class="card-title">{{ $case ? __('Edit Case #') . $case->case_number : __('Create New Case') }}</h6>
                    </div>

                    <div class="card-body">
                        <form action="{{ $case ? route('admin.cases.update', $case->id) : route('admin.cases.store') }}" method="POST">
                            @csrf
                            
                            <div class="form-group">
                                <label for="title">{{ __('Case Title') }} <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $case ? $case->title : '') }}" placeholder="e.g. Tax Audit Audit-2026 Representation">
                                @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="client_id">{{ __('Client') }} <span class="text-danger">*</span></label>
                                    <select name="client_id" id="client_id" class="form-control" required>
                                        <option value="">-- {{ __('Select Client') }} --</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" @if(old('client_id', $case ? $case->client_id : '') == $client->id) selected @endif>{{ $client->name }} ({{ $client->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('client_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="attorney_id">{{ __('Assigned Attorney/Officer') }}</label>
                                    <select name="attorney_id" id="attorney_id" class="form-control">
                                        <option value="">-- {{ __('Unassigned') }} --</option>
                                        @foreach($attorneys as $attorney)
                                            <option value="{{ $attorney->id }}" @if(old('attorney_id', $case ? $case->attorney_id : '') == $attorney->id) selected @endif>{{ $attorney->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('attorney_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="court_date">{{ __('Court/Due Date') }}</label>
                                    <input type="datetime-local" name="court_date" id="court_date" class="form-control" value="{{ old('court_date', ($case && $case->court_date) ? $case->court_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('court_date') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="status">{{ __('Case Status') }} <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="pending" @if(old('status', $case ? $case->status : 'pending') == 'pending') selected @endif>{{ __('Pending') }}</option>
                                        <option value="active" @if(old('status', $case ? $case->status : 'pending') == 'active') selected @endif>{{ __('Active') }}</option>
                                        <option value="suspended" @if(old('status', $case ? $case->status : 'pending') == 'suspended') selected @endif>{{ __('Suspended') }}</option>
                                        <option value="resolved" @if(old('status', $case ? $case->status : 'pending') == 'resolved') selected @endif>{{ __('Resolved') }}</option>
                                    </select>
                                    @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">{{ __('Case Description') }}</label>
                                <textarea name="description" id="description" rows="5" class="form-control" placeholder="Describe the details, goals and timeline of representation...">{{ old('description', $case ? $case->description : '') }}</textarea>
                                @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mt-4 pt-2 border-top border-secondary">
                                <button type="submit" class="btn btn-primary btn-sm px-4"><i class="fas fa-save mr-1"></i> {{ __('Save Case Details') }}</button>
                                <a href="{{ route('admin.cases.index') }}" class="btn btn-secondary btn-sm ml-2">{{ __('Cancel') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Document Vault Section (Visible only when editing) -->
            @if($case)
                <div class="col-lg-6" id="vault">
                    <!-- Upload File Form -->
                    <div class="card card-dark bg-dark mb-4">
                        <div class="card-header">
                            <h6 class="card-title"><i class="fas fa-upload mr-1"></i> {{ __('Upload File to Secure Document Vault') }}</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.cases.upload-document', $case->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="doc_title">{{ __('Document Title (Optional)') }}</label>
                                    <input type="text" name="title" id="doc_title" class="form-control" placeholder="e.g. Initial Intake Form (Defaults to filename if blank)">
                                </div>
                                <div class="form-group">
                                    <label for="doc_file">{{ __('Select Files') }} <span class="text-danger">*</span></label>
                                    <input type="file" name="files[]" id="doc_file" class="form-control-file" multiple required>
                                    <small class="text-muted">{{ __('Supported: PDF, Images, Word, Excel (Max 20MB per file, can select multiple)') }}</small>
                                </div>
                                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus mr-1"></i> {{ __('Upload to Vault') }}</button>
                            </form>
                        </div>
                    </div>

                    <!-- Vault List Table -->
                    <div class="card card-dark bg-dark">
                        <div class="card-header">
                            <h6 class="card-title"><i class="fas fa-folder-open mr-1"></i> {{ __('Secure Document Vault') }}</h6>
                        </div>
                        <div class="card-body">
                            @if($case->documents->isEmpty())
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <p>{{ __('No documents uploaded to this case vault yet.') }}</p>
                                </div>
                            @else
                                <div class="table-responsive style-scroll">
                                    <table class="table table-striped table-bordered table-dark small">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Title') }}</th>
                                                <th>{{ __('Size') }}</th>
                                                <th>{{ __('Uploaded By') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($case->documents as $doc)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $doc->title }}</strong>
                                                        @if($doc->is_client_uploaded)
                                                            <span class="badge badge-warning ml-1">{{ __('Client Upload') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($doc->file_size / 1024, 1) }} KB</td>
                                                    <td>{{ $doc->uploader->name }}</td>
                                                    <td>{{ $doc->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if(in_array(strtolower($doc->file_type), ['pdf', 'jpg', 'jpeg', 'png']))
                                                                <button type="button" class="btn btn-xs btn-outline-success m-1 preview-btn" data-url="{{ route('admin.cases.document.preview', $doc->id) }}" data-title="{{ $doc->title }}"><i class="fas fa-eye"></i></button>
                                                            @endif
                                                            
                                                            <form action="{{ route('admin.cases.destroy-document', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this document from the vault?') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-xs btn-outline-danger m-1"><i class="fas fa-trash"></i></button>
                                                            </form>
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
                    
                    <!-- Case Milestones Section -->
                    <div class="card card-dark bg-dark mt-4">
                        <div class="card-header">
                            <h6 class="card-title"><i class="fas fa-route mr-1"></i> {{ __('Case Progress Milestones') }}</h6>
                        </div>
                        <div class="card-body">
                            <!-- Add Milestone Form -->
                            <form action="{{ route('admin.cases.add-milestone', $case->id) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="milestone_title" class="small">{{ __('Milestone Title') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="milestone_title" class="form-control form-control-sm text-white bg-dark border-secondary" required placeholder="e.g. Filed Complaint, Discovery Request">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="milestone_date" class="small">{{ __('Target Date') }}</label>
                                        <input type="date" name="milestone_date" id="milestone_date" class="form-control form-control-sm text-white bg-dark border-secondary">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="milestone_status" class="small">{{ __('Status') }} <span class="text-danger">*</span></label>
                                        <select name="status" id="milestone_status" class="form-control form-control-sm text-white bg-dark border-secondary" required>
                                            <option value="pending">{{ __('Pending') }}</option>
                                            <option value="active">{{ __('Active') }}</option>
                                            <option value="completed">{{ __('Completed') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="milestone_desc" class="small">{{ __('Brief Description') }}</label>
                                        <input type="text" name="description" id="milestone_desc" class="form-control form-control-sm text-white bg-dark border-secondary" placeholder="e.g. Handled by IRS clerk.">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-info btn-xs font-weight-bold px-3 py-1"><i class="fas fa-plus mr-1"></i> {{ __('Add Milestone') }}</button>
                            </form>

                            <!-- Milestones List -->
                            @if($case->milestones->isEmpty())
                                <div class="text-center py-3 text-muted small border-top border-secondary pt-3">
                                    <i class="fas fa-info-circle mb-1"></i>
                                    <p class="mb-0">{{ __('No progress milestones registered for this case yet.') }}</p>
                                </div>
                            @else
                                <div class="table-responsive style-scroll border-top border-secondary pt-3">
                                    <table class="table table-striped table-bordered table-dark small mb-0">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Milestone') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Target Date') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($case->milestones as $milestone)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $milestone->title }}</strong>
                                                        @if($milestone->description)
                                                            <div class="text-muted small">{{ $milestone->description }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($milestone->status === 'completed')
                                                            <span class="badge badge-success">{{ __('Completed') }}</span>
                                                        @elseif($milestone->status === 'active')
                                                            <span class="badge badge-primary">{{ __('Active') }}</span>
                                                        @else
                                                            <span class="badge badge-secondary">{{ __('Pending') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $milestone->milestone_date ? $milestone->milestone_date->format('M d, Y') : __('N/A') }}
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('admin.cases.destroy-milestone', $milestone->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this milestone?') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                                                        </form>
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
            @endif
        </div>
    </div>

    <!-- Document Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="previewModalLabel">{{ __('Document Preview') }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
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
