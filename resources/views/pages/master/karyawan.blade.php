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
                    <h4 class="mb-sm-0 font-size-20">Data Karyawan</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Karyawan</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card" style="border-radius:5px;">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Tampilan Data</h4>
                        <div class="flex-shrink-0">
                            <ul class="nav justify-content-end nav-pills card-header-pills" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#table1" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-table"></i></span>
                                        <span class="d-none d-sm-block">Table</span> 
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#table2" role="tab" onclick="loadKaryawanList()">
                                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                        <span class="d-none d-sm-block">List</span> 
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content text-muted">
                            <div class="tab-pane active" id="table1" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="float-start">
                                            <button class="btn btn-success btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#containerModal" onclick="tambahData()"><i class="bx bx-plus me-1"></i> Tambah Data</button>
                                        </div>
                                        <div class="float-end">
                                            <button class="btn btn-primary btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#importModal" onclick="importData()"><i class="bx bx-upload me-1"></i> Import Data</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-borderless nowrap w-100">
                                                <thead class="table-info">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>Tanggal lahir</th>
                                                    <th>Departement</th>
                                                    <th>Jabatan</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="table2" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-lg-auto col-auto">
                                        <div class="mb-3">
                                            <h3 class="text-success card-title">Jumlah Data <span class="text-success fw-normal ms-1" id="jumlah_karyawan_list">(0)</span></h3>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-auto col-auto">
                                        <div id="check-karyawan-list" class="row">  
                                        </div>
                                    </div>
                                </div>
                                <div class="row" >
                                    
                                    <div class="col-md-12 col-lg-12">
                                        <div class="row"id="karyawan_list">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
    
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

        {{-- modal --}}
    <!-- Static Backdrop Modal -->
    <div class="modal fade" id="containerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true" data-bs-focus="false">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Data Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" novalidate action="#" id="formSubmit" enctype="multipart/form-data">
                        @csrf
                        {{-- header --}}
                        <input type="hidden" class="form-control" id="tipe_submit" name="tipe_submit" required>
                        <input type="hidden" class="form-control" id="id_data" name="id_data">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label class="form-label" for="nama_lengkap">Nama Lengkap*</label>
                                    <input type="text" class="form-control" id="nama_lengkap" placeholder="Nama Lengkap" name="nama_lengkap" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-1">
                                    <label class="form-label" for="nama_panggilan">Panggilan*</label>
                                    <input type="text" class="form-control" id="nama_panggilan" placeholder="Nama Panggilan" name="nama_panggilan" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-1">
                                    <label class="form-label" for="tempat_lahir">Tempat*</label>
                                    <input type="text" class="form-control" id="tempat_lahir" placeholder="Tempat lahir" name="tempat_lahir" required>
                                </div>
                            </div>  
                            <div class="col-md-3">
                                <div class="mb-1">
                                    <label>Tanggal Lahir*</label>
                                    <input type="date" class="form-control" id="tanggal_lahir" placeholder="Tanggal lahir" name="tanggal_lahir" required>
                                </div>
                            </div>    
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="jk">Email*</label>
                                    <input type="email" class="form-control" id="email" placeholder="Email Address" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-1">
                                    <label class="form-label" for="jk">Jenis Kelamin*</label>
                                    <select name="jk" id="jk" class="form-control" required>
                                        <option value="" selected hidden>Pilih Jenis Kelamin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-1">
                                    <label class="form-label" for="pendidikan">Pendidikan*</label>
                                    <select name="pendidikan" id="pendidikan" class="form-control" required>
                                        <option value="" selected hidden>Pilih Pendidikan</option>
                                    </select>
                                </div>
                            </div>    
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="agama">Agama*</label>
                                    <select name="agama" id="agama" class="form-control" required>
                                        <option value="">Pilih Agama</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="alamat">Alamat*</label>
                                    <input type="text" class="form-control" id="alamat" placeholder="Alamat" name="alamat" required>
                                </div>
                            </div>    
                        </div>
    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="sts">Status*</label>
                                    <select name="sts" id="sts" class="form-control" required>
                                        <option value="">Pilih Status</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="nama_susis">Nama Suami/istri</label>
                                    <input type="text" class="form-control" id="nama_susis" placeholder="Nama Suami/Istri" name="nama_susis">
                                </div>
                            </div>    
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="jumlah_anak">Jumlah Anak</label>
                                    <input type="number" class="form-control" id="jumlah_anak" placeholder="Jumlah anak" name="jumlah_anak">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="nama_ibu">Nama Ibu*</label>
                                    <input type="text" class="form-control" id="nama_ibu" placeholder="Nama Ibu" name="nama_ibu" required>
                                </div>
                            </div>    
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="notelp">No. Handphone*</label>
                                    <input type="text" class="form-control" id="notelp" placeholder="No. Telp" name="notelp" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="tanggal_gabung">Tanggal Bergabung*</label>
                                    <input type="text" class="form-control" id="tanggal_gabung" placeholder="Tanggal Bergabung" name="tanggal_gabung" required>
                                </div>
                            </div>    
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="departement">Departement*</label>
                                    <select name="departement" id="departement" class="form-control"required>
                                        <option value="">Pilih Departement</option>
                                    </select>
                                </div>   
                            </div>

                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="jbt">Jabatan*</label>
                                    <select name="jbt" id="jbt" class="form-control" required>
                                        <option value="">Pilih Jabatan</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="no_ktp">No. KTP*</label>
                                    <input type="text" class="form-control" id="no_ktp" placeholder="No. KTP" name="no_ktp" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="no_kk">No. Kartu Keluarga*</label>
                                    <input type="text" class="form-control" id="no_kk" placeholder="No. Kartu Keluarga" name="no_kk" required>
                                </div>
                            </div>    
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="mb-1">
                                    <label class="form-label" for="noker">No. Ketenagakerjaan</label>
                                    <input type="text" class="form-control" id="noker" placeholder="No. Ketenagakerjaan" name="noker">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="mb-1">
                                    <label class="form-label" for="nokes">No. Kesehatan</label>
                                    <input type="text" class="form-control" id="nokes" placeholder="No. Kesehatan" name="nokes">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="mb-1">
                                    <label class="form-label" for="kd_jabatan_wlk">Kode Jabatan WLK</label>
                                    <input type="text" class="form-control" id="kd_jabatan_wlk" placeholder="Kode Jabawan WLK" name="kd_jabatan_wlk">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="mb-1">
                                    <label class="form-label" for="npwp">NPWP</label>
                                    <input type="text" class="form-control" id="npwp" placeholder="No NPWP" name="npwp">
                                </div>
                            </div>     
                        </div>

                        <div class="row mb-2">

                            <div class="col-lg-6 col-md-6 mt-2">
                                <div class="mb-2">
                                    <label class="form-label" for="foto">Foto</label>
                                    <br>
                                    <img id="modal-preview" src="" alt="Preview"
                                    class="form-group hidden" width="160" height="150">
                                </div>
                                <div class="mb-1">
                                    
                                    <input type="file" class="form-control" id="foto" placeholder="Foto" name="foto" onchange="readURL(this);" accept=".png,.jpeg,.jpg,.gif,.svg">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="active">Aktif/Tidak Aktif</label>
                                    <select name="active" id="active" class="form-control">
                                        <option value="t">Aktif</option>
                                        <option value="f">Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">ID Absensi</label>
                                    <input type="text" name="id_absensi" id="id_absensi" class="form-control">
                                </div>
                            </div> 
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="importModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Upload Data Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose1"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" novalidate action="#" id="formImport" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-1">
                                    <label class="form-label" for="upload_file">Upload File* <a href="#" onclick="downloadTemplate()" style="cursor: pointer;color:red;"><small>Klik Disini Untuk Download Template Upload</small></a></label>
                                    <input type="file" class="form-control" id="upload_file" placeholder="Format Excel" name="upload_file" required accept=".xls,.xlsx">
                                </div>
                            </div>  
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="containerModal1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModal1Label" aria-hidden="true" data-bs-focus="false">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModal1Label"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Data Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose1"></button>
                </div>
                <div class="modal-body">
                    <h2>Hello World</h2>   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal --}}
</div>
@endsection
@push('script')
<script>
    var dropdownParentEl = $('#containerModal > .modal-dialog > .modal-content > .modal-body')
    var delayInMilliseconds = 1000; //1 second
    const tanggal_lahir = flatpickr("#tanggal_lahir",{});
    const tanggal_gabung = flatpickr("#tanggal_gabung",{});

    $(document).ready(function(){
        var dataTableMaster;
        // load data table awal
        loadData()

        $("form#formSubmit").submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            var url = "{{route('karyawan.save')}}"
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                async: true,
                success: function(data) {
                    if(data.code == "200"){
                        $('#ket_success_toast').html(data.message)
                        new bootstrap.Toast(toastsuccess).show()
                        $('#datatable').DataTable().ajax.reload();
                        $("#edClose").click();
                        $('#formSubmit').trigger("reset");
                    }else{
                        $('#ket_failed_toast').html(data.message)
                        new bootstrap.Toast(toastfailed).show()
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

        $("form#formImport").submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            var url = "{{route('karyawan.import')}}"
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                async: true,
                success: function(data) {
                    if(data.code == "200"){
                        let message = `
                           ${data.message}<br> Jumlah Sukses : ${data.data.success}<br> Jumlah Gagal : ${data.data.gagal}<br>Pesan : <br>
                             <p>${data.data.message}</p>
                        `
                        $('#ket_success_toast').html(message)
                        new bootstrap.Toast(toastsuccess).show()
                        $('#datatable').DataTable().ajax.reload();
                        $("#edClose1").click();
                        $('#formImport').trigger("reset");
                    }else{
                        $('#ket_failed_toast').html(data.message)
                        new bootstrap.Toast(toastfailed).show()
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
        // load Data Master
        getPendidikan() 
        getAgama()   
        getJK()
        getStatus()
        getDepartement()
        getDepartementKaryawanList()

        $('#departement').change(function(){
            getJabatan()
        });

    })

    function getPendidikan(){
        $.ajax({
            type: "GET",
            url: "{{ route('pendidikan.all') }}",
            success: function(data, textStatus, jqXHR) {
                $('#pendidikan').select2({      
                    data: data,
                    theme: "bootstrap-5",
                    dropdownParent: dropdownParentEl
                });
            },
            error: function(data, textStatus, jqXHR) {},
        });     
    }

    function getAgama(){
        $.ajax({
            type: "GET",
            url: "{{ route('agama.all') }}",
            success: function(data, textStatus, jqXHR) {
                $('#agama').select2({      
                    data: data,
                    theme: "bootstrap-5",
                    // dropdownParent: $("#containerModal") 
                    dropdownParent: dropdownParentEl
                });
            },
            error: function(data, textStatus, jqXHR) {},
        });     
    }

    function getJabatan(){
        $('#jbt').html('<option value="">Pilih Jabatan</option>');
        let id=$('#departement').val();
        if(id == ""){
            id = "Empty";
        }
        $.ajax({
            type: "GET",
            url: "{{ url('jabatan/filter') }}/"+id,
            success: function(data, textStatus, jqXHR) {
                $('#jbt').select2({      
                    data: data,
                    theme: "bootstrap-5",
                    dropdownParent: dropdownParentEl 
                });
            },
            error: function(data, textStatus, jqXHR) {},
        });     
    }

    function getJK(){
        var jkl=[
            {id:'L',text:"Laki-Laki"},
            {id:'P',text:"Perempuan"},
        ];
        $('#jk').select2({      
            data: jkl,
            theme: "bootstrap-5",
            dropdownParent: dropdownParentEl  
        });
    }

    function getStatus(){
        $.ajax({
            type: "GET",
            url: "{{ route('karyawan.getStatus') }}",
            success: function(data, textStatus, jqXHR) {
                $('#sts').select2({      
                    data: data,
                    theme: "bootstrap-5",
                    dropdownParent: dropdownParentEl
                });
            },
            error: function(data, textStatus, jqXHR) {},
        });  
    }

    
    function getDepartement(){
        $.ajax({
            type: "GET",
            url: "{{ route('departement.all') }}",
            success: function(data, textStatus, jqXHR) {
                $('#departement').select2({      
                    data: data,
                    theme: "bootstrap-5",
                    dropdownParent: dropdownParentEl
                });
            },
            error: function(data, textStatus, jqXHR) {},
        });     
    }

    function getDepartementKaryawanList(){
        $('#check-karyawan-list').html('')
        $.ajax({
            type: "GET",
            url: "{{ route('departement.all') }}",
            success: function(data, textStatus, jqXHR) {
                data.forEach(element => {
                    divElement = `<div class="form-check mb-3 col-lg-auto col-sm-auto col-auto">
                        <input class="form-check-input" type="checkbox" id="departement_karyawan_list" value="${element.id}" name="departement_karyawan_list" onchange="getKaryawanList()">
                        <label class="form-check-label text-success" for="departement_karyawan_list">
                            ${element.id}
                        </label>
                    </div>`;
                    $('#check-karyawan-list').append(divElement)
                });
            },
            error: function(data, textStatus, jqXHR) {},
        });     
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
    
        dataTableMaster = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            paging: true,
            ajax: "{{url('/karyawan/get')}}",
            columns: [{
                    "searchable": false,
                    "targets": 0,
                    "data": null,
                    "width": "50px",
                    "sClass": "text-center",
                    "orderable": false
                },
                {
                    data: 'nama_lengkap',
                    name: 'nama_lengkap'
                },
                {
                    data: 'jenis_kelamin',
                    name: 'jenis_kelamin'
                },
                {
                    data: 'tanggal_lahir',
                    name: 'tanggal_lahir',
                },
                {
                    data: 'kd_departement',
                    name: 'kd_departement',
                },
                {
                    data: 'nama_jabatan',
                    name: 'jabatan.nama_jabatan',
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable : false,
                    searchable : false
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


    function tambahData(){
        $('#ket_submit').html("Tambah");
        $('#tipe_submit').val("add");
        $('#active').val("t");
        $('#id_data').val("");
        $('#nama_lengkap').val("")
        $('#email').val("")
        $('#nama_panggilan').val("")
        $('#tempat_lahir').val("")
        // $('#tanggal_lahir').val("") 
        tanggal_lahir.clear()
        $('#jk').val("").trigger("change")
        $('#pendidikan').val("").trigger("change");
        $('#agama').val("").trigger("change");
        $('#alamat').val("")
        $('#sts').val("").trigger("change")
        $('#nama_susis').val("")
        $('#jumlah_anak').val("")
        $('#nama_ibu').val("")
        $('#notelp').val("")
        // $('#tanggal_gabung').val("")
        tanggal_gabung.clear()
        $('#jbt').val("").trigger("change");
        $('#departement').val("").trigger("change");
        $('#no_ktp').val("")
        $('#no_kk').val("")
        $('#noker').val("")
        $('#nokes').val("")
        $('#kd_jabatan_wlk').val("")
        $('#foto').val("")
        $('#modal-preview').attr('src','https://via.placeholder.com/150');
    }

     //edit data
     function editData(id){
        $('#ket_submit').html("Update");
        $('#tipe_submit').val("edit");
        $('#id_data').val(id);
        $('#foto').val(null)
        $('#modal-preview').attr('src','https://via.placeholder.com/150');
        // call ajax to get data
        var url = "{{route('karyawan.show')}}"+`?id=${id}`
        // var url2 = "{{route('departement.get')}}"
        $.ajax({
            url: url,
            type: 'GET',
            async: true,
            success: function(data) {
                $('#departement').val(data.kd_departement).trigger("change")
                $('#nama_lengkap').val(data.nama_lengkap)
                $('#nama_panggilan').val(data.nama_panggilan)
                $('#tempat_lahir').val(data.tempat_lahir)
                // $('#tanggal_lahir').val(data.tanggal_lahir)
                tanggal_lahir.setDate(data.tanggal_lahir, true);
                $('#email').val(data.email)
                $('#jk').val(data.jenis_kelamin).trigger("change")
                $('#pendidikan').val(data.kd_pendidikan).trigger("change")
                $('#agama').val(data.kd_agama).trigger("change")
                $('#alamat').val(data.alamat)
                $('#sts').val(data.kd_status).trigger("change")
                $('#nama_susis').val(data.nama_pasangan)
                $('#jumlah_anak').val(data.jumlah_anak)
                $('#nama_ibu').val(data.nama_ibu)
                $('#notelp').val(data.no_hp)
                // $('#tanggal_gabung').val(data.tanggal_bergabung)
                tanggal_gabung.setDate(data.tanggal_bergabung, true);
                $('#no_ktp').val(data.no_ktp)
                $('#no_kk').val(data.no_kk)
                $('#noker').val(data.no_ketenagakerjaan)
                $('#nokes').val(data.no_kesehatan)
                $('#npwp').val(data.npwp)
                $('#active').val(data.active)
                $('#id_absensi').val(data.id_absensi)
                if (data.foto) {
                    $('#modal-preview').attr('src','/storage/karyawan/' + data.foto);
                }else{
                    console.log("Tidak Ada Foto")
                }
                $('#kd_jabatan_wlk').val(data.kode_jabatan_wlk)
               
                setTimeout(function() {
                    $('#jbt').val(data.kd_jabatan).trigger("change")
                }, delayInMilliseconds);
             
            },

            error: function(xhr, status, error) {
                console.log(error)
                $('#ket_failed_toast').html(JSON.parse(xhr.responseText))
                new bootstrap.Toast(toastfailed).show()
            },
        });
    }


    function deleteData(id){
        Swal.fire({
            title:"Are you sure?",
            text:"You won't be able to revert this!",
            icon:"warning",
            showCancelButton:!0,
            confirmButtonColor:"#2ab57d",
            cancelButtonColor:"#fd625e",
            confirmButtonText:"Yes, delete it!"}).then(function(e){
                if(e.value){
                    var url = "{{route('karyawan.delete')}}"
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { 
                            id
                        },
                        async: true,
                        success: function(data) {
                            if(data.code == "200"){
                                $('#ket_success_toast').html(data.message)
                                new bootstrap.Toast(toastsuccess).show()
                                $('#datatable').DataTable().ajax.reload();
                            }else{
                                $('#ket_failed_toast').html(data.message)
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
                // e.value&&Swal.fire("Deleted!","Your file has been deleted.","success")
            })
    }

    function readURL(input,id){
        id=id|| '#modal-preview';
        if(input.files && input.files[0]){
            var reader=new FileReader();
            reader.onload=function(e){
                $(id).attr('src',e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
            $('#modal-preview').removeClass('hidden');
            $('#start').hide();
        }
    }

    function downloadTemplate(){
        url = "{{route('karyawan.downloadTemplate')}}"
        window.open(url)
    }

    function importData(){
        $('#upload_file').val()
    }

    function loadKaryawanList(){
        var karyawan_list = $('#karyawan_list')
        karyawan_list.html('');
        var element = "";
        $('#jumlah_karyawan_list').html(`(0)`)
        let arrayDepartementCheck = []; 
        $("input:checkbox[name=departement_karyawan_list]:checked").each(function() { 
            arrayDepartementCheck.push($(this).val()); 
        }); 
        if(arrayDepartementCheck.length != 0){
            departementList = arrayDepartementCheck.toString()
        }else{
            departementList = ""
        }
        $.ajax({
            type: "GET",
            url: "{{ route('karyawan.getListKaryawan') }}?departement="+`${departementList}`,
            success: function(data, textStatus, jqXHR) {
                // console.log(data);
                $('#jumlah_karyawan_list').html(`(${data.length})`)
                data.forEach(function(val){
                    var tanggal = "-";
                    if(val.tanggal_bergabung){
                        tanggal = val.tanggal_bergabung.split("-");
                        tanggal = tanggal[2]+"-"+tanggal[1]+"-"+tanggal[0]
                    }else{
                        tanggal = "-";
                    }

                    if(val.foto){
                        url_foto = `storage/karyawan/${val.foto}`
                    }else{
                        url_foto = `assets/images/users/avatar-new.png`;
                    }
                    if(val.jabatan != null){
                        nama_jabatan = val.jabatan.nama_jabatan;
                    }else{
                        nama_jabatan = "";
                    }
                    if(val.kd_departement == null){
                        val.kd_departement = ""
                    }
                    element = `<div class="col-xl-3 col-sm-6 col-md-3">
                                        <div class="card text-center background_karyawan" >
                                            <div class="card-body" style="min-height: 40vh;">
                                                <div class="mx-auto mb-4">
                                                    <img src="{{asset('${url_foto}')}}" alt="" class="avatar-xl rounded-circle img-thumbnail">
                                                </div>
                                                <h5 class="font-size-16 mb-1 text-dark">${val.nama_lengkap.toUpperCase()}</h5>
                                                <p class="text-dark mb-2">${val.kd_departement}-${nama_jabatan}</p>
                                                <p class="text-dark mb-2 fw-bold">${tanggal}</p>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-4 offset-2">
                                                        <button type="button" class="btn btn-primary text-truncate" onclick="editData('${val.id}')" data-bs-toggle="modal" data-bs-target="#containerModal"><i class="fas fa-pencil-alt"></i> Edit</button>
                                                    </div>
                                                    <div class="col-4">
                                                        <button type="button" class="btn btn-success text-truncate" onclick="lihatData('${val.id}')"><i class="fas fa-user"></i> Lihat</button>
                                                    </div>
                                                </div>
                                        </div>
                                        <!-- end card -->
                                    </div>
                            `;
                    karyawan_list.append(element)
                })
            },
            error: function(data, textStatus, jqXHR) {},
        }); 
    }

    function getKaryawanList(){
        loadKaryawanList()
    }

    function lihatData(id){
        // console.log(id)
        window.location.href = `/karyawan/lihat/${id}`;
    }
</script>
@endpush