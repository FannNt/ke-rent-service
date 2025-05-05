<?php

namespace App\Interface\Chat;

use App\Models\Chat;

interface ChatRepositoryInterface
{
    public function create(array $data);

    public function addParticipant(Chat $chat,array $userIds);

    public function findById(int $chatId);

    public function checkUserChat($chatId);
}
