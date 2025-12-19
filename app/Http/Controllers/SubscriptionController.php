<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Session;

class SubscriptionController extends Controller
{
    public function success(Request $request)
    {
        Log::info('SubscriptionController Success - Duitku return received.');
        Log::info('Duitku Return Params: ' . json_encode($request->all()));

        $resultCode = $request->input('resultCode');
        $merchantOrderId = $request->input('merchantOrderId');
        $reference = $request->input('reference');

        // Here you would typically verify the payment status with Duitku's API
        // using the merchantOrderId and reference, and then update your database.
        // For now, we'll just check the resultCode.

        if ($resultCode === '00') {
            // Payment was successful
            // You should update your subscription status in the database here.
            // Example: Subscription::where('order_id', $merchantOrderId)->update(['status' => 'paid']);
            Session::flash('success', 'Pembayaran langganan berhasil!');
            return view('subscription.success', compact('merchantOrderId', 'reference'));
        } else {
            // Payment failed or is pending
            Session::flash('error', 'Pembayaran langganan gagal atau tertunda. Kode: ' . $resultCode);
            return view('subscription.failed', compact('merchantOrderId', 'resultCode'));
        }
    }
}
