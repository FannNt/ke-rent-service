<?php

namespace App\Interface\Transaction;

use App\Models\User;
use App\Models\Product;

interface TransactionRepositoryInterface
{
    public function all();

    public function create(array $data,$userId);

    public function update($id,array $data);

    public function delete($id);

    public function findById($id);

    public function getByUserId($userId);
}
