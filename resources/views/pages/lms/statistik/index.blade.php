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

    .valueCard{
        font-size:2.5em;
    }
    .valueCard1{
        font-size:1.5em;
    }

</style>
@endpush
@section('content')

<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Data Statistik</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Statistics</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius:5px;">
                <div class="card-header">
                    {{-- <input type="hidden" class="form-control" id="isadmin" name="isadmin" value=""> --}}

                    <div class="karyawan">
                        <div class="mb-3">
                            <select class="form-control" id="karyawan" name="karyawan">

                            </select>
                        </div>
                    </div>
                    
                    <div class="tahun">
                        <div class="mb-3">
                            <select class="form-control" id="tahun" name="tahun">

                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="float-end">
                            <button class="btn btn-success btn-md waves-effect waves-light" onclick="loadData()"><i class="bx bx-plus me-1"></i>Search</button>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <h5>Training</h5>
                    <div class="row justify-content-md-center">
                        <div class="card col-xl-3 col-sm-6 col-md-3">
                            <div class="card-body btn btn-info">
                                <h5 class="text-white font-size-16 mb-1" style="text-align: center;">Jumlah Training</h5>
                                <p class="jumlahTraining text-white mb-2 valueCard counter-value" style="text-align: center;"></p>
                            </div>
                        </div>
                        <div class="card col-xl-3 col-sm-6 col-md-3">
                            <div class="card-body btn btn-success">
                                <h5 class="text-white font-size-16 mb-1" style="text-align: center;">Training Selesai</h5>
                                <p class="trainingSelesai text-white mb-2 valueCard counter-value" style="text-align: center;"></p>
                            </div>
                        </div>
                        <div class="card col-xl-3 col-sm-6 col-md-3">
                            <div class="card-body btn btn-warning">
                                <h5 class="text-white font-size-16 mb-1" style="text-align: center;">Training Berlangsung</h5>
                                <p class="trainingBerlangsung text-white mb-2 valueCard counter-value" style="text-align: center;"></p>
                            </div>
                        </div>
                        <div class="card col-xl-3 col-sm-6 col-md-3">
                            <div class="card-body btn btn-danger">
                                <h5 class="text-white font-size-16 mb-1" style="text-align: center;">Training Tidak Selesai</h5>
                                <p class="tidakSelesai text-white mb-2 valueCard counter-value" style="text-align: center;"></p>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="card col-xl-4 col-sm-6 col-md-4">
                            <div class="card-body btn btn-primary">
                                <h5 class="text-white font-size-16 mb-1" style="text-align: center;">Rata-rata Waktu Penyelesain</h5>
                                <p class="avgtraining text-white mb-2 valueCard1 counter-value" style="text-align: center;"></p>
                            </div>
                        </div>
                        <div class="card col-xl-4 col-sm-6 col-md-4">
                            <div class="card-body btn btn-primary">
                                <h5 class="text-white font-size-16 mb-1" style="text-align: center;">Minimal Waktu Penyelesaian</h5>
                                <p class="mintraining text-white mb-2 valueCard1 counter-value" style="text-align: center;"></p>
                            </div>
                        </div>
                        <div class="card col-xl-4 col-sm-6 col-md-4">
                            <div class="card-body btn btn-primary">
                                <h5 class="text-white font-size-16 mb-1" style="text-align: center;">Maximal Waktu Penyelesaian</h5>
                                <p class="maxtraining text-white mb-2 valueCard1 counter-value" style="text-align: center;"></p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Grafik Nilai Pre Test Vs Post Test</h4>
                </div>
                <div class="card-body">
                    {{-- <div id="grafikprepost"></div> --}}
                    <div id="spline_area" data-colors='["#5156be", "#2ab57d"]' class="apex-charts" dir="ltr"></div>  
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Average Pre Test</h4>
                </div>
                <div class="card-body">
                    <div id="averagepretest"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Min Pre Test</h4>
                </div>
                <div class="card-body">
                    <div id="minpretest"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Max Pre Test</h4>
                </div>
                <div class="card-body">
                    <div id="maxpretest"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Average Post Test</h4>
                </div>
                <div class="card-body">
                    <div id="averageposttest"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Min Post Test</h4>
                </div>
                <div class="card-body">
                    <div id="minposttest"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Max Post Test</h4>
                </div>
                <div class="card-body">
                    <div id="maxposttest"></div>
                </div>
            </div>
        </div>
    </div>

    

</div>
@endsection
@push('script')
<!-- apexcharts js -->
<script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>
<script>
    var user = '{{ Auth::user()->is_admin}}';
    // var dropdownParentEl = $('.card > .card-header > .tahun')
    $(document).ready(function() {
        // $('#isadmin').attr('value', user)
        loadData()
        getKaryawan(user)
        getTahun()
    })
    

    function loadData() {

        tahun = $('#tahun').val();
        tahun = tahun == null ? "" : tahun;
        karyawan = $('#karyawan').val();
        karyawan = karyawan == null ? "" : karyawan;

        // tahun = $('#tahun').val();
        // tahun = tahun == null ? "" : tahun;
        $.ajax({
            url: '{{ route("statistik.all") }}' + `?tahun=${tahun}`+ `&karyawan=${karyawan}`
            , method: 'GET'
            , success: function(data) {
                console.log(data);
                $('.jumlahTraining').text(data.totaltrainings);
                $('.trainingSelesai').text(data.trainingselesai);
                $('.trainingBerlangsung').text(data.trainingberlangsung);
                $('.tidakSelesai').text(data.trainingtakselesai);
                $('.avgtraining').html("<i class='fas fa-stopwatch'></i> "+data.avgtraining+" Jam");
                $('.mintraining').html("<i class='fas fa-stopwatch'></i> "+data.mintraining+" Jam");
                $('.maxtraining').html("<i class='fas fa-stopwatch'></i> "+data.maxtraining+" Jam");
                classElement = {
                    "averagepretest":data.avgpretest,"minpretest":data.minpretest,"maxpretest":data.maxpretest,"averageposttest":data.avgposttest,"minposttest":data.minposttest,"maxposttest":data.maxposttest
                }
                makeNilaiChart(classElement)

                var trainingNames = data.trainingScores.map(score => score.nama_training);
                var preTestScores = data.trainingScores.map(score => score.nilai_pre_test == null ? 0 : score.nilai_pre_test);
                var postTestScores = data.trainingScores.map(score => score.nilai_post_test == null ? 0 : score.nilai_post_test);

                pretestposttest(trainingNames, preTestScores, postTestScores);

            }
            , error: function(data, textStatus, jqXHR) {}
        , });
    }

    function getKaryawan(user) {
        $.ajax({
            type: "GET"
            , url: '{{route("getKaryawan.all")}}'
            , async: true
            , success: function(data) {
                if(user=='t'){
                    $('#karyawan').html('<option value="">Filter By Karyawan</option>');
                    $('#karyawan').select2({
                    data: data,
                    theme: "bootstrap-5"
                });
                }else{
                    $('.karyawan').html('');
                }
              
            }
            , error: function(data, textStatus, jqXHR) {}
        , });
    }

    function getTahun() {
        $('#tahun').html('<option value="">All</option>');
        $.ajax({
            type: "GET"
            , url: '{{route("getYear.all")}}'
            , async: true
            , success: function(data) {
                $('#tahun').select2({
                    data: data,
                    theme: "bootstrap-5"
                });
            }
            , error: function(data, textStatus, jqXHR) {}
        , });
    }

    function pretestposttest(trainingNames, preTestScores, postTestScores) {
        $('#spline_area').html('')
        var splneAreaColors = getChartColorsArray("#spline_area"),
        options = {
            chart: { height: 350, type: "area", toolbar: { show: !1 } },
            dataLabels: { enabled: !1 },
            stroke: { curve: "smooth", width: 3 },
            series: [
                { name: "Pre Test", data: preTestScores },
                { name: "Post Test", data: postTestScores },
            ],
            colors: splneAreaColors,
            xaxis: { type: "string", categories: trainingNames },
            grid: { borderColor: "#f1f1f1" },
        };
        (chart = new ApexCharts(document.querySelector("#spline_area"), options)).render();
    }


    function getChartColorsArray(e) {
        e = $(e).attr("data-colors");
        return (e = JSON.parse(e)).map(function (e) {
            e = e.replace(" ", "");
            if (-1 == e.indexOf("--")) return e;
            e = getComputedStyle(document.documentElement).getPropertyValue(e);
            return e || void 0;
        });
    }

    function makeNilaiChart(classElement){
        var options = {
            chart: {
                height: 280, 
                type: "radialBar",
            },
            series: [0],
            colors: ["#20E647"],
            plotOptions: {
                radialBar: {
                    hollow: {
                        margin: 0,
                        size: "70%",
                        background: "#fff"
                    }
                    , track: {
                        dropShadow: {
                            enabled: true,
                            top: 2,
                            left: 0,
                            blur: 4,
                            opacity: 0.2
                        }
                    }
                    , dataLabels: {
                        name: {
                            offsetY: -10, 
                            color: "#000000", 
                            fontSize: "18px"
                        }
                        , value: {
                            color: "#000000", 
                            fontSize: "35px",
                            show: true,
                            formatter: function (val) {
                                return val
                            }
                        }
                    }
                }
            },
            fill: {
                type: "gradient",
                gradient: {
                    shade: "dark",
                    type: "vertical",
                    gradientToColors: ["#ff3333"],
                    stops: [0, 100]
                }
            },
            stroke: {
                lineCap: "round"
            }, 
            labels: ["Nilai"],
            
        };
        for (var key in classElement) {
            $(`#${key}`).html('')
            var nilai = classElement[key]
            options.series = [nilai];
            new ApexCharts(document.querySelector(`#${key}`), options).render();
        }
    }

</script>

@endpush
