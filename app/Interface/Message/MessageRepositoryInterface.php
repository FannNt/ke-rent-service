<?php

namespace App\Interface\Message;

interface MessageRepositoryInterface
{
    public function create(array $data);

    public function getMessageByChatId(int $chatId);
}
