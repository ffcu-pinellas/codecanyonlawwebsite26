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
                    <p><a href="{{route('home')}}">{{ __('Home') }}</a> {{isset($title)?clean($title):''}}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Inner Section End -->

    <!-- Start Service Section-->
    <section class="service-section style-3">
        <div class="container">
            <div class="row service-wrapper style-3">
                @forelse($services as $service)
                <div class="col-lg-4">
                    <div class="service-post">
                        <div class="icon">
                            <img src="{{asset('upload/services/'.$service->icon)}}" class="img-fluid" alt="" width="36" height="36">
                        </div>
                        <h4><a href="#"><a href="{{route('view-single-service-page',$service->id)}}">{{ clean($service->title) }}</a></a></h4>
                        <p>{!! clean(substr($service?$service->description:'', 0,  64)) !!}</p>
                    </div>
                </div>
                @empty
                    <div class="card-body">
                        <h4 class="alert alert-warning">{{ __('No service found.') }}</h4>
                    </div>
                @endforelse
            </div>
            <div class="row blog-paginate">
                {{ $services->links('vendor.pagination.default') }}
            </div>
        </div>
    </section>
    <!--End Service Section -->
@endsection

@section('page-script')

@endsection
