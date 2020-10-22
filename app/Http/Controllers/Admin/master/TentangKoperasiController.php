<?php

namespace App\Http\Controllers\Admin\Master;

use App\Data;

use App\Project\Helper;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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

    public function simpanfile(Request $request) 
    {
       
        if(!Auth()->user()->hasAnyPermission('tentang_koperasi'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helper::responseJson(false, "" , $pesan );
        }

        $tipe = $request->input('tipe');

        if ($request->hasFile('file')) {
       
            $filename = "tentang".$tipe.".dokumen";
            $file = $request->file('file');
            $destinationPath = env('LOKASI_DOKUMEN');
            $file->move($destinationPath, $filename);
        }


        return view('admin.master.tentangkoperasi.index');
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

    public function tampilfile($id)
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
        return response()->file($tempImage);

    }




}
