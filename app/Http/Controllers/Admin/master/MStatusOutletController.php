<?php

namespace App\Http\Controllers\Admin\Master;

use App\M_StatusOutlet;
use App\Helpers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;

class MStatusOutletController extends Controller
{
    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('master_status_outlet'))
        {
            return redirect()->route('admin.');
        }

        $xwarna = Helpers::warna();
        $warna = Array();

        foreach ($xwarna as $key => $value) {
            $warna[$key] = $key;
        }

        $table = $this::datatable(null);

        return view('admin.master.status_outlet.index', compact('table','warna'));
    }

    public function store(Request $request)
    { 
        $result = new \stdClass();
        if(!Auth()->user()->hasAnyPermission('master_status_outlet'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helpers::responseJson($pesan,"" );
        }

        $simpan = $request->input('simpan');

        DB::beginTransaction();

        try {        

            if ($simpan =='baru') {
                $id= 0;
                $status_outlet = M_StatusOutlet::create($request->all());
                $pesan = "Data berhasil disimpan";

            } else {
                $id = $request->input('id');
        
                $status_outlet = M_StatusOutlet::findOrFail($id);
                $status_outlet->update($request->all());
                $pesan = "Data berhasil diupdate";
        }

            $status_outlet->updated =  Helpers::generateUpdated();
            $status_outlet->save();
            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }

        $table = $this::datatable($request, $status_outlet);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }


    public function hapusdata(Request $request)
    {

        if (! Gate::allows('master_status_outlet')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $id = $request->input('id');
        try {

            $status_outlet = M_StatusOutlet::findOrFail($id);
        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if (empty($status_outlet)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        $status_outlet->delete();
        
        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdipilih(Request $request)
    {
        if (! Gate::allows('master_status_outlet')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        if ($request->input('ids')) {
            $entries = M_StatusOutlet::whereIn('id', $request->input('ids'))->get();

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
        $rv = "<button class='btn btn-xs ".$warna[$warnalabel]."'>".$deskripsi."</button>";
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
        $status_outlet = M_StatusOutlet::where('id','!=', '0');

        if(!empty($kriteria)){
            $status_outlet = $status_outlet->where('deskripsi','LIKE','%'.$kriteria.'%');
        }

        $count = count($status_outlet->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $status_outlet = $status_outlet->skip($skip)->take($take)->get();

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

        foreach ($status_outlet as $detail) {
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
