@php
   use App\Helper\Menu;
   $helper_menu = new Menu;
   $dataMenu = $helper_menu->getMenu(Session::get('kd_service_apps'));
   $created_at = array_column($dataMenu, 'created_at');
   array_multisort($created_at, SORT_ASC, $dataMenu);
@endphp
<style>
   
    .a_menu:hover {background-color: #0087ff;color:white !important!;}
    .a_menu:hover .a_info{color: white!important; }
    .a_menu {color:black !important;}
    .a_menu:hover .a_icon{color: white !important;}
    .a_icon{
        color: #0087ff !important;
    }
    .mm-active .active {
        color: #0087ff !important;
    }
    .mm-active .active i {
        color: #0087ff !important;
    }
    .mm-active>a {
        color: #0087ff !important;
    }
    .mm-active>a i {
        color: #0087ff !important;
    }
    .mm-active .active svg {
        color: #0087ff !important;
    }
</style>
{{-- width:241px !important; --}}
<div class="vertical-menu" style="background-color:aliceblue!important; ">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu" >
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                {{-- untuk get menu ALL --}}
                @php
                    $dataAll = DB::table("level1_menu")->where("kd_service_apps","ALL")->where('active','t')->orderBy('created_at','asc')->get();
                @endphp
                @foreach ($dataAll as $value)
                <li class="link">
                    <a href="{{ url($value->link) }}" class="a_menu" >
                        <i class="{{$value->icon}} a_icon"></i>
                        <span data-key="t-{{$value->nama_menu}}" class="a_info">{{$value->nama_menu}}</span>
                    </a>
                </li>
                @endforeach
                {{-- <li class="menu-title" data-key="t-menu">Menu</li> --}}
                @foreach($dataMenu as $value)
                        <li class="link">
                            <a href="{{ url($value['link']) }}" class="{{$value["level2_menu"] != null ? 'has-arrow': ''}} a_menu" >
                                <i class="{{$value['icon']}} a_icon"></i>
                                <span data-key="t-{{$value['nama_menu']}}" class="a_info">{{$value['nama_menu']}}</span>
                            </a>
                            @if($value["level2_menu"] != null)
                                <ul class="sub-menu" aria-expanded="false">
                                    @foreach($value['level2_menu'] as $menu2)
                                        <li>
                                            <a href="{{ url($menu2['link']) }}" class="a_menu">
                                                <i data-feather="circle" class="a_icon"></i>
                                                <span data-key="t-{{$menu2['nama_menu']}}" class="a_info">{{$menu2['nama_menu']}}</span>
                                            </a>
                                            @if($menu2["level3_menu"] != null)
                                                <ul class="sub-menu" aria-expanded="false">
                                                    @foreach($menu2["level3_menu"] as $menu3)
                                                        <li><a href="{{ url($menu3['link']) }}" data-key="t-{{$menu3['nama_menu']}}" class="a_menu">{{$menu3['nama_menu']}}</a></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                @endforeach

                {{-- <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="grid" class="text-dark"></i>
                        <span data-key="t-apps" class="text-dark">Data Master</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="/departement">
                                <span data-key="t-calendar" class="text-dark">Departement</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-email">Email</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="apps-email-inbox.html" data-key="t-inbox">Inbox</a></li>
                                <li><a href="apps-email-read.html" data-key="t-read-email">Read Email</a></li>
                            </ul>
                        </li>
                    </ul>
                </li> --}}
            </ul>

            {{-- <div class="card sidebar-alert border-0 text-center mx-4 mb-0 mt-5">
                <div class="card-body">
                    <img src="{{asset('assets/images/giftbox.png')}}" alt="">
                    <div class="mt-4">
                        <h5 class="alertcard-title font-size-16">Unlimited Access</h5>
                        <p class="font-size-13">Upgrade your plan from a Free trial, to select ‘Business Plan’.</p>
                        <a href="#!" class="btn btn-primary mt-2">Upgrade Now</a>
                    </div>
                </div>
            </div> --}}
        </div>
        <!-- Sidebar -->
    </div>
</div>
{{-- @push('script')
<script>
$(document).ready(function(){
    const newLi=$(`<li><a href="#">
                                
                                <span class="text-dark" style="font-weight:bold;">Admin Interface</span>
                            </a></li>`);
    $('#side-menu li:nth-child(1)').after(newLi);
        const newLiUser=$(`<li><a href="#">
                                <i data-feather="fas fa-home" class="text-danger"></i>
                                <span class="text-dark" style="font-weight:bold;">User Interface</span>
                            </a></li>`);
    $('#side-menu li:nth-child(5)').after(newLiUser);
});
</script>
@endpush --}}