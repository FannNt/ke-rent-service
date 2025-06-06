<?php

namespace App\Interface\Product;

use App\Models\User;

interface ProductRepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update($id,array $data);

    public function delete($id);

    public function findById($id);

    public function findUserProduct($userId);

    public function addProductImage($image,$productId);

    public function rating(array $data);
}
