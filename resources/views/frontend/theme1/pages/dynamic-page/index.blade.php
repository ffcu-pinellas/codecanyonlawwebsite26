@extends('frontend.theme1.layouts.master-layout')

@include('frontend.theme1.layouts.meta')

@section('title', config('app.name', 'laravel'). ' | '.$title)

@section('page-css')

@endsection

@section('content')

    <!-- Inner Section Start -->
    <section class="inner-area" style="@if(!empty($page->breadcrumb_bg))background-image: url({{asset($page->breadcrumb_bg)}});@else background-image: url({{asset('frontend/theme1/images/bg/2.jpg')}}); @endif">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h4>{{ clean($title) }}</h4>
                    <p>{{clean($page->sub_title?$page->sub_title:'')}}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Inner Section End -->

    <!-- Start Section-->
    <section class="about-section">
        <div class="container">
            <div class="row about-wrapper style-2">
                <div class="content">
                    {!! clean($page->page_content) !!}
                </div>
            </div>
        </div>
    </section>
    <!--End Section -->


@endsection

@section('page-script')

@endsection
