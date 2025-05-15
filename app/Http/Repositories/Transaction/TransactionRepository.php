<?php

namespace App\Http\Repositories\Transaction;

use App\Models\Transaction;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use App\Interface\Transaction\TransactionRepositoryInterface;

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

    public function create(array $data)
    {
        DB::beginTransaction();

        try{
            $transaction = Transaction::create([
                "user_id" => $data['user_id'],
                "total_price" => $data['total_price'],
                "status" => $data['status'],
                "created_at" => $data['created_at']
            ]);

            $payment = Payment::create([
                "transaction_id" => $transaction->id,
                "methods" => $data['payment']['methods'] ?? 'COD',
                "status" => $data['payment']['status'] ?? 'not paid'
            ]);

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
