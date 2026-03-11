@extends('frontend.theme1.layouts.master-layout')

@include('frontend.theme1.layouts.meta')

@section('title', config('app.name', 'laravel'). ' | '.$title)

@section('page-css')

@endsection

@section('content')
    <!-- Inner Section Start -->
    <section class="inner-area"
             style="@if(!empty($blog->bg_image))background-image: url({{asset('/upload/blogs/bg-images/'.$blog->bg_image)}});@else background-image: url({{asset('frontend/theme1/images/bg/2.jpg')}}); @endif">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h4>{{isset($title)?clean($title):''}}</h4>
                    <p><a href="{{route('home')}}">{{__('Home')}}</a> <a
                            href="{{route('view-all-blogs-page')}}">{{__('Blogs')}}</a> {{isset($blog->title)?clean($blog->title):''}}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- Inner Section End -->

    <!-- Start Service Section -->
    <div class="blog-details-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-8">
                    <div class="blog-details">
                        <div class="thumb">
                            <img src="{{asset('/upload/blogs/features/'.$blog->feature_img)}}" alt="">
                        </div>
                        <div class="content">
                            <div class="tag">
                                <ul>
                                    <li>{{date('M d, Y',strtotime($blog->created_at))}}</li>
                                    <li>{{__('-By')}} {{clean($blog->userName)}}</li>
                                    <li><a href="#">{{clean($blog->cat_name)}}</a></li>
                                </ul>
                            </div>
                            <h4>{{clean($blog->title)}}</h4>

                            <div>{!! clean($blog->description) !!}</div>
                        </div>
                        @if(!empty($commentSettings->show) && $commentSettings->show===1)
                            {!! clean($commentSettings->code) !!}
                            <div class="fb-comments" data-href="{{clean($commentSettings->url)}}" data-width=""
                                 data-numposts="5"></div>
                        @endif
                    </div>
                </div>
                <div class="col-md-12 col-lg-4">
                    <div class="sideber-area">
                        <div class="sideber-search">
                            <form action="{{route('search-blog')}}" method="get" class="clearfix">
                                <input type="search" name="s" placeholder="{{__('Search Here..')}}">
                                <button type="submit">
                                    <span class="icofont-search"></span>
                                </button>
                            </form>
                        </div>

                        @if(!empty($recentBlogs))
                            <div class="widget">
                                <div class="sideber-title">
                                    <h4>{{__('Recent Posts')}}</h4>
                                </div>
                                <div class="blog-small">
                                    @foreach($recentBlogs as $recentBlog)
                                        <div class="item">
                                            <img src="{{asset('/upload/blogs/features/'.$recentBlog->feature_img)}}"
                                                 alt="">
                                            <div class="tex">
                                                <h5>
                                                    <a href="{{route('view-single-blog-page',$recentBlog->id)}}">{{clean($recentBlog->title)}}</a>
                                                </h5>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        @endif

                        @if(!empty($categories))
                            <div class="widget">
                                <div class="sideber-title">
                                    <h4>{{__('Categories')}}</h4>
                                </div>
                                <div class="categories-item">
                                    <ul>
                                        @foreach($categories as $category)
                                            <li>
                                                <a href="{{route('blog-category', $category->id)}}">{{clean($category->name)}}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if(!empty($blogTags))
                            <div class="widget">
                                <div class="sideber-title">
                                    <h4>{{__('Popular Tags')}}</h4>
                                </div>
                                <div class="tags-item">
                                    <ul>
                                        @foreach($blogTags as $blogTag)
                                            <li>
                                                <a href="{{route('blog-tag', $blogTag->tag_id)}}">{{clean($blogTag->tag_name)}}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Service Section -->
@endsection

@section('page-script')

@endsection
