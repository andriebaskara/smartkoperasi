<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Data extends Model{
    protected $table = 'data';
    protected $fillable = [ 'pkey', 
                            'nstring', 
                            'ninteger', 
                            'ndouble', 
                            'ndate', 
                            'updated', 
                        ];


}
