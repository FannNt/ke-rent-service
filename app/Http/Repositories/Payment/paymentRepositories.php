<?php

namespace App\Http\Repositories\Payment;

use App\Models\Payment;
use App\Interface\Payment\PaymentRepositoryInterface;

class PaymentRepositories implements PaymentRepositoryInterface
{
    public function create(array $data)
    {
        return Payment::create($data);
    }

    public function update($id, array $data)
    {
        $payment = Payment::findOrFail($id);
        $payment->update($data);

        return $payment;
    }

    public function findById($id)
    {
        return Payment::findOrFail($id);
    }
}