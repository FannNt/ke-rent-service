<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class ProductController extends Controller
{
    private $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $product = $this->productService->index();

        return ApiResponse::sendResponse(ProductResource::collection($product),'');
    }

    public function create(ProductCreateRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->validated());
        return ApiResponse::sendResponse(new ProductResource($product),'Success Create Product', 201);
    }

    public function update($id, ProductUpdateRequest $request): JsonResponse
    {
        try {
            $product = $this->productService->update($id, $request->validated());
            return ApiResponse::sendResponse(new ProductResource($product), 'Product updated successfully');
        } catch (UnauthorizedException $e) {
            return ApiResponse::sendErrorResponse($e->getMessage(), 403);
        } catch (\Exception $e) {
            Log::error('Product update failed', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return ApiResponse::sendErrorResponse('Failed to update product');
        }
    }

    public function delete($id)
    {
        $product = $this->productService->delete($id);
        if ($product){
            return ApiResponse::sendResponse('','Success');
        }
        return ApiResponse::sendErrorResponse('Failed Delete Product');
    }

    public function findById($id)
    {
        $product = $this->productService->findById($id);
        return ApiResponse::sendResponse(new ProductResource($product),'');
    }

    public function findUserProduct()
    {
        $data = $this->productService->findUserProduct();
        return ApiResponse::sendResponse(ProductResource::collection($data),'');
    }

    public function findProductByUserId($userId)
    {
        $data = $this->productService->getByUserId($userId);
        return ApiResponse::sendResponse(ProductResource::collection($data),"Success get product from $userId");
    }

}
