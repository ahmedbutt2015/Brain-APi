<?php

namespace App\Listeners;

use App\Channels\TwilioChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\InteractsWithQueue;
use App\Message;


class LogNotification
{
    private $message;

    /**
     * LogNotification constructor.
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Handle the event.
     *
     * @param  NotificationSent  $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        if($event->channel == TwilioChannel::class){
            $event->notification->message->response = json_encode($event->response->toArray());
            $event->notification->message->save();
        }
    }
}
