@extends('layouts.app')
@push('css-custom')
<style>
.telat {
    color:rgb(248, 21, 21) !important;
}
#paginationArea {
    gap: 10px;
}
#pagination button, #pagination span {
    min-width: 35px;
}
@media (max-width: 576px) {
    #paginationArea {
            flex-direction: column;
            align-items: center;
    }
}

table.table-bordered th,
table.table-bordered td {
    border: 1px solid #dee2e6;
    white-space: nowrap;
    font-size: 12px;
    
}
.thvertical1 {
        vertical-align : middle !important; 
        /* text-align:center !important; */
        /* background : #8cc14c !important; */
        background : #189dfb !important;
        
        font-size:12px !important;
        color : rgb(251, 251, 251) !important;
        font-weight: bold !important;
    }




</style>
@endpush
@section('content')         
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Report Cuti</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Report Cuti</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>



     <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius:5px;">
               
                <div class="card-header">
                    @if (Auth::user()->is_admin == 't' || $flag_approval == 't')
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label" for="tanggal_awal">Tanggal Awal</label>
                            <input type="text" class="form-control" id="tanggal_awal" placeholder="Pilih tanggal awal" name="tanggal_awal" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="tanggal_akhir">Tanggal Akhir</label>
                            <input type="text" id="tanggal_akhir" class="form-control" placeholder="Pilih tanggal akhir" name="tanggal_akhir" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="kode_departemen">Kode Departement</label>
                            <select class="form-control" id="departement" name="departement">

                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Pilih Karyawan</label>
                            <select class="form-control" id="karyawan" name="karyawan">
                                <option value="">--First, Selection Department--</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button class="btn btn-success" id="tombol_filter"><i class="fas fa-search" style='color:aqua'></i> Tampil Data</button>
                        <button class="btn btn-info" type="button" onclick="downloadData()" id="export"><i class='fas fa-file-excel' style='color:rgb(191, 0, 255)'></i> Export Report</button>
                        <button class="btn btn-primary" type="button" onclick="downloadRaw()" id="exportraw2"><i class='fas fa-file-excel' style='color:yellow'></i> Export Detail</button>
                    </div>
                    @endif
                </div>
              

                
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">

                            <div class="mb-3">
                                <input type="text" id="input_cari" class="form-control" placeholder="Cari Nama / Jenis Cuti...">
                            </div>

                            <div class="table-responsive">
                                <table id="reportcuti" class="table table-bordered nowrap w-100">
                                    <thead id="thead-row">
                                        {{-- <tr>
                                            <th class="thvertical1">No</th>
                                            <th class="thvertical1">Nama Karyawan</th>
                                            <th class="thvertical1">Jenis Cuti</th>
                                            <th class="thvertical1">Tanggal Cuti</th>
                                        </tr> --}}
                                    </thead>
                                    <tbody style="font-size: 13px">
                                    </tbody>
                                </table>
                            </div>


                            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap" id="paginationArea">
                                <div id="pagination" class="my-2"></div>
                                <div class="input-group my-2" style="max-width: 150px;">
                                    <input type="number" id="gotoPage" class="form-control form-control-sm" placeholder="Halaman">
                                    <button class="btn btn-sm btn-success" onclick="goToInputPage()">Go</button>
                                </div>
                            </div>

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
    // const tanggal_awal = flatpickr("#tanggal_awal",{});
    // const tanggal_akhir = flatpickr("#tanggal_akhir",{});
    const today = new Date();

    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    document.getElementById("tanggal_awal").value = formatDate(firstDay);
    document.getElementById("tanggal_akhir").value = formatDate(lastDay);

    const tanggal_awal = flatpickr("#tanggal_awal", {
        dateFormat: "Y-m-d",
        defaultDate: firstDay
    });

    const tanggal_akhir = flatpickr("#tanggal_akhir", {
        dateFormat: "Y-m-d",
        defaultDate: lastDay
    });

    
    let allData=[];
    let filterAllData=[];
    let filterKaryawan = false;
    let currentPage = 1;
    const rowsPerPage=5;
    // tanggal_awal.clear()
    // tanggal_akhir.clear()
    $('#departement').val('');
    $('#karyawan').val('');

    $(document).ready(function() {
        getTahun()
        getBulan()
        loadData()
        getDepartement()


        $('#input_cari').on('keyup', function() {
        cariData();
        });

        $('#tombol_filter').on('click', function() {
            let tanggalAwal = $('#tanggal_awal').val();
            let tanggalAkhir = $('#tanggal_akhir').val();
        
            if(tanggalAwal && tanggalAkhir){
                loadData();
            }else{
                alert('Isikan Range Tanggal Jika Ingin Filter Data');
                return
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
    });

        function getDepartement() {
            $.ajax({
                type: "GET"
                , url: '{{route("ambilDepartement.all")}}'
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

        function renderTable(data) {
            let tbody = '';
            let no = (currentPage - 1) * rowsPerPage + 1;

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedData = Object.entries(data).slice(start, end);

            $('#thead-row').html(`
                <tr>
                <th class="thvertical1">No</th>
                <th class="thvertical1">Nama Karyawan</th>
                <th class="thvertical1">Jenis Cuti</th>
                <th class="thvertical1">List Tanggal Cuti</th>
                ${filterKaryawan ? '<th class="thvertical1">Alasan Cuti</th>' : ''}
                <tr>
            `);

            $.each(paginatedData, function(index, [nama, cutis]) {
                let rowspan = cutis.length;
                let first = true;
                let alasanList = '';
                let jenisCutiCount = {};
                // console.log(filterKaryawan);

                $.each(cutis, function(idx, cuti) {
                    tbody += '<tr>';
                    if (first) {
                        tbody += '<td rowspan="'+rowspan+'" style="vertical-align: middle;">'+ no +'</td>';
                        tbody += '<td rowspan="'+rowspan+'" style="vertical-align: middle;">'+ nama +'</td>';
                        first = false;
                    }
                    tbody += '<td>'+ cuti.jenis_cuti +'</td>';
                    tbody += '<td>'+ cuti.tanggal_cuti +'</td>';
                    if (filterKaryawan) {
                        tbody += '<td>'+ (cuti.alasan ?? '-') +'</td>';
                    }
                    tbody += '</tr>';

                });

                no++;
            });

            $('#reportcuti tbody').html(tbody);
            renderPagination(Object.keys(data).length);
        }

        function renderPagination(totalItems) {
            const totalPages = Math.ceil(totalItems / rowsPerPage);
            let paginationHTML = '';

            if (currentPage > 1) {
                paginationHTML += `<button class="btn btn-sm btn-outline-primary mx-1" onclick="goToPage(${currentPage - 1})">Previous</button>`;
            }

            for (let i = 1; i <= totalPages; i++) {
                if (i === currentPage) {
                    paginationHTML += `<button class="btn btn-sm btn-primary mx-1">${i}</button>`;
                } else if (i <= 2 || i > totalPages - 2 || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    paginationHTML += `<button class="btn btn-sm btn-outline-primary mx-1" onclick="goToPage(${i})">${i}</button>`;
                } else if (i === 3 && currentPage > 4) {
                    paginationHTML += `<span class="mx-1">...</span>`;
                } else if (i === totalPages - 2 && currentPage < totalPages - 3) {
                    paginationHTML += `<span class="mx-1">...</span>`;
                }
            }

            if (currentPage < totalPages) {
                paginationHTML += `<button class="btn btn-sm btn-outline-primary mx-1" onclick="goToPage(${currentPage + 1})">Next</button>`;
            }

            $('#pagination').html(paginationHTML);
        }


        function goToPage(page) {
            const totalPages = Math.ceil(Object.keys(filterAllData).length / rowsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderTable(filterAllData);
        }

        function goToInputPage() {
            const page = parseInt($('#gotoPage').val());
            if (!isNaN(page)) {
                goToPage(page);
            }
        }

        function loadData() {

            let tanggalAwal = $('#tanggal_awal').val();
            let tanggalAkhir = $('#tanggal_akhir').val();
            const ambilDepartement = $('#departement').val();
            const kd_karyawan = $('#karyawan').val();
            console.log(kd_karyawan);

            filterKaryawan = kd_karyawan ? true : false;
            console.log(filterKaryawan);

            $.ajax({
                url: "{{ route('cuti.reportGet') }}",
                method: "GET",
                data: {
                    tanggal_awal: tanggalAwal,
                    tanggal_akhir: tanggalAkhir,
                    kode_departement: ambilDepartement,
                    kode_karyawan: kd_karyawan
                },
                success: function(response) {
                    allData = response.data;
                    filterAllData = allData;
                    currentPage = 1;
                    renderTable(filterAllData);
                },
                error: function(xhr) {
                    alert('Gagal mengambil data');
                }
            });
        }

        function cariData() {
            const query = $('#input_cari').val().toLowerCase();

            if (query === '') {
                filterAllData = allData;
            } else {
                filterAllData = {};

                $.each(allData, function(nama, cutis) {
                    const lowerNama = nama.toLowerCase();
                    if (lowerNama.includes(query)) {
                        filterAllData[nama] = cutis;
                    } else {
                        const matchingCutis = cutis.filter(cuti => 
                            cuti.jenis_cuti.toLowerCase().includes(query) || 
                            cuti.tanggal_cuti.toLowerCase().includes(query)
                        );

                        if (matchingCutis.length > 0) {
                            filterAllData[nama] = matchingCutis;
                        }
                    }
                });
            }

            currentPage = 1;
            renderTable(filterAllData);
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

    function getBulan() {
        $.ajax({
            type: "GET",
            url: '{{ route("getMonth.all") }}',
            async: true,
            success: function(data) {
                $('#bulan').select2({
                    data: data.map(function(text) {
                        return { id: text.index, text: text.bulan };
                    }),
                    theme: "bootstrap-5"
                });
                var bulanSekarang = new Date().getMonth();
    
                $('#bulan').val(bulanSekarang+1).trigger('change');
            },
            error: function(data, textStatus, jqXHR) {
    
            }
        });
    }

    function downloadData(){
            let tanggalAwal = $('#tanggal_awal').val();
            let tanggalAkhir = $('#tanggal_akhir').val();
            const ambilDepartement = $('#departement').val();
            const kd_karyawan = $('#karyawan').val();
        
            if(tanggalAwal && tanggalAkhir){
                url = "{{route('cuti.exportGet')}}?"+`tanggal_awal=${tanggalAwal}&tanggal_akhir=${tanggalAkhir}&kode_departement=${ambilDepartement}&kode_karyawan=${kd_karyawan}`
                window.open(url)
            }else{
                alert('Range Tanggal Wajib Diisi');
                return
            }
    }

    function downloadRaw(){
        let tanggalAwal = $('#tanggal_awal').val();
            let tanggalAkhir = $('#tanggal_akhir').val();
            const ambilDepartement = $('#departement').val();
            const kd_karyawan = $('#karyawan').val();
        
            if(tanggalAwal && tanggalAkhir){
                url = "{{route('cuti.exportRawGet')}}?"+`tanggal_awal=${tanggalAwal}&tanggal_akhir=${tanggalAkhir}&kode_departement=${ambilDepartement}&kode_karyawan=${kd_karyawan}`
                window.open(url)
            }else{
                alert('Range Tanggal Wajib Diisi');
                return
            }
    }

</script>
@endpush

{{-- // function loadData(){
    //     $.ajax({
    //     url: "{{ route('cuti.reportGet') }}",
    //     method: "GET",
    //     success: function(response) {
    //         let tbody = '';
    //         $.each(response, function(nama, cutis) {
    //             let rowspan = cutis.length;
    //             let first = true;
    //             $no=1;
    //             $.each(cutis, function(index, cuti) {
    //                 $no++;
    //                 tbody += '<tr>';
    //                     if (first) {
    //                     tbody += '<td rowspan="'+rowspan+'" style="vertical-align: middle;">'+$no+'</td>';
    //                     tbody += '<td rowspan="'+rowspan+'" style="vertical-align: middle;">'+nama+'</td>';
                        
    //                     first = false;
    //                 }
    //                 tbody += '<td>'+cuti.jenis_cuti+'</td>';
    //                 tbody += '<td>'+cuti.tanggal_cuti+'</td>';
    //                 tbody += '</tr>';
    //             });
    //         });

    //         $('#reportcuti tbody').html(tbody);

    //         $('#reportcuti').DataTable({
    //             responsive: true
    //         });
    //     },
    //     error: function(xhr) {
    //         alert('Gagal mengambil data');
    //     }
    // });

    // } --}}


{{-- // function loadData() {
    //     if (table) {
    //         table.destroy();
    //     }

    // $.ajax({
    //     url: "{{ route('cuti.reportGet') }}",
    //     method: "GET",
    //     success: function(response) {
    //         let tbody = '';
    //         let no = 1;
            
    //         $.each(response.data, function(nama, cutis) {
    //             let rowspan = cutis.length;
    //             let first = true;

    //             $.each(cutis, function(index, cuti) {
    //                 tbody += '<tr>';
    //                 if (first) {
    //                     tbody += '<td rowspan="'+rowspan+'" style="vertical-align: middle;">'+ no +'</td>';
    //                     tbody += '<td rowspan="'+rowspan+'" style="vertical-align: middle;">'+ nama +'</td>';
    //                     first = false;
    //                 }
    //                 tbody += '<td>'+ cuti.jenis_cuti +'</td>';
    //                 tbody += '<td>'+ cuti.tanggal_cuti +'</td>';
    //                 tbody += '</tr>';
    //             });

    //             no++;
    //         });

    //         $('#reportcuti tbody').html(tbody);

    //         // table = $('#reportcuti').DataTable({
    //         //     responsive: true
    //         // });
    //     },
    //     error: function(xhr) {
    //         alert('Gagal mengambil data');
    //     }
    // });
    // } --}}


        