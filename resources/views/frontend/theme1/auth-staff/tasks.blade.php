@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .task-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        margin-bottom: 25px;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-pending { background-color: #f39c12; color: white; }
    .badge-completed { background-color: #3498db; color: white; }
    .badge-approved { background-color: #2ecc71; color: white; }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="card task-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h5 class="mb-0" style="font-weight: 700; color: #2c3e50;"><i class="fas fa-tasks mr-2 text-primary"></i>{{ __('Corporate Tasks') }}</h5>
                        <span class="badge badge-primary font-weight-bold">{{ $tasks->count() }} {{ __('Total Tasks') }}</span>
                    </div>

                    <div class="list-group list-group-flush">
                        @forelse($tasks as $task)
                            <div class="list-group-item px-0 py-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="font-weight-bold text-dark mb-0 mr-3">{{ $task->title }}</h6>
                                            <span class="status-badge badge-{{ $task->status }}">{{ $task->status }}</span>
                                        </div>
                                        <p class="text-muted mb-2">{{ $task->description }}</p>
                                        <div class="small text-muted">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ __('Due Date:') }} <strong>{{ $task->due_date ? $task->due_date->format('M d, Y') : __('No Deadline') }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                        @if($task->status === 'pending' || $task->status === 'in_progress')
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#completeModal-{{ $task->id }}">
                                                <i class="fas fa-check-circle mr-1"></i> {{ __('Complete Task') }}
                                            </button>
                                        @elseif($task->status === 'completed')
                                            <span class="text-muted small"><i class="fas fa-clock mr-1 text-warning"></i> {{ __('Awaiting Verification') }}</span>
                                        @elseif($task->status === 'approved')
                                            <span class="text-success small"><i class="fas fa-check-double mr-1 text-success"></i> {{ __('Approved & Verified') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Completion Modal -->
                                <div class="modal fade" id="completeModal-{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="completeModalLabel-{{ $task->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title font-weight-bold text-dark" id="completeModalLabel-{{ $task->id }}">{{ __('Complete Task: ') }} {{ $task->title }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('staff.tasks.complete', $task->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body text-left">
                                                    <div class="form-group mb-3">
                                                        <label class="font-weight-bold small text-muted text-uppercase mb-2">{{ __('Completion Notes') }} <span class="text-danger">*</span></label>
                                                        <textarea name="completion_notes" class="form-control" rows="4" placeholder="{{ __('Provide details of task completion...') }}" required></textarea>
                                                    </div>
                                                    
                                                    <div class="form-group mb-0">
                                                        <label class="font-weight-bold small text-muted text-uppercase mb-2">{{ __('Upload Proof / Document') }}</label>
                                                        <input type="file" name="attachment" class="form-control-file">
                                                        <small class="text-muted d-block mt-1">{{ __('Optional PDF, zip or image (Max 10MB)') }}</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                    <button type="submit" class="btn btn-primary">{{ __('Submit Completion') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-tasks fa-3x text-light mb-3"></i>
                                <p class="text-muted mb-0">{{ __('No assigned tasks found.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
