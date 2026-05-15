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
                        <h2 class="fw-bold mb-2 text-dark text-center">Login Account</h2>
                        {{-- <p class="text-dark mb-2 text-center">Please enter your email and password!</p> --}}
          
                        <form class="mt-2 pt-2" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-dark">Email</label>
                                {{-- <input type="text" class="form-control" id="username" placeholder="Enter username"> --}}
                                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Enter email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-dark">Password</label>
                                
                                <div class="input-group auth-pass-inputgroup">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Enter password" name="password" value="{{ old('password') }}" required aria-label="Password" aria-describedby="password-addon">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <button class="btn btn-info shadow-none ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                </div>
                            </div>
                            <div class="mb-3 pt-3">
                                <button class="btn btn-success w-100 waves-effect waves-light" type="submit">Login</button>
                            </div>
                        </form>
                  </div>
                </div>
              </div>
            </div>
          </section>
        


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
        <script src="{{asset('assets/js/pages/pass-addon.init.js')}}"></script>

    </body>

</html>
