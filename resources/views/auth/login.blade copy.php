<!doctype html>
<html lang="en">

    @include('components.head')

    <body>

    <!-- <body data-layout="horizontal"> -->
        <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-xxl-3 col-lg-4 col-md-5">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-75">
                                    <div class="mb-4 mb-md-4 text-center">
                                        <a href="index.html" class="d-block auth-logo">
                                            <img src="{{asset('assets/images/logo-ma-big.png')}}" alt="" height="50">
                                        </a>
                                    </div>
                                    <div class="auth-content my-auto">
                                        <div class="text-center">
                                            <h5 class="mb-0">Welcome Back !</h5>
                                            <p class="text-muted mt-2">Sign in to continue to LMS.</p>
                                        </div>
                                        <form class="mt-4 pt-2" method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                {{-- <input type="text" class="form-control" id="username" placeholder="Enter username"> --}}
                                                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Enter email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-grow-1">
                                                        <label class="form-label">Password</label>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <div class="">
                                                            <a href="#" class="text-muted">Forgot password?</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="input-group auth-pass-inputgroup">
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Enter password" name="password" value="{{ old('password') }}" required aria-label="Password" aria-describedby="password-addon">
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <button class="btn btn-info w-100 waves-effect waves-light" type="submit" style="background-color:#0087ff !important;">Log In</button>
                                            </div>
                                        </form>

                                        <div class="mt-5 text-center">
                                            <p class="text-muted mb-0">Don't have an account ? <a href="#"
                                                    class="text-info fw-semibold"> Please Contact Admin </a> </p>
                                        </div>
                                    </div>
                                    <div class="mt-4 mt-md-5 text-center">
                                        <p class="mb-0">Menara Agung © <script>document.write(new Date().getFullYear())</script> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end auth full page content -->
                    </div>
                    <!-- end col -->
                    @include('components.carousel-login')
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container fluid -->
        </div>
        


        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
        <script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>
        <!-- pace js -->
        <script src="{{asset('assets/libs/pace-js/pace.min.js')}}"></script>
        <!-- password addon init -->
        <script src="{{asset('assets/js/pages/pass-addon.init.js')}}"></script>

    </body>

</html>
