@extends('layouts.app')
@push('css-custom')
<style>
    button {
        transition: transform 0.3s;
        cursor: pointer;
    }

    button:hover {
        transform: scale(1.05);
    }

    .card {
        border: none;
    }

    /* .valueCard{
        font-size:1.0em;
    } */
    .valueCard1 {
        font-size: 0.75em;
    }

    .fc .fc-toolbar h2 {
        font-size: 14px;
        line-height: 30px;
        text-transform: uppercase;
        background:linear-gradient(180deg, #d58266,#921904) !important;
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
<link rel="stylesheet" href="{{asset('assets/kendo-ui/styles/kendo.common-material.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/kendo-ui/styles/kendo.material.min.css')}}" />

<style>
    #container {
        width: 1000px;
        margin: 20px auto;
    }

    .table-responsive table {
        /* background-color: #ffffff;
      border-collapse: collapse; */
        width: 100%;
        max-width: 100%;
        /* margin-bottom: 1rem; */
        /* color: #212529; */
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .table-responsive table th,
    .table-responsive table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .table-responsive table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid rgba(0, 0, 0, 0.1);
    }

    .table-responsive table tbody tr:nth-of-type(even) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .table-responsive table th {
        font-weight: 700;
        color: #495057;
        background-color: rgba(0, 0, 0, 0.03);
    }

</style>
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Kalender Cuti</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Kalender Cuti</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    @if(Auth::user()->is_admin == 't')
    <div class="row d-flex">
        <div class="col-xl-4 col-sm-12 col-md-6 mb-3">
            <select class="form-control" id="departement" name="departement">

            </select>
        </div>
        {{-- <div class="col-1 mb-3">
            <a onClick="carikaryawan()" class="btn btn-primary btn-md waves-effect waves-light" style="margin-left: 10px;"> <i class="fas fa-search" type="submit"></i></a>
        </div> --}}
        <div class="col-xl-4 col-sm-12 col-md-6 mb-3">
            <select class="form-control" id="karyawan" name="karyawan">

            </select>
        </div>

        {{-- <div class="col-3 mb-3">
            <select class="form-control" id="tahun" name="tahun">

            </select>
        </div> --}}

        <div class="col-xl-4 col-sm-12 col-md-12 mb-3">
            <div class="float-start">
                <button class="btn btn-info btn-md waves-effect waves-light" onclick="loadFilter()"><i class="fas fa-search"></i> Filter</button>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius:5px;">
                <div class="card-header">
                    
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-sm-4 col-8">
                            <span><i class="fas fa-dot-circle text-success"></i> <b>On Time</b></span>
                            <span style="margin-left:10px;"><i class="fas fa-dot-circle text-danger"></i> <b>Overdue</b></span>
                            {{-- <span style="margin-left:10px;"><i class="fas fa-dot-circle text-primary"></i> <b>Selesai</b></span> --}}
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
</div>
</div>


    @endsection

    @push('script')
    <script src="{{asset('assets/libs/fullcalendar/index.global.min.js')}}"></script>
    
    <script>
    $(document).ready(function(){    
        loadMonth();
        getDepartement();
        // getTahun();

        $('#departement').on('change', function() {
                var kd_departement = $('#departement').val();

                $.ajax({
                    url: '{{url("/cuti/ambilkaryawan?kd_departement=")}}' + kd_departement
                    , type: 'GET'
                    , async: false
                    , success: function(data) {
                        $('#karyawan').html('<option value="">Pilih Karyawan</option>');
                        $('#karyawan').select2({
                            data: data,
                            theme: "bootstrap-5"
                        }); 
                    }
                });
            });

        })

    function loadFilter()
    {
        loadMonth();  
        loadYear();
    }

    function loadYear() {
    karyawan = $('#karyawan').val();
    karyawan = karyawan == null ? "" : karyawan;
    departement = $('#departement').val();
    departement = departement == null ? "" : departement;

    var dataCuti = [];
    // console.log(dataCuti);
    var url = `{{ route('jadwal_cuti.show') }}?departement=${departement}&karyawan=${karyawan}`;
    
    $.ajax({
        url: url,
        type: 'GET',
        async: false,
        success: function(data) {
            console.log(data);
            data.forEach(element => {
                element.tanggal_cuti.forEach(tgl => { 
                    dataCuti.push({
                        title: element.nama_karyawan + ' - ' + element.perihal_cuti,
                        className: element.className,
                        start: tgl,
                        end: tgl
                    });
                });
            });
            console.log(dataCuti);
        },
        error: function(xhr, status, error) {
            console.log(error);
            $('#ket_failed_toast').html(JSON.parse(xhr.responseText));
            new bootstrap.Toast(toastfailed).show();
        }
    });

    var jumlahBulan = 12;
    for (let index = 1; index <= jumlahBulan; index++) {
        var month = index < 10 ? "0" + index : index;
        var date = new Date();
        var year = date.getFullYear();
        var firstDay = new Date(year, index - 1, 1);
        var lastDay = new Date(year, index, 0);

        var e = document.getElementById(`calendar-${index}`);
        new FullCalendar.Calendar(e, {
            fixedWeekCount: false,
            height: 450,
            contentHeight: 400,
            timeZone: "local",
            displayEventTime: false,
            initialView: "dayGridMonth",
            initialDate: `${year}-${month}-01`,
            themeSystem: "bootstrap",
            headerToolbar: { left: "", center: "title", right: "" },
            events: dataCuti,
            validRange: {
                start: convertDate(firstDay),
                end: convertDate(lastDay)
            },
            eventDidMount: function(info) {
                $(info.el).tooltip({
                    title: info.event.title,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            }
        }).render();
    }
}

function loadMonth() {
    karyawan = $('#karyawan').val();
    karyawan = karyawan == null ? "" : karyawan;
    departement = $('#departement').val();
    departement = departement == null ? "" : departement;


    var dataCuti = [];
    var url = `{{ route('jadwal_cuti.show') }}?departement=${departement}&karyawan=${karyawan}`;

    $.ajax({
        url: url,
        type: 'GET',
        async: false,
        success: function(data) {
            console.log(data);
            data.forEach(element => {
                element.tanggal_cuti.forEach(tgl => {
                    dataCuti.push({
                        title: element.nama_karyawan + ' - ' + element.perihal_cuti,
                        className: element.className,
                        start: tgl,
                        end: tgl
                    });
                });
            });
            console.log(dataCuti);
        },
        error: function(xhr, status, error) {
            console.log(error);
            $('#ket_failed_toast').html(JSON.parse(xhr.responseText));
            new bootstrap.Toast(toastfailed).show();
        }
    });

    var calendarElement = document.getElementById('calendar');
    new FullCalendar.Calendar(calendarElement, {
        timeZone: "local",
        displayEventTime: false,
        initialView: "dayGridMonth",
        height: "auto",
        themeSystem: "bootstrap",
        headerToolbar: { left: "prev,next today", center: "title", right: "" },
        events: dataCuti,
    }).render();
}

function convertDate(date) {
    var year = date.getFullYear();
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var day = ("0" + date.getDate()).slice(-2);
    return year + "-" + month + "-" + day;
}

function getDepartement() {
        $.ajax({
            type: "GET"
            , url: '{{route("departement.all")}}'
            , async: true
            , success: function(data) {
                    $('#departement').html('<option value="">Pilih Departement</option>');
                    $('#departement').select2({
                    data: data,
                    theme: "bootstrap-5"
                });  
            }
            , error: function(data, textStatus, jqXHR) {}
        , });
    }

    // function getTahun() {
    //         $.ajax({
    //             type: "GET",
    //             url: '{{ route("getYear.all") }}',
    //             async: true,
    //             success: function(data) {
    //                 $('#tahun').select2({
    //                     data: data.map(function(year) {
    //                         return { id: year, text: year };
    //                     }),
    //                     theme: "bootstrap-5"
    //                 });

    //                 var tahunsekarang = new Date().getFullYear();

    //                 $('#tahun').val(tahunsekarang).trigger('change');
                    
    //             },
    //             error: function(data, textStatus, jqXHR) {

    //             }
    //         });
    //     }
    </script>


    @endpush
