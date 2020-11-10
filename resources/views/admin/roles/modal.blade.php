    <div class="modal fade" id="TambahDataPermission">
        <div class="modal-dialog  modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title-permission">Roles</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body">

                {!! Form::open(['method' => 'POST', 'id' => 'form_data_permission']) !!}
                <div class="row">
                    <div class="col-md-12 form-group" id='nama_role'>
                        <p><b>Nama Role</b></p>
                        <input type="text" class="form-control" id='name' name='name' placeholder='Nama Roles', maxlength=30>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                User & Security
                            </div>

                            <div class="card-body">

                                <label>
                                    <input type="checkbox" class="flat-red"  id='user_security' name='permission[]' value="user_security"> User & Roles
                                </label> <br>
                            </div>
                        </div>


                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                Approval
                            </div>

                            <div class="card-body">

                                <label>
                                    <input type="checkbox" class="flat-red"  id='approval_anggota' name='permission[]' value="approval_anggota"> Approval Anggota
                                </label> <br>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                Master Data
                            </div>

                            <div class="card-body">

                                <label>
                                    <input type="checkbox" class="flat-red"  id='tentang_koperasi' name='permission[]' value="tentang_koperasi"> Tentang Koperasi
                                </label> <br>

                                <label>
                                    <input type="checkbox" class="flat-red"  id='master_berita' name='permission[]' value="master_berita"> Master Berita
                                </label> <br>

                                <label>
                                    <input type="checkbox" class="flat-red"  id='master_agenda' name='permission[]' value="master_agenda"> Master Agenda
                                </label> <br>

                                <label>
                                    <input type="checkbox" class="flat-red"  id='master_lokasi' name='permission[]' value="master_lokasi"> Master Lokasi
                                </label> <br>

                                <label>
                                    <input type="checkbox" class="flat-red"  id='master_tipe_iuran' name='permission[]' value="master_tipe_iuran"> Master Tipe Iuran
                                </label> <br>
                                <label>
                                    <input type="checkbox" class="flat-red"  id='master_status_outlet' name='permission[]' value="master_status_outlet"> Master Status Outlet
                                </label> <br>
                                <label>
                                    <input type="checkbox" class="flat-red"  id='master_status_penjualan' name='permission[]' value="master_status_penjualan"> Master Status Penjualan
                                </label> <br>
                                <label>
                                    <input type="checkbox" class="flat-red"  id='master_status_produk' name='permission[]' value="master_status_produk"> Master Status Produk
                                </label> <br>
                                <label>
                                    <input type="checkbox" class="flat-red"  id='master_status_anggota' name='permission[]' value="master_status_anggota"> Master Status Anggota
                                </label> <br>
                                <label>
                                    <input type="checkbox" class="flat-red"  id='master_status_pengajuan' name='permission[]' value="master_status_pengajuan"> Master Status Pengajuan
                                </label> <br>
                                <label>
                                    <input type="checkbox" class="flat-red"  id='master_kategori' name='permission[]' value="master_kategori"> Master Kategori
                                </label> <br>
                                <label>
                                    <input type="checkbox" class="flat-red"  id='master_jenis_pembayaran' name='permission[]' value="master_jenis_pembayaran"> Master Jenis Pembayaran
                                </label> <br>

                            </div>
                        </div>

                    </div>

                    <div id="paramhiddenpermission">
                    </div>

                </div>

                {!! Form::close() !!}


                <div id="pesanpermission">
                </div>


            </div>



            <div class="modal-footer justify-content-between">
                <span class='pull-left'>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-warning" id='btnTidakPilih' onclick="TidakPilihPermission()" >@lang('global.app_selectnone')</button>
                    <button type="button" class="btn btn-success" id='btnPilihSemua' onclick="PilihSemuaPermission()" >@lang('global.app_selectall')</button>
                </span>

                <span class='pull-right'>

                    <i id='overlay-modal-permission' class="fas fa-2x fa-sync-alt fa-spin" style="display:none"></i>
                    <button type="button" id="btnSimpanDataPermission" class="btn btn-primary" onclick="SimpanDataPermission()">@lang('global.app_save')</button>
                </span>

            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

