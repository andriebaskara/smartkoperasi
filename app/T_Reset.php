<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class T_Reset extends Model{
    protected $table = 't_reset';
    protected $fillable = [ 'email', 
                            'token', 
                            'updated', 
                        ];

}
