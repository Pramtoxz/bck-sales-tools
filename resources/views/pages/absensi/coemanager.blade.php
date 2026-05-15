@extends('layouts.app')
@push('css-custom')
<style>
.telat {
    color:rgb(248, 21, 21) !important;
}
.calendar-container {
    max-width: 100%;
    margin: auto;
    background: #189dfb;
    /* background: hotpink; */
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    padding: 15px;
    font-family: 'Segoe UI', sans-serif;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

#prev-month{
    font-weight: 1000;
    cursor: pointer;
    border-radius: 8px;
    color: yellow;
}

#next-month{
    font-weight: 1000;
    cursor: pointer;
    border-radius: 8px;
    color: yellow;
}

.month-year {
    color: yellow;
    font-size: 18px;
    font-weight: 600;
    /* color:yellow; */
}

.calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    font-weight: bold;
    margin-bottom: 8px;
    color: green;
    /* color: hotpink; */
    /* background: #f0f0f0; */
    background: aqua;
    padding: 5px 0;
    border-radius: 5px;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
}

.calendar-day {
    background-color: #f8f8f8;
    transition: transform 0.3s, box-shadow 0.3s;
    /* background-color: #f8f8; */
    border-radius: 6px;
    min-height: 80px;
    padding: 6px;
    position: relative;
    box-shadow: inset 0 0 0 1px #e0e0e0;
    /* box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); */
    transition: background-color 0.2s;
}

.calendar-day:hover {
    background-color: #eaf2ff;
    transform: scale(1.02);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
}

.day-number {
    font-size: 14px;
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
}

.calendar-event {
    font-size: 11px;
    padding: 2px 5px;
    border-radius: 4px;
    margin-top: 4px;
    color: #fff;
    word-wrap: break-word;
}

/* .tanggalkosong{
    background-color: #;
    box-shadow: none;
    cursor: default;
} */

.bg-danger { background-color: #e74c3c; }
.bg-success { background-color: #27ae60; }
.bg-primary { background-color: #3498db; }

/* Responsif */
@media (max-width: 680px) {
    .calendar-day {
        min-height: 60px;
        padding: 4px;
    }
    .day-number {
        font-size: 10px;
    }
    .calendar-event {
        font-size: 8px;
    }
    /* .nav-btn {
        padding: 6px 10px;
        font-size: 12px;
    } */
    .month-year {
        font-size: 16px;
    }
    .infos{
        display: none;
    }
}

@media (max-width: 550px) {
    .calendar-day {
        min-height: 10px;
        padding: 2px;
    }
    .day-number {
        font-size: 4px;
    }
    .calendar-event {
        font-size: 4px;
    }
    /* .nav-btn {
        padding: 1px 3px;
        font-size: 4px;
    } */
    .month-year {
        font-size: 14px;
    }
    .calendar-weekdays{
        font-size: 9px;
    }
    .infos{
        display: none;
    }
}
    /* .grid-kalender {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }
    .calendar-day {
        border: 1px solid #ddd;
        padding: 5px;
        min-height: 80px;
        position: relative;
    }
    .day-number {
        font-weight: bold;
    }
    .bg-danger { background-color: #f8d7da; }
    .bg-success { background-color: #d4edda; }
    .bg-primary { background-color: #cce5ff; }
    .calendar-event {
        font-size: 12px;
        margin-top: 5px;
    } */


</style>
@endpush
@section('content')         
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Data Calender Of All Events</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Calender Of All Events</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->



    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius:5px;">
                
                <div class="card-header">
                    <div class="row d-flex">
                        @if(Auth::user()->is_admin == 't')
                        <div class="col-xl-3 col-sm-12 col-md-6">
                            <select class="form-control" id="departement" name="departement">
                
                            </select>
                        </div>
                
                        <div class="col-xl-3 col-sm-12 col-md-6">
                            <select class="form-control" id="karyawan" name="karyawan">
                                <option value="">--First, Selection Department--</option>
                            </select>
                        </div>
                        @endif
                        <div class="col-xl-2 col-sm-12 col-md-6">
                            <select class="form-control tahun" name="tahun" id="tahun">
                
                            </select>
                        </div>

                        <div class="col-xl-2 col-sm-12 col-md-6">
                            <select class="form-control bulan" name="bulan" id="bulan">
                
                            </select>
                        </div>
                
                        <div class="col-xl-2 col-sm-12 col-md-6">
                            {{-- <div class="float-start"> --}}
                                <button class="btn btn-info btn-md waves-effect waves-light" onclick="loadFilter()"><i class="fas fa-search"></i> Filter Data COE</button>
                            {{-- </div> --}}
                        </div>
                    </div>
                    {{-- <hr> --}}
                </div>
               
                <div class="card-body">

                    <div class="calendar-container">
                        <div class="calendar-header">
                            <div>
                            <button id="prev-month" class="btn btn-primary btn-md waves-effect waves-light">&lt; Prev</button>
                            <button class="btn btn-info btn-md waves-effect waves-light infos">Info Cuti &nbsp;&nbsp;&nbsp;&nbsp;</button>
                            </div>
                            <span id="month-year" class="month-year"></span>
                            <div>
                            <button class="btn btn-success btn-md waves-effect waves-light infos">Info Training</button>
                            <button id="next-month" class="btn btn-primary btn-md waves-effect waves-light">Next &gt;</button>
                            </div>
                        </div>
                        <div class="calendar-weekdays">
                            <div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div>
                        </div>
                        <div id="kalender_costume" class="calendar-grid"></div>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> 
    <!-- end row -->

</div>



@endsection
@push('script')
<script>
    
    const listBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    
    let tanggalSekarang = new Date();

    let ambiltahunsekarang = tanggalSekarang.getFullYear();
    let ambilbulansekarang = tanggalSekarang.getMonth();

    $(document).ready(function() {
        buatKalender(tanggalSekarang.getFullYear(), tanggalSekarang.getMonth());

        $('#prev-month').click(function() {
            if (ambilbulansekarang === 0) {
                ambilbulansekarang = 11;
                ambiltahunsekarang--;
            } else {
                ambilbulansekarang--;
            }

            buatKalender(ambiltahunsekarang, ambilbulansekarang, 
            $('#departement').val(), $('#karyawan').val(), $('#tahun').val());
            // tanggalSekarang.setMonth(tanggalSekarang.getMonth() - 1);
            // buatKalender(tanggalSekarang.getFullYear(), tanggalSekarang.getMonth());
        });

        $('#next-month').click(function() {
            if (ambilbulansekarang === 11) {
                ambilbulansekarang = 0;
                ambiltahunsekarang++;
            } else {
                ambilbulansekarang++;
            }

            buatKalender(ambiltahunsekarang, ambilbulansekarang, 
                $('#departement').val(), $('#karyawan').val(), $('#tahun').val());
            // tanggalSekarang.setMonth(tanggalSekarang.getMonth() + 1);
            // buatKalender(tanggalSekarang.getFullYear(), tanggalSekarang.getMonth());
        });

        getDepartement();
        getTahun();
        getBulan();

        $('#departement').on('change', function() {
                var kd_departement = $('#departement').val();

                $.ajax({
                    url: '{{url("/cuti/ambilkaryawan?kd_departement=")}}' + kd_departement
                    , type: 'GET'
                    , async: false
                    , success: function(data) {
                        $('#karyawan').html('<option value="">Select Employees Here</option>');
                        $('#karyawan').select2({
                            data: data,
                            theme: "bootstrap-5"
                        }); 
                    }
                });
            });

        

    });

    // function buatKalender(year, month) {
    //     $('#kalender_costume').empty();
    //     $('#month-year').text(`${listBulan[month]} ${year}`);

    //     const firstDay = new Date(year, month, 1).getDay();
    //     const daysInMonth = new Date(year, month + 1, 0).getDate();


    //     const startDay = (firstDay === 0 ? 6 : firstDay - 1);
    //     for (let i = 0; i < startDay; i++) {
    //         $('#kalender_costume').append('<div class="calendar-day2"></div>');
    //     }

    //     for (let day = 1; day <= daysInMonth; day++) {
    //         const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    //         const dayCell = $(`
    //             <div class="calendar-day" data-date="${dateStr}">
    //                 <div class="day-number">${day}</div>
    //             </div>
    //         `);
    //         $('#kalender_costume').append(dayCell);
    //     }

    //     dataKalender();
    // }

    function buatKalender(year, month, departement = null, karyawan = null, tahun = null) {
        $('#kalender_costume').empty();
        $('#month-year').text(`${listBulan[month]} ${year}`);

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const startDay = (firstDay === 0 ? 6 : firstDay - 1);

        for (let i = 0; i < startDay; i++) {
            $('#kalender_costume').append('<div class="calendar-day2"></div>');
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const dayCell = $(`
                <div class="calendar-day" data-date="${dateStr}">
                    <div class="day-number">${day}</div>
                </div>
            `);
            $('#kalender_costume').append(dayCell);
        }

        dataKalender(departement, karyawan, tahun);
    }

    function dataKalender(departement = null, karyawan = null, tahun = null) {
        $('.calendar-day .calendar-event').remove();

        $.get("{{ route('coemanager.getdatacoe') }}", {
            departement: departement,
            karyawan: karyawan,
            tahun: tahun
        }, function(data) {
            data.forEach(event => {
                const start = new Date(event.start);
                const end = event.end ? new Date(event.end) : start;

                for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
                    const dateStr = d.toISOString().split('T')[0];
                    const cell = $(`.calendar-day[data-date="${dateStr}"]`);
                    if (cell.length) {
                        const eventEl = $(`<div class="calendar-event ${event.className}" title="${event.description}">${event.title}</div>`);
                        cell.append(eventEl);
                    }
                }
            });
        });
    }

    // function dataKalender(departement, karyawan, tahun) {
    //     $.get("{{ route('coemanager.getdatacoe') }}", function(data) {
    //         data.forEach(event => {
    //             const start = new Date(event.start);
    //             const end = event.end ? new Date(event.end) : start;

    //             for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
    //                 const dateStr = d.toISOString().split('T')[0];
    //                 const cell = $(`.calendar-day[data-date="${dateStr}"]`);
    //                 if (cell.length) {
    //                     const eventEl = $(`<div class="calendar-event ${event.className}" title="${event.description}">${event.title}</div>`);
    //                     cell.append(eventEl);
    //                 }
    //             }
    //         });
    //     });
    // }

    function getDepartement() {
        $.ajax({
            type: "GET"
            , url: '{{route("departement.all")}}'
            , async: true
            , success: function(data) {
                    $('#departement').html('<option value="">Choose Departement</option>');
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
                    $('.tahun').html('<option value="">Go To Year</option>');
                    $('.tahun').select2({
                        data: data.map(function(year) {
                            return { id: year, text: year };
                        }),
                        theme: "bootstrap-5"
                    });

                    // var tahunsekarang = new Date().getFullYear();
                    // $('.tahun').val(tahunsekarang).trigger('change');
                    
                },
                error: function(data, textStatus, jqXHR) {

                }
            });
        }

    function getBulan() {
        $.ajax({
            type: "GET",
            url: '{{ route("getMonth.all") }}',
            async: true,
            success: function(data) {
                $('#bulan').html('<option value="">Go To Month</option>');
                $('#bulan').select2({
                    data: data.map(function(text) {
                        return { id: text.index, text: text.bulan };
                    }),
                    theme: "bootstrap-5"
                });

                // var bulanSekarang = new Date().getMonth();
                // $('#bulan').val(bulanSekarang+1).trigger('change');

            },
            error: function(data, textStatus, jqXHR) {
    
            }
        });
    }

        function loadFilter()
        {
            let departement = $('#departement').val();
            let karyawan = $('#karyawan').val();
            let tahun = $('#tahun').val();
            let bulan = $('#bulan').val();
            // console.log(bulan);

            const currentMonthYear = $('#month-year').text().split(' ');
            // const month = bulan ? bulan-1 : listBulan.indexOf(currentMonthYear[0]);
            // const year = tahun ? tahun : parseInt(currentMonthYear[1]);

            // buatKalender(year, month, departement, karyawan, tahun);


            ambilbulansekarang = bulan ? bulan - 1 : tanggalSekarang.getMonth(); 
            ambiltahunsekarang = tahun ? parseInt(tahun) : tanggalSekarang.getFullYear();

            buatKalender(ambiltahunsekarang, ambilbulansekarang, departement, karyawan, ambiltahunsekarang);

        }


</script>


@endpush


        