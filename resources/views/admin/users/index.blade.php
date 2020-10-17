@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Master User')

@section('content_header')
    Pengaturan User
@stop


@section('content')

    <p>
            <button type="button" class="btn btn-success" onclick="BuatBaru()" id='btnBuatBaru'>@lang('global.app_create')</button>
    </p>

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_list')</h3>

        </div>

        <div class="card-body">

            <div class='row'>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="kriteria" placeholder="@lang('global.app_search')">
                        <span class="input-group-append">
                            <button type="button" class="btn btn-info btn-flat" onclick="LoadData(1)"><i class="fas fa-search"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-md-9">
                    <span class="float-right">
                        <div id='pagination'>
                            {!! $table['pagination'] !!}
                        </div>
                    </span>
                </div>        
            </div>

            <br>


            <div id='tablearea'>
                {!! $table['table'] !!}
            </div>
        </div>

        <div class="overlay" id="overlaytablearea"  style="display:none">
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>

        <div class="card-footer">
            <button type="button" class="btn btn-xs btn-danger" onclick="HapusTerpilih()" id='btnHapusTerpilih'>@lang('global.app_deleteselected')</button>
        </div>
    </div>

    @include('admin.users.modal')
    @include('admin.roles.modal')
@stop

@section('js') 
    <script>
        window.halaman_aktif = 1;

        function PilihSemuaData() {

            var chk;
            if(document.getElementById('PilihSemuaData').checked){
                $chk =  true;
            }else{
                $chk =  false;
            }

            var pilih = document.getElementsByName("ids[]");
            var jml=pilih.length;

            var b=0;
            for (b=0;b<jml;b++)
            {
                pilih[b].checked=$chk;
            }
        }

    </script>

    @include('admin.users.jsdata')
    @include('admin.users.jsrole')
    @include('admin.users.jspermission')

@endsection