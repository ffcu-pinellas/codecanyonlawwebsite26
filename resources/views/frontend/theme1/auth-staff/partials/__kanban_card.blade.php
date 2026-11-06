@php
    $priority = $task->priority ?? 'normal';
    $pClass = 'badge-normal';
    if ($priority === 'urgent') $pClass = 'badge-urgent';
    elseif ($priority === 'high') $pClass = 'badge-high';
@endphp

<div class="kanban-card" draggable="true" data-task-id="{{ $task->id }}">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="card-priority-badge {{ $pClass }}">{{ ucfirst($priority) }}</span>
        <small class="text-muted"><i class="far fa-calendar-alt mr-1"></i> {{ $task->due_date ? $task->due_date->format('M d') : 'No Date' }}</small>
    </div>
    
    <h6 class="font-weight-bold text-dark mb-1" style="font-size: 13.5px; line-height: 1.3;">{{ $task->title }}</h6>
    <p class="text-muted small mb-2" style="font-size: 11.5px; line-height: 1.4;">{{ Str::limit($task->description, 75) }}</p>

    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
        <button type="button" class="btn btn-xs btn-outline-info" data-toggle="modal" data-target="#previewModal-{{ $task->id }}">
            <i class="fas fa-eye"></i> Details
        </button>
        
        @if($task->status !== 'completed' && $task->status !== 'approved')
            <button type="button" class="btn btn-xs btn-outline-primary font-weight-bold" data-toggle="modal" data-target="#completeModal-{{ $task->id }}">
                <i class="fas fa-check-circle"></i> Complete
            </button>
        @else
            <span class="text-success small font-weight-bold"><i class="fas fa-check-double"></i> Done</span>
        @endif
    </div>
</div>

