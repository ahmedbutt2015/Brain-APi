<?php


namespace App\Channels\Messages;


class TwilioMessage
{
    public $content;
    public $from;
    public $to;
    public $channel;

    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    public function channel($channel)
    {
        $this->channel = $channel;

        return $this;
    }
}