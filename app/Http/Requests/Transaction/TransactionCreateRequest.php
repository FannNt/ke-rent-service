<?php

namespace App\Http\Requests\Transaction;

use App\Classes\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'payment_id'  => 'required|exists:payment,id',
            'user_id'     => 'required|exists:users,id',
            'total_price' => 'required|integer',
            'status'      => 'required|in:pending,approved,rejected,completed',
            'created_at'   => 'required|date',
            'payment.methods' => 'required|in:COD,Transfer,Other',
            'payment.status' => 'required|in:paid,not paid'
        ];
    }
}