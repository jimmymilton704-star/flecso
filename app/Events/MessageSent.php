<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /*
    |-----------------------------------------
    | CHANNEL
    |-----------------------------------------
    */
    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->chat_id);
    }

    /*
    |-----------------------------------------
    | EVENT NAME
    |-----------------------------------------
    */
    public function broadcastAs()
    {
        return 'message.sent';
    }

    /*
    |-----------------------------------------
    | DATA
    |-----------------------------------------
    */
    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'chat_id' => $this->message->chat_id,
            'sender_type' => $this->message->sender_type,
            'sender_id' => $this->message->sender_id,
            'message' => $this->message->message,
            'file' => $this->message->file ? asset($this->message->file) : null,
            'file_type' => $this->message->file_type,
            'is_seen' => $this->message->is_seen,
            'created_at' => $this->message->created_at,
        ];
    }
}