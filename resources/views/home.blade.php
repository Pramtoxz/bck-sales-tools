@extends('layouts.app')

@push('css-custom')
<style>
    .img-home {
        width:35% !important;
    }
    @media only screen and (max-width: 600px) {
        .img-home {
            width: 100% !important;
        }
    }
    @media only screen and (max-width: 800px) {
        .img-home {
            width: 80% !important;
        }
    }
</style>
@endpush
@section('content')
<div class="container-fluid">


    <div class="row">
        <div class="col-12 text-center">
            <div class="pt-4">
                <img src="{{asset('assets/images/lms/background.gif')}}" alt="" class="img-home" >
            </div>
            <div class="pt-2">
                @php
                    $nm_service = DB::table('service_apps')->where('kd_service_apps',Session::get('kd_service_apps'))->select("name_apps")->first();
                @endphp
                <h1 style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;color:#303131;">Menara Agung</h1>
                <h3 style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;color:#303131;margin-bottom:0;"><b>{{$nm_service->name_apps ?? "Menara Agung"}}</b></h3>
            </div>
        </div><!-- end col -->   
    </div><!-- end row-->

</div>
@endsection

@push('script')
{{-- <script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{asset('assets/js/pages/dashboard.init.js')}}"></script> --}}

@endpush
