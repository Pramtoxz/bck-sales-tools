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
        <div class="col-xl-4 col-sm-12 col-md-6">
            <select class="form-control" id="departement" name="departement">

            </select>
        </div>

        <div class="col-xl-4 col-sm-12 col-md-6">
            <select class="form-control" id="karyawan" name="karyawan">

            </select>
        </div>

        <div class="col-xl-2 col-sm-12 col-md-6">
            <select class="form-control tahun" name="tahun" id="tahun">

            </select>
        </div>

        <div class="col-xl-2 col-sm-12 col-md-6">
            <div class="float-start">
                <button class="btn btn-info btn-md waves-effect waves-light" onclick="loadFilter()"><i class="fas fa-search"></i> Filter</button>
            </div>
        </div>
    </div>
    <hr>
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

                    {{-- @if(Auth::user()->is_admin == 't')
                    <div class="row mt-2">
                        <div class="col-xl-4 col-lg-4 col-4">
                                <button class="btn btn-success btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#containerModal" onclick="tambahLibur()"><i class="bx bx-plus me-1"></i> Tambah Libur Kerja</button>
                        </div>
                    </div>
                    @endif --}}

              
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


{{-- <div class="modal fade" id="containerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Tanggal Libur Kerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                    <form class="needs-validation" novalidate action="#" id="formSubmit">
                    @csrf

                    <input type="hidden" class="form-control" id="tipe_submit" name="tipe_submit" required>
                    <input type="hidden" class="form-control" id="id_data" name="id_data">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-1">
                                <label class="form-label" for="tanggal_libur">Tanggal Libur</label>
                                <input type="text" class="form-control" id="tanggal_libur" name="tanggal_libur" placeholder="Pilih Tanggal Libur (Bisa Pilih Multiple)">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-1">
                                <label class="form-label" for="tahunkerja">Tahun Kerja</label>
                                <select class="form-control tahun" name="tahunkerja" id="tahunkerja"></select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                    </div>
                </div>
                <hr>
                <div class="row mt-2" id="wadah_table">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                        <table class="table" id="data_libur" class="table table-bordered" style="width:100%;">
                            <thead class="table-success" >
                                <tr>
                                    <td>No</td>
                                    <td>Tanggal libur</td>
                                    <td>Tahun</td>
                                    <td>Aksi</td>
                                </tr>
                            </thead>
                        </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div> --}}


    @endsection

    @push('script')
    <script src="{{asset('assets/libs/fullcalendar/index.global.min.js')}}"></script>
    
    <script>
         const tanggal_libur = flatpickr("#tanggal_libur", {
            mode: "multiple"
            , dateFormat: "d-m-Y"
            // , minDate:"today"
        });

    $(document).ready(function(){    
        loadMonth();
        getDepartement();
        getTahun()
        // $('#data_libur').DataTable({})

        $("form#formSubmit").submit(function(e) {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                // console.log(formData);
                var url = "{{route('simpanlibur.post')}}"
                $.ajax({
                    url: url
                    , type: 'POST'
                    , data: formData
                    , async: true
                    , success: function(data) {
                        if (data.status == "true") {
                            // $("#edClose").click();
                            $('#data_libur').DataTable().ajax.reload();

                                Swal.fire({
                                    icon: 'success'
                                    , title: 'Berhasil'
                                    , text: 'Berhasil Simpan Libur!'
                                    , confirmButtonText: 'OK'
                                    });
                        
                           
                            $("#formSubmit")[0].reset();

                        } else {
            
                            Swal.fire({
                                    icon: 'warning'
                                    , title: 'Gagal!!!'
                                    , text: data.message
                                    , confirmButtonText: 'OK'
                                    });
                        }
          
                    },
                    error: function(xhr, status, error) {
                        console.log(error)
                        $('#ket_failed_toast').html(JSON.parse(xhr.responseText))
                        new bootstrap.Toast(toastfailed).show()
                    }
                    , cache: false
                    , contentType: false
                    , processData: false
                });
            });


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
        for (let i = 1; i <= 12; i++) {
        document.getElementById(`calendar-${i}`).innerHTML = '';
        }

        loadMonth();  
        loadYear();
    }

    function loadYear() {
    karyawan = $('#karyawan').val();
    karyawan = karyawan == null ? "" : karyawan;
    departement = $('#departement').val();
    departement = departement == null ? "" : departement;
    let tahun = $('#tahun').val() || new Date().getFullYear();

    var dataCuti = [];
    // console.log(dataCuti);
    var url = `{{ route('jadwal_cuti.show') }}?departement=${departement}&karyawan=${karyawan}&tahun=${tahun}`;
    
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

    // var jumlahBulan = 12;
    // for (let index = 1; index <= jumlahBulan; index++) {
    //     var month = index < 10 ? "0" + index : index;
    //     var date = new Date();
    //     var year = tahun ? tahun : date.getFullYear();
    //     var firstDay = new Date(year, index - 1, 1);
    //     var lastDay = new Date(year, index, 0);

    //     var e = document.getElementById(`calendar-${index}`);
    //     new FullCalendar.Calendar(e, {
    //         fixedWeekCount: false,
    //         height: 450,
    //         contentHeight: 400,
    //         timeZone: "local",
    //         displayEventTime: false,
    //         initialView: "dayGridMonth",
    //         initialDate: `${year}-${month}-01`,
    //         themeSystem: "bootstrap",
    //         headerToolbar: { left: "", center: "title", right: "" },
    //         events: dataCuti,
    //         validRange: {
    //             start: convertDate(firstDay),
    //             end: convertDate(lastDay)
    //         },
    //         eventDidMount: function(info) {
    //             $(info.el).tooltip({
    //                 title: info.event.title,
    //                 placement: 'top',
    //                 trigger: 'hover',
    //                 container: 'body'
    //             });
    //         }
    //     }).render();
    // }

    for (let index = 1; index <= 12; index++) {
        let month = index < 10 ? "0" + index : index;
        let firstDay = new Date(tahun, index - 1, 1);
        let lastDay = new Date(tahun, index, 0);

        let calendarEl = document.getElementById(`calendar-${index}`);

        let calendar = new FullCalendar.Calendar(calendarEl, {
            fixedWeekCount: false,
            height: 450,
            contentHeight: 400,
            timeZone: "local",
            displayEventTime: false,
            initialView: "dayGridMonth",
            initialDate: `${tahun}-${month}-01`,
            themeSystem: "bootstrap",
            headerToolbar: { left: "", center: "title", right: "" },
            events: dataCuti,
            validRange: {
                start: convertDate(firstDay),
                end: convertDate(lastDay)
            },
            eventDidMount: function(info) {
                new bootstrap.Tooltip(info.el, {
                    title: info.event.title,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            }
        });

        calendar.render();
    }

}

function loadMonth() {
    karyawan = $('#karyawan').val();
    karyawan = karyawan == null ? "" : karyawan;
    departement = $('#departement').val();
    departement = departement == null ? "" : departement;
    let tahun = $('#tahun').val() || new Date().getFullYear();

    var dataCuti = [];
    var url = `{{ route('jadwal_cuti.show') }}?departement=${departement}&karyawan=${karyawan}&tahun=${tahun}`;

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

    function getTahun() {
            $.ajax({
                type: "GET",
                url: '{{ route("getYear.all") }}',
                async: true,
                success: function(data) {
                    $('.tahun').select2({
                        data: data.map(function(year) {
                            return { id: year, text: year };
                        }),
                        theme: "bootstrap-5"
                    });

                    var tahunsekarang = new Date().getFullYear();
                    $('.tahun').val(tahunsekarang).trigger('change');
                    
                },
                error: function(data, textStatus, jqXHR) {

                }
            });
        }

        function deleteData(id){
        Swal.fire({
            title:"Yakin Hapus Data ?",
            icon:"warning",
            showCancelButton:!0,
            confirmButtonColor:"#2ab57d",
            cancelButtonColor:"#fd625e",
            confirmButtonText:"Hapus !"}).then(function(e){
                if(e.value){
                    var url = "{{route('tanggallibur.delete')}}"
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { 
                            id
                        },
                        async: true,
                        success: function(data) {
                            if(data){
                                $('#ket_success_toast').html("Sukses Hapus Data")
                                new bootstrap.Toast(toastsuccess).show()
                                $('#data_libur').DataTable().ajax.reload();
                            }else{
                                $('#ket_failed_toast').html("Gagal Hapus Data")
                                new bootstrap.Toast(toastfailed).show()
                            }
                        },

                        error: function(xhr, status, error) {
                            console.log(error)
                            $('#ket_failed_toast').html(JSON.parse(xhr.responseText))
                            new bootstrap.Toast(toastfailed).show()
                        },
                    }); 
                }
            })
        }

        // function tambahLibur() {
        //     $('#ket_submit').html('Tambah');
        //     $('#tipe_submit').val("add");
        //     $('#id_data').val("");

        //     $('#tahun').val("");

        //     tanggal_libur.clear()

        //     loadDataLibur();
        // }

        // function loadDataLibur(){
        //     $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
        //     return {
        //         "iStart": oSettings._iDisplayStart,
        //         "iEnd": oSettings.fnDisplayEnd(),
        //         "iLength": oSettings._iDisplayLength,
        //         "iTotal": oSettings.fnRecordsTotal(),
        //         "iFilteredTotal": oSettings.fnRecordsDisplay(),
        //         "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
        //         "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
        //     };
        // };
    
        // dataTableMaster = $('#data_libur').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     bDestroy: true,
        //     paging: true,
        //     pageLength : 10,
        //     ajax: "{{url('data_libur/data')}}",
        //     columns: [{
        //             "searchable": false,
        //             "targets": 0,
        //             "data": null,
        //             "width": "10px",
        //             "sClass": "text-center",
        //             "orderable": false
        //         },
        //         {
        //             data: 'tanggal_libur',
        //             name: 'tanggal_libur'
        //         },
        //         {
        //             data: 'tahun',
        //             name: 'tahun'
        //         },
        //         {
        //             data: 'aksi',
        //             name: 'aksi'
        //         },
        //     ],
    
        //     "rowCallback": function(row, data, iDisplayIndex) {
        //         var info = this.fnPagingInfo();
        //         var page = info.iPage;
        //         var length = info.iLength;
        //         var index = page * length + (iDisplayIndex + 1);
        //         $('td:eq(0)', row).html(index);
        //     },
        // });
        // }

    </script>


    @endpush
