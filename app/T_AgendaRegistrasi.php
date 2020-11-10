<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class T_AgendaRegistrasi extends Model{
    protected $table = 't_agenda_registrasi';
    protected $fillable = [ 'agenda_id', 
                            'anggota_id', 
                            'updated', 
                        ];

}
