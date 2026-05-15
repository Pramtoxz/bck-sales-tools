<!doctype html>
<html lang="en">
    <head>
    @include('components.head')
    @stack('css-custom')
    </head>
    <body style="background-color:#fbfaff;">
    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
           @include('components.nav')

            <!-- ========== Left Sidebar Start ========== -->
          @include('components.sidebar')
            <!-- Left Sidebar End -->

            

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    @yield('content')
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->


                @include('components.footer')
            </div>
            <!-- end main content-->

        </div>
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
        @stack('script')
        
    </body>
</html>