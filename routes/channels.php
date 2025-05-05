<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\UsersChat;
use Illuminate\Support\Facades\Log;

Broadcast::routes([
    'prefix' => 'api',
    'middleware' => ['auth:api']
]);

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    Log::info('Channel authorization attempt', [
        'user_id' => $user->id,
        'chat_id' => $chatId
    ]);

    $isParticipant = UsersChat::where('chat_id', $chatId)
        ->where('user_id', $user->id)
        ->exists();

    Log::info('Channel authorization result', [
        'is_participant' => $isParticipant
    ]);

    return $isParticipant;
});
