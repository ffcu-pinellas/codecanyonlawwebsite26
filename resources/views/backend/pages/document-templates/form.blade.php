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
                        <a class="breadcrumb-item text-white" href="{{ route('admin.document-templates.index') }}">{{__('Document Templates')}}</a>
                        <span class="breadcrumb-item active">{{__($title)}}</span>
                        <span class="breadcrumb-info" id="time"></span>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-dark bg-dark">
                        <div class="card-header">
                            <h6 class="card-title">{{__($title)}}</h6>
                        </div>
                        <form action="{{ $template ? route('admin.document-templates.update', $template->id) : route('admin.document-templates.store') }}" method="POST">
                            @csrf
                            @if($template)
                                @method('PATCH')
                            @endif
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group mb-3">
                                            <label for="title" class="font-weight-bold text-white">{{__('Template Title :')}}</label>
                                            <input type="text" name="title" id="title" class="form-control" placeholder="{{__('e.g., Client Engagement Letter')}}" value="{{ $template ? $template->title : old('title') }}" required>
                                            @if ($errors->has('title'))
                                                <span class="text-danger">{{ $errors->first('title') }}</span>
                                            @endif
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="content" class="font-weight-bold text-white">{{__('Template Content :')}}</label>
                                            <textarea class="form-control bapric_edittor" name="content" id="content" aria-label="With textarea" rows="15" placeholder="{{__('Write template content here...')}}">{!! clean($template ? $template->content : old('content')) !!}</textarea>
                                            @if ($errors->has('content'))
                                                <span class="text-danger">{{ $errors->first('content') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="key" class="font-weight-bold text-white">{{__('Template Key (Unique Identifier) :')}}</label>
                                            <input type="text" name="key" id="key" class="form-control" placeholder="{{__('e.g., engagement_letter')}}" value="{{ $template ? $template->key : old('key') }}" required {{ $template ? 'readonly' : '' }}>
                                            <small class="form-text text-muted">Use snake_case. Replaced automatically with safe alphanumeric characters.</small>
                                            @if ($errors->has('key'))
                                                <span class="text-danger">{{ $errors->first('key') }}</span>
                                            @endif
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="type" class="font-weight-bold text-white">{{__('Template Category :')}}</label>
                                            <select name="type" id="type" class="form-control" required>
                                                <option value="client" {{ ($template && $template->type == 'client') || old('type') == 'client' ? 'selected' : '' }}>Client Action Template</option>
                                                <option value="staff" {{ ($template && $template->type == 'staff') || old('type') == 'staff' ? 'selected' : '' }}>Staff Action Template</option>
                                            </select>
                                            @if ($errors->has('type'))
                                                <span class="text-danger">{{ $errors->first('type') }}</span>
                                            @endif
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="default_action" class="font-weight-bold text-white">{{__('Default Client Execution Protocol :')}}</label>
                                            <select name="default_action" id="default_action" class="form-control text-warning font-weight-bold">
                                                <option value="sign_pin">{{ __('Electronic E-Signature (4-Digit PIN Authentication)') }}</option>
                                                <option value="sign_upload">{{ __('Signed Copy PDF / Image Upload') }}</option>
                                                <option value="approve">{{ __('Review & Digital Approval') }}</option>
                                                <option value="none">{{ __('Informational / Review Only') }}</option>
                                            </select>
                                            <small class="text-muted">Specifies the execution protocol enforced when assigned to a client.</small>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="status" class="font-weight-bold text-white d-block">{{__('Publish Status :')}}</label>
                                            <label class="switch">
                                                <input type="checkbox" name="status" id="status" {{ $template ? ($template->status ? 'checked' : '') : 'checked' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>

                                        <div class="mb-3">
                                            <button type="button" class="btn btn-outline-info btn-block font-weight-bold" id="btnLiveDocPreview">
                                                <i class="fas fa-eye mr-1"></i> {{ __('Live Client Preview') }}
                                            </button>
                                        </div>

                                        <div class="card mt-4 bg-secondary text-white">
                                            <div class="card-body">
                                                <h6 class="font-weight-bold"><i class="material-icons align-middle mr-1">info</i> Available Dynamic Placeholders:</h6>
                                                <hr class="bg-white">
                                                <p class="mb-1"><strong>Client Templates:</strong></p>
                                                <ul class="pl-3 mb-3">
                                                    <li><code>@{{client_name}}</code></li>
                                                    <li><code>@{{client_email}}</code></li>
                                                    <li><code>@{{client_phone}}</code></li>
                                                    <li><code>@{{client_address}}</code></li>
                                                    <li><code>@{{company_name}}</code></li>
                                                    <li><code>@{{date}}</code></li>
                                                    <li><code>@{{attorney_name}}</code></li>
                                                    <li><code>@{{case_number}}</code></li>
                                                </ul>
                                                <p class="mb-1"><strong>Staff Templates:</strong></p>
                                                <ul class="pl-3 mb-0">
                                                    <li><code>@{{employee_name}}</code></li>
                                                    <li><code>@{{employee_email}}</code></li>
                                                    <li><code>@{{employee_phone}}</code></li>
                                                    <li><code>@{{employee_address}}</code></li>
                                                    <li><code>@{{staff_id}}</code></li>
                                                    <li><code>@{{company_name}}</code></li>
                                                    <li><code>@{{date}}</code></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-dark border-top border-secondary">
                                <button type="submit" class="btn btn-primary font-weight-bold px-4"><i class="fas fa-save mr-1"></i> {{__('Save Template')}}</button>
                                <a href="{{ route('admin.document-templates.index') }}" class="btn btn-secondary ml-2">{{__('Cancel')}}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Live Preview Modal -->
    <div class="modal fade" id="docPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title font-weight-bold text-warning"><i class="fas fa-file-contract mr-2"></i> {{ __('Client Live Document Preview') }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4" style="background: #ffffff; color: #111827; border-radius: 0 0 8px 8px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                    <div id="modalDocPreviewContent" style="min-height: 250px; line-height: 1.6;"></div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close Preview') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('backend/assets/js/form-summerNote.js') }}"></script>
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                // Auto-generate key from title if creating new
                @if(!$template)
                $('#title').on('keyup input', function() {
                    var title = $(this).val();
                    var key = title.toLowerCase()
                        .replace(/[^\w ]+/g, '')
                        .replace(/ +/g, '_');
                    $('#key').val(key);
                });
                @endif

                // Live Preview Modal Trigger
                $('#btnLiveDocPreview').on('click', function() {
                    var content = $('#content').val();
                    if (typeof $('.bapric_edittor').summernote !== 'undefined') {
                        content = $('.bapric_edittor').summernote('code');
                    }
                    
                    var previewHtml = content
                        .replace(/@?\{\{client_name\}\}/g, '<strong>Kalyn Mickle</strong>')
                        .replace(/@?\{\{client_email\}\}/g, 'kalyn.mickle@aol.com')
                        .replace(/@?\{\{client_phone\}\}/g, '+1 (555) 349-2910')
                        .replace(/@?\{\{client_address\}\}/g, '2630 Batestown Rd, Oakwood, IL 61858')
                        .replace(/@?\{\{company_name\}\}/g, '<strong>{{ config("app.name", "Your CPA Expert") }}</strong>')
                        .replace(/@?\{\{date\}\}/g, '{{ date("M d, Y") }}')
                        .replace(/@?\{\{attorney_name\}\}/g, 'Gary Livingston, Senior CPA & Legal Counsel')
                        .replace(/@?\{\{case_number\}\}/g, 'CS-287747');

                    $('#modalDocPreviewContent').html(previewHtml);
                    $('#docPreviewModal').modal('show');
                });
            });
        })(jQuery);
    </script>
    @include('backend.layouts.message')
@endsection
