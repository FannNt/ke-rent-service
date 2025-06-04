<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionHistoryResource;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use App\Classes\ApiResponse;
use App\Http\Requests\Transaction\TransactionCreateRequest;
use App\Http\Requests\Transaction\TransactionUpdateRequest;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class TransactionController extends Controller
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(): JsonResponse
    {
        $transaction = $this->transactionService->index();
        return ApiResponse::sendResponse($transaction, '');
    }

    public function create(TransactionCreateRequest $request)
    {
        $data = $request->validated();
        $result = $this->transactionService->create($data);

        return ApiResponse::sendResponse($result,"transaction created successfully", 201);
    }

    public function acceptTransaction($id)
    {
        $data = $this->transactionService->acceptTransaction($id);
        return ApiResponse::sendResponse($data,'success approve transaction');
    }
    public function rejectTransaction($id)
    {
        $data = $this->transactionService->rejectTransaction($id);
        return ApiResponse::sendResponse($data,'success reject transaction');
    }



    public function findById($id): JsonResponse
    {
        $transaction = $this->transactionService->findById($id);
        return ApiResponse::sendResponse(new TransactionResource($transaction),'');
    }

    public function update(TransactionUpdateRequest $request,$id): JsonResponse
    {
        $transaction = $this->transactionService->update($id, $request->validated());
        return ApiResponse::sendResponse($transaction,'');
    }

    public function delete($id): JsonResponse
    {
        $this->transactionService->delete($id);
        return ApiResponse::sendResponse('','transaction deleted successfully');
    }

    public function transactionHistory($userId)
    {
        $transactions = $this->transactionService->getByUserId($userId);

        return ApiResponse::sendResponse(TransactionHistoryResource::collection($transactions),'');
    }
}
