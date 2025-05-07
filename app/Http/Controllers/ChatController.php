<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Requests\Chat\CreateChatRequest;
use App\Http\Requests\Message\SendMessageRequest;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class ChatController extends Controller
{
    protected $chatService;
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function create(CreateChatRequest $request)
    {
        $chat = $this->chatService->createChat($request->validated());
        return ApiResponse::sendResponse($chat,'Success Create Chat', 201);
    }

    public function sendMessage(SendMessageRequest $request, int $chatId)
    {
        $message = $this->chatService->sendMessage($chatId,$request->validated()['message']);
        return ApiResponse::sendResponse($message,'');
    }

    public function getMessages(int $chatId)
    {
        try {
            $message = $this->chatService->getMessages($chatId);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return ApiResponse::sendResponse($message,'');
    }
}
