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
                            href="{{route('view-all-teams-page')}}">{{__('Teams')}}</a> {{isset($attorney->name)?clean($attorney->name):''}}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- Inner Section End -->

    <!-- Start questions Section-->
    <section class="questions-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-5 xs-margin-30px-bottom px-4">
                    <div class="attorney-grids style-2">
                        <div class="grid w-100">
                            <div class="thumb">
                                <img src="{{ asset('upload/attorneys/'.$attorney->image) }}" alt="">
                            </div>
                            <div class="content">
                                <h3>{{clean($attorney->name)}}</h3>
                                <p>{{clean($attorney->designation->name)}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="contact mt-3">
                        <div class="row mt-2">
                            <div class="col-sm-3">
                                <h6 class="mb-0">{{__('Email')}}</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                : {{ clean($attorney->email) }}
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-sm-3">
                                <h6 class="mb-0">{{__('Phone')}}</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                : {{clean($attorney->phone)}}
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-sm-3">
                                <h6 class="mb-0">{{__('Fax')}}</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                : {{ clean($attorney->fax) }}
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-sm-3">
                                <h6 class="mb-0">{{__('Address')}}</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                : {{ clean($attorney->address) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-md-7 px-3">

                    <div class="border p-4">

                        <div class="service-details mt-4">
                            <div class="content">
                                <h4>{{__('Description')}}</h4>
                                <div class="mb-20">
                                    {!!  clean($attorney?$attorney->description:'')  !!}
                                </div>

                                <h4>{{__('Education')}}</h4>
                                <div class="mb-40">
                                    {!!  clean($attorney?$attorney->education:'')  !!}
                                </div>

                                <h4>{{__('Professional Experience')}}</h4>
                                <div class="mb-40">
                                    {!!  clean($attorney?$attorney->professional_exp:'')  !!}
                                </div>

                                <h4>{{__('Legal Experience')}}</h4>
                                <div class="mb-40">
                                    {!!  clean($attorney?$attorney->legal_exp:'')  !!}
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!--End questions Section -->


@endsection

@section('page-script')

@endsection
