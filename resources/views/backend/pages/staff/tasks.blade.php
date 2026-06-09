@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .task-status-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .badge-pending { background-color: #f39c12; color: white; }
    .badge-completed { background-color: #3498db; color: white; }
    .badge-approved { background-color: #2ecc71; color: white; }
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

        <div class="row">
            <!-- Left Side: Task Assign form -->
            <div class="col-lg-4 mb-4">
                <div class="card card-dark bg-dark">
                    <div class="card-header">
                        <h6 class="card-title">{{ __('Assign Corporate Task') }}</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.staff.tasks.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="staff_user_id">{{ __('Assign to Staff') }} <span class="text-danger">*</span></label>
                                <select name="staff_user_id" id="staff_user_id" class="form-control" required>
                                    <option value="">-- {{ __('Select Staff') }} --</option>
                                    @foreach($staffUsers as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }} (ID: {{ $s->staffDetail->staff_id ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="task_title">{{ __('Task Title') }} <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="task_title" class="form-control" placeholder="e.g. Audit Q2 Tax Documents" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="task_desc">{{ __('Description') }}</label>
                                <textarea name="description" id="task_desc" class="form-control" rows="3" placeholder="Provide notes or specific details..."></textarea>
                            </div>

                            <div class="form-group mb-4">
                                <label for="task_due">{{ __('Due Date') }}</label>
                                <input type="date" name="due_date" id="task_due" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm btn-block"><i class="fas fa-paper-plane mr-1"></i> {{ __('Assign & Notify') }}</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side: Tasks List -->
            <div class="col-lg-8">
                <div class="card card-dark bg-dark">
                    <div class="card-header">
                        <h6 class="card-title">{{ __('Assigned Tasks') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive style-scroll">
                            <table class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Staff') }}</th>
                                        <th>{{ __('Task Title') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Due Date') }}</th>
                                        <th>{{ __('Submissions') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tasks as $task)
                                        <tr>
                                            <td>
                                                <strong>{{ $task->user->name }}</strong>
                                                <div class="small text-muted">ID: {{ $task->user->staffDetail->staff_id ?? 'N/A' }}</div>
                                            </td>
                                            <td>
                                                <strong>{{ $task->title }}</strong>
                                                <div class="small text-muted">{{ $task->description }}</div>
                                            </td>
                                            <td>
                                                <span class="task-status-badge badge-{{ $task->status }}">{{ $task->status }}</span>
                                            </td>
                                            <td>{{ $task->due_date ? $task->due_date->format('M d, Y') : __('No Deadline') }}</td>
                                            <td>
                                                @if($task->status === 'completed' || $task->status === 'approved')
                                                    @if($task->attachment_path)
                                                        <a href="{{ asset($task->attachment_path) }}" target="_blank" class="btn btn-xs btn-outline-info"><i class="fas fa-file-download mr-1"></i>{{ __('Download Proof') }}</a>
                                                    @endif
                                                    @if($task->completion_notes)
                                                        <div class="small mt-1 text-muted"><em>"{{ $task->completion_notes }}"</em></div>
                                                    @endif
                                                @else
                                                    <span class="text-muted small">--</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($task->status === 'completed')
                                                        <form action="{{ route('admin.staff.tasks.status', $task->id) }}" method="POST" class="d-inline mr-1">
                                                            @csrf
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit" class="btn btn-xs btn-success" title="{{ __('Approve Completion') }}"><i class="fas fa-check"></i></button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('admin.staff.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-danger" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
