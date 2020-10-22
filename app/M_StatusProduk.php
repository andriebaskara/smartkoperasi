<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_StatusProduk extends Model{
    protected $table = 'm_status_produk';
    protected $fillable = [ 'deskripsi', 
                            'warna', 
                            'updated', 
                        ];


}
