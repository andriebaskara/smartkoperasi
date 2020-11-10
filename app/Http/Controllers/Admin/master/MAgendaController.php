<?php

namespace App\Http\Controllers\Admin\Master;

use App\M_Agenda;
use App\Helpers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;

class MAgendaController extends Controller
{
    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('master_agenda'))
        {
            return redirect()->route('admin.');
        }

        $table = $this::datatable(null);

        return view('admin.master.agenda.index', compact('table'));
    }

    public function store(Request $request)
    { 
        $result = new \stdClass();
        if(!Auth()->user()->hasAnyPermission('master_agenda'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helpers::responseJson($pesan,"" );
        }

        $simpan = $request->input('simpan');

        DB::beginTransaction();

        try {        

            if ($simpan =='baru') {
                $id= 0;
                $agenda = M_Agenda::create($request->all());
                $pesan = "Data berhasil disimpan";

            } else {
                $id = $request->input('id');
        
                $agenda = M_Agenda::findOrFail($id);
                $agenda->update($request->all());
                $pesan = "Data berhasil diupdate";
        }

            $agenda->updated =  Helpers::generateUpdated();
            $agenda->save();
            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }

        $table = $this::datatable($request, $agenda);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }


    public function edit($id)
    {
        if(!Auth()->user()->hasAnyPermission('master_agenda'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helpers::responseJson($pesan,"" );
        }

        $agenda = M_Agenda::findOrFail($id);
        if (empty($agenda)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        return Helpers::responseJson(true, $agenda, "OK" );

    }

    public function hapusdata(Request $request)
    {

        if (! Gate::allows('master_agenda')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $id = $request->input('id');
        try {

            $agenda = M_Agenda::findOrFail($id);
        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if (empty($agenda)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        $agenda->delete();
        
        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdipilih(Request $request)
    {
        if (! Gate::allows('master_agenda')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        if ($request->input('ids')) {
            $entries = M_Agenda::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function loaddatatable(Request $request) {

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public static function formAction($data)
    {

        $form = "<button class='btn btn-xs btn-warning' id='btnEdit' onclick=\"UpdateGambar(".$data->id.")\">UpdateGambar</button> ";
        $form .= "<button class='btn btn-xs btn-info' id='btnEdit' onclick=\"EditData(".$data->id.")\">Edit</button> 
                <button class='btn btn-xs btn-danger' id='btnHapus' onclick=\"HapusData(".$data->id.")\">Hapus</button> ";
        return $form;
    }

    public function datatable($request, $dataproses = null)
    {

        if (!empty($request)) {
            $take = $request->input('take');
            $kriteria = $request->input('kriteria');
            $halaman = $request->input('halaman');
        }

        if (empty($halaman)) {$halaman = 1;}
        if (empty($take)) {$take = 30;}
        $skip = ($halaman - 1) * $take;
        $agenda = M_Agenda::where('id','!=', '0');

        if(!empty($kriteria)){
            $agenda = $agenda->where('deskripsi','LIKE','%'.$kriteria.'%');
        }

        $count = count($agenda->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $agenda = $agenda->skip($skip)->take($take)->get();

        $table = "<table class='table table-hover'>
                  <thead>
                    <tr>
                        <th style=\"text-align:center;\">
                            <input type=\"checkbox\" id=\"PilihSemuaData\" onclick=\"PilihSemuaData()\">
                            </th>
                      <th>Title</th>
                      <th>Tanggal</th>
                      <th>Mulai</th>
                      <th>Selesai</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";

        if (!empty($dataproses)) {

            $table .= "<tr>
                      <td align='center'>".Helpers::formCheckbox($dataproses->id)."</td>
                      <td align='left'>".$dataproses->title."</td>
                      <td align='left'>".$dataproses->tanggal."</td>
                      <td align='left'>".$dataproses->mulai."</td>
                      <td align='left'>".$dataproses->selesai."</td>

                      <td align='center'>".$this::formAction($dataproses)."</td>
                    </tr>
                ";
            $dataproses_id = $dataproses->id;
        } else {
            $dataproses_id = 0;
        }

        foreach ($agenda as $detail) {
            if ($detail->id != $dataproses_id) {

                $table .= "<tr>
                          <td align='center'>".Helpers::formCheckbox($detail->id)."</td>
                          <td align='left'>".$detail->title."</td>
                          <td align='left'>".$detail->tanggal."</td>
                          <td align='left'>".$detail->mulai."</td>
                          <td align='left'>".$detail->selesai."</td>
                          <td align='center'>".$this::formAction($detail)."</td>
                        </tr>
                    ";
            }
        }

        $table .= "</table>";
        $result = array(
            'table' => $table,
            'pagination' => $pagination,
        );        

        return $result;
    }

    public function simpangambar(Request $request) {

        try
        {

            $id = $request->input('id');
            $namafile = "agenda_".$id.".jpg";

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $image->storeAs(
                            'public/agenda', $namafile
                        );
            }
            return Helpers::responseJson( true, "OK" , "OK");

        } catch(\Exception $exception){
            return Helpers::responseJson( false, $exception , "Terdapat kesalahan data");
        }

    }

}
