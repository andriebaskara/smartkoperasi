<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class M_Kategori extends Model{
    protected $table = 'm_kategori';
    protected $fillable = [ 'deskripsi', 
                            'updated', 
                        ];


}
