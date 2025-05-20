<?php

namespace App\Http\Repositories\Product;

use App\Interface\Product\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\User;

class ProductRepositories implements ProductRepositoryInterface {
    public function all()
    {
        return Product::all();
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $user = Product::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        return Product::delete($id);
    }

    public function findById($id)
    {
        return Product::findOrFail($id);
    }

    public function findUserProduct($userId)
    {
        return Product::where('user_id',$userId)->get();
    }
}
