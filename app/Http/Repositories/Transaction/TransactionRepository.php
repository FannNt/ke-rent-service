<?php

namespace App\Http\Repositories\Transaction;

use App\Models\Transaction;
use App\Interface\Product\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\User;

class TransactionRepositories implements ProductRepositoryInterface {
    public function all()
    {
        return Transaction::all();
    }

    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function update($id, array $data)
    {
        $user = Transaction::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        return Transaction::delete($id);
    }

    public function findById($id)
    {
        return Transaction::findOrFail($id);
    }

    public function findUserProduct(User $user)
    {
        return $user->products()->get();
    }
}
