<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    //
    public function family()
    {
        return $this->belongsTo('App\Family');
    }
    public function useraddons(){
        return $this->hasMany('App\UserAddon');
    }
}
