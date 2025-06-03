<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Interface\Product\ProductRepositoryInterface;
use App\Interface\Transaction\TransactionRepositoryInterface;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\BuyerApproveNotification;
use App\Notifications\BuyerRejectNotification;
use App\Notifications\BuyerWaitingNotification;
use App\Notifications\SellerBuyedNotification;
use App\Notifications\SellerWaitingNotification;
use App\TransactionStatus;
use Carbon\Carbon;
use Cloudinary\Cloudinary;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use App\Http\Repositories\Transaction\TransactionRepository;
use App\Services\PaymentServices;
use Midtrans\Config;
use Midtrans\Snap;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class TransactionService
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

        $product = $this->productRepository->findById($data['product_id']);
        $start = Carbon::parse($data['rental_start']);
        $end = Carbon::parse($data['rental_end']);
        $rentDay =  $start->diffInDays($end);

        $total_price = $rentDay * $product->price;

        $data['total_price'] = $total_price;
        $data['rent_day'] = $rentDay;
        $result = $this->transactionRepository->create($data,auth()->id());
        $transaction = $result['transaction'];
        $payment = $result['payment'];
        $transaction->user->notify(new BuyerWaitingNotification($transaction));
        $transaction->product->user->notify(new SellerBuyedNotification($transaction));
        $response = [
            "transaction" => $transaction->only('id','user_id','total_price','status','product_id'),
            "payment" => $payment->only('id','transaction_id','methods')
        ];

        Log::info($transaction);
        return $response;
    }

    public function acceptTransaction($id)
    {
        $transaction = $this->transactionRepository->findById($id);
        if ($transaction->product->user != auth()->id()){
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('Forbidden',403)
            );
        }
        $transaction->user->notify(new BuyerApproveNotification($transaction));
        auth()->notify(new SellerWaitingNotification($transaction));

        return $this->transactionRepository->updateStatus($id, TransactionStatus::APPROVED);
    }

    public function rejectTransaction($id)
    {
        $transaction = $this->transactionRepository->findById($id);
        if ($transaction->product->user != auth()->id()){
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('Forbidden',403)
            );
        }

        $transaction->user->notify(new BuyerRejectNotification($transaction));
        $this->transactionRepository->updateStatus($id, TransactionStatus::REJECTED);

        return true;
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
