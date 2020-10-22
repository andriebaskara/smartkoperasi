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
                            <button class='btn btn-xs btn-info' id='btnUpdate' onclick="Update('latar')">Update File</button>
                            <button class='btn btn-xs btn-warning' id='btnView' onclick="Tampilkan('latar')">View File</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Tujuan</td>
                        <td>
                            <button class='btn btn-xs btn-info' id='btnUpdate' onclick="Update('tujuan')">Update File</button>
                            <button class='btn btn-xs btn-warning' id='btnView' onclick="Tampilkan('tujuan')">View File</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Visi & Misi</td>
                        <td>
                            <button class='btn btn-xs btn-info' id='btnUpdate' onclick="Update('visi')">Update File</button>
                            <button class='btn btn-xs btn-warning' id='btnView' onclick="Tampilkan('visi')">View File</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Struktur</td>
                        <td>
                            <button class='btn btn-xs btn-info' id='btnUpdate' onclick="Update('struktur')">Update File</button>
                            <button class='btn btn-xs btn-warning' id='btnView' onclick="Tampilkan('struktur')">View File</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Legalitas</td>
                        <td>
                            <button class='btn btn-xs btn-info' id='btnUpdate' onclick="Update('legalitas')">Update File</button>
                            <button class='btn btn-xs btn-warning' id='btnView' onclick="Tampilkan('legalitas')">View File</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Manfaat</td>
                        <td>
                            <button class='btn btn-xs btn-info' id='btnUpdate' onclick="Update('manfaat')">Update File</button>
                            <button class='btn btn-xs btn-warning' id='btnView' onclick="Tampilkan('manfaat')">View File</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="TambahData">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Update Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">

                    {!! Form::open(['method' => 'POST', 'route' => ['admin.master.tentangkoperasi.simpanfile'],'files' => true]) !!}
                    <div class="row">

                            <div class="col-xs-12 form-group">
                                <input type="file" name='file'>
                            </div>
                        <div id="paramhidden">
                        </div>
                    </div>

                    {!! Form::submit("Simpan", ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}

                </div>

                <div class="modal-footer">
                    <span class='pull-left'>
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
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
    <!-- /.modal  https://paulund.co.uk/how-to-disable-enter-key-on-forms -->

@stop

@section('js') 
    <script>
       $("form_data").keypress(function(e) {
          //Enter key
          if (e.which == 13) {
            return false;
          }
        });

        function Update(tipe){
            $('#modal-title').html("Update file " + tipe );
            $('#paramhidden').html("<input type='hidden' name='tipe' value = '"+ tipe +"'>");
            $('#pesan').html("");
            $('#TambahData').modal('show');
        }


        function Tampilkan(id){

            var v_url = '{{ route('admin.master.tentangkoperasi.tampilfile', ['id' => '-id-']) }}';
            v_url = v_url.replace('-id-', id);
            window.open(v_url, '_blank');


            return;

        }



    </script>
@endsection