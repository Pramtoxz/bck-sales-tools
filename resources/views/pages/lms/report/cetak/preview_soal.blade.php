<!DOCTYPE html>
<html>
<head>
    <!-- Bootstrap Css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Preview Soal</title>
    <style>
        .indentation {
            text-indent: 20px !important;
            line-height: 10px;
        }
    </style>
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
    <div class="row">
        <div class="col-lg-12">
            <table border="1" >
                <tr >
                    <td class="px-2">Kode Soal</td>
                    <td class="px-2">{{$data->kode_soal}}</td>
                </tr>
                <tr>
                    <td class="px-2">Nama Soal</td>
                    <td class="px-2">{{$data->nama_soal}}</td>
                </tr>
                <tr>
                    <td class="px-2">Tanggal Soal</td>
                    <td class="px-2">{{date("d-m-Y H:i:s",strtotime($data->created_at))}}</td>
                </tr>
            </table>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <h4>Soal</h4>
            {{-- @dd($data); --}}
            @foreach($data->soalPreview as  $value)
                <span>{{$value->no_soal}}. {{$value->text_soal}}</span>
                <br>
                <br>
                <div>
                    <p class="indentation">a.{{$value->opsi_a}}</p>
                    <p class="indentation">b.{{$value->opsi_b}}</p>
                    <p class="indentation">c.{{$value->opsi_c}}</p>
                    <p class="indentation">d.{{$value->opsi_d}}</p>
                </div>
                <br>
            @endforeach
        </div>
    </div>
  
</body>
</html>