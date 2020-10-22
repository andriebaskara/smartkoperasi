<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_StatusAnggota extends Model{
    protected $table = 'm_status_anggota';
    protected $fillable = [ 'deskripsi', 
                            'warna', 
                            'updated', 
                        ];


}
