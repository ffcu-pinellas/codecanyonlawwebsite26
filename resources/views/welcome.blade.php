@extends('frontend.theme1.layouts.master-layout')

@section('title', config('app.name', 'laravel'). ' | '.$title)

@section('page-css')

@endsection

@section('content')

    <!-- Start Home Section-->
    @if($slider_setting?$slider_setting->show:'')
    <div id="home-st1" class="carousel slide carousel-fade home-slider home-st1-sec" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($sliders as $slider)
            <li data-target="#home-st1" data-slide-to="{{$loop->index}}" class="{{$loop->first?'active':''}}"></li>
            @endforeach
        </ol>
        <div class="carousel-inner" role="listbox">
            @foreach($sliders as $slider)
            <div class="carousel-item {{$loop->first?'active':''}} fullHeight">
                <img class="w-100" src="{{ asset('frontend/theme1/images/slider/1.jpg')}}" alt="First slide">
                <div class="slide-caption">
                    <div class="text">
                        <div class="sub-title">
                            <h4>{{ clean($slider?$slider->title:'') }}</h4>
                        </div>
                        <div class="title">
                            <h1>{{ clean($slider?$slider->sub_title:'') }}</h1>
                        </div>
                        <div class="p-text">
                            <p>{!! clean($slider?$slider->description:'')  !!}</p>
                        </div>
                        <div class="btn">
                            <a class="home-btn" href="{{$slider->button_url}}">{{ clean($slider?$slider->button_name:'') }}</a>
                        </div>
                    </div>
                    <div class="img">
                        <img src="{{ asset('upload/sliders/'.$slider->bg_image)}}" alt="">
                    </div>
                </div>
            </div>

            @endforeach
        </div>
        <a class="carousel-control-prev" href="#home-st1" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">{{__('Previous')}}</span>
        </a>
        <a class="carousel-control-next" href="#home-st1" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">{{__('Next')}}</span>
        </a>
    </div>
    @endif
    <!--End Home Section -->


    <!-- Start About Section-->
    <section class="about-section">
        <div class="container">
            <div class="row about-wrapper">
                <div class="col-lg-5">
                    <img src="{{ $about_setting?$about_setting->fnt_img:'' }}" alt="">

                </div>
                <div class="col-lg-7">
                    <div class="content">
                        <h6>{{ clean($about_setting?$about_setting->title:'') }}</h6>
                        <h4>{{ clean($about_setting?$about_setting->sub_title:'') }}</h4>
                        {!! clean($about_setting?$about_setting->description:'') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End About Section -->

    <!-- Start Service Section-->
    @if($service_setting?$service_setting->show:'')
    <section class="service-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title section-title-left st-2">
                        <h6>{{ clean($service_setting?$service_setting->title:'') }}</h6>
                        <h4 class="text-white">{{ clean($service_setting?$service_setting->sub_title:'') }}</h4>
                        <p>{!! clean($service_setting?$service_setting->description:'') !!}  </p>
                        <div class="right">
                            <a href="{{ route('view-all-services-page') }}" class="theme-btn">{{__('view all service')}}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="service-slider owl-carousel service-wrapper">
                        @foreach($services as $service)
                        <div class="item">
                            <div class="service-post">
                                <div class="icon d-flex justify-content-center">
                                   <img src="{{asset('upload/services/'.$service->icon)}}" class="service_icon img-fluid" alt="" width="64" height="64">
                                </div>
                                <h4><a href="{{route('view-single-service-page',$service->id)}}">{{ clean($service->title) }}</a></h4>
                                <p>{!! clean(Str::limit(strip_tags($service->description),60,'.')) !!} </p>
                            </div>
                        </div>
                         @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    <!--End Service Section -->


    <!-- Start Testimonials Section-->
    @if($testimonial_setting?$testimonial_setting->show:'')
    <section class="testimonials-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title section-title-left st-2 text-center">
                        <h6>{{ clean($testimonial_setting?$testimonial_setting->title:'') }}</h6>
                        <h4>{{ clean($testimonial_setting?$testimonial_setting->sub_title:'') }}</h4>
                        <div>{!! clean($testimonial_setting?$testimonial_setting->description:'') !!}</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="testimonials-slider owl-carousel owl-dot-st2">
                        @forelse($testimonials as $testmonial)
                        <div class="item">
                            <div class="testimonials-area">
                                <div class="content">
                                    <p>{{clean($testmonial?$testmonial->testimonial:'')}}</p>
                                </div>
                                <div class="thumb">
                                    <img src="{{ asset('upload/testimonial/'.$testmonial->image) }}" alt="">
                                    <div class="title-box mt-2">
                                        <h4>{{clean($testmonial?$testmonial->name:'')}}</h4>
                                        <p>{{clean($testmonial?$testmonial->designation:'')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <h4>{{__('No testimonial found')}}</h4>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    <!-- End Testimonials Section-->

    <!-- Start Appointment Section-->
    @if($appointment_setting?$appointment_setting->show:'')
    <section class="appointment-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="appointment-area">
                        <div class="appointment-title">
                            <h2> {!! clean($appointment_setting?$appointment_setting->form_title:'') !!} </h2>
                            <p>{{clean($appointment_setting?$appointment_setting->form_subtitle:'')}}</p>
                        </div>
                        <form id="appointment_form" action="javascript:void(0)" method="post">
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
                                        <a class="btn-theme" href="javascript:void(0)">{{__('Get Quote')}}</a>
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
                <div class="col-lg-7">
                    <div class="appointment-content">
                        <h6>{{clean($appointment_setting?$appointment_setting->title:'')}}</h6>
                        <h2>{{clean($appointment_setting?$appointment_setting->sub_title:'')}}</h2>
                        <p>{!! clean($appointment_setting?$appointment_setting->description:'') !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    <!-- End Appointment Section-->

    <!-- Start Case Success Section-->
    @if($case_study_setting?$case_study_setting->show:'')
    <section class="case-success-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title section-title-left st-2">
                        <h6>{{clean($case_study_setting?$case_study_setting->title:'')}}</h6>
                        <h4>{{clean($case_study_setting?$case_study_setting->sub_title:'')}}</h4>
                        <p>{!! clean($case_study_setting?$case_study_setting->description:'') !!}</p>
                        <div class="right">
                            <a href="{{route('view-all-cases-page')}}" class="theme-btn">{{__('view all case')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="case-success-area">
                        @forelse($case_studies as $case_study)
                        <div class="case-success-post">
                            <div class="thumb">
                                <div class="overlay">
                                    <div class="text-box">
                                        <p>{{clean($case_study->service?$case_study->service->title:'')}}</p>
                                        <a href="{{route('view-single-cases-page',$case_study->id)}}">{{clean($case_study?$case_study->title:'')}}</a>
                                    </div>
                                </div>
                                <img src="{{ asset('/upload/case-study/'.$case_study->featured_image) }}" class="height-345px" alt="">
                            </div>
                        </div>
                        @empty
                            <h4>{{__('No case found')}}</h4>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    <!-- End Case Success Section-->

    <!-- Start Attorney Section-->
    @if($attorney_setting?$attorney_setting->show:'')
    <section class="attorney-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title section-title-left st-2">
                        <h6>{{clean($attorney_setting?$attorney_setting->title:'')}}</h6>
                        <h4 class="text-white">{{clean($attorney_setting?$attorney_setting->sub_title:'')}}</h4>
                        <p>{!! clean($attorney_setting?$attorney_setting->description:'') !!}</p>
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
                        <div class="item">
                            <div class="grid">
                                <div class="thumb">
                                    <img src="{{ asset('upload/attorneys/'.$attorney->image) }}" alt="">
                                </div>
                                <div class="content">
                                    <a href="{{ route('view-attorney',$attorney->id) }}" class=""><h3>{{clean($attorney?$attorney->name:'')}}</h3></a>
                                    <p>{{clean($attorney?$attorney->designation->name:'')}}</p>
                                </div>
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


    <!-- Start Blog Section-->
    @if($blog_setting?$blog_setting->show:'')
    <section class="blog-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title section-title-left st-2">
                        <h6>{{clean($blog_setting?$blog_setting->title:'')}}</h6>
                        <h4>{{clean($blog_setting?$blog_setting->sub_title:'')}}</h4>
                        <p>{!! clean($blog_setting?$blog_setting->description:'') !!}</p>
                        <div class="right">
                            <a href="{{ route('view-all-blogs-page') }}" class="theme-btn">{{__('view all blog')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row blog-wrapper">
                @forelse($blogs as $blog)
                <div class="col-lg-4">
                    <div class="blog-post">
                        <div class="thumb">
                            <img src="{{asset('/upload/blogs/features/'.$blog->feature_img)}}" alt="">
                        </div>
                        <div class="content">
                            <h4><a href="{{ route('view-single-blog-page',$blog->id) }}">{{clean($blog?$blog->title:'')}}</a></h4>
                            <div class="tag">
                                <ul>
                                    <li> {{ date('M d, Y', strtotime($blog?$blog->created_at:'')) }}</li>
                                    <li><a href="{{ route('blog-category',$blog->category?$blog->category->id:'') }}">{{clean($blog->category?$blog->category->name:'')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <h4>{{__('No blogs found.')}}</h4>
                @endforelse

            </div>
        </div>
    </section>
    @endif
    <!-- End Blog Section-->

    <!-- Start Partner Section-->
    @if($partner_setting?$partner_setting->show:'')
        <section class="partner-section">
            <div class="container">
                @if(!empty($partner_setting))
                    @if($partner_setting->title || $partner_setting->sub_title || $partner_setting->description)
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="section-title section-title-left st-2 text-center">
                                    <h6>{{clean($partner_setting->title?$partner_setting->title:'')}}</h6>
                                    <h4 class="text-white">{{clean($partner_setting->sub_title?$partner_setting->sub_title:'')}}</h4>
                                    <div>{!! clean($partner_setting->description?$partner_setting->description:'') !!}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                @if(!empty($partners))
                    <div class="row partner-area">
                        <div class="col-lg-12 col-md-12">
                            <div class="partner-slider owl-carousel">
                                @forelse($partners as $partner)
                                    <div class="item">
                                        <div class="thumb">
                                            <img src="{{ asset('upload/partners/'.$partner->image) }}" alt="">
                                        </div>
                                    </div>
                                @empty
                                    <h4>{{__('No partner found')}}</h4>
                                @endforelse

                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif
    <!-- End Partner Section-->

@endsection

@section('page-script')
    <script src="{{ asset('backend/page-section-settings/appointment-form.js') }}"></script>
@endsection
