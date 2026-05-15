@extends('layouts.app')
@push('css-custom')
<style>
    button {
        transition: transform 0.3s;
        cursor: pointer;
    }

    button:hover {
        transform: scale(1.05);
    }

    .card {
        border: none;
    }

    /* .valueCard{
        font-size:1.0em;
    } */
    .valueCard1 {
        font-size: 0.75em;
    }

</style>
@endpush
@section('content')
<link rel="stylesheet" href="{{asset('assets/kendo-ui/styles/kendo.common-material.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/kendo-ui/styles/kendo.material.min.css')}}" />

<style>
    /* #container {
        width: 1000px;
        margin: 20px auto;
    } */



</style>
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Data Cuti</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Cuti</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    
    
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius:5px;">
                @if(Auth::user()->is_admin == 't')
                <div class="card-header">
                    <div class="row d-flex">
                        <div class="col-xl-4 col-sm-12 col-md-6 mb-3">
                            <select class="form-control" id="departement" name="departement">

                            </select>
                        </div>
                        {{-- <div class="col-1 mb-3">
                            <a onClick="carikaryawan()" class="btn btn-primary btn-md waves-effect waves-light" style="margin-left: 10px;"> <i class="fas fa-search" type="submit"></i></a>
                        </div> --}}
                        <div class="col-xl-4 col-sm-12 col-md-6 mb-3">
                            <select class="form-control" id="karyawan" name="karyawan">

                            </select>
                        </div>

                        <div class="col-xl-2 col-sm-12 col-md-6 mb-3">
                            <select class="form-control" id="tahun" name="tahun">

                            </select>
                        </div>

                        <div class="col-xl-2 col-sm-12 col-md-6 mb-3">
                            <div class="float-start">
                                <button class="btn btn-info btn-md waves-effect waves-light" onclick="loadData()"><i class="fas fa-search"></i> Filter</button>
                            </div>
                        </div>

                    </div>

                    {{-- <div class="mb-3">
                        <div class="float-start">
                            <button class="btn btn-success btn-md waves-effect waves-light" onclick="loadData()"><i class="bx bx-plus me-1"></i>Search</button>
                        </div>
                    </div> --}}

                </div>
                @endif

                <div class="card-body">

                    <div class="row justify-content-md-left">
                        <div class="card col-xl-3 col-sm-12 col-md-6">
                            <div class="card-body btn btn-danger">
                                <h5 class="text-white font-size-16 mb-1" style="text-align: center;">Sisa Cuti Tahunan</h5>
                                <p class="sisacutitahunan text-white mb-2 valueCard counter-value" style="text-align: center;"></p>
                            </div>
                        </div>
{{--                         
                        <div class="card col-xl-3 col-sm-12 col-md-6">
                            <div class="card-body btn btn-info">
                                <h5 class="text-white font-size-16 mb-1" style="text-align: center;">Cuti Tahunan</h5>
                                <p class="cutitahunan text-white mb-2 valueCard counter-value" style="text-align: center;"></p>
                            </div>
                        </div>
              
                        <div class="card col-xl-3 col-sm-12 col-md-6">
                            <div class="card-body btn btn-info">
                                <h5 class="text-white font-size-16 mb-1" style="text-align: center;">Cuti Melahirkan</h5>
                                <p class="cutimelahirkan text-white mb-2 valueCard counter-value" style="text-align: center;"></p>
                            </div>
                        </div>
                        <div class="card col-xl-3 col-sm-12 col-md-6">
                            <div class="card-body btn btn-info">
                                <h5 class="text-white font-size-16 mb-1" style="text-align: center;">Cuti Nikah</h5>
                                <p class="cutinikah text-white mb-2 valueCard counter-value" style="text-align: center;"></p>
                            </div>
                        </div> --}}

                    </div>
            

                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="float-start">
                                <button class="btn btn-success btn-md waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#containerModal" onclick="tambahDraft()"><i class="bx bx-plus me-1"></i> Ajukan Cuti</button>
                            </div>
                        </div>
                        @if(Auth::user()->is_admin == 't')
                        <div class="col-6">
                            <div class="float-end">
                                <button class="btn btn-danger btn-md waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#containerModal2" onclick="potongCuti()"><i class="bx bx-plus me-1"></i> Potong Cuti</button>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tabelcuti" class="table table-border nowrap w-100">
                                    <thead class="table-info">
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis Cuti</th>
                                            <th>Nama</th>
                                            <th>Tgl Buat</th>
                                            {{-- <th>Departement</th> --}}
                                            {{-- <th>Tanggal Cuti</th> --}}
                                            <th>Jumlah</th>
                                            <th>Surat</th>
                                            {{-- <th>File</th> --}}
                                            <th>Status</th>
                                            <th>Action</th>
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


    <div class="modal fade" id="containerModalDetail"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Detail Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose1"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                      <div class="row">
                        <div class="col-md-6 col-12">
                          <h6>Jenis Cuti:</h6>
                          <p></p>
                        </div>
                        <div class="col-md-6 col-12">
                          <h6>Nama Karyawan:</h6>
                          <p></p>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 col-12">
                          <h6>Departement:</h6>
                          <p style="text-align:justify"></p>
                        </div>
                        <div class="col-md-6 col-12">
                            <h6>Tanggal Pengajuan:</h6>
                            <p></p>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                            <h6>Tanggal Cuti:</h6>
                            <p style="word-wrap: break-word; max-width: 100%; overflow-wrap: break-word;">
                              
                              </p>
                              
                          </div>
                      </div>
                      <div class="row">
                     
                        <div class="col-md-6 col-12">
                            <h6>Jumlah Cuti:</h6>
                            <p></p>
                          </div>
                          <div class="col-md-6 col-12">
                            <h6>Alasan Cuti:</h6>
                            <p></p>
                          </div>
                      
                      </div>
                      <div class="row">
                   
                        <div class="col-md-6 col-12">
                          <h6>Perihal Cuti:</h6>
                          <p></p>
                        </div>
                        <div class="col-md-6 col-12">
                            <h6>Status Approval:</h6>
                            <p></p>
                          </div>
                      
                      </div>
                      <div class="row">
                   
                        <div class="col-md-6 col-12">
                          <h6>TTD Approval:</h6>
                          <p></p>
                        </div>
                        <div class="col-md-6 col-12">
                            <h6>Tanggal Approval:</h6>
                            <p></p>
                          </div>
                      
                      </div>
                      {{-- <div class="row">
                      
                        <div class="col-md-6 col-12">
                          <h6>Status Approval:</h6>
                          <p></p>
                        </div>
                
                      </div> --}}
                 
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="containerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Pengajuan Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <select class="form-control" id="jenis_cuti" name="jenis_cuti" required style="width:100%;">
                                    <option value="">Pilih Jenis Cuti</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <form class="needs-validation" novalidate action="#" id="formSubmit" style="display: none">
                        @csrf

                        <input type="hidden" class="form-control" id="tipe_submit" name="tipe_submit" required>
                        <input type="hidden" class="form-control" id="id_data" name="id_data">
                        <input type="hidden" class="form-control" id="id_cuti" name="id_cuti">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-1">
                                    <label class="form-label" for="perihal_cuti">Perihal Cuti</label>
                                    <input type="text" class="form-control" id="perihal_cuti" placeholder="Isikan Perihal Cuti" name="perihal_cuti" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-1">
                                    <label class="form-label" for="alasan">Alasan Cuti</label>
                                    <textarea class="form-control" id="alasan" name="alasan" rows="5" placeholder="Alasan Cuti ( Optional )"></textarea>
                                </div>
                            </div>
                        </div>


                        <div class="row" id="tampil_tanggal_cuti" style="display:none;">
                            <div class="col-md-12">
                                <div class="mb-1">
                                    <label class="form-label" for="tanggal_cuti">Tanggal Cuti</label>
                                    <input type="text" class="form-control" id="datepicker-multiple" name="tanggal_cuti" placeholder="Pilih Tanggal Cuti (Bisa Pilih Multiple)">
                                </div>
                            </div>
                        </div>

                        <div class="row" id="tampil_tanggal_cuti_range" style="display:none;">
                            <div class="col-md-12">
                                <div class="mb-1">
                                    <label class="form-label" for="tanggal_cuti_range">Tanggal Cuti</label>
                                    <input type="text" class="form-control" id="datepicker-range" name="tanggal_cuti_range" placeholder="Pilih Tanggal Cuti (In Range)">
                                </div>
                            </div>
                        </div>

                        <div class="row" id="tampil_upload_surat" style="display: none">
                            <div class="col-md-12">
                                <div class="mb-1">
                                    <label class="form-label" for="upload_surat">Upload Surat Sakit</label>
                                    <input type="file" class="form-control" id="upload_surat" name="upload_surat" placeholder="Upload Surat" name="upload_surat" required>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                            <div class="row">
                                <div id="loading" class="mt-3" style="display: none">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Validasi Cuti, please wait</span>
                                
                                </div>
                                </div>
                    </form>


                </div>
            </div>
        </div>
    </div>

</div>

    <div class="modal fade" id="containerModal2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel3" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalLabel3"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit2"></span> Potong Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose2"></button>
                </div>
                <div class="modal-body">
            
                    <form class="needs-validation" novalidate action="#" id="formSubmit2">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <select class="form-control" id="kd_karyawan" name="kd_karyawan" required style="width:100%;">
                                        <option value="">Pilih Karyawan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-1">
                                    <label class="form-label" for="alasan_potong">Alasan Potong Cuti</label>
                                    <textarea class="form-control" id="alasan_potong" name="alasan_potong" rows="5" placeholder="Alasan Potong Cuti" required></textarea>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-1">
                                    <label class="form-label" for="tanggalpotong">Tanggal Potong</label>
                                    <input type="text" class="form-control" id="tanggalpotong" name="tanggalpotong" placeholder="Pilih Tanggal Potong (Bisa Pilih Multiple)">
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

</div>
</div>


    @endsection

    @push('script')
    <script src="{{asset('assets/js/kendoui_custom.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/kendoui.min.js')}}"></script>

    <script>
        const tanggal_cuti = flatpickr("#datepicker-multiple", {
            mode: "multiple"
            , dateFormat: "d-m-Y"
            , minDate:"today"
        });
        const tanggal_cuti_range = flatpickr("#datepicker-range", {
            mode: "range"
            , dateFormat: "d-m-Y"
            , minDate:"today"
        });
        const tanggal_potong = flatpickr("#tanggalpotong", {
            mode: "multiple"
            , dateFormat: "d-m-Y"
            , minDate:"today"
        });


        $('.cutitahunan').html("<i class='fas fa-stopwatch'></i> "+10+" Hari");
        $('.cutimelahirkan').html("<i class='fas fa-stopwatch'></i> "+60+" Hari");
        $('.cutinikah').html("<i class='fas fa-stopwatch'></i> "+3+" Hari");
        // $('.sisacutitahunan').html("<i class='fas fa-stopwatch'></i> "+sisa_cuti_tahunan+" Hari");

        $(document).ready(function() {
            hitungsisacuti()
            loadData()
            getJenisCuti()
            getDepartement()
            getKaryawan()
            getTahun()

            $("form#formSubmit").submit(function(e) {
                e.preventDefault();
                $('#loading').show();
                var formData = new FormData($(this)[0]);
                // console.log(formData);
                var url = "{{route('simpandatacuti.post')}}"
                $.ajax({
                    url: url
                    , type: 'POST'
                    , data: formData
                    , async: true
                    , success: function(data) {
                        $('#loading').hide();
                        if (data.status == "true") {
                            $("#edClose").click();
                            $('#tabelcuti').DataTable().ajax.reload();

                            if(data.id_cuti == "4"){
                                // $('#ket_success_toast').html("Pengajuan Izin Sakit Berhasil");
                                Swal.fire({
                                    icon: 'success'
                                    , title: 'Berhasil'
                                    , text: 'Izin Sakit Berhasil Terkirim!'
                                    , confirmButtonText: 'OK'
                                    });
                            }else{
                                // $('#ket_success_toast').html("Pengajuan cuti berhasil, Menunggu Persetujuan Manager!");
                                Swal.fire({
                                    icon: 'success'
                                    , title: 'Berhasil'
                                    , text: 'Pengajuan cuti berhasil, Menunggu Persetujuan Manager!'
                                    , confirmButtonText: 'OK'
                                    });
                            }
                           
                            // new bootstrap.Toast(toastsuccess).show();
                            // $('#tabelcuti').DataTable().ajax.reload();
                            // window.location.reload();
                        
                            $("#formSubmit")[0].reset();
                        } else {
                            // $('#ket_failed_toast').html(data.message);
                            // new bootstrap.Toast(toastfailed).show();
                            Swal.fire({
                                    icon: 'warning'
                                    , title: 'Gagal!!!'
                                    , text: data.message
                                    , confirmButtonText: 'OK'
                                    });
                        }
                        // if (data.status == 'success') {
                        //     SimpanCuti(formData);
                        //     // $("#edClose").click();
                        //     // $('#tabelcuti').DataTable().ajax.reload();
                        // } else {
                        //     $('#ket_failed_toast').html(data.message)
                        //     new bootstrap.Toast(toastfailed).show()
                        // }
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
                var url = "{{route('simpanpotongcuti.get')}}"
                $.ajax({
                    url: url
                    , type: 'POST'
                    , data: formData
                    , async: true
                    , success: function(data) {
                        if (data.status == "true") {
                            $("#edClose2").click();
                            $('#tabelcuti').DataTable().ajax.reload();
                            $('#ket_success_toast').html("Sukses Potong Cuti Karyawan!");
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

            $('#jenis_cuti').on('change', function() {
                var tampungjenis = $(this).val();

                if (tampungjenis) {
                    $('#tampil_upload_surat').hide();
                    $('#tampil_tanggal_cuti').hide();
                    $('#tampil_tanggal_cuti_range').hide();
                    $('#formSubmit').show();
                    $('#id_cuti').val(tampungjenis);

                    if (tampungjenis == '1' || tampungjenis == '3') {
                        $('#tampil_tanggal_cuti').show();
                        $('#upload_surat').prop('required', false);
                        $('#tanggal_cuti').prop('required', true);
                        $('#tanggal_cuti_range').prop('required', false);
                        tanggal_cuti_range.clear()
                        $('#upload_surat').val("");
                    } else if(tampungjenis == '4'){
                        $('#tampil_tanggal_cuti').show(); 
                        $('#tampil_upload_surat').show();
                        $('#upload_surat').prop('required', true);
                        $('#tanggal_cuti').prop('required', true);
                        $('#tanggal_cuti_range').prop('required', false);  
                        tanggal_cuti_range.clear()
                    }else{
                        $('#tampil_tanggal_cuti_range').show();
                        $('#upload_surat').prop('required', false);
                        $('#tanggal_cuti').prop('required', false);
                        $('#tanggal_cuti_range').prop('required', true);
                        tanggal_cuti.clear()
                        $('#upload_surat').val("");
                    }
                } else {
                    alert("Jenis Cuti Tidak Boleh Kosong.");
                    $('#formSubmit').hide();
                }
            });

            $('#departement').on('change', function() {
                var kd_departement = $('#departement').val();

                $.ajax({
                    url: '{{url("/cuti/ambilkaryawan?kd_departement=")}}' + kd_departement
                    , type: 'GET'
                    , async: false
                    , success: function(data) {
                        $('#karyawan').html('<option value="">Pilih Karyawan</option>');
                        $('#karyawan').select2({
                            data: data,
                            theme: "bootstrap-5"
                        }); 
                    }
                });
            });


        })
      

        function loadData() {

            karyawan = $('#karyawan').val();
            karyawan = karyawan == null ? "" : karyawan;
            departement = $('#departement').val();
            departement = departement == null ? "" : departement;
            tahun = $('#tahun').val();
            tahun = tahun == null ? "" : tahun;

            $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
                return {
                    "iStart": oSettings._iDisplayStart
                    , "iEnd": oSettings.fnDisplayEnd()
                    , "iLength": oSettings._iDisplayLength
                    , "iTotal": oSettings.fnRecordsTotal()
                    , "iFilteredTotal": oSettings.fnRecordsDisplay()
                    , "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength)
                    , "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                };
            };

            dataTableMaster = $('#tabelcuti').DataTable({
                processing: true
                , serverSide: true
                , bDestroy: true
                , paging: true
                , ajax: `{{ url('/cuti/get') }}?departement=${departement}&karyawan=${karyawan}&tahun=${tahun}`
                // , ajax: "{{url('/cuti/get')}}"
                , columns: [{
                        "searchable": false
                        , "targets": 0
                        , "data": null
                        , "width": "30px"
                        // , "sClass": "text-center"
                        , "orderable": false
                    }
                    , {
                        data: 'jenis_cuti'
                        , name: 'jenis_cuti'
                    }
                    , {
                        data: 'nama_lengkap'
                        , name: 'nama_lengkap'
                    }
                    , {
                        data: 'created_at'
                        , name: 'created_at'
                    }
                    // , {
                    //     data: 'deskripsi'
                    //     , name: 'deskripsi'
                    // , }
                    // , {
                    //     data: 'tgl_cuti'
                    //     , name: 'tgl_cuti'
                    // , }
                    , {
                        data: 'jumlah_cuti'
                        , name: 'jumlah_cuti'
                    , }
                    , {
                        data: 'file'
                        , name: 'file'
                    , }
                    // , {
                    //     data: 'file'
                    //     , name: 'file'
                    // , }
                    , {
                        data: 'status_approval'
                        , name: 'status_approval'
                    , }
                    , {
                        data: 'aksi'
                        , name: 'aksi'
                        , "width": "170px"
                        , orderable: false
                        , searchable: false
                    }
                , ]
                // , "order": [
                //     [1, 'asc']
                // ]
                , "rowCallback": function(row, data, iDisplayIndex) {
                    var info = this.fnPagingInfo();
                    var page = info.iPage;
                    var length = info.iLength;
                    var index = page * length + (iDisplayIndex + 1);
                    $('td:eq(0)', row).html(index);
                }
            });
        }

        function tambahDraft() {
            $('#ket_submit').html('Tambah');
            $('#tipe_submit').val("add");
            $('#id_data').val("");
            // $('#jenis_cuti').val("").trigger("change")
            $('#perihal_cuti').val("");
            $('#alasan').val("");
            $('#upload_surat').val("");

            tanggal_cuti.clear()
            tanggal_cuti_range.clear()

        }

        function potongCuti() {
            $('#ket_submit').html('Tambah');
            $('#tipe_submit').val("add");
            $('#id_data').val("");
            $('#kd_karyawan').val("").trigger("change")
            $('#alasan_potong').val("");
            tanggal_potong.clear()

        }

        function getJenisCuti(user) {
            $.ajax({
                type: "GET"
                , url: '{{route("getjeniscuti.get")}}'
                , async: true
                , success: function(data) {
                    $('#jenis_cuti').select2({
                        data: data
                        , theme: "bootstrap-5"
                    });

                }
                , error: function(data, textStatus, jqXHR) {}
            , });
        }

        function getKaryawan() {
            $.ajax({
                    url: '{{url("/cuti/ambilkaryawan")}}'
                    , type: 'GET'
                    , async: false
                    , success: function(data) {
                        $('#kd_karyawan').html('<option value="">Pilih Karyawan</option>');
                        $('#kd_karyawan').select2({
                            data: data,
                            theme: "bootstrap-5"
                        }); 
                    }
                });
        }

        function getTahun() {
            $.ajax({
                type: "GET",
                url: '{{ route("getYear.all") }}',
                async: true,
                success: function(data) {
                    $('#tahun').select2({
                        data: data.map(function(year) {
                            return { id: year, text: year };
                        }),
                        theme: "bootstrap-5"
                    });

                    var tahunsekarang = new Date().getFullYear();

                    $('#tahun').val(tahunsekarang).trigger('change');
                    
                },
                error: function(data, textStatus, jqXHR) {

                }
            });
        }

        function detail(id){
            $.ajax({
            type: 'GET',
            url: '{{route("getcutidetail.get")}}'+`?id=${id}`,
                success: function(response) {
                    tampilkanDetailCuti(response[0]);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
            // getHistory(kd_event_training)
        }
        function tampilkanDetailCuti(data)
        {
            let DataDetail = Object.values(data);
            // console.log(DataDetail);

            $("#containerModalDetail").find("p").each(function(index) {
            $(this).text(DataDetail[index]);
            });
            $('#containerModalDetail').modal('show');

        }

        function reject(id){
            Swal.fire({
            title:"Yakin Mau Reject Cuti?",
            text:"Setelah Di Reject Tidak bisa Di batalkan lagi!",
            icon:"warning",
            showCancelButton:!0,
            confirmButtonColor:"#2ab57d",
            cancelButtonColor:"#fd625e",
            confirmButtonText:"Ya, Reject saja!"}).then(function(e){
                if(e.value){
                    var url = "{{route('rejectCuti.save')}}"
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { 
                            id
                        },
                        async: true,
                        success: function(data) {
                            if(data.code == "200"){
                                // $('#ket_success_toast').html(data.message)
                                // new bootstrap.Toast(toastsuccess).show()
                                // window.location.reload();
                                Swal.fire({
                                    icon: 'success'
                                    , title: 'Berhasil Reject Cuti'
                                    , text: 'Terima kasih atas Konfirmasinya!'
                                    , confirmButtonText: 'OK'
                                    });
                                    $('#tabelcuti').DataTable().ajax.reload();
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

        function approval(id){
            Swal.fire({
            title:"Yakin Mau Approve Cuti?",
            text:"Setelah Di Approve Tidak bisa Di batalkan lagi!",
            icon:"warning",
            showCancelButton:!0,
            confirmButtonColor:"#2ab57d",
            cancelButtonColor:"#fd625e",
            confirmButtonText:"Yes, Approve it!"}).then(function(e){
                if(e.value){
                    var url = "{{route('approveCuti.save')}}"
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { 
                            id
                        },
                        async: true,
                        success: function(data) {
                            if(data.code == "200"){
                                   Swal.fire({
                                    icon: 'success'
                                    , title: 'Berhasil Approve Cuti'
                                    , text: 'Terima kasih atas Konfirmasinya!'
                                    , confirmButtonText: 'OK'
                                    });
                                // $('#ket_success_toast').html(data.message)
                                // new bootstrap.Toast(toastsuccess).show()
                                $('#tabelcuti').DataTable().ajax.reload();
                                // setTimeout(function() {
                                    hitungsisacuti();
                                        // window.location.reload();
                                    // }, 2000);
                            }else{
                                Swal.fire({
                                    icon: 'warning'
                                    , title: 'Gagal Approve Cuti'
                                    , text: 'Yang bersangkutan sudah melebihi batas kuota pengambilan Cuti.!'
                                    , confirmButtonText: 'OK'
                                    });
                                // $('#ket_failed_toast').html(data.message)
                                // new bootstrap.Toast(toastfailed).show()
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

        function editData(id){

        $('#ket_submit').html("Update");
        $('#tipe_submit').val("edit");
        $('#id_data').val(id);
       
   
        var url = "{{route('editCuti.show')}}"+`?id=${id}`
    
        $.ajax({
            url: url,
            type: 'GET',
            async: true,
            success: function(data) {
                // console.log(data);
                $('#jenis_cuti').val(data.id_jenis_cuti).trigger("change")
                $('#alasan').val(data.alasan)
                $('#perihal_cuti').val(data.perihal_cuti)
                if(data.id_jenis_cuti=='2'){
                    $('#datepicker-range').val(data.tgl_cuti)
                }else{
                    $('#datepicker-multiple').val(data.tgl_cuti)
                }
                $('#id_cuti').val(data.id_jenis_cuti);

                // tanggal_lahir.setDate(data.tanggal_lahir, true);
                // tanggal_gabung.setDate(data.tanggal_bergabung, true);
             
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
            title:"Yakin Batal Ajukan Cuti?",
            text:"Cuti tidak akan Di Proses Jika Click Ya!",
            icon:"warning",
            showCancelButton:!0,
            confirmButtonColor:"#2ab57d",
            cancelButtonColor:"#fd625e",
            confirmButtonText:"Ya, Gak Jadi Cuti!"}).then(function(e){
                if(e.value){
                    var url = "{{route('hapuscuti.delete')}}"
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
                                $('#tabelcuti').DataTable().ajax.reload();;
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
            })
        }

    function getDepartement() {
        $.ajax({
            type: "GET"
            , url: '{{route("departement.all")}}'
            , async: true
            , success: function(data) {
                    $('#departement').html('<option value="">Pilih Departement</option>');
                    $('#departement').select2({
                    data: data,
                    theme: "bootstrap-5"
                });  
            }
            , error: function(data, textStatus, jqXHR) {}
        , });
    }

    function hitungsisacuti(){
        var url = "{{route('getsisacuti.get')}}"
    
        $.ajax({
            url: url,
            type: 'GET',
            async: true,
            success: function(data) {
                // console.log(data);
            $('.sisacutitahunan').html("<i class='fas fa-stopwatch'></i> "+data+" Hari");             
            },

            error: function(xhr, status, error) {
                console.log(error)
                $('#ket_failed_toast').html(JSON.parse(xhr.responseText))
                new bootstrap.Toast(toastfailed).show()
            },
        });
    }

    // function carikaryawan(){
    //     var kd_departement = $('#departement').val();
    //     $.ajax({
    //         url: '{{url("/cuti/ambilkaryawan?kd_departement=")}}' + kd_departement
    //         , type: 'GET'
    //         , async: false
    //         , success: function(data) {
    //             $('#karyawan').html('<option value="">Pilih Karyawan</option>');
    //             $('#karyawan').select2({
    //                 data: data,
    //                 theme: "bootstrap-5"
    //             }); 
    //         }
    //     });
    // }


    </script>

    @endpush
