@extends('frontend.theme1.auth-staff.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | ' . $title)

@section('page-css')
<style>
    .kanban-board-container {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 20px;
        min-height: 520px;
    }
    .kanban-column {
        flex: 1;
        min-width: 280px;
        max-width: 340px;
        background: #11151e;
        border: 1px solid #28303f;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        flex-direction: column;
    }
    .kanban-col-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 2px solid #28303f;
    }
    .kanban-col-title {
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0;
    }
    .kanban-cards-wrapper {
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex-grow: 1;
        min-height: 200px;
    }
    .kanban-card {
        background: #161a23;
        border: 1px solid #28303f;
        border-radius: 10px;
        padding: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        transition: all 0.2s ease;
        cursor: grab;
        color: #f1f5f9;
    }
    .kanban-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.4);
        border-color: #fecc56;
    }
    .kanban-card.dragging {
        opacity: 0.5;
        border: 2px dashed #fecc56;
    }
    .card-priority-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
        text-transform: uppercase;
    }
    .badge-urgent { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.35); }
    .badge-high { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.35); }
    .badge-normal { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.35); }

    /* Light Mode Overrides */
    body.light-mode .kanban-column, html.light-mode .kanban-column {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .kanban-col-header, html.light-mode .kanban-col-header {
        border-bottom-color: #e2e8f0 !important;
    }
    body.light-mode .kanban-card, html.light-mode .kanban-card {
        background: #ffffff !important;
        border-color: #cbd5e1 !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05) !important;
        color: #0f172a !important;
    }
    body.light-mode .kanban-card:hover, html.light-mode .kanban-card:hover {
        border-color: #3b82f6 !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap" style="gap: 12px;">
        <div>
            <h4 class="font-weight-bold text-dark mb-1"><i class="fas fa-columns mr-2 text-primary"></i>{{ __('Practice Task Board') }}</h4>
            <p class="text-muted small mb-0">{{ __('Manage audit discovery, tax return filing milestones, and administrative deadlines.') }}</p>
        </div>
        <div class="d-flex align-items-center" style="gap: 10px;">
            <span class="badge badge-primary font-weight-bold px-3 py-2" style="font-size: 12px;">
                <i class="fas fa-tasks mr-1"></i> {{ $tasks->count() }} {{ __('Total Assigned') }}
            </span>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="kanban-board-container" id="kanbanBoard">
        <!-- COLUMN 1: PENDING / BACKLOG -->
        <div class="kanban-column" data-status="pending">
            <div class="kanban-col-header" style="border-color: #f59e0b;">
                <h6 class="kanban-col-title text-warning"><i class="fas fa-inbox mr-1"></i> {{ __('Assigned / Intake') }}</h6>
                <span class="badge badge-warning text-dark font-weight-bold">{{ $tasks->where('status', 'pending')->count() }}</span>
            </div>
            <div class="kanban-cards-wrapper" id="col-pending">
                @foreach($tasks->where('status', 'pending') as $task)
                    @include('frontend.theme1.auth-staff.partials.__kanban_card', ['task' => $task])
                @endforeach
            </div>
        </div>

        <!-- COLUMN 2: IN PROGRESS -->
        <div class="kanban-column" data-status="in_progress">
            <div class="kanban-col-header" style="border-color: #3b82f6;">
                <h6 class="kanban-col-title text-primary"><i class="fas fa-spinner fa-spin mr-1"></i> {{ __('In Progress') }}</h6>
                <span class="badge badge-primary font-weight-bold">{{ $tasks->where('status', 'in_progress')->count() }}</span>
            </div>
            <div class="kanban-cards-wrapper" id="col-in_progress">
                @foreach($tasks->where('status', 'in_progress') as $task)
                    @include('frontend.theme1.auth-staff.partials.__kanban_card', ['task' => $task])
                @endforeach
            </div>
        </div>

        <!-- COLUMN 3: REVIEW -->
        <div class="kanban-column" data-status="review">
            <div class="kanban-col-header" style="border-color: #8b5cf6;">
                <h6 class="kanban-col-title" style="color: #8b5cf6;"><i class="fas fa-search mr-1"></i> {{ __('Supervisor & Quality Review') }}</h6>
                <span class="badge font-weight-bold text-white" style="background: #8b5cf6;">{{ $tasks->where('status', 'review')->count() }}</span>
            </div>
            <div class="kanban-cards-wrapper" id="col-review">
                @foreach($tasks->where('status', 'review') as $task)
                    @include('frontend.theme1.auth-staff.partials.__kanban_card', ['task' => $task])
                @endforeach
            </div>
        </div>

        <!-- COLUMN 4: COMPLETED / VERIFIED -->
        <div class="kanban-column" data-status="completed">
            <div class="kanban-col-header" style="border-color: #10b981;">
                <h6 class="kanban-col-title text-success"><i class="fas fa-check-circle mr-1"></i> {{ __('Completed & Verified') }}</h6>
                <span class="badge badge-success font-weight-bold">{{ $tasks->whereIn('status', ['completed', 'approved'])->count() }}</span>
            </div>
            <div class="kanban-cards-wrapper" id="col-completed">
                @foreach($tasks->whereIn('status', ['completed', 'approved']) as $task)
                    @include('frontend.theme1.auth-staff.partials.__kanban_card', ['task' => $task])
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
@foreach($tasks as $task)
    <!-- Complete Submission Modal -->
    <div class="modal fade" id="completeModal-{{ $task->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold text-dark">{{ __('Submit Task Completion') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('staff.tasks.complete', $task->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <h6 class="font-weight-bold text-primary mb-2">{{ $task->title }}</h6>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted">{{ __('Completion Notes & Deliverables:') }} <span class="text-danger">*</span></label>
                            <textarea name="completion_notes" class="form-control" rows="3" required placeholder="{{ __('Explain work performed, filings made, or document links...') }}"></textarea>
                        </div>
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted">{{ __('Attach Work Product / Form (Optional):') }}</label>
                            <input type="file" name="completion_attachment" class="form-control-file">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">{{ __('Confirm & Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal-{{ $task->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: #11151e; border: 1px solid #28303f; color: #f1f5f9;">
                <div class="modal-header" style="border-bottom: 1px solid #28303f;">
                    <h5 class="modal-title font-weight-bold text-white"><i class="fas fa-file-alt text-warning mr-2"></i>{{ __('Task Briefing') }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h6 class="font-weight-bold text-warning mb-2" style="font-size: 16px;">{{ $task->title }}</h6>
                    <p class="text-light small mb-3" style="line-height: 1.5;">{{ $task->description ?: __('No detailed instructions provided.') }}</p>
                    
                    @if($task->attachment_path)
                        <div class="p-3 mb-3 rounded" style="background: rgba(254, 204, 86, 0.08); border: 1px solid rgba(254, 204, 86, 0.25);">
                            <span class="small font-weight-bold text-warning d-block mb-2"><i class="fas fa-paperclip mr-1"></i> {{ __('Attached Briefing / Document:') }}</span>
                            <a href="{{ asset($task->attachment_path) }}" target="_blank" class="btn btn-sm btn-warning text-dark font-weight-bold">
                                <i class="fas fa-file-download mr-1"></i> {{ __('Download Document for Review') }}
                            </a>
                        </div>
                    @endif

                    <div class="row small p-3 rounded mb-2" style="background: #0a0d14; border: 1px solid #28303f;">
                        <div class="col-6">
                            <span class="text-muted d-block small mb-1">{{ __('Due Date:') }}</span>
                            <strong style="color: #fecc56; font-size: 13px;"><i class="far fa-calendar-alt mr-1"></i> {{ $task->due_date ? $task->due_date->format('M d, Y') : __('No Deadline') }}</strong>
                        </div>
                        <div class="col-6 text-right">
                            <span class="text-muted d-block small mb-1">{{ __('Workflow Status:') }}</span>
                            <span class="badge badge-warning text-dark font-weight-bold" style="font-size: 11px;">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #28303f;">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var draggables = document.querySelectorAll('.kanban-card');
        var columns = document.querySelectorAll('.kanban-cards-wrapper');

        draggables.forEach(function (card) {
            card.addEventListener('dragstart', function () {
                card.classList.add('dragging');
            });

            card.addEventListener('dragend', function () {
                card.classList.remove('dragging');
                var newCol = card.closest('.kanban-column');
                if (newCol) {
                    var newStatus = newCol.getAttribute('data-status');
                    var taskId = card.getAttribute('data-task-id');
                    updateTaskStage(taskId, newStatus);
                }
            });
        });

        columns.forEach(function (wrapper) {
            wrapper.addEventListener('dragover', function (e) {
                e.preventDefault();
                var draggingCard = document.querySelector('.dragging');
                if (draggingCard) {
                    wrapper.appendChild(draggingCard);
                }
            });
        });

        function updateTaskStage(taskId, status) {
            fetch('/staff/tasks/' + taskId + '/update-stage', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            }).then(function (res) {
                return res.json();
            }).then(function (data) {
                if (data.success) {
                    console.log('Stage updated to ' + status);
                }
            }).catch(function (err) {
                console.error(err);
            });
        }
    });
</script>
@endsection
