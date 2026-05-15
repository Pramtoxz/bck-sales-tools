@extends('layouts.app')
@push('css-custom')
<style>
.telat {
    color:rgb(248, 21, 21) !important;
}
.table-responsive {
    overflow-x: auto;
    
}
table.table-bordered th,
table.table-bordered td {
    border: 1px solid #dee2e6;
    white-space: nowrap;
    font-size: 12px;
    
}
th.thvertical1 {
        vertical-align : middle !important; 
        text-align:center !important;
        background : #8cc14c !important;
        font-size:10px !important;
        color : rgb(251, 251, 251) !important;
        font-weight: bold !important;
    }

    .hijau{
    background : #8cc14c !important;
    color: white;
}
</style>
@endpush
@section('content')         
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Report Absensi</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Report Absensi</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius:5px;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <label for="">Pilih Bulan</label>
                            <select class="form-control" id="bulan" name="bulan">

                            </select>
                        </div>
                        <div class="col-3">
                            <label for="">Pilih Tahun</label>
                            <select class="form-control" id="tahun" name="tahun">

                            </select>
                        </div>
                        <div class="col-3">
                            <button class="btn btn-success mt-4" onclick="downloadData()" type="button"><i class="fas fa-file-excel"></i> Download Excel</button>
                        </div>
                        
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
    $(document).ready(function() {
        getTahun()
        getBulan()
    });
    
    function getTahun() {
        $.ajax({
            type: "GET",
            url: '{{ route("getYear.all") }}',
            async: true,
            success: function(data) {
                $('#tahun').select2({
                    data: data.map(function(year) {
                        return { id: year, text: year };
                    }),
                    theme: "bootstrap-5"
                });
    
                var tahunsekarang = new Date().getFullYear();
    
                $('#tahun').val(tahunsekarang).trigger('change');
                
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
                $('#bulan').select2({
                    data: data.map(function(text) {
                        return { id: text.index, text: text.bulan };
                    }),
                    theme: "bootstrap-5"
                });
                var bulanSekarang = new Date().getMonth();
    
                $('#bulan').val(bulanSekarang+1).trigger('change');
            },
            error: function(data, textStatus, jqXHR) {
    
            }
        });
    }

    function downloadData(){
        var bulan = $('#bulan').val()
        var tahun = $('#tahun').val()
        if(bulan == "" || tahun == ""){
            alert("Bulan Dan Tahun Wajib Dipilih");
            return
        }
        url = "{{route('absensi.report')}}?"+`bulan=${bulan}&tahun=${tahun}`
        window.open(url)
    }
</script>
@endpush


        