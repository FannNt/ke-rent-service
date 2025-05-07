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
        return response()->json($transaction);
    }

    public function create(TransactionCreateRequest $request)
    {
        $data = $request->validated();
        $result = $this->transactionService->create($data);

        $transaction = $result['transaction'];
        $payment = $result['payment'];

        $response = [
            "message" => 'transaction created successfuly',
            "data" => [
                'transaction_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'total_price' => $transaction->total_price,
                'status' => $transaction->status,
                'payment' => [
                    'methods' => $payment->methods,
                    'status' => $payment->status
                ]
            ]
        ];

        return response()->json($response, 201);
    }

    public function findById($id): JsonResponse
    {
        $transaction = $this->transactionService->findById($id);
        return response()->json($transaction);
    }

    public function update(TransactionUpdateRequest $request,$id): JsonResponse
    {
        $transaction = $this->transactionService->update($id, $request->validated());
        return response()->json($transaction);
    }

    public function delete($id): JsonResponse
    {
        $this->transactionService->delete($id);
        return response()->json(['message' => 'transaction deleted successfuly']);
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
