{{-- toast --}}
<div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
    <div id="toastSuccess" class="toast overflow-hidden" role="alert" aria-live="assertive"
        aria-atomic="true" style="margin-top:4.8em;">
        <div class="toast-header bg-white">
            <img src="{{asset('assets/images/logo-ma-small.png')}}" alt="logo_ma" class="me-2" height="18">
            <strong class="me-auto">LMS-MA</strong>
            <small class="text-success"><i class="fas fa-check-circle"></i> Notifikasi</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
        <div class="toast-body bg-white" id="ket_success_toast">
        </div>
    </div>
</div>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
    <div id="toastFailed" class="toast overflow-hidden" role="alert" aria-live="assertive"
        aria-atomic="true" style="margin-top:4.8em;">
        
                <div class="toast-header bg-white">
                    <img src="{{asset('assets/images/logo-ma-small.png')}}" alt="logo_ma" class="me-2" height="18">
                    <strong class="me-auto">LMS-MA</strong>
                    <small class="text-danger"><i class="fas fa-times-circle" ></i> Notifikasi</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
                <div class="toast-body bg-white">
                    <span><span id="ket_failed_toast"></span></span>
                </div>
           
    </div>
</div>
{{-- end toast --}}