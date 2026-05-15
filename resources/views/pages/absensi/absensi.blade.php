@extends('layouts.app')
@push('css-custom')
<style>
.telat {
    color:rgb(248, 21, 21) !important;
}
</style>
@endpush
@section('content')         
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Data Absensi</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Absensi</li>
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
                    @if(Auth::user()->is_admin == 't')
                    <div class="row mb-3">
                        <div class="col-xl-4 col-lg-4 col-4">
                                <button class="btn btn-danger btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#containerModal" onclick="tambahLibur()"><i class="bx bx-plus me-1"></i> Tambah Hari Libur Kerja</button>
                        </div>
                    </div>
                    <hr>
                    @endif
                    

                    <div class="row">
                        <div class="col-3">
                            <label for="">Pilih Bulan</label>
                            <select class="form-control" id="bulan" name="bulan">

                            </select>
                        </div>
                        <div class="col-3">
                            <label for="">Pilih Tahun</label>
                            <select class="form-control tahun" id="tahun" name="tahun">

                            </select>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-info mt-4" onclick="lihatData()"><i class="fas fa-search"></i> Lihat</button>
                            @if(Auth::user()->is_admin == "t")
                            <button class="btn btn-success mt-4" onclick="downloadData()" id="wadah_tombol_download" style="display: none;"><i class="fas fa-download"></i> Download</button>
                            <button class="btn btn-danger mt-4" onclick="clearData()" id="wadah_tombol_clear" style="display: none;"><i class="fas fa-trash"></i> Clear</button>
                            @endif
                        </div>
                        
                    </div>
                    <div class="row wadah_spinner" style="display:none;">
                        <div class="col-lg-12">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                              </div>
                        </div>
                    </div>
                    @if(Auth::user()->is_admin == "t")
                    <div class="row mt-3">
                        
                        <div class="col-5" id="wadah_upload_data" style="display: none;">
                            <form class="needs-validation" novalidate action="#" id="formImport" enctype="multipart/form-data">
                                <div class="mb-2">
                                    <label class="form-label" for="upload_file"> <img src="{{asset('assets/images/absensi/no-data.png')}}" alt="" width="12%"> Data Belum Ditemukan Silhakan Upload Data</label>
                                    <input type="file" class="form-control" id="upload_file" placeholder="Format Excel" name="upload_file" required accept=".xls,.xlsx,.csv">
                                </div>
                                <button type="submit" class="btn btn-info">Upload</button>
                            </form>
                        </div>
                    </div>
                    @endif
                    <div class="row mt-2" id="wadah_table" style="display: none;">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                            <table class="table" id="datatable" class="table table-borderless" style="width:100%;">
                                <thead class="table-info" >
                                    <tr>
                                        <td>No</td>
                                        <td>Nama</td>
                                        <td>Kedatangan</td>
                                    </tr>
                                </thead>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> 
    <!-- end row -->

</div>

<div class="modal fade" id="containerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
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
</div>

@endsection
@push('script')
<script>

const tanggal_libur = flatpickr("#tanggal_libur", {
            mode: "multiple"
            , dateFormat: "d-m-Y"
            // , minDate:"today"
        });

    $(document).ready(function() {
        getTahun()
        getBulan()
        $('#datatable').DataTable({})

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


        $("form#formImport").submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            bulan = $('#bulan').val()
            tahun = $('#tahun').val()
            formData.append("bulan", bulan);
            formData.append("tahun", tahun);
            var url = "{{route('absensi.import')}}"
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                async: true,
                beforeSend : function(){
                    $('.wadah_spinner').show()
                    $('#wadah_upload_data').hide()
                },
                success: function(data) {
                    $('.wadah_spinner').hide()
                    if(data.code == '200'){
                        Swal.fire({
                            icon: 'success'
                            , title: 'Berhasil'
                            , text: 'Data Berhasil Di Upload!'
                            , confirmButtonText: 'OK'
                        });
                        $('#datatable').DataTable().ajax.reload();
                        $('#formImport').trigger("reset");
                    }else{
                        Swal.fire({
                            icon: 'warning'
                            , title: 'Gagal import data!!!'
                            , text: data.message
                            , confirmButtonText: 'OK'
                        });
                    }

                },
    
                error: function(xhr, status, error) {
                    console.log(error)
                    $('#ket_failed_toast').html(JSON.parse(xhr.responseText))
                    new bootstrap.Toast(toastfailed).show()
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });
    
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

    function lihatData(){
        loadData()
        $('#wadah_table').show()
    }

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

        bulan = $('#bulan').val()
        tahun = $('#tahun').val()
    
        dataTableMaster = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            paging: true,
            pageLength : 10,
            ajax: "{{url('absensi/data')}}"+`?bulan=${bulan}&tahun=${tahun}`,
            columns: [{
                    "searchable": false,
                    "targets": 0,
                    "data": null,
                    "width": "10px",
                    "sClass": "text-center",
                    "orderable": false
                },
                {
                    data: 'nama_lengkap',
                    name: 'karyawan.nama_lengkap',
                    "orderable": false
                },
                {
                    data: 'waktu_kedatangan',
                    name: 'absensi.waktu_kedatangan',
                    "orderable": false
                },
            ],
            createdRow: function ( row, data, index ) {
                    if(data['telat']){
                        $('td', row).eq(2).addClass('telat');
                    }
            },
            "rowCallback": function(row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
            },
            "drawCallback": function( settings, start, end, max, total, pre ) {  
                if(this.fnSettings().fnRecordsTotal() > 0){
					$('#wadah_upload_data').hide()
                    $('#wadah_tombol_clear').show()
                    $('#wadah_tombol_download').show()
				}else{
					$('#wadah_upload_data').show()
                    $('#wadah_tombol_clear').hide()
                    $('#wadah_tombol_download').hide()
                }
            },
        }); 
    }

    function clearData(){
        bulan = $('#bulan').val()
        tahun = $('#tahun').val()
        Swal.fire({
            title:"Yakin Hapus Data ?",
            icon:"warning",
            showCancelButton:!0,
            confirmButtonColor:"#2ab57d",
            cancelButtonColor:"#fd625e",
            confirmButtonText:"Hapus !"}).then(function(e){
                if(e.value){
                    var url = "{{route('absensi.delete')}}"
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { 
                            bulan,
                            tahun
                        },
                        async: true,
                        success: function(data) {
                            if(data){
                                $('#ket_success_toast').html("Sukses Hapus Data")
                                new bootstrap.Toast(toastsuccess).show()
                                $('#datatable').DataTable().ajax.reload();
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

    function tambahLibur() {
            $('#ket_submit').html('Tambah');
            $('#tipe_submit').val("add");
            $('#id_data').val("");

            $('#tahun').val("");

            tanggal_libur.clear()

            loadDataLibur();
        }
    
        function loadDataLibur(){
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
    
        dataTableMaster = $('#data_libur').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            paging: true,
            pageLength : 10,
            ajax: "{{url('data_libur/data')}}",
            columns: [{
                    "searchable": false,
                    "targets": 0,
                    "data": null,
                    "width": "10px",
                    "sClass": "text-center",
                    "orderable": false
                },
                {
                    data: 'tanggal_libur',
                    name: 'tanggal_libur'
                },
                {
                    data: 'tahun',
                    name: 'tahun'
                },
                {
                    data: 'aksi',
                    name: 'aksi'
                },
            ],
    
            "rowCallback": function(row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
            },
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


        