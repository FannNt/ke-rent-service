<?php

namespace App\Interface\Transaction;

use App\Models\User;
use App\Models\Product;
use App\TransactionStatus;

interface TransactionRepositoryInterface
{
    public function all();

    public function create(array $data,$userId);

    public function updateStatus($id, TransactionStatus $status);

    public function delete($id);

    public function findById($id);

    public function getByUserId($userId);
}
