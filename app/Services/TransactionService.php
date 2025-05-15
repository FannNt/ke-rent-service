<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Interface\Transaction\TransactionRepositoryInterface;
use App\Models\Transaction;
use App\Models\User;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use App\Http\Repositories\Transaction\TransactionRepository;
use App\Services\PaymentServices;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class TransactionService implements ServiceInterface
{
    protected $transactionRepository;
    protected $paymentServices;

    public function __construct(TransactionRepository $transactionRepo, PaymentServices $paymentServices)
    {
        $this->transactionRepository = $transactionRepo;
        $this->paymentServices = $paymentServices;
    }

    public function index()
    {
        return $this->transactionRepository->all();
    }

    public function create(array $data)
    {
        return $this->transactionRepository->create($data);
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
