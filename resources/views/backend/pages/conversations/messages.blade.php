@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'Bapric').' | '.$title)

@section('page-css')
    <style>

    </style>
@endsection

@section('content')
    <div id="wrapper-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                        <a class="breadcrumb-item text-white"
                           href="{{route('admin.dashboard')}}">{{__('Home')}}</a>
                        <a class="breadcrumb-item text-white"
                           href="{{route('admin.conversation.index')}}">{{__('All Conversation')}}</a>
                        <span class="breadcrumb-item active">{{__($title)}}</span>
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
                                    <h6 class="card-title">{{__($title)}}</h6>
                                </div>
                                <div class="col-md-6 col-sm-12 text-right">

                                </div>
                            </div>
                        </div>
                        <div class="card-body messages-content">
                            <div class="msg_card_body">
                                @foreach($conversation->messages as $message)
                                    @if($message->user->id === Auth::user()->id)
                                        @if($message->file)
                                            <div class="d-flex justify-content-end mb-4">
                                                <div class="msg_cotainer_send px-5">
                                                    {!! $message->file?'<a href="'.route('download.chatting-file',$message->id).'"><i class="fas fa-file"></i> &nbsp;'.$message->file_name.'</a></br>':'' !!}
                                                    <span class="msg_time_send">{{ date('H:i',strtotime($message->created_at)) }}, {{ strtotime(date('Y-m-d',strtotime($message->created_at))) === date('Y-m-d',time()) ? 'Today':date('Y-m-d',strtotime($message->created_at)) }}</span>
                                                </div>
                                                <div class="img_cont_msg">
                                                    <img src="{{ asset(Auth::user()->profile_photo_url) }}" class="rounded-circle user_img_msg">
                                                </div>
                                            </div>
                                        @endif
                                        @if($message->text)
                                            <div class="d-flex justify-content-end mb-4">
                                                <div class="msg_cotainer_send px-5">
                                                    {{ clean($message->text) }}
                                                    <span class="msg_time_send">{{ date('H:i',strtotime($message->created_at)) }}, {{ strtotime(date('Y-m-d',strtotime($message->created_at))) === date('Y-m-d',time()) ? 'Today':date('Y-m-d',strtotime($message->created_at)) }}</span>
                                                </div>
                                                <div class="img_cont_msg">
                                                    <img src="{{ asset(Auth::user()->profile_photo_url) }}" class="rounded-circle user_img_msg">
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        @if($message->file)
                                            <div class="d-flex justify-content-start mb-4">
                                                <div class="img_cont_msg">
                                                    <img src="{{ asset($message->user->profile_photo_url) }}" class="rounded-circle user_img_msg">
                                                </div>
                                                <div class="msg_cotainer px-5">
                                                    {!! $message->file?'<a href="'.route('download.chatting-file',$message->id).'"><i class="fas fa-file"></i> &nbsp;'.$message->file_name.'</a></br>':'' !!}
                                                    <span class="msg_time">{{ date('H:i',strtotime($message->created_at)) }}, {{ strtotime(date('Y-m-d',strtotime($message->created_at))) === date('Y-m-d',time()) ? 'Today':date('Y-m-d',strtotime($message->created_at)) }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if($message->text)
                                            <div class="d-flex justify-content-start mb-4">
                                                <div class="img_cont_msg">
                                                    <img src="{{ asset($message->user->profile_photo_url) }}" class="rounded-circle user_img_msg">
                                                </div>
                                                <div class="msg_cotainer px-5">
                                                    {{ clean($message->text) }}
                                                    <span class="msg_time">{{ date('H:i',strtotime($message->created_at)) }}, {{ strtotime(date('Y-m-d',strtotime($message->created_at))) === date('Y-m-d',time()) ? 'Today':date('Y-m-d',strtotime($message->created_at)) }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="col-12">
                                <form action="{{ route('admin.conversation.send-message', $conversation->slug) }}" method="post" enctype="multipart/form-data" id="chatForm">
                                    @csrf
                                    <input type="text" name="text" id="chatTaxtInput" class="form-control" placeholder="Write your text.">
                                    <input type="file" hidden name="file" id="chatFileInput" class="form-control">
                                    <label for="chatFileInput" class="chatFileInputLabel"><i class="fas fa-paperclip"></i></label>
                                    <button type="submit" class="btn btn-danger btn-sm rounded"><i class="fas fa-paper-plane"></i></button>
                                </form>
                                <p class="text-right" id="showSelectedFile"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    @include('backend.pages.appointments.internal-assets.js.delete-warning')
    @include('backend.layouts.message')
    <script src="{{ asset('backend/assets/js/file-name-view.js') }}"></script>
@endsection
