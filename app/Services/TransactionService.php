<?php

namespace App\Service;

use App\Classes\ApiResponse;
use App\Interface\Transaction\TransactionRepositoryInterface;
use App\Models\Transaction;
use App\Models\User;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use App\Repositories\TransactionRepository;

class TransactionService extends ServiceInterface
{
    protected $transactionRepo;

    public function __construct(TransactionRepository $transactionRepo)
    {
        $this->transactionRepo = $transactionRepo;
    }

    public function index()
    {
        return $this->transactionRepo->all();
    }

    public function createW(array $data)
    {
        return $this->transactionRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->transactionRepository->update($id, $data);
    }

    public function getAllTransactions()
    {
        return $this->transactionRepository->getAll();
    }

    public function getTransactionById($id)
    {
        return $this->transactionRepository->findById($id);
    }

    public function delete($id)
    {
        return $this->transactionRepository->delete($id);
    }
}