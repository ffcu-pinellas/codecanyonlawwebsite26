@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')

@endsection

@section('content')
<div id="wrapper-content">
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
                <div class="card-header">
                    <h6 class="card-title">{{__($title)}}</h6>
                </div>
                <form action="{{ route('admin.settings.general-save') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body ">
                        @csrf
                        <p class="mb-1">{{__('Site Name')}}: </p>
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="site_name" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                   placeholder="{{__('Site Name')}}" value="{{$generalSetting?clean($generalSetting->site_name):''}}">
                            <br>
                            @if ($errors->has('site_name'))
                                <span class="text-danger">{{ $errors->first('site_name') }}</span>
                            @endif
                        </div>

                        <p class="mb-1">{{__('Site Tag Line')}}: </p>
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="site_tag_line" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                   placeholder="{{__('Site Tag Line')}}" value="{{$generalSetting?clean($generalSetting->site_tag_line):''}}">
                            <br>
                            @if ($errors->has('site_tag_line'))
                                <span class="text-danger">{{ $errors->first('site_tag_line') }}</span>
                            @endif
                        </div>

                        <p class="mb-1">{{__('Site Sub Tag Line')}}: </p>
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="site_sub_tag_line" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                   placeholder="{{__('Site Sub Tag Line')}}" value="{{$generalSetting?clean($generalSetting->site_sub_tag_line):''}}">
                            <br>
                            @if ($errors->has('site_sub_tag_line'))
                                <span class="text-danger">{{ $errors->first('site_sub_tag_line') }}</span>
                            @endif
                        </div>

                        <p class="mb-1">{{__('Author Name')}}: </p>
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="author_name" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                   placeholder="{{__('Author Name')}}" value="{{$generalSetting?clean($generalSetting->author_name):''}}">
                            <br>
                            @if ($errors->has('author_name'))
                                <span class="text-danger">{{ $errors->first('author_name') }}</span>
                            @endif
                        </div>


                        <p class="mb-1">{{__('Og Meta Title')}}: </p>
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="og_meta_title" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                   placeholder="{{__('Og Meta Title')}}" value="{{$generalSetting?clean($generalSetting->og_meta_title):''}}">
                        </div>

                        <p class="mb-1">{{__('Og Meta Description')}}: <code>{{__('maximum 50 word')}}</code></p>
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="og_meta_description" aria-label="With textarea"
                                      rows="4">{{$generalSetting?clean($generalSetting->og_meta_description):''}}</textarea>
                        </div>

                        <p class="mb-1">{{__('Og Meta Image')}}: <code>{{__('expected size is 32x32px')}}</code></p>
                        <div class="form-row">
                            <div class="col-md-10 col-sm-12">
                                <div class="form-group">
                                    <div role="button" class="btn btn-primary mr-2">
                                        <input type="file" title='Click to add Files' name="og_meta_image" />

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2  d-md-block  d-sm-none">

                                <div class="img-favicon">
                                    <img src="{{$generalSetting?$generalSetting->og_meta_image:''}}" alt="Og Meta Image" class="img-thumbnail">
                                </div>


                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <div class="wizard-action text-left">
                            <button class="btn btn-wave-light btn-danger btn-lg" type="submit">{{__('Submit form')}}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
    @include('backend.layouts.message')
@endsection
