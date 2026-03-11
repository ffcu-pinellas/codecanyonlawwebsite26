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
                    <p><a href="{{route('home')}}">{{__('Home')}}</a> {{isset($title)?clean($title):''}}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Inner Section End -->

    <!-- Start Contact Section-->
    <section class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="form-area">
                        <form action="javascript:void(0)" id="contact_form" method="post">
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <input type="text" name="f_name" value="{{old('f_name')}}"
                                           class="form-control @error('f_name') is-invalid @enderror"
                                           placeholder="{{__('First Name')}}">
                                    <div class="invalid-feedback">
                                        @if ($errors->has('f_name'))
                                            <span class="text-danger">{{ $errors->first('f_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <input type="text" name="l_name" value="{{old('l_name')}}"
                                           class="form-control @error('l_name') is-invalid @enderror"
                                           placeholder="{{__('Last Name')}}">
                                    <div class="invalid-feedback">
                                        @if ($errors->has('l_name'))
                                            <span class="text-danger">{{ $errors->first('l_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group col-12">
                                    <input type="email" name="email" value="{{old('email')}}"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="{{__('Email')}}">
                                    <div class="invalid-feedback">
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group col-12">
                                    <input type="text" name="website" value="{{old('website')}}"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="{{__('Website')}}">
                                    <div class="invalid-feedback">
                                        @if ($errors->has('website'))
                                            <span class="text-danger">{{ $errors->first('website') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group col-12">
                                    <textarea class="form-control @error('message') is-invalid @enderror" name="message"
                                              placeholder="{{__('Write Your Message...')}}">{{old('message')}}</textarea>
                                    <div class="invalid-feedback">
                                        @if ($errors->has('message'))
                                            <span class="text-danger">{{ $errors->first('message') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <a href="javascript:void(0)" class="btn_the">{{__('Send Message')}}</a>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12 text-center">

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4">
                    @if($contact?$contact->show:'')
                        <div class="contact-info-box">
                            <div class="icon-box">
                                <i class="icofont-contact-add"></i>
                            </div>
                            <div class="content">
                                <h5>{{clean($contact?$contact->title:'')}}</h5>
                                <p>{{ $contact?$contact->line_one:''}}<br> {{ $contact?$contact->line_two:''}}</p>
                            </div>
                        </div>
                    @endif

                    @if($businessInfo?$businessInfo->show:'')
                        <div class="contact-info-box">
                            <div class="icon-box">
                                <i class="icofont-clock-time"></i>
                            </div>
                            <div class="content">
                                <p>{{clean($businessInfo?$businessInfo->line_one:'')}}
                                <br>{{clean($businessInfo?$businessInfo->line_two:'')}}</p>
                            </div>
                        </div>
                    @endif

                    @if($emailInfo?$emailInfo->show:'')
                        <div class="contact-info-box">
                            <div class="icon-box">
                                <i class="icofont-ui-email"></i>
                            </div>
                            <div class="content">
                                <p>{{clean($emailInfo?$emailInfo->line_one:'')}}<br>{{clean($emailInfo?$emailInfo->line_two:'')}}
                                </p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
    <!-- End Contact Section-->



@endsection

@section('page-script')
    <script src="{{ asset('backend/page-section-settings/contact-form.js') }}"></script>
    @include('backend.layouts.message')
@endsection
