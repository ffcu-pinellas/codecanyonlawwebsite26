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

    <!-- Start Blog Section-->
    <section class="blog-section">
        <div class="container">
            <div class="row blog-wrapper style-2">
                <div class="col-lg-12">
                    @foreach($blogs as $blog)
                    <div class="grid">
                        <div class="blog-post">
                            <div class="thumb">
                                <img src="{{asset('/upload/blogs/features/'.$blog->feature_img)}}" alt="">
                            </div>
                            <div class="content">
                                <h4><a href="{{route('view-single-blog-page',$blog->id)}}">{{clean($blog->title)}}</a></h4>
                                <div class="tag">
                                    <ul>
                                        <li>{{ date('M d, Y',strtotime($blog->created_at))}}</li>
                                        <li>{{clean($blog->user_name)}}</li>
                                        <li><a href="{{route('blog-category', $blog->category_id)}}">{{clean($blog->category_name)}}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="row blog-paginate mt-5">
                {{ $blogs->links('vendor.pagination.default') }}
            </div>
        </div>
    </section>
    <!-- End Blog Section-->
@endsection

@section('page-script')

@endsection
