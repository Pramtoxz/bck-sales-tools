@extends('layouts.app')
@section('content') 
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Data Profile</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">

            <div class="card mb-0" style="border-radius:5px;">
                <div class="nav justify-content-start">
                    <img src="{{asset('assets/images/small/img-9.jpg')}}" class="card-img-top" alt="..." style="max-width: 100%; max-height: 220px; opacity:0.6;">
                    {{-- <div class="card-img-overlay d-flex justify-content-left align-items-left">
                        <img src="" class="rounded-circle avatar-xxl col-3 col-sm-3 col-md-3 col-lg-3 col-xl-2 col-xxl-2" alt="user-pic"  id="foto_karyawan">
                    </div> --}}
                </div>
            </div>

            <div class="card" style="border-radius:5px;">
                <div class="card-header align-items-center d-flex">
                    <div class="flex-shrink-0">
                        <ul class="nav justify-content-start nav-pills card-header-pills" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#table1" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-table"></i></span>
                                    <span class="d-none d-sm-block">Edit Profile</span> 
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#table2" role="tab" >
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">History Job</span> 
                                </a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#table3" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">Change Password</span> 
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
    <div class="card-body">
        <div class="tab-content text-muted">
            <div class="tab-pane active" id="table1" role="tabpanel">
                <form class="needs-validation" novalidate action="#" id="formSubmit" enctype="multipart/form-data">
                    @csrf
                    {{-- header --}}
                    <input type="hidden" class="form-control" id="tipe_submit" name="tipe_submit" value="edit" required>
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
                                <label class="form-label" for="nama_panggilan">Nama Panggilan*</label>
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
                                <label class="form-label" for="email">Email*</label>
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
                                <label class="form-label" for="pendidikan">Pendidikan Terakhir*</label>
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
                        <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label" for="noker">No. Ketenagakerjaan</label>
                                <input type="text" class="form-control" id="noker" placeholder="No. Ketenagakerjaan" name="noker">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label" for="nokes">No. Kesehatan</label>
                                <input type="text" class="form-control" id="nokes" placeholder="No. Kesehatan" name="nokes">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label" for="kd_jabatan_wlk">Kode Jabatan WLK</label>
                                <input type="text" class="form-control" id="kd_jabatan_wlk" placeholder="Kode Jabawan WLK" name="kd_jabatan_wlk">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label" for="npwp">NPWP</label>
                                <input type="text" class="form-control" id="npwp" placeholder="No NPWP" name="npwp">
                            </div>
                        </div>     
                    </div>

                    <div class="row">

                        <div class="col-md-6 mt-2">
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
                                <select name="active" id="active" class="form-control" @disabled(true)>
                                    <option value="t">Aktif</option>
                                    <option value="f">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>    
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success float-end">Update</button>
                        </div>
                    </div>
                </form>
            </div>
                    {{-- tabel history job --}}
            {{-- <div class="tab-pane" id="table2" role="tabpanel">
                            <div class="row mb-3" >
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="datatable" class="table table-borderless nowrap w-100">
                                                    <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Jabatan</th>
                                                        <th>Departement</th>
                                                        <th>Mulai Menjabat</th>
                                                        <th>akhir Menjabat</th>
                                                        <th>Resign</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                            </div>
            </div> --}}
                        {{-- end table history job --}}
                        {{-- form ganti pasword --}}
        <div class="tab-pane" id="table3" role="tabpanel">
        <form class="needs-validation" novalidate action="#" id="formSubmit">
        @csrf
            <div class="row mb-4">
                <div class="col-md-3">
                <label class="form-label" for="old_password">Old Password</label>
                </div>
                <div class="col-md-6">
                <input type="password" class="form-control" id="old_password" placeholder="Enter Current Password" name="old_password">
                <span id="error-check" class="text-danger error-text old_password_error" style="font-size: 12px;position: absolute;"></span>
                </div>   
            </div>

            <div class="row mb-4">      
                <div class="col-md-3">
                <label class="form-label" for="new_password">New Password</label>
                </div>
                <div class="col-md-6">
                <input type="password" class="form-control" id="new_password" placeholder="Enter new Password" name="new_password">
                <span id="error-check" class="text-danger error-text new_password_error" style="font-size: 12px;position: absolute;"></span>
            </div>            
            </div>

            <div class="row mb-4">        
                <div class="col-md-3">
                <label class="form-label" for="retype_new_password">Retype New Password</label>
                </div>
                <div class="col-md-6">
                <input type="password" class="form-control" id="retype_new_password" placeholder="ReEnter New Password" name="retype_new_password">
                <span id="error-check" class="text-danger error-text retype_new_password_error" style="font-size: 12px;position: absolute;"></span>
            </div>
            </div> 

            <div class="row mb-4"> 
                <div class="col-md-3 offset-md-3">
                <button type="submit" class="btn btn-success">Submit</button>
                <button type="button" class="btn btn-warning" onclick="reset()">Reset Form</button>
                </div>
            
            </div>
        </form>
            
            
        </div>


            {{-- end form ganti password --}}
    </div>      
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->


</div>
@endsection
@push('script')
<script>
    var delayInMilliseconds = 1000; //1 second
    const tanggal_lahir = flatpickr("#tanggal_lahir",{});
    const tanggal_gabung = flatpickr("#tanggal_gabung",{});
    $(document).ready(function(){
        loadHistoryJob()
        $('#old_password').val("");
        $('#new_password').val("");
        $('#retype_new_password').val("");

        //save new password
        $("form#formSubmit").submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            var url = "{{route('profileChangePassword.update')}}"
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                async: true,
                success: function(data) {
                    if(data.status == "0"){
                        $.each(data.error,function(prefix,val){
                            $('span.'+prefix+'_error').text(val[0]);
                            setTimeout(() => {
                            $('span.'+prefix+'_error').text("");
                        }, 2000);
                        });
                       
                    }else{
                        $('form#formSubmit')[0].reset();
                        $('#ket_success_toast').html(data.msg)
                        new bootstrap.Toast(toastsuccess).show()
                        $('span.'+prefix+'_error').text("");
                        // $('#ket_failed_toast').html(data.message)
                        // new bootstrap.Toast(toastfailed).show()
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
        //end save new password
        getPendidikan() 
        getAgama()   
        getJK()
        getStatus()
        getDepartement()
        $('#departement').change(function(){
            getJabatan()
        });
        setTimeout(function() {
            loadData()
        }, delayInMilliseconds);

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
        $('#modal-preview').attr('src','https://via.placeholder.com/150');
        $('#foto_karyawan').attr('src','https://via.placeholder.com/150');
    })

    function getPendidikan(){
        $.ajax({
            type: "GET",
            url: "{{ route('pendidikan.all') }}",
            success: function(data, textStatus, jqXHR) {
                $('#pendidikan').select2({      
                    data: data,
                    theme: "bootstrap-5",
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
                console.log(data)
                $('#jbt').select2({      
                    data: data,
                    theme: "bootstrap-5",
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
                });
            },
            error: function(data, textStatus, jqXHR) {},
        });     
    }

    function loadData(){
        var url = "{{route('profile.get')}}"
        $.ajax({
            url: url,
            type: 'GET',
            async: true,
            success: function(data) {
                console.log(data)
                var dataprofile=data;
                $('#id_data').val(dataprofile.id)
                $('#departement').val(dataprofile.kd_departement).trigger('change')
                // $('#jbt').val(dataprofile.kd_jabatan).trigger('change')
                $('#nama_lengkap').val(dataprofile.nama_lengkap)
                $('#nama_panggilan').val(dataprofile.nama_panggilan)
                $('#tempat_lahir').val(dataprofile.tempat_lahir)
                // $('#tanggal_lahir').val(dataprofile.tanggal_lahir)
                tanggal_lahir.setDate(dataprofile.tanggal_lahir, true);
                $('#email').val(dataprofile.email)
                $('#jk').val(dataprofile.jenis_kelamin).trigger('change')
                $('#pendidikan').val(dataprofile.kd_pendidikan).trigger('change')
                $('#agama').val(dataprofile.kd_agama).trigger('change')
                $('#alamat').val(dataprofile.alamat)
                $('#sts').val(dataprofile.kd_status).trigger('change')
                $('#nama_susis').val(dataprofile.nama_pasangan)
                $('#jumlah_anak').val(dataprofile.jumlah_anak)
                $('#nama_ibu').val(dataprofile.nama_ibu)
                $('#notelp').val(dataprofile.no_hp)
                // $('#tanggal_gabung').val(dataprofile.tanggal_bergabung)
                tanggal_gabung.setDate(dataprofile.tanggal_bergabung, true);
                $('#no_ktp').val(dataprofile.no_ktp)
                $('#no_kk').val(dataprofile.no_kk)
                $('#noker').val(dataprofile.no_ketenagakerjaan)
                $('#nokes').val(dataprofile.no_kesehatan)
                $('#kd_jabatan_wlk').val(dataprofile.kode_jabatan_wlk)
                $('#npwp').val(dataprofile.npwp)
                if (dataprofile.foto) {
                    $('#modal-preview').attr('src','/storage/karyawan/' + dataprofile.foto);
                    $('#foto_karyawan').attr('src','/storage/karyawan/' + dataprofile.foto);
                }else{
                    console.log("Tidak Ada Foto")
                }
                setTimeout(function() {
                    $('#jbt').val(dataprofile.kd_jabatan).trigger("change")
                }, delayInMilliseconds);      
            },
        });
    }

    function loadHistoryJob(){
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
            ajax: '{{route("profilehistory.get")}}',
            columns: [{
                    "searchable": false,
                    "targets": 0,
                    "data": null,
                    "width": "50px",
                    "sClass": "text-center",
                    "orderable": false
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

    function reset()
    {
        $('#old_password').val('')
        $('#new_password').val('')
        $('#retype_new_password').val('')
        
    }



</script>
@endpush