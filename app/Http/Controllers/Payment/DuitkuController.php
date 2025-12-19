<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Gateway\Duitku;
use Session;
use Illuminate\Support\Facades\Log;

class DuitkuController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('DuitkuController Callback - Request received.');
        // Duitku::handleCallback will process the request and return a response for Duitku
        // It also sets Session::put('payment_info') based on the payment status
        return Duitku::handleCallback($request);
    }

    public function success()
    {
        Log::info('DuitkuController Success - Redirected to success URL.');
        // This method is for the user's return URL after successful payment
        // You can retrieve payment_info from session and display a success message
        $paymentInfo = Session::get('payment_info');
        Log::info('DuitkuController Success - Payment Info from session: ' . json_encode($paymentInfo));
        // Clear the session data after use
        Session::forget('payment_info');

        if ($paymentInfo && $paymentInfo['payment_status'] == 1) {
            // Payment was successful, redirect to the original success URL
            return redirect(Duitku::redirect_if_payment_success());
        } else {
            // Payment was not successful or session expired, redirect to failure URL
            return redirect(Duitku::redirect_if_payment_faild());
        }
    }

    public function failed()
    {
        Log::info('DuitkuController Failed - Redirected to failed URL.');
        // This method is for the user's return URL after failed payment
        $paymentInfo = Session::get('payment_info');
        Log::info('DuitkuController Failed - Payment Info from session: ' . json_encode($paymentInfo));
        // Clear the session data after use
        Session::forget('payment_info');

        // Redirect to the original failure URL
        return redirect(Duitku::redirect_if_payment_faild());
    }
}
