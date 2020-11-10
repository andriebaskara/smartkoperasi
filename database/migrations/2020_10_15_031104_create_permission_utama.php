<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Spatie\Permission\Models\Permission;

class CreatePermissionUtama extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Permission::create([
                        'name' => 'user_security'
                        ]);
         Permission::create([
                        'name' => 'master_lokasi'
                        ]);    
         Permission::create([
                        'name' => 'master_tipe_iuran'
                        ]);    
         Permission::create([
                        'name' => 'master_status_outlet'
                        ]);    
         Permission::create([
                        'name' => 'master_status_penjualan'
                        ]);    
         Permission::create([
                        'name' => 'master_status_produk'
                        ]);    
         Permission::create([
                        'name' => 'master_status_anggota'
                        ]);    
         Permission::create([
                        'name' => 'master_status_pengajuan'
                        ]);    
         Permission::create([
                        'name' => 'master_kategori'
                        ]);    
         Permission::create([
                        'name' => 'master_jenis_pembayaran'
                        ]);    


         Permission::create([
                        'name' => 'approval_anggota'
                        ]);
         Permission::create([
                        'name' => 'approval_pinjaman'
                        ]);
         Permission::create([
                        'name' => 'approval_outlet'
                        ]);
         Permission::create([
                        'name' => 'approval_produk'
                        ]);


         Permission::create([
                        'name' => 'tentang_koperasi'
                        ]);

         Permission::create([
                        'name' => 'master_berita'
                        ]);
         Permission::create([
                        'name' => 'master_agenda'
                        ]);

         
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


    }
}
