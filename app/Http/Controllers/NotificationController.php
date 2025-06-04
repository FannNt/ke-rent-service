<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Resources\NotificationResource;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    protected $transactionService;
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }


    public function index()
    {
        $notifications =  auth()->user()->notifications()->latest()->paginate(10);
        $notifications->getCollection()->transform(function ($notification){
            $transactionId = $notification->data['transaction_id'];

            $notification->transaction = Transaction::with('product.user','product.image')->find($transactionId);

            return $notification;
        });
        Log::info($notifications);

        return ApiResponse::sendResponse(NotificationResource::collection($notifications),'success get all user notification');
    }

    public function readNotification($id)
    {
        $notification = auth()->user()->unreadNotifications()->find($id);
        if (!$notification) {
            return ApiResponse::sendErrorResponse('failed to mark as read');
        }
        $notification->markAsRead();

        return ApiResponse::sendResponse('','Success read notification');
    }

    public function count()
    {
        $notification = auth()->user()->unreadNotifications();
        $unread = $notification->count();

        return ApiResponse::sendResponse($unread,'Success count unread notification');
    }

}
