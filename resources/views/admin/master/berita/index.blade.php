@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Master Berita')

@section('content_header')
    Master Berita
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

    @include('admin.master.berita.modal')
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
            $('#modal-title').html("Tambah Data Berita");
            $('#paramhidden').html("<input type='hidden' name='simpan' value = 'baru'>");
            $('#pesan').html("");
            $('#title').val("");
            $('#singkat').val("");
            $('#content').val("");
            $('#btnSimpanData').prop('disabled', false);
            $('#TambahData').modal('show');
        }


        function EditData(id){
            $('#pesan').html("");
            $('#title').val("");
            $('#singkat').val("");
            $('#content').val("");

            var v_url = '{{ route('admin.master.berita.edit', ['beritum' => '-id-']) }}';
            v_url = v_url.replace('-id-', id);


            $.ajax({
                type : 'GET',
                url  : v_url,
                data : '',
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";

                    if (success) {

                        $('#modal-title').html("Edit Data berita");
                        $('#paramhidden').html("<input type='hidden' name='simpan' value = 'edit'><input type='hidden' name='id' value = " + id + ">");

                        $('#title').val(data.title);
                        $('#content').val(data.content);
                        $('#singkat').val(data.singkat);
                        $('#mulai').val(data.mulai);
                        $('#selesai').val(data.selesai);
                        $('#tanggal').val(data.tanggal);

                        $('#btnSimpanData').prop('disabled', false);
                        $('#TambahData').modal('show');

                    } else {

                        html = " <div class='alert alert-danger alert-dismissable'>";
                        html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                        html = html +  pesan ;
                        html = html +  "</div>";

                        $('#pesan').html(html);
                        $('#btnSimpanData').prop('disabled', false);

                    }
                }
            })
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
                url  : '{{ route('admin.master.berita.hapusdata') }}',
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
                    url: '{{ route('admin.master.berita.hapusdipilih') }}',
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
                url  : '{{ route('admin.master.berita.loaddatatable') }}',
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
            var title = document.getElementById("title").value;
            if (title.trim() == "") {
                alert("title harus diisi");
                return false;
            }

            var content = document.getElementById("content").value;
            if (content.trim() == "") {
                alert("content harus diisi");
                return false;
            }
            var singkat = document.getElementById("singkat").value;
            if (singkat.trim() == "") {
                alert("deskripsi singkat harus diisi");
                return false;
            }
            var mulai = document.getElementById("mulai").value;
            if (mulai.trim() == "") {
                alert("mulai harus diisi");
                return false;
            }
            var selesai = document.getElementById("selesai").value;
            if (selesai.trim() == "") {
                alert("selesai harus diisi");
                return false;
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
                url  : '{{ route('admin.master.berita.store') }}',
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

        function UpdateGambar(id)
        {
            _berita_terpilih = id;
            var url = "{!! url("storage/berita") !!}";
            $('#showgambar').attr('src', url + "/Berita_" + id + ".jpg?" +  makeid(10));

            $('#paramhiddengambar').html("<input type='hidden' name='id' value = " + id + ">");
            $('#btnSimpanGambar').prop('disabled', false);
            $('#overlay-modal-gambar').hide();
            $('#btnSimpanGambar').show();
            $('#pesan_tambah_gambar').html('');


            $('#TambahGambar').modal('show');

        }

        function makeid(length) {
           var result           = '';
           var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
           var charactersLength = characters.length;
           for ( var i = 0; i < length; i++ ) {
              result += characters.charAt(Math.floor(Math.random() * charactersLength));
           }
           return result;
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#showgambar').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#image_file").change(function () {
            readURL(this);
        });

        function SimpanGambar()
        {

            var confirmation = confirm("Apakah yakin data sudah benar ?");

            if (!confirmation) {
                return false;
            }

            $('#overlay-modal-gambar').show();
            $('#btnSimpanGambar').hide();


            var image_file = $('#image_file').get(0).files[0];
            var formData = new FormData();
            formData.append("image", image_file);
            formData.append("id", _berita_terpilih);
            formData.append("_token", _token);

            $.ajax({
              url: '{{ route('admin.master.berita.simpangambar') }}',
              type: 'POST',
              data: formData,
              cache: false,
              contentType: false,
              processData: false,
              success: function (rv) {
                    var pesan = rv.message;
                    var success = rv.success;
                    var data = rv.data;

                    $('#overlay-modal-gambar').hide();
                    $('#btnSimpanGambar').show();

                    if (success) {
                        $('#TambahGambar').modal('hide');
                    } else {

                        alert(pesan);
                    }
              }
            });
        }


    </script>
@endsection