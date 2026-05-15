<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    {{-- <style>
        body{
            background: url('{{asset("assets/images/sertifikat/sertifikat2.png")}}');
            background-size: cover;
        }
    </style> --}}
</head>
<body>
    <img src="{{public_path('assets/images/sertifikat/sertifikat2.png')}}" alt="" width="1030px" height="700px" style="position:absolute;z-index:0;">
    <div class="container" style="z-index:999999999;">
        
        <div class="row">
            <div class="col-lg-12 text-center">
                <img style="padding-top:60px;" src="{{public_path('assets/images/logo-ma-big.png')}}" alt="" width="390" height="80">
                <h1 style="padding-top:20px;letter-spacing: 3px;font-family:sans-serif;">SERTIFIKAT</h1>
                <h3 style="font-family:sans-serif;">Diberikan Kepada</h3>
                <h1 style="font-size:3em;border-bottom:1px solid black;padding-top:40px;font-family:sans-serif;">{{$nama_karyawan}}</h1>
                <h3 style="padding-top:50px;font-family:sans-serif;">Telah Menyelesaikan Training</h3>
                <h3 style="font-family:sans-serif;"><b>{{$nama_training}}</b></h3>
                <h3 style="font-family:sans-serif;"><i>{{$kode_event_training}}</i></h3>
            </div>
        </div>
    </div>
</body>
</html>