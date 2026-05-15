<!doctype html>
<html lang="en">
<head>
    @include('components.head')
    @stack('css-custom')
    {{-- <link href="assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" /> --}}
    
    <style>

        .fallback{
            justify-items: center;
        }
        #rating{
            font-size: 50px;
            text-align: center;
        }

        .checked {
            color: orange;
        }

        .locked {
            color: grey;
        }


        .access {
            color: black;
        }

        .active {
            color: #0087ff;
        }
        .check{
            color: #0087ff;   
        }


        .material-list {
            background: #f8f9fa;
            /* background: aliceblue; */
            padding: 5px;
            overflow-y: auto;
            border-right: 1px solid #dee2e6;
            font-weight: bold;
            
        }

        .material-list ul {
            list-style: none;
            padding: 0;
        }

        .material-list li {
            padding: 10px 50px;
            margin-bottom: 10px;
            cursor: pointer;
            display: flex;
            justify-content: left;
            align-items: center;
            /* transition: background-color 0.3s, transform 0.3s; */
            
        }

        .sidebarMateri {  
            margin-bottom: 10px;
            background: #ffffff;
            cursor: pointer;
            justify-content: space-between;
            align-items: center;
            transition: background 0.3s;
            color: #ffffff;
            transition: background-color 0.3s, transform 0.3s;
        }
        .sidebarMateri:hover{
            background: #ffffff;
            color: rgb(47, 66, 243);
            /* transform: scale(1.05); */
        }


        .material-list li:hover {
            background: #e2eae2;
            color: rgb(47, 66, 243);
            /* transform: scale(1.05); */

        }
        .material-list li .fa-check-circle {
            color: #d4edda;
            transition: color 0.3s;
           
        }

        .material-list li.completed .fa-check-circle {
            color: #28a745;
        }

        .material-content {
            padding: 20px;
            overflow-y: auto;
            background: #fff;
            text-align: justify;
        }

        .material-content h2 {
            margin-top: 0;
        }

        .material-content p {
            margin-bottom: 15px;
        }

        /* .navigation-buttons {
            justify-content: space-around;
            background: #f8faf8;

        } */


        .btn-custom {
            background-color: #ffffff;
            border-radius: 30px;
            color: black;
            cursor: pointer;
            transition: transform 0.3s;
            font-weight: bold;
        }

        .btn-custom:hover {
            color: rgb(47, 66, 243);
            transform: scale(1.05);
        }

        .btn-custom i {
            background-color: #0087ff;
            padding: 5px;
            color: #ffffff;
            border-radius: 50%;
            margin-left: 10px;
            margin-right: 10px;
            transition: transform 0.3s;
        }

        .btn-custom i:hover {
            transform: translateX(5px);
        }

    </style>
</head>
<body style="background-color:#fbfaff;">
    <!-- <body data-layout="horizontal"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">


        @include('components.nav')


        <div class="vertical-menu" style="background-color:#f8f9fa;">
     <div data-simplebar class="h-100">   
        <div id="sidebar-menu" class="material-list">
              
                        <div class="sidebarMateri" onclick=BackToDashboard()>
                                <button class="btn btn-custom" >
                                    <i class="fas fa-home"></i>Dashboard</button>

                        </div>   
                        <div class="sidebarMateri" onclick=ruletraining()>
                            <button class="btn btn-custom">
                                <i class="fas fa-clock"></i>Rule Training</button>

                    </div>  
           
            </div>   
        </div>
    </div>

    <div class="main-content">
        <div class="page-content">
            <div class="material-content col-sm-12 col-md-12">
        <div id="material-title"></div>
        <div id="material-contents">
        </div>
    </div>

</div>
    


<footer class="footer" style="background-color:#f8f9fa;position:fixed;">
    <div class="container-fluid">
        <div class="row" >
            <div class="offset-1 col-5 col-sm-6 text-dark" >
            <button id="prev-button" class="btn btn-custom" style="justify-content: between;display:none;">
                <i class="fas fa-arrow-left"></i> Previous</button>
            </div>
            <div class="col-5 col-sm-5 text-dark" >
              
            <button id="next-button" class="btn btn-custom" style="justify-content: between;display:none">
                next <i class="fas fa-arrow-right"></i>
            </button>
                
            </div>  
        </div>
    </div>
</footer>
</div>
        {{-- modal ulasan --}}
        <div class="modal fade" id="modalUlasan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        
                        <h5 class="modal-title" id="modalUlasanLabel"><i class="fas fa-check-circle" title="Data"></i> Ulasan</h5>
                        
                        
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="edClose"></button>
                       
                        
                    </div>
                    <div class="modal-body">
                        {{-- <p class="modal-title text-center"><u><b>Click jumlah bintang dan isikan ulasan :D</b></u></p> --}}
                        <form class="needs-validation" novalidate action="#" id="ulasanForm">

                            {{-- header --}}
                            {{-- <input type="hidden" class="form-control" id="kd_training" name="kd_training"> --}}

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 mt-3">
                                        {{-- <label class="form-label" for="rating">Rating</label> --}}
                                        <div class="rating" id="rating" name="rating">
                                            <i class="fas fa-star" data-value="1"></i>
                                            <i class="fas fa-star" data-value="2"></i>
                                            <i class="fas fa-star" data-value="3"></i>
                                            <i class="fas fa-star" data-value="4"></i>
                                            <i class="fas fa-star" data-value="5"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="ulasan">Ulasan</label>
                                        <textarea class="form-control" id="ulasan" name="ulasan" rows="5" placeholder="Tuliskan Ulasan Disini" required></textarea>
                                    </div>
                                </div>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- end modal ulasan --}}

        <!-- END layout-wrapper -->


        <!-- Right Sidebar -->
        @include('components.rightbar')

        {{-- alert toast --}}
        @include('components.toast')

        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        @include('components.script')
        {{-- <script src="assets/libs/dropzone/min/dropzone.min.js"></script> --}}

        <script>
            var kdTraining = "{{ $kd_training }}";
            var kdEventTraining="{{ $kd_event_training }}";
            var user_selesai_pre_test = null;
            var user_selesai_post_test = null;
            var rating_user = null;
            $(document).ready(function() {
                var materials = [];
                var status;
                var jawabanPreTest;
                var currentMaterialIndex = 0;

                loadMaterials(kdTraining,kdEventTraining);

                //handle rating star
                $('.rating .fa-star').on('click', function() {
                    rating = $(this).data('value');
                    $('.rating .fa-star').removeClass('checked');
                    for (let i = 1; i <= rating; i++) {
                        $(`.rating .fa-star[data-value=${i}]`).addClass('checked');
                    }
                });

                $('#ulasanForm').on('submit', function(e) {
                    e.preventDefault();
                    let ulasan = $('#ulasan').val();

                    var url = "{{route('ulasan.save')}}"
                    $.ajax({
                        url: url
                        , method: 'POST'
                        , data: {
                            kd_training: kdTraining
                            , rating: rating
                            , ulasan: ulasan
                        }
                        , success: function(response) {
                            if (response.code == "200") {
                                $('#modalUlasan').modal('hide');
                                alert("Ulasan Berhasil di kirim")
                                // Swal.fire({
                                //     icon: 'success'
                                //     , title: 'Ulasan Berhasil di kirim'
                                //     , text: 'Terima kasih atas ulasan anda!'
                                //     , confirmButtonText: 'OK'
                                // });
                                window.location.reload();
                            } else {
                                Swal.fire({
                                    icon: 'failed'
                                    , title: 'Ulasan Gagal di kirim'
                                    , text: 'Ada kesalahan pada server'
                                    , confirmButtonText: 'OK'
                                });
                            }
                            
                        }
                    })
                });

            });


            function loadMaterials(kdTraining,kdEventTraining) {
                // console.log(kdEventTraining)
                $.ajax({
                    type: "GET"
                    , url: '{{route("materi.all")}}' + `?kd_training=${kdTraining}&kd_event_training=${kdEventTraining}`
                    , success: function(response) {
                        
                        id = response[0].id;
                        id_peserta_training = response[0].id_peserta_training;
                        status = response[0].status;
                        jawabanPreTest = response[0].jawaban_pre_test;
                        soalPreTest = response[0].document_pre_test;
                        soalPostTest = response[0].document_post_test;
                        TrainingKode = response[0].kd_training;
                        event_training=response[0].kd_event_training;
                        user_selesai_pre_test = response[0].user_selesai_pre_test;
                        user_selesai_post_test = response[0].user_selesai_post_test;
                        rating_user = response[0].feedback_training

                        if(response[0].feedback_training.length != 0){
                            catatan = response[0].feedback_training[0].catatan;
                        }else{
                            catatan = null;
                        }
                        // console.log(catatan);
                        materials = response;
                        setupMaterialList();
                    }
                });
            }
            // <li class="${status === 'Proses' ? (index === 0 ? 'active' : 'unlocked') : 'locked'}">
            function setupMaterialList() {
                var materialList = $('.material-list');
                // var rule = $('#material-contents');
                materialList.append(`
                 <div class="sidebarMateri" onclick="openPreTest('${kdEventTraining}')"><button id="preTest" class="btn btn-custom"> <i class="fas fa-clipboard"></i>
                Pre-Test Objective</button></div>
                 <div class="sidebarMateri" style="cursor:default;"><button  class="btn btn-custom" style="cursor:default;"> <i class="fas fa-${user_selesai_pre_test ? 'arrow-down' : 'lock'}"></i>
                Materi</button></div>
                ${materials.map((material, index) => `
                    <li class="${user_selesai_pre_test ? 'access' : 'locked' }" data-id="${material.id}">
                        <i class="fas fa-${user_selesai_pre_test ? (material.history_activity_training ? 'check' : material.tipe_materi == "document" ? "file-pdf":"play") : 'lock'} active"></i>
                        <span style="padding-left: 10px;"> Materi ${index+1}</span>
                    </li>
                `).join('')}

                    <div class="sidebarMateri" onclick="openPostTest('${kdEventTraining}')"><button id="preTest" class="btn btn-custom"> <i class="fas fa-${user_selesai_pre_test ? 'clipboard' : 'lock'}"></i>
                    Post-Test Objective</button></div>
                    <div class="sidebarMateri" onclick="FinalProject('${kdEventTraining}')"><button id="FinalProject" class="btn btn-custom"> <i class="fas fa-${user_selesai_pre_test ? 'clipboard' : 'lock'}"></i>
                    Final Project</button></div>
                    <div class="sidebarMateri" onclick="openRating('${kdEventTraining}')"><button id="open_rating" class="btn btn-custom"> <i class="fas fa-${user_selesai_post_test ? 'star' : 'lock'}"></i>
                    Rating ${rating_user.length > 0 ? '(sudah)' : '(belum)'}</button></div>
                `);
                ruletraining();



                $('.material-list li').each(function(index) {
                    $(this).on('click', function() {
                        
                        idPeserta=id_peserta_training;
                        // console.log(idPeserta);
                        // console.log(id);
                        idMateri=$(this).data('id');
                        console.log(idMateri);
                        currentMaterialIndex = index;
                        if (user_selesai_pre_test) {
                            if (currentMaterialIndex === 0) {
                                $('#prev-button').hide();
                            } else {
                                $('#prev-button').fadeIn();
                            }
                            if (currentMaterialIndex === materials.length - 1) {

                                $('#next-button').hide();
                            } else {
                                $('#next-button').fadeIn();
                            }
                            $(this).find('i').addClass('fas fa-check');
                            updateContent(currentMaterialIndex);
                            //history Log
                            $.ajax({
                            type: "GET",
                            url: "{{ route('training.HistoryActivityTraining') }}?idPeserta=" + idPeserta + `&idMateri=` + idMateri + `&keterangan=MATERI`,
                            });
                            //history Log
                        } else {
                            swal.fire({
                                title: 'Kamu Belum Mengerjakan Pre Test'
                                , text: "Akses materi akan dibuka setelah mengerjakan Pre Test"
                                , icon: 'warning'
                                , confirmButtonColor: "#2ab57d"
                                , confirmButtonText: 'Click OK'
                            });
                        }
                    });
                });

            }
            $('#next-button').on('click', function() {
                    let jml_li = $('.material-list li').length;
                    if (currentMaterialIndex >= 0) {
                        $('#prev-button').fadeIn();
                    } else {
                        $('#prev-button').hide();
                    }
                    if (currentMaterialIndex === jml_li - 2) {

                        $('#next-button').hide();
                    } else {
                        $('#next-button').fadeIn();
                    }
                    idPeserta=id_peserta_training;
                    var idMateri=$($('.access')[currentMaterialIndex +1]).data('id');
                    $('.access').eq(currentMaterialIndex + 1).find('i').addClass('fas fa-check');

                    updateContent(currentMaterialIndex +1);
                    $.ajax({
                            type: "GET",
                            url: "{{ route('training.HistoryActivityTraining') }}?idPeserta=" + idPeserta + `&idMateri=` + idMateri + `&keterangan=MATERI`,
                            });
                    
                    currentMaterialIndex++;
                });

            $('#prev-button').on('click', function() {
                    let jml_li = $('.material-list li').length;
                    currentMaterialIndex--;
                    if (currentMaterialIndex === 0) {
                        $('#prev-button').hide();
                    } else {
                        $('#prev-button').fadeIn();
                    }
                    
                    if (currentMaterialIndex === jml_li - 1) {

                        $('#next-button').hide();
                    } else {
                        $('#next-button').fadeIn();
                    }
                    idPeserta=id_peserta_training;
                    var idMateri=$($('.access')[currentMaterialIndex]).data('id');
                    updateContent(currentMaterialIndex);
                    $.ajax({
                            type: "GET",
                            url: "{{ route('training.HistoryActivityTraining') }}?idPeserta=" + idPeserta + `&idMateri=` + idMateri + `&keterangan=MATERI`,
                            });
                
                });
            function updateContent(currentMaterialIndex) {
                $('.footer').show();
                // console.log(currentMaterialIndex);
                $('#material-title').html();
                $('#material-contents').html();
                // <li class="${jawabanPreTest ? 'access' : 'locked'}">
                // $('#material-title').html(materials[currentMaterialIndex].filename);

                if (materials[currentMaterialIndex].tipe_materi === 'video') {
                    let videoUrl = getEmbedUrl(materials[currentMaterialIndex].link);
                    $('#material-contents').html(`
                    <iframe width="100%" height="380" src="${videoUrl}" frameborder="0" allowfullscreen></iframe>
                    `);
                } else if (materials[currentMaterialIndex].tipe_materi === 'document' && materials[currentMaterialIndex].link === null){    
                    let fileUrl=materials[currentMaterialIndex].filename;
                    $('#material-contents').html(`
                    <iframe src="${fileUrl}" width="100%" height="600" style="border:none;"></iframe>
                    `);
                }else{
                    let fileUrl=materials[currentMaterialIndex].link;
                    $('#material-contents').html(`
                    <iframe src="${fileUrl}" width="100%" height="600" style="border:none;"></iframe>
                    `);
                }

                // $('#material-contents').html(materials[currentMaterialIndex].link ? ('<embed src="' + materials[currentMaterialIndex].link + '" width="auto" height="700px"/>') : '');
                $('.material-list li').removeClass('active').each(function(index) {
                    if (index === currentMaterialIndex) {
                        $(this).addClass('active');
                    }
                });

                // if (currentMaterialIndex === materials.length - 1) {
                //     markCompleted(currentMaterialIndex);
                // }
            }
            function BackToDashboard(){
                window.location.href = "{{ route('home') }}";
            }
            function ruletraining(){
                var rule = $('#material-contents').html('');
                $('.footer').hide();
                // rule.val('');
                url_foto = "assets/images/lms/rule1.svg";
                rule.append(`
                <div class="row px-3">
                    <div class="col-6 text-center">
                        <img src="{{ asset('${url_foto}') }}" class="img-fluid" style="width:90%;" />
                    </div>
                    <div class="col-6">
                        <h1 class="text-info fw-bold text-center pb-3">Peraturan Training</h1> 
                        <ul>
                            <li>Sebelum mengakses materi kamu <b>wajib mengerjakan pre test</b> terlebih dahulu</li>
                            <li>Halaman Ini hanya bisa di akses dalam rentang waktu event training yang telah ditentukan, Harap Selesaikan Training dalam rentang waktu tersebut</li>
                            <li>Setelah melakukan post test, kamu dapat mengakses riwayat training pada menu riwayat training</li>
                        </ul>
                    
                    </div>
                </div>        
                `);
            }

            function getEmbedUrl(youtubeUrl){
                console.log(youtubeUrl);
                let videoId=youtubeUrl.split('v=')[1];
                let ampersandPosition=videoId.indexOf('&');
                if(ampersandPosition !== -1){
                    videoId=videoId.substring(0,ampersandPosition);
                }
                return `https://www.youtube.com/embed/${videoId}`;
            }

            function preTestDownload(path){
                window.location.href=`/downloadPreTestFile/${path}`;
            }
            function postTestDownload(path){
                window.location.href=`/downloadPostTestFile/${path}`;
            }

            function reloadAfterPreTest(){    
                location.reload();
                $("#preTest").click();
            }

            function openPreTest(kd_event_training){
                window.open(`/view/test/${kd_event_training}/pre-test`);
            }

            function openPostTest(kd_event_training){
                if(user_selesai_pre_test){
                    window.open(`/view/test/${kd_event_training}/post-test`);
                }else{
                    swal.fire({
                                title: 'Kamu Belum Mengerjakan Pre Test'
                                , text: "Akses Post Test Dibuka setelah mengerjakan Pre Test"
                                , icon: 'warning'
                                , confirmButtonColor: "#2ab57d"
                                , confirmButtonText: 'Click OK'
                            });
                }
            }

            function openRating(kd_event_training){
                if(user_selesai_post_test){
                    $('#modalUlasan').modal('show');
                }else{
                    swal.fire({
                                title: 'Kamu Belum Mengerjakan Post Test'
                                , text: "Akses Post Test Dibuka setelah mengerjakan Pre Test"
                                , icon: 'warning'
                                , confirmButtonColor: "#2ab57d"
                                , confirmButtonText: 'Click OK'
                            });
                }
            }

            $(document).on('submit', 'form#upload_final_project', function(e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    console.log(formData);
                    var url = "{{ route('training.uploadFinalProject') }}";

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        async: true,
                        success: function(data) {
                            if (data.status == "true") {
                               
                                // $('#final_project').DataTable().ajax.reload();
                                $('#ket_success_toast').html("Sukses Upload Final Project!");
                                new bootstrap.Toast(toastsuccess).show();
                                $("#upload_final_project")[0].reset();

                                dataFinalProject(data.kd_event_training)

                            } else {
                                $('#ket_failed_toast').html(data.message);
                                new bootstrap.Toast(toastfailed).show();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error)
                            $('#ket_failed_toast').html(JSON.parse(xhr.responseText));
                            new bootstrap.Toast(toastfailed).show();
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                });

            function FinalProject(kd_event_training){
                var rule = $('#material-contents').html('');
                $('.footer').hide();

                rule.append(`
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="text-center"> 
                                    <form class="needs-validation" novalidate action="#" id="upload_final_project" enctype="multipart/form-data">
                                        <div class="fallback">
                                            <input type="hidden" id="load_event_training" name="kd_event_training" value="${kd_event_training}">
                                            <input id="file" name="file" type="file" multiple="multiple">
                                        </div>
                                        <div class="dz-message needsclick">
                                            <div>
                                                <i class="display-4 text-muted bx bx-cloud-upload"></i>
                                            </div>
                                            <h5>Upload Ulang Jika Ingin Mengganti Final Project.</h5>
                                        </div>

                                        <div class="text-center mt-3">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light">Kirim Project</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card-body">
                                <h4 class="card-title">Final Project</h4>
                                <div class="table-responsive">
                                    <table id="final_project" class="table table-borderless nowrap w-100">
                                        <thead class="table-info">
                                            <tr>
                                                <th>Nama Training</th>
                                                <th>Tanggal Mulai</th>
                                                <th>Pre Test</th>
                                                <th>Post Test</th>
                                                <th>Final Project</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            `);
                
                dataFinalProject(kd_event_training);
            }

            function dataFinalProject(kd_event_training) { 
                $.ajax({
                    url: "{{ route('training.FinalProject') }}?kd_event_training=" + kd_event_training,
                    method: 'GET',
                    success: function(response) {
                        console.log(response);

                        $('#final_project').DataTable({
                            data: [response],
                            destroy: true,
                            columns: [
                                {
                                    data: 'nama_training',
                                    name: 'nama_training',
                                    // title: 'Nama Training'
                                },
                                {
                                    data: 'tanggal_mulai',
                                    name: 'tanggal_mulai'
                                },
                                {
                                    data: 'nilai_pre_test',
                                    name: 'nilai_pre_test'
                                },
                                {
                                    data: 'nilai_post_test',
                                    name: 'nilai_post_test'
                                },
                                {
                                    data: 'preview_url',
                                    name: 'preview',
                                    render: function(data, type, row) {
                                        if (data) {
                                            return `<a href="${data}" target="_blank"><i class="fas fa-link" title="Preview"></i> Preview</a>`;
                                        } else {
                                            return 'Belum Dikirim';
                                        }
                                    }
                                }
                            ]
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error: " + status + " - " + error);
                    }
                });
            }

            // function dataFinalProject(kd_event_training)
            // { 
            //     $.ajax({
            //     url: "{{ route('training.FinalProject') }}?kd_event_training=" + kd_event_training
            //     , method: 'GET' 
            //     , success: function(response) {
            //         console.log(response);

            //         $('#final_project').DataTable({
            //             data: [response]
            //             , destroy: true
            //             , columns: [ {
            //                     data: 'nama_training'
            //                     , name: 'nama_training'
            //                 }
            //                 , {
            //                     data: 'tanggal_mulai'
            //                     , name: 'tanggal_mulai'
            //                 }
            //                 , {
            //                     data: 'preview'
            //                     , name: "preview"
            //                 }
                            
            //             ]
            //         });

            //         $('#final_project').DataTable().ajax.reload();

            //     }
            //     , error: function(xhr, status, error) {
            //         console.error("AJAX error: " + status + " - " + error);
            //     }
            // });
            // }

          
        </script>
</body>
</html>
