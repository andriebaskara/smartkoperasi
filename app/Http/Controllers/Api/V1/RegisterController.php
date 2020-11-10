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
use App\T_Reset;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

use App\Mail\requestreset;
use Illuminate\Support\Facades\Mail;

use App\Helpers;

use DB;
use Hash;


class RegisterController extends Controller
{
    public function register(Request $request)
    {

        $kategori = $request->input('kategori');
        if ($kategori == "pendaftarananggota") {
            $rv = $this::pendaftarananggota($request);            

        } elseif ($kategori == "ambilgambartentangkoperasi") {
            $rv = $this::ambilgambartentangkoperasi($request);     

        } elseif ($kategori == "ambilgambarberita") {
            $rv = $this::ambilgambarberita($request);      

        } elseif ($kategori == "listtentangkoperasi") {
            $rv = $this::listtentangkoperasi($request);          

        } elseif ($kategori == "listcontentumum") {
            $rv = $this::ListContentUmum($request);          

  
        } elseif ($kategori == "listallberita") {
            $rv = $this::listallberita($request);          
  
        } elseif ($kategori == "listallagenda") {
            $rv = $this::listallagenda($request);          
  
        } elseif ($kategori == "loginanggota") {
            $rv = $this::loginanggota($request);          
  
        } elseif ($kategori == "requestreset") {
            $rv = $this::requestreset($request);   
  
        } elseif ($kategori == "resetpassword") {
            $rv = $this::resetpassword($request);   



  
        } elseif ($kategori == "ambilgambaragenda") {
            $rv = $this::ambilgambaragenda($request);          

        } elseif ($kategori == "detailberita") {
            $rv = $this::detailberita($request);          

        } elseif ($kategori == "detailagenda") {
            $rv = $this::detailagenda($request);          




        } else {
            $rv = Helpers::Response($request, false, $kategori, "Kriteria tidak dikenal", 200);
        }

        return $rv;
    }
    
    public function ListContentUmum($request) 
    {

        $result = array();

        $sekarang = Date('Y-m-d');

        $outlet = M_StatusOutlet::pluck('deskripsi','id')->toArray();
        $lokasi = M_Lokasi::pluck('deskripsi','id')->toArray();
        $status = M_StatusAnggota::pluck('deskripsi','id')->toArray();
        $berita = M_Berita::where('mulai', '<=', $sekarang)
                            ->where('selesai', '>=', $sekarang)
                            ->orderBy('id', 'desc')
                            ->offset(0)->limit(10)
                            ->get();

        $agenda = M_Agenda::where('mulai', '<=', $sekarang)
                            ->where('selesai', '>=', $sekarang)
                            ->orderBy('id', 'desc')
                            ->offset(0)->limit(5)
                            ->get();

        if (!empty($lokasi)) {
            $result['lokasi'] = $lokasi;
        }

        if (!empty($outlet)) {
            $result['outlet'] = $outlet;
        }

        if (!empty($status)) {
            $result['status'] = $status;
        }

        if (!empty($berita)) {
            $result['berita'] = $berita;
        }

        if (!empty($agenda)) {
            $result['agenda'] = $agenda;
        }


        return Helpers::Response($request, true, $result, "OK", 200);
    }

    public function pendaftarananggota($request) 
    {

        $email = $request->input('email');
        $nama = $request->input('nama');
        $password = $request->input('password');
        $telp = $request->input('telp');
        $alamat = $request->input('alamat');
        $lokasi_id = $request->input('lokasi_id');

        $anggota = M_Anggota::where('email', $email)->first();

        if(!empty($anggota)){
            return Helpers::Response($request, false, $email, "Email sudah digunakan oleh user lain", 200);
        }

        //$encrypted = Crypt::encryptString('Belajar Laravel Di malasngoding.com');
        //$decrypted = Crypt::decryptString($encrypted);

        $m = M_Anggota::create([
            'email' => $email,
            'nama' => $nama,
            'password' => Crypt::encryptString($password),
            'telp' => $telp,
            'alamat' => $alamat,
            'lokasi_id' => $lokasi_id,
        ]);

        unset($m['password']); 

        return Helpers::Response($request, true, $m, "OK", 200);
    }

    public function ambilgambartentangkoperasi($request)
    {
        try {
            $file = $request->input('file');
            return Storage::download($file);
        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi", 401 );
        }
    }

    public function ambilgambarberita($request)
    {
        try {
            $id = $request->input('id');
            $fileurl = "public/berita/Berita_".$id.".jpg";

            if(Storage::exists($fileurl)) {
                return Storage::download($fileurl);
            } else {
                return Helpers::responseJson(false, "Berita_".$id.".jpg", "File tidak ditemukan", 401 );
            }

        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi", 401 );
        }
    }

    public function detailberita($request)
    {
        try {
            $id = $request->input('id');
            $fileurl = "public/berita/Berita_".$id.".jpg";

            $berita = M_Berita::Where('id', $id)->first();

            if (empty($berita)) {
                return Helpers::responseJson(false, $id, "Data tidak ditemukan", 201 );
            }
            $berita->imgurl = $fileurl;

            return Helpers::responseJson(true, $berita, "OK");

        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi", 401 );
        }
    }

    public function detailagenda($request)
    {
        try {
            $id = $request->input('id');
            $fileurl = "public/agenda/agenda_".$id.".jpg";

            $agenda = M_Agenda::Where('id', $id)->first();

            if (empty($agenda)) {
                return Helpers::responseJson(false, $id, "Data tidak ditemukan", 201 );
            }
            $agenda->imgurl = $fileurl;

            return Helpers::responseJson(true, $agenda, "OK");

        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi", 401 );
        }
    }



    public function listtentangkoperasi($request)
    {

        $listfile = Array(); 
        $result = Array(); 
        $tipe = $request->input('tipe');

        $datas = Data::where('pkey', 'tentangkoperasi_'.$tipe)
                        ->orderby('ninteger')
                        ->get();
        foreach ($datas as $data) {
            $listfile[$data->ninteger] = $data->nstring;
        }

        $result['listfile'] = $listfile;
        $result['jumlahdata'] = $datas->count();
        $result['tipe'] = $tipe;

        return Helpers::Response($request, true, $result, "ok", 200);
    }

    public function listallberita($request)
    {
        $sekarang = Date('Y-m-d');

        $listfile = Array(); 
        $result = Array(); 
        $tipe = $request->input('tipe');

        $datas = M_Berita::where('mulai', '<=', $sekarang)
                            ->where('selesai', '>=', $sekarang)
                            ->orderBy('id', 'desc')
                            ->get();

        $result = Array();
        $result['berita'] = $datas;

        return Helpers::Response($request, true, $result, "ok", 200);
    }

    public function listallagenda($request)
    {
        $sekarang = Date('Y-m-d');

        $listfile = Array(); 
        $result = Array(); 
        $tipe = $request->input('tipe');

        $datas = M_Agenda::where('mulai', '<=', $sekarang)
                            ->where('selesai', '>=', $sekarang)
                            ->orderBy('id', 'desc')
                            ->get();

        $result = Array();
        $result['agenda'] = $datas;

        return Helpers::Response($request, true, $result, "ok", 200);
    }

    public function loginanggota($request)
    {
        $result = Array(); 
        $email = $request->input('email');
        $password = $request->input('password');

        $result['email'] = $email;

        $data = M_Anggota::where('email', $email)
                            ->first();

        if (empty($data)) {
            return Helpers::Response($request, false, $result, "Data tidak valid", 200);
        }

        if (is_null($data->status_id)) {
            return Helpers::Response($request, false, $result, "Data anggota belum disetujui ", 200);
        }

        if (is_null($data->no_anggota)) {
            return Helpers::Response($request, false, $result, "Data anggota belum disetujui. No Anggota belum diterbitkan ", 200);
        }

        if ($data->status_id != 1) {
            return Helpers::Response($request, false, $result, "Status anggota ".$data->status->deskripsi, 200);
        }

        if ($data->is_anggota != 1) {
            return Helpers::Response($request, false, $result, "Anda bukan sebagai anggota koperasi", 200);
        }

        $decrypted = Crypt::decryptString($data->password);

        if ($password != $decrypted) {
            return Helpers::Response($request, false, $result, "Email atau Password tidak valid", 200);
        }

        $token = Helpers::rndstr(70);
        $data->token = $token;
        $data->update();

        $result['token'] = $token;
        $result['nama'] = $data->nama;
        $result['id'] = $data->id;

        return Helpers::Response($request, true, $result, "ok", 200);
    }

    public function requestreset($request)
    {
        $result = Array(); 
        $email = $request->input('email');

        $result['email'] = $email;

        $data = M_Anggota::where('email', $email)
                            ->first();

        if (empty($data)) {
            return Helpers::Response($request, false, $result, "Data tidak valid", 200);
        }

        $token = Helpers::rndstr(70);
        $hapus = T_Reset::where('email', $email)->delete();
        $reset = T_Reset::Create([
                        "email" => $email,
                        "token" => $token,
                    ]);
        $reset->nama = $data->nama;

        $qr = \QrCode::size(200)
                ->backgroundColor(255, 255, 255)
                ->generate($token);
        $e_qr = base64_encode($qr);

        $reset->qr = $qr;

        $statusmail = Mail::to($email)->send(new requestreset($reset));

        return Helpers::Response($request, true, $statusmail, "ok", 200);
    }


    public function resetpassword($request)
    {
        try {
            $email = $request->input('email');
            $password = $request->input('password');
            $tokenreset = $request->input('tokenreset');

            if (strlen($password) < 6) {
                return Helpers::Response($request, false, $password, "Password kurang dari 6 karakter", 203);
            }

            $cek = T_Reset::where('email', $email)
                        ->where('token', $tokenreset)
                        ->first();
            if (empty($cek)) {
                return Helpers::responseJson( false, $tokenreset , "Data tidak valid", 203);
            }

            $anggota = M_Anggota::where('email', $email)->first();
            if (empty($anggota)) {
                return Helpers::responseJson( false, $email , "Data anggota tidak valid", 203);
            }

            $anggota->password =  Crypt::encryptString($password);
            $anggota->update();

            $cek->delete();

            return Helpers::Response($request, true, $cek, "ok", 200);

        } catch(\Exception $exception){
            return Helpers::responseJson( false, $exception , "Terdapat kesalahan data", 401);
        }

    }

    public function ambilgambaragenda($request)
    {
        try {
            $id = $request->input('id');
            $tipe = $request->input('tipe');

            if (is_null($tipe)) {
                $tipe = "l";
            }

            if ($tipe == "l") {
                $fileurl = "agenda_".$id.".jpg";
            } else {
                $fileurl = "agenda2_".$id.".jpg";
            }

            $fileurl = "public/agenda/".$fileurl;

            return Storage::download($fileurl);
        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi", 401 );
        }
    }


}

