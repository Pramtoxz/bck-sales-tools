@extends('layouts.app')

@section('content')         
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Data Nama Training</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Hasil Training</li>
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
                            {{-- <div class="float-start">
                                <button class="btn btn-success btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#containerModal" onclick="tambahData()"><i class="bx bx-plus me-1"></i> Tambah Hasil Training</button>
                            </div> --}}
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-borderless nowrap w-100">
                            <thead class="table-info">
                            <tr>
                                <th>No</th>
                                <th>Nama Training</th>
                                <th>Jenis Training</th>
                                <th>Tag Training</th>
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
  
    <!-- Static Backdrop Modal -->
    <div class="modal fade" id="containerModalTambah"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Daftar Peserta </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose1"></button>
                </div>
                <div class="modal-body">
                    <div id="navigation" class="mb-3">
                        {{-- batch --}}
                        {{-- <button>test</button> --}}
                        {{-- select2 --}}
                         <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="batch">Navigasi Batch Training</label>
                                    <input type="hidden" name="kd_training" id="kd_training" />
                                    <select class="form-control" id="batch" name="batch" required style="width:100%;" onchange="loadDataPeserta()">
                                        {{-- <option value="">Pilih Batch</option> --}}
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                
                                <table id="table_materi" class="table table-border nowrap w-100" >
                                    <thead class="table-info">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Karyawan</th>
                                            <th scope="col">Jabatan</th>
                                            <th scope="col">Progress</th>
                                            <th scope="col">Pre-test Nilai</th>
                                            <th scope="col">Post-test Nilai</th>
                                            <th scope="col">Final Project</th>
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

    {{-- modal ulasan --}}
    <div class="modal fade" id="containerModalUlasan"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Data Ulasan </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose1"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="ulasan" class="table table-borderless nowrap w-100" >
                                    <thead class="table-info">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Karyawan</th>
                                            <th scope="col">Jabatan</th>
                                         
                                            <th scope="col">Score</th>
                                            <th scope="col">Catatan</th>
                                            <th scope="col" style="width: 20%">ACTIONS</th>
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
    {{-- end modal ulasan --}}


</div>
@endsection
@push('script')
<script>
    var dropdownParentEl = $('#containerModalTambah > .modal-dialog > .modal-content > .modal-body')
    var delayInMilliseconds = 500; //1 second
    $(document).ready(function(){
        // var i=0;
        var dataTableMaster;
        var table_materi;
        var ulasan;
        // load data table awal
        loadData()
        // getDataBatch()

        // $("form#formSubmit").submit(function(e) {
        //     e.preventDefault();          
        //     var formData = new FormData($(this)[0]);
        //     console.log(formData);
        //     var url = "{{route('training.save')}}"
        //     $.ajax({
        //         url: url,
        //         type: 'POST',
        //         data: formData,
        //         async: true,
        //         success: function(data) {
        //             if(data.code == "200"){
        //                 $('#ket_success_toast').html(data.message)
        //                 new bootstrap.Toast(toastsuccess).show()
        //                 $('#datatable').DataTable().ajax.reload();
        //                 $("#edClose").click();
        //             }else{
        //                 $('#ket_failed_toast').html(data.message)
        //                 new bootstrap.Toast(toastfailed).show()
        //             }
        //         },
    
        //         error: function(xhr, status, error) {
        //             console.log(error)
        //             $('#ket_failed_toast').html(JSON.parse(xhr.responseText))
        //             new bootstrap.Toast(toastfailed).show()
        //         },
        //         cache: false,
        //         contentType: false,
        //         processData: false
        //     });
        // }); 
        
        // $("form#formSubmitMateri").submit(function(e) {
        //     e.preventDefault();          
        //     var formData = new FormData($(this)[0]);
        //     console.log(formData);
        //     var url = "{{route('trainingMateri.save')}}"
        //     $.ajax({
        //         url: url,
        //         type: 'POST',
        //         data: formData,
        //         async: true,
        //         success: function(data) {
        //             if(data.code == "200"){
        //                 $('#ket_success_toast').html(data.message)
        //                 new bootstrap.Toast(toastsuccess).show()
        //                 $('#table_materi').DataTable().ajax.reload();
        //                 $("#edClose").click();
        //             }else{
        //                 $('#ket_failed_toast').html(data.message)
        //                 new bootstrap.Toast(toastfailed).show()
        //             }
        //         },
    
        //         error: function(xhr, status, error) {
        //             console.log(error)
        //             $('#ket_failed_toast').html(JSON.parse(xhr.responseText))
        //             new bootstrap.Toast(toastfailed).show()
        //         },
        //         cache: false,
        //         contentType: false,
        //         processData: false
        //     });
        // });  

        // getJenisTraining() 
    })
    

    function getDataBatch(){

        $.ajax({
            type: "GET",
            url: "{{ route('batch.all') }}",
            success: function(data, textStatus, jqXHR) {
            console.log(data[0]);
                $('#batch').select2({    
                    data: data,
                    theme: "bootstrap-5",
                    dropdownParent: dropdownParentEl
                });
            },
            error: function(data, textStatus, jqXHR) {},
        });  
            // console.log("testets")
        //     return kd_training;
        //     $.ajax({
        //     type: "GET",
        //     url: "{{ route('batch.all') }}",
        //     success: function(data, textStatus, jqXHR) {
        //         $('#batch').select2({      
        //             data: data,
        //             theme: "bootstrap-5",
        //             dropdownParent: dropdownParentEl
        //         });
        //     },
        //     error: function(data, textStatus, jqXHR) {},
        // });   
        
        
        // $.ajax({
        //     type: "GET",
        //     url: '{{route("batch")}}'+`?kd_training=${kd_training}`,
        //     success: function(data, textStatus, jqXHR) {
        //         $('#batch').select2({   
        //             data: data,
        //             theme: "bootstrap-5",
        //             dropdownParent: dropdownParentEl
        //         });
        //     },
        //     error: function(data, textStatus, jqXHR) {},
        // });     
            
    }
    
    // function getJenisTraining(){
    //     $.ajax({
    //         type: "GET",
    //         url: "{{ route('jenisTraining.all') }}",
    //         success: function(data, textStatus, jqXHR) {
    //             $('#kd_jenis_training').select2({      
    //                 data: data,
    //                 theme: "bootstrap-5",
    //                 dropdownParent: dropdownParentEl
    //             });
    //         },
    //         error: function(data, textStatus, jqXHR) {},
    //     });     
    // }

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
            ajax: '{{route("penilaian.get")}}',
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
                    name: 'nama_jenis'
                },
                {
                    data: 'training_tag',
                    name: 'training_tag'
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


//     function getBatch(kd_training){
//     $('#batch').html('<option value="">Pilih Batch Event</option>');
//     $.ajax({
//         type: "GET",
//         url: '{{ route("batch") }}' + `?kd_training=${kd_training}`,
//         async: true,
//         success: function(data) {
//             console.log(data);
//             // Mengonversi data menjadi format yang dapat digunakan oleh Select2
//             var formattedData = data.map(function(item) {
//                 return {
//                     id: item.id,
//                     text: item.text
//                 };
//             });

//             // Inisialisasi Select2 dengan data yang sudah diformat
//             $('#batch').select2({    
//                 data: formattedData,
//                 theme: "bootstrap-5",
//                 dropdownParent: dropdownParentEl
//             });
//         },
//         error: function(data, textStatus, jqXHR) {
//             // Tindakan yang diambil jika terjadi kesalahan
//         },
//     });             
// }



        function getBatch(kd_training){
            $('#batch').html('<option value="">Pilih Batch Event</option>');
                $.ajax({
                    type: "GET",
                    url: '{{route("batch")}}'+`?kd_training=${kd_training}`,
                    async: true,
                    success: function(data) {
                        $('#batch').select2({    
                            data: data,
                            theme: "bootstrap-5",
                            dropdownParent: dropdownParentEl
                        });
                        // $('#batch').val(data.id).trigger("change");
                    },
                    error: function(data, textStatus, jqXHR) {},
                });             
        }

    function tambahNilaiPeserta(kd_training){
        $('#kd_training').val(kd_training)
        getBatch(kd_training)
        // time out 1 menit
        setTimeout(function() {
                     // ajax
                    $.ajax({
                        type: "GET",
                        url: '{{route("batch.akhir")}}'+`?kd_training=${kd_training}`,
                        async: true,
                        success: function(data) {
                            // berupa id event terakhir
                            if(data != null){
                                $('#batch').val(data).trigger('change')
                            }
                        },
                        error: function(data, textStatus, jqXHR) {},
                    });  
        }, delayInMilliseconds);
       
    }

    function loadDataPeserta(){
        id_event = $('#batch').val()
        console.log(id_event)

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
            ajax: '{{route("penilaian.show")}}'+`?id_event=${id_event}`,
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
                    data: 'nama_jabatan',
                    name: 'nama_jabatan'
                },
                {
                    data: 'status',
                    name: 'status'
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
                    data: 'final_project',
                    name: 'final_project'
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

    
    

    function ulasan(kd_training)
    {
        // $('#kd_training').val(kd_training);
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

        var table_ulasan = $('#ulasan').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            paging: true,
            ajax: '{{route("ulasan.get")}}'+`?kd_training=${kd_training}`,
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
                    data: 'nama_jabatan',
                    name: 'nama_jabatan'
                },
                {
                    data: 'rating',
                    name: 'rating'
                },
    
                {
                    data: 'catatan',
                    name: 'catatan'
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

    function penilaian(id)
    {
            var inputPreTest = $('#nilai_pre_test' + id);
            var nilaiPreTest = inputPreTest.val();
            var inputPostTest = $('#nilai_post_test' + id);
            var nilaiPostTest = inputPostTest.val();
            var buttonElement = $('#button-' + id);
            // if (nilaiPreTest !== '' || nilaiPostTest !== '' && buttonElement.text() === 'Submit') {
                $.ajax({
                    url: '{{ route("penilaian.save") }}',
                    type: 'POST',
                    data: {
                        id: id,
                        nilai_pre_test: nilaiPreTest,
                        nilai_post_test: nilaiPostTest
                    },
                    success: function(data) {
                    if(data.code == "200"){
                        $('#ket_success_toast').html(data.message)
                        new bootstrap.Toast(toastsuccess).show()
                        $('#table_materi').DataTable().ajax.reload();
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
                    var url = "{{route('penilaian.delete')}}"
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
                                $('#ulasan').DataTable().ajax.reload();
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


        