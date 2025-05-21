<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Interface\Product\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\User;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class ProductService implements ServiceInterface
{
    protected $productRepository;
    protected $cloudinaryService;

    public function __construct(ProductRepositoryInterface $productRepository, CloudinaryService $cloudinaryService)
    {
        $this->productRepository = $productRepository;
        $this->cloudinaryService = $cloudinaryService;
    }

    public function index()
    {
        return $this->productRepository->all();
    }

    public function create(array $data)
    {
        $image = $this->cloudinaryService->uploadProduct($data['image']);
        $data['image'] = $image['url'];
        $data['image_publicId'] = $image['public_id'];
        return $this->productRepository->create($data);
    }

    public function update($id,$data)
    {
        $product = Product::findOrFail($id);
        if (!auth()->id() == $product->user_id){
            throw new UnauthorizedException('Unauthorized');
        }
        if (isset($data['image'])) {
            $this->cloudinaryService->deleteProduct($product->image_publicId);
            $image =$this->cloudinaryService->uploadProduct($data['image']);
            $data['image'] = $image['url'];
            $data['image_publicId'] = $image['public_id'];
        }

        $product->update($data);

        return $product->fresh();
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        if (!auth()->id() == $product->user_id){
            throw new UnauthorizedException('Unauthorized');
        }
        $product->delete($id);
        $this->cloudinaryService->deleteProduct($product->image_publicId);
        return true;
    }

    public function findById($id)
    {
        return $this->productRepository->findById($id);
    }

    public function findUserProduct()
    {
        $user = auth()->id();
        $product = $this->productRepository->findUserProduct($user);
        return $product;
    }


    public function getByUserId($userId)
    {
        $product = $this->productRepository->findUserProduct($userId);
        return $product;
    }
}
