<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_StatusPenjualan extends Model{
    protected $table = 'm_status_penjualan';
    protected $fillable = [ 'deskripsi', 
                            'warna', 
                            'updated', 
                        ];


}
