<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use App\M_Anggota;
use App\M_Lokasi;
use App\M_StatusAnggota;
use App\M_Berita;
use App\M_StatusOutlet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
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

        } elseif ($kategori == "ambilpdftentangkoperasi") {
            $rv = $this::ambilpdftentangkoperasi($request);     

        } elseif ($kategori == "ambilgambarberita") {
            $rv = $this::ambilgambarberita($request);          









        } elseif ($kategori == "cektoken") {
            $rv = $this::cektoken($request);          

        } elseif ($kategori == "listcontentumum") {
            $rv = $this::ListContentUmum($request);          
  
        } elseif ($kategori == "listgambarslide") {
            $rv = $this::ListGambarSlide($request);          
  
        } elseif ($kategori == "listgambarnews") {
            $rv = $this::ListGambarNews($request);          
  
        } elseif ($kategori == "listgambarworkpoint") {
            $rv = $this::ListGambarWorkpoint($request);          

        } elseif ($kategori == "uploadprofilepicture") {
            $rv = $this::uploadprofilepicture($request);          
  


  
        } else {
            $rv = Helpers::Response($request, false, "ERROR", "Kriteria tidak dikenal", 200);
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

    public function ambilpdftentangkoperasi($request)
    {
        $tipe = $request->input('tipe');

        $fileurl = "tentang".$tipe.".dokumen";
        $destinationPath = env('LOKASI_DOKUMEN_WEB');

        if (!file_exists($destinationPath.$fileurl)) {
            $fileurl = "tidakadadata.pdf";
        }

        $url = url($destinationPath.$fileurl) ;
        $filename = $tipe.".pdf";
        $tempImage = tempnam(sys_get_temp_dir(), $filename);

        copy($url, $tempImage);

        return response()->download($tempImage, $filename);
    }

    public function ambilgambarberita($request)
    {
        $id = $request->input('id');

        $fileurl = "Berita_".$id.".jpg";
        $destinationPath = env('LOKASI_DOKUMEN_WEB');

        if (file_exists($destinationPath.$fileurl)) {
            $url = url($destinationPath.$fileurl) ;
            $tempImage = tempnam(sys_get_temp_dir(), $fileurl);
            copy($url, $tempImage);

            return response()->download($tempImage, $fileurl);
            //$fileurl = "noimages.png";
        }


        return Helpers::Response($request, false, "", "file tidak ada", 404);
    }

    /*

    public function login($request) 
    {

        $email = $request->input('email');
        $nik = $request->input('nik');
        $password = $request->input('password');
        $fcm_key = $request->input('fcm_key');

        $user = User::where('email', $email)->first();

        if(empty($user)){
            return Helpers::Response($request, false, $email, "Data tidak ditemukan atau password salah", 200);
        }

        if (Hash::check($request->input('password'), $user->password)) {
            if ($user->nik == $nik) {
                $token = Helpers::generateToken();
                $user->remember_token = $token;
                $user->fcm_key = $fcm_key;
                $user->save();

                $rv = array(
                    'id'            => $user->id,
                    'token'         => $token,
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'nik'           => $user->nik,
                    'departemen'    => $user->departemen,
                    'workpoint_id'  => $user->workpoint_id,
                );        


                return Helpers::Response($request, true, $rv, "OK", 200);
            } else {
                return Helpers::Response($request, false, $email, "Data tidak sesuai", 200);
            }
        } else {
            return Helpers::Response($request, false, $email, "Data tidak ditemukan atau password salah", 200);
        }
    }


    public function cektoken($request) 
    {

        $token = $request->input('token');

        $user = User::where('remember_token', $token)->first();

        if(empty($user)){
            return Helpers::Response($request, false, $token, "Data tidak ditemukan", 200);
        }

        $rv = array(
            'token'     => $token,
            'name'      => $user->name,
            'email'     => $user->email,
            'as'        => $user->as,
            'listorder' => Helpers::ListOrder($user->id),
        );        

        return Helpers::Response($request, true, $rv, "OK", 200);
    }

    public function ListGambarSlide($request)
    {

        $id = $request->input('id');

        $destinationPath = env('IMGSLIDE'); 
        $filter = "Slide_".$id."_*.jpg";
        $files = glob($destinationPath.$filter);

        $result = Array();
        foreach($files as $f) { 
            $namafile = basename($f);
            $urlfile = url($f);
            
            $result[] = array(
                "namafile" => $namafile,
                "urlfile" => $urlfile,
            );
        }
        return Helpers::Response($request, true, array("list" => $result), "OK", 200);
    }

    public function ListGambarNews($request)
    {

        $id = $request->input('id');

        $destinationPath = env('IMGNEWS'); 
        $filter = "News_".$id."_*.jpg";
        $files = glob($destinationPath.$filter);

        $result = Array();
        foreach($files as $f) { 
            $namafile = basename($f);
            $urlfile = url($f);
            
            $result[] = array(
                "namafile" => $namafile,
                "urlfile" => $urlfile,
            );
        }
        return Helpers::Response($request, true, array("list" => $result), "OK", 200);

    }

    public function ListGambarWorkpoint($request)
    {

        $id = $request->input('id');
        $destinationPath = env('IMGWORKPOINT'); 
        $filter = "Workpoint_".$id."_*.jpg";
        $files = glob($destinationPath.$filter);

        $result = Array();
        foreach($files as $f) { 
            $namafile = basename($f);
            $urlfile = url($f);
            
            $result[] = array(
                "namafile" => $namafile,
                "urlfile" => $urlfile,
            );
        }
        return Helpers::Response($request, true, array("list" => $result), "OK", 200);

    }

    public function uploadprofilepicture($request)
    {
        try
        {

            if ($request->hasFile('image')) {

                $image = $request->file('image');

                $filename = $image->getClientOriginalName();

                $destinationPath = env('IMGPROFILE'); 
                $image->move($destinationPath, $filename);
            }

            return Helpers::responseJson( true, "OK" , "OK");

        } catch(\Exception $exception){
            return Helpers::responseJson( false, $exception , "Terdapat kesalahan data");
        }
    }
    */



}
