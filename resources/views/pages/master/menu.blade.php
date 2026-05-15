@extends('layouts.app')
@section('content') 
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Seting Menu User</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Menu</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius:5px;">
                <div class="card-body">
                    <form action="#" id="formSubmit">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="mb-2">
                                    <label class="form-label" for="kd_karyawan">Karyawan</label>
                                    <select class="form-control" id="kd_karyawan" name="kd_karyawan" required style="width:100%;">
                                        <option value="">Pilih Karyawan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="float-start">
                                    <button class="btn btn-success btn-md waves-effect waves-light" type="submit">Search</button>
                                </div>
                                <div class="float-end">
                                    <button class="btn btn-primary btn-md waves-effect waves-light" type="button" onclick="simpan()">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form action="">
                        <input type="hidden" name="kd_karyawan_check" id="kd_karyawan_check">
                        <div class="row" id="wadah_form_menu" style="display: none;">
                        @foreach($menu as $key => $value)
                            @if(count($value->master_menu) != 0)
                            <div class="col-4">
                                <div class="card">
                                    <div class="card-body">
                                        <p>* {{$value->name_apps}}</p>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="flexCheckDefault" onclick="pilihSemua('{{$value->kd_service_apps}}',this)" data-menu="{{$value->kd_service_apps}}">
                                            <input type="hidden" name="menu" value="{{$value->kd_service_apps}}">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Pilih semua
                                            </label>
                                        </div>
                                        @foreach($value->master_menu as $i => $master_menu)
                                        <div class="form-check mb-3" style="margin-left:20px;">
                                            <input class="form-check-input" type="checkbox" id="flexCheckDefault" value="{{$master_menu->id}}" data-menu="{{$value->kd_service_apps}}" id-menu="{{$master_menu->id}}" name="check_menu" >
                                            <label class="form-check-label" for="flexCheckDefault">
                                                {{$master_menu->nama_menu}}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>

    $(document).ready(function(){
        $("form#formSubmit").submit(function(e) {
            $('#kd_karyawan_check').val($('#kd_karyawan').val())
            $(`input[type="checkbox"]`).prop('checked', false);
                $('#wadah_form_menu').hide()
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                
                var url = "{{route('menu.user.view')}}"
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    async: true,
                    success: function(data) {
                        $('#wadah_form_menu').show()
                        data.forEach(value => {
                            $(`input[id-menu="${value.id_menu}"]`).prop('checked', true);
                        });
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
        getKaryawan()
    })
    function getKaryawan(){
        $.ajax({
            type: "GET",
            url: "{{ route('karyawan.all') }}",
            success: function(data, textStatus, jqXHR) {
                $('#kd_karyawan').select2({      
                    data: data,
                    theme: "bootstrap-5"
                });
            },
            error: function(data, textStatus, jqXHR) {},
        });     
    }
    function pilihSemua(namaMenu, param) {
        $(`input[data-menu="${namaMenu}"]`).prop('checked', param.checked);
        $(`input[data-semua-menu="${namaMenu}"]`).prop('checked', param.checked);
    }
    function simpan(){
        var menuChecked = [];
        $('input[name=check_menu]:checked').each(function () { 
            menuChecked.push($(this).val())
        });
        const formData = new FormData()
        var kd_karyawan = $('#kd_karyawan_check').val()
        formData.append('menu', menuChecked)
        formData.append('kd_karyawan', kd_karyawan)
        var url = "{{route('menu.user.simpan')}}"
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            async: true,
            success: function(data) {
                if(data.code == "200"){
                    $('#ket_success_toast').html(data.message)
                    new bootstrap.Toast(toastsuccess).show()
                }else{
                    $('#ket_failed_toast').html(data.message)
                    new bootstrap.Toast(toastfailed).show()
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
    }
</script>
@endpush

