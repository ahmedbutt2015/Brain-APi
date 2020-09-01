<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interlocutor extends Model
{
    protected $fillable = [
        'name', 'email', 'phone_number', 'user_id',
    ];

    public function tags()
    {
        return $this->belongsToMany('App\Tag');

    }
}
