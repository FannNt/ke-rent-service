<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Interface\Product\ProductRepositoryInterface;
use App\Interface\Transaction\TransactionRepositoryInterface;
use App\Interface\User\UserRepositoryInterface;
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
    protected $userRepository;
    public function __construct(UserRepositoryInterface $userRepository,TransactionRepository $transactionRepo, PaymentServices $paymentServices, ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->transactionRepository = $transactionRepo;
        $this->paymentServices = $paymentServices;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return $this->transactionRepository->all();
    }

    public function create(array $data)
    {

        $product = $this->productRepository->findById($data['product_id']);
        if ($product->user_id == auth()->id()) {
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('Cant buy your own product',403)
            );
        }
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
        return [
            "transaction" => $transaction->only('id','user_id','total_price','status','product_id'),
            "payment" => $payment->only('id','transaction_id','methods')
        ];
    }

    public function acceptTransaction($id)
    {
        $transaction = $this->transactionRepository->findById($id);
        if ($transaction->product->user->id != auth()->id()){
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('Forbidden',403)
            );
        }
        $transaction->user->notify(new BuyerApproveNotification($transaction));
        auth()->user()->notify(new SellerWaitingNotification($transaction));

        return $this->transactionRepository->updateStatus($id, TransactionStatus::APPROVED);
    }

    public function rejectTransaction($id)
    {
        $transaction = $this->transactionRepository->findById($id);
        if ($transaction->product->user->id != auth()->id()){
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

    public function addBill($transactionId)
    {
        $transaction = $this->transactionRepository->findById($transactionId);
        if (!$transaction) {
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('Not Found', 404)
            );
        }elseif($transaction->status != 'completed') {
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('Cant do that action | forbidden',403)
            );
        }

        $user = $transaction->product->user;
        $this->userRepository->addBill($user->id,$transaction->total_price);

        return true;
    }

    public function rating($id,$data)
    {
        $transaction = $this->transactionRepository->findById($id);

        if($transaction->status != 'completed'){
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('Cant rating before completed',403)
            );
        }

        return $this->productRepository->rating([
            'product_id' => $transaction->product->id,
            'rating' => $data['rating'],
            'message' => $data['message']
        ]);

    }
}
