<?php

namespace App\Interface\Payment;

use App\Models\Transaction;

interface PaymentRepositoryInterface
{
    public function create(array $data);

    public function update($id, array $data);

    public function findById($id);
}