
    <div class="modal fade" id="TambahData">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Tambah Data Divisi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">

                    {!! Form::open(['method' => 'POST', 'id' => 'form_data']) !!}
                    <div class="row">

                            <div class="col-md-6 form-group">
                                <p><b>No. Anggota</b></p>
                                <input type="text" class="form-control" id='no_anggota' name='no_anggota' placeholder='No Anggota', maxlength=20>
                            </div>

                            <div class="col-md-6 form-group">
                                <p><b>Nama</b></p>
                                <input type="text" class="form-control" id='nama' name='nama' placeholder='Nama', maxlength=20>
                            </div>

                            <div class="col-md-6 form-group">
                                <p><b>Email</b></p>
                                <input type="text" class="form-control" id='email' name='email' placeholder='email', maxlength=20>
                            </div>

                            <div class="col-md-6 form-group">
                                <p><b>Telepon</b></p>
                                <input type="text" class="form-control" id='telp' name='telp' placeholder='telp', maxlength=20>
                            </div>

                            <div class="col-md-12 form-group">
                                <p><b>Alamat</b></p>
                                <input type="text" class="form-control" id='alamat' name='alamat' placeholder='alamat', maxlength=200>
                            </div>

                            <div class="col-md-6 form-group">
                                <p><b>Lokasi</b></p>
                                {!! Form::select('lokasi_id', $lokasi, old('lokasi'), ['id' => 'lokasi_id', 'class' => 'form-control select2', 'required' => '', 'style' => 'width: 100%' ]) !!}
                            </div>

                            <div class="col-md-3 form-group">
                                <p><b>Status</b></p>
                                {!! Form::select('status_id', $status, old('status'), ['id' => 'status_id', 'class' => 'form-control select2', 'required' => '', 'style' => 'width: 100%' ]) !!}
                            </div>

                            <div class="col-md-3 form-group">
                                <p><b>Anggota</b></p>
                                <select id="is_anggota" class="form-control select2 select2-hidden-accessible" required="" style="width: 100%" name="is_anggota" tabindex="-1" aria-hidden="true">
                                    <option value="0"></option>
                                    <option value="1">Anggota</option>
                                </select>
                            </div>

                        <div id="paramhidden">
                        </div>

                    </div>

                    {!! Form::close() !!}
                    <div id="pesan">
                    </div>

                </div>




                <div class="modal-footer  justify-content-between">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                    
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
    <!-- /.modal  https://paulund.co.uk/how-to-disable-enter-key-on-forms -->

