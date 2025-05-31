<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Interface\Product\ProductRepositoryInterface;
use App\Interface\Transaction\TransactionRepositoryInterface;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use App\Http\Repositories\Transaction\TransactionRepository;
use App\Services\PaymentServices;
use Midtrans\Config;
use Midtrans\Snap;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class TransactionService implements ServiceInterface
{
    protected $transactionRepository;
    protected $paymentServices;
    protected $productRepository;

    public function __construct(TransactionRepository $transactionRepo, PaymentServices $paymentServices, ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->transactionRepository = $transactionRepo;
        $this->paymentServices = $paymentServices;
    }

    public function index()
    {
        return $this->transactionRepository->all();
    }

    public function create(array $data)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $product = $this->productRepository->findById($data['product_id']);
        $start = Carbon::parse($data['rental_start']);
        $end = Carbon::parse($data['rental_end']);
        $rentDay =  $start->diffInDays($end);

        $total_price = $rentDay * $product->price;

        $data['total_price'] = $total_price;
        $data['rent_day'] = $rentDay;
        Log::info('data', $data);
        $result = $this->transactionRepository->create($data,auth()->id());
        $transaction = $result['transaction'];
        $payment = $result['payment'];

        $transaction->setRelation('product',$product);
        $product = $transaction->product;
        $params = [
            'transaction_details' => [
                'order_id' => $transaction->id,
                'gross_amount' => $transaction->total_price,
            ],
            'customer_details' => [
                'fisrt_name' => auth()->user()->username,
                'email' => auth()->user()->email
            ],
            'item_details' => [
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $transaction->rent_day,
                    'price' => $product->price

                ]
            ]
        ];

        $response = [
            "transaction" => $transaction->only('id','user_id','total_price','status','product_id'),
            "payment" => $payment->only('id','transaction_id','methods')
        ];
        if ($payment->methods != "COD"){
            $snapToken = Snap::getSnapToken($params);
            $response['snap_token'] = $snapToken;
        }
        Log::info($transaction);
        return $response;
    }

    public function update($id, array $data)
    {
        return $this->transactionRepository->update($id, $data);
    }

    public function findById($id)
    {
        $transaction = $this->transactionRepository->findById($id);
        $user = JWTAuth::parseToken()->authenticate()->id;

        if(!$transaction || $transaction->user_id !== $user){
            return  ApiResponse::sendErrorResponse('Unauthorized',403);
        }

        return $transaction;
    }

    public function delete($id)
    {
        return $this->transactionRepository->delete($id);
    }

    public function getByUserId($userId)
    {
        return $this->transactionRepository->getByUserId($userId);
    }
}
