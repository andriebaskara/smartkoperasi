<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class M_StatusPengajuan extends Model{
    protected $table = 'm_status_pengajuan';
    protected $fillable = [ 'deskripsi', 
                            'level', 
                            'updated', 
                        ];


}
