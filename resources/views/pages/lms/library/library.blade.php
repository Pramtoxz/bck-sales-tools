@extends('layouts.app')
@section('content') 
<div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-20">Data File Library</h4>
    
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">File Library</li>
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
                        <div class="col-xs-12 col-md-8">
                            {{-- <h3>My Library</h3> --}}

                            <div>
                                <button onclick="toggleOptions()" class="btn btn-sm btn-primary"><i class="bx bx-plus me-1"></i>Create New</button>
                                <div id="pilih_opsi" style="display:none; margin-top:10px;">
                                    <button class="btn btn-sm btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal_folder" onclick="createFolder()">Create Folder</button>
                                    <button class="btn btn-sm btn-info waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal_file" onclick="createFile()">Create File</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tabellibrary" class="table table-bordered nowrap w-100">
                                    <thead class="table-info">
                                        <tr>
                                            <th>Type</th>
                                            <th>Nama</th>
                                            <th>Owner</th>
                                            <th>Last Modified</th>
                                            <th>File Size</th>
                                            <th>Aksi</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 13px">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

          
        <div class="modal fade" id="containerModalTambah"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> File </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose1"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="needs-validation" novalidate action="#" id="formSubmitFileFolder" enctype="multipart/form-data">
                                    @csrf
                                    {{-- header --}}
                                    {{-- <input type="hidden" class="form-control" id="id_folder" name="id_folder">  --}}

                                    <input type="hidden" class="form-control" id="tipe_submit_file_folder" name="tipe_submit_file" required>
                                    <input type="hidden" class="form-control" id="id_data_file_folder" name="id_data_file">
                                    <input type="hidden" class="form-control" id="id_folder_file_folder" name="id_folder"> 


                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-2">
                                                <input type="text" class="form-control" id="nama_file_folder" placeholder="Isikan Nama File" name="nama_file" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-2">
                                                
                                                <input type="file" class="form-control" id="file_upload_folder" placeholder="Upload File" name="file_upload" accept=".doc,.xls,.xlsx,.docs,.pdf" required>
                                                <div id="file_preview_folder"></div>
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="mb-2">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                  
                                </form>
                                <div class="table-responsive">
                                    <table id="tabel_file" class="table table-borderless nowrap w-100" >
                                        <thead class="table-info">
                                            <tr>
                                                <th scope="col">Type</th>
                                                <th scope="col">Nama File</th>
                                                <th scope="col">Owner</th>
                                                <th scope="col">Last Modified</th>
                                                <th scope="col">File Size</th>
                                                <th scope="col">Aksi</th>
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

        {{-- modal folder --}}
        <div class="modal fade" id="modal_folder" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit_folder"></span> Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose"></button>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" novalidate action="#" id="formSubmit">
                            @csrf
    
                            <input type="hidden" class="form-control" id="tipe_submit_folder" name="tipe_submit_folder" required>
                            <input type="hidden" class="form-control" id="id_data_folder" name="id_data_folder">
    
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="nama_folder">Nama Folder</label>
                                        <input type="text" class="form-control" id="nama_folder" placeholder="Isikan Nama Folder" name="nama_folder" required>
                                    </div>
                                </div>
                            </div>
    
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- end modal folder --}}

</div>


                   {{-- modal file --}}
                   <div class="modal fade" id="modal_file" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit_file"></span> File</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose2"></button>
                            </div>
                            <div class="modal-body">
                                <form class="needs-validation" novalidate action="#" id="formSubmit2">
                                    @csrf
            
                                    <input type="hidden" class="form-control" id="tipe_submit_file" name="tipe_submit_file" required>
                                    <input type="hidden" class="form-control" id="id_data_file" name="id_data_file">
                                    <input type="hidden" class="form-control" id="id_folder_file" name="id_folder"> 
                     
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="nama_file">Nama File</label>
                                                <input type="text" class="form-control" id="nama_file" placeholder="Isikan Nama File" name="nama_file" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="file_upload">Upload File</label>
                                                <div id="file_preview"></div>
                                                <input type="file" class="form-control" id="file_upload" placeholder="Upload File" name="file_upload" accept=".doc,.xls,.xlsx,.docs,.pdf" required>
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end modal file --}}
@endsection
@push('script')
<script>

    $(document).ready(function(){ 
        loadData();

        $("form#formSubmit").submit(function(e) {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                // console.log(formData);
                var url = "{{route('simpanfolder.get')}}"
                $.ajax({
                    url: url
                    , type: 'POST'
                    , data: formData
                    , async: true
                    , success: function(data) {
                        if (data.status == "true") {
                            $("#edClose").click();
                            $('#tabellibrary').DataTable().ajax.reload();
                            $('#ket_success_toast').html("Berhasil Di Simpan!");
                            new bootstrap.Toast(toastsuccess).show();
                        
                            $("#formSubmit")[0].reset();

                        } else {
                            $('#ket_failed_toast').html(data.message);
                            new bootstrap.Toast(toastfailed).show();
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

            $("form#formSubmit2").submit(function(e) {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                // console.log(formData);
                var url = "{{route('simpanfile.get')}}"
                $.ajax({
                    url: url
                    , type: 'POST'
                    , data: formData
                    , async: true
                    , success: function(data) {
                        if (data.status == "true") {
                            $("#edClose2").click();
                            $('#tabellibrary').DataTable().ajax.reload();
                            $('#ket_success_toast').html("Berhasil Simpan Data!");
                            new bootstrap.Toast(toastsuccess).show();
                        
                            $("#formSubmit2")[0].reset();

                        } else {
                            $('#ket_failed_toast').html(data.message);
                            new bootstrap.Toast(toastfailed).show();
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

            $("form#formSubmitFileFolder").submit(function(e) {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                // console.log(formData);
                var url = "{{route('simpanfile.get')}}"
                $.ajax({
                    url: url
                    , type: 'POST'
                    , data: formData
                    , async: true
                    , success: function(data) {
                        if (data.status == "true") {
                            // $("#edClose1").click();
                            $('#tabel_file').DataTable().ajax.reload();
                            $('#ket_success_toast').html("Berhasil Simpan Data!");
                            new bootstrap.Toast(toastsuccess).show();
                        
                            $("#formSubmitFileFolder")[0].reset();
                            $("#file_preview_folder").html('');

                        } else {
                            $('#ket_failed_toast').html(data.message);
                            new bootstrap.Toast(toastfailed).show();
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
    })

    function loadData(){
        dataTableMaster = $('#tabellibrary').DataTable({
                processing: true
                , serverSide: true
                , bDestroy: true
                , paging: true
                , ajax: `{{ url('/filelibrary/getData') }}`
                , columns: [{
                        data: 'type'
                        , name: 'type'
                        ,"width": "10px",
                    }
                    , {
                        data: 'name'
                        , name: 'name'
                    }
                    , {
                        data: 'nama_lengkap'
                        , name: 'nama_lengkap'
                    }
                    , {
                        data: 'updated_at'
                        , name: 'updated_at'
                    }
                    , {
                        data: 'size'
                        , name: 'size'
                        , orderable: false
                        , searchable: false
                    }
                    , {
                        data: 'aksi'
                        , name: 'aksi'
                        ,"width": "5px"
                        , orderable: false
                        , searchable: false
                       
                    , }
                , ]
            });
    }

    function tambahFile(id_folder){
        // $('#id_folder').val(id_folder);
        $('#nama_file_folder').val('');
        $('#file_upload_folder').val('');

        $('#ket_submit').html("Tambah");
        $('#tipe_submit_file_folder').val("add");
        $('#id_data_file_folder').val("");
        $('#id_folder_file_folder').val(id_folder);

    
        var table_materi = $('#tabel_file').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            paging: true,
            ajax: '{{route("libraryFile.show")}}'+`?id_folder=${id_folder}`,
            columns: [
                {
                        data: 'type'
                        , name: 'type'
                        ,"width": "10px",
                    },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'nama_lengkap',
                    name: 'nama_lengkap'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                 {
                        data: 'size'
                        , name: 'size'
                        , orderable: false
                        , searchable: false
                    },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable : false,
                    searchable : false
                },
            ]
           
        }); 
    }

    function toggleOptions() {
    const el = document.getElementById('pilih_opsi');
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }

    function createFolder(){
        $('#ket_submit_folder').html("Tambah");
        $('#tipe_submit_folder').val("add");
        $('#id_data_folder').val("");
        $('#nama_folder').val("");
    }

    function createFile(){
        $('#ket_submit_file').html('Tambah');
        $('#tipe_submit_file').val("add");
        $('#id_data_file').val("");
        $('#id_folder_file').val("");

        $('#nama_file').val("");
        $('#file_upload').val("");
    }

    function deleteData(id,type){
            Swal.fire({
            title: type=='Folder' ? "Yakin Mau Hapus Folder ?" : "Yakin Mau Hapus File ?",
            text: type=='Folder' ? "Ini Akan Menghapus Seluruh File Dalam Folder!" : "File Akan Terhapus Jika Click Ya!",      
            icon:"warning",
            showCancelButton:!0,
            confirmButtonColor:"#2ab57d",
            cancelButtonColor:"#fd625e",
            confirmButtonText:"Ya, Silahkan Di Hapus!"}).then(function(e){
                if(e.value){
                    var url = "{{route('hapusData.delete')}}"
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { 
                            id,
                            type
                        },
                        async: true,
                        success: function(data) {
                            if(data.code == "200"){
                                $('#ket_success_toast').html(data.message)
                                new bootstrap.Toast(toastsuccess).show()
                                if(data.type=='blank'){
                                    $('#tabel_file').DataTable().ajax.reload();
                                }else{
                                    $('#tabellibrary').DataTable().ajax.reload();
                                }
                            }else{
                                $('#ket_failed_toast').html(data.message)
                                new bootstrap.Toast(toastfailed).show()
                            }
                            hitungsisacuti()
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

    function editDataFolder(id)
    {
        $('#ket_submit_folder').html("Update");
        $('#tipe_submit_folder').val("edit");
        $('#id_data_folder').val(id);

        var url = "{{route('editfolder.show')}}"+`?id=${id}`
    
        $.ajax({
            url: url,
            type: 'GET',
            async: true,
            success: function(data) {
                $('#nama_folder').val(data.nama)        
            },

            error: function(xhr, status, error) {
                console.log(error)
                $('#ket_failed_toast').html(JSON.parse(xhr.responseText))
                new bootstrap.Toast(toastfailed).show()
            },
        });
    }

    function editDataFile(id)
    {
        $('#ket_submit_file').html("Update");
        $('#tipe_submit_file').val("edit");
        $('#id_data_file').val(id);

        var url = "{{route('editfile.show')}}"+`?id=${id}`
    
        $.ajax({
            url: url,
            type: 'GET',
            async: true,
            success: function(data) {
                $('#nama_file').val(data.nama) 
                $('#file_preview').html(`<small class="text-muted">Data File Saat Ini: ${data.file}</small>`);
                // $('#file_upload').val(data.file)       
            },

            error: function(xhr, status, error) {
                console.log(error)
                $('#ket_failed_toast').html(JSON.parse(xhr.responseText))
                new bootstrap.Toast(toastfailed).show()
            },
        });
    }

    function editDataFileFolder(id)
    {
        $('#ket_submit').html("Update");
        $('#tipe_submit_file_folder').val("edit");
        $('#id_data_file_folder').val(id);

        var url = "{{route('editfile.show')}}"+`?id=${id}`
    
        $.ajax({
            url: url,
            type: 'GET',
            async: true,
            success: function(data) {
                $('#nama_file_folder').val(data.nama) 
                $('#file_preview_folder').html(`<small class="text-muted">Data File Saat Ini: ${data.file}</small>`);
                // $('#file_upload').val(data.file)       
            },

            error: function(xhr, status, error) {
                console.log(error)
                $('#ket_failed_toast').html(JSON.parse(xhr.responseText))
                new bootstrap.Toast(toastfailed).show()
            },
        });
    }





</script>
@endpush

