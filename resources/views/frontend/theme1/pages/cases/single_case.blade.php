@extends('frontend.theme1.layouts.master-layout')

@include('frontend.theme1.layouts.meta')

@section('title', config('app.name', 'laravel'). ' | '.$title)

@section('page-css')

@endsection

@section('content')
    <!-- Inner Section Start -->
    <section class="inner-area"
             style="@if(!empty($pageContent->bg_img))background-image: url({{asset($pageContent->bg_img)}});@else background-image: url({{asset('frontend/theme1/images/bg/2.jpg')}}); @endif">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h4>{{isset($title)?clean($title):''}}</h4>
                    <p><a href="{{route('home')}}">{{__('Home')}}</a> <a
                            href="{{route('view-all-cases-page')}}">{{__('Cases')}}</a> {{isset($singleCase->title)?clean($singleCase->title):''}}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- Inner Section End -->

    <!-- Start Service Section -->
    <section class="case-details-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-4">
                    <div class="case-details-sidebar">
                        <div class="case-list mb-40">
                            <ul>
                                @foreach($caseStudy as $case)
                                    <li>
                                        <a class="active"
                                           href="{{route('view-single-cases-page',$case->id)}}">{{clean($case->title)}}
                                            <i class="flaticon-right-arrow-1"></i>
                                        </a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-8">
                    <div class="case-details">
                        <div class="thumb">
                            <img src="{{asset('/upload/case-study/'.$singleCase->featured_image)}}" alt="">
                        </div>
                        <div class="content">
                            <h4>{{$singleCase?clean($singleCase->title):''}}</h4>
                            <p class="mb-20">{!! clean($singleCase?$singleCase->description:'') !!}</p>

                            <div class="case-tab mb-40">

                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="features-one-tab" data-toggle="tab"
                                           href="#features-one" role="tab" aria-controls="features-one"
                                           aria-selected="true">{{clean($singleCase->problem_title)}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="features-two-tab" data-toggle="tab" href="#features-two"
                                           role="tab" aria-controls="features-two" aria-selected="false">{{clean($singleCase->solution_title)}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="features-three-tab" data-toggle="tab"
                                           href="#features-three" role="tab" aria-controls="features-three"
                                           aria-selected="false">{{clean($singleCase->result_title)}}</a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="features-one" role="tabpanel"
                                         aria-labelledby="features-one-tab">
                                        {!! clean($singleCase->problem_description) !!}
                                    </div>
                                    <div class="tab-pane fade" id="features-two" role="tabpanel"
                                         aria-labelledby="features-two-tab">
                                        {!! clean($singleCase->solution_description) !!}
                                    </div>
                                    <div class="tab-pane fade" id="features-three" role="tabpanel"
                                         aria-labelledby="features-three-tab">
                                        {!! clean($singleCase->result_description) !!}
                                    </div>
                                </div>

                            </div>
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
