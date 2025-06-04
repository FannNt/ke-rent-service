<?php

namespace App\Http\Repositories\Product;

use App\Interface\Product\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;

class ProductRepositories implements ProductRepositoryInterface {
    public function all()
    {
        return Product::with('image')->get();
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $user = Product::findOrFail($id);
        $user->update($data);
        return $user->load('image');
    }

    public function delete($id)
    {
        return Product::delete($id);
    }

    public function findById($id)
    {
        return Product::with(['image','user.status'])->findOrFail($id);
    }

    public function findUserProduct($userId)
    {
        return Product::with('image')->where('user_id',$userId)->get();
    }

    public function addProductImage($image,$productId)
    {
        return ProductImage::create([
            'product_id' => $productId,
            'image' => $image['url'],
            'image_publicId' => $image['public_id']
        ]);
    }
}
