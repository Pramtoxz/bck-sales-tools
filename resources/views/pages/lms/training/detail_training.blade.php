@extends('layouts.app')
@section('content')
<style>
    /* card detail */
    .training-card-detail {
        position: relative;
        width: 100%;
        height: auto;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s, box-shadow 0.3s;
        /* background: linear-gradient(135deg, #800000, #800000 50%, #8e58d2); */
        /* background: linear-gradient(135deg, #4e2a85, #6f42c1, #8e58d2); */
        /* background : linear-gradient(135deg, #eba63f, #f04e4e, #e85353); */
        background-color:#0087ff;
        color: #fff;
        display: flex;
    }

    .training-card-detail:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
    }

    .training-card-detail-left {
        flex: 1;
        padding: 30px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .training-card-detail-header {
        display: flex;
        justify-content: flex-start;
        font-size: 1.1rem;
        font-weight: bold;
    }

    .training-card-detail-header .date-pill {
        background-color: #bfd0db;
        color: #333;
        padding: 10px 20px;
        border-radius: 50px;
        text-align: center;
        font-size: 14px;
    }

    .training-card-detail-body {
        flex-grow: 1;
        margin-top: 20px;
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
        text-align: left;
    }

    .training-card-detail-body h2 {
        margin: 0;
        font-size: 2rem;
        margin-bottom: 15px;
        color: #fff;
    }

    .training-card-detail-body p {
        font-size: 1.1rem;
    }

    .training-card-detail-footer {
        text-align: left;
    }

    .training-card-detail-footer button {
        background-color: #28a745;
        border: none;
        padding: 12px 25px;
        font-size: 1.2rem;
        border-radius: 30px;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.3s;
        display: flex;
        align-items: center;
    }

    .training-card-detail-footer button:hover {
        background-color: #218838;
        transform: scale(1.05);
    }

    .training-card-detail-footer button i {
        background-color: #fff;
        color: #28a745;
        border-radius: 50%;
        padding: 10px;
        margin-left: 10px;
        transition: transform 0.3s;
    }

    .training-card-detail-footer button:hover i {
        transform: translateX(5px);
    }

    .training-card-detail-right {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
    }

    .training-card-detail-right img {
        width: 300px;
        height: 300px;
        object-fit: fill;
        border-top-right-radius: 110px;
        border-bottom-right-radius: 90px;
        border-top-left-radius: 250px;
        border-bottom-left-radius: 300px;
        /* opacity: 0.7; */
    }
     .pagination_ulasan li a {
        color: rgb(17, 0, 255);
        text-decoration: none;
        font-size: 1.2em;
        line-height: 25px;
        justify-content: center;
    }

    .pagination_ulasan {
        justify-content: center;

    }
    .slider-controls {
      display: flex;
      justify-content: space-between;
      margin-top: 5px;
     /* margin-left: 30px; */
      margin-right: 20px;
    }
    .slider-controls button {
      background-color: orange;
      color: #fff;
      border: none;
      border-radius: 50%;
      width: 25px;
      height: 25px;
      margin: 0 5px;
      cursor: pointer;
    }

    .review-card {
      /* width: 185px; */
      height: 170px; 
      margin-right: 10px;
      background: #04aa6d; /* Gradasi warna ungu */
      border-radius: 25px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-bottom: 20px;
      position: relative;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .review-card:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
    }

    .review-card:last-child {
      margin-right: 0;
    }
    .review-card h3 {
      margin-top: 0;
      color: #fff;
    }
    .review-card p {
      margin-bottom: 10px;
      color: #eee;
      font-size: 12px;
    }
    .review-card .rating {
      position: absolute;
      bottom: 10px;
      /* left: 20px; */
    }
    .review-card .time {
      position: absolute;
      bottom: 40px;
      left: 20px;
      font-size: 12px;
    }
    .card {
    border: none;
    }

    .rating-container {
    text-align: center;
    /* margin: 5px auto; */
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .left-div, .middle-div, .right-div {
    flex: 1;
  }
  h2 {
    font-size: 18px;
    left: 20px;
    
  }
  .average-rating {
    font-size: 70px;
    /* margin-bottom: 20px; */
  }
  .stars-container {
    display: inline-block;
    font-size: 16px;
  }
  .star {
    color: orange;
    margin: 0 2px;
    
  }
  .count {
    font-size: 16px;
    margin-top: 10px;
  }
  .button-container {
    display: flex;
    flex-direction: column;
    /* background-color: white; */
  }
  .rating-button {
    margin-bottom: 5px;
    margin-right: 7px;
    padding: 5px;
    color: rgb(223, 221, 217);
    border: none;
    border-radius: 5px;
    cursor: pointer;
    background-color:#04aa6d;
  }
  .star-row{
    margin-bottom: 5px;
    padding: 5px;
  }

</style>
<div class="container-fluid">

<!-- start page title -->
<div class="row" id="judulPage">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-20">Detail Training</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Detail Training</li>
                </ol>
            </div>

        </div>
    </div>
</div>


    <div class="row" id="detail">
        <div class="col-12">
            <div class="card mb-0" style="border-radius:5px;">
                <div class="nav justify-content-start" id="training_list_detail">

                </div>
            </div>
        </div>
    </div>

    {{-- rating dan ulasan--}}
    <div class="row mt-3" id="rating">
        <div class="row col-12"> 
            <h2>Rating & Ulasan</h2>
        </div>
        <div class="col-sm-12 col-md-7">
            <div class="mb-4">
                
                <div class="rating-container">
                    <div class="left-div">
                     
                      <div class="average-rating" style="font-family:Arial, Helvetica, sans-serif;"></div>
                      <div class="stars-container">
                        <span class="star">&#9733;</span>
                        <span class="star">&#9733;</span>
                        <span class="star">&#9733;</span>
                        <span class="star">&#9733;</span>
                        <span class="star">&#9733;</span>
                      </div>
                      <div class="count"></div>
                    </div>
                    <div class="middle-div">
                      <div class="button-container">
                        <button class="rating-button"></button>
                        <button class="rating-button"></button>
                        <button class="rating-button"></button>
                        <button class="rating-button"></button>
                        <button class="rating-button"></button>
                      </div>
                    </div>
                    <div class="d-none d-sm-block right-div">
                      <div class="stars-container">
                        <div class="star-row">
                          <span class="star">&#9733;</span>
                          <span class="star">&#9733;</span>
                          <span class="star">&#9733;</span>
                          <span class="star">&#9733;</span>
                          <span class="star">&#9733;</span>
                        </div>
                        <div class="star-row">
                          <span class="star">&#9733;</span>
                          <span class="star">&#9733;</span>
                          <span class="star">&#9733;</span>
                          <span class="star">&#9733;</span>
                          <span class="star">&#9734;</span>
                        </div>
                        <div class="star-row">
                          <span class="star">&#9733;</span>
                          <span class="star">&#9733;</span>
                          <span class="star">&#9733;</span>
                          <span class="star">&#9734;</span>
                          <span class="star">&#9734;</span>
                        </div>
                        <div class="star-row">
                          <span class="star">&#9733;</span>
                          <span class="star">&#9733;</span>
                          <span class="star">&#9734;</span>
                          <span class="star">&#9734;</span>
                          <span class="star">&#9734;</span>
                        </div>
                        <div class="star-row">
                          <span class="star">&#9733;</span>
                          <span class="star">&#9734;</span>
                          <span class="star">&#9734;</span>
                          <span class="star">&#9734;</span>
                          <span class="star">&#9734;</span>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-5">
            <div class="row col-12"><h2></h2></div>
            <div class="mb-4" id="ulasan_list" class="ulasan_list">
                <div class="row" id="card_ulasan" style="justify-content: center">

                </div>

                <div class="pagination_ulasan">
                    <div class="slider-controls" style="justify-content: center">
                        <button onclick="prevSlide()"><span class="fas fa-chevron-left"></span></button>
                        <button onclick="nextSlide()"><span class="fas fa-chevron-right"></span></button>
                      </div>
                </div>

            </div>
        </div>
    </div>
    {{-- end rating dan ulasan --}}
</div>



</div>
@endsection
@push('script')
<script>
    let slideIndex = 0;
    var slides = 0;
    $(document).ready(function() { 
        var kd_training = "{{ $kd_training }}";
        var event_training="{{ $event_training }}";
        // console.log(event_training);
        detailTraining(kd_training,event_training)
    })

    function detailTraining(kd_training,event_training) {    
        var training_list_detail = $('#training_list_detail')
        var cardUlasan = $('#card_ulasan')
        var averageRating=$('.average-rating')
        var jumlahReview=$('.count')
        var user = '{{ Auth::user()->kd_karyawan}}';
        var peserta= false;

        $.ajax({
            type: "GET"
            , url: '{{route("training.getEventTraining")}}' + `?kd_training=${kd_training}&kd_event_training=${event_training}`,
            // url: '{{route("materi.all")}}' + `?kd_training=${kdTraining}&kd_event_training=${kdEventTraining}`
             success: function(data, textStatus, jqXHR) {
                // console.log(data);
                var currentDate = new Date();
                currentDate.setHours(0, 0, 0, 0); 

                var startDate = new Date(data[0].tanggal_mulai);
                var endDate = new Date(data[0].tanggal_akhir);
                startDate.setHours(0, 0, 0, 0); 
                endDate.setHours(0, 0, 0, 0); 

                data[0].peserta_event_training.forEach(function(val){
                    if(val.kd_karyawan == user){
                       peserta =true;
                    //    jawabanPostTest=val.jawaban_post_test;
                       idPeserta=val.id;
                    }
                });
          
                //detail training tanggal event
                if (data[0].tanggal_mulai) {
                    if (peserta) {
                        if (endDate.getTime() >= currentDate.getTime()) {
                            date = `${changeFormatDate(data[0].tanggal_mulai)} - ${changeFormatDate(data[0].tanggal_akhir)}`;
                        } else {
                            date = 'Jadwal Telah Berlalu';
                        }
                    } else {
                        date = 'Anda tidak terdaftar';
                    }
                } else {
                    date = 'Belum ada Jadwal';
                }
                //end detail training tanggal event


                //detail training button mulai training
                if (endDate.getTime() >= currentDate.getTime()) {
                        if (peserta) {
                            if (currentDate.getTime() >= startDate.getTime() && currentDate.getTime() <= endDate.getTime()) {
                                        button = `<button onclick="startTraining('${data[0].kd_training}','${data[0].kd_event_training}',${idPeserta})">
                                                    Mulai Training <i class="fas fa-arrow-right"></i>
                                                </button>`;
                                    } else {
                                        button = `<button>Training Belum Dimulai</button>`;
                            }
                        } else {
                            button = 'Anda tidak terdaftar';
                        }
                } else {
                        button='';
                }


                if (data[0].avatar_training) {
                    url_foto = `storage/gambar_training/${data[0].avatar_training}`
                } else {
                    url_foto = `assets/images/small/img-3.jpg`;
                }
                element = ` 
                                 <div class="training-card-detail m-2">
                                <div class="training-card-detail-left">
                                <div class="training-card-detail-header">
                                    <div class="date-pill">   
                                    ${date}
                                    </div>
                                </div>
                                <br>
                                <div class="d-sm-block d-lg-none d-md-none d-block">
                                    <img src="{{ asset('${url_foto}') }}" alt="Training Image" class="img img-thumbnail" style="border-radius:10px;">
                                </div>
                                <div class="training-card-detail-body">
                                <h2>${data[0].nama_training}</h2>
                                <p>${data[0].deskripsi}</p>
                                </div>
                                <div class="training-card-detail-footer">
                                ${button}
                                </div>
                                </div>
                                <div class="d-none d-sm-block training-card-detail-right">
                                    <img src="{{ asset('${url_foto}') }}" alt="Training Image">
                                </div>
                                
                                </div>
                                        <!-- end card -->
                            `;
                training_list_detail.append(element)

                let totalRating = 0;
                let totalRating5 = 0;
                let totalRating4 = 0;
                let totalRating3 = 0;
                let totalRating2 = 0;
                let totalRating1 = 0;
              
                data[0].feedback_training.forEach(function(val) {
                totalRating += val.rating; 
                switch(val.rating) {
                    case 1:
                        totalRating1 +=( val.rating/val.rating);
                        break;
                    case 2:
                        totalRating2 += ( val.rating/val.rating);
                        break;
                    case 3:
                        totalRating3 += ( val.rating/val.rating);
                        break;
                    case 4:
                        totalRating4 += ( val.rating/val.rating);
                        break;
                    case 5:
                        totalRating5 += ( val.rating/val.rating);
                        break;
                    default:
                        break;
                }
                    let createdAt = new Date(val.created_at);
                    let currentTime = new Date();
                    let timeDifference = currentTime - createdAt;

                    let seconds = Math.floor(timeDifference / 1000);
                    let minutes = Math.floor(seconds / 60);
                    let hours = Math.floor(minutes / 60);
                    let days = Math.floor(hours / 24);

                    let createdAtHumanReadable;
                    if (days > 0) {
                        createdAtHumanReadable = days + " hari yang lalu";
                    } else if (hours > 0) {
                        createdAtHumanReadable = hours + " jam yang lalu";
                    } else if (minutes > 0) {
                        createdAtHumanReadable = minutes + " menit yang lalu";
                    } else {
                        createdAtHumanReadable = seconds + " detik yang lalu";
                    }
     
                elemenUlasan = `
                <div class="review-card col-5 col-md-5">
                <p>"${val.catatan}".</p>
                <p class="rating"> `;

                for (let i = 0; i < val.rating; i++) {
                elemenUlasan += `<span style="color: #ffd700;">★</span>`;
                }

                elemenUlasan += `</p>
                <p class="time">${createdAtHumanReadable}</p>
                </div>`;

                cardUlasan.append(elemenUlasan);
                });

                $('.rating-button').eq(0).text('5 (' + totalRating5 + ' reviews)');
                $('.rating-button').eq(1).text('4 (' + totalRating4 + ' reviews)');
                $('.rating-button').eq(2).text('3 (' + totalRating3 + ' reviews)');
                $('.rating-button').eq(3).text('2 (' + totalRating2 + ' reviews)');
                $('.rating-button').eq(4).text('1 (' + totalRating1 + ' reviews)');
            
                let jumlah=data[0].feedback_training.length;
                // let average = Math.floor(totalRating / jumlah);
                let average = totalRating / jumlah; 
                
                if(!average){
                    averageRating.text(0)
                }else{
                    average = average.toFixed(1);
                    average = parseFloat(average); 
                    averageRating.text(average);
                }

                jumlahReview.text(jumlah + ' Reviews');
                slides = document.querySelectorAll('.review-card');
                 showSlides();
            }
            , error: function(data, textStatus, jqXHR) {}
        , }); 
    }

        
    function startTraining(kd_training,kd_event_training,idPeserta) {
        // console.log(kd_event_training);
        $.ajax({
            type: "GET",
            url: '{{route("training.HistoryPesertaTraining")}}'+ `?idPeserta=${idPeserta}`,
            success: function(data, textStatus, jqXHR) {
                window.location.href = `/materi/${kd_training}/${kd_event_training}`;
            }
            , error: function(data, textStatus, jqXHR) {}
        , });

        
    }

    //PAGINATION ULASAN
    function showSlides() {
        if (slides.length <= 2) {
            return;
        }
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = 'none';
        }
        for (let i = slideIndex; i < slideIndex + 2; i++) {
            if (i < slides.length) {
                slides[i].style.display = 'block';
            }
        }
    }

    function nextSlide() {
         slides = document.querySelectorAll('.review-card');
        if (slideIndex < slides.length - 2) {
            slideIndex += 2;
        } else {
            slideIndex = 0;
        }
        showSlides();
    }

    function prevSlide() {
         slides = document.querySelectorAll('.review-card');
        if (slideIndex > 0) {
            slideIndex -= 2;
        } else {
            slideIndex = slides.length - 2;
        }
        showSlides();
    }
    //END PAGINATION ULASAN

</script>
@endpush
