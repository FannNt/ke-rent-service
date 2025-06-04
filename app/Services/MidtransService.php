<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use PHPUnit\Exception;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createToken($data)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $data->order_id,
                'gross_amount' => $data->total_price,
            ],
            'customer_details' => [
                'fisrt_name' => auth()->user()->username,
                'email' => auth()->user()->email
            ],
            'item_details' => [
                [
                    'id' => $data->product->id,
                    'name' => $data->product->name,
                    'quantity' => $data->rent_day,
                    'price' => $data->product->price

                ]
            ]
        ];
            return Snap::getSnapToken($params);
    }
}
