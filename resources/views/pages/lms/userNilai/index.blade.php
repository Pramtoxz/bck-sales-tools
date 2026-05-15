@extends('layouts.app')

@section('content') 
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Data Nilai</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Hasil Training</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-0" style="border-radius:5px;">
                <div class="nav justify-content-start">
                    <img src="{{ asset('assets/images/small/img-9.jpg') }}" class="card-img-top" alt="..." style="max-width: 100%; max-height: 200px; opacity:0.5;">
                </div>
            </div>
            <div class="card" style="border-radius:5px;margin-bottom:4px">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-md-3 text-center mt-2">
                            @php
                                $foto = auth()->user()->karyawan->foto;
                            @endphp
                            <img src='{{ asset("storage/karyawan/$foto") }}' alt="foto"
                            class="form-group img-thumbnail img-circle" style="border-radius:10px;max-height:13.8em;width:13.8em;">
                        </div>
                        <div class="col-md-9 mt-3">
                            <table class="table table-borderless table-info">
                                <tr>
                                    <td>Nama</td>
                                    <td id="nama_lengkap">{{auth()->user()->karyawan->nama_lengkap}}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Mulai Bekerja</td>
                                    <td id="masakerja">{{date('d-m-Y',strtotime(auth()->user()->karyawan->tanggal_bergabung))}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <p class="text-danger">*E-Certificate Akan Muncul Ketika Sudah Selesai Mengerjakan Post Test</p>
                            <div class="table-responsive">
                                <table id="datatable" class="table table-borderless nowrap w-100">
                                    <thead class="table-info">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Training</th>
                                        <th>Mulai</th>
                                        <th>Akhir</th>
                                        <th>Nilai Pre Test</th>
                                        <th>Nilai Post Test</th>
                                        <th>E-Certificate</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
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
$(document).ready(function(){
    var dataTableMaster;
    loadData()
})

function loadData(){
        $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };
    
        dataTableMaster = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            paging: true,
            ajax: '{{route("userGetNilai.get")}}',
            columns: [{
                    "searchable": false,
                    "targets": 0,
                    "data": null,
                    "width": "50px",
                    "sClass": "text-center",
                    "orderable": false
                },
                {
                    data: 'nama_training',
                    name: 'nama_training'
                },
                {
                    data: 'tanggal_mulai',
                    name: 'tanggal_mulai'
                },
                {
                    data: 'tanggal_selesai',
                    name: 'tanggal_selesai'
                },
                {
                    data: 'nilai_pre_test',
                    name: 'nilai_pre_test'
                },
                {
                    data: 'nilai_post_test',
                    name: 'nilai_post_test'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            "order": [
                [1, 'asc']
            ],
            "rowCallback": function(row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
            }
        }); 
    }

    $('#datatable').on('draw.dt', function () {
    var dataTable = $('#datatable').DataTable();
    var rowData = dataTable.row(0).data(); 
    var dataValue = rowData.masa_kerja;
    var datadepartement = rowData.deskripsi;
    var datajabatan = rowData.nama_jabatan;
    var namalengkap = rowData.nama_lengkap;
    var foto = rowData.foto;
     $('#nama_lengkap').html(namalengkap);
     $('#masakerja').html(dataValue);
     $('#masakerja').html(dataValue);
     $('#departement').html(datadepartement);
     $('#jabatan').html(datajabatan);
    $('#modal-preview').attr('src','https://via.placeholder.com/150'); 
    if (foto) {
        $('#modal-preview').attr('src','/storage/karyawan/' + foto);
    }
    $('#foto').text(foto);   

});

    function downloadSertifikat(kode_event_training){
        window.open(`/download/sertifikat/${kode_event_training}`);
    }


</script>
    
@endpush