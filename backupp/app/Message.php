<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['user_id', 'from', 'to', 'channel', 'body', 'direction', 'response', 'read_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->hasMany(MessageStatus::class);
    }

    public static function getByTwilioSid($twilioSid)
    {
        return Message::where('response', 'like', '%'.$twilioSid.'%')->get()->first();
    }

    public static function extractChannel($data)
    {
        return explode(':', $data)[0];
    }

    public static function extractNumber($data)
    {
        return str_replace(' ', '+', explode(':', $data)[1]);
    }

}
