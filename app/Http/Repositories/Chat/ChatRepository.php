<?php

namespace App\Http\Repositories\Chat;

use App\Interface\Chat\ChatRepositoryInterface;
use App\Models\Chat;
use App\Models\UsersChat;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class ChatRepository Implements ChatRepositoryInterface
{

    public function create(array $data): Chat
    {
        Log::info('Data',$data);
        return Chat::create($data);
    }

    public function addParticipant(Chat $chat, array $userIds): void
    {
        $userIds = array_unique($userIds);
        $chat->users()->attach($userIds);
    }

    public function findById(int $chatId): ?Chat
    {
        return Chat::findOrFail($chatId);
    }

    public function checkUserChat($chatId)
    {
        $userId = JWTAuth::parseToken()->authenticate()->id;
        $chat = UsersChat::where('chat_id',$chatId)->where('user_id',$userId)->exists();
        if ($chat) {
            return true;
        } else {
            return false;
        }
    }

    public function findRoom()
    {
        $userId = JWTAuth::parseToken()->authenticate()->id;
        return UsersChat::where('user_id',$userId)->with(['chat.users'])->get();
    }
}
