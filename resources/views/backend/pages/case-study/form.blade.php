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
                       href="{{ route('admin.casestudy.index') }}">{{__('Case Study')}}</a>
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
                        action="{{ $caseStudy? route('admin.casestudy.update',$caseStudy->id) : route('admin.casestudy.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if($caseStudy)
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
                                               value="{{ $caseStudy?$caseStudy->title:old('title') }}">
                                        <br>
                                        @if ($errors->has('title'))
                                            <span class="text-danger">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>


                                    <p class="mb-1 font-weight-bold">{{__('Description :')}} </p>
                                    <div class="input-group mb-3">
                                        <textarea class="form-control bapric_edittor" name="description"
                                                  aria-label="With textarea" rows="5"
                                                  placeholder="{{__('Write description here...')}}">{!! clean($caseStudy?$caseStudy->description:old('description')) !!}</textarea>
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Service :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <select class="form-control form-control-lg" name="service_id">
                                            <option disabled="" selected="" class="text-capitalize"
                                                    value="">{{__('Select one')}}</option>

                                            @foreach($services as $service)
                                                <option class="text-capitalize"
                                                        {{ $caseStudy?($caseStudy->service_id==$service->id?'selected':''):' ' }} value="{{ $service->id }}">{{$service->title}}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Problem title :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="problem_title" class="form-control" aria-label="Large"
                                               aria-describedby="inputGroup-sizing-sm"
                                               placeholder="{{__('Problem title')}}"
                                               value="{{ $caseStudy?$caseStudy->problem_title:old('problem_title') }}">
                                        <br>
                                        @if ($errors->has('problem_title'))
                                            <span class="text-danger">{{ $errors->first('problem_title') }}</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Problem Description :')}} </p>
                                    <div class="input-group mb-3">
                                        <textarea class="form-control" name="problem_description"
                                                  aria-label="With textarea" rows="5"
                                                  placeholder="{{__('Write problem description here...')}}">{!! clean($caseStudy?$caseStudy->problem_description:old('problem_description')) !!}</textarea>
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Solution title :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="solution_title" class="form-control" aria-label="Large"
                                               aria-describedby="inputGroup-sizing-sm"
                                               placeholder="{{__('Problem title')}}"
                                               value="{{ $caseStudy?$caseStudy->solution_title:old('solution_title') }}">
                                        <br>
                                        @if ($errors->has('solution_title'))
                                            <span class="text-danger">{{ $errors->first('solution_title') }}</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Solution Description :')}} </p>
                                    <div class="input-group mb-3">
                                        <textarea class="form-control bapric_edittor" name="solution_description"
                                                  aria-label="With textarea" rows="5"
                                                  placeholder="{{__('Write problem description here...')}}">{!! clean($caseStudy?$caseStudy->solution_description:old('solution_description')) !!}</textarea>
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Result Title :')}}</p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="result_title" class="form-control" aria-label="Large"
                                               aria-describedby="inputGroup-sizing-sm"
                                               placeholder="{{__('Problem title')}}"
                                               value="{{ $caseStudy?$caseStudy->result_title:old('result_title') }}">
                                        <br>
                                        @if ($errors->has('result_title'))
                                            <span class="text-danger">{{ $errors->first('result_title') }}</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Result Description : ')}}</p>
                                    <div class="input-group mb-3">
                                        <textarea class="form-control bapric_edittor" name="result_description"
                                                  aria-label="With textarea" rows="5"
                                                  placeholder="{{__('Write result description here...')}}">{!! clean($caseStudy?$caseStudy->result_description:old('result_description')) !!}</textarea>
                                    </div>


                                </div>

                                <div class="col-md-4">

                                    <div class="pl-3 pr-2">
                                        <p class="mb-2 font-weight-bold">{{__('Featured Image : ')}}<br>
                                            <code>{{__('Only jpeg, png, jpg and gif file is acceptable')}}</code></p>
                                        <div class="mb-3">
                                            <div class="featured_image" id="featured_image">
                                                <div class="input-images"></div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($caseStudy)
                                        <div class="pl-3 pr-2 ">
                                            <p class="mb-2 font-weight-bold ">{{__('Old Feature Image :')}} </p>
                                            <img src="{{ asset('upload/case-study/'.$caseStudy->featured_image) }}"
                                                 alt="" class="img-thumbnail bg-secondary mb-3" width="250"
                                                 height="150">
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
    @include('backend.pages.case-study.internal-assets.js.service-page-scripts')
    @include('backend.layouts.message')
@endsection
