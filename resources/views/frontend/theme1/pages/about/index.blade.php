@extends('frontend.theme1.layouts.master-layout')

@include('frontend.theme1.layouts.meta')

@section('title', config('app.name', 'laravel'). ' | '.$title)

@section('page-css')

@endsection

@section('content')


    <!-- Inner Section Start -->
    <section class="inner-area" style="@if(!empty($breadcumbBgImg->bg_img))background-image: url({{asset($breadcumbBgImg->bg_img)}});@else background-image: url({{asset('frontend/theme1/images/bg/2.jpg')}}); @endif">
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

    <!-- Start About Section-->
    <section class="about-section">
        <div class="container">
            <div class="row about-wrapper style-2">
                <div class="col-lg-5">
                    <div class="thumb">
                     <img src="{{ $leftImage?$leftImage->fnt_img:''}}" alt="">
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="content">
                        <h6>{{ clean($rightAbout?$rightAbout->title:'') }}</h6>
                        <h4>{{ clean($rightAbout?$rightAbout->sub_title:'') }}</h4>
                        {!! clean($rightAbout?$rightAbout->description:'') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End About Section -->


    <!-- Start Appointment Section-->
    @if($aboutAppointment?$aboutAppointment->show:'')
    <section class="appointment-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="funfact-grids">
                        <div class="grid">
                            <p>{{ $aboutAppointment?clean($aboutAppointment->case_won):'' }}</p>
                        </div>
                        <div class="grid">
                            <p>{{ $aboutAppointment?clean($aboutAppointment->total_attorney):'' }}</p>
                        </div>
                        <div class="grid">
                            <p>{{ $aboutAppointment?clean($aboutAppointment->total_client):'' }}</p>
                        </div>
                        <div class="grid">
                            <p>{{ $aboutAppointment?clean($aboutAppointment->total_case_dismissed):'' }}</p>
                        </div>
                    </div>
                </div>
               <div class="col-lg-7">
                    <div class="appointment-area">
                        <div class="appointment-title">

                            <h2> {!! clean($aboutAppointment?$aboutAppointment->form_title:'') !!}  </h2>
                            <p>{{ $aboutAppointment?clean($aboutAppointment->form_subtitle):'' }}</p>
                        </div>
                        <form id="appointment_form" action="javascript:void(0)" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <input type="text" name="name" value="{{old('name')}}"  class="form-control @error('f_name') is-invalid @enderror" placeholder="{{__('Your Name')}}">
                                    <div class="invalid-feedback">
                                        @if ($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <input type="email" name="email" value="{{old('email')}}" class="form-control @error('email') is-invalid @enderror" placeholder="{{__('Your Email')}}">
                                    <div class="invalid-feedback">
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="contact-textarea">
                                        <textarea class="form-control @error('email') is-invalid @enderror" placeholder="{{__('Description')}}" name="description"></textarea>
                                        <div class="invalid-feedback">
                                            @if ($errors->has('description'))
                                                <span class="text-danger">{{ $errors->first('description') }}</span>
                                            @endif
                                        </div>
                                        <a class="btn-theme" href="javascript:void(0)">{{ __('Get Quote') }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12 text-center">

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    <!-- End Appointment Section-->

    <!-- Start Attorney Section-->
    @if($aboutAttorney?$aboutAttorney->show:'')
    <section class="attorney-section bgc-fff">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title section-title-left st-2">
                        <h6>{{ $aboutAttorney?clean($aboutAttorney->title):'' }}</h6>
                        <h4>{{ $aboutAttorney?clean($aboutAttorney->sub_title):''}}</h4>
                        <p>{!! clean($aboutAttorney?$aboutAttorney->description:'') !!}</p>
                        <div class="right">
                            <a href="{{route('view-all-teams-page')}}" class="theme-btn">{{__('view all attorney')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="attorney-grids attorney-slider owl-carousel">
                        @forelse($attorneys as $attorney)
                            <div class="grid">
                                <div class="thumb">
                                    <img src="{{ asset('upload/attorneys/'.$attorney->image) }}" alt="">
                                </div>
                                <div class="content">
                                    <a href="{{ route('view-attorney',$attorney->id) }}" class=""><h3>{{$attorney?clean($attorney->name):''}}</h3></a>
                                    <p>{{ $attorney?clean($attorney->designation->name):''}}</p>
                                </div>
                            </div>
                        @empty
                            <h4>{{__('No attorney found')}}</h4>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    <!-- End Attorney Section-->
@endsection

@section('page-script')
    <script src="{{ asset('backend/page-section-settings/appointment-form.js') }}"></script>
@endsection
