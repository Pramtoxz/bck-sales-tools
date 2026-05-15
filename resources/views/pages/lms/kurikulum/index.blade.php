@extends('layouts.app')
@section('content')
<style>

    .star {
        font-size: 16px;
        color: white;
        position: relative;
        display: inline-block;
    }

    .star::before {
        content: '\2605'; 
        color: white;
    }

    .star.full::before{
        color: orange;
    }

    .card-content {
        transition: transform 0.3s;
        cursor: pointer;
    }

    .card-content:hover {
        transform: scale(1.05);
    }

    .footerAllCard {
        position: absolute;
        bottom: 7px;
        left: 15px;
    }

    .footer-card {
        align-items: center;
        justify-content: center;
        padding: 5px 8px;
        border-radius: 15px;
        color: #fff;
        background-color: rgba(255, 255, 255, 0.2);
        display: inline-flex;
        font-size: 12px;
    }

    .footer-card i {
        margin-right: 5px;
        color: hotpink;

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

    .active>.page-link, .page-link.active {
        z-index: 3;
        color: var(--bs-pagination-active-color);
        background-color: #189dfb;
        border-color: #189dfb;
    }

    .pagination li a {
        color: #189dfb;
        text-decoration: none;
        font-size: 1.2em;
        line-height: 25px;
        justify-content: center;
    }

</style>
<div class="container-fluid">

    <div class="row" id="judulPage">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Katalog Training</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Katalog Training</li>
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
                    <div class="card-img-overlay d-flex justify-content-center align-items-center">
                        <form class="form-inline" id="form_search_data">
                            <div class="input-group">
                                <input style="max-width: 100%; opacity:1;" type="text" name="search" id="search" class="form-control" placeholder="Filter By Search...">
                                <div class="input-group-append">
                                    <button class="btn btn-info" style="font-weight: bold;" type="submit">Search</button>
                                    {{-- <button onclick="searchData()" class="btn btn-danger" style="font-weight: bold;" type="submit">Search</button> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card" style="border-radius:5px;">
                <div class="card-header align-items-center d-flex">
                    <h5 class="card-title mb-0 flex-grow-1 ms-4">Training List <span class="text-muted fw-normal ms-1" id="jumlah_training_list">(0)</span></h5>


                    <div class="flex-shrink-0">
                        <ul class="nav justify-content-end nav-pills card-header-pills" role="tablist">
                            <li class="nav-item">
                                <select name="active" id="kodekaryawan" class="form-control" onchange="getTrainingBaseKaryawan()">
                                    <option id='all' value="">Semua Training</option>
                                    <option id='kd_karyawan'>Training Saya</option>
                                </select>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content text-muted">
                        <div class="tab-pane active" id="table1" role="tabpanel">
                            <div class="row mb-1">
                                <div class="col-auto">
                                    <div class="mb-2">
                                        <h6 class="text-dark fw-bold"><i class="fas fa-filter"></i> FILTER</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div id="check-training-list">
                                    </div>
                                </div>

                                <div class="col-md-9 col-lg-9">
                                    <div class="row" id="training_list">

                                    </div>

                                    {{-- pagination --}}

                                    <div class="pagination">


                                    </div>

                                    {{-- end pagination --}}
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane" id="table2" role="tabpanel">
                            <div class="col-md-12">
                                <div class="row" id="riwayat_list">

                                </div>

                                <div class="pagination_riwayat">


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
    var user = '{{ Auth::user()->kd_karyawan}}';

    var delayInMilliseconds = 1000; //1 second
    let slideIndex = 0;
    var slides = 0;
    $(document).ready(function() {
        $('#kd_karyawan').attr('value', user)
        $('#search').val('');
        getJenisTrainingList()
        // ratingrefresh()
        // loadTrainingList()
        // loadRiwayatList()
        // showSlides();

        //PAGINATION TRAINING LIST
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

        $("#kodekaryawan").on("change", function() {
            setTimeout(function() {
                var numberOfItems = $(".card-content .card").length;
                var limitPerPage = 6;
                var totalPages = Math.ceil(numberOfItems / limitPerPage);
                var paginationSize = 7;
                var currentPage = 1;

                showPage(1);

                function showPage(whichPage) {
                    if (whichPage < 1 || whichPage > totalPages)
                        return false;
                    currentPage = whichPage;
                    $(".card-content .card").hide().slice((currentPage - 1) * limitPerPage, currentPage *
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
                $(".card-content").show();
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
            //END PAGINATION TRAINING LIST

        });
        $("#kodekaryawan").trigger("change");
    })

    function searchData() {
        loadTrainingList()
    }

    $('form#form_search_data').submit(function(e) {
        e.preventDefault();
        loadTrainingList()
    })

    function getJenisTrainingList() {
        $('#check-training-list').html('')
        $.ajax({
            type: "GET"
            , url: "{{ route('jenisTraining.all') }}"
            , success: function(data, textStatus, jqXHR) {
                data.forEach(element => {

                    divElement = `<div class="mb-1">
                        <ul class="list-group">
                            <li class="list-group-item d-flex align-items-center">
                            <input class="" type="checkbox" id="jenis_training_list_${element.id}" value="${element.id}" name="jenis_training_list" onchange="getTrainingList()">
                            <label class="form-check-label" style="margin-left:10px;" for="jenis_training_list_${element.id}">
                                ${element.text}
                            </label>
                            </li>
                        </ul>
                    </div>`;
                    $('#check-training-list').append(divElement)
                });
            }
            , error: function(data, textStatus, jqXHR) {}
        , });
    }

    function getTrainingList() {
        loadTrainingList()
    }

    function getTrainingBaseKaryawan() {     
        loadTrainingList()
    }

    function detailTraining(kd_training,event_training) {
        window.location.href = `/detail/${kd_training}/${event_training}`
    }

    function loadTrainingList() {
        var kd_karyawan = $('#kodekaryawan').val();
        var search = $('#search').val();
        var training_list = $('#training_list')
        training_list.html('');
        var element = "";
        $('#jumlah_training_list').html(`(0)`)
        let arrayJenisTrainingCheck = [];
        $("input:checkbox[name=jenis_training_list]:checked").each(function() {
            arrayJenisTrainingCheck.push($(this).val());
        });
        if (arrayJenisTrainingCheck.length != 0) {
            jenisList = arrayJenisTrainingCheck.toString()
        } else {
            jenisList = ""
        }

        $.ajax({
            type: "GET"
            , url: "{{ route('training.getListTraining') }}?jenis=" + jenisList + `&search=` + search + `&kode=` + kd_karyawan
            , success: function(data, textStatus, jqXHR) {
               console.log(data);
                $('#jumlah_training_list').html(`(${data.length})`)
                data.forEach(function(val) {
                    var jumlah = 0;
                    var ratarata = 0;
                    event_training=null;
                    val.event_training.forEach(function(valP) {
                        if (valP.jml_karyawan !== undefined) {
                            jumlah = valP.jml_karyawan;
                            event_training=valP.kd_event_training;
                            // console.log(event_training);
                        }
                    });
                    val.feedback_training.forEach(function(valr) {
                        if (valr.ratarating !== undefined) {
                            ratarata = valr.ratarating;
                        }

                    })


                    if (val.avatar_training) {
                        url_foto = `storage/gambar_training/${val.avatar_training}`
                    } else {
                        url_foto = `assets/images/auth-bg.jpg`;
                    }

                    element = `
                        <div class="card-content col-xl-4 col-sm-6 col-md-4" onclick="detailTraining('${val.kd_training}','${event_training}')" >
                            <div class="card text-center" style="border-radius:10px;border:1px solid #c1b9b9;">
                                                <img src="{{ asset('${url_foto}') }}" alt="" class="img-fluid" style="max-width: auto; height: 150px;object-fit: fill;border-top-left-radius:10px;border-top-right-radius:10px;">
                                                <div class="card-body" style="background-color:#0087ff;box-shadow:0 5px 5px rgb(1 1 1/20%);border-bottom-left-radius:10px;border-bottom-right-radius:10px;height: 150px;">
                                                <h5 class="font-size-14 mb-1 fw-bold" style="text-align: left"><a href="#" class="text-white">${shortenString(val.nama_training)}</a></h5>
                                                <p class="text-white font-size-7 mb-2" style="text-align: left;">${val.jenis_training.nama_jenis}</p>
                                                <div class="rating" data-rating="${ratarata}" style="text-align:left;">
                                                    @for($i=1;$i<=5;$i++)
                                                   <span class="star" data-index="{{ $i }}"></span>
                                                    @endfor
                                                </div>
                                                <div class="footerAllCard">
                                                    <span class="footer-card"><i class="fas fa-user" style="color:orange;"></i>${jumlah}</span>
                                                    <span class="footer-card"><i class="fas fa-tag" style="color:orange;"></i>${val.training_tag}</span>
                                                </div>
                                            
                            </div>
                         </div>
                            `;
                    training_list.append(element)
                })
               //rating
               $('.rating').each(function() {
                var rating = parseFloat($(this).data('rating'));
                var fullStars = Math.floor(rating);
                var partialStarWidth = (rating - fullStars) * 100;
                if (partialStarWidth > 50){
                    ratingbackground=fullStars+1;
                }else{
                    ratingbackground=fullStars;
                }
                $(this).find('.star').each(function(index) {
                        if(index < ratingbackground){
                            $(this).addClass('full');
                        }
                });
            }); 
            
            }
            , error: function(data, textStatus, jqXHR) {}
        , });

    }

    function shortenString(str) {
        console.log(str)
        // Check if the string length is greater than 50
        if (str.length > 40) {
            // Truncate string to 50 characters and append "..."
            return str.substring(0, 40) + '...';
        }
        // Return the string as is if it's not longer than 50
        return str;
    }

</script>
@endpush
