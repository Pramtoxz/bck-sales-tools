@extends('layouts.app')
@section('content')         
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Data Notifikasi</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Notifikasi</li>
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
                                <button class="btn btn-success btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#containerModalTambah" onclick="loadPeserta()"><i class="bx bx-plus me-1"></i> Push Notifications</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-borderless wrap">
                            <thead class="table-info">
                            <tr>
                                <th style="width: 3%">No</th>
                                <th style="width: 4%">Action</th>
                                <th style="width: 23%">title</th>
                                <th style="width: 40%">Message</th>
                                <th style="width: 15%">Penerima</th>
                                <th style="width: 15%">Read At</th>
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
    <div class="modal fade" id="containerModalTambah" data-bs-backdrop="static"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> Push Notifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose1"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="title">Title</label>
                                <input type="text" class="form-control" id="title" placeholder="Title" name="title" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="message">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Message" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="table_peserta" class="table table-borderless nowrap w-100" >
                                    <thead class="table-info">
                                        <tr>
                                            <th scope="col"><input name="select_all" id="select_all" type="checkbox"> All</th>
                                            <th scope="col">Nama Peserta</th>
                                            <th scope="col">Departement</th>
                                            <th scope="col">Jabatan</th>
                                            <th scope="col">Kode</th>
                                          
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
            ajax: '{{route("notifTraining.get")}}',
            columns: [{
                    "searchable": false,
                    "targets": 0,
                    "data": null,
                    "width": "50px",
                    "sClass": "text-center",
                    "orderable": false
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable : false,
                    searchable : false
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'message',
                    name: 'message'
                },
                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'read_at',
                    name: 'read_at'
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
    
    function loadPeserta(){
        $('#title').val('')
        $('#message').val('')
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
            ajax: '{{route("pesertaTraining.get")}}',
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
        var title=$('#title').val()
        var message = $('#message').val()
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
                url: `{{route("notifTraining.peserta.save")}}`,
                type: 'POST',
                data : {
                    arrayData: arrSct,
                    title,
                    message
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
            function deleteData(id){
                console.log(id);
                    var url = "{{route('notifTraining.delete')}}"
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
</script>
@endpush


        