<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use App\Data;
use App\M_Anggota;
use App\M_Agenda;
use App\M_Lokasi;
use App\M_StatusAnggota;
use App\M_Berita;
use App\M_StatusOutlet;
use App\T_AgendaRegistrasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Helpers;

use DB;
use Hash;


class AnggotaController extends Controller
{
    public function anggota(Request $request)
    {
        $kategori = $request->input('kategori');
        if ($kategori == "joinagenda") {
            $rv = $this::joinagenda($request);            

        } elseif ($kategori == "upload_user_image") {
            $rv = $this::upload_user_image($request);          

        } elseif ($kategori == "download_user_image") {
            $rv = $this::download_user_image($request);          
  
        } elseif ($kategori == "gantipassword") {
            $rv = $this::gantipassword($request);          
  
        } else {
            $rv = Helpers::Response($request, false, $kategori, "Kriteria tidak dikenal", 200);
        }

        return $rv;

    }



    public function joinagenda($request)
    {
        //https://stackoverflow.com/questions/30212390/laravel-middleware-return-variable-to-controller
        $anggota = $request->get('anggota');

        $agenda_id = $request->input('agenda_id');

        $cekagenda = M_Agenda::where('id', $agenda_id)->first();
        if (empty($cekagenda)) {
            return Helpers::Response($request, false, $agenda_id, "Agenda tidak ditemukan", 200);
        }

        $cekjoin = T_AgendaRegistrasi::where('anggota_id', $anggota->id)
                            ->where('agenda_id', $agenda_id)
                            ->first();
        if (!empty($cekjoin)) {
            return Helpers::Response($request, false, $agenda_id, "Anda sudah pernah terdaftar", 200);
        }

        try {

            $regist = T_AgendaRegistrasi::create(['anggota_id' => $anggota->id, 
                                            'agenda_id' => $agenda_id,
                                        ]);

            return Helpers::Response($request, true, $regist, "Pendaftaran agenda berhasil", 200);


        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }
    }

    public function upload_user_image($request)
    {
        try {

            $anggota = $request->get('anggota');
            $anggota_id = $anggota->id;

            if ($request->hasFile('image')) {
                $namafile = "anggota_".$anggota_id.".jpg";

                $image = $request->file('image');
                $filename = $image->storeAs(
                            'public/anggota', $namafile
                        );
            }


            return Helpers::responseJson( true, "OK" , "OK");

        } catch(\Exception $exception){
            return Helpers::responseJson( false, $exception , "Terdapat kesalahan data");
        }
    }

    public function download_user_image($request)
    {
        try {

            $anggota = $request->get('anggota');
            $anggota_id = $anggota->id;
            $fileurl = "public/anggota/anggota_".$anggota_id.".jpg";

            return Storage::download($fileurl);

        } catch(\Exception $exception){
            return Helpers::responseJson( false, $exception , "Terdapat kesalahan data", 401);
        }
    }

    public function gantipassword($request)
    {
        try {
            $anggota = $request->get('anggota');
            $password = $request->input('password');
            $password_baru = $request->input('password_baru');

            $decrypted = Crypt::decryptString($anggota->password);

            if (strlen($password_baru) < 6) {
                return Helpers::Response($request, false, $password_baru, "Password kurang dari 6 karakter", 203);
            }

            if ($password != $decrypted) {
                return Helpers::Response($request, false, $password, "Password lama tidak valid", 203);
            }

            $anggota->password =  Crypt::encryptString($password_baru);
            $anggota->update();

            return Helpers::Response($request, true, "OK", "ok", 200);

        } catch(\Exception $exception){
            return Helpers::responseJson( false, $exception , "Terdapat kesalahan data", 401);
        }

    }


}

