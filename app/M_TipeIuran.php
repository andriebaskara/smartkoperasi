<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class M_TipeIuran extends Model{
    protected $table = 'm_tipe_iuran';
    protected $fillable = [ 'deskripsi', 
                            'updated', 
                        ];


}
