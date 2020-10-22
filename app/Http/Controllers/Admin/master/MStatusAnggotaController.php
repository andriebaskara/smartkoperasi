<?php

namespace App\Http\Controllers\Admin\Master;

use App\M_StatusAnggota;
use App\Helpers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;

class MStatusAnggotaController extends Controller
{
    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('master_status_anggota'))
        {
            return redirect()->route('admin.');
        }

        $xwarna = Helpers::warna();
        $warna = Array();

        foreach ($xwarna as $key => $value) {
            $warna[$key] = $key;
        }

        $table = $this::datatable(null);

        return view('admin.master.status_anggota.index', compact('table','warna'));
    }

    public function store(Request $request)
    { 
        $result = new \stdClass();
        if(!Auth()->user()->hasAnyPermission('master_status_anggota'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helpers::responseJson($pesan,"" );
        }

        $simpan = $request->input('simpan');

        DB::beginTransaction();

        try {        

            if ($simpan =='baru') {
                $id= 0;
                $status_anggota = M_StatusAnggota::create($request->all());
                $pesan = "Data berhasil disimpan";

            } else {
                $id = $request->input('id');
        
                $status_anggota = M_StatusAnggota::findOrFail($id);
                $status_anggota->update($request->all());
                $pesan = "Data berhasil diupdate";
        }

            $status_anggota->updated =  Helpers::generateUpdated();
            $status_anggota->save();
            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }

        $table = $this::datatable($request, $status_anggota);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }


    public function hapusdata(Request $request)
    {

        if (! Gate::allows('master_status_anggota')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $id = $request->input('id');
        try {

            $status_anggota = M_StatusAnggota::findOrFail($id);
        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if (empty($status_anggota)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        $status_anggota->delete();
        
        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdipilih(Request $request)
    {
        if (! Gate::allows('master_status_anggota')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        if ($request->input('ids')) {
            $entries = M_StatusAnggota::whereIn('id', $request->input('ids'))->get();

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

        $form = "<button class='btn btn-xs btn-info' id='btnEdit' onclick=\"EditData(".$data->id.",'".$data->deskripsi."','".$data->warna."')\">Edit</button> 
                <button class='btn btn-xs btn-danger' id='btnHapus' onclick=\"HapusData(".$data->id.")\">Hapus</button> ";
        return $form;
    }

    public static function WarnaLabel($deskripsi, $warnalabel, $warna)
    {

        if (array_key_exists($warnalabel, $warna)) {
            $cls = $warna[$warnalabel];
        } else {
            $cls = $warna['BIRU'];
        }

        $rv = "<button class='btn btn-xs ".$cls."'>".$deskripsi."</button>";
        return $rv;
    }

    public function datatable($request, $dataproses = null)
    {

        $warna = Helpers::warna();

        if (!empty($request)) {
            $take = $request->input('take');
            $kriteria = $request->input('kriteria');
            $halaman = $request->input('halaman');
        }

        if (empty($halaman)) {$halaman = 1;}
        if (empty($take)) {$take = 30;}
        $skip = ($halaman - 1) * $take;
        $status_anggota = M_StatusAnggota::where('id','!=', '0');

        if(!empty($kriteria)){
            $status_anggota = $status_anggota->where('deskripsi','LIKE','%'.$kriteria.'%');
        }

        $count = count($status_anggota->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $status_anggota = $status_anggota->skip($skip)->take($take)->get();

        $table = "<table class='table table-hover'>
                  <thead>
                    <tr>
                        <th style=\"text-align:center;\">
                            <input type=\"checkbox\" id=\"PilihSemuaData\" onclick=\"PilihSemuaData()\">
                        </th>
                      <th>Deskripsi</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";

        if (!empty($dataproses)) {

            $table .= "<tr>
                      <td align='center'>".Helpers::formCheckbox($dataproses->id)."</td>
                      <td align='left'>". $this::WarnaLabel($dataproses->deskripsi, $dataproses->warna, $warna)."</td>

                      <td align='center'>".$this::formAction($dataproses)."</td>
                    </tr>
                ";
            $dataproses_id = $dataproses->id;
        } else {
            $dataproses_id = 0;
        }

        foreach ($status_anggota as $detail) {
            if ($detail->id != $dataproses_id) {

                $table .= "<tr>
                          <td align='center'>".Helpers::formCheckbox($detail->id)."</td>
                          <td align='left'>".$this::WarnaLabel($detail->deskripsi, $detail->warna, $warna)."</td>
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


}
