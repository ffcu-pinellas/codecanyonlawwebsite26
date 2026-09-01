@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert').' | '.$title)

@section('page-css')
@endsection

@section('content')
    <div id="wrapper-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                        <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{__('Home')}}</a>
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
                                    <a href="{{ route('admin.document-templates.history') }}" class="btn btn-info btn-sm rounded mr-2"> <i class="material-icons">history</i> Sent History</a>
                                    <a href="{{ route('admin.document-templates.create') }}" class="btn btn-danger btn-sm rounded"> <i class="material-icons">add</i> Add Template</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive style-scroll">
                                <table id="templatesTable" class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="5%">{{__('SL No.')}}</th>
                                        <th>{{__('Key')}}</th>
                                        <th>{{__('Title')}}</th>
                                        <th width="10%">{{__('Type')}}</th>
                                        <th width="10%">{{__('Status')}}</th>
                                        <th width="15%" class="text-center">{{__('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($templates as $data)
                                        <tr>
                                            <th>{{ $loop->index + 1 }}</th>
                                            <td><code>{{ $data->key }}</code></td>
                                            <td>{{ $data->title }}</td>
                                            <td>
                                                <span class="badge {{ $data->type === 'client' ? 'badge-primary' : 'badge-info' }} text-uppercase">
                                                    {{ $data->type }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $data->status ? 'badge-success' : 'badge-secondary' }} text-uppercase">
                                                    {{ $data->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.document-templates.preview', $data->id) }}" class="btn btn-sm btn-warning m-1"><i class="material-icons align-middle" style="font-size: 16px;">visibility</i> {{ __('Preview & Email') }}</a>
                                                <a href="{{ route('admin.document-templates.edit', $data->id) }}" class="btn btn-sm btn-success m-1">{{ __('Edit') }}</a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger m-1 delete-template-btn" data-url="{{ route('admin.document-templates.destroy', $data->id) }}">{{ __('Delete') }}</a>
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

    <!-- Hidden Delete Form -->
    <form id="deleteTemplateForm" action="" method="post" style="display: none;">
        @csrf
        @method('delete')
    </form>
@endsection

@section('page-script')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                if (!$.fn.DataTable.isDataTable('.bapric_table')) {
                    $('.bapric_table').DataTable();
                }

                $(document).on('click', '.delete-template-btn', function(e) {
                    e.preventDefault();
                    var url = $(this).data('url');
                    swal({
                        title: "{{ __('Are you sure?') }}",
                        text: "{{ __('Once deleted, you will not be able to recover this document template!') }}",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            var form = $('#deleteTemplateForm');
                            form.attr('action', url);
                            form.submit();
                        }
                    });
                });
            });
        })(jQuery);
    </script>
@endsection
