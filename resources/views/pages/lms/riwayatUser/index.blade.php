@extends('layouts.app')
@section('content')
<style>
    .history-card-detail {
        height: auto;
        border-radius: 20px;
        color: black;
        border: 1px solid rgb(230, 224, 224);
        padding:20px;

    }
    .riwayatscale{
        transition: transform 0.3s;
        cursor: pointer;
    }
    .riwayatscale:hover{
        transform: scale(1.05);
    }
    /* .history-card-detail:hover {
        
    } */

    .history-card-detail-center {
        padding: 10px;
    }

    .history-card-detail-body {
        margin-top: 5px;
        margin-bottom: 5px;
        text-align: left;   
    }

    .history-card-detail-body h2 {
        margin: 0;
        font-size: 1rem;
        color: black;
    }

    .history-card-detail-footer {
        text-align: left;
    }

    .tombol {
        background-color: #28a745;
        border: none;
        font-size: 12px;
        width: auto;
        padding: 5px 10px;
        border-radius: 30px;
        color: #fff;
        font-weight: bold;
    }
    .status {
        color: #28a745;
        font-weight: bold;
    }


    .history-card-detail-left {
        /* padding: 10px; */
    }

    .history-card-detail-left img {
        width: 100px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
        opacity: 1;
    }

    /* .history-card-detail-right {
        align-content: center;
    } */

    .download-button {
        background-color: #007bff;
        width: 120px;
    }

    .review-button {
        background-color: #28a745;
        width: 120px;
    }

    .pagination li a {
        color: rgb(17, 0, 255);
        text-decoration: none;
        font-size: 1.2em;
        line-height: 25px;
        justify-content: center;
    }

    .pagination {
        justify-content: center;

    }

    .card {
    border: none;
    }

 
  h2 {
    font-size: 18px;
    left: 40px;
    
  }


</style>
<div class="container-fluid">

<div class="row" id="judulPage">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-20">Riwayat Training</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Riwayat Training</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row" id="alltraininglist">
    <div class="col-12">
        <div class="card mb-0" style="border-radius:5px;">
            <div class="nav justify-content-start">
                <img src="{{ asset('assets/images/small/img-9.jpg') }}" class="card-img-top" alt="..." style="max-width: 100%; max-height: 200px; opacity:0.5;">
            </div>
        </div>
        <div class="card" style="border-radius:5px;">
            <div class="card-header align-items-center d-flex">
                <h5 class="card-title mb-0 flex-grow-1 ms-4">Riwayat List <span class="text-muted fw-normal ms-1" id="jumlah_riwayat_list">(0)</span></h5>
            </div>
            <div class="card-body">
                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="table2" role="tabpanel">
                        
                            <div class="row me-2" id="riwayat_list">

                            </div>

                            <div class="pagination">


                            </div>

                    </div>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


<div class="modal fade" id="containerModalTambah"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Daftar Materi </h5>
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
                                        <th scope="col">Tipe Materi</th>
                                        <th scope="col">Materi</th>
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


<div class="modal fade" id="containerModalDetail"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="containerModalLabel"><i class="fas fa-check-circle" title="Data"></i> <span id="ket_submit"></span> Detail Training</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose1"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                  <div class="row">
                    <div class="col-md-6 col-12">
                      <h6>Jenis Training:</h6>
                      <p></p>
                    </div>
                    <div class="col-md-6 col-12">
                      <h6>Nama Training:</h6>
                      <p></p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <h6>Deskripsi Training:</h6>
                      <p style="text-align:justify"></p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 col-12">
                      <h6>Tanggal Mulai:</h6>
                      <p></p>
                    </div>
                    <div class="col-md-6 col-12">
                      <h6>Tanggal Akhir:</h6>
                      <p></p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 col-12">
                      <h6>Status Training:</h6>
                      <p></p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">History Activity</h4>
                            </div><!-- end card header -->

                            <div class="card-body px-0">
                                <div class="px-3" data-simplebar style="max-height: 352px;">
                                    <ul class="list-unstyled activity-wid mb-0" id="wadah_activity">

                                        
                                    </ul>
                                </div>    
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="viewNilai()" >Lihat Nilai</button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
@push('script')
<script>
    var delayInMilliseconds = 1000;
    $(document).ready(function() {
        var table_materi;
        loadRiwayatList()

        //pagination
            $(".pagination").append(
                $("<li>").addClass("page-item").addClass("previous-page").append($("<a>").addClass(
                    "page-link").attr({
                    href: "javascript:void(0)"
                }).text("Prev"))
                , $("<li>").addClass("page-item").addClass("next-page").append($("<a>").addClass(
                    "page-link").attr({
                    href: "javascript:void(0)"
                }).text("Next"))
            );

        function getPageList(totalPages, page, maxLength) {
            function range(start, end) {
                return Array.from(Array(end - start + 1)
                    , (_, i) => i + start);
            }
            var sideWith = maxLength < 9 ? 1 : 2;
            var leftWidth = (maxLength - sideWith * 2 - 3) >> 1;
            var rightWidth = (maxLength - sideWith * 2 - 3) >> 1;
            if (totalPages <= maxLength) {
                return range(1, totalPages);
            }
            if (page <= maxLength - sideWith - 1 - rightWidth) {
                return range(1, maxLength - sideWith - 1).concat(0, range(totalPages - sideWith + 1, totalPages));
            }

            if (page <= totalPages - sideWith - 1 - rightWidth) {
                return range(1, sideWith).concat(0, range(totalPages - sideWith - 1 - rightWidth - leftWidth, totalPages));
            }
            return range(1, sideWith).concat(0, range(page - leftWidth, page + rightWidth), 0, range(totalPages - sideWith +
                1, totalPages));
        }
        
        setTimeout(function() {
            var numberOfItems = $(".history-card-detail").length;
            var limitPerPage = 5;
            var totalPages = Math.ceil(numberOfItems / limitPerPage);
            var paginationSize = 7;
            var currentPage = 1;

            showPage(1);

            function showPage(whichPage) {
                if (whichPage < 1 || whichPage > totalPages)
                    return false;
                currentPage = whichPage;
                $(".history-card-detail").hide().slice((currentPage - 1) * limitPerPage, currentPage *
                    limitPerPage).show();
                $(".pagination").css({
                    "text-align": "center"
                    , "margin": "30px 30px 60px"
                    , "user-select": "none"
                });
                $(".pagination li").css({
                    "display": "inline-block",
                    // "margin": "5px",
                    "box-shadow": "0 5px 25px rgb(1 1 1 / 10%)"
                });

                $(".previous-page, .next-page").css({
                    "background": "#0AB1CE"
                    , "width": "80px"
                    , "border-radius": "45px"
                    , "cursor": "pointer"
                    , "transition": "0.3s ease"
                });
                $(".previous-page:hover").css({
                    "transform": "translateX(-5px)"
                });
                $(".next-page:hover").css({
                    "transform": "translateX(5px)"
                });
                $(".current-page, .dots").css({
                    "background": "#ccc"
                    , "width": "45px"
                    , "border-radius": "50%"
                    , "cursor": "pointer"
                });
                $(".disable").css({
                    "background": "#ccc"
                });

                $(".pagination li").slice(1, -1).remove();

                getPageList(totalPages, currentPage, paginationSize).forEach(item => {
                    $("<li>").addClass("page-item").addClass(item ? "current-page" : "dots")
                        .toggleClass("active", item === currentPage).append($("<a>")
                            .addClass("page-link").attr({
                                href: "javascript:void(0)"
                            })
                            .text(item || "...")).insertBefore(".next-page");
                });
                $(".previous-page").toggleClass("disabled", currentPage === 1);
                $(".next-page").toggleClass("disabled", currentPage === totalPages);
                return true;
            }
            // $(".card-content").show();
            $(document).on("click", ".pagination li.current-page:not(.active)", function() {
                return showPage(+$(this).text());
            });
            $(".next-page").on("click", function() {
                return showPage(currentPage + 1);
            });
            $(".previous-page").on("click", function() {
                return showPage(currentPage - 1);
            }); 
        }, delayInMilliseconds);
        //end pagination

    })

    function loadRiwayatList() {
        var RiwayatList = $('#riwayat_list')
        $('#jumlah_riwayat_list').html(`(0)`)
        RiwayatList.html('');
        $.ajax({
            type: "GET"
            , url: "{{ route('training.getRiwayatTraining') }}"
            , success: function(data, textStatus, jqXHR) {
                $('#jumlah_riwayat_list').html(`(${data.length})`)
                data.forEach(function(val) {
                    if (val.avatar_training) {
                        url_foto = `storage/gambar_training/${val.avatar_training}`
                    } else {
                        url_foto = `assets/images/auth-bg.jpg`;
                    }

                    element = `
                    <div class="col-lg-6 col-12 col-md-6">        
                                <div class="card riwayatscale border px-lg-3" style="border-radius:10px;background-color:#0087ff;">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <img class="card-img img-fluid" src="{{ asset('${url_foto}') }}" alt="Card image" style="border-radius:10px;min-height:8em;max-height:8em;" >
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="text-white">${val.nama_training}</h5>
                                                <span class="card-text text-white">${changeFormatDate(val.tanggal_mulai)}</span>
                                                <br>
                                                <span class="card-text badge bg-warning">${val.status}</span>
                                                <br>
                                                <br>
                                                <button class="btn btn-success btn-sm" onclick="downloadMateri('${val.kd_training}','${val.status}')"><i class="fas fa-download"></i> Materi</button>
                                                <button class="btn btn-success btn-sm" onclick="viewDetail('${val.status}','${val.kd_event_training}')"><i class="fas fa-search"></i> Detail</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
            `;
                    RiwayatList.append(element)
                })

            }
            , error: function(data, textStatus, jqXHR) {}
        , });
        
    }

    function downloadMateri(kd_training,status){
        console.log(status);
        if(status != 'Selesai'){
            swal.fire({
            text: "Kamu Belum Menyelesaikan Training", 
            icon: 'warning', 
            confirmButtonColor: "#2ab57d", 
            confirmButtonText: 'Click OK'
            });
        }else{
            $('#containerModalTambah').modal('show');
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
            ajax: '{{route("materiTrainingUser.show")}}'+`?kd_training=${kd_training}`,
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
                    data: 'tipe_materi',
                    name: 'tipe_materi'
                },
                {
                    data: 'preview',
                    name: 'preview'
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
    }

    function viewDetail(status,kd_event_training){
        // if(status != 'Selesai'){
        //     swal.fire({
        //     text: "Kamu Belum Menyelesaikan Training", 
        //     icon: 'warning', 
        //     confirmButtonColor: "#2ab57d", 
        //     confirmButtonText: 'Click OK'
        //     });
        // }else{   

            $.ajax({
            type: 'GET',
            url: '{{route("detailTrainingUser.show")}}'+`?kd_event_training=${kd_event_training}`,
                success: function(response) {
                    tampilkanDetailTraining(response[0]);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
            getHistory(kd_event_training)
        // }
    }

    function tampilkanDetailTraining(data) {
        // console.log(data);
        

        let DataDetail = Object.values(data);

        $("#containerModalDetail").find("p").each(function(index) {
        $(this).text(DataDetail[index]);
        });
        $('#containerModalDetail').modal('show');
    }

    function getHistory(kd_event_training){
        var wadah_activity = $('#wadah_activity')
        wadah_activity.html('')
        var element = ""
        $.ajax({
            type: 'GET',
            url: '{{route("detailTrainingUser.historyDetail")}}'+`?kd_event_training=${kd_event_training}`,
                success: function(response) {
                   response.forEach((val,index) => {
                    if(response.length != index+1){
                        className = "activity-border";
                    }else{
                        className = "";
                    }
                    element = `<li class="activity-list ${className}">
                                        <div class="activity-icon avatar-md">
                                            <span class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                            <i class="fas fa-link font-size-24"></i>
                                            </span>
                                        </div>
                                        <div class="timeline-list-item">
                                            <div class="d-flex">
                                                <div class="flex-grow-1 overflow-hidden me-4">
                                                    <h5 class="font-size-14 mb-1">${val.tanggal}</h5>
                                                    <p class="text-truncate text-muted font-size-13"><b>${val.keterangan}</b></p>
                                                </div>
                                            </div>
                                        </div> 
                                    </li>`;
                    wadah_activity.append(element)
                   });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
    }

    function downloadSoalPreTest(value){
        window.location.href=`/downloadSoalPreTest/${value}`;
    }

    function downloadJawabanPreTest(value){
        window.location.href=`/downloadJawabanPreTest/${value}`;
    }

    function downloadSoalPostTest(value){
        window.location.href=`/downloadSoalPostTest/${value}`;
    }

    function downloadJawabanPostTest(value) {
        window.location.href=`/downloadJawabanPostTest/${value}`;
    }

    function viewNilai(){
        window.location.href=`/nilai`;
    }
  

</script>
@endpush
