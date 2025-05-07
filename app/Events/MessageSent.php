<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatId;
    public $message;
    public $senderId;

    public function __construct($chatId, $message)
    {
        $this->chatId = $chatId;
        $this->message = $message;
        $this->senderId = auth()->id();
        Log::info('MessageSent event constructed', [
            'chatId' => $chatId,
            'message' => $message
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->chatId);
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'chat_id' => $this->chatId,
            'sender_id' => $this->senderId,
            'timestamp' => now()->toISOString()
        ];
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}
