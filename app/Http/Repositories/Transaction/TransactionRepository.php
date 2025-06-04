<?php

namespace App\Http\Repositories\Transaction;

use App\Models\Transaction;
use App\Models\Payment;
use App\TransactionStatus;
use Illuminate\Support\Facades\DB;
use App\Interface\Transaction\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Log;

class TransactionRepository implements TransactionRepositoryInterface
{


    public function all()
    {
        return Transaction::with(['product','payment']);
    }

    public function create(array $data,$userId)
    {
        DB::beginTransaction();
        Log::info('data',[$data]);

        try{
            $transaction = Transaction::create([
                "product_id" => $data['product_id'],
                "user_id" => $userId,
                "total_price" => $data['total_price'],
                'rent_day' =>$data['rent_day'],
                "rental_start" => $data['rental_start'],
                "rental_end" => $data['rental_end']
            ]);
            $transaction->refresh();



            $payment = Payment::create([
                "order_id" => uniqid().$transaction->id,
                "transaction_id" => $transaction->id,
                "methods" => $data['payment']['methods'] ?? 'Transfer'
                ]);
                $payment->refresh();


            DB::commit();
            return [
                "transaction" => $transaction,
                "payment" => $payment
            ];

        }catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
    }

    public function updateStatus($id,TransactionStatus $status)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'status' => $status
        ]);
        return $transaction;
    }


    public function findById($id)
    {
        return Transaction::with('payment','product.user','product.image')->findOrFail($id);
    }

    public function delete($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return true;
    }

    public function getByUserId($userId)
    {
        return Transaction::with('payment','product.user','product.image')
            ->where('user_id', $userId)
            ->get();
    }
}
