<?php

namespace App\Http\Repositories\Transaction;

use App\Models\Transaction;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use App\Interface\Transaction\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Log;

class TransactionRepository implements TransactionRepositoryInterface
{
    protected $model;

    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
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
                "transaction_id" => $transaction->id,
                "methods" => $data['payment']['methods'] ?? 'COD',
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

    public function update($id, array $data)
    {
        $transaction = $this->model->findOrFail($id);
        $transaction->update($data);
        return $transaction;
    }

    public function findById($id)
    {
        return Transaction::with('payment')->findOrFail($id);
    }

    public function delete($id)
    {
        $transaction = $this->model->findOrFail($id);
        $transaction->delete();
        return true;
    }

    public function getByUserId($userId)
    {
        return Transaction::with('payment')
            ->where('user_id', $userId)
            ->get();
    }
}
