@extends('layouts.app')
@section('content') 
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Data History Job</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">History Job</li>
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
                                <th>Nama Karyawan</th>
                                <th>Departement</th>
                                <th>Jabatan</th>
                                <th>Mulai Menjabat</th>
                                <th>Akhir Menjabat</th>
                                <th>Resign</th>
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
    <div class="modal fade" id="containerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true" data-bs-focus="false">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Data Jabatan</h5>
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
                                <div class="mb-2">
                                    <label class="form-label" for="kd_karyawan">Pilih Karyawan</label>
                                    <select class="form-control" id="kd_karyawan" name="kd_karyawan" required style="width:100%;">
                                        <option value="">Pilih Karyawan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label class="form-label" for="kd_jabatan">Pilih Jabatan</label>
                                    <select class="form-control" id="kd_jabatan" name="kd_jabatan" required style="width:100%;">
                                        <option value="">Pilih Jabatan</option>
                                    </select>
                                    <input type="hidden" id="kd_departement" name="kd_departement">
                                </div>
                            </div>
                        </div>

                
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label class="form-label" for="mulai_menjabat">Mulai Menjabat</label>
                                    <input type="text" class="form-control" id="mulai_menjabat" placeholder="Tanggal Mulai menjabat" name="mulai_menjabat" required>
                                </div>
                            </div>
                        </div>

                       
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label class="form-label" for="akhir_menjabat">Akhir Menjabat</label>
                                    <input type="text" class="form-control" id="akhir_menjabat" placeholder="Tanggal Akhir Menjabat" name="akhir_menjabat" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label class="form-label" for="resign">Resign</label>
                                    <input type="text" class="form-control" id="resign" placeholder="Tanggal Resign" name="resign" required>
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
    var dropdownParentEl = $('#containerModal > .modal-dialog > .modal-content > .modal-body')
    const mulai_menjabat = flatpickr("#mulai_menjabat",{});
    const akhir_menjabat = flatpickr("#akhir_menjabat",{});
    const resign = flatpickr("#resign",{});

    $(document).ready(function(){
        var dataTableMaster;
        // load data table awal
        loadData()
        $("form#formSubmit").submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            var url = "{{route('historyJob.save')}}"
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
        getKaryawan()  
        getJabatan()
        getDepartement()
    })

    function getKaryawan(){
        $.ajax({
            type: "GET",
            url: "{{ route('karyawan.all') }}",
            success: function(data, textStatus, jqXHR) {
                $('#kd_karyawan').select2({      
                    data: data,
                    theme: "bootstrap-5",
                    dropdownParent: dropdownParentEl
                });
            },
            error: function(data, textStatus, jqXHR) {},
        });     
    }

    function getJabatan(){
        $.ajax({
            type: "GET",
            url: "{{ route('jabatan.all') }}",
            success: function(data, textStatus, jqXHR) {
                $('#kd_jabatan').select2({      
                    data: data,
                    theme: "bootstrap-5",
                    dropdownParent: dropdownParentEl
                });
            },
            error: function(data, textStatus, jqXHR) {},
        });     
    }
    function getDepartement(){
       $('#kd_jabatan').on('change',function(){
        var id=$(this).val();
        $.ajax({
            url:"{{ url('jabatan/filterkode') }}/"+id,
            type:'GET',
            success:function(data){
                if (data.kd_departement) {
                $('#kd_departement').val(data.kd_departement);
                }else {
                    console.error('Properti kd_departement tidak ditemukan dalam data:', data);
                }
            },
            error: function(data, textStatus, jqXHR) {},
        });
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
            ajax: '{{route("historyJob.get")}}',
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
                    data: 'deskripsi',
                    name: 'deskripsi'
                },
                {
                    data: 'nama_jabatan',
                    name: 'nama_jabatan'
                },
                {
                    data: 'mulai_menjabat',
                    name: 'mulai_menjabat'
                },
                {
                    data: 'akhir_menjabat',
                    name: 'akhir_menjabat'
                },
                {
                    data: 'resign',
                    name: 'resign'
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
        $('#kd_karyawan').val("").trigger("change");
        $('#kd_jabatan').val("").trigger("change");
        $('#kd_departement').val("");
        mulai_menjabat.clear()
        akhir_menjabat.clear()
        resign.clear()
    }

    //edit data
    function editData(id){
        $('#ket_submit').html("Update");
        $('#tipe_submit').val("edit");
        $('#id_data').val(id);
        var url = "{{route('historyJob.show')}}"+`?id=${id}`
        $.ajax({
            url: url,
            type: 'GET',
            async: true,
            success: function(data) {
                console.log(data)
                $('#kd_departement').val(data.kd_departement)
                $('#kd_karyawan').val(data.kd_karyawan).trigger("change");
                $('#kd_jabatan').val(data.kd_jabatan).trigger("change");
                mulai_menjabat.setDate(data.mulai_menjabat, true);
                akhir_menjabat.setDate(data.akhir_menjabat, true);
                resign.setDate(data.resign, true);
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
                    var url = "{{route('historyJob.delete')}}"
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

