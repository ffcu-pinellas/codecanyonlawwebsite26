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
                                            <label for="status" class="font-weight-bold text-white d-block">{{__('Publish Status :')}}</label>
                                            <label class="switch">
                                                <input type="checkbox" name="status" id="status" {{ $template ? ($template->status ? 'checked' : '') : 'checked' }}>
                                                <span class="slider round"></span>
                                            </label>
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
                            <div class="card-footer">
                                <div class="wizard-action text-left">
                                    <button class="btn btn-wave-light btn-danger btn-lg mr-2" type="submit">{{__('Save Template')}}</button>
                                    <a href="{{ route('admin.document-templates.index') }}" class="btn btn-wave-light btn-secondary btn-lg">{{__('Cancel')}}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('backend/assets/js/form-summerNote.js') }}"></script>
    @include('backend.layouts.message')
@endsection
