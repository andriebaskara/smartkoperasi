<?php

namespace App\Http\Controllers\Admin\Master;

use App\Data;
use App\Helpers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;

class TentangKoperasiController extends Controller
{
    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('tentang_koperasi'))
        {
            return redirect()->route('admin.');
        }

        return view('admin.master.tentangkoperasi.index');
    }

    public function simpangambar(Request $request) 
    {
       
        if(!Auth()->user()->hasAnyPermission('tentang_koperasi'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helper::responseJson(false, "" , $pesan );
        }

        try {

            $tipe = $request->input('tipe');

            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');

                $urutan = Data::Where('pkey', 'tentangkoperasi_'.$tipe)
                        ->max('ninteger');
                if (is_null($urutan)) {$urutan = 0;}
                $urutan++;

                $filename = $gambar->store('public/tentangkoperasi');


                $rv = Data::Create([
                            "pkey" => 'tentangkoperasi_'.$tipe,
                            "ninteger" => $urutan,
                            "nstring" => $filename,
                        ]);
            }

            $result = $this::SusunFile($tipe);
            return Helpers::responseJson(true, $result, "OK" );

        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }

    }



    public function downloadfile($id)
    {
     
        $fileurl = "tentang".$id.".dokumen";
        $destinationPath = env('LOKASI_DOKUMEN_WEB');

        if (!file_exists($destinationPath.$fileurl)) {
            $fileurl = "tidakadadata.pdf";
        }

        $url = url($destinationPath.$fileurl) ;
        $filename = "data.pdf";
        $tempImage = tempnam(sys_get_temp_dir(), $filename);

        copy($url, $tempImage);

        return response()->download($tempImage, $filename);
    }

    public function tampilfile($tipe)
    {
        $listfile = $this::SusunFile($tipe);

        return Helpers::responseJson(true, $listfile, "OK" );
    }

    public function SusunFile($tipe) {
        $listfile = Array(); 
        $result = Array(); 

        $datas = Data::where('pkey', 'tentangkoperasi_'.$tipe)
                        ->orderby('ninteger')
                        ->get();
        foreach ($datas as $data) {
            $url = url(Storage::url($data->nstring));
            $listfile[$data->ninteger] = $url;
        }

        $result['listfile'] = $listfile;
        $result['jumlahdata'] = $datas->count();

        return $result;
    }

    public function hapusfile(Request $request) {
        
        if(!Auth()->user()->hasAnyPermission('tentang_koperasi'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helper::responseJson(false, "" , $pesan );
        }

        try {

            $tipe = $request->input('tipe');
            $urutan = $request->input('urutan');

            $data = Data::where('pkey', 'tentangkoperasi_'.$tipe)
                            ->where('ninteger', $urutan)
                            ->first();
            if (empty($data)) {
                return Helpers::responseJson(false, "", "Data tidak ditemukan" );
            }

            Storage::delete($data->nstring);
            $data->delete();

            DB::select(DB::raw("UPDATE data set ninteger = ninteger - 1 where pkey='tentangkoperasi_".$tipe."' and ninteger > ". $urutan ));

            $result = $this::SusunFile($tipe);
            return Helpers::responseJson(true, $result, "OK" );

        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }        
    }


    public function naikurutan(Request $request) {
        
        if(!Auth()->user()->hasAnyPermission('tentang_koperasi'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helper::responseJson(false, "" , $pesan );
        }

        try {

            $tipe = $request->input('tipe');
            $urutan = $request->input('urutan');
            if ($urutan == 1) {
                return Helpers::responseJson(false, "", "Urutan pertama tidak bisa dinaikkan" );

            }

            $data = Data::where('pkey', 'tentangkoperasi_'.$tipe)
                            ->where('ninteger', $urutan)
                            ->first();
            if (empty($data)) {
                return Helpers::responseJson(false, "", "Data tidak ditemukan" );
            }

            $data2 = Data::where('pkey', 'tentangkoperasi_'.$tipe)
                            ->where('ninteger', $urutan - 1)
                            ->first();
            if (!empty($data2)) {
                $data2->ninteger = $urutan;
                $data2->save();
            }

            if (!empty($data)) {
                $data->ninteger = $urutan - 1;
                $data->save();
            }

            $result = $this::SusunFile($tipe);
            return Helpers::responseJson(true, $result, "OK" );

        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }        
    }

    public function turunurutan(Request $request) {
        
        if(!Auth()->user()->hasAnyPermission('tentang_koperasi'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helper::responseJson(false, "" , $pesan );
        }

        try {

            $tipe = $request->input('tipe');
            $urutan = $request->input('urutan');

            $data = Data::where('pkey', 'tentangkoperasi_'.$tipe)
                            ->where('ninteger', $urutan)
                            ->first();
            if (empty($data)) {
                return Helpers::responseJson(false, "", "Data tidak ditemukan" );
            }

            $data2 = Data::where('pkey', 'tentangkoperasi_'.$tipe)
                            ->where('ninteger', $urutan + 1)
                            ->first();
            if (!empty($data2)) {
                $data2->ninteger = $urutan;
                $data2->save();
            }

            if (!empty($data)) {
                $data->ninteger = $urutan + 1;
                $data->save();
            }

            $result = $this::SusunFile($tipe);
            return Helpers::responseJson(true, $result, "OK" );

        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }        
    }


}
