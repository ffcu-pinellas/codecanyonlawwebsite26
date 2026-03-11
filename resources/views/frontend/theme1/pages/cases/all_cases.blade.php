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
                    <p><a href="{{route('home')}}">{{__('Home')}}</a> {{isset($title)?clean($title):''}}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Inner Section End -->

    <!-- Start Case Success Section-->
    <section class="case-success-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div id="" class="case-success-area style-2">
                        @foreach($allCases as $cases)
                        <div class="case-success-post">
                            <div class="thumb">
                                <div class="overlay">
                                    <div class="text-box">
                                        <p>{{clean($cases->service->title)}}</p>
                                        <a href="{{route('view-single-cases-page',$cases->id)}}">{{clean($cases->title)}}</a>
                                    </div>
                                </div>
                                <img src="{{asset('/upload/case-study/'.$cases->featured_image)}}" class="img-fluid height-345px" alt="" width="500" height="505">
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
            <div class="row blog-paginate mt-5">
                {{ $allCases->links('vendor.pagination.default') }}
            </div>
        </div>
    </section>
    <!-- End Case Success Section-->
@endsection

@section('page-script')

@endsection
