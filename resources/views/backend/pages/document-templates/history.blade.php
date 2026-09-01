@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert').' | '.$title)

@section('page-css')
<style>
    .badge-sent { background-color: #ffeef0; color: #f84f5a; }
    .badge-opened { background-color: #e8f5e9; color: #2e7d32; }
    .badge-viewed { background-color: #eef2f6; color: #1a1a2e; }
    .badge-generated { background-color: #e3f2fd; color: #0d47a1; }
</style>
@endsection

@section('content')
    <div id="wrapper-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                        <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{__('Home')}}</a>
                        <a class="breadcrumb-item text-white" href="{{ route('admin.document-templates.index') }}">{{__('Document Templates')}}</a>
                        <span class="breadcrumb-item active">{{__($title)}}</span>
                        <span class="breadcrumb-info" id="time"></span>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-dark bg-dark">
                        <div class="card-header d-block">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <h6 class="card-title">{{__($title)}}</h6>
                                </div>
                                <div class="col-md-6 col-sm-12 text-right">
                                    <a href="{{ route('admin.document-templates.index') }}" class="btn btn-danger btn-sm rounded"> <i class="material-icons">list</i> Manage Templates</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive style-scroll">
                                <table id="historyTable" class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="5%">{{__('SL No.')}}</th>
                                        <th>{{__('Document Template')}}</th>
                                        <th>{{__('Sender')}}</th>
                                        <th>{{__('Recipient')}}</th>
                                        <th width="10%">{{__('Emailed?')}}</th>
                                        <th width="10%">{{__('Tracking Status')}}</th>
                                        <th>{{__('Opened At')}}</th>
                                        <th>{{__('Date / Time')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($logs as $data)
                                        <tr>
                                            <th>{{ $loop->index + 1 }}</th>
                                            <td>
                                                <strong>{{ $data->template_title }}</strong><br>
                                                <code>{{ $data->template_key }}</code>
                                            </td>
                                            <td>
                                                {{ $data->sender ? $data->sender->name : 'System Generated' }}
                                            </td>
                                            <td>
                                                {{ $data->recipient_email }}<br>
                                                @if($data->client)
                                                    <span class="small text-muted">Client: {{ $data->client->name }}</span>
                                                @elseif($data->staff)
                                                    <span class="small text-muted">Staff: {{ $data->staff->name }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $data->sent_to_email ? 'badge-success' : 'badge-secondary' }} text-uppercase">
                                                    {{ $data->sent_to_email ? 'Yes' : 'No' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $data->status }} text-uppercase">
                                                    {{ $data->status }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $data->opened_at ? $data->opened_at->format('M d, Y h:i A') : 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $data->created_at->format('M d, Y h:i A') }}
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
    </div>
@endsection

@section('page-script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                if ($.fn.DataTable.isDataTable('#historyTable')) {
                    $('#historyTable').DataTable().destroy();
                }
                $('#historyTable').DataTable({
                    order: [[7, 'desc']]
                });
            });
        })(jQuery);
    </script>
@endsection
