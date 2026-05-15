<header id="page-topbar" style="background-color:#189dfb;">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box" style="background-color:#189dfb;border-right:1px solid #189dfb;max-height:auto;margin-left:-1px;">
                <a href="{{route('home')}}" class="logo logo-dark">
                    <span class="logo-sm" style="margin-left:-7px;">
                        <img src="{{asset('assets/images/logo-ma-small.png')}}" alt="" height="30">
                    </span>
                    <span class="logo-lg" style="padding:0px;border-radius:5px;">
                        <img src="{{asset('assets/images/lms/logo-menara-horizontal.png')}}" alt="" style="width:86%;height:60%;"> 
                    </span>
                </a>

                {{-- <a href="{{route('home')}}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{asset('assets/images/logo-honda1.png')}}" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('assets/images/logo-ma-big.png')}}" alt="" height="24"> <span class="logo-txt text-white"></span>
                    </span>
                </a> --}}
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars text-white"></i>
            </button>

            <!-- App Search-->
            <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    @php
                        $nm_service = DB::table('service_apps')->where('kd_service_apps',Session::get('kd_service_apps'))->select("name_apps")->first();
                    @endphp
                    <h3 class="text-white" style="font-weight: bold;">{{$nm_service->name_apps ?? "Menara Agung"}}</h3>
                </div>
            </form>
        </div>

        <div class="d-flex">

            {{-- <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item" id="page-header-search-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="search" class="icon-lg text-white"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..." aria-label="Search Result">

                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>  --}}

            {{-- <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-lg layout-mode-dark text-white"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light text-white"></i>
                </button>
            </div> --}}
            <div class="dropdown d-lg-none">
                <button type="button" class="btn header-item" onclick="history.back()">
                    <i data-feather="arrow-left" class="icon-lg layout-mode-dark text-white"></i>
                </button>
            </div>
            <div class="dropdown d-lg-none">
                <button type="button" class="btn header-item" onclick="location.reload()">
                    <i data-feather="rotate-cw" class="icon-lg layout-mode-dark text-white"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon position-relative" id="page-header-notifications-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:#189dfb;">
                    <i data-feather="bell" class="icon-lg text-white"></i>
                    <span class="badge bg-danger rounded-pill">{{ auth()->user()->unreadNotifications()->count() }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> Notifications </h6>
                            </div>
                            <div class="col-auto">
                                <a href="#!" class="small text-reset text-decoration-underline"> Unread ({{ auth()->user()->unreadNotifications()->count() }})</a>
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;overflow:auto">
                        @php $count = 0 @endphp
                        @foreach(auth()->user()->unreadNotifications as $notification)
                         @if($count < 2)
                         <a href="{{ url($notification->data['url'] . '?id=' . $notification->id) }}" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{asset('assets/images/logo-ma-small.png')}}" class="rounded-circle avatar-sm" alt="user-pic">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $notification->data['title'] }}</h6>
                                    <div class="font-size-13 text-muted">
                                        {{-- <p class="mb-1">{{ ucwords($notification->data['message']) }}</p> --}}
                                        <p class="mb-1">Click Detail Notification</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>{{ $notification->created_at->diffForHumans() }}</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                            @php $count++ @endphp
                     @else
                                @break
                            @endif
                        @endforeach
                    </div>
                    <div class="p-2 border-top d-grid">
                        <a class="btn btn-sm btn-link font-size-14 text-center" href="{{ route('moreNotifications') }}">
                            <i class="mdi mdi-arrow-right-circle me-1"></i> <span>View More..</span> 
                        </a>
                    </div>
                </div>
            </div>

            {{-- <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item right-bar-toggle me-2">
                    <i data-feather="settings" class="icon-lg text-white"></i>
                </button>
            </div> --}}

            <div class="dropdown d-inline-block">
                @php
                    $foto = Auth::user()->karyawan->foto ?? '';
                @endphp
                <button type="button" class="btn header-item" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:#189dfb;">
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
