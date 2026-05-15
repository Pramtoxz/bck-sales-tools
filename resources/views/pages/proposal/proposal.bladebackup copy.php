@extends('layout.default')
@section('css')

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" />
@endsection
@section('content')

    <style type="text/css">

   .tabtab{
    background-color: #f3f3f3;
    border-radius: 5px;
   }
    
.table-responsive {
    overflow-x: auto;
    
}

table.table-bordered th,
table.table-bordered td {
    border: 1px solid #dee2e6;
    white-space: nowrap;
    font-size: 12px;
    
}

.fixed-col {
    position: sticky;
    left: 0;
    background-color: #f7f7f7;
    z-index: 1;
    vertical-align: middle  !important;
    text-align: left;  
}

.text-center{
    text-align: center;
    
}
.hijau{
    background : #8cc14c !important;
    color: white;
}
#spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 1000;
        }

    /* end css tabel monitoring */
        .fab {
            position: relative;
        }

        /* 
                                td,
                                tr, 
                                */
        th {
            text-transform: uppercase;
        }

        .icon-fab {
            position: fixed;
            bottom: 10px;
            right: 10px;

        }

        td.jumlah,
        td.desc {
            text-align: center;
        }

        .td {
            font-weight: bold;
        }



        /* The Modal (background) */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1500;
            /* Sit on top */
            padding-top: 10px;
            /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            border: 1px solid #888;
            width: 50%;
            height: 600px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            -webkit-animation-name: animatetop;
            -webkit-animation-duration: 0.4s;
            animation-name: animatetop;
            animation-duration: 0.4s
        }

        /* Add Animation */
        @-webkit-keyframes animatetop {
            from {
                top: -300px;
                opacity: 0
            }

            to {
                top: 0;
                opacity: 1
            }
        }

        @keyframes animatetop {
            from {
                top: -300px;
                opacity: 0
            }

            to {
                top: 0;
                opacity: 1
            }
        }

        /* The Close Button */
        .close {
            color: black;
            float: right;
            font-size: 28px;
            font-weight: bold;


        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;

        }

        .modal-header {
            padding: 2px 16px;
            color: #fff;
            background-color: #fff;
        }

        .modal-body {
            padding: 2px 16px;
            height: 521px;
            border: 1px;
            overflow-x: hidden;
            overflow-y: scroll;
        }

        .modal-footer {
            padding: 2px 16px;
            color: #fff;
            background-color: #da314b;
            fixed: bottom;
        }

        .ok {

            margin-top: 1px;

        }

        .ass {
            margin-top: 0px;
            width: 100%;
            height: 120%;
            margin-bottom: 0px;
            /*padding-top:0px;*/
            /*top:0px;*/
        }

        .switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 22px;
  margin: none;
  padding:none;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
  /*margin:0px;*/
}

.slider:before {
  position: absolute;
  content: "";
  height: 15px;
  width: 15px;
  left: 4px;
  bottom: 4px;
  
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

    </style>
    <style>
        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner:before {
            content: '';
            box-sizing: border-box;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100px;
            height: 100px;
            margin-top: -20px;
            margin-left: -30px;
            border-radius: 100%;
            border: 10px solid red;
            border-top-color: #333;
            animation: spinner .6s linear infinite;
            /* background: #333;   */
        }
        .thvertical1 {
        vertical-align : middle !important; 
        text-align:center !important;
        background : #8cc14c !important;
        font-size:10px !important;
        color : rgb(251, 251, 251) !important;
        font-weight: bold !important;
    }
    .thvertical {
        vertical-align : middle !important; 
        text-align:center !important;
        font-size:11px !important;
    }

        
    </style>

    <style>
        #loading {
            display: none;
            border: 4px solid #f3f3f3; /* Warna latar belakang lingkaran loading */
            border-top: 4px solid #3498db; /* Warna latar belakang lingkaran loading */
            border-radius: 50%; /* Membuat lingkaran */
            width: 24px; /* Lebar lingkaran */
            height: 24px; /* Tinggi lingkaran */
            animation: spin 1s linear infinite; /* Animasi putaran */
            position: absolute; /* Menempatkan di posisi absolut */
            top: 50%; /* Posisi relatif terhadap top */
            left: 50%; /* Posisi relatif terhadap kiri */
            margin-top: -12px; /* Mengatur margin top untuk memusatkan vertikal */
            margin-left: -12px; /* Mengatur margin left untuk memusatkan horizontal */
        }

        @keyframes spin {
            0% { transform: rotate(0deg); } /* Putaran awal */
            100% { transform: rotate(360deg); } /* Putaran akhir */
        }
    </style>
    <div id="page_content">
        <div id="page_content_inner">
            @if(Auth::user()->flg_md_d == 'MD')
            <h3 class="heading_b uk-margin-bottom">MD > H1 > Monitoring Test Ride </h3>
            @endif
            @if(Auth::user()->flg_md_d == 'D')
            <h3 class="heading_b uk-margin-bottom">H1 > Update Test Ride </h3>
            @endif
            {{-- <div class="loader"></div> --}}
            <div id="exTab1" class="uk-width-1-1">
                @if(Auth::user()->flg_md_d == 'MD')
                <ul class="nav nav-pills">
                    <li class="tabtab active">
                        <a href="#1a" data-toggle="tab">Monitoring Test Ride</a>
                    </li>
                    <li class="tabtab">
                        <a href="#2a" data-toggle="tab">Raw Monitoring Test Ride</a>
                    </li>
                    <li class="tabtab">
                        <a href="#4a" data-toggle="tab">Data Motor Riding Test</a>
                    </li>
                    {{-- <li class="tabtab">
                        <a href="#3a" data-toggle="tab">Data Series Motor</a>
                    </li> --}}
                </ul>
                <div class="tab-content clearfix" style="border:1px solid #e0e0e0; border-radius:5px;margin-top:5px;">
                    <div class="tab-pane active" id="1a">

                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <div class="md-card uk-margin-medium-bottom">
                                    <div class="md-card-content">
                                           
                                        <button class="uk-button uk-button-success md-btn-wave-light" onclick="toggleFilter3()" id="buttonFilter3"><b>Tampil Filter</b></button>

                                        <div id="formFilterDeal" style="display: none;">
                                        <div class="uk-grid" >
                                            <div class="uk-width-1-4">
                                                <label for="i_tgl_awal">Tanggal Awal</label>
                                                <input class="i_tanggal" id="i_tgl_awal" name="i_tgl_awal" style="width:100%">
                                            </div>
                                            <div class="uk-width-1-4">
                                                <label for="i_tgl_akhir">Tanggal Akhir</label>
                                                <input class="i_tanggal" id="i_tgl_akhir" name="i_tgl_akhir" style="width:100%">
                                            </div>
                                            <div class="uk-width-1-4">
                                                <label>Jenis Pembelian</label>
                                                <div class="md-input-wrapper">
                                                    <input id="jenis_pembelian" name="jenis_pembelian" class="jenis_pembelian" style="width: 100%" />
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            {{-- <div class="uk-width-1-4">
                                                <label>Status Active</label>
                                                <div class="md-input-wrapper">
                                                    <input id="status_active" name="status_active" class="status_active" style="width: 100%" />
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div> --}}
                                        </div>
                                            {{-- <div class="uk-width-1-4">
                                                <label for="filterrrrrrr" style="color: white">Filter</label>
                                                <a class="md-btn md-btn-success md-btn-block md-btn-wave-light" onClick="filtertanggal()">Filter Tanggal</a>
                                            </div>
                                            <div class="uk-width-1-4">
                                                <label for="filterrrrrrr" style="color: white">Filter</label>
                                                <a class="md-btn md-btn-primary md-btn-block md-btn-wave-light" onClick="export_excel()">Export Excel</a>
                                            </div> --}}
                                        <div class="uk-grid">
                                            <div class="uk-width-medium-1-6">
                                                <div class="uk-form-row">
                                                    <button id="filter" class="uk-button uk-button-primary md-btn-block md-btn-wave-light"  type="submit" onclick="filterData()">Lihat</button>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-6">
                                                <div class="uk-form-row">
                                                    <button id="filter" class="uk-button uk-button-success md-btn-block md-btn-wave-light"  type="button" onclick="export_excel()" >Export Excel</button>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-6">
                                                <div class="uk-form-row">
                                                    <button id="filter" class="uk-button uk-button-danger md-btn-block md-btn-wave-light"  type="button" onclick="toggleFilter3()">Tutup Filter</button>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                        <hr>
                                        <div id="spinner" style="display:none;">
                                            <img src="{{asset('assets/img/spinners/spinner_large.gif')}}" alt="Loading..." />
                                        </div>
                                        <div class="table-responsive">
                                            <table id="datatable2" class="table table-bordered nowrap w-100">
                                                <thead>
                                                    <tr id="header-row-1" class="hijau"></tr>
                                                    <tr id="header-row-2"  class="hijau"></tr>
                                                </thead>
                                                <tbody>
                                           
                                                </tbody>
                                            </table>
                                            <table border="0" style="font-size: 11px; width: 90%;">
                                                <b>Notes :</b>
                                                <tr>                                 
                                                    <td>1. Prospek</td>
                                                    <td>:</td>
                                                    <td>Akumulasi dari jumlah kegiatan riding test tiap tiap dealer yang di group berdasarkan nama series motor</td>                                 										
                                                </tr>
                                                <tr>                                 
                                                    <td>2. Deal</td>
                                                    <td>:</td>
                                                    <td>Jika Konsumen yang sudah mencoba tipe kendaraan saat riding test(prospek) membeli kendaraan yang sama saat riding test akan di akumulasikan sebagai deal.</td>
                                                                                                                           
                                                </tr> 
                                                <tr>                                 
                                                    <td>3. Other</td>
                                                    <td>:</td>
                                                    <td>Jika Konsumen yang sudah mencoba tipe kendaraan saat riding test(prospek) tidak membeli kendaraan yang sama saat riding test akan di akumulasikan sebagai other.</td> 	  									
                                                </tr>                                                                                                                           																			                                                                                                                                                                                                                             
                                            </table> 
                                        </div>
                                   
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>

                     <div class="tab-pane" id="2a">
                      
                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <div class="md-card uk-margin-medium-bottom">
                            <div class="md-card-content">
                                <button class="uk-button uk-button-success md-btn-wave-light" onclick="toggleFilter2()" id="buttonFilter2"><b>Tampil Filter</b></button>

                                <div id="formFilterraw" style="display: none;">
                                    <div class="uk-grid" >
                                        <div class="uk-width-3-4">
                                            <label>FK Dealer</label>
                                            <div class="md-input-wrapper">
                                                <input id="fk_dealer_raw" name="fk_dealer_raw[]" class="fk_dealer" style="width: 100%" />
                                                <span class="md-input-bar "></span>
                                            </div>
                                        </div>
                                    
                                    </div>
                                    <div class="uk-grid">
                                        <div class="uk-width-1-4">
                                            <label for="i_tgl_awal_raw">Tanggal Awal</label>
                                            <input class="i_tanggal" id="i_tgl_awal_raw" name="i_tgl_awal_raw" style="width:100%">
                                        </div>
                                        <div class="uk-width-1-4">
                                            <label for="i_tgl_akhir_raw">Tanggal Akhir</label>
                                            <input class="i_tanggal" id="i_tgl_akhir_raw" name="i_tgl_akhir_raw" style="width:100%">
                                        </div>
                                        <div class="uk-width-1-4">
                                            <label>Jenis Pembelian</label>
                                            <div class="md-input-wrapper">
                                                <input id="jenis_pembelian_raw" name="jenis_pembelian_raw" class="jenis_pembelian" style="width: 100%" />
                                                <span class="md-input-bar "></span>
                                            </div>
                                        </div>
                                        {{-- <div class="uk-width-1-4">
                                            <label>Status Active</label>
                                            <div class="md-input-wrapper">
                                                <input id="status_active_raw" name="status_active_raw" class="status_active" style="width: 100%" />
                                                <span class="md-input-bar "></span>
                                            </div>
                                        </div> --}}
                                    </div>
                                        {{-- <div class="uk-width-1-4">
                                            <label for="filterrrrrrr" style="color: white">Filter</label>
                                            <a class="md-btn md-btn-success md-btn-block md-btn-wave-light" onClick="filtertanggal()">Filter Tanggal</a>
                                        </div>
                                        <div class="uk-width-1-4">
                                            <label for="filterrrrrrr" style="color: white">Filter</label>
                                            <a class="md-btn md-btn-primary md-btn-block md-btn-wave-light" onClick="export_excel()">Export Excel</a>
                                        </div> --}}
                                    <div class="uk-grid">
                                        <div class="uk-width-medium-1-6">
                                            <div class="uk-form-row">
                                                <button id="filter" class="uk-button uk-button-primary md-btn-block md-btn-wave-light"  type="submit" onclick="filterDataRaw()">Lihat</button>
                                            </div>
                                        </div>
                                        <div class="uk-width-medium-1-6">
                                            <div class="uk-form-row">
                                                <button id="filter" class="uk-button uk-button-success md-btn-block md-btn-wave-light"  type="button" onclick="export_excelRaw()" >Export Excel</button>
                                            </div>
                                        </div>
                                        <div class="uk-width-medium-1-6">
                                            <div class="uk-form-row">
                                                <button id="filter" class="uk-button uk-button-danger md-btn-block md-btn-wave-light"  type="button" onclick="toggleFilter2()">Tutup Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    <hr>
                                    <div id="spinner" style="display:none;">
                                        <img src="{{asset('assets/img/spinners/spinner_large.gif')}}" alt="Loading..." />
                                    </div>
                       
                                <table class="table table-borderless" cellspacing="0" width="100%" id="table_master">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="thvertical1 fixed-col" style="border-top-left-radius:10px;">Kode Dealer</th>
                                            <th rowspan="2" class="thvertical1 fixed-col">Nama Dealer</th>
                                            <th rowspan="2" class="thvertical1">Tgl GuestBook</th>
                                            <th rowspan="2" class="thvertical1">Nama Konsumen</th>
                                            <th rowspan="2" class="thvertical1">No. Handphone</th>
                                            <th colspan="2" class="thvertical1">Prospek</th>
                                            <th colspan="2" class="thvertical1">SPK</th>
                                            <th colspan="2" class="thvertical1">SO</th>
                                            <th rowspan="2" class="thvertical1">No. Mesin</th>
                                            <th rowspan="2" class="thvertical1">No. Rangka</th>
                                            <th rowspan="2" class="thvertical1">Saran</th>
                                            <th rowspan="2" class="thvertical1" style="border-top-right-radius:10px;">Kesan</th>
                                        </tr>
                                        <tr>
                                            <th class="thvertical1">Tgl Prospek</th>
                                            <th class="thvertical1">Motor Prospek</th>
                                            <th class="thvertical1">Tgl SPK</th>
                                            <th class="thvertical1">Motor SPK</th>
                                            <th class="thvertical1">Tgl SO</th>
                                            <th class="thvertical1">Motor SO</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size:10px;text-align:center;font-weight:bold;">                 
                                    </tbody>
                                </table>
                                
                     
                            </div>
                        </div>
                    </div>
                </div>
             </div>

                    {{-- <div class="tab-pane" id="3a">
                      
                        <div class="md-card-content">
                            <div class="md-card-content">
                                <button onClick="addSeries()" class="md-fab md-fab-danger md-fab-wave-light waves-effect waves-button waves-light icon-fab" data-uk-modal="{target:'#addseriesmotortestride'}">
                                    <i class="material-icons">add_circle</i>
                                </button>
                                <table class="uk-table" cellspacing="0" width="100%" id="series_monitoring">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Series</th>
                                            <th>Jumlah Kendaraan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                                
                     
                            </div>
                        </div>
                    </div> --}}

                    <div class="tab-pane" id="4a">
                        @endif
                        <div class="md-card-content">
                            <div class="md-card-content">
                                @if(Auth::user()->flg_md_d == 'MD')
                                <button onClick="addForm()" class="md-fab md-fab-danger md-fab-wave-light waves-effect waves-button waves-light icon-fab" data-uk-modal="{target:'#addmotortestride'}">
                                    <i class="material-icons">add_circle</i>
                                </button>
                                @endif
                                <table class="uk-table" cellspacing="0" width="100%" id="table_monitoring">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>FK Dealer</th>
                                            <th>Nama Dealer </th>
                                            <th>Jumlah Unit Riding</th>
                                            <th>Aksi</th>

                                        </tr>
                                    </thead>
                                </table>
                                <table border="0" style="font-size: 11px; width: 90%;">
                                    <b>Fungsi Tool :</b>
                                    <tr>                                 
                                        <td>1. <a class="material-icons" style="color:green">remove_red_eye</a></td>
                                        <td>:</td>
                                        <td>Tool ini berfungsi untuk menampilkan detail data motor yang terdaftar untuk riding test.</td>                               										
                                    </tr>
                                    @if(Auth::user()->flg_md_d == 'MD')
                                    <tr>   
                                        <td>1. <a class="material-icons" style="color:#3f51b5">edit</a></td>
                                        <td>:</td>
                                        <td>Tool ini berfungsi untuk mengubah data motor yang terdaftar untuk riding test.</td>                                                                                                         
                                    </tr> 
                                    <tr>                                 
                                        <td>3. <a class="material-icons" style="background-color:red; color:white;">add_circle</a></td>
                                        <td>:</td>
                                        <td>Tool ini berfungsi untuk menambahkan data motor untuk riding test.</td> 	  									
                                    </tr> 
                                    @endif                                                                                                                          																			                                                                                                                                                                                                                             
                                </table>  
                     
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <div class="fab">
        <div id="addmotortestride" class="uk-modal">
            <div class="uk-modal-dialog uk-modal-dialog-medium">
                <button type="button" class="uk-modal-close uk-close"></button>
                <form id="tambahformriding" name="tambahformriding">
                    {!! csrf_field() !!}
                    <input type="hidden" class="form-control" id="tipe_submit" name="tipe_submit" required>
                    <input type="hidden" class="form-control" id="id_data" name="id_data">
                    <div class="uk-modal-header">
                        <h3 class="uk-modal-title" id="ket_submit">Tipe Motor Riding Test</h3>
                    </div>
                    <div class="uk-modal-content">

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <label for="tipe_kendaraan">Tipe Kendaraan </b></label>
                            </div>
                            <div class="uk-width-medium-1-1">
                                <select class="md-input tipe_kendaraan" name="tipe_kendaraan[]" id="tipe_kendaraan">
   
                                </select>
                            </div>
                        
                        </div>

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <label for="fk_dealer">Dealer </b></label>
                            </div>
                            <div class="uk-width-medium-1-1">
                                <select class="md-input fk_dealer" name="fk_dealer[]" id="fk_dealer">
                  
                                </select>
                            </div>
                        
                        </div>
                    
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <label for="active">Active </b></label>
                            </div>
                            <div class="uk-width-medium-1-1">
                                <select class="md-input" name="active" id="active">
                                    <option value="" disabled selected hidden>Pilih Status</option>
                                    <option data-id="t" value="t">Yes</option>
                                    <option data-id="f" value="f">No</option>
    
                                </select>
                            </div>
                        
                        </div>
                    </div>
                    <div class="uk-modal-footer uk-text-right">
                        <button class="md-btn md-btn-flat">RESET</button>
                        <button class="md-btn md-btn-flat md-btn-flat-danger " id="simpan">SUBMIT</button>
                    </div>
    
            </div>
            </form>
        </div>
    </div>

    {{-- <div class="fab">
        <div id="addseriesmotortestride" class="uk-modal">
            <div class="uk-modal-dialog uk-modal-dialog-medium">
                <button type="button" class="uk-modal-close uk-close"></button>
                <form id="tambahseriesformriding" name="tambahseriesformriding">
                    {!! csrf_field() !!}
                    <input type="hidden" class="form-control" id="seriestipe_submit" name="tipe_submit" required>
                    <input type="hidden" class="form-control" id="seriesid_data" name="id_data">
                    <div class="uk-modal-header">
                        <h3 class="uk-modal-title" id="series_ket_submit"></h3>
                    </div>
                    <div class="uk-modal-content">

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <label for="nama_series">Nama Series </b></label>
                            </div>
                            <div class="uk-width-medium-1-1"> 
                                    <div class="md-input-wrapper">
                                        <input class="md-input" type="text" name="nama_series" id="nama_series" required />
                                        <span class="md-input-bar "></span>
                                    </div>
                            </div>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <label for="tipe_kendaraan">Tipe Kendaraan </b></label>
                            </div>
                            <div class="uk-width-medium-1-1">
                                <select class="md-input tipe_kendaraan" name="tipe_kendaraan[]" id="tipe_kendaraanseries">
   
                                </select>
                            </div>
                        
                        </div>
      
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <label for="active">Active </b></label>
                            </div>
                            <div class="uk-width-medium-1-1">
                                <select class="md-input" name="active" id="activeseries">
                                    <option value="" disabled selected hidden>Pilih Status</option>
                                    <option data-id="t" value="t">Yes</option>
                                    <option data-id="f" value="f">No</option>
    
                                </select>
                            </div>
                        
                        </div>
                    </div>
                    <div class="uk-modal-footer uk-text-right">
                        <button class="md-btn md-btn-flat">RESET</button>
                        <button class="md-btn md-btn-flat md-btn-flat-danger " id="simpan">SUBMIT</button>
                    </div>
    
            </div>
            </form>
        </div>
    </div> --}}
    
    {{-- <div class="fab">
        <div id="detailseries" class="uk-modal">
            <div class="uk-modal-dialog uk-modal-dialog-large">
                <button type="button" class="uk-modal-close uk-close"></button>
                    <div class="uk-modal-header">
                        <h3 class="uk-modal-title" id="series_tipe_ket_submit"></h3>
                    </div>
                <table class="uk-table" cellspacing="0" width="100%" id="table_series">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Series</th>
                            <th>Tipe Kendaraan </th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>      
        </div>
    </div> --}}

    <div class="fab">
        <div id="detailtiperiding" class="uk-modal">
            <div class="uk-modal-dialog uk-modal-dialog-large">
                <button type="button" class="uk-modal-close uk-close"></button>
                    <div class="uk-modal-header">
                        <h3 class="uk-modal-title" id="riding_tipe_submit"></h3>
                    </div>
                <table class="uk-table" cellspacing="0" width="100%" id="table_riding">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>FK Dealer</th>
                            <th>Nama Dealer</th>
                            <th>Tipe Kendaraan</th>
                            <th>Nama Kendaraan</th>
                            <th>Status</th>
                            @if(Auth::user()->flg_md_d == 'MD')
                            <th>Aksi</th>
                            @else
                            <th>Keterangan</th>
                            @endif
                        </tr>
                    </thead>
                </table>
                <table border="0" style="font-size: 11px; width: 90%;">
                    <b>Fungsi Tool :</b>   
                    <tr>  
                        <td><label class="switch">
                            <input  type="checkbox" checked>
                            <span class="slider round" ></span>
                          </label>
                        </td>
                        <td>:</td>
                        <td>Tombol untuk merubah Status Aktif atau Tidak Aktif Tipe Kendaraan.</td>                                 										
                    </tr>  
                </table> 

            </div>
                
        </div>
    </div>

@endsection
@section('datatable')
    {{-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> --}}

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- datatables custom integration -->
    <script src="{{ asset('assets/js/custom/datatables/datatables.uikit.min.js') }}"></script>

    <!--  datatables functions -->
    <script src="{{ asset('assets/js/pages/plugins_datatables.min.js') }}"></script>
    <!-- kendo UI -->
    <script src="{{ asset('themplate/alteir2/assets/js/kendoui_custom.min.js') }}"></script>

    <!--  kendoui functions -->
    <script src="{{ asset('themplate/alteir2/assets/js/pages/kendoui.min.js') }}"></script>


@endsection
@push('script')
    <script>
         $('#i_tgl_awal').val('');
         $('#i_tgl_awal_raw').val('');
        $('#i_tgl_akhir').val('');
        $('#i_tgl_akhir_raw').val('');
        $('#jenis_pembelian').val('');
        $('#jenis_pembelian_raw').val('');
        $('#i_tgl_awal').val('');
        $('#i_tgl_akhir').val('');
        $('#jenis_pembelian').val('');
        $('#status_active').val('');
        $('#status_active_raw').val('');
        $(".i_tanggal").kendoDatePicker({
            format: "yyyy-MM-dd"
        });
        $(document).ready(function(){
            loadData();
            loadtipekendaraan();
            loadnamadealer();
            loaddatamonitoring();
            loaddatamonitoringraw();
            // loadDataSeries();
            jenis_pembelian();
            status_active();
            // fk_dealer_raw();

            $("form#tambahformriding").submit(function(e) 
            {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                // console.log(formData);
                $.ajax({
                    url:"{{url('/h1/monitoring/test-ride/postdata')}}",
                    type: 'POST',
                    data: formData,
                    async: true,
                    success: function(data) {
                        if (data.status == 'success') {
                            new PNotify({
                                text: data.message,
                                type: 'success',
                                hide: true,
                                delay: 3000
                            });
                        } else {
                            new PNotify({
                                text: data.message,
                                type: 'error',
                                hide: true,
                                delay: 5000
                            });
                        }
                        // alert(data);
                        var modal = UIkit.modal("#addmotortestride");
                        modal.hide();
                        var table = $('#table_monitoring').DataTable();
                        table.ajax.reload(function(json) {
                            $('#table_monitoring').val(json.lastInput);
                        });
                    },
                    error: function(data) {},
                    cache: false,
                    contentType: false,
                    processData: false
                });
               
            });

            $("form#tambahseriesformriding").submit(function(e) 
            {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                // console.log(formData);
                $.ajax({
                    url:"{{url('/h1/monitoring/test-ride/postdataseries')}}",
                    type: 'POST',
                    data: formData,
                    async: true,
                    success: function(data) {
                        if (data.status == 'success') {
                            new PNotify({
                                text: data.message,
                                type: 'success',
                                hide: true,
                                delay: 3000
                            });
                        } else {
                            new PNotify({
                                text: data.message,
                                type: 'error',
                                hide: true,
                                delay: 5000
                            });
                        }
                        // alert(data);
                        var modal = UIkit.modal("#addseriesmotortestride");
                        modal.hide();
                        var table = $('#series_monitoring').DataTable();
                        table.ajax.reload(function(json) {
                            $('#series_monitoring').val(json.lastInput);
                        });
                    },
                    error: function(data) {},
                    cache: false,
                    contentType: false,
                    processData: false
                });
               
            });


        });    

        function loadData()
    {
            $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
        {
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


        var fk_dealer = "{{ Auth::user()->fk_dealer }}";
        var flg_md_d = "{{ Auth::user()->flg_md_d }}";
        // console.log(fk_dealer);
        // console.log(flg_md_d);
        $('#table_monitoring').DataTable({ 
           processing  : true,
           serverSide  : true,
           bDestroy: true,
           ajax: {
            url:"{{url('/h1/monitoring/test-ride/getdata')}}",
            type:"GET",

           },
        
           columns: [
               {
                   "searchable": false, "targets": 0,
                   "data": null,
                   "width": "50px",
                   "sClass": "text-center",
                   "orderable": false
               },
               { data: 'fk_dealer', name:'fk_dealer'},
               { data: 'nm_alias_dealer_2',name:"nm_alias_dealer_2"},
               { data: 'jumlah_unit_riding',name:"jumlah_unit_riding"},
               { data: 'aksi', name:'aksi'}
           ],
      
           "rowCallback": function (row, data, iDisplayIndex) {
               var info = this.fnPagingInfo();
               var page = info.iPage;
               var length = info.iLength;
               var index = page * length + (iDisplayIndex + 1);
               $('td:eq(0)', row).html(index);
           }
       });
    }
        function addForm() 
        {
            $('#ket_submit').html("Add Tipe Motor Riding Test");
            $('#tipe_submit').val("add");
            $('#id_data').val("");
            $("form#tambahformriding")[0].reset();
        }

        function loadtipekendaraan() 
    {
        $.ajax({
            url:"{{url('/h1/monitoring/test-ride/gettipekendaraan')}}",
            type: 'GET',
            success: function(data) {
                $('.tipe_kendaraan').kendoMultiSelect({
                    dataSource: data,
                    dataTextField: "namamotor",
                    dataValueField: "kd_tipe_kendaraan"
                });
            }
        });


    }

    function  loadnamadealer()
    {
            $.ajax({
                url:"{{url('/h1/monitoring/test-ride/getdealer')}}",
            type: 'GET',
            success: function(data) {
                $('.fk_dealer').kendoMultiSelect({
                    dataSource: data,
                    dataTextField: "nm_alias_dealer_2",
                    dataValueField: "kd_dealer_ahm"
                });
            }
        });
    }

    function validasipilihsatu(id) 
    {
        var tipe_submit = $("#tipe_submit").val();

        if (tipe_submit === 'edit') {
        var pilihbanyak = $("#" + id).data("kendoMultiSelect");
        var datadipilih = pilihbanyak.value();

        if (datadipilih.length > 1) {
            alert("Hanya satu nilai yang diizinkan");
            pilihbanyak.value([datadipilih[0]]); 
        }
        }
    }

    function editData(id)
    {
        $('#ket_submit').html("Edit Tipe Motor Riding Test");
        $('#tipe_submit').val("edit");
        $('#id_data').val(id);  
        $("#tipe_kendaraan").data("kendoMultiSelect").value('');
        $("#fk_dealer").data("kendoMultiSelect").value(''); 
        $('#active').val('');
        $.ajax({
        url:"{{url('/h1/monitoring/test-ride/editdata')}}",
        type: 'POST',
        data: {
            id: id,
            _token: '{{ csrf_token() }}'
        },
        async: true,
        success: function(data) {
            var flg_md_d = "{{ Auth::user()->flg_md_d }}";
            if (flg_md_d == 'MD') {
                $("#tipe_kendaraan").data("kendoMultiSelect").value(data);
                $("#fk_dealer").data("kendoMultiSelect").value(id);
            }
            $('#active').val('t');
        },
        error: function(xhr, status, error) {
            console.log(error);
          
        },
        });
    }

    function editDataSeries(id)
    {
            $('#series_ket_submit').html("Update Series Motor Riding Test");
            $('#seriestipe_submit').val("edit");
            $('#seriesid_data').val(id);
            
            $.ajax({
            url:"{{url('/h1/monitoring/test-ride/editdataseries')}}",
            type: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            async: true,
            success: function(data) {
                console.log(data);
 
                $("#nama_series").val(id); 
                $("#tipe_kendaraanseries").data("kendoMultiSelect").value(data); 
                $("#activeseries").val('t');

            },
            error: function(xhr, status, error) {
                console.log(error); 
            },
            });       
    }

    function deleteData(id)
    {
                id = id;
                UIkit.modal.confirm('Hapus ' + id + '?', function() {
                    $.ajax({
                        url: "{{ url('/h1/monitoring/test-ride/hapusdata') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        async: false,
                        success: function(data) {
                            if (data.status == 'success') {
                                new PNotify({
                                    text: data.message,
                                    type: 'success',
                                    hide: true,
                                    delay: 3000
                                });

                                $('#table_riding').DataTable().ajax.reload();
                                var table = $('#table_riding').DataTable();
                                table.ajax.reload(function(json) {
                                $('#table_riding').val(json.lastInput);
                                });
                                $('#table_monitoring').DataTable().ajax.reload();

                            } else {
                                new PNotify({
                                    target: document.body,
                                    data: {
                                        text: data.message,
                                        type: 'error',
                                        hide: true,
                                        delay: 3000
                                    }
                                });
                            }
                        }

                    });
                }, {
                    labels: {
                        'Ok': 'Yes',
                        'Cancel': 'No'
                    }
                });
    }


function filterDataRaw(){
        var ambildealer = $("#fk_dealer_raw").data("kendoMultiSelect");
        var startDate = $('#i_tgl_awal_raw').val();
        var jenis_pembelian = $('#jenis_pembelian_raw').val()
        // console.log(jenis_pembelian);
        //var status_active = $('#status_active_raw').val()

        var dealer= [];
        var datapilih = ambildealer.value();
        for (var i=0;i<datapilih.length;i++)
        {
        dealer.push(datapilih[i]);
        }
        

        var endDate = $('#i_tgl_akhir_raw').val();
            if (startDate == null || startDate == '') {
                alert('Tanggal awal harus diisi');
            } else if(endDate == null || endDate == '') {
                alert('Tanggal akhir harus diisi');
            }else{
                loaddatamonitoringraw(dealer,startDate, endDate, jenis_pembelian);
            }
}

function export_excel(){
    var start_date=$('#i_tgl_awal').val();
    var end_date=$('#i_tgl_akhir').val();
    var jenis_pembelian=$('#jenis_pembelian').val();
    if (start_date == null || start_date == '') {
                alert('Tanggal awal harus diisi');
            } else if(end_date == null || end_date == '') {
                alert('Tanggal akhir harus diisi');
            }else {
                window.open("{{url('h1/monitoring/test-ride/export?awal=')}}"+start_date+'&akhir='+end_date+'&jenis='+jenis_pembelian,'_blank')
            }
}

function export_excelRaw(){
    var ambildealer = $("#fk_dealer_raw").data("kendoMultiSelect");
        var dealer= [];
        var datapilih = ambildealer.value();
        for (var i=0;i<datapilih.length;i++)
        {
        dealer.push(datapilih[i]);
        }

    var start_date=$('#i_tgl_awal_raw').val();
    var end_date=$('#i_tgl_akhir_raw').val();
    var jenis_pembelian=$('#jenis_pembelian_raw').val();
    if (start_date == null || start_date == '') {
                alert('Tanggal awal harus diisi');
            } else if(end_date == null || end_date == '') {
                alert('Tanggal akhir harus diisi');
            }else {
                window.open("{{url('h1/monitoring/test-ride/exportraw?awal=')}}"+start_date+'&akhir='+end_date+'&dealer='+dealer+'&jenis='+jenis_pembelian,'_blank')
            }
}

function detailDataRiding(fk_dealer){
    $('#fk_dealer').val(fk_dealer);
    $('#riding_tipe_submit').html('Data Kendaraan Riding Test FK-' + fk_dealer );
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

         
        $('#table_riding').DataTable({ 
           processing  : true,
           serverSide  : true,
           bDestroy: true,
           ajax: {
            url:"{{url('/h1/monitoring/test-ride/getdatadetail')}}"+`?fk_dealer=${fk_dealer}`,
            type:"GET",
           },
        
           columns: [
               {
                   "searchable": false, "targets": 0,
                   "data": null,
                   "width": "50px",
                   "sClass": "text-center",
                   "orderable": false
               },
               { data: 'fk_dealer', name:'fk_dealer'},
               { data: 'nm_alias_dealer_2', name:'nm_alias_dealer_2'},
               { data: 'kd_tipe_kendaraan',name:"kd_tipe_kendaraan"},
               { data: 'desc_tipe_cust',name:"desc_tipe_cust"},
               { data: 'statusactive', name:'statusactive'},
               { data: 'aksi', name:'aksi'}
           ],
           "order": [
                [1, 'asc']
            ],
           "rowCallback": function (row, data, iDisplayIndex) {
               var info = this.fnPagingInfo();
               var page = info.iPage;
               var length = info.iLength;
               var index = page * length + (iDisplayIndex + 1);
               $('td:eq(0)', row).html(index);
           }
       });
}
$('#table_riding').on( 'change', 'input[type=checkbox]', function () {
      
      var inii=$(this).hasClass("celek");
      
      if($(this).hasClass("celek"))
      {
          vDefault='';
          $(this).removeClass("celek");
          vDefault='f';
      }else{
          $(this).removeClass("celek");
          vDefault='';
          $(this).addClass("celek");
          vDefault='t';
      } 
      
      var kode=$(this).val();
          $.ajax({
              url:"{{url('/h1/monitoring/test-ride/setactiveriding')}}"+`?kode=${kode}&isDefault=${vDefault}`,
              type:"GET",
              success:function(data){
                  console.log(data);
                  new PNotify({
                              target: document.body,
                              text: data.message,
                              type:data.tipe,
                              hide: true,
                              delay: 2000
                          });
                  
                  $("#table_riding").DataTable().ajax.reload();
              
              }
          });
          stopPropagation(inii);
      
  } );

function hapusData(id)
{
    id = id;
                UIkit.modal.confirm('Hapus ' + id + '?', function() {
                    $.ajax({
                        url: "{{ url('/h1/monitoring/test-ride/hapusdatatipeseries') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        async: false,
                        success: function(data) {
                            if (data.status == 'success') {
                                new PNotify({
                                    text: data.message,
                                    type: 'success',
                                    hide: true,
                                    delay: 3000
                                });
                               
                                $('#table_series').DataTable().ajax.reload();  
                                var table = $('#table_series').DataTable();
                                table.ajax.reload(function(json) {
                                $('#table_series').val(json.lastInput);
                                });
                                $('#series_monitoring').DataTable().ajax.reload();
                                // var table2 = $('#table_series').DataTable();
                                // table2.ajax.reload(function(json) {
                                // $('#table_series').val(json.lastInput);
                                // });


                            } else {
                                new PNotify({
                                    target: document.body,
                                    data: {
                                        text: data.message,
                                        type: 'error',
                                        hide: true,
                                        delay: 3000
                                    }
                                });
                            }

                            // $('#table_monitoring').dataTable().ajax.reload();

                        }

                    });
                }, {
                    labels: {
                        'Ok': 'Yes',
                        'Cancel': 'No'
                    }
                });
}

function toggleFilter3(){
        $('#formFilterDeal').toggle()
        $('#buttonFilter3').toggle()
    }
function toggleFilter2(){
        $('#formFilterraw').toggle()
        $('#buttonFilter2').toggle()
    }
function jenis_pembelian(){
    $(".jenis_pembelian").kendoComboBox({
        filter: "contains",
        placeholder: "",
        dataTextField: "name",
        dataValueField: "id",
        dataSource: [
                {
                    name:'All',
                    id:''
                },
                {
                    name:'Individu',
                    id:'I'
                },
                {
                    name:'Group',
                    id:'G'
                }
            ]
        });
}
function status_active(){
    $(".status_active").kendoComboBox({
        filter: "contains",
        placeholder: "",
        dataTextField: "name",
        dataValueField: "id",
        dataSource: [
       
                {
                    name:'Active',
                    id:'t'
                },
                {
                    name:'Tidak Active',
                    id:'f'
                }
            ]
        });
}
// function fk_dealer_raw(){
//     $.ajax({
//         url:"{{url('/h1/monitoring/test-ride/getdealer')}}",
//         type: 'GET',
//         success: function(data) {
//         $('#fk_dealer_raw').kendoMultiSelect({
//             dataSource: data,
//             dataTextField: "nm_alias_dealer_2",
//             dataValueField: "kd_dealer_ahm"
//         });
//         }
//         });

// }
    </script>

@endpush
