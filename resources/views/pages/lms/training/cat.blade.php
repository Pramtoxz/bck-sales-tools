@extends('layouts.app_cat',['keterangan' => $keterangan])

@push('css-custom')
<style>
    .img-home {
        width:30% !important;
    }

    @media only screen and (max-width: 600px) {
        .img-home {
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 800px) {
        .img-home {
            width: 80% !important;
        }
    }
    

    .main-content {
        margin-left: 0px !important;
    }
    .card-title {
        font-size: 15.4px;
        margin: 0 0 0px 0;
    }
    .card {
        margin-bottom: .9rem;
    }

    .col-baru {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 2rem;
        margin-right:5px;
        margin-bottom:0px;
    }

    .form-check-input{
        border:1px solid rgb(94, 93, 93) !important;
    }

    .form-check-input:checked {
        background-color: #0b8cd7;
        border:1px solid #0b8cd7 !important;
    }

    .bg-belum {
        background-color:white;
        color:black;
    }

    .bg-active {
        background-color : #e7a10a;
        color:white;
    }

    .bg-sudah {
        background-color : #07bb34;
        color:white;
    }

    .ujian{
        cursor:pointer;
    }

    /* .spinner-border{
        box-sizing: border-box;
        position: absolute;
        top: 40%;
        left: 30%;
        width:100px;
        height:100px;
    } */

    .peringatan {
        color:red;
    }

    .img-awal {
        width:20% !important;
    }
    @media only screen and (max-width: 600px) {
        .img-awal {
            width: 100% !important;
        }
    }
    @media only screen and (max-width: 800px) {
        .img-awal {
            width: 80% !important;
        }
    }

</style>
@endpush
@section('content')
<div class="container-fluid">
    {{-- bantu --}}
    @php
        $ket_mulai_test = "belum";
        $waktu = null;
        $batas_waktu = null;
        $waktu_sekarang = date('Y-m-d H:i:s');
        if(!empty($data_test)){
            if($keterangan == "pre-test"){
                $waktu = $data_test->waktu_mulai_pre_test;
                $batas_waktu = $data_test->batas_waktu_pre_test;
                if($waktu != null){
                    $ket_mulai_test = "sudah";
                    if($waktu_sekarang > $batas_waktu){
                        $ket_mulai_test = "selesai";
                    }else if($data_test->user_selesai_pre_test != null){
                        $ket_mulai_test = "selesai";
                    }
                }
            }else{
                $waktu = $data_test->waktu_mulai_post_test;
                $batas_waktu = $data_test->batas_waktu_post_test;
                if($waktu != null){
                    $ket_mulai_test = "sudah";
                    if($waktu_sekarang > $batas_waktu){
                        $ket_mulai_test = "selesai";
                    }else if($data_test->user_selesai_post_test != null){
                        $ket_mulai_test = "selesai";
                    }
                }
            }
        }  
    @endphp
    <input type="hidden" name="ket_mulai_test" id="ket_mulai_test" value="{{$ket_mulai_test}}">
    <input type="hidden" name="waktu_mulai" id="waktu_mulai" value="{{$waktu}}">
    <input type="hidden" name="batas_waktu" id="batas_waktu" value="{{$batas_waktu}}">
    {{-- end bantu --}}

    <div class="row" id="mulai_test" style="display: none;">
        <div class="col-lg-12 text-center pt-5">
                <img src="{{asset('assets/images/lms/35020247_8262064.svg')}}" alt="" class="img-awal">
                <h2>Klik tombol 'Mulai Test' untuk memulai. Anda akan memiliki waktu yang terbatas untuk menyelesaikan ujian.</h2>
                <button class="btn btn-success" onclick="mulaiTest()">Mulai Test</button>
        </div>
    </div>
    <div class="row" id="wadah_ujian" style="display: none;">
        {{-- bantu --}}
        
        {{-- end bantu --}}
        <div class="col-lg-2 col-12 border rounded-3 py-3" style="background-color:#189dfb;min-height:32em !important;">
            <h4 style="font-family:Arial, Helvetica, sans-serif;color:white;text-align:center;"> <i class="fa fa-clipboard-check"></i> <b>Nomor Soal</b></h4>
            <div class="row mt-4" id="wadah_pilihan_soal">
                
            </div>
        </div>
        <div class="col-lg-10 col-12 px-4 py-3">
            <div class="row wadah_spinner">
                <div class="col-lg-12">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                      </div>
                </div>
            </div>
            <div class="row wadah_soal_ujian">
                <div class="col-lg-6">
                    <h2 style="font-family:Arial, Helvetica, sans-serif;"><b>Soal No <span id="no_soal_display"></span></b></h2>
                </div>
                <div class="col-lg-6 text-end">
                    <h2 id="display_waktu_test"><i class="fas fa-clock" title="waktu"></i> <span id="jam_display"></span>:<span id="menit_display"></span>:<span id="detik_display"></span></h2>
                </div>
            </div>
            
            <hr>
            <div class="row wadah_soal_ujian">
                <div class="col-lg-12">
                    <p style="font-family:Arial, Helvetica, sans-serif;font-size:1.2em;" id="soal_display">-</p>
                    <div>
                        <form action="#" method="POST" id="formJawab">
                            {{-- bantu --}}
                            <input type="hidden" name="kd_event_training" id="kd_event_training" value="{{$kd_event_training}}">
                            <input type="hidden" name="keterangan" id="keterangan" value="{{$keterangan}}">
                            <input type="hidden" name="kode_soal" id="kode_soal" value="{{$kode_soal}}">
                            {{-- end bantu --}}
                            {{-- dynamic --}}
                            <input type="hidden" name="no_soal" id="no_soal">
                            <input type="hidden" name="no_soal_index" id="no_soal_index">
                            {{-- end dynamic --}}
                            @csrf
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="option" id="option_a" value="a" required>
                                <label class="form-check-label" for="option_a" id="option_display_a">
                                A.Default radio
                                </label>
                            </div>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="option" id="option_b" value="b" required>
                                <label class="form-check-label" for="option_b" id="option_display_b">
                                    B.Default checked radio
                                </label>
                            </div>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="option" id="option_c" value="c" required>
                                <label class="form-check-label" for="option_c" id="option_display_c">
                                    C.Default checked radio
                                </label>
                            </div>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="option" id="option_d" value="d" required>
                                <label class="form-check-label" for="option_d" id="option_display_d">
                                    D.Default checked radio
                                </label>
                            </div>
                            <div class="mt-4">
                                <button class="btn btn-success btn-xs" type="submit">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>   
    </div>
    <button id="btn_selesai" class="btn btn-danger" style="position:absolute;bottom:15px;right:15px;" onclick="selesaiUjian()">Selesai</button>
    <div class="row" id="selesai_test" style="display: none;">
        <div class="col-lg-12 text-center pt-5">
                <img src="{{asset('assets/images/lms/rb_6987-removebg-preview.png')}}" alt="" class="img-home">
                <h2 class="mt-2">Yeay,, Kamu Sudah Menyelesaikan Test :D</h2>
                @if($jumlah_jawaban != null)
                <div class="row mt-4 text-center d-flex justify-content-center">
                    <div class="col-lg-1 col-2">
                        <h2><i class="fas fa-check text-success"></i> <span id="jumlah_betul">{{$jumlah_jawaban['jumlah_betul']}}</span></h2>
                    </div>
                    <div class="col-lg-1 col-2">
                        <h2><i class="fas fa-times text-danger"></i> <span id="jumlah_salah">{{$jumlah_jawaban['jumlah_salah']}}</span></h2>
                    </div>
                    <div class="col-lg-2">
                        <h2>Nilai : <span id="total_nilai">{{$jumlah_jawaban['nilai']}}</span></h2>
                    </div>
                </div>
                @endif
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        $(document).ready(function(){
            loadPilihanSoal()
            openAutomatic()

            $("form#formJawab").submit(function(e) {
            e.preventDefault();          
            var formData = new FormData($(this)[0]);
            console.log(formData);
            var url = "{{route('trainingSoal.saveJawaban')}}"
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                async: true,
                beforeSend : function(){
                    $('.wadah_spinner').show()
                    $('.wadah_soal_ujian').hide()
                },
                success: function(data) {
                    // remove class active sebelumnya
                    $('.ujian').removeClass("bg-active")
                    data_soal = data.data_soal
                    if(data_soal != null){
                        $('#no_soal_display').html(data.index+1)
                        // set no soal 1
                        $('#no_soal_index').val(data.index)

                        getDisplaySoal(data_soal)

                        

                        // set jawaban jika sudah ada
                        if(data.jawaban_pilihan != null){
                            setJawaban(data.jawaban_pilihan)
                        }else{
                            $("input[name='option']").prop('checked', false)
                        }

                        $(`#index_${data.index}`).addClass('bg-active')
                        $(`#index_${data.index-1}`).addClass('bg-sudah')

                    }else{
                        $(`#index_${data.index}`).addClass('bg-sudah')
                    }

                    $('.wadah_spinner').hide()
                    $('.wadah_soal_ujian').show()
                    
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
        })

        function mulaiTest(){
            // ada pengaturan kondisi awal jika sudah mulai maka halaman akan buka langsung cat
            $('#mulai_test').hide()
            $('#wadah_ujian').show()
            $('#selesai_test').hide()
            $('#btn_selesai').show()
            var kd_event_training = $('#kd_event_training').val()
            var keterangan = $('#keterangan').val()
            console.log(kd_event_training)
            var url = "{{route('trainingSoal.startTest')}}"
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    kd_event_training: kd_event_training,
                    keterangan:keterangan,
                    _token: '{{ csrf_token() }}'
                },
                async: true,
                success: function(data) {
                    $('#ket_mulai_test').val("sudah")
                    if(keterangan == "pre-test"){
                        $('#waktu_mulai').val(data.waktu_mulai_pre_test)
                        $('#batas_waktu').val(data.batas_waktu_pre_test)
                        batas_waktu = data.batas_waktu_pre_test
                    }else{
                        $('#waktu_mulai').val(data.waktu_mulai_post_test)
                        $('#batas_waktu').val(data.batas_waktu_post_test)
                        batas_waktu = data.batas_waktu_post_test
                    }
                    countDownTimer(batas_waktu)
                },
    
                error: function(xhr, status, error) {
                    console.log(error)
                    $('#ket_failed_toast').html(JSON.parse(xhr.responseText))
                    new bootstrap.Toast(toastfailed).show()
                }
            });
        }

        function loadPilihanSoal(){
            var wadah_pilihan_soal = $('#wadah_pilihan_soal')
            var kd_event_training = $('#kd_event_training').val()
            var keterangan = $('#keterangan').val()
            var kode_soal = $('#kode_soal').val()
            wadah_pilihan_soal.html()
            $.ajax({
                url:"{{url('/view/pilihan/soal')}}"+`?kd_event_training=${kd_event_training}&keterangan=${keterangan}&kode_soal=${kode_soal}`,
                type: 'GET',
                beforeSend : function(){
                    $('.wadah_spinner').show()
                    $('.wadah_soal_ujian').hide()
                },
                success: function(data) {
                    jawaban = data.jawaban
                    data.data_pilihan.forEach((no_soal,index) => {
                        bg_active = index ==  0 ? 'bg-active' : ''
                        bg_sudah_jawab = jawaban.includes(no_soal) ? 'bg-sudah' : ''
                        wadah_pilihan_soal.append(`
                            <div class="col-baru" >
                                <div class="card ujian ${bg_active} ${bg_sudah_jawab}" style="width: 2rem;" id="index_${index}" onclick="getSoal('${no_soal}','${index}')">
                                    <div class="card-title text-center" style="font-family:Arial, Helvetica, sans-serif;" >
                                        ${index+1}
                                    </div>
                                </div>
                            </div>
                        `)
                    });
                    // soal
                    data_soal = data.data_soal
                    $('#no_soal_display').html("1")
                    // set no soal 1
                    $('#no_soal_index').val(0)

                    getDisplaySoal(data_soal)
                    
                    // set jawaban jika sudah ada
                    if(data.jawaban_pilihan != null){
                        setJawaban(data.jawaban_pilihan)
                    }

                    $('.wadah_spinner').hide()
                    $('.wadah_soal_ujian').show()
                }
            });
        }

        function countDownTimer(waktu=null){
            if(waktu == null){
                waktu = $('#batas_waktu').val()
            }
            console.log(waktu)
            // Set the date we're counting down to
            var countDownDate = new Date(waktu).getTime();
            console.log(countDownDate)

            // Update the count down every 1 second
            var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            $('#jam_display').html(addZero(hours))
            $('#menit_display').html(addZero(minutes))
            $('#detik_display').html(addZero(seconds))

            // berikan warna merah
            if(minutes <= 5){
                $('#display_waktu_test').addClass('peringatan')
            }

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                $('#display_waktu_test').html('Loading...')
                prosesSelesai()
            }
            }, 1000);
        }

        function getDisplaySoal(data_soal){
            $('#soal_display').html(data_soal.text_soal)
            $('#option_display_a').html(`A.${data_soal.opsi_a}`)
            $('#option_display_b').html(`B.${data_soal.opsi_b}`)
            $('#option_display_c').html(`C.${data_soal.opsi_c}`)
            $('#option_display_d').html(`D.${data_soal.opsi_d}`)
            // set soal pertama kali muncul
            $('#no_soal').val(data_soal.no_soal)
        }

        function setJawaban(jawaban){
            console.log(jawaban)
            $(`#option_${jawaban}`).prop('checked', true)
        }

        function getSoal(no_soal,index){
            var kd_event_training = $('#kd_event_training').val()
            var keterangan = $('#keterangan').val()
            var kode_soal = $('#kode_soal').val()
            $.ajax({
                url:"{{url('/view/soal')}}"+`?kd_event_training=${kd_event_training}&keterangan=${keterangan}&kode_soal=${kode_soal}&no_soal=${no_soal}&no_soal_index=${index}`,
                type: 'GET',
                beforeSend : function(){
                    $('.wadah_spinner').show()
                    $('.wadah_soal_ujian').hide()
                },
                success: function(data) {
                    // remove class active sebelumnya
                    $('.ujian').removeClass("bg-active")

                    data_soal = data.data_soal
                    $('#no_soal_display').html(parseInt(index)+1)
                    // set no soal 1
                    $('#no_soal_index').val(index)

                    getDisplaySoal(data_soal)

                    // set jawaban jika sudah ada
                    if(data.jawaban_pilihan != null){
                        setJawaban(data.jawaban_pilihan)
                    }else{
                        $("input[name='option']").prop('checked', false)
                    }

                    $(`#index_${data.index}`).addClass('bg-active')

                    $('.wadah_spinner').hide()
                    $('.wadah_soal_ujian').show()
                }
            });
        }

        function openAutomatic(){
            ket_mulai_test = $('#ket_mulai_test').val()
            waktu_mulai = $('#waktu_mulai').val()
            if(ket_mulai_test == "sudah"){
                $('#mulai_test').hide()
                $('#wadah_ujian').show()
                $('#selesai_test').hide()
                $('#btn_selesai').show()
                countDownTimer()
            }else if(ket_mulai_test == "belum"){
                $('#mulai_test').show()
                $('#wadah_ujian').hide()
                $('#selesai_test').hide()
                $('#btn_selesai').hide()
            }else if(ket_mulai_test == "selesai"){
                $('#mulai_test').hide()
                $('#wadah_ujian').hide()
                $('#selesai_test').show()
                $('#btn_selesai').hide()
            }
        }

        function closeAutomatic(){
            $('#mulai_test').hide()
            $('#wadah_ujian').hide()
            $('#selesai_test').show()
            $('#ket_mulai_test').val('selesai')
            $('#btn_selesai').hide()
        }

        function addZero(i) {
			if (i < 10) {i = "0" + i}
			return i;
		}

        function selesaiUjian(){
            Swal.fire({
            title:"Yakin Untuk Selesai ?",
            text:"You won't be able to revert this!",
            icon:"warning",
            showCancelButton:!0,
            confirmButtonColor:"#2ab57d",
            cancelButtonColor:"#fd625e",
            confirmButtonText:"Yes !"}).then(function(e){
                if(e.value){
                    prosesSelesai()
                }
            })
        }

        function prosesSelesai(){
            var kd_event_training = $('#kd_event_training').val()
            var kode_soal = $('#kode_soal').val()
            var keterangan = $('#keterangan').val()
            var url = "{{route('trainingSoal.selesaiTest')}}"
            $.ajax({
                url: url,
                type: 'POST',
                data: { 
                    kd_event_training,
                    kode_soal,
                    keterangan
                },
                async: true,
                success: function(data) {
                    if(data){
                        window.location.href = window.location.href;
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
