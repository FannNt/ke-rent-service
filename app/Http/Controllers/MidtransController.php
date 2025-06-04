<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        Log::info($request);
        $serverKey = config('midtrans.server_key');
        $signatureKey = hash('sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($signatureKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Get transaction by order_id
        $transaction = Payment::where('transaction_id', $request->order_id)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Update status based on Midtrans notification
        switch ($request->transaction_status) {
            case 'capture':
            case 'settlement':
                $transaction->status = 'paid';
                break;

            case 'expire':
                $transaction->status = 'expired';
                break;

            case 'cancel':
            case 'deny':
                $transaction->status = 'failed';
                break;

            case 'pending':
                $transaction->status = 'waiting_payment';
                break;
        }

        $transaction->save();

        return response()->json(['message' => 'Notification handled'], 200);
    }

}
