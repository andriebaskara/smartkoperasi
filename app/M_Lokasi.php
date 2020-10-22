<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class M_Lokasi extends Model{
    protected $table = 'm_lokasi';
    protected $fillable = [ 'deskripsi', 
                            'updated', 
                        ];


}
