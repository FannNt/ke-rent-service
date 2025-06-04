<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Http\Repositories\Transaction\TransactionRepository;
use App\Models\Payment;
use App\Interface\Payment\PaymentRepositoryInterface;
use Cloudinary\Cloudinary;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use App\Http\Repositories\Payment\PaymentRepositories;

class PaymentServices
{
    protected $PaymentRepo;
    protected $midtransService;
    protected $transactionRepository;

    public function __construct(PaymentRepositories $PaymentRepo, MidtransService $midtransService, TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->PaymentRepo = $PaymentRepo;
        $this->midtransService = $midtransService;
    }

    public function pay($transactionId)
    {
        $transaction = $this->transactionRepository->findById($transactionId);

        if (!$transaction) {
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('Transaction Not Found',404)
            );
        } elseif($transaction->status != 'approved') {
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('cant do that action right now',403)
            );
        }
        $order_id = $transaction->payment->order_id;
        $transaction->order_id = $order_id;
        $token = $this->midtransService->createToken($transaction);
        return [
            'token' => $token,
            'order_id' => $order_id
        ];
    }

    public function update($id, array $data)
    {
        return $this->PaymentRepo->update($id, $data);
    }

    public function findByID($id)
    {
        return $this->PaymentRepo->findById($id);
    }

    public function index()
    {

    }

    public function getByUserId($userId)
    {

    }

    public function delete($id)
    {

    }
}
