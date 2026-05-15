@extends('layouts.app')
@section('content') 
<div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-20">Data Riwayat Training</h4>
    
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Riwayat Training</li>
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
                            <div>
                                <select class="form-control" id="departement" name="departement" style="width:100%;" onchange="loadData()">
                                   
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-borderless nowrap w-100">
                                <thead class="table-info">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jenis Kelamin</th>
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
            </div> <!-- end col -->
        </div> <!-- end row -->


            {{-- modal review --}}
            {{-- <div class="modal fade" id="containerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Data Training</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="#" id="formSubmit" enctype="multipart/form-data">
                                @csrf
                              
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
                                            <input type="text" class="form-control" id="training_tag" placeholder="Nama Tag Training" name="training_tag" required>
                                        </div>
                                    </div>
                            
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="document_pre_test">Upload Soal Pre-Test</label>
                                            <input type="file" class="form-control" id="document_pre_test" placeholder="Upload Here" name="document_pre_test" accept=".pdf">
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
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="document_post_test">Upload Soal Post-Test</label>
                                            <input type="file" class="form-control" id="document_post_test" placeholder="Upload Here" name="document_post_test" accept=".pdf">
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
            </div> --}}
          
            <!-- Static Backdrop Modal -->
            <div class="modal fade" id="containerModalTambah"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> Daftar Riwayat Training</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose1"></button>
                        </div>
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="table_materi" class="table table-borderless nowrap w-100" >
                                            <thead class="table-info">
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">Nama Training</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Pre Test</th>
                                                    <th scope="col">Ket</th>
                                                    <th scope="col">Post Test</th>
                                                    <th scope="col">Ket</th>
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
    {{-- end modal review --}}

</div>
@endsection
@push('script')
<script>
    var dropdownParentEl = $('.card > .card-header')
    $(document).ready(function(){ 
        var dataTableMaster;
        var table_materi;
        loadData()
        getDepartement()

    })
    
    function loadData(){
        departement = $('#departement').val();
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
            ajax: '{{route("riwayatTraining.get")}}'+`?departement=${departement}`,
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
                    data: 'kd_departement',
                    name: 'kd_departement'
                },
                {
                    data: 'nama_jabatan',
                    name: 'nama_jabatan'
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

    function getDepartement(){
        $('#departement').html('<option value="">Search By Department</option>');
                $.ajax({
                    type: "GET",
                    url: '{{route("departement.all")}}',
                    async: true,
                    success: function(data) {
                        console.log(data);
                        $('#departement').select2({    
                            data: data,
                            theme: "bootstrap-5",
                            dropdownParent: dropdownParentEl
                        });
                    },
                    error: function(data, textStatus, jqXHR) {},
                });  
    }


    function viewData(kd_karyawan) {
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
            ajax: "{{ route('training.getRiwayatTraining') }}"+`?kd_karyawan=${kd_karyawan}`,
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
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'nilai_pre_test',
                    name: 'nilai_pre_test'
                },
                {
                    data: 'Ket_pre_test',
                    name: 'Ket_pre_test'
                },
                {
                    data: 'nilai_post_test',
                    name: 'nilai_post_test'
                },
                {
                    data: 'Ket_post_test',
                    name: 'Ket_post_test'
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
        // var RiwayatList = $('#riwayat_list')
        // RiwayatList.html('');
        // $.ajax({
        //     type: "GET", 
        //     url: "{{ route('training.getRiwayatTraining') }}"+`?kd_karyawan=${kd_karyawan}`
        //     , success: function(data, textStatus, jqXHR) {
        //         // console.log(data);
        //         data.forEach(function(val) {

        //             if (val.avatar_training) {
        //                 url_foto = `storage/gambar_training/${val.avatar_training}`
        //             } else {
        //                 url_foto = `assets/images/auth-bg.jpg`;
        //             }

        //             element = `
        //      <div class="history-card-detail m-2">

        //          <div class="history-card-detail-left">
        //         <img src="{{ asset('${url_foto}') }}" alt="Training Image">
        //         </div>

        //         <div class="history-card-detail-center">
        //                 <div class="history-card-detail-body">
        //                 <h2>${val.nama_training}</h2>
        //                 <p>${val.deskripsi}</p>
        //                 </div>
        //                     <div class="history-card-detail-footer">
        //                        <button class="tombol">
        //                         ${val.status}</button>
        //                     </div>
        //         </div>

        //         <div class="history-card-detail-right">
        //             <button class="tombol review-button">Beri Ulasan</button>
        //         </div>
               
        //      </div>
        //     `;
        //             RiwayatList.append(element)
        //         })

        //     }
        //     , error: function(data, textStatus, jqXHR) {}
        // , });
        
    }


    // $('#table_materi').on('draw.dt', function () {
    //     var userName = "<?php echo auth()->user()->kd_karyawan; ?>";
    //     var dataTable = $('#table_materi').DataTable();
    //     var rowData = dataTable.row(0).data(); 
    //     var dataValue = rowData.kd_karyawan;
    //     console.log(dataValue);
    //     console.log(userName);
    //     if (userName === dataValue) {
    //         $('#nama_peserta').text(userName);
    //     }
    // });



</script>
@endpush

