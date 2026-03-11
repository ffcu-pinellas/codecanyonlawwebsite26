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
                    <form action="{{ route('admin.settings.social-media-save') }}" method="POST" enctype="multipart/form-data">
                        <div class="card-body ">
                            @csrf
                            <div class="row">
                                @foreach($socialMediaSettingAll as $item)
                                <div class="col-md-6">
                                    <p class="mb-1 text-capitalize">{{$item->name}}:<code>{{__('Leave it blank, If you would not luke to show the it,')}}</code> </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="{{$item->name}}" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                               placeholder="{{__($item->name.' Link')}}" value="{{$item->url}}">
                                        <br>
                                        @if ($errors->has($item->name))
                                            <span class="text-danger">{{ $errors->first($item->name) }}</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
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
