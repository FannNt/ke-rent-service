<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\ApiResponse;
use App\Http\Requests\Product\TransactionCreateRequest;
use App\Http\Requests\Product\TransactionUpdateRequest;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(): JsonResponse
    {
        $transaction = $this->transactionService->getAllTransactions();
        return response()->json($transaction);
    }

    public function create(TransactionCreateRequest $request): JsonResponse
    {
        $transaction = $this->transactionService->create($request->validated());
        return response()->json($transaction, 201);
    }

    public function read($id): JsonResponse
    {
        $transaction = $this->transactionService->getTransactionById($id);
        return response()->json($transaction);
    }

    public function update(TransactionUpdateRequset $request,$id): JsonResponse
    {
        $transaction = $this->transactionService->update($id, $request->validated());
        return response()->json($transaction);
    }

    public function delete($id): JsonResponse
    {
        $this->transactionService->delete($id);
        return response()->json(['massage' => 'transaction deleted successfuly']);
    }

    public function findById($id)
    {
        $transaction = $this->transactionService->findById($id);
        return ApiResponse::sendResponse($transaction, '');
    }
}
