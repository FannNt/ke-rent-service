<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Interface\Product\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\User;
use Cloudinary\Cloudinary;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
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

        $clean = Arr::except($data,['images']);
        $product = $this->productRepository->create($clean);

        foreach ($data['images'] as $image) {
            $data = $this->cloudinaryService->uploadProduct($image);
            $this->productRepository->addProductImage($data,$product->id);
        }

        return $product->load('image');
    }

    public function update($id,$data)
    {
        $product = $this->productRepository->findById($id);
        if (auth()->id() != $product->user_id){
            throw new UnauthorizedException('Unauthorized');
        }

        $clear = Arr::except($data,['images']);

        $product->update($clear);

        return $product->fresh();
    }

    public function delete($id)
    {
        $product = $this->productRepository->findById($id);
        if (auth()->id() != $product->user_id){
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('Unauthorized',401)
            );
        }
        foreach ($product->image->pluck('image_publicId') as $image) {
            Log::info($image);
            $this->cloudinaryService->deleteProduct($image);
        }
        $product->delete($id);
        Log::info("success delete product $product->id, from user $product->user_id");
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
