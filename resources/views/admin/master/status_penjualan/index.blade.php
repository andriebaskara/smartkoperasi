@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Master Status Penjualan')

@section('content_header')
    Master Status Penjualan
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

    @include('admin.master.status_penjualan.modal')
@stop

@section('js') 
    <script>
        window.halaman_aktif = 1;
        $('#form_data').on('keyup keypress', function(e) {
          var keyCode = e.keyCode || e.which;
          if (keyCode === 13) { 
            e.preventDefault();
            return false;
          }
        });

        $(function () {

            $('#warna').select2({
                width: '100%',
                dropdownParent: $("#TambahData")
            })

        });



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

        function BuatBaru(){
            $('#paramhidden').html("<input type='hidden' name='simpan' value = 'baru'>");
            $('#modal-title').html("Buat Data Status Penjualan");
            $('#pesan').html("");
            $('#deskripsi').val("");

            $('#overlay-modal').hide();
            $('#btnSimpanData').show();

            $('#TambahData').modal('show');
        }

        function EditData(id, deskripsi, warna){
            $('#pesan').html("");
            $('#modal-title').html("Edit Data Status Penjualan");
            $('#paramhidden').html("<input type='hidden' name='simpan' value = 'edit'><input type='hidden' name='id' value = "+id+">");
            $('#deskripsi').val(deskripsi);
            $('#warna').val(warna);
            $('#warna').change();

            $('#overlay-modal').hide();
            $('#btnSimpanData').show();

            $('#TambahData').modal('show');
        }

        function HapusData(id){
            var confirmation = confirm(" @lang('global.app_confirm') ");
            if (!confirmation) {
                return false;
            }

            $('#overlaytablearea').show();

            var datanya = "halaman=" + halaman_aktif + "&_token=" + _token + "&id=" + id;

            var kriteria = document.getElementById("kriteria").value;
            if (kriteria.trim() != "") {
                datanya = datanya + "&kriteria=" + kriteria;
            }
            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.master.status_penjualan.hapusdata') }}',
                data : datanya,
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";
                    $('#overlaytablearea').hide();
                  
                    if (success) {
                        $('#tablearea').html(data.table);
                        $('#pagination').html(data.pagination);
                    } else {
                        alert(pesan);
                    }
                }
            })   

        }

        function HapusTerpilih(){
            if (confirm('  @lang('global.app_confirm')  ')) {

                $('#overlaytablearea').show();
                var ids = [];

                $("input[name='ids[]']:checked").each(function ()
                {
                    ids.push(parseInt($(this).val()));
                });

                var kriteria = document.getElementById("kriteria").value;

                $.ajax({
                    method: 'POST',
                    url: '{{ route('admin.master.status_penjualan.hapusdipilih') }}',
                    data: {
                        _token: _token,
                        halaman: halaman_aktif,
                        kriteria: kriteria,
                        ids: ids
                    }
                }).done(function (rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;
                    $('#overlaytablearea').hide();

                    if (success) {
                        $('#tablearea').html(data.table);
                        $('#pagination').html(data.pagination);
                    } else {
                        alert(pesan);
                    }
                });
            }

            return false;
        }

        function LoadData(halaman) {

            $('#overlaytablearea').show();

            var datanya = "halaman=" + halaman + "&_token=" + _token;

            var kriteria = document.getElementById("kriteria").value;
            if (kriteria.trim() != "") {
                datanya = datanya + "&kriteria=" + kriteria;
            }
            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.master.status_penjualan.loaddatatable') }}',
                data : datanya,
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";
                  
                    $('#overlaytablearea').hide();

                    if (success) {
                        halaman_aktif = halaman;
                        $('#tablearea').html(data.table);
                        $('#pagination').html(data.pagination);

                    } else {

                        alert(pesan);
                    }
                }
            })   

        }

        function SimpanData(){
            var deskripsi = document.getElementById("deskripsi");
            if (deskripsi.value.trim() == "") {
                alert("Nama Status penjualan belum diisi");
                deskripsi.focus();
                return;
            }

            var confirmation = confirm(" @lang('global.app_confirm') ");
            if (!confirmation) {
                return false;
            }

            $('#overlay-modal').show();
            $('#btnSimpanData').hide();


            var datanya = $("#form_data").serialize();
            datanya = datanya + "&halaman=" + halaman_aktif;
            
            var kriteria = document.getElementById("kriteria").value;
            if (kriteria.trim() != "") {
                datanya = datanya + "&kriteria=" + kriteria;
            }

            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.master.status_penjualan.store') }}',
                data : datanya,
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";

                    $('#overlay-modal').hide();
                    $('#btnSimpanData').show();


                    if (success) {
                        html = " <div class='alert alert-success alert-dismissable'>";
                        html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";

                        html = html +  data.pesan;

                        html = html +  "</div>";
                        $('#pesan').html(html);
                        $('#tablearea').html(data.table);
                        $('#pagination').html(data.pagination);

                        $('#TambahData').modal('hide');

                    } else {

                        html = " <div class='alert alert-danger alert-dismissable'>";
                        html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                        html = html +  pesan;
                        html = html +  "</div>";

                        $('#pesan').html(html);
                        $('#btnSimpanData').prop('disabled', false);
                    }
                }
            })
            return false ;
        }


    </script>
@endsection