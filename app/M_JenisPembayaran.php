<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class M_JenisPembayaran extends Model{
    protected $table = 'm_jenis_pembayaran';
    protected $fillable = [ 'deskripsi', 
                            'updated', 
                        ];
}
