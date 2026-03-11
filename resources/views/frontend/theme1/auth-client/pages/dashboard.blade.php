@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'laravel'). ' | '.$title)

@section('page-css')

@endsection

@section('content')
    <div class="main-wrapper">
        <div class="cardBox">
            <div class="card">
                <div class="col-12 p-0">
                    <div class="float-left d-inline">
                        <div class="number">{{ $hardshipCount }}</div>
                        <div class="cardName">{{ __('Total Hardship') }}</div>
                    </div>
                    <div class="iconBox float-right d-inline">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="col-12 p-0">
                    <div class="float-left d-inline">
                        <div class="number">{{ $conversationCount }}</div>
                        <div class="cardName">{{ __('Total Conversations') }}</div>
                    </div>
                    <div class="iconBox float-right d-inline">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="col-12 p-0">
                    <div class="float-left d-inline">
                        <div class="number">{{ $unreadMessageCount }}</div>
                        <div class="cardName">{{ __('Unread Messages') }}</div>
                    </div>
                    <div class="iconBox float-right d-inline">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('page-script')

@endsection
