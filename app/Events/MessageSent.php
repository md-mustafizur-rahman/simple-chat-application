<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;
    public $user;
    public $timestamp;

    public function __construct($user, $message, $timestamp)
    {
        $this->user = $user;
        $this->message = $message;
        $this->timestamp = $timestamp;
    }

    public function broadcastOn()
    {
        return new Channel('chat');
    }

    public function broadcastWith()
    {
        return [
            'user' => $this->user,
            'message' => $this->message,
            'timestamp' => $this->timestamp
        ];
    }
}
