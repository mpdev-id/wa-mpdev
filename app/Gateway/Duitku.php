<?php

namespace App\Gateway;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Duitku\Pop;
use Duitku\Config;
use Illuminate\Support\Facades\Log;

class Duitku
{
    public static function redirect_if_payment_success()
    {
        return route('duitku.success');
    }

    public static function redirect_if_payment_faild()
    {
        return route('duitku.failed');
    }

    public static function make_payment($array)
    {
        $config = new Config(
            config('duitku.merchant_key'),
            config('duitku.merchant_code'),
            (bool)config('duitku.sandbox'), // isSandboxMode
            true, // isSanitizedMode
            true  // duitkuLogs
        );

        $paymentAmount = $array['pay_amount'];
        $merchantOrderId = 'sen-' . Str::random(10); // Unique order ID
        $productDetails = $array['billName'];
        $customerVaName = $array['name'];
        $email = $array['email'];
        $phoneNumber = $array['phone'];
        $callbackUrl = url('/payment/duitku/callback'); // Duitku will post to this URL
        $returnUrl = route('duitku.success'); // User will be redirected here after payment
        $expiryPeriod = 60; // in minutes

        // Store necessary data in session for callback verification and processing
        Session::put('duitku_payment_data', [
            'merchant_order_id' => $merchantOrderId,
            'amount' => $array['amount'], // Original amount before charge
            'charge' => $array['charge'],
            'getway_id' => $array['getway_id'],
            'main_amount' => $array['main_amount'] ?? $array['amount'],
        ]);

        try {
            $params = [
                'paymentAmount' => $paymentAmount,
                'merchantOrderId' => $merchantOrderId,
                'productDetails' => $productDetails,
                'customerVaName' => $customerVaName,
                'email' => $email,
                'phoneNumber' => $phoneNumber,
                'callbackUrl' => $callbackUrl,
                'returnUrl' => $returnUrl,
                'expiryPeriod' => $expiryPeriod,
            ];

            $paymentMethod = $array['payment_method'] ?? 'VC'; // Default to Virtual Account if not provided
            $params['paymentMethod'] = $paymentMethod; // Add paymentMethod to params array
            $response = \Duitku\Pop::createInvoice($params, $config);
            $response = json_decode($response, true);

            if (isset($response['paymentUrl'])) {
                return redirect($response['paymentUrl']);
            } else {
                // Handle error from Duitku API
                Session::forget('duitku_payment_data');
                Session::flash('error', $response['message'] ?? 'Duitku payment initiation failed.');
                return redirect(Duitku::redirect_if_payment_faild());
            }
        } catch (\Exception $e) {
            Session::forget('duitku_payment_data');
            Session::flash('error', 'Duitku payment initiation failed: ' . $e->getMessage());
            return redirect(Duitku::redirect_if_payment_faild());
        }
    }

    public static function handleCallback(Request $request)
    {
        $config = new Config(
            config('duitku.merchant_key'),
            config('duitku.merchant_code'),
            (bool)config('duitku.sandbox')
        );

        try {
            Log::info('Duitku Callback - Raw Request Data: ' . json_encode($request->all()));
            $callbackJson = \Duitku\Pop::callback($config);
            Log::info('Duitku Callback - JSON Response from Pop::callback: ' . $callbackJson);
            $callbackData = json_decode($callbackJson, true);
            Log::info('Duitku Callback - Decoded Callback Data: ' . json_encode($callbackData));
            Log::info('Duitku Callback - Result Code: ' . ($callbackData['resultCode'] ?? 'N/A'));

            // At this point, $callbackData contains verified information from Duitku
            // You should use $callbackData['merchantOrderId'] to find your order
            // and update its status.

            // For demonstration, we'll use session data if available, but in a real app,
            // you'd query your database using merchantOrderId.
            $sessionData = Session::get('duitku_payment_data');

            if ($callbackData['resultCode'] == '00') {
                // Payment successful
                $data = [
                    'payment_id' => $callbackData['reference'],
                    'payment_method' => "duitku",
                    'getway_id' => $sessionData['getway_id'] ?? null,
                    'amount' => $sessionData['main_amount'] ?? $callbackData['amount'],
                    'charge' => $sessionData['charge'] ?? 0,
                    'status' => 1,
                    'payment_status' => 1,
                    'merchant_order_id' => $callbackData['merchantOrderId'],
                ];
                Session::forget('duitku_payment_data');
                Session::put('payment_info', $data);
                return response('SUCCESS', 200); // Duitku expects 'SUCCESS' string
            } else {
                // Payment failed or pending
                $data = [
                    'payment_status' => 0,
                    'merchant_order_id' => $callbackData['merchantOrderId'],
                    'message' => $callbackData['message'] ?? 'Payment failed',
                ];
                Session::forget('duitku_payment_data');
                Session::put('payment_info', $data);
                return response('FAILED', 200); // Duitku expects 'FAILED' string
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Duitku Callback Error: ' . $e->getMessage());
            return response('FAILED', 500); // Indicate failure to Duitku
        }
    }
}