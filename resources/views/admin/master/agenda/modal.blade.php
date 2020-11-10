    <div class="modal hide fade" id="TambahData">
        <div class="modal-dialog  modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title">Agenda</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">

                {!! Form::open(['method' => 'POST', 'id' => 'form_data']) !!}

                              <div class="row">
                    <div class="col-md-12 form-group">
                        <p><b>Title</b></p>
                        <input type="text" class="form-control" id='title' name='title' placeholder='Title' maxlength=50 required>
                    </div>
                    <div class="col-md-4 form-group">
                        <p><b>Tanggal</b></p>
                        <input type="date" class="form-control" id='tanggal' name='tanggal' placeholder='tanggal' maxlength=50 required>
                    </div>
                    <div class="col-md-4 form-group">
                        <p><b>Mulai</b></p>
                        <input type="date" class="form-control" id='mulai' name='mulai' placeholder='mulai' maxlength=50 required>
                    </div>
                    <div class="col-md-4 form-group">
                        <p><b>Selesai</b></p>
                        <input type="date" class="form-control" id='selesai' name='selesai' placeholder='selesai' maxlength=50 required>
                    </div>
                    <div class="col-md-12 form-group">
                        <p><b>Content</b></p>
                        <textarea class="textarea" placeholder="Tulis Content disini" id="content" name="content" rows="10" 
                          style="width: 100%; height: 400px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                          </textarea>
                    </div>

                    <div id="paramhidden">
                    </div>

                </div>

                {!! Form::close() !!}
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
      <!-- /.modal -->


<div class="modal fade" id="TambahGambar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title-gambar">Gambar Agenda</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <form enctype="multipart/form-data" method="post" id="FormTambahGambar">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                Poster Agenda
                            </div>

                            <div class="card-body">
                               <img src="" id="showgambar" class="img-fluid" />
                            
                                <div class="input-group">
                                  <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image_file" name="image_file[]" style="float:left; width: 300px">
                                    <label class="custom-file-label" for="file">Pilih file</label>
                                  </div>
                                </div>
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
