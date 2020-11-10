<?php

namespace App\Http\Controllers\Admin\Approval;

use App\M_Anggota;
use App\M_Lokasi;
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

class AAnggotaController extends Controller
{
    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('approval_anggota'))
        {
            return redirect()->route('admin.');
        }

        $lokasi = M_Lokasi::pluck('deskripsi','id')->toArray();
        $status = M_StatusAnggota::pluck('deskripsi','id')->toArray();


        $table = $this::datatable(null);

        return view('admin.approval.anggota.index', compact('table', 'lokasi','status'));
    }

    public function store(Request $request)
    { 
        $result = new \stdClass();
        if(!Auth()->user()->hasAnyPermission('approval_anggota'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helpers::responseJson($pesan,"" );
        }

        $simpan = $request->input('simpan');

        DB::beginTransaction();

        try {        

            $id = $request->input('id');
    
            $anggota = M_Anggota::findOrFail($id);
            $anggota->update($request->all());
            $pesan = "Data berhasil diupdate";

            $anggota->updated =  Helpers::generateUpdated();
            $anggota->save();
            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }

        $table = $this::datatable($request, $anggota);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }


    public function edit($id)
    {
        if(!Auth()->user()->hasAnyPermission('approval_anggota'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk approval";
            return Helpers::responseJson(false, "" , $pesan );
        }
        $data = M_Anggota::where('id',$id)->first();

        return Helpers::responseJson(true, $data, "OK");
    }

    public function loaddatatable(Request $request) {

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public static function formAction($data)
    {
        $form = "<button class='btn btn-xs btn-info' id='btnEdit' onclick=\"EditData(".$data->id.")\">Edit</button> ";
        return $form;
    }

    public function datatable($request, $dataproses = null)
    {

        $datawarna = Helpers::warna();

        if (!empty($request)) {
            $take = $request->input('take');
            $kriteria = $request->input('kriteria');
            $halaman = $request->input('halaman');
        }

        if (empty($halaman)) {$halaman = 1;}
        if (empty($take)) {$take = 30;}
        $skip = ($halaman - 1) * $take;
        $anggota = M_Anggota::orderby('status_id');


        if(!empty($kriteria)){
            $anggota = $anggota->where('nama','LIKE','%'.$kriteria.'%')
                            ->orwhere('email','LIKE','%'.$kriteria.'%')
                            ->orwhere('telp','LIKE','%'.$kriteria.'%')
                            ->orwhere('alamat','LIKE','%'.$kriteria.'%')
                            ->orwhere('no_anggota','LIKE','%'.$kriteria.'%')
                        ;
            
        }

        $count = count($anggota->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $anggota = $anggota->skip($skip)->take($take)->get();

        $table = "<table class='table table-hover'>
                  <thead>
                    <tr>
                      <th>No Anggota</th>
                      <th>Nama</th>
                      <th>Email</th>
                      <th>Telp</th>
                      <th>Alamat</th>
                      <th>Status</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";

        if (!empty($dataproses)) {

            $table .= "<tr>
                      <td align='left'>".$dataproses->no_anggota."</td>
                      <td align='left'>".$dataproses->nama."</td>
                      <td align='left'>".$dataproses->email."</td>
                      <td align='left'>".$dataproses->telp."</td>
                      <td align='left'>".$dataproses->alamat."</td>
                      <td align='center'>".$this::statusanggota($datawarna, $dataproses)."</td>
                      <td align='center'>".$this::formAction($dataproses)."</td>
                    </tr>
                ";
            $dataproses_id = $dataproses->id;
        } else {
            $dataproses_id = 0;
        }

        foreach ($anggota as $detail) {
            if ($detail->id != $dataproses_id) {

                $table .= "<tr>
                      <td align='left'>".$detail->no_anggota."</td>
                      <td align='left'>".$detail->nama."</td>
                      <td align='left'>".$detail->email."</td>
                      <td align='left'>".$detail->telp."</td>
                      <td align='left'>".$detail->alamat."</td>
                      <td align='center'>".$this::statusanggota($datawarna, $detail)."</td>
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

    public function statusanggota($datawarna, $dataanggota) {

        try
        {


            $status_id = $dataanggota->status_id;
            if (is_null($status_id)) {
                return "-";
            }

            $status = M_StatusAnggota::where('id', $status_id)->first();
            if (empty($status)) {
                return "-";
            }

            $warna = $status->warna;
            $deskripsi = $status->deskripsi;

            if (array_key_exists($warna, $datawarna)) {
                $cls = $datawarna[$warna];
            } else {
                $cls = $datawarna['BIRU'];
            }

            $rv = "<button class='btn btn-xs ".$cls."'>".$deskripsi."</button>";
            return $rv;

        } catch(\Exception $exception){
            return "E";
        }

    }




    public function simpangambar(Request $request) {

        try
        {

            $id = $request->input('id');

            $namafile = "Berita_".$id.".jpg";

            if ($request->hasFile('image')) {

                $image = $request->file('image');
                $destinationPath = env('LOKASI_DOKUMEN'); 

                $image->move($destinationPath, $namafile);
            }

            return Helpers::responseJson( true, "OK" , "OK");

        } catch(\Exception $exception){
            return Helpers::responseJson( false, $exception , "Terdapat kesalahan data");
        }

    }




}
