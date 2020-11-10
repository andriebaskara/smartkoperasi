<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/


Route::get('/', 'Auth\LoginController@showLoginForm');
Auth::routes(); /*standar untuk login, logout, lupa password, register */

Route::get('/home', 'HomeController@index')->name('home'); /*halaman dashboard */
Route::get('/profile', 'Auth\ProfileController@index')->name('profile');
Route::post('/profile/gantipassword', 'Auth\ProfileController@gantipassword')->name('gantipassword');


Route::get('image-crop', 'ImageController@imageCrop');
Route::post('image-crop', 'ImageController@imageCropPost')->name('uploadimage');



Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_loaddatatable', ['uses' => 'Admin\RolesController@loaddatatable', 'as' => 'roles.loaddatatable']);
    Route::post('roles_hapusdata', ['uses' => 'Admin\RolesController@hapusdata', 'as' => 'roles.hapusdata']);
    Route::post('roles_hapusdipilih', ['uses' => 'Admin\RolesController@hapusdipilih', 'as' => 'roles.hapusdipilih']);



    Route::resource('users', 'Admin\UsersController');
    Route::post('users_loaddatatable', ['uses' => 'Admin\UsersController@loaddatatable', 'as' => 'users.loaddatatable']);
    Route::post('users_hapusdata', ['uses' => 'Admin\UsersController@hapusdata', 'as' => 'users.hapusdata']);
    Route::post('users_hapusdipilih', ['uses' => 'Admin\UsersController@hapusdipilih', 'as' => 'users.hapusdipilih']);

    Route::get('user/roles/{user}', ['uses' => 'Admin\UsersController@rolesuser', 'as' => 'users.roles']);
    Route::post('user/simpanroles', ['uses' => 'Admin\UsersController@simpanrolesuser', 'as' => 'users.simpanroles']);
    Route::get('user/permissions/{user}', ['uses' => 'Admin\UsersController@permissionsuser', 'as' => 'users.permissions']);
    Route::post('user/simpanpermissions', ['uses' => 'Admin\UsersController@simpanpermissionsuser', 'as' => 'users.simpanpermissions']);

    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {


        //lokasi
        Route::resource('lokasi', 'Admin\Master\MLokasiController');
        Route::post('lokasi_loaddatatable', ['uses' => 'Admin\Master\MLokasiController@loaddatatable', 'as' => 'lokasi.loaddatatable']);
        Route::post('lokasi_hapusdata', ['uses' => 'Admin\Master\MLokasiController@hapusdata', 'as' => 'lokasi.hapusdata']);
        Route::post('lokasi_hapusdipilih', ['uses' => 'Admin\Master\MLokasiController@hapusdipilih', 'as' => 'lokasi.hapusdipilih']);

        //tipe_iuran
        Route::resource('tipe_iuran', 'Admin\Master\MTipeIuranController');
        Route::post('tipe_iuran_loaddatatable', ['uses' => 'Admin\Master\MTipeIuranController@loaddatatable', 'as' => 'tipe_iuran.loaddatatable']);
        Route::post('tipe_iuran_hapusdata', ['uses' => 'Admin\Master\MTipeIuranController@hapusdata', 'as' => 'tipe_iuran.hapusdata']);
        Route::post('tipe_iuran_hapusdipilih', ['uses' => 'Admin\Master\MTipeIuranController@hapusdipilih', 'as' => 'tipe_iuran.hapusdipilih']);

        //kategori
        Route::resource('kategori', 'Admin\Master\MKategoriController');
        Route::post('kategori_loaddatatable', ['uses' => 'Admin\Master\MKategoriController@loaddatatable', 'as' => 'kategori.loaddatatable']);
        Route::post('kategori_hapusdata', ['uses' => 'Admin\Master\MKategoriController@hapusdata', 'as' => 'kategori.hapusdata']);
        Route::post('kategori_hapusdipilih', ['uses' => 'Admin\Master\MKategoriController@hapusdipilih', 'as' => 'kategori.hapusdipilih']);

        //jenis_pembayaran
        Route::resource('jenis_pembayaran', 'Admin\Master\MJenisPembayaranController');
        Route::post('jenis_pembayaran_loaddatatable', ['uses' => 'Admin\Master\MJenisPembayaranController@loaddatatable', 'as' => 'jenis_pembayaran.loaddatatable']);
        Route::post('jenis_pembayaran_hapusdata', ['uses' => 'Admin\Master\MJenisPembayaranController@hapusdata', 'as' => 'jenis_pembayaran.hapusdata']);
        Route::post('jenis_pembayaran_hapusdipilih', ['uses' => 'Admin\Master\MJenisPembayaranController@hapusdipilih', 'as' => 'jenis_pembayaran.hapusdipilih']);

        //status_outlet
        Route::resource('status_outlet', 'Admin\Master\MStatusOutletController');
        Route::post('status_outlet_loaddatatable', ['uses' => 'Admin\Master\MStatusOutletController@loaddatatable', 'as' => 'status_outlet.loaddatatable']);
        Route::post('status_outlet_hapusdata', ['uses' => 'Admin\Master\MStatusOutletController@hapusdata', 'as' => 'status_outlet.hapusdata']);
        Route::post('status_outlet_hapusdipilih', ['uses' => 'Admin\Master\MStatusOutletController@hapusdipilih', 'as' => 'status_outlet.hapusdipilih']);

        //status_penjualan
        Route::resource('status_penjualan', 'Admin\Master\MStatusPenjualanController');
        Route::post('status_penjualan_loaddatatable', ['uses' => 'Admin\Master\MStatusPenjualanController@loaddatatable', 'as' => 'status_penjualan.loaddatatable']);
        Route::post('status_penjualan_hapusdata', ['uses' => 'Admin\Master\MStatusPenjualanController@hapusdata', 'as' => 'status_penjualan.hapusdata']);
        Route::post('status_penjualan_hapusdipilih', ['uses' => 'Admin\Master\MStatusPenjualanController@hapusdipilih', 'as' => 'status_penjualan.hapusdipilih']);

        //status_produk
        Route::resource('status_produk', 'Admin\Master\MStatusProdukController');
        Route::post('status_produk_loaddatatable', ['uses' => 'Admin\Master\MStatusProdukController@loaddatatable', 'as' => 'status_produk.loaddatatable']);
        Route::post('status_produk_hapusdata', ['uses' => 'Admin\Master\MStatusProdukController@hapusdata', 'as' => 'status_produk.hapusdata']);
        Route::post('status_produk_hapusdipilih', ['uses' => 'Admin\Master\MStatusProdukController@hapusdipilih', 'as' => 'status_produk.hapusdipilih']);

        //status_anggota
        Route::resource('status_anggota', 'Admin\Master\MStatusAnggotaController');
        Route::post('status_anggota_loaddatatable', ['uses' => 'Admin\Master\MStatusAnggotaController@loaddatatable', 'as' => 'status_anggota.loaddatatable']);
        Route::post('status_anggota_hapusdata', ['uses' => 'Admin\Master\MStatusAnggotaController@hapusdata', 'as' => 'status_anggota.hapusdata']);
        Route::post('status_anggota_hapusdipilih', ['uses' => 'Admin\Master\MStatusAnggotaController@hapusdipilih', 'as' => 'status_anggota.hapusdipilih']);

        //status_pengajuan
        Route::resource('status_pengajuan', 'Admin\Master\MStatusPengajuanController');
        Route::post('status_pengajuan_loaddatatable', ['uses' => 'Admin\Master\MStatusPengajuanController@loaddatatable', 'as' => 'status_pengajuan.loaddatatable']);
        Route::post('status_pengajuan_hapusdata', ['uses' => 'Admin\Master\MStatusPengajuanController@hapusdata', 'as' => 'status_pengajuan.hapusdata']);
        Route::post('status_pengajuan_hapusdipilih', ['uses' => 'Admin\Master\MStatusPengajuanController@hapusdipilih', 'as' => 'status_pengajuan.hapusdipilih']);


        //berita
        Route::resource('berita', 'Admin\Master\MBeritaController');
        Route::post('berita_loaddatatable', ['uses' => 'Admin\Master\MBeritaController@loaddatatable', 'as' => 'berita.loaddatatable']);
        Route::post('berita_hapusdata', ['uses' => 'Admin\Master\MBeritaController@hapusdata', 'as' => 'berita.hapusdata']);
        Route::post('berita_hapusdipilih', ['uses' => 'Admin\Master\MBeritaController@hapusdipilih', 'as' => 'berita.hapusdipilih']);
        Route::post('berita-simpangambar', ['uses' => 'Admin\Master\MBeritaController@simpangambar', 'as' => 'berita.simpangambar']);



        //Tentang Koperasi        
        Route::get('tentangkoperasi', ['uses' => 'Admin\Master\TentangKoperasiController@index', 'as' => 'tentangkoperasi.index']);
        Route::post('tentangkoperasi/simpangambar', ['uses' => 'Admin\Master\TentangKoperasiController@simpangambar', 'as' => 'tentangkoperasi.simpangambar']);
        Route::get('tentangkoperasi/downloadfile/{id}', ['uses' => 'Admin\Master\TentangKoperasiController@downloadfile', 'as' => 'tentangkoperasi.downloadfile']);
        Route::get('tentangkoperasi/tampilfile/{id}', ['uses' => 'Admin\Master\TentangKoperasiController@tampilfile', 'as' => 'tentangkoperasi.tampilfile']);
        Route::post('tentangkoperasi/hapusfile', ['uses' => 'Admin\Master\TentangKoperasiController@hapusfile', 'as' => 'tentangkoperasi.hapusfile']);
        Route::post('tentangkoperasi/naikurutan', ['uses' => 'Admin\Master\TentangKoperasiController@naikurutan', 'as' => 'tentangkoperasi.naikurutan']);
        Route::post('tentangkoperasi/turunurutan', ['uses' => 'Admin\Master\TentangKoperasiController@turunurutan', 'as' => 'tentangkoperasi.turunurutan']);



        //agenda
        Route::resource('agenda', 'Admin\Master\MAgendaController');
        Route::post('agenda_loaddatatable', ['uses' => 'Admin\Master\MAgendaController@loaddatatable', 'as' => 'agenda.loaddatatable']);
        Route::post('agenda_hapusdata', ['uses' => 'Admin\Master\MAgendaController@hapusdata', 'as' => 'agenda.hapusdata']);
        Route::post('agenda_hapusdipilih', ['uses' => 'Admin\Master\MAgendaController@hapusdipilih', 'as' => 'agenda.hapusdipilih']);
        Route::post('agenda-simpangambar', ['uses' => 'Admin\Master\MAgendaController@simpangambar', 'as' => 'agenda.simpangambar']);


 
    });

    Route::group(['prefix' => 'approval', 'as' => 'approval.'], function () {
      
        //anggota
        Route::resource('anggota', 'Admin\Approval\AAnggotaController');
        Route::post('anggota-loaddatatable', ['uses' => 'Admin\Approval\AAnggotaController@loaddatatable', 'as' => 'anggota.loaddatatable']);

    });

});




