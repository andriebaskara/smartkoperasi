<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Berita extends Model{
    protected $table = 'm_berita';
    protected $fillable = [ 'title', 
                            'singkat', 
                            'content', 
                            'mulai', 
                            'selesai', 
                            'tanggal', 
                            'updated', 
                        ];

}
