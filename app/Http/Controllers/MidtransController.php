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
        $payment = Payment::with('transaction')->where('order_id', $request->order_id)->first();

        if (!$payment) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $payment->methods = $request->payment_type;

        // Update status based on Midtrans notification
        switch ($request->transaction_status) {
            case 'capture':
            case 'settlement':
                $payment->status = 'paid';
                $payment->transaction->status = 'completed';
                break;

            case 'expire':
                $payment->status = 'expired';
                break;

            case 'cancel':
            case 'deny':
                $payment->status = 'failed';
                break;

            case 'pending':
                $payment->status = 'waiting_payment';
                break;
        }

        $payment->save();

        return response()->json(['message' => 'Notification handled'], 200);
    }

}
