@extends('layouts.app')
@push('css-custom')
<style>
    .background_karyawan{
        background: #fbfaff;  /* fallback for old browsers */ /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    }
</style>
@endpush
@section('content') 
<div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-20">Report</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Report</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card" style="border-radius:5px;">
                    <div class="card-body">
                        <h6>History Jadwal Training</h6>
                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="mb-2">
                                    <input type="text" class="form-control" id="tanggal_awal_jadwal_training" placeholder="Tanggal Awal" name="tanggal_awal_jadwal_training" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <input type="text" class="form-control" id="tanggal_akhir_jadwal_training" placeholder="Tanggal Akhir" name="tanggal_akhir_jadwal_training" required>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-4">
                                <button type="button" class="btn btn-info btn-sm" onclick="downloadHistoryJadwal()"><i class="fas fa-download"></i> Download</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                    
</div>
@endsection
@push('script')
<script>
   const tanggal_awal_jadwal_training = flatpickr("#tanggal_awal_jadwal_training",{});
   const tanggal_akhir_jadwal_training = flatpickr("#tanggal_akhir_jadwal_training",{});

   function downloadHistoryJadwal(){
        var tanggal_awal = $('#tanggal_awal_jadwal_training').val()
        var tanggal_akhir = $('#tanggal_akhir_jadwal_training').val()
        if(tanggal_awal == "" || tanggal_akhir == ""){
            $('#ket_failed_toast').html("Tanggal harus dipilih")
            new bootstrap.Toast(toastfailed).show()
            return
        }
        window.open("{{route('report.training.download')}}"+`?tanggal_awal=${tanggal_awal}&tanggal_akhir=${tanggal_akhir}`);
   }
</script>
@endpush