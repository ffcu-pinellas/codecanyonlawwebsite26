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

    <!-- Start questions Section-->
    <section class="questions-section">
        <div class="container">
            <div class="row">
                <div class="questions-area w-100" id="accordion">

                    @forelse($faqs as $faq)
                        <div class="card">
                            <div class="card-header" id="heading{{$loop->index}}">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse"
                                            data-target="#collapse{{$loop->index}}" aria-expanded="true"
                                            aria-controls="collapse{{$loop->index}}">
                                        {{ clean($faq->question) }} #{{$loop->index+1}}
                                    </button>
                                </h5>
                            </div>

                            <div id="collapse{{$loop->index}}" class="collapse {{ $loop->index==0?'show':'' }}"
                                 aria-labelledby="heading{{$loop->index}}" data-parent="#accordion">
                                <div class="card-body">
                                    {{clean($faq->answer)}}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card">
                            <div class="card-body">
                                <h4 class="alert alert-warning text-center">{{__('No FAQs Found')}}</h4>
                            </div>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </section>
    <!--End questions Section -->


@endsection

@section('page-script')

@endsection
