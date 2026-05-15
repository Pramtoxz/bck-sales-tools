<script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
<script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>


<!-- pace js -->
{{-- <script src="{{asset('assets/libs/pace-js/pace.min.js')}}"></script> --}}

<!-- datepicker js -->
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>

<!-- Plugins js-->
<script src="{{asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- dashboard init -->
<script src="{{asset('assets/js/app.js')}}"></script>

<script src="{{asset('assets/js/code.js')}}"></script>

{{-- datatable --}}
<!-- Required datatable js -->
<script src="{{asset('assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>

<!-- Responsive examples -->
{{-- <script src="{{asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script> --}}

<!-- Sweet Alerts js -->
<script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>

<!-- twitter-bootstrap-wizard js -->
<script src="{{asset('assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js')}}"></script>
<script src="{{asset('assets/libs/twitter-bootstrap-wizard/prettify.js')}}"></script>

<!-- form wizard init -->
<script src="{{asset('assets/js/pages/form-wizard.init.js')}}"></script>

<script src="{{asset('assets/js/pages/form-wizard2.init.js')}}"></script>

{{-- <script src="{{asset('assets/js/pages/form-advanced.init.js')}}"></script> --}}


  {{-- <!-- ckeditor -->
  <script src="{{asset('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js')}}"></script>
  <script src="{{asset('assets/vendor/ckeditor5.js')}}"></script>
  <!-- init js -->
  <script src="{{asset('assets/js/pages/form-editor.init.js')}}"></script> --}}

<script>
    var toastsuccess = document.getElementById("toastSuccess");
    var toastfailed = document.getElementById("toastFailed");
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // flatpickr(".datepicker-basic",{defaultDate:new Date})
    });
    function preview(path){
        window.open(`{{asset('${path}')}}`)
    }
    function changeFormatDate(tanggal){
        convertTanggal = tanggal.split("-")
        if(convertTanggal.length == 3){
            return convertTanggal[2]+"-"+convertTanggal[1]+"-"+convertTanggal[0];
        }else{
            return tanggal;
        }
    }
</script>

