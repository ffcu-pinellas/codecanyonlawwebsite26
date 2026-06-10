@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')
<style>
    .status-badge-pending { background-color: #f39c12; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
    .status-badge-active { background-color: #27ae60; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
    .status-badge-suspended { background-color: #c0392b; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
    .status-badge-resolved { background-color: #2980b9; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
</style>
@endsection

@section('content')
    <div id="wrapper-content">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                    <span class="breadcrumb-item active">{{ __($title) }}</span>
                    <span class="breadcrumb-info" id="time"></span>
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
            <div class="col-12">
                <div class="card card-dark bg-dark">
                    <div class="card-header d-block">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <h6 class="card-title lh-35">{{ __($title) }}</h6>
                            </div>
                            <div class="col-md-6 col-sm-12 text-md-right text-left">
                                <a href="{{ route('admin.cases.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> {{ __('Create New Case') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive style-scroll">
                            <table class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Case Number') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Client') }}</th>
                                        <th>{{ __('Assigned Attorney') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Court Date') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cases as $case)
                                        <tr>
                                            <td><strong>{{ $case->case_number }}</strong></td>
                                            <td>{{ $case->title }}</td>
                                            <td>
                                                <strong>{{ $case->client->name }}</strong>
                                                <div class="text-muted small">{{ $case->client->email }}</div>
                                            </td>
                                            <td>{{ $case->attorney ? $case->attorney->name : __('Unassigned') }}</td>
                                            <td>
                                                <span class="status-badge-{{ $case->status }}">{{ ucfirst($case->status) }}</span>
                                            </td>
                                            <td>{{ $case->court_date ? $case->court_date->format('M d, Y h:i A') : __('No date set') }}</td>
                                            <td>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <a href="{{ route('admin.cases.edit', $case->id) }}" class="btn btn-xs btn-info m-1" title="{{ __('Edit Case') }}"><i class="fas fa-edit"></i></a>
                                                    
                                                    @if(Auth::user()->hasRole('admin'))
                                                        <form action="{{ route('admin.cases.destroy', $case->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this case? This action deletes all vaulted documents and linked invoices.') }}');" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-danger m-1" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    @endif
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
