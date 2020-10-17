<div class="modal fade" id="TambahData">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="modal-title">Pengguna</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>


        <div class="modal-body">

            {!! Form::open(['method' => 'POST', 'id' => 'form_data']) !!}
            <div class="row">
                <div class="col-md-12 form-group">
                    <p><b>Nama Pengguna</b></p>
                    <input type="text" class="form-control" id='name' name='name' placeholder='Nama Pengguna', maxlength=100>
                </div>

                <div class="col-md-12">
                    <p><b>Alamat Email</b></p>
                    <input type="email" class="form-control" id='email' name='email' placeholder='Alamat Email Pengguna', maxlength=200>
                </div>


                <div id="paramhidden">
                </div>

            </div>

            {!! Form::close() !!}

            <br>
            <div id="pesan">
            </div>
        </div>

        <div class="modal-footer justify-content-between">
            <span class='pull-left'>
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </span>

            <span class='pull-right'>

                <i id='overlay-modal' class="fas fa-2x fa-sync-alt fa-spin" style="display:none"></i>
                <button type="button" id="btnSimpanData" class="btn btn-primary" onclick="SimpanData()">@lang('global.app_save')</button>
            </span>

        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="ModalRole">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="modal-title-role">Role</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>


        <div class="modal-body">
            {!! Form::open(['method' => 'POST', 'id' => 'form_data_role']) !!}
            <div class="row">

                @foreach ($roles as $role)


                    <div class="col-md-6">
                        <label>
                            <input type="checkbox" class="flat-red"  id="{!!str_replace(' ', '_', $role)!!}" name='role[]' value='{!!$role!!}'> {!!$role!!}
                        </label> <br>

                    </div>
                @endforeach

                <div id="paramhiddenrole">
                </div>

            </div>

            {!! Form::close() !!}

            <br>
            <div id="pesanrole">
            </div>
        </div>

        <div class="modal-footer justify-content-between">
            <span class='pull-left'>
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" id='btnTidakPilih' onclick="TidakPilihRole()" >@lang('global.app_selectnone')</button>
                <button type="button" class="btn btn-success" id='btnPilihSemua' onclick="PilihSemuaRole()" >@lang('global.app_selectall')</button>
            </span>

            <span class='pull-right'>
                <i id='overlay-modal-role' class="fas fa-2x fa-sync-alt fa-spin" style="display:none"></i>
                <button type="button" id="btnSimpanDataRole" class="btn btn-primary" onclick="SimpanRole()">@lang('global.app_save')</button>
            </span>

        </div>
      </div>
    </div>
</div>

