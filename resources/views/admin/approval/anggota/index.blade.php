@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Approval Anggota')

@section('content_header')
    Approval Anggota
@stop

@section('content')

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

    </div>

    @include('admin.approval.anggota.modal')
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


        function EditData(id){
            $('#pesan').html("");
            $('#title').val("");
            $('#no_anggota').val("");
            $('#nama').val("");
            $('#nama').val("");


            var v_url = '{{ route('admin.approval.anggota.edit', ['anggotum' => '-id-']) }}';
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


                        $('#overlay-modal').hide();
                        $('#btnSimpanData').show();

                        $('#modal-title').html("Edit Data Anggota");
                        $('#paramhidden').html("<input type='hidden' name='simpan' value = 'edit'><input type='hidden' name='id' value = " + id + ">");

                        $('#no_anggota').val(data.no_anggota);
                        $('#nama').val(data.nama);
                        $('#email').val(data.email);
                        $('#telp').val(data.telp);
                        $('#alamat').val(data.alamat);
                        $('#lokasi_id').val(data.lokasi_id);
                        $('#status_id').val(data.status_id);
                        $('#is_anggota').val(data.is_anggota);

                        $('#lokasi_id').trigger('change');  
                        $('#status_id').trigger('change');  
                        $('#is_anggota').trigger('change');  


                        $('#btnSimpanData').prop('disabled', false);
                        $('#TambahData').modal('show');


                    } else {

                        html = " <div class='alert alert-danger alert-dismissable'>";
                        html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                        html = html +  pesan + " ";
                        html = html +  "</div>";

                        $('#pesan').html(html);
                        $('#btnSimpanData').prop('disabled', false);

                    }
                }
            })
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
                url  : '{{ route('admin.approval.anggota.loaddatatable') }}',
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
            var no_anggota = document.getElementById("no_anggota").value;
            if (no_anggota.trim() == "") {
                alert("Nomor Anggota harus diisi");
                return false;
            }

            var nama = document.getElementById("nama").value;
            if (nama.trim() == "") {
                alert("nama harus diisi");
                return false;
            }

            var email = document.getElementById("email").value;
            if (email.trim() == "") {
                alert("email harus diisi");
                return false;
            }

            var telp = document.getElementById("telp").value;
            if (telp.trim() == "") {
                alert("telepon harus diisi");
                return false;
            }
            var lokasi_id = document.getElementById("lokasi_id").value;
            if (lokasi_id.trim() == "") {
                alert("lokasi harus diisi");
                return false;
            }
            var status_id = document.getElementById("status_id").value;
            if (status_id.trim() == "") {
                alert("status harus diisi");
                return false;
            }
            var confirmation = confirm(" @lang('global.app_confirm') ");
            if (!confirmation) {
                return false;
            }



            $('#overlay-modal').show();
            $('#btnSimpanData').hide();


            var datanya = $("#form_data").serialize();
            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.approval.anggota.store') }}',
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

                        if (data == 0) {
                            html = html +  'Data berhasil disimpan' ;
                        } else {
                            html = html +  'Data berhasil diupdate' ;

                        }

                        html = html +  "</div>";
                        $('#pesan').html(html);

                        $('#tablearea').html(data.table);
                        $('#pagination').html(data.pagination);
                        $('#TambahData').modal('hide');


                    } else {

                        var errorInfo = data.errorInfo;
                        var msgErr = errorInfo[2];

                        html = " <div class='alert alert-danger alert-dismissable'>";
                        html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                        html = html +  pesan + " " + msgErr ;
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