<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Agenda extends Model{
    protected $table = 'm_agenda';
    protected $fillable = [ 'title', 
                            'content', 
                            'mulai', 
                            'selesai', 
                            'tanggal', 
                            'updated', 
                        ];

}
