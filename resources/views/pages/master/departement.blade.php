@extends('layouts.app')
@section('content')         
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Data Departement</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Departement</li>
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
                                <button class="btn btn-success btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#containerModal" onclick="tambahData()"><i class="bx bx-plus me-1"></i> Tambah Data</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-borderless nowrap w-100">
                            <thead class="table-info">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Deskripsi</th>
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
    </div> 
    <!-- end row -->

    {{-- modal --}}
    <!-- Static Backdrop Modal -->
    <div class="modal fade" id="containerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Data Departement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" novalidate action="#" id="formSubmit">
                        @csrf
                        {{-- header --}}
                        <input type="hidden" class="form-control" id="tipe_submit" name="tipe_submit" required>
                        <input type="hidden" class="form-control" id="id_data" name="id_data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="kd_departement">Kode Departement</label>
                                    <input type="text" class="form-control" id="kd_departement" placeholder="Kode Departement" name="kd_departement" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="deskripsi">Deskripsi</label>
                                    <input type="text" class="form-control" id="deskripsi" placeholder="Deskripsi" name="deskripsi" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
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
    {{-- end modal --}}

    

</div>
@endsection
@push('script')
<script>
    $(document).ready(function(){
        var dataTableMaster;
        // load data table awal
        loadData()
        $("form#formSubmit").submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            var url = "{{route('departement.save')}}"
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
            ajax: '{{route("departement.get")}}',
            columns: [{
                    "searchable": false,
                    "targets": 0,
                    "data": null,
                    "width": "50px",
                    "sClass": "text-center",
                    "orderable": false
                },
                {
                    data: 'kd_departement',
                    name: 'kd_departement'
                },
                {
                    data: 'deskripsi',
                    name: 'deskripsi'
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
    
    function tambahData(){
        $('#ket_submit').html("Tambah");
        $('#tipe_submit').val("add");
        $('#id_data').val("");
        $('#kd_departement').val("")
        $('#deskripsi').val("")
        $('#active').val("t")
    }

    function editData(id){
        $('#ket_submit').html("Update");
        $('#tipe_submit').val("edit");
        $('#id_data').val(id);
        // call ajax to get data
        var url = "{{route('departement.show')}}"+`?id=${id}`
        $.ajax({
            url: url,
            type: 'GET',
            async: true,
            success: function(data) {
                $('#kd_departement').val(data.kd_departement)
                $('#deskripsi').val(data.deskripsi)
                $('#active').val(data.active)
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
                    var url = "{{route('departement.delete')}}"
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
</script>
@endpush


        