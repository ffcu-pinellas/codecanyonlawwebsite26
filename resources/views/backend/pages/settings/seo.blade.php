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
                    <h6 class="card-title">{{__('basic')}}</h6>
                </div>
                <div class="card-body ">
                    <form action="{{ route('admin.settings.seo-save') }}" method="POST" enctype="multipart/form-data" class="wma-form">
                        @csrf
                        <p class="mb-1">{{__('Meta Keyword')}}: <code>{{__('Put comma(,) for separate the meta key')}}</code></p>
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="meta_keyword" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                placeholder="{{__('Meta Keyword')}}" data-role="tagsinput" value="{{$seoSetting?$seoSetting->meta_keyword:''}}">
                            <br>
                            @if ($errors->has('meta_keyword'))
                            <span class="text-danger">{{ $errors->first('meta_keyword') }}</span>
                            @endif
                        </div>

                        <p class="mb-1">{{__("Meta Description")}}: <code>{{__('maximum 50 word')}}</code></p>
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="meta_description" aria-label="With textarea"
                                rows="4">{{ $seoSetting?$seoSetting->meta_description:''}}</textarea>
                            <br>
                            @if ($errors->has('meta_description'))
                            <span class="text-danger">{{ $errors->first('meta_description') }}</span>
                            @endif
                        </div>


                        <div class="wizard-action text-left">
                            <button class="btn btn-wave-light btn-danger btn-lg" type="submit">{{__('Submit form')}}</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@section('page-script')
    @include('backend.layouts.message')
@endsection
