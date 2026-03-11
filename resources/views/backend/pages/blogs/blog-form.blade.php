@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')
    <link href="{{asset('backend/assets/plugin/select2/select2.min.css')}}" rel="stylesheet"/>
@endsection

@section('content')

    <div id="wrapper-content">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white" href="{{route('admin.dashboard')}}">{{__('Home')}}</a>
                    <span class="breadcrumb-item active">{{$title}}</span>
                    <span class="breadcrumb-info" id="time"></span>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-dark bg-dark">

                    <div class="card-header d-block">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <h6 class="card-title lh-35">{{$title}}</h6>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <form
                            action="{{ $blog? route('admin.blog.weblog.update',$blog->id):route('admin.blog.weblog.store') }}"
                            method="POST" enctype="multipart/form-data" class="wma-form">
                            @csrf
                            @if($blog)
                                @method('PUT')
                            @endif


                            <p class="mb-1"><label for="name"
                                                   class="card-title font-weight-bold">{{__('Title :')}}</label></p>
                            <div class="input-group input-group-lg mb-3">
                                <input type="text" name="title" value="{{__($blog?$blog->title:old('title')) }}"
                                       class="form-control" aria-label="Large"
                                       aria-describedby="inputGroup-sizing-sm" placeholder="{{__('Title')}}" required>
                            </div>


                            <p class="mb-1"><label for="name"
                                                   class="card-title font-weight-bold">{{__('Category :')}}</label></p>
                            <div class="input-group input-group-lg mb-3">
                                <select class="form-control form-control-lg" name="category_id">
                                    <option value="">{{__('Select one')}}</option>

                                    @foreach($categories as $category)
                                        <option
                                            value="{{ $category->id }}" {{ $blog?($blog->category_id==$category->id?'selected':''):'' }}>{{$category->name}}</option>
                                    @endforeach

                                </select>
                                <br>
                            </div>

                            <p class="h6 mb-3">{{ __('Background Image') }}:<code>{{__('(Only jpeg, png, jpg and gif file is
                                acceptable)')}}</code></p>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="">
                                        <div class="thumbnail-image" id="thumbnail_image">
                                            <div class="input-images"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-center">
                                    @if($blog)
                                        <img src="{{ asset('upload/blogs/bg-images/'.$blog->bg_image)  }}"
                                             class="img-thumbnail " width="35%" height="200">
                                    @endif
                                </div>

                            </div>

                            <p class="h6 mb-3">{{ __('Feature Image') }}:<code>{{__('(Only jpeg, png, jpg and gif file is
                                acceptable)')}}</code></p>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="">
                                        <div class="feature-image" id="feature_image">
                                            <div class="input-images"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-center">
                                    @if($blog)
                                        <img src="{{ asset('/upload/blogs/features/'.$blog->feature_img)  }}"
                                             class="img-thumbnail " width="35%">
                                    @endif
                                </div>


                            </div>

                            <div class="form-group">
                                <label for="pageContent">
                                    <span class="d-block card-title mb-1 font-weight-bold">{{__('Description')}} </span>
                                </label>
                                <textarea name="description" id="description" rows="10"
                                          class="form-control bapric_edittor"
                                          placeholder="{{__('Blog description here')}}">{!! clean($blog?$blog->description:old('description')) !!}</textarea>
                            </div>

                            <p class="mb-1"><label for="name"
                                                   class="card-title font-weight-bold">{{__('Tags :')}}</label></p>
                            <div class="input-group input-group-lg mb-3">
                                <select class="form-control form-control-lg blog_tag_multiple_input" name="blog_tags[]"
                                        multiple="multiple">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                        @if(!empty($blog_tags))
                                            @foreach($blog_tags as $blog_tag)
                                                {{$blog_tag->tag_id == $tag->id?'selected':''}}
                                                @endforeach
                                            @endif
                                        >{{$tag->name}}</option>
                                    @endforeach

                                </select>
                                <br>
                            </div>

                            <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                            <div class="wizard-action text-left">
                                <button class="btn btn-wave-light btn-danger btn-lg"
                                        type="submit">{{__('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('backend/assets/plugin/select2/select2.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/blog.js') }}"></script>
    @include('backend.pages.blogs.internal-assets.js.delete-warning')
    @include('backend.layouts.message')
@endsection
