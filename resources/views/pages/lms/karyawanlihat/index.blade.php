@extends('layouts.app')
@section('content') 
<style>
  .vl {
  border-left: 1px solid #e0e0e0;
  height: 200px;
    }
    hr{
      color: #0087ff;
      border:1px solid #0087ff;
    }
    /* .vls {
  border-left: 1px solid #e0e0e0;
  height: 150px;
    } */
    .table-responsive {
      margin-top: 20px;
    }
    .table-responsive table {
      /* background-color: #ffffff;
      border-collapse: collapse; */
      width: 100%;
      max-width: 100%;
      margin-bottom: 1rem;
      /* color: #212529; */
      border: 1px solid rgba(0, 0, 0, 0.1);
    }
    .table-responsive table th,
    .table-responsive table td {
      padding: 0.75rem;
      vertical-align: top;
      border-top: 1px solid rgba(0, 0, 0, 0.1);
    }
    .table-responsive table thead th {
      vertical-align: bottom;
      border-bottom: 2px solid rgba(0, 0, 0, 0.1);
    }
    .table-responsive table tbody tr:nth-of-type(even) {
      background-color: rgba(0, 0, 0, 0.05);
    }
    .table-responsive table th {
      font-weight: 700;
      color: #495057;
      background-color: rgba(0, 0, 0, 0.03);
    }
  </style>
<div class="container-fluid">
    <div class="card position-relative">
        <div class="row">
          <div class="col-12">
              <div>
                <img src="{{asset('assets/images/lms/waves-top.svg')}}" style="width:100%;padding:0 !important;margin-bottom:-50px;" alt="">
              </div>
          </div>    
        </div> 
        <div class="card-body">
          <div class="row mb-2 justify-content-center">
              <div class="col-auto">
                  <div class="profile-picture">
                      <img
                      src=""
                      alt="Profile Picture "
                      class="rounded-circle mb-3 img-thumbnail"
                      style="width: 120px; height: 120px;border: 3px solid #0087ff;"
                      id="foto_karyawan"
                  /> 
                  </div>
              </div>
              <div class="col-12 text-center col-md-auto pt-4">
                  <h2 class="mb-1" id="nama_judul" style="font-weight: bold"></h2>
                  <p class="lead mb-0" id="judul_jabatan" style="font-weight: bold"></p>
              </div>
          </div>
        <hr />

        <div class="section mb-3">
          <div class="row">
            <div class="col-md-1 col-sm-2 text-center">
              <i class="fa fa-user fa-2x  rounded-circle   p-2 d-none d-md-block" style="color:#0087ff;"></i>
            </div>
            <div class="col-md-11 col-sm-10">
              <button type="button" class="btn text-white  btn-lg btn-block" style="background-color:#0087ff;">
                Data Pribadi
              </button>
              <div class="row mt-3">
                <div class="col-12" id="tabel1">
                
                  {{-- list --}}
                </div>
          
              </div>
            </div>
          </div>
        </div>
       
        <hr />
        <div class="section mb-3">
          <div class="row">
            <div class="col-md-1 col-sm-2 text-center">
              <i
                class="fas fa-award fa-2x  rounded-circle p-2 d-none d-md-block"
                style="color:#0087ff;"></i>
              {{-- <div class="vl text-center text-danger d-none d-md-block mt-2"></div> --}}
              {{-- <hr class="vertical-line d-md-block d-none" /> --}}
            </div>
            <div class="col-md-11 col-sm-10">
              <button type="button" class="btn text-white btn-lg btn-block" style="background-color:#0087ff;">
                List Training
              </button>
              <div class="table-responsive">
                <table id="datatable" class="table table-borderless nowrap w-100">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Training</th>
                        <th>Tanggal Mulai</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            
            </div>
          </div>
      </div>
      <hr>
        <div class="section mb-3">
          <div class="row">
            <div class="col-md-1 col-sm-2 text-center">
              <i
                class="fas fa-graduation-cap fa-2x  rounded-circle p-2 d-none d-md-block"
                style="color:#0087ff;"></i>
              {{-- <div class="vl text-center text-danger d-none d-md-block mt-2"></div> --}}
              {{-- <hr class="vertical-line d-md-block d-none" /> --}}
            </div>
            <div class="col-md-11 col-sm-10">
              <button type="button" class="btn text-white  btn-lg btn-block" style="background-color:#0087ff;">
                History Job
              </button>
              <div class="table-responsive">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Nama Jabatan</th>
                      <th>Mulai Menjabat</th>
                      <th>Akhir Menjabat</th>
                    </tr>
                  </thead>
                  <tbody id="tabel2">
                   
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        
          {{-- <hr /> --}}
     {{-- end image --}}
     

      </div>
      <div class="row">
        <div class="col-12">
            <div class="image" style="background-image: url({{asset('assets/images/lms/waves-bottom.svg')}}); background-repeat: no-repeat;background-size:cover;min-height:40vh;">
            </div>
        </div>    
      </div> 
    </div>
  </div>

@endsection

@push('script')
<script>
    $(document).ready(function(){       
        var id = "{{ $id }}";
        $('#foto_karyawan').attr('src','https://via.placeholder.com/150');
        loadDataAwal(id)
        loadData(id)
    })

    function loadDataAwal(id){
        console.log(id);
                $.ajax({
                    type: "GET", 
                    url: '{{route("karyawan.lihatdetail")}}' + `?id=${id}`,
                    success: function(response) {
                        console.log(response);
                        foto=response.foto;
                    if (foto) {
                    $('#foto_karyawan').attr('src','/storage/karyawan/' + foto);
                    }
                    $('#nama_judul').text(response.nama_lengkap);
                    $('#judul_jabatan').text(response.nama_jabatan);

                    tabel1 = `
                    <div class="form-group row">
                    <label class="col-12 col-md-2 text-md-right"><strong>Nama</strong></label>
                    <div class="col-12 col-md-4">${response.nama_lengkap}</div>
                    <label class="col-12 col-md-2 text-md-right"><strong>Departement</strong></label>
                    <div class="col-12 col-md-4">${response.deskripsi}</div>
                    </div>

                    <div class="form-group row">
                    <label class="col-12 col-md-2 text-md-right"><strong>Alamat</strong></label>
                    <div class="col-12 col-md-4">${response.alamat}</div>
                    <label class="col-12 col-md-2 text-md-right"><strong>Jabatan</strong></label>
                    <div class="col-12 col-md-4">${response.nama_jabatan}</div>
                    </div>

                    <div class="form-group row">
                    <label class="col-12 col-md-2 text-md-right"><strong>Tempat, Tanggal Lahir</strong></label>
                    <div class="col-12 col-md-4">${response.tempat_lahir}, ${changeFormatDate(response.tanggal_lahir)}</div>
                    <label class="col-12 col-md-2 text-md-right"><strong>Tanggal Bergabung</strong></label>
                    <div class="col-12 col-md-4">${changeFormatDate(response.tanggal_bergabung)}</div>
                    </div>

                    <div class="form-group row">
                    <label class="col-12 col-md-2 text-md-right"><strong>No Handphone</strong></label>
                    <div class="col-12 col-md-4">${response.no_hp}</div>
                    <label class="col-12 col-md-2 text-md-right"><strong>No. Ketenagakerjaan</strong></label>
                    <div class="col-12 col-md-4">${response.no_ketenagakerjaan}</div>
                    </div>

                    <div class="form-group row">
                    <label class="col-12 col-md-2 text-md-right"><strong>Email</strong></label>
                    <div class="col-12 col-md-4">${response.email}</div>
                    <label class="col-12 col-md-2 text-md-right"><strong>No. Kesehatan</strong></label>
                    <div class="col-12 col-md-4">${response.no_kesehatan}</div>
                    </div>
                    
                    `;
                   
                    $('#tabel1').append(tabel1)

                    response.historyjob.forEach(function(val) {  
                        tabel2 = `
                        <tr>
                      <td>${val.nama_jabatan}</td>
                      <td>${changeFormatDate(val.mulai_menjabat)}</td>
                      <td>${changeFormatDate(val.akhir_menjabat)}</td>
                    </tr>
                        `;
                    $('#tabel2').append(tabel2)   
                    });

                    // response.peserta_training.forEach(function(val) {   
                    //     let ket=''; 
                    //     val.nilai_post_test > 70 ? ket='Lulus' : ket='Tidak Lulus' ;
                    //     tabel3 = `
                    //     <tr>
                    //   <td>${val.nama_training}</td>
                    //   <td>${changeFormatDate(val.tanggal_mulai)}</td>
                    //   <td>${val.nilai_post_test}</td>
                    //   <td>${ket}</td>
                    // </tr>
                    //             `;
                    //     $('#tabel3').append(tabel3)
                    // });    
                    }
                });
     
    }
    function loadData(id){
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
            ajax: '{{route("karyawan.lihatdetailtraining")}}'+ `?id=${id}`,
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
                    data: 'tanggal_mulai',
                    name: 'tanggal_mulai'
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
    }

</script>
@endpush

