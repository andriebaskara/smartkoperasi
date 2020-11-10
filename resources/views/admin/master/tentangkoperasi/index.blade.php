@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Tentang Koperasi')

@section('content_header')
    Tentang Koperasi
@stop

@section('content')

    <div class="card card-primary">
        <div class="card-header">
            @lang('global.app_list')
        </div>

        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Latar</td>
                        <td>
                            <button class='btn btn-xs btn-warning' id='btnView' onclick="Tampilkan('latar')">View File</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Tujuan</td>
                        <td>
                            <button class='btn btn-xs btn-warning' id='btnView' onclick="Tampilkan('tujuan')">View File</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Visi & Misi</td>
                        <td>
                            <button class='btn btn-xs btn-warning' id='btnView' onclick="Tampilkan('visi')">View File</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Struktur</td>
                        <td>
                            <button class='btn btn-xs btn-warning' id='btnView' onclick="Tampilkan('struktur')">View File</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Legalitas</td>
                        <td>
                            <button class='btn btn-xs btn-warning' id='btnView' onclick="Tampilkan('legalitas')">View File</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Manfaat</td>
                        <td>
                            <button class='btn btn-xs btn-warning' id='btnView' onclick="Tampilkan('manfaat')">View File</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="overlay" id="overlaytablearea"  style="display:none">
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>

    </div>

    <div class="modal fade" id="ListData">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title-list">List Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">

                    <div class="row" id="areadata">

                    </div>

                </div>

                <div class="modal-footer">
                    <span class='pull-left'>
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    </span>
                    <span class='pull-right'>

                        <i id='overlay-modal' class="fas fa-2x fa-sync-alt fa-spin" style="display:none"></i>
                        <button type="button" id="btnSimpanData" class="btn btn-primary" onclick="TambahData()">Tambah Data</button>
                    </span>

                    <br>
                    <p></p>
                    <div  id='pesan'></div>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

<div class="modal fade" id="TambahGambar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title-gambar">Gambar</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <form enctype="multipart/form-data" method="post" id="FormTambahGambar">
                <div class="row">
                    <div class="col-xs-12 form-group">
                        <img class="img-fluid" src="" id="showgambar" style="max-width:400px;max-height:400px;float:left;" />
                    
                        <div class="input-group">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image_file" name="image_file[]" style="float:left; width: 300px">
                            <label class="custom-file-label" for="file">Pilih file</label>
                          </div>
                        </div>


                    </div>
                    <div id="paramhiddengambar">
                    </div>
                </div>
                </form>
              
                <div  id='pesan_tambah_gambar'></div>

            </div>

            <div class="modal-footer justify-content-between">
                <span class='pull-left'>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </span>

                <span class='pull-right'>

                    <i id='overlay-modal-gambar' class="fas fa-2x fa-sync-alt fa-spin" style="display:none"></i>
                    <button type="button" id="btnSimpanGambar" class="btn btn-primary" onclick="SimpanGambar()">@lang('global.app_save')</button>
                </span>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal  https://paulund.co.uk/how-to-disable-enter-key-on-forms -->


@stop

@section('js') 
    <script>
        window.tipe_terpilih = "";
        $("form_data").keypress(function(e) {
          //Enter key
          if (e.which == 13) {
            return false;
          }
        });


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

        function Tampilkan(tipe){
            tipe_terpilih = tipe;
            $('#overlaytablearea').show();

            var v_url = '{{ route('admin.master.tentangkoperasi.tampilfile', ['id' => '-id-']) }}';
            v_url = v_url.replace('-id-', tipe);
            $.ajax({
                type : 'GET',
                url  : v_url,
                data : '',
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    $('#overlaytablearea').hide();
                  
                    if (success) {
                        var html = "";
                        html = SusunListData(data);

                        $('#modal-title-list').html(tipe);
                        $('#areadata').html(html);
                        $('#ListData').modal('show');
                        $('#ListData').modal('handleUpdate')

                    } else {
                        alert(pesan);
                    }
                }
            })   
        }

        function SusunListData(data) {
            var html = "";
            var listfile = data.listfile;
            var jumlahdata = data.jumlahdata;


            $.each(listfile, function(key, value) {

                html = html +  "<div class='col-md-6'>";
                html = html +  "    <div class='card card-primary'>";
                html = html +  "        <div class='card-header'>";
                html = html +  "            Urutan " + key;

                html = html +  "            <div class='card-tools'>";


                if (key  > 1 ) {
                    html = html +  "                <button type='button' class='btn btn-tool' data-toggle='tooltip' title='Naikkan Urutan' data-widget='chat-pane-toggle'>";
                    html = html +  "                    <i class='fas fa-2x fa-arrow-alt-circle-up'  onclick=\"NaikkanGambar(" + key + ")\"></i></button>";

                }

                if (key < jumlahdata) {

                    html = html +  "                <button type='button' class='btn btn-tool' data-toggle='tooltip' title='Turunkan Urutan' data-widget='chat-pane-toggle'>";
                    html = html +  "                    <i class='fas fa-2x fa-arrow-alt-circle-down'  onclick=\"TurunkanGambar(" + key + ")\"></i></button>";
                }

                html = html +  "                <button type='button' class='btn btn-tool' data-toggle='tooltip' title='Hapus Gambar' data-widget='chat-pane-toggle'>";
                html = html +  "                    <i class='fas fa-2x fa-trash-alt'  onclick=\"HapusGambar(" + key + ")\"></i></button>";
                html = html +  "            </div>";
                html = html +  "        </div>";

                html = html +  "        <div class='card-body'>";
                html = html +  "            <img class='img-fluid' src='" + value + "'/>";
                html = html +  "        </div>";
                html = html +  "    </div>";
                html = html +  "</div>";

            });

            return html;
        }


        function TambahData() {

            $('#showgambar').attr('src', '');
            $('#paramhiddengambar').html("");
            $('#btnSimpanGambar').prop('disabled', false);
            $('#overlay-modal-gambar').hide();
            $('#btnSimpanGambar').show();
            $('#pesan_tambah_gambar').html('');
            $('#TambahGambar').modal('show');
        }

        function Update2(tipe){
            $('#modal-title').html("Update file " + tipe );
            $('#paramhidden').html("<input type='hidden' name='tipe' value = '"+ tipe +"'>");
            $('#pesan').html("");
            $('#TambahData').modal('show');
        }

        function Tampilkanold(id){

            var v_url = '{{ route('admin.master.tentangkoperasi.tampilfile', ['id' => '-id-']) }}';
            v_url = v_url.replace('-id-', id);
            window.open(v_url, '_blank');


            return;

        }
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
            formData.append("gambar", image_file);
            formData.append("tipe", tipe_terpilih);
            formData.append("_token", _token);

            $.ajax({
              url: '{{ route('admin.master.tentangkoperasi.simpangambar') }}',
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

                        var html = "";
                        html = SusunListData(data);
                        $('#areadata').html(html);
                        $('#ListData').modal('handleUpdate')
                    } else {

                        alert(pesan);
                    }
              }
            });
        }

        function HapusGambar(urutan)
        {
            var confirmation = confirm("Apakah yakin menghapus gambar ?");

            if (!confirmation) {
                return false;
            }

            var datanya = "tipe=" + tipe_terpilih + "&_token=" + _token + "&urutan=" + urutan;
            $.ajax({
              url: '{{ route('admin.master.tentangkoperasi.hapusfile') }}',
              type: 'POST',
              data: datanya,
              success: function (rv) {
                    var pesan = rv.message;
                    var success = rv.success;
                    var data = rv.data;

                    if (success) {
                        $('#TambahGambar').modal('hide');

                        var html = "";
                        html = SusunListData(data);
                        $('#areadata').html(html);
                        $('#ListData').modal('handleUpdate')
                    } else {

                        alert(pesan);
                    }
              }
            });
        }

        function NaikkanGambar(urutan)
        {
            var datanya = "tipe=" + tipe_terpilih + "&_token=" + _token + "&urutan=" + urutan;
            $.ajax({
              url: '{{ route('admin.master.tentangkoperasi.naikurutan') }}',
              type: 'POST',
              data: datanya,
              success: function (rv) {
                    var pesan = rv.message;
                    var success = rv.success;
                    var data = rv.data;

                    if (success) {
                        $('#TambahGambar').modal('hide');

                        var html = "";
                        html = SusunListData(data);
                        $('#areadata').html(html);
                        $('#ListData').modal('handleUpdate')
                    } else {

                        alert(pesan);
                    }
              }
            });
        }

        function TurunkanGambar(urutan)
        {
            var datanya = "tipe=" + tipe_terpilih + "&_token=" + _token + "&urutan=" + urutan;
            $.ajax({
              url: '{{ route('admin.master.tentangkoperasi.turunurutan') }}',
              type: 'POST',
              data: datanya,
              success: function (rv) {
                    var pesan = rv.message;
                    var success = rv.success;
                    var data = rv.data;

                    if (success) {
                        $('#TambahGambar').modal('hide');

                        var html = "";
                        html = SusunListData(data);
                        $('#areadata').html(html);
                        $('#ListData').modal('handleUpdate')
                    } else {

                        alert(pesan);
                    }
              }
            });
        }




    </script>
@endsection