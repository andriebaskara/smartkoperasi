<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Anggota extends Model{
    protected $table = 'm_anggota';
    protected $fillable = [ 'nama', 
                            'no_anggota', 
                            'email', 
                            'password', 
                            'telp', 
                            'alamat', 
                            'lokasi_id', 
                            'status_id', 
                            'is_anggota', 
                            'token', 
                            'updated', 
                        ];

    public function status(){
        return $this->belongsTo(M_StatusAnggota::class,'status_id');
    }



}
