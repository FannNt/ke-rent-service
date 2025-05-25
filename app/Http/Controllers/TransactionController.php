<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
        $data = $result['data'];
        $snapToken = $result['snap_token'];

        $response = [
            "data" => $data,
            "snap_token" => $snapToken
        ];

        return ApiResponse::sendResponse($response,"transaction created successfully", 201);
    }

    public function findById($id): JsonResponse
    {
        $transaction = $this->transactionService->findById($id);
        return ApiResponse::sendResponse($transaction,'');
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

    public function getByUserId($userId)
    {
        $transactions = $this->transactionService->getByUserId($userId);

        return response()->json([
            'status' => 'success',
            'data' => [
                'user_id' => $userId,
                'transactions' => $transactions
            ]
        ]);
    }
}
