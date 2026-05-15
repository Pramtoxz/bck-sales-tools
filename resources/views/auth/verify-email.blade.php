<!doctype html>
<html lang="en">

    @include('components.head')
    <style>
        .gradient-custom {
/* fallback for old browsers */
/* background: #6a11cb; */

/* Chrome 10-25, Safari 5.1-6 */
/* background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1)); */

/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
/* background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1)); */
background: lightblue url("/assets/images/lms/background.svg");
background-size: cover;
background-attachment: fixed;
background-repeat: no-repeat;
background-position: center;
    }
    </style>
    <body class="gradient-custom">
    <!-- <body data-layout="horizontal"> -->
        <section id="login_baru">
            <div class="container " style="padding-top:15vh;">
              <div class="row d-flex justify-content-center align-items-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                  <div class="card bg-white text-white" style="border-radius: 1rem;">
                    <div class="card-body px-4 px-lg-5 px-md-4">
                        <div class="mb-4 mb-md-4 text-center">
                            <a href="" class="d-block auth-logo">
                                <img src="{{asset('assets/images/lms/logo-menara-horizontal.png')}}" alt="" height="60">
                            </a>
                        </div>
                        div class="text-center">
                                            <div class="avatar-lg mx-auto">
                                                <div class="avatar-title rounded-circle bg-light">
                                                    <i class="bx bxs-envelope h2 mb-0 text-info"></i>
                                                </div>
                                            </div>
                                            <div class="p-2 mt-4 text-center">
                                                <h4 class="text-dark">Verify your email</h4>
                                                <p class="text-dark">Verification email <span class="fw-bold">{{Auth::user()->email}}</span></p>
                                                <div class="mt-4">
                                                    <form action="#" method="" id="formSubmit">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger w-10" id="btn_send">Send Link <i class="fas fa-location-arrow"></i></button>
                                                    </form>
                                                </div>
                                                <div class="mt-2">
                                                    <a href="{{route('home')}}" class="btn btn-success" id="btn_reload">Check Verification <i class="fas fa-check-circle"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pb-5 text-center">
                                            <p class="text-dark mb-0">Didn't receive an email ? <a class="text-danger" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                              document.getElementById('logout-form').submit();">
                                                 {{ __('Logout') }}
                                                </a> </p>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                        </div>
          
                  </div>
                </div>
              </div>
            </div>
          </section>
        

          @include('components.toast')
        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
        <script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>
        <!-- pace js -->
        {{-- <script src="{{asset('assets/libs/pace-js/pace.min.js')}}"></script> --}}
        <!-- password addon init -->
        <script>
            var toastsuccess = document.getElementById("toastSuccess");
            var toastfailed = document.getElementById("toastFailed");
            $("form#formSubmit").submit(function(e) {
                $('#btn_send').hide()
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                var url = "{{route('verification.send')}}"
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    async: true,
                    success: function(data) {
                        console.log(data)
                        if(data.code == "200"){
                            $('#ket_success_toast').html(data.message)
                            new bootstrap.Toast(toastsuccess).show();
                            $('#btn_send').show()
                            $('#btn_send').html("Resend Verify Email <i class='fas fa-location-arrow'></i>")
                        }
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
        </script>

    </body>

</html>

