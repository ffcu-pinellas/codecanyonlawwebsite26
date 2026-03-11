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
                    <a class="breadcrumb-item text-white"
                       href="{{ route('admin.service.index') }}">{{__('Service')}}</a>
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
                    <form
                        action="{{ $service? route('admin.service.update',$service->id) : route('admin.service.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if($service)
                            @method('PATCH')
                        @endif
                        <div class="card-body ">
                            <div class="form-row">
                                <div class="col-md-8">
                                    <p class="mb-1 font-weight-bold">{{__('Title :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="title" class="form-control" aria-label="Large"
                                               aria-describedby="inputGroup-sizing-sm"
                                               placeholder="{{__('Service title')}}"
                                               value="{{ $service?$service->title:old('title') }}">
                                        <br>
                                        @if ($errors->has('title'))
                                            <span class="text-danger">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>


                                    <p class="mb-1 font-weight-bold">{{__('Description :')}} </p>
                                    <div class="input-group mb-3">
                                        <textarea class="form-control bapric_edittor" name="description"
                                                  aria-label="With textarea" rows="5"
                                                  placeholder="{{__('Write description here...')}}">{!! clean($service?$service->description:old('description')) !!}</textarea>
                                    </div>

                                    <div class=" pr-2">
                                        <p class="mb-2 font-weight-bold">{{__('Featured Image :')}} <code>{{__('(Only jpeg, png, jpg
                                           and gif file is acceptable)')}}</code></p>
                                        <div class="mb-3">
                                            <div class="featured_image" id="featured_image">
                                                <div class="input-images"></div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($service)
                                        <div class=" pr-2 ">
                                            <p class="mb-2 font-weight-bold ">{{__('Old Feature Image :')}} </p>
                                            <img src="{{ asset('upload/services/'.$service->featured_image) }}" alt=""
                                                 class="img-thumbnail bg-secondary mb-3" width="250" height="150">
                                        </div>
                                    @endif

                                    <p class="mb-2 font-weight-bold">{{__('Presentation :')}} <code>{{__('(Only pdf, ppt, png, jpg file
                                       are acceptable)')}}</code></p>
                                    <div class="form-row">
                                        <div class="col-md-10 col-sm-12">
                                            <div class="form-group">
                                                <div role="button" class="btn btn-primary mr-2">
                                                    <input type="file" title='Click to add Files' name="presentation"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2  d-md-block  d-sm-none">
                                            @if($service)
                                                <a href="" class="" target="_blank">
                                                    <img src="{{ asset('backend/assets/img/file/pdf.png') }}" alt=""
                                                         class="img-fluid">
                                                </a>
                                            @endif

                                        </div>
                                    </div>


                                    <p class="mb-2 font-weight-bold">{{__('Brochures :')}} <code>{{__('(Only pdf, ppt, png, jpg file
                                       are acceptable)')}}</code></p>
                                    <div class="form-row">
                                        <div class="col-md-10 col-sm-12">
                                            <div class="form-group">
                                                <div role="button" class="btn btn-primary mr-2">
                                                    <input type="file" title='Click to add Files' name="brochures"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2  d-md-block  d-sm-none">
                                            @if($service)
                                                <a href="" class="" target="_blank">
                                                    <img src="{{ asset('backend/assets/img/file/pdf.png') }}" alt=""
                                                         class="img-fluid">
                                                </a>
                                            @endif

                                        </div>
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
                                                           {{ $service?($service->status==true?'checked':''):'' }} id="programStatus">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <div class="pl-3 pr-2">
                                        <p class="mb-2 font-weight-bold">{{__('Icon :')}} <code>{{__('(Only jpeg, png, jpg and gif file
                                           is acceptable)')}}</code></p>
                                        <div class="mb-3">
                                            <div class="service_icon" id="service_icon">
                                                <div class="input-images"></div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($service)
                                        <div class="pl-3 pr-2 ">
                                            <p class="mb-2 font-weight-bold ">{{__('Old Icon :')}} </p>
                                            <img src="{{ asset('upload/services/'.$service->icon) }}" alt=""
                                                 class="img-thumbnail bg-secondary" width="200" height="100">
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
    @include('backend.pages.services.internal-assets.js.service-page-scripts')
    @include('backend.layouts.message')
@endsection
