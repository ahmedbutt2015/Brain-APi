<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function interlocutors()
    {
        return $this->belongsToMany('App\Interlocutor');
    }
}
