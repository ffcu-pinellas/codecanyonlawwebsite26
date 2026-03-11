@extends('frontend.theme1.layouts.master-layout')

@include('frontend.theme1.layouts.meta')

@section('title', config('app.name', 'laravel'). ' | '.$title)

@section('page-css')

@endsection

@section('content')
    <!-- Inner Section Start -->
    <section class="inner-area" style="@if(!empty($pageContent->bg_img))background-image: url({{asset($pageContent->bg_img)}});@else background-image: url({{asset('frontend/theme1/images/bg/2.jpg')}}); @endif">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h4>{{isset($title)?clean($title):''}}</h4>
                    <p><a href="{{route('home')}}">{{ __('Home') }}</a> <a href="{{route('view-all-services-page')}}">{{ __('Services') }}</a> {{isset($service->title)?clean($service->title):''}}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Inner Section End -->

    <!-- Start Service Section -->
    <section class="service-details-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-4">
                    <div class="service-details-sidebar">
                        <div class="service-list mb-40">
                            <ul>
                                @foreach($allService as $category)
                                <li>
                                    <a class="" href="{{route('view-single-service-page',$category->id)}}">{{clean($category->title)}}
                                        <i class="flaticon-right-arrow-1"></i>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="download-box  mb-40">
                            @if(!empty($service->presentation))
                                <div class="download-item">
                                    <a href="{{ asset('upload/services/'.$service->presentation) }}" target="_blank"><i class="fa fa-file-pdf-o"></i> {{ __('COMPANY PRESENTATION') }}</a>
                                </div>
                            @endif
                            @if(!empty($service->brochures))
                                <div class="download-item">
                                    <a href="{{ asset('upload/services/'.$service->brochures) }}" target="_blank"><i class="fa fa-file-pdf-o"></i>{{ __('Download Brochures') }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-8">
                    <div class="service-details">
                        <div class="thumb">
                            @if(!empty($service->featured_image))
                                <img src="{{ asset('upload/services/'.$service->featured_image) }}" alt="">
                            @endif
                        </div>
                        <div class="content">
                            <h4>{{$service?clean($service->title):''}}</h4>
                           {!! clean($service?$service->description:'') !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Service Section -->
@endsection

@section('page-script')

@endsection
