@extends('layouts.app')
@section('content')         
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Data Training</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Training</li>
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
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="float-start">
                                <button class="btn btn-success btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#containerModal" onclick="tambahData()"><i class="bx bx-plus me-1"></i> Tambah Training</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-borderless nowrap w-100">
                            <thead class="table-info">
                            <tr>
                                <th>No</th>
                                <th>Nama Training</th>
                                <th>Jenis Training</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    {{-- modal --}}

    <!-- Static Backdrop Modal -->
    <div class="modal fade" id="containerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Data Training</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" novalidate action="#" id="formSubmit" enctype="multipart/form-data">
                        @csrf
                        {{-- header --}}
                        <input type="hidden" class="form-control" id="tipe_submit" name="tipe_submit" required>
                        <input type="hidden" class="form-control" id="id_data" name="id_data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="kd_jenis_training">Pilih Jenis Training</label>
                                    <select class="form-control" id="kd_jenis_training" name="kd_jenis_training" required style="width:100%;">
                                        <option value="">Pilih Jenis Training</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="mb-1">
                                    <img id="modal-preview" src="" alt="Preview" class="img-thumbnail" width="100" height="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="nama_training">Nama Training</label>
                                    <input type="text" class="form-control" id="nama_training" placeholder="Nama Training" name="nama_training" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="avatar_training">Upload Foto</label>
                                    <input type="file" class="form-control" id="avatar_training" placeholder="Upload Here" name="avatar_training" onchange="readURL(this);" accept=".png,.jpeg,.jpg,.gif,.svg">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="training_tag">Tag Training</label>
                                    {{-- <input type="text" class="form-control" id="training_tag" placeholder="Nama Tag Training" name="training_tag" required> --}}
                                    <select class="js-example-basic-multiple form-control" name="training_tag[]" multiple="multiple" id="training_tag">
                                    </select>
                                </div>
                            </div>
                    
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="deskripsi">Deskripsi</label>
                                    <input type="text" class="form-control" id="deskripsi" placeholder="Nama Deskripsi" name="deskripsi" required>
                                </div>
                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="active">Aktif/Tidak Aktif</label>
                                    <select name="active" id="active" class="form-control">
                                        <option value="t">Aktif</option>
                                        <option value="f">Tidak Aktif</option>
                                    </select>
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
  
    <!-- Static Backdrop Modal -->
    <div class="modal fade" id="containerModalTambah"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Daftar Materi </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose1"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="needs-validation" novalidate action="#" id="formSubmitMateri" enctype="multipart/form-data">
                                @csrf
                                {{-- header --}}
                                <input type="hidden" class="form-control" id="kd_training" name="kd_training"> 
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-2">
                                            <select name="tipe_materi" id="tipe_materi" class="form-control" required>
                                                <option value="">Pilih Tipe</option>
                                                <option value="document">Document</option>
                                                <option value="video">Video</option>
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <select name="jenis_materi" id="jenis_materi" class="form-control" required onchange="changeJenisMateri()">
                                                <option value="">Pilih Jenis</option>
                                                <option value="file">File</option>
                                                <option value="link">Link</option>
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input type="file" class="form-control" id="materi_file" placeholder="Upload Materi" name="materi_file" accept=".doc,.docs,.pdf" required>
                                            <input type="text" class="form-control" id="materi_link" placeholder="https://" name="materi_link" required style="display: none;">
                                        </div>
                                    </div>
                                   
                                   
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 float-end">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table id="table_materi" class="table table-borderless nowrap w-100" >
                                    <thead class="table-info">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Training</th>
                                            <th scope="col">Tipe Materi</th>
                                            <th scope="col">Materi</th>
                                            <th scope="col">ACTIONS</th>
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

    {{-- container soal cat --}}
    <div class="modal fade" id="containerModalSoal"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Bank Soal </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose1"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="needs-validation" novalidate action="#" id="formSubmitSoal" enctype="multipart/form-data">
                                @csrf
                                {{-- header --}}
                                <input type="hidden" class="form-control" id="kd_training_soal" name="kd_training_soal"> 
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Nama Soal</label>
                                        <input type="text" class="form-control" id="nama_soal" name="nama_soal" required>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-1">
                                            <label class="form-label" for="upload_file">Upload File* <a href="#" onclick="downloadTemplate()" style="cursor: pointer;color:red;"><small>Klik Disini Untuk Download Template Upload Bank Soal</small></a></label>
                                            <input type="file" class="form-control" id="bank_soal" placeholder="Upload Soal" name="bank_soal" accept=".xls,.xlsx" required>
                                        </div>
                                    </div>  
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>       
                                </div>
                              
                            </form>
                            <div class="table-responsive">
                                <table id="table_soal" class="table table-borderless nowrap w-100" >
                                    <thead class="table-info">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Kode Soal</th>
                                            <th scope="col">Nama Soal</th>
                                            <th scope="col">Jumlah Soal</th>
                                            <th scope="col">Tanggal Import</th>
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

</div>
@endsection
@push('script')
<script>
    var dropdownParentEl = $('#containerModal > .modal-dialog > .modal-content > .modal-body')
    $(document).ready(function(){
        $('.js-example-basic-multiple').select2();
        var i=0;
        var dataTableMaster;
        var table_materi;
        // load data table awal
        loadData()

        $("form#formSubmit").submit(function(e) {
            e.preventDefault();          
            var formData = new FormData($(this)[0]);
            console.log(formData);
            var url = "{{route('training.save')}}"
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                async: true,
                success: function(data) {
                    console.log(data)
                    if(data.code == "200"){
                        $('#ket_success_toast').html(data.message)
                        new bootstrap.Toast(toastsuccess).show()
                        $('#datatable').DataTable().ajax.reload();
                        $("#edClose").click();
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
        
        $("form#formSubmitMateri").submit(function(e) {
            e.preventDefault();          
            var formData = new FormData($(this)[0]);
            console.log(formData);
            var url = "{{route('trainingMateri.save')}}"
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                async: true,
                success: function(data) {
                    if(data.code == "200"){
                        $('#ket_success_toast').html(data.message)
                        new bootstrap.Toast(toastsuccess).show()
                        $('#table_materi').DataTable().ajax.reload();
                        $("#edClose").click();
                        $('#materi_link').val(null)
                        $('#materi_file').val(null)
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

        $("form#formSubmitSoal").submit(function(e) {
            e.preventDefault();          
            var formData = new FormData($(this)[0]);
            // console.log(formData);
            var url = "{{route('trainingSoal.save')}}"
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                async: true,
                success: function(data) {
                    if(data.code == "200"){
                        $('#ket_success_toast').html(data.message)
                        new bootstrap.Toast(toastsuccess).show()
                        $('#table_soal').DataTable().ajax.reload();
                        $("#edClose").click();
                        $('#bank_soal').val(null)
                        $('#nama_soal').val('')
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

        getJenisTraining()
        getTrainingTag() 
    })
    
    function getJenisTraining(){
        $.ajax({
            type: "GET",
            url: "{{ route('jenisTraining.all') }}",
            success: function(data, textStatus, jqXHR) {
                $('#kd_jenis_training').select2({      
                    data: data,
                    theme: "bootstrap-5",
                    dropdownParent: dropdownParentEl
                });
            },
            error: function(data, textStatus, jqXHR) {},
        });     
    }

    function getTrainingTag(){
        $.ajax({
            type: "GET",
            url: "{{ route('trainigTag.all') }}",
            success: function(data, textStatus, jqXHR) {
                $('#training_tag').select2({      
                    data: data,
                    theme: "bootstrap-5",
                    dropdownParent: dropdownParentEl
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
            ajax: '{{route("training.get")}}',
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
                    data: 'nama_jenis',
                    name: 'jenis_training.nama_jenis'
                },
                {
                    data: 'active',
                    name: 'active',
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
    function tambahMateri2(kd_training){
        $('#kd_training').val(kd_training);
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
    
        var table_materi = $('#table_materi').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            paging: true,
            ajax: '{{route("materiTraining.show")}}'+`?kd_training=${kd_training}`,
            columns: [{
                    "searchable": false,
                    "targets": 0,
                    "data": null,
                    "width": "50px",
                    "sClass": "text-center",
                    "orderable": false
                },
                {
                    data: 'training.nama_training',
                    name: 'training.nama_training'
                },
                {
                    data: 'tipe_materi',
                    name: 'tipe_materi'
                },
                {
                    data: 'preview',
                    name: 'preview'
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

    function tambahSoal2(kd_training){
        $('#kd_training_soal').val(kd_training);
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
    
        var table_soal = $('#table_soal').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            paging: true,
            ajax: '{{route("trainingSoal.show")}}'+`?kd_training=${kd_training}`,
            columns: [{
                    "searchable": false,
                    "targets": 0,
                    "data": null,
                    "width": "50px",
                    "sClass": "text-center",
                    "orderable": false
                },
                {
                    data: 'kode_soal',
                    name: 'kode_soal'
                },
                {
                    data: 'nama_soal',
                    name: 'nama_soal'
                },
                {
                    data: 'jumlah_soal',
                    name: 'jumlah_soal'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
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
            }
        }); 
    }


    function tambahData(){
        $('#ket_submit').html("Tambah");
        $('#tipe_submit').val("add");
        $('#id_data').val("");
        $('#avatar_training').val(null)
        $('#modal-preview').attr('src','https://via.placeholder.com/150');
        $('#nama_training').val("")
        $('#training_tag').val("").trigger('change')
        $('#deskripsi').val("")
        $('#kd_jenis_training').val("").trigger('change')
        $('#active').val("t")
    }
   
    function editData(id){
        $('#ket_submit').html("Update");
        $('#tipe_submit').val("edit");
        $('#id_data').val(id);
        $('#avatar_training').val(null)
        $('#modal-preview').attr('src','https://via.placeholder.com/150');
        // call ajax to get data
        var url = "{{route('training.show')}}"+`?id=${id}`
        $.ajax({
            url: url,
            type: 'GET',
            async: true,
            success: function(data) {
                $('#kd_jenis_training').val(data.kd_jenis_training).trigger("change");
                $('#nama_training').val(data.nama_training);
                if (data.avatar_training) {
                    $('#modal-preview').attr('src','/storage/gambar_training/' + data.avatar_training);
                }
                if(data.training_tag != null){
                    training_tag = data.training_tag.split(",")
                    $('#training_tag').val(training_tag).trigger('change');
                }
                
                $('#deskripsi').val(data.deskripsi);
                $('#active').val(data.active);
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
                    var url = "{{route('training.delete')}}"
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


    function btnHapusMateri(id){
        Swal.fire({
            title:"Are you sure?",
            text:"You won't be able to revert this!",
            icon:"warning",
            showCancelButton:!0,
            confirmButtonColor:"#2ab57d",
            cancelButtonColor:"#fd625e",
            confirmButtonText:"Yes, delete it!"}).then(function(e){
                if(e.value){
                    var url = "{{route('trainingMateri.delete')}}"
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
                                $('#table_materi').DataTable().ajax.reload();;
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

    function changeJenisMateri(){
        var jenis_materi = $('#jenis_materi').val()
        if(jenis_materi == "link"){
            $('#materi_file').hide()
            $('#materi_link').show()
        }else{
            $('#materi_file').show()
            $('#materi_link').hide()
        }
    }

    function downloadTemplate(){
        url = "{{route('training.downloadTemplate')}}"
        window.open(url)
    }

    function btnHapusSoal(kode_soal){
        Swal.fire({
            title:"Are you sure?",
            text:"You won't be able to revert this!",
            icon:"warning",
            showCancelButton:!0,
            confirmButtonColor:"#2ab57d",
            cancelButtonColor:"#fd625e",
            confirmButtonText:"Yes, delete it!"}).then(function(e){
                if(e.value){
                    var url = "{{route('trainingSoal.deleteSoal')}}"
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { 
                            kode_soal
                        },
                        async: true,
                        success: function(data) {
                            if(data.code == "200"){
                                $('#ket_success_toast').html(data.message)
                                new bootstrap.Toast(toastsuccess).show()
                                $('#table_soal').DataTable().ajax.reload();;
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

    function previewSoal(kode_soal){
        url = "{{route('trainingSoal.preViewSoal')}}?kode_soal="+kode_soal
        window.open(url)
    }
</script>
@endpush


        