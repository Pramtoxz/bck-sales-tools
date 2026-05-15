@extends('layouts.app')
@section('content') 

<link href="{{asset('assets/ckeditor/lark.css')}}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/ckeditor/ckeditor5.css')}}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/ckeditor/ckeditor5-premium-features.css')}}" id="app-style" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('assets/kendo-ui/styles/kendo.common-material.min.css')}}"/>
<link rel="stylesheet" href="{{asset('assets/kendo-ui/styles/kendo.material.min.css')}}"/>

<style>
    #container {
        width: 1000px;
        margin: 20px auto;
    }
    .ck-editor__editable[role="textbox"] {
        /* editing area */
        min-height: 200px;
    }
    .ck-content .image {
        /* block images */
        max-width: 80%;
        margin: 20px auto;
    }
    /* .table-responsive {
      margin-top: 20px;
    } */
    .table-responsive table {
      /* background-color: #ffffff;
      border-collapse: collapse; */
      width: 100%;
      max-width: 100%;
      /* margin-bottom: 1rem; */
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

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Data Proposal</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Proposal</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius:5px;">
                <div class="card-header align-items-center d-flex">
                    <div class="flex-shrink-0">
                        <ul class="nav justify-content-start nav-pills card-header-pills" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#table1" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-table"></i></span>
                                    <span class="d-none d-sm-block">Draft</span> 
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#table2" role="tab" onclick="loadKaryawanList()">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">Submitted Proposal</span> 
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#table3" role="tab" onclick="loadKaryawanList()">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">Final Approval</span> 
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content text-muted">
                        <div class="tab-pane active" id="table1" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="float-start">
                                        <button class="btn btn-success btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#containerModal" onclick="tambahDraft()"><i class="bx bx-plus me-1"></i> Create Draft</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <table id="datatable" class="table table-borderless nowrap w-100">
                                            <thead class="table-info">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>No. Proposal</th>
                                                <th>Judul Proposal</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="table2" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-lg-auto col-auto">
                                    <div class="mb-3">
                                        <h3 class="text-success card-title">Jumlah Data 2 <span class="text-success fw-normal ms-1" id="jumlah_karyawan_list">(0)</span></h3>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-auto col-auto">
                                    <div id="check-karyawan-list" class="row">  
                                    </div>
                                </div>
                            </div>
                            <div class="row" >
                                
                                <div class="col-md-12 col-lg-12">
                                    <div class="row"id="karyawan_list">
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="tab-pane" id="table3" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-lg-auto col-auto">
                                    <div class="mb-3">
                                        <h3 class="text-success card-title">Jumlah Data 3 <span class="text-success fw-normal ms-1" id="jumlah_karyawan_list">(0)</span></h3>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-auto col-auto">
                                    <div id="check-karyawan-list" class="row">  
                                    </div>
                                </div>
                            </div>
                            <div class="row" >
                                
                                <div class="col-md-12 col-lg-12">
                                    <div class="row"id="karyawan_list">
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->


    <div class="modal fade" id="containerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Data Draft</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose"></button>
                </div>
                <div class="modal-body">
                   
                    <div id="progrss-wizard" class="twitter-bs-wizard">
                        <ul class="twitter-bs-wizard-nav nav nav-pills nav-justified">
                            <li class="nav-item">
                                <a href="#pendahuluan" class="nav-link" data-toggle="tab">
                                    <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="pendahuluan">
                                        <i class='bx bx-layer'></i>
                                    </div>
                                </a>
                                <h6>Pendahuluan</h6>
                            </li>
                            
                            <li class="nav-item">
                                <a href="#kegiatan" class="nav-link" data-toggle="tab">
                                    <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Bank Details">
                                        <i class="bx bxs-bank"></i>
                                    </div>
                                </a>
                                <h6>Kegiatan</h6>
                            </li>
                            <li class="nav-item">
                                <a href="#detail" class="nav-link" data-toggle="tab">
                                    <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="pendahuluan">
                                        <i class="bx bx-list-ul"></i>
                                    </div>
                                </a>
                                <h6>Detail</h6>
                            </li>
                            <li class="nav-item">
                                <a href="#beban" class="nav-link" data-toggle="tab">
                                    <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Company Document">
                                        <i class='bx bxs-book-add'></i>
                                    </div>
                                </a>
                                <h6>Beban</h6>
                            </li>
                            
                            <li class="nav-item">
                                <a href="#approval" class="nav-link" data-toggle="tab">
                                    <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Bank Details">
                                        <i class='bx bx-check-double'></i>
                                    </div>
                                </a>
                                <h6>Peserta & Approval</h6>
                            </li>
                            <li class="nav-item">
                                <a href="#konfirmasi" class="nav-link" data-toggle="tab">
                                    <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Company Document">
                                        <i class='bx bx-save'></i>
                                    </div>
                                </a>
                                <h6>Konfirmasi</h6>
                            </li>
                        </ul>
                        <!-- wizard-nav -->

                        <div id="bar" class="progress mt-4">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"></div>
                        </div>
                        <div class="tab-content twitter-bs-wizard-tab-content">
                            <div class="tab-pane" id="pendahuluan">
                                <form id="proposal" action="{{ route('proposal.save') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="judulproposal">Judul Proposal</label>
                                                <input type="text" class="form-control" id="judulproposal" placeholder="Masukkan Judul Proposal">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="tanggal_buat">Tanggal</label>
                                                <input type="text" class="form-control" id="tanggal_buat" placeholder="Masukkan Tanggal Proposal" name="tanggal_buat" required>
                                                {{-- <input type="date" class="form-control" id="tanggal_buat" placeholder="Masukkan Tanggal Proposal"> --}}
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="no_proposal">No. Proposal</label>
                                                <input type="text" class="form-control" id="no_proposal" placeholder="Masukkan Nomor Proposal">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                        <div class="mb-3">
                                        <label for="no_proposal">Latar Belakang</label>
                                        <textarea id="editor"></textarea>
                                    
                                        </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="tujuan" class="form-label">Tujuan</label>
                                                <div id="editor2" ></div>
                                            </div>
                                        </div>
                                    </div>
                                 
                               
                                    <ul class="pager wizard twitter-bs-wizard-pager-link">
                                    <li class="next"><a href="javascript: void(0);" class="btn btn-primary">Next <i
                                                class="bx bx-chevron-right ms-1"></i></a></li>
                                     </ul>
                            </div>
                            <div class="tab-pane" id="kegiatan">
                              <div>
                                <div id="listkegiatan">

                                    <div class="row kegiatann">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                            <label for="bentuk_kegiatan">Bentuk Kegiatan</label>
                                            <div id="editor4" class="editor4"></div>
                                            </div>
                                            </div>
                                      
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai Kegiatan</label>
                                                <input type="text" class="form-control tanggal_mulai" id="tanggal_mulai" placeholder="Tanggal Mulai Kegiatan">
                                            </div>
                                            <div class="mb-3">
                                                <label for="tanggal_akhir" class="form-label">Tanggal Akhir Kegiatan</label>
                                                <input type="text" class="form-control tanggal_akhir" id="tanggal_akhir" placeholder="Tanggal Akhir Kegiatan">
                                            </div>
                                            <div class="mb-3">
                                                <label for="lokasi_kegiatan" class="form-label">Lokasi Kegiatan</label>
                                                <input type="text" class="form-control lokasi" id="lokasi_kegiatan" placeholder="Lokasi Kegiatan">
                                            </div>
                                            <div class="mb-3">
                                                <label for="attachment" class="form-label">Attachment</label>
                                                <input type="file" class="form-control" id="attachment">
                                            </div>
                                        </div>
                                 
                                    </div>  
                                </div>
                                    <div class="row">
                                        <div class="col-lg-10">
                                            <hr>
                                        </div>
                                        <div class="col-lg-2">
                                        <button type="button" class="btn btn-success" id="tambahkegiatan">Tambah Kegiatan</button>
                                      </div>
                                    </div>
                              

                                <ul class="pager wizard twitter-bs-wizard-pager-link">
                                    <li class="previous"><a href="javascript: void(0);" class="btn btn-primary"><i
                                                class="bx bx-chevron-left me-1"></i> Previous</a></li>
                                    <li class="next"><a href="javascript: void(0);" class="btn btn-primary">Next <i
                                                class="bx bx-chevron-right ms-1"></i></a></li>
                                </ul>
                              </div>
                            </div>
                            <div class="tab-pane" id="detail">
                                <div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mb-0">
                                            <label for="detail">Detail</label>
                                            
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered" id="tabeldetail">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:27%">Nama Item</th>
                                                            <th style="width:7%">Satuan</th>
                                                            <th style="width:7%">Quantity</th>
                                                            <th style="width:12%">Harga Satuan</th>
                                                            <th style="width:13%">Total</th>
                                                            <th style="width:28%">Catatan</th>
                                                            <th style="width:6%">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="text" class="form-control nama_item" name="nama_item[]"></td>
                                                            <td><input type="text" class="form-control satuan" name="satuan[]"></td>
                                                            <td><input type="number" class="form-control quantity" name="quantity[]"></td>
                                                            <td><input type="number" class="form-control harga_satuan" name="harga_satuan[]"></td>
                                                            <td><input type="text" class="form-control total" name="total[]" readonly></td>
                                                            <td><input type="text" class="form-control catatan" name="catatan[]"></td>
                                                            <td><button type="button" class="btn btn-danger hapusitem">Hapus</button></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <button type="button" class="btn btn-success" id="tambahitem">+ Item</button>
                                              </div>

                                        </div>


                                    </div>
                                 
                                 
                                
                                  <ul class="pager wizard twitter-bs-wizard-pager-link">
                                      <li class="previous"><a href="javascript: void(0);" class="btn btn-primary"><i
                                                  class="bx bx-chevron-left me-1"></i> Previous</a></li>
                                      <li class="next"><a href="javascript: void(0);" class="btn btn-primary">Next <i
                                                  class="bx bx-chevron-right ms-1"></i></a></li>
                                  </ul>
                                </div>
                              </div>
                              <div class="tab-pane" id="beban">
                                <div>
                                
                                      <div class="row">
                                          <div class="col-lg-6">
                                              <div class="mb-3">
                                                  <label for="beban_ahm" class="form-label">Beban AHM</label>
                                                  <input type="number" class="form-control" id="beban_ahm" placeholder="Nominal Beban AHM">
                                              </div>
                                          </div>
  
                                          <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="notes_beban_ahm" class="form-label">Notes Beban AHM</label>
                                                <input type="text" class="form-control" id="notes_beban_ahm" placeholder="Notes Beban AHM">
                                            </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="beban_dealer" class="form-label">Beban Dealer</label>
                                                <input type="number" class="form-control" id="beban_dealer" placeholder="Nominal Beban Dealer">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                          <div class="mb-3">
                                              <label for="notes_beban_dealer" class="form-label">Notes Beban Dealer</label>
                                              <input type="text" class="form-control" id="notes_beban_dealer" placeholder="Notes Beban Dealer">
                                          </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="beban_md" class="form-label">Beban MD</label>
                                                <input type="number" class="form-control" id="beban_md" placeholder="Nominal Beban MD">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                          <div class="mb-3">
                                              <label for="notes_beban_md" class="form-label">Notes Beban MD</label>
                                              <input type="text" class="form-control" id="notes_beban_md" placeholder="Notes Beban MD">
                                          </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="total_biaya" class="form-label">Total Biaya</label>
                                                <input type="number" class="form-control" id="total_biaya" readonly>
                                            </div>
                                        </div>

                        
                                    </div>
                                 
                                  <ul class="pager wizard twitter-bs-wizard-pager-link">
                                      <li class="previous"><a href="javascript: void(0);" class="btn btn-primary"><i
                                                  class="bx bx-chevron-left me-1"></i> Previous</a></li>
                                      <li class="next"><a href="javascript: void(0);" class="btn btn-primary">Next <i
                                                  class="bx bx-chevron-right ms-1"></i></a></li>
                                  </ul>
                                </div>
                              </div>
                              <div class="tab-pane" id="approval">
                                <div>
                                      <div class="row">

                                        <div class="col-lg-6">
                                        <div class="mb-3">
                                        <label for="peserta">Peserta</label>
                                        <div id="editor3" ></div>
                                        </div>
                                        </div>

                                          <div class="col-lg-6">
                                              <div class="mb-3">
                                                  <label class="form-label"><u>Pilih Approval</u></label>
                                                <br>
                                                  <label for="pilih_kabag" class="form-label">Kabag</label>
                                                  <select class="form-control" id="pilih_kabag" name="pilih_kabag">
                                                    <option value="">Pilih Kabag</option>
                                                </select>
                                             </div>

                                             <div class="mb-3">
                                                <label for="ga_approval" class="form-label">General Affair</label>
                                                <input type="text" class="form-control ga_approval" id="ga_approval" name="ga_approval" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label for="gm_approval" class="form-label">General Manager</label>
                                                <input type="text" class="form-control gm_approval" id="gm_approval" name="gm_approval" readonly>
                                            </div>

                                          </div>
  
                                      </div>
                              
                                  <ul class="pager wizard twitter-bs-wizard-pager-link">
                                      <li class="previous"><a href="javascript: void(0);" class="btn btn-primary"><i
                                                  class="bx bx-chevron-left me-1"></i> Previous</a></li>
                                      <li class="next" onclick="reviewdraft()"><a href="javascript: void(0);" class="btn btn-primary">Next <i
                                                  class="bx bx-chevron-right ms-1"></i></a></li>
                                  </ul>
                                </div>
                              </div>

                            <div class="tab-pane" id="konfirmasi">
                                <div>
                                    <div class="text-center mb-4">
                                        {{-- <h5>Reviews Draft</h5> --}}
                                    </div>
                                 
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Reviews Draft</h4>
                                                    {{-- <p class="card-title-desc">Click <code>.Submit</code> J <code>background-color</code>, some borders, and some rounded corners to render accordions edge-to-edge with their parent container.</p> --}}
                                                </div> <!-- end card header -->
                                                
                                                <div class="card-body">
                                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="flush-headingOne">
                                                                <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse"
                                                                    data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne">
                                                                    Pendahuluan
                                                                </button>
                                                            </h2>
                                                            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                                                                data-bs-parent="#accordionFlushExample">
                                                                <div class="accordion-body text-muted" id="pendahuluanreview">
                                                                
                                                                      <div class="row">
                                                                        <div class="col-12">
                                                                          <h6>Judul Proposal:</h6>
                                                                          <p style="text-align:justify" id="reviewjudul"></p>
                                                                        </div>
                                                                      </div>

                                                                      <div class="row">
                                                                        <div class="col-12">
                                                                          <h6>No. Proposal:</h6>
                                                                          <p style="text-align:justify" id="reviewnomor"></p>
                                                                        </div>
                                                                      </div>
                                                                   
                                                                      <div class="row">
                                                                        <div class="col-12">
                                                                          <h6>Tanggal Buat:</h6>
                                                                          <p style="text-align:justify" id="reviewtanggal"></p>
                                                                        </div>
                                                                      </div>

                                                                      <div class="row">
                                                                        <div class="col-12">
                                                                          <h6>Latar Belakang:</h6>
                                                                          <p id="reviewlatar"></p>
                                                                        </div>
                                                                      </div>

                                                                      <div class="row">
                                                                        <div class="col-12">
                                                                          <h6>Tujuan:</h6>
                                                                          <p id="reviewtujuan"></p>
                                                                        </div>
                                                                      </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="flush-headingTwo">
                                                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse"
                                                                    data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                                                   Kegiatan
                                                                </button>
                                                            </h2>
                                                            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo"
                                                                data-bs-parent="#accordionFlushExample">
                                                                <div class="accordion-body text-muted" id="reviewkegiatan">
                                                                        {{-- data kegiatan  --}}
                                                                </div>
                                                        </div>
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="flush-headingThree">
                                                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse"
                                                                    data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                                                    Detail
                                                                </button>
                                                            </h2>
                                                            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree"
                                                                data-bs-parent="#accordionFlushExample">
                                                                <div class="accordion-body text-muted" id="detaildatareview">
                                                                    {{-- data detail --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="flush-headingThree">
                                                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse"
                                                                    data-bs-target="#flush-collapsefour" aria-expanded="false" aria-controls="flush-collapseThree">
                                                                    Beban
                                                                </button>
                                                            </h2>
                                                            <div id="flush-collapsefour" class="accordion-collapse collapse" aria-labelledby="flush-headingThree"
                                                                data-bs-parent="#accordionFlushExample">
                                                                <div class="accordion-body text-muted" id="beban">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                          <h6>Beban AHM:</h6>
                                                                          <p style="text-align:justify" id="reviewbebanahm"></p>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <h6>Notes Beban AHM:</h6>
                                                                            <p style="text-align:justify" id="reviewnotesbebanahm"></p>
                                                                          </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                          <h6>Beban Dealer:</h6>
                                                                          <p style="text-align:justify" id="reviewbebandealer"></p>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <h6>Notes Beban dealer:</h6>
                                                                            <p style="text-align:justify" id="reviewnotesbebandealer"></p>
                                                                          </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                          <h6>Beban MD:</h6>
                                                                          <p style="text-align:justify" id="reviewbebanmd"></p>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <h6>Notes Beban MD:</h6>
                                                                            <p style="text-align:justify" id="reviewnotesbebanmd"></p>
                                                                          </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                          <h6>Total Biaya:</h6>
                                                                          <p style="text-align:justify" id="reviewtotalbiaya"></p>
                                                                        </div>
                                
                                                                    </div>


                                                                     

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="flush-headingThree">
                                                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse"
                                                                    data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-collapseThree">
                                                                    Approval
                                                                </button>
                                                            </h2>
                                                            <div id="flush-collapsefive" class="accordion-collapse collapse" aria-labelledby="flush-headingThree"
                                                                data-bs-parent="#accordionFlushExample">
                                                                <div class="accordion-body text-muted" id="approval">
                                                                    {{-- data approval --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- end accordion -->
                                                </div><!-- end card-body -->
                                            </div><!-- end card -->
                                        </div><!-- end col -->
                                    </div><!--  end row -->
                          
                                 
                                  <ul class="pager wizard twitter-bs-wizard-pager-link">
                                    <li class="previous"><a href="javascript: void(0);" class="btn btn-primary"><i
                                                class="bx bx-chevron-left me-1"></i> Previous</a></li>
                                    <li class="float-end">
                                        <a href="javascript: void(0);" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target=".confirmModal">Save
                                            Draft</a>
                                    </li>
                                        </form>
                                </ul>
                                </div>
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
<script src="{{asset('assets/ckeditor/ckbox.js')}}"></script>
<script src="{{asset('assets/js/kendoui_custom.min.js')}}"></script>
<script src="{{asset('assets/js/pages/kendoui.min.js')}}"></script>
{{-- <script src="https://cdn.ckbox.io/ckbox/2.4.0/ckbox.js"></script> --}}
<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.0/",
            "ckeditor5-premium-features": "https://cdn.ckeditor.com/ckeditor5-premium-features/42.0.0/ckeditor5-premium-features.js",
            "ckeditor5-premium-features/": "https://cdn.ckeditor.com/ckeditor5-premium-features/42.0.0/"
        }
    }
</script>
<script type="module">
import {
        ClassicEditor,Bold,Italic,Underline,BlockQuote,CloudServices,Essentials,Heading,Indent,IndentBlock,Link,List,Paragraph,SourceEditing,TextTransformation,HtmlEmbed,CodeBlock,RemoveFormat,Code,HorizontalLine,TodoList,Highlight,Alignment
    } 
from 'ckeditor5';

    function ambilEditor(id,index) {
        ClassicEditor.create(document.querySelector(id), {
            plugins: [BlockQuote,Bold,CloudServices,Essentials,Heading,Indent,IndentBlock,Italic,Link,List,Paragraph,SourceEditing,TextTransformation,Underline,HtmlEmbed,CodeBlock,
                RemoveFormat,Code,HorizontalLine,TodoList,Highlight,Alignment,
            ],
            toolbar: {
                items: [
                    'undo', 'redo','|','bold', 'italic', 'underline','highlight', 'blockQuote',
                    'alignment','|','bulletedList', 'numberedList', 'todoList','outdent', 'indent','heading'
                ],
                shouldNotGroupWhenFull: true
            },
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },{ model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },{ model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },{ model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },{ model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },{ model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },{ model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                ]
            },
            link: {
                addTargetToExternalLinks: true,
                defaultProtocol: 'https://'
            }
        })
        .then((editor) => {
            dataEditor[index] = editor; 
        })
        .catch((error) => {
            console.error(error.stack);
        });
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        ambilEditor('#editor', 'editor1');
        ambilEditor('#editor2', 'editor2');
        ambilEditor('#editor3', 'editor3');
        ambilEditor('#editor4', 'editor4');
    });

    $('#tambahkegiatan').click(function() {
        var editorIndex = Object.keys(dataEditor).length + 1;
        var kegiatan = `
        <div class="col-lg-12">
            <hr>
        </div>
        <div class="row mb-3 kegiatann">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="bentuk_kegiatan">Bentuk Kegiatan</label>
                    <div class="editorbaru"  id="editor${editorIndex}"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai Kegiatan</label>
                    <input type="text" class="form-control tanggal_mulai" placeholder="Tanggal Mulai Kegiatan">
                </div>
                <div class="mb-3">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir Kegiatan</label>
                    <input type="text" class="form-control tanggal_akhir" placeholder="Tanggal Akhir Kegiatan">
                </div>
                <div class="mb-3">
                    <label for="lokasi_kegiatan" class="form-label">Lokasi Kegiatan</label>
                    <input type="text" class="form-control lokasi" placeholder="Lokasi Kegiatan">
                </div>
                <div class="mb-3">
                    <label for="attachment" class="form-label">Attachment</label>
                    <input type="file" class="form-control">
                </div>
            </div>
        </div>`;

        $('#listkegiatan').append(kegiatan);
        ambilEditor(`#editor${editorIndex}`, `editor${editorIndex}`);
        const tanggal_awal = flatpickr(".tanggal_mulai",{});
        const tanggal_akhir = flatpickr(".tanggal_akhir",{});
    });

</script>
<script>
var dataEditor = [];
const tanggal_buat = flatpickr("#tanggal_buat",{});
const tanggal_awal_kegiatan = flatpickr(".tanggal_mulai",{});
const tanggal_akhir_kegiatan = flatpickr(".tanggal_akhir",{});

$(document).ready(function(){
    loadData()
    loadNamaKabag()
    // $('#ga_approval').val('ga')
    // $('#gm_approval').val('gm')
})

function loadData()
{
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
            ajax: "{{url('/proposal/get')}}",
            columns: [{
                    "searchable": false,
                    "targets": 0,
                    "data": null,
                    "width": "50px",
                    "sClass": "text-center",
                    "orderable": false
                },
                {
                    data: 'tanggal_buat',
                    name: 'tanggal_buat'
                },
                {
                    data: 'no_proposal',
                    name: 'no_proposal'
                },
                {
                    data: 'judul_proposal',
                    name: 'judul_proposal',
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
function loadNamaKabag()
{
    $.ajax({
        url:"{{url('/proposal/kabag')}}",
            type: 'GET',
            success: function(data) {
                let kabag=[];
                // let ga="";
                // let gm="";

                data.forEach(item => {
                    if(item.role==='Kabag'){
                        kabag.push({
                            kd_karyawan:item.kd_karyawan,
                            nama_lengkap:item.nama_lengkap
                        });
                    }else if(item.role==='GA'){
                        ga=item.nama_lengkap;
                    }else if(item.role==='GM'){
                        gm=item.nama_lengkap;
                    }
                });

                $('#pilih_kabag').kendoMultiSelect({
                    dataSource: kabag,
                    dataTextField: "nama_lengkap",
                    dataValueField: "kd_karyawan"
                });
                $('#ga_approval').val(ga)
                $('#gm_approval').val(gm)        
            }
        });
}

function tambahDraft()
{
    // $("#proposal")[0].reset();
    // $('#ga_approval').val(ga)
    // $('#gm_approval').val(gm)  
}

function hitungtotal() {
        $('#tabeldetail tbody tr').each(function() {
            var qty = $(this).find('input[name="quantity[]"]').val();
            var satuan = $(this).find('input[name="harga_satuan[]"]').val();
            var total = qty * satuan;
            $(this).find('input[name="total[]"]').val(rupiah(total));
        });
    }

function hitungtotalbeban(){
            var bebanahm = $('input[id="beban_ahm"]').val();
            var bebandealer = $('input[id="beban_dealer"]').val();
            var bebanmd = $('input[id="beban_md"]').val();
            var total = bebanahm * bebandealer * bebanmd;
            $('input[id="total_biaya"]').val(total);
}

    $('#tambahitem').click(function() {
        var newRow = `
        <tr>
            <td><input type="text" class="form-control nama_item" name="nama_item[]"></td>
            <td><input type="text" class="form-control satuan" name="satuan[]"></td>
            <td><input type="number" class="form-control quantity" name="quantity[]"></td>
            <td><input type="number" class="form-control harga_satuan" name="harga_satuan[]"></td>
            <td><input type="text" class="form-control total" name="total[]" readonly></td>
            <td><input type="text" class="form-control catatan" name="catatan[]"></td>
            <td><button type="button" class="btn btn-danger hapusitem">Hapus</button></td>
        </tr>`;
        
        $('#tabeldetail tbody').append(newRow);
    });
   
    $(document).on('click', '.hapusitem', function() {
        $(this).closest('tr').remove();
    });

    $(document).on('input', 'input[name="quantity[]"], input[name="harga_satuan[]"]', function() {
        hitungtotal();
    });

    $(document).on('input', 'input[id="beban_md"]', function() {
        hitungtotalbeban();
    });

    const rupiah = (number)=>{
    return new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR"
    }).format(number);
  }

function reviewdraft()
{   
    let proposalData = {};
    latarbelakang = dataEditor['editor1'].getData();
    tujuan = dataEditor['editor2'].getData();
    peserta = dataEditor['editor3'].getData();

    proposalData.pendahuluan = {
            judul: $('#judulproposal').val(),
            tanggal: $('#tanggal_buat').val(),
            no_proposal: $('#no_proposal').val(),
            latar:latarbelakang,
            tujuann:tujuan  
    };

    let kegiatanData = [];
    $('#listkegiatan .kegiatann').each(function(index) {
        kegiatanData.push({
            bentuk_kegiatan: dataEditor[`editor${index + 4}`].getData(),
            tanggal_mulai: $(this).find('.tanggal_mulai').val(),
            tanggal_akhir: $(this).find('.tanggal_akhir').val(),
            lokasi: $(this).find('.lokasi').val(),
            attachment: $(this).find('.attachment').val()
        });
        index++;
    });
    proposalData.kegiatan=kegiatanData;

    let datadetail=[];
    $('#tabeldetail tbody tr').each(function(){
        datadetail.push({
            namaitem: $(this).find('.nama_item').val(),
            satuan: $(this).find('.satuan').val(),
            quantity: $(this).find('.quantity').val(),
            hargaSatuan: $(this).find('.harga_satuan').val(),
            total: $(this).find('.total').val(),
            catatan: $(this).find('.catatan').val()
        });
    });

    proposalData.detail = datadetail;

    proposalData.beban = {
            beban_ahm:$('#beban_ahm').val(),
            notes_beban_ahm:$('#notes_beban_ahm').val(),
            beban_dealer:$('#beban_dealer').val(),
            notes_beban_dealer:$('#beban_md').val(),
            beban_md:$('#beban_md').val(),
            notes_beban_md:$('#notes_beban_md').val(),
            total_biaya:$('#total_biaya').val()
    };


    $('#reviewjudul').html(proposalData.pendahuluan.judul);
    $('#reviewnomor').html(proposalData.pendahuluan.no_proposal);
    $('#reviewtanggal').html(proposalData.pendahuluan.tanggal);
    $('#reviewlatar').html(proposalData.pendahuluan.latar);
    $('#reviewtujuan').html(proposalData.pendahuluan.tujuann);

    $('#reviewbebanahm').html(proposalData.beban.beban_ahm);
    $('#reviewnotesbebanahm').html(proposalData.beban.notes_beban_ahm);
    $('#reviewbebandealer').html(proposalData.beban.beban_dealer);
    $('#reviewnotesbebandealer').html(proposalData.beban.notes_beban_dealer);
    $('#reviewbebanmd').html(proposalData.beban.beban_md);
    $('#reviewnotesbebanmd').html(proposalData.beban.notes_beban_md);
    $('#reviewtotalbiaya').html(proposalData.beban.total_biaya);
                                                                                                                             
    var kegiatanHtml = '';
    proposalData.kegiatan.forEach(function(item, index) {
        kegiatanHtml += `
        <div>
            <div class="row"><div class="col-12"><h6>Bentuk Kegiatan${index + 1}:</h6>${item.bentuk_kegiatan}</div></div>
            <div class="row"><div class="col-12"><h6>Tanggal Mulai:</h6><p>${item.tanggal_mulai}</p></div></div>
            <div class="row"><div class="col-12"><h6>Tanggal Akhir:</h6><p>${item.tanggal_akhir}</p></div></div>
            <div class="row"><div class="col-12"><h6>Lokasi:</h6><p>${item.lokasi}</p></div></div>
        </div><br>`;
    });

    $('#reviewkegiatan').html(kegiatanHtml);
    

    let reviewDetaildata = `<div class="table-responsive"><table class="table table-striped table-bordered"><thead><tr><th>Nama Item</th><th>Satuan</th><th>Quantity</th><th>Harga Satuan</th><th>Total</th><th>Catatan</th></tr></thead><tbody>`;
        proposalData.detail.forEach(function(itemdetail) {
            reviewDetaildata += `
                <tr>
                    <td>${itemdetail.namaitem}</td>
                    <td>${itemdetail.satuan}</td>
                    <td>${itemdetail.quantity}</td>
                    <td>${itemdetail.hargaSatuan}</td>
                    <td>${itemdetail.total}</td>
                    <td>${itemdetail.catatan}</td>
                </tr>
            `;
        });
        reviewDetaildata += '</tbody></table></div>';
        $('#detaildatareview').html(reviewDetaildata); 

        // console.log(JSON.stringify($('#pilih_kabag').data('kendoMultiSelect').dataSource.data().nama_lengkap));
        // console.log(JSON.stringify($('#pilih_kabag').data('kendoMultiSelect').dataSource._data.nama_lengkap));

        var approval = $("#pilih_kabag").data("kendoMultiSelect");
        // console.log(approval);
        var kabag= [];
        var datapilih = approval.value();
        for (var i=0;i<datapilih.length;i++)
        {
        kabag.push(datapilih[i]);
        }
        // console.log(kabag);
}

</script>

@endpush



