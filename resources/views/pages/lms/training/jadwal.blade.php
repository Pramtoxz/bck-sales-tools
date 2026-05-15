@extends('layouts.app')
@push('css-custom')
<style>
    .fc .fc-toolbar h2 {
        font-size: 14px;
        line-height: 30px;
        text-transform: uppercase;
        background:linear-gradient(180deg, #d58266,#dd2d2d) !important;
        color:white;
        padding:5px 10px;
        border-radius: 10px;
        margin-top:10px;
    }
    /* .fc .fc-day-other .fc-daygrid-day-top {
        opacity: 0;
    }
    .fc .fc-day-other .fc-daygrid-day-events {
        opacity: 0;
    }
    .fc .fc-day-other .fc-daygrid-day-bg {
        opacity: 0;
    } */

    .fc .fc-daygrid-day-number{
        font-weight: bold;
    }

    .fc-daygrid-day-number {
        color:black !important;
    }
    .fc .fc-scroller-liquid-absolute {
        inset: 0px;
        position: absolute;
        right: -10px;
    }
    
</style>
@endpush
@section('content')         
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Jadwal Training</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Jadwal</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card mb-0" style="border-radius:5px;">
                <div class="nav justify-content-start">
                    <img src="{{ asset('assets/images/small/img-9.jpg') }}" class="card-img-top" alt="..." style="max-width: 100%; max-height: 200px; opacity:0.5;">
                </div>
            </div>
            <div class="card" style="border-radius:5px;">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-sm-4 col-8">
                            <span><i class="fas fa-dot-circle text-success"></i> <b>On Time</b></span>
                            <span style="margin-left:10px;"><i class="fas fa-dot-circle text-danger"></i> <b>Overdue</b></span>
                            <span style="margin-left:10px;"><i class="fas fa-dot-circle text-primary"></i> <b>Selesai</b></span>
                            <span style="margin-left:10px;"><i class="fas fa-dot-circle" style="color:#b7dbf9;"></i> <b>Today</b></span> 
                        </div>
                        <div class="col-xl-8 col-lg-8 col-4">
                            <div class="float-end">
                                <ul class="nav justify-content-end nav-pills card-header-pills" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#table1" role="tab" onclick="loadMonth()">
                                            <span class="d-block d-sm-none"><i class="fas fa-clipboard-list"></i></span>
                                            <span class="d-none d-sm-block">Month</span> 
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#table2" role="tab" onclick="loadYear()">
                                            <span class="d-block d-sm-none"><i class="fas fa-calendar-times"></i></span>
                                            <span class="d-none d-sm-block">Year</span> 
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content text-muted">
                        <div class="tab-pane active" id="table1" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="table2" role="tabpanel">
                            <div class="row">
                                @php
                                    $jumlahBulan = 12;
                                @endphp
                                @for ($i = 1; $i <= $jumlahBulan; $i++)
                                    <div class="col-md-3 mb-4">
                                        <div id="calendar-{{$i}}"></div>
                                    </div> 
                                @endfor    
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div style='clear:both'></div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    

</div>
@endsection
@push('script')
<script src="{{asset('assets/libs/fullcalendar/index.global.min.js')}}"></script>
{{-- <script src="{{asset('assets/js/pages/calendar.init.js')}}"></script> --}}

<script>
    $(document).ready(function(){    
        loadMonth();
    })

    function loadYear(){
        r = FullCalendar.Draggable;
        var dataEvents = [];
        var url = "{{route('jadwal_training.show')}}"
        $.ajax({
            url: url,
            type: 'GET',
            async: false,
            success: function(data) {
                console.log(data)
                data.forEach(element => {
                    dataEvents.push({
                        title  : element.nama_karyawan+'-'+element.nama_training,
                        className : element.className,
                        start : new Date(element.awal.year-1,element.awal.month,element.awal.day),
                        end : new Date(element.akhir.year-1,element.akhir.month,element.akhir.day),
                    })
                });
            },

            error: function(xhr, status, error) {
                console.log(error)
                $('#ket_failed_toast').html(JSON.parse(xhr.responseText))
                new bootstrap.Toast(toastfailed).show()
            },
        });
        
        // e = document.getElementById("calendar");
        var jumlahBulan = 12;
        var month = 0;
        for (let index = 1; index <= jumlahBulan; index++) {
            month = index;
            if(index < 10){
                month = "0"+index
            }
            var date = new Date(), y = date.getFullYear();
            var m = index-1;
            var firstDay = new Date(y, m, 1);
            var lastDay = new Date(y, m + 1, 0+1);
            
            e = document.getElementById(`calendar-${index}`);
            var v = new FullCalendar.Calendar(e, {
                fixedWeekCount : false,
                height: 450,
                contentHeight: 400,
                timeZone: "local",
                displayEventTime: false,
                initialView: "dayGridMonth",
                initialDate : `${new Date().getFullYear()}-${month}-01`,
                themeSystem: "bootstrap",
                headerToolbar: { left: "", center: "title", right: "" },
                events: dataEvents,
                validRange: {
                    start: convertDate(firstDay),
                    end: convertDate(lastDay)
                }
            }).render();
        }
    }

    function loadMonth(){
        r = FullCalendar.Draggable;
        var dataEvents = [];
        var url = "{{route('jadwal_training.show')}}"
        $.ajax({
            url: url,
            type: 'GET',
            async: false,
            success: function(data) {
                console.log(data)
                data.forEach(element => {
                    dataEvents.push({
                        title  : element.nama_karyawan+'-'+element.nama_training,
                        className : element.className,
                        start : new Date(element.awal.year-1,element.awal.month,element.awal.day),
                        end : new Date(element.akhir.year-1,element.akhir.month,element.akhir.day),
                    })
                });
            },

            error: function(xhr, status, error) {
                console.log(error)
                $('#ket_failed_toast').html(JSON.parse(xhr.responseText))
                new bootstrap.Toast(toastfailed).show()
            },
        });
        var calendarElement = document.getElementById('calendar');
        var calendarV = new FullCalendar.Calendar(calendarElement, {
                timeZone: "local",
                displayEventTime: false,
                initialView: "dayGridMonth",
                height: "auto",
                themeSystem: "bootstrap",
                headerToolbar: { left: "prev,next today", center: "title", right: "" },
                events: dataEvents,
        }).render()
    }

    function convertDate(date){
        var year = date.toLocaleString("default", { year: "numeric" });
        var month = date.toLocaleString("default", { month: "2-digit" });
        var day = date.toLocaleString("default", { day: "2-digit" });
        return year + "-" + month + "-" + day;
    }

</script>


@endpush


        