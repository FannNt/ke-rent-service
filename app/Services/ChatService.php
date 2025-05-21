<?php

namespace App\Services;


use App\Classes\ApiResponse;
use App\Events\MessageSent;
use App\Interface\Chat\ChatRepositoryInterface;
use App\Interface\Message\MessageRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ChatService
{
    protected $chatRepository;
    protected $messageRepository;

    public function __construct(ChatRepositoryInterface $chatRepository, MessageRepositoryInterface $messageRepository)
    {
        $this->chatRepository = $chatRepository;
        $this->messageRepository = $messageRepository;
    }

    public function show()
    {
        return $this->chatRepository->findRoom();
    }

    public function createChat(array $data)
    {
        Log::info('Participants', $data);
        $participants = $data['participants'];
        $chat = $this->chatRepository->create([]);
        $this->chatRepository->addParticipant($chat,array_merge($participants, [auth()->id()]));
        return $chat;
    }

    public function sendMessage(int $chatId, string $message)
    {
        $user = $this->chatRepository->checkUserChat($chatId);
        if (!$user) {
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('Unauthorized',401)
            );
        }

        $messageModel = $this->messageRepository->create([
            'chat_id' => $chatId,
            'user_id' => auth()->id(),
            'message' => $message
        ]);

        broadcast(new MessageSent($chatId, $message))->toOthers();

        return $messageModel;
    }

    public function getMessages(int $chatId)
    {
        $user = $this->chatRepository->checkUserChat($chatId);
        if(!$user){
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('Unauthorized',401)
            );
        }
        return $this->messageRepository->getMessageByChatId($chatId);
    }
}
