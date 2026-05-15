{{-- <footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <p class="text-dark">Menara Agung © <script>document.write(new Date().getFullYear())</script></p>
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block text-dark">
                    LMS-V1</a>
                </div>
            </div>
        </div>
    </div>
</footer> --}}

<!-- menu bottom navigation -->
<div id="container" style="z-index: 99;">
    <nav class="d-md-none bottom_nav">
        
        @if(Session::get('kd_service_apps') == "LMS")
        <a href="{{ route('home') }}" class="bottom_nav__link {{ Request::segment(1) == '' ? 'aktif' : '' }}">
            <i class="fa fa-home"></i>
            <span class="bottom_nav__text">Home</span>
        </a>
        <a href="{{ route('jadwal_training.view') }}" class="bottom_nav__link {{ Request::segment(1) == 'jadwal' ? 'aktif' : '' }}">
            <i class="fa fa-clipboard-list"></i>
            <span class="bottom_nav__text">Jadwal</span>
        </a>
        <a href="{{ route('kurikulum.index') }}" class="bottom_nav__link {{ Request::segment(1) == 'kurikulum' ? 'aktif' : '' }}">
            <i class="fab fa-youtube"></i>
            <span class="bottom_nav__text">Training</span>
        </a>
        <a href="{{ route('riwayatUser.index') }}" class="bottom_nav__link {{ Request::segment(1) == 'riwayatUser' ? 'aktif' : '' }}">
            <i class="fas fa-book"></i>
            <span class="bottom_nav__text">Riwayat</span>
        </a>
        <a href="{{ route('profile.index') }}" class="bottom_nav__link {{ Request::segment(1) == 'profile' ? 'aktif' : '' }}">
            <i class="fa fa-user"></i>
            <span class="bottom_nav__text">Profile</span>
        </a>
        @endif
        @if(Session::get('kd_service_apps') == "ABSENSI")
        <a href="{{ route('home') }}" class="bottom_nav__link {{ Request::segment(1) == '' ? 'aktif' : '' }}">
            <i class="fa fa-home"></i>
            <span class="bottom_nav__text">Home</span>
        </a>
        <a href="{{ route('cuti.all') }}" class="bottom_nav__link {{ Request::segment(1) == 'cuti' ? 'aktif' : '' }}">
            <i class="fa fa-clipboard-list"></i>
            <span class="bottom_nav__text">Pengajuan</span>
        </a>
        <a href="{{ route('kalendercuti.get') }}" class="bottom_nav__link {{ Request::segment(1) == 'kalendercuti' ? 'aktif' : '' }}">
            <i class="fab fa-youtube"></i>
            <span class="bottom_nav__text">Kelender</span>
        </a>
        @endif
    </nav>
</div>