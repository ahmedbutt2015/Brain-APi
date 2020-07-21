<?php


namespace App\Channels;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class TwilioChannel
{
    public function send($notifiable, Notification $notification)
    {

        $message = $notification->toTwilio($notifiable);

        $twilioConfig = $notifiable->routeNotificationFor('Twilio');

        $to = $message->to;
        $from = $twilioConfig['twilio_phone_number'];
        $channel = $message->channel;

        $twilio = new Client($twilioConfig['twilio_auth_sid'], $twilioConfig['twilio_auth_token']);


        $return = $twilio->messages->create($channel.':' . $to, [
            "from" => $channel . ':' . $from,
            "body" => $message->content
        ]);

        return $return;
    }
}