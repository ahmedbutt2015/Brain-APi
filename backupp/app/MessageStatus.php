<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class MessageStatus extends Model
{
    protected $table = 'message_status';

    protected $fillable = ['message_id', 'status', 'response', 'read_at'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public static function getList()
    {
        $userId = Auth::user()->id;
        $statusList = MessageStatus::select('message_status.*')
            ->join('messages', 'messages.id', '=', 'message_status.message_id')
            ->where('messages.user_id', '=', $userId);

        return $statusList;
    }
}
