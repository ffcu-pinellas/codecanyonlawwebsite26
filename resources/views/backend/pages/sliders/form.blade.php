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
                    <a class="breadcrumb-item text-white" href="{{ route('admin.slider.index') }}">{{__('Slider')}}</a>
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
                    <form action="{{ $slider? route('admin.slider.update',$slider->id) : route('admin.slider.store') }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @if($slider)
                            @method('PATCH')
                        @endif
                        <div class="card-body ">
                            <div class="form-row">
                                <div class="col-md-8">
                                    <p class="mb-1 font-weight-bold">{{__('Title :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="title" class="form-control" aria-label="Large"
                                               aria-describedby="inputGroup-sizing-sm"
                                               placeholder="{{__('Slider title')}}"
                                               value="{{ $slider?$slider->title:old('title') }}">
                                        <br>
                                        @if ($errors->has('title'))
                                            <span class="text-danger">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Subtitle :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="sub_title" class="form-control" aria-label="Large"
                                               aria-describedby="inputGroup-sizing-sm"
                                               placeholder="{{__('Slider sub_title')}}"
                                               value="{{ $slider?$slider->sub_title:old('sub_title') }}">
                                        <br>
                                        @if ($errors->has('subtitle'))
                                            <span class="text-danger">{{ $errors->first('subtitle') }}</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Button Name :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="button_name" class="form-control" aria-label="Large"
                                               aria-describedby="inputGroup-sizing-sm"
                                               placeholder="{{__('Slider button name')}}"
                                               value="{{ $slider?$slider->button_name:old('button_name') }}">
                                        <br>
                                        @if ($errors->has('button_name'))
                                            <span class="text-danger">{{ $errors->first('button_name') }}</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Button Url :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="button_url" class="form-control" aria-label="Large"
                                               aria-describedby="inputGroup-sizing-sm"
                                               placeholder="{{__('Slider button url')}}"
                                               value="{{ $slider?$slider->button_url:old('button_url') }}">
                                        <br>
                                        @if ($errors->has('button_url'))
                                            <span class="text-danger">{{ $errors->first('button_url') }}</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Description :')}} </p>
                                    <div class="input-group mb-3">
                                        <textarea class="form-control" name="description" aria-label="With textarea"
                                                  rows="5"
                                                  placeholder="{{__('Write description here...')}}">{!! clean($slider?$slider->description:old('description')) !!}</textarea>
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    <table class="table table-responsive-sm">
                                        <tbody>
                                        <tr>
                                            <td class="pl-3">
                                                <label for="programStatus">
                                                    <span class="font-weight-bold">{{__('Publish Status')}}</span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch float-left">
                                                    <input type="checkbox" name="status"
                                                           {{ $slider?($slider->status==true?'checked':''):'' }} id="programStatus">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <div class="pl-3 pr-2">
                                        <p class="mb-2 font-weight-bold">{{__('Background Image : ')}}<br><code>{{__('Acceptable image
                                           size 1920 x 1200 pixel')}}</code></p>
                                        <div class="mb-3">
                                            <div class="slider_bg_image" id="slider_bg_image">
                                                <div class="input-images"></div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($slider)
                                        <div class="pl-3 pr-2 ">
                                            <p class="mb-2 font-weight-bold ">{{__('Old Image :')}} </p>
                                            <img src="{{ asset('upload/sliders/'.$slider->bg_image) }}" alt=""
                                                 class="img-thumbnail bg-secondary">
                                        </div>
                                    @endif

                                </div>
                            </div>


                        </div>
                        <div class="card-footer">
                            <div class="wizard-action text-left">
                                <button class="btn btn-wave-light btn-danger btn-lg"
                                        type="submit">{{__('Submit form')}}</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    @include('backend.pages.sliders.internal-assets.js.slider-page-scripts')
    @include('backend.layouts.message')
@endsection
