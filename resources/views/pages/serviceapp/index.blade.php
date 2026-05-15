<!doctype html>
<html lang="en">

    @include('components.head')
    <style>
    .gradient-custom {
        background: #f0f8ff;
    }

    .nav-baru {
            background-color:rgb(0 135 255);
            border-right:1px solid rgb(0 135 255);
            max-height:74px !important;
            margin-left:-10px;
        }

    @media only screen and (max-width: 600px) {
        .nav-baru {
            background-color:rgb(0 135 255);
            border-right:1px solid rgb(0 135 255);
            max-height:74px !important;
            margin-left:-10px;
        }
    }
    @media only screen and (max-width: 800px) {
        .nav-baru {
            background-color:rgb(0 135 255);
            border-right:1px solid rgb(0 135 255);
            max-height:72px !important;
            margin-left:-10px;
        }
    }
    </style>
    <body class="gradient-custom">
    <!-- <body data-layout="horizontal"> -->
        <div class="container">
            <header id="page-topbar" style="background-color:#189dfb;">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box nav-baru" style="background-color:#189dfb;border-right:1px solid #189dfb;max-height:70px;margin-left:-10px;">
                            <a href="{{route('home')}}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{asset('assets/images/logo-ma-small.png')}}" alt="" height="30">
                                </span>
                                <span class="logo-lg" style="padding:0px;border-radius:5px;">
                                    <img src="{{asset('assets/images/lms/logo-menara-horizontal.png')}}" alt="" style="width:86%;height:60%;"> 
                                </span>
                            </a>
            
                            {{-- <a href="{{route('home')}}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{asset('assets/images/logo-ma-small.png')}}" alt="" height="30">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('assets/images/logo-ma-big.png')}}" alt="" height="24"> <span class="logo-txt text-white"></span>
                                </span>
                            </a> --}}
                        </div>
            
                        {{-- <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                            <i class="fa fa-fw fa-bars text-white"></i>
                        </button> --}}
            
                        <!-- App Search-->
                        <form class="app-search">
                            <div class="position-relative">
                                <h3 class="text-white" style="font-weight: bold;margin-left:20px;padding-top:10px;">PILIH APLIKASI</h3>
                            </div>
                        </form>
                    </div>
            
                    <div class="d-flex">
            
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:#189dfb;">
                            @php
                                $foto = Auth::user()->karyawan->foto;
                            @endphp
                                <img class="rounded-circle header-profile-user" id="getFoto" src="{{asset('storage/karyawan/'.$foto)}}" style="padding:2px;background-color:#f5f5f5 !important;">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium text-white">{{Auth::user()->name}}</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block text-white"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a class="dropdown-item" href="{{ route('profile.index') }}"><i class="mdi mdi mdi-face-man font-size-16 align-middle me-1"></i> Edit Profile</a>
                                {{-- <a class="dropdown-item" href="auth-lock-screen.html"><i class="mdi mdi-lock font-size-16 align-middle me-1"></i> Lock Screen</a> --}}
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();">
                                 {{ __('Logout') }}
                             </a>
            
                             <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                 @csrf
                             </form>
                            </div>
                        </div>
            
                    </div>
                </div>
            </header>
            
            <div class="row d-flex justify-content-center align-items-center" style="margin-top:120px;">
                @if(count($service_app) == 0)
                    <div class="col-12 text-center">
                        <h2>Akun Ini Belum Terdaftar Pada Aplikasi Apapun, Silahkan Hubungi Admin</h2>
                        <div>
                            <img src="{{asset('assets/images/error-img.png')}}" alt="" class="img-fluid">
                        </div>
                    </div>
                @else
                    @foreach($service_app as $key => $value)
                        @if($value->active == "t")
                            <div class="col-6 col-lg-4 col-md-4">
                                <a href="{{ route('service.app.pilih', $value->kd_service_apps) }}">
                                    <div class="card" style="background:{{$value->warna}};border-radius:10px;">
                                        <div class="card-body text-center">
                                            <i class="{{$value->icon}} text-white" style="font-size: 10vh;"></i>
                                            <h2 class="text-white mt-2">{{strtoupper($value->kd_service_apps)}}</h2>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endforeach
                @endif
              </div>
    </div>
        
          <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
        <script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>
          <!-- pace js -->
        {{-- <script src="{{asset('assets/libs/pace-js/pace.min.js')}}"></script> --}}

    </body>

</html>
