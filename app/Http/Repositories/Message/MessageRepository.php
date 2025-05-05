<?php

namespace App\Http\Repositories\Message;

use App\Interface\Message\MessageRepositoryInterface;
use App\Models\Message;
use Illuminate\Support\Facades\Log;

class MessageRepository implements MessageRepositoryInterface
{

    public function create(array $data): Message
    {
        return Message::create($data);
    }

    public function getMessageByChatId(int $chatId)
    {
        $message =  Message::where('chat_id', $chatId)->with('user')->get();
        return $message;
    }
}

