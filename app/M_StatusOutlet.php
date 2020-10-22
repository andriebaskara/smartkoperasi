<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class M_StatusOutlet extends Model{
    protected $table = 'm_status_outlet';
    protected $fillable = [ 'deskripsi', 
                            'warna', 
                            'updated', 
                        ];


}
