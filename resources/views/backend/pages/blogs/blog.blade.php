@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')

@endsection

@section('content')

<div id="wrapper-content">

    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                <a class="breadcrumb-item text-white" href="{{route('admin.dashboard')}}">{{__('Home')}}</a>
                <span class="breadcrumb-item active">{{ __($title) }}</span>
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
                            <h6 class="card-title lh-35">{{ __($title) }}</h6>
                        </div>
                        <div class="col-md-6 col-sm-12 text-right">
                            <a href="{{ route('admin.blog.weblog.create') }}" class="btn btn-danger btn-sm rounded"><i class="material-icons">add</i></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive style-scroll">
                        <table id="example" class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th width="10%">{{__('SL')}}</th>
                                <th>{{__('Blog Name')}}</th>
                                <th>{{__('BG Image')}}</th>
                                <th>{{__('Feature Image')}}</th>
                                <th>{{__('Category')}}</th>
                                <th>{{__('Description')}}</th>
                                <th width="10%">{{__('Popular')}}</th>
                                <th width="10%"> {{__('Featured')}}</th>
                                <th width="10%">{{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($blogs as $blog)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{$blog->title}}</td>
                                    <td>
                                        <img src="{{ asset('upload/blogs/bg-images/'.$blog->bg_image) }}" alt="Background image" width="80" height="80">
                                    </td>
                                    <td>
                                        <img src="{{ asset('upload/blogs/features/'.$blog->feature_img) }}" alt="Feature image" width="80" height="80">
                                    </td>

                                    <td>{{ $blog->category_name }}</td>
                                    <td>{{ Str::limit(strip_tags($blog->description),60,'...') }}</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" {{ $blog->is_popular?'checked':'' }} id="{{ $blog->id }}" class="blogIsPopularBtn">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" {{ $blog->is_featured?'checked':'' }} id="{{ $blog->id }}" class="blogIsFeaturedBtn">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('admin.blog.weblog.edit',$blog->id) }}">
                                                <button type="button" class="btn btn-sm btn btn-success m-1">{{ __('Edit') }}</button>
                                            </a>

                                            <a href="javascript:void(0)" title="{{__('Delete')}}" class="deleteRow">
                                                <button type="button" class="btn btn-sm btn btn-danger m-1">{{__('Delete')}}</button>
                                                <form action="{{ route('admin.blog.weblog.destroy', $blog->id) }}" method="post" class="deleteForm">
                                                    @csrf
                                                    @method('delete')
                                                </form>
                                            </a>


                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')
    @include('backend.pages.blogs.internal-assets.js.delete-warning')
    <script src="{{asset('backend/assets/js/tag-modal.js')}}"></script>
    <script src="{{ asset('backend/assets/js/blog-feature-popular.js') }}"></script>
@endsection
