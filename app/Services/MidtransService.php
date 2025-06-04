<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createToken(array $data)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $data['transaction.id'],
                'gross_amount' => $data['transaction.total_price'],
            ],
            'customer_details' => [
                'fisrt_name' => auth()->user()->username,
                'email' => auth()->user()->email
            ],
            'item_details' => [
                [
                    'id' => $data['product.id'],
                    'name' => $data['product.name'],
                    'quantity' => $data['transaction.rent_day'],
                    'price' => $data['product.price']

                ]
            ]
        ];
        Snap::getSnapToken($params);
    }
}
