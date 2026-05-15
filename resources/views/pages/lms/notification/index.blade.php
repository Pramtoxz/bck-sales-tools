@extends('layouts.app')
@section('content')         
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Data Notifications</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Notifications</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius:5px;">
                <div class="card-body">
                    <div class="row" id="notifications">
                        @foreach(auth()->user()->unreadNotifications as $notification)
                        <a href="{{ url($notification->data['url'] . '?id=' . $notification->id) }}" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{asset('assets/images/logo-ma-small.png')}}" class="rounded-circle avatar-sm" alt="user-pic">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $notification->data['title'] }}</h6>
                                    <div class="font-size-13 text-muted">
                                        <p class="mb-1">{{ ucwords($notification->data['message']) }}</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>{{ $notification->created_at->diffForHumans() }}</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
           
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

</div>
@endsection
@push('script')

@endpush


        