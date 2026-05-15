@extends('layouts.app')
@section('content')         
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Data Event Training</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Event Training</li>
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
                                <th>Nama Training</th>
                                <th>Jenis Training</th>
                                <th>Jumlah Peserta</th>
                                <th>Start Date</th>
                                <th>End Date</th>
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
                    <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Data Event Training</h5>
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
                                    <label class="form-label" for="kd_training">Pilih Training</label>
                                    <select class="form-control" id="kd_training" name="kd_training" required style="width:100%;" onchange="getKodeSoal()">
                                        <option value="">Pilih Training</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label class="form-label" for="tanggal_mulai">Start Training</label>
                                    <input type="text" class="form-control" id="tanggal_mulai" placeholder="Tanggal Mulai Training" name="tanggal_mulai" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label class="form-label" for="tanggal_akhir">Akhir Training</label>
                                    <input type="text" class="form-control" id="tanggal_akhir" placeholder="Tanggal Akhir Training" name="tanggal_akhir" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label class="form-label" for="kode_soal_pre_test">Pilih Soal Pre-Test</label>
                                    <select class="form-control" id="kode_soal_pre_test" name="kode_soal_pre_test" required style="width:100%;">
                                        <option value="">Pilih Soal Pre-Test</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label class="form-label" for="kode_soal_post_test">Pilih Soal Post Test</label>
                                    <select class="form-control" id="kode_soal_post_test" name="kode_soal_post_test" required style="width:100%;">
                                        <option value="">Pilih Soal Post-Test</option>
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
    <div class="modal fade" id="containerModalTambah" data-bs-backdrop="static"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> Tambah Peserta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose1"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <input type="hidden" name="kd_event_training" id="kd_event_training" />
                                <input type="hidden" name="kd_training" id="kd_training" />
                                <table id="table_peserta" class="table table-borderless nowrap w-100" >
                                    <thead class="table-info">
                                        <tr>
                                            <th scope="col"><input name="select_all" id="select_all" type="checkbox"> All</th>
                                            <th scope="col">Nama Peserta</th>
                                            <th scope="col">Departement</th>
                                            <th scope="col">Jabatan</th>
                                            <th scope="col">Kode</th>
                                            <th scope="col">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="submitPeserta()">Submit</button>
                    <div class="row">
                    <div id="loading" class="mt-3" style="display: none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <span>Sending Notifications, please wait</span>
                    
                    </div>
                    </div>
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
    const tanggal_mulai = flatpickr("#tanggal_mulai",{});
    const tanggal_akhir = flatpickr("#tanggal_akhir",{});
    var delayInMilliseconds = 1000; //1 second
    $(document).ready(function(){
        var dataTableMaster;
        var dataTablePeserta;
        // load data table awal
        loadData()
        $("form#formSubmit").submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            var url = "{{route('eventTraining.save')}}"
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

        getTraining() 
    })

    function getTraining(){
        $.ajax({
            type: "GET",
            url: "{{ route('training.all') }}",
            success: function(data, textStatus, jqXHR) {
                $('#kd_training').select2({      
                    data: data,
                    theme: "bootstrap-5",
                    dropdownParent: dropdownParentEl
                });
            },
            error: function(data, textStatus, jqXHR) {},
        });  
    }

    function getKodeSoal(){
        $('#kode_soal_pre_test').html('<option value="">Pilih Soal Pre-Test</option>');
        $('#kode_soal_post_test').html('<option value="">Pilih Soal Post-Test</option>');
        var kd_training = $('#kd_training').val()
        $.ajax({
            type: "GET",
            url: "{{ route('eventTraining.getKodeSoal') }}?kd_training="+kd_training,
            success: function(data, textStatus, jqXHR) {
                $('#kode_soal_pre_test').select2({      
                    data: data,
                    theme: "bootstrap-5",
                    dropdownParent: dropdownParentEl
                });
                $('#kode_soal_post_test').select2({      
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
            ajax: '{{route("eventTraining.get")}}',
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
                    data: 'training.jenis_training.nama_jenis',
                    name: 'training.jenis_training.nama_jenis'
                },
                {
                    data: 'jumlah_peserta',
                    name: 'jumlah_peserta',
                    "sClass": "text-center",
                    searchable : false
                },
                {
                    data: 'tanggal_mulai',
                    name: 'tanggal_mulai',
                },
                {
                    data: 'tanggal_akhir',
                    name: 'tanggal_akhir',
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable : false,
                    searchable : false
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
        $('#kd_training').val("")
        tanggal_mulai.clear()
        tanggal_akhir.clear()
    }

    function editData(id){
        $('#ket_submit').html("Update");
        $('#tipe_submit').val("edit");
        $('#id_data').val(id);
        // call ajax to get data
        var url = "{{route('eventTraining.show')}}"+`?id=${id}`
        $.ajax({
            url: url,
            type: 'GET',
            async: true,
            success: function(data) {
                $('#kd_training').val(data.kd_training).trigger("change");
                tanggal_mulai.setDate(data.tanggal_mulai, true);
                tanggal_akhir.setDate(data.tanggal_akhir, true);
                setTimeout(function() {
                    $('#kode_soal_pre_test').val(data.kode_soal_pre_test).trigger("change")
                $('#kode_soal_post_test').val(data.kode_soal_post_test).trigger("change")
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
                    var url = "{{route('eventTraining.delete')}}"
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

    function loadPeserta(kd_event_training,kd_training){
        $('#kd_event_training').val(kd_event_training)
        $('#kd_training').val(kd_training)
        $('#select_all').prop('checked',false);
        var rows_selected = [];
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
    
        dataTablePeserta = $('#table_peserta').DataTable({
            processing: true,
            serverSide: false,
            bDestroy: true,
            paging: true,
            ajax: `{{route("eventTraining.peserta")}}?kd_event_training=${kd_event_training}`,
            columns: [
                {
                    data: 'aksi',
                    name: 'aksi',
                    searchable : false,
                    orderable : false
                },
                {
                    data: 'nama_lengkap',
                    name: 'nama_lengkap'
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
                    data: 'kd_karyawan',
                    name: 'kd_karyawan',
                    visible:false
                }
                ,
                {
                    data: 'hapus',
                    name: 'hapus',
                    searchable : false,
                    orderable : false
                }
            ],
            columnDefs: [
                // Center align both header and body content of columns 1, 2 & 3
                { className: "dt-center", targets: [ 0] }
            ]
        });
        
        $('#table_peserta tbody').on('change', 'input[type="checkbox"]', function(e) {
			var $row = $(this).closest('tr');
			var data = dataTablePeserta.row($row).data();
			var rowId = data[0];
			var index = $.inArray(rowId, rows_selected);
			if (this.checked && index === -1) {
				rows_selected.push(rowId);
			} else if (!this.checked && index !== -1) {
				rows_selected.splice(index, 1);
			}
			if (this.checked) {
				$row.addClass('selected');
			} else {
				$row.removeClass('selected');
			}
			e.stopPropagation();
		});

		$('input[name="select_all"]', dataTablePeserta.table().container()).on('click', function(e) {
			if (this.checked) {
				$('#table_peserta tbody input[type="checkbox"]:not(:checked)').trigger('click');
			} else {
				$('#table_peserta tbody input[type="checkbox"]:checked').trigger('click');
			}
			e.stopPropagation();
		});
    }

    function submitPeserta(){
        $('#loading').show();
        var kd_training=$('#kd_training').val()
        var kd_event_training = $('#kd_event_training').val()
        var arrSct = [];
		var SctArr = $.map(dataTablePeserta.rows('.selected').data(),
			function(SctArr) {
				arrXSct = {
					kd_karyawan: SctArr.kd_karyawan,
				}
				arrSct.push(arrXSct);
			}
			
		);
		// console.log(arrSct);
		if (arrSct.length >= 1) {
            $.ajax({
                url: `{{route("eventTraining.peserta.save")}}`,
                type: 'POST',
                data : {
                    arrayData: arrSct,
                    kd_event_training,
                    kd_training
                },
                async: true,
                success: function(data) {
                    $('#loading').hide();
                    if(data.code == "200"){
                        $('#ket_success_toast').html(data.message)
                        new bootstrap.Toast(toastsuccess).show()
                        $('#table_peserta').DataTable().ajax.reload();
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
        }else{
            console.log("tidak ada data")
        }
    }

    function deleteDataPeserta(id_peserta_training){
        $.ajax({
                url: `{{route("eventTraining.peserta.delete")}}`,
                type: 'POST',
                data : {
                    id_peserta_training
                },
                async: true,
                success: function(data) {
                    if(data.code == "200"){
                        $('#ket_success_toast').html(data.message)
                        new bootstrap.Toast(toastsuccess).show()
                        $('#table_peserta').DataTable().ajax.reload();
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
</script>
@endpush


        