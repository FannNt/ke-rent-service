<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Interface\Transaction\TransactionRepositoryInterface;
use App\Models\Transaction;
use App\Models\User;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use App\Http\Repositories\Transaction\TransactionRepositories;
use App\Services\PaymentServices;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class TransactionService implements ServiceInterface
{
    protected $transactionRepository;
    protected $PaymentServices;

    public function __construct(TransactionRepositories $transactionRepo, PaymentServices $PaymentServices)
    {
        $this->transactionRepository = $transactionRepo;
        $this->paymentServices = $PaymentServices;
    }
    
    public function index()
    {
        return $this->transactionRepository->all();
    }

    public function create(array $data)
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $result = $this->transactionRepository->create($data);
        $transaction = $result['transaction'];
        $payment = $result['payment'];

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->id,
                'gross_amount' => $transaction->total_price,
            ]
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return [
            "data" => [
                'transaction_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'total_price' => $transaction->total_price,
                'status' => $transaction->status,
                'payment' => [
                    'methods' => $payment->methods,
                    'status' => $payment->status
                ]
            ],
            "snap_token" => $snapToken
        ];
    }

    public function update($id, array $data)
    {
        return $this->transactionRepository->update($id, $data);
    }

    public function findById($id)
    {
        $transaction = $this->transactionRepository->findById($id);
        $user = JWTAuth::parseToken()->authenticate()->id;

        if(!$transaction || $transaction->user_id !== $user){
            abort(403, 'Unauthorized');
        }

        return $transaction;
    }

    public function delete($id)
    {
        return $this->transactionRepository->delete($id);
    }

    public function getByUserId($userId)
    {
        return $this->transactionRepository->getByUserId($userId);
    }
}