<!DOCTYPE html>
<html>
<head>
    <!-- Bootstrap Css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>History Jadwal</title>
</head>
<body>
    <div class="row ">
        <div class="col-lg-12 text-center">
            <img src="{{public_path('assets/images/logo-ma-big.png')}}" alt="" width="250" height="50" class="mb-1">
            <h5>Learning Management System</h5>
            <p>{{ date('d/m/Y') }}</p>
            <hr style="border-top:2px solid black;">
        </div>
    </div>
    <br>
    <h5>Rekap Jadwal Training</h5>
    <table class="table table-bordered">
        <tr class="table-primary">
            <th class="text-center">No</th>
            <th class="text-center">Jadwal</th>
            <th class="text-center">Nama Training</th>
            <th class="text-center">Nama Karyawan</th>
            <th class="text-center">Pre Test</th>
            <th class="text-center">Post Test</th>
        </tr>
        @php
            $no = 0;
        @endphp
        @foreach($data as $value)
            @foreach($value->peserta_training as  $key =>  $peserta)
            @php
                $no += 1;
            @endphp
            <tr>
                @if($key == 0)
                <td rowspan="{{count($value->peserta_training)}}" class="text-center" style="vertical-align : middle;">{{ $no }}</td>
                <td rowspan="{{count($value->peserta_training)}}" class="text-center" style="vertical-align : middle;">{{ date('d/m/Y',strtotime($value->tanggal_mulai)) }}-{{date('d/m/Y',strtotime($value->tanggal_akhir))  }}</td>
                <td rowspan="{{count($value->peserta_training)}}" class="text-center" style="vertical-align : middle;">{{ $value->nama_training }}</td>
                @endif
                <td class="text-center" style="vertical-align : middle;">{{ $peserta->karyawan->nama_lengkap }}</td>
                <td class="text-center" style="vertical-align : middle;">{{ $peserta->nilai_pre_test }}</td>
                <td class="text-center" style="vertical-align : middle;">{{ $peserta->nilai_post_test }}</td>
            </tr>
            @endforeach
        @endforeach
    </table>
  
</body>
</html>