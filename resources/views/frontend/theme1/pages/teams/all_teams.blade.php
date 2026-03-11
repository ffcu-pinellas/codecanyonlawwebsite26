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

    <!-- Start Attorney Section-->
    <section class="attorney-section bgc-fff">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="attorney-grids style-2">
                       @foreach($teamMembers as $team)
                        <div class="grid">
                            <div class="thumb">
                                <img src="{{asset('/upload/attorneys/'.$team->image)}}" alt="">
                            </div>
                            <div class="content">
                                <h3><a href="{{route('view-attorney',$team->id)}}">{{clean($team->name)}}</a></h3>
                                <p>{{clean($team->designation->name)}}</p>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End Attorney Section-->
@endsection

@section('page-script')

@endsection
