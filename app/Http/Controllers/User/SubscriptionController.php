<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Gateway;
use App\Models\Order;
use App\Traits\Notifications;
use Session;
use Auth;
use DB;
use Storage;
use Illuminate\Support\Facades\Cache;

class SubscriptionController extends Controller
{
    use Notifications;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if user has any pending order
        $pendingOrder = Order::where('user_id', Auth::id())->where('status', 2)->first();
        if ($pendingOrder) {
            return redirect()->route('user.subscription.invoice', $pendingOrder->id);
        }

        $plans = Plan::where('status',1)->where('price','>',0)->latest()->get();
        $subscription = Auth::user();

        return view('user.subscription.index',compact('plans', 'subscription'));
    }

    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function invoice($id)
    {
        $order = Order::where('user_id', Auth::id())->find($id);
        
        if (empty($order)) {
            Session::flash('saas_error', __('Tagihan tidak ditemukan.'));
            return redirect('/user/subscription');
        }

        if ($order->status != 2) {
            Session::flash('saas_error', __('Transaksi ini sudah selesai atau dibatalkan.'));
            return redirect('/user/subscription');
        }
        
        $plan = Plan::where('status', 1)->where('price', '>', 0)->find($order->plan_id);
        if (empty($plan)) {
            Session::flash('saas_error', __('Paket tidak ditemukan.'));
            return redirect('/user/subscription');
        }
        
        $gateways = Gateway::where('status', 1)->get();
        $tax = $order->tax;
        $total = $order->amount;
        
        $qrisGateway = $gateways->where('namespace', 'App\Gateway\CustomGateway')->first() ?? $gateways->first();
        $dynamic_qris = '';
        if ($qrisGateway) {
            $gatewayData = json_decode($qrisGateway->data ?? '{}', true);
            $qrisString = $gatewayData['qris_string'] ?? '';
            if (!empty($qrisString)) {
                $dynamic_qris = $this->makeDynamicQRIS($qrisString, $total);
            }
        }

        $invoice_data = get_option('invoice_data', true);
        
        Session::forget('payment_info');
        Session::forget('call_back');
        Session::put('plan_id', $plan->id);
        
        return view('user.subscription.payment', compact('plan', 'gateways', 'tax', 'total', 'invoice_data', 'dynamic_qris', 'order'));
    }

    public function checkStatus($id)
    {
        $order = Order::where('user_id', Auth::id())->find($id);
        if (!$order) {
            return response()->json(['status' => 0]);
        }
        return response()->json(['status' => $order->status]);
    }

    public function show($id)
    {
        // 1. Check if they already have any pending order
        $pendingOrder = Order::where('user_id', Auth::id())->where('status', 2)->first();
        if ($pendingOrder) {
            Session::flash('saas_error', __('Anda memiliki tagihan yang belum diselesaikan. Silakan selesaikan atau batalkan terlebih dahulu.'));
            return redirect()->route('user.subscription.invoice', $pendingOrder->id);
        }

        // 2. Normal flow
        $plan = Plan::where('status', 1)->where('price', '>', 0)->where('id', $id)->first();
        
        if (empty($plan)) {
           Session::flash('saas_error', __('Please select a plan from here')); 
           return redirect('/user/subscription');
        }

        $gateways = Gateway::where('status', 1)->get();
        $tax = get_option('tax');
        $tax = $tax > 0 ? ($plan->price / 100) * $tax : 0;
        $total = (double)$tax + $plan->price;

        $has_manual = $gateways->where('is_auto', 0)->count() > 0;
        if ($has_manual) {
            $sessionKey = 'payment_unique_code_' . $plan->id;
            if (Session::has($sessionKey)) {
                $unique_code = Session::get($sessionKey);
                if ($unique_code >= 1000) {
                    $payable = $total + $unique_code;
                    $exists = Order::where('status', 2)
                                   ->where('amount', $payable)
                                   ->exists();
                    if ($exists) {
                        do {
                            $unique_code = rand(1000, 1999);
                            $payable = $total + $unique_code;
                            $exists = Order::where('status', 2)
                                           ->where('amount', $payable)
                                           ->exists();
                        } while ($exists);
                        Session::put($sessionKey, $unique_code);
                    }
                }
            } else {
                $userId = Auth::user()->id;
                if ($userId < 1000) {
                    $unique_code = $userId;
                } else {
                    do {
                        $unique_code = rand(1000, 1999);
                        $payable = $total + $unique_code;
                        $exists = Order::where('status', 2)
                                       ->where('amount', $payable)
                                       ->exists();
                    } while ($exists);
                }
                Session::put($sessionKey, $unique_code);
            }
            $total = $total + $unique_code;
        }

        $qrisGateway = $gateways->where('namespace', 'App\Gateway\CustomGateway')->first() ?? $gateways->first();
        
        $order = new Order;
        $order->plan_id     = $plan->id;
        $order->user_id     = Auth::id();
        $order->tax         = $tax;
        $order->amount      = $total;
        $order->status      = 2; // pending
        if ($qrisGateway) {
            $order->gateway_id = $qrisGateway->id;
        }
        $order->save();

        if ($has_manual) {
            $sessionKey = 'payment_unique_code_' . $plan->id;
            Session::forget($sessionKey);
        }

        Session::forget('payment_info');
        Session::forget('call_back');
        Session::forget('plan_id');

        return redirect()->route('user.subscription.invoice', $order->id);
    }

    public function log()
    {
        $orders = Order::where('user_id',Auth::id())->with('plan','gateway')->latest()->paginate(20);
        $total = Order::where('user_id',Auth::id())->count();
        $totalPaid = Order::where('user_id',Auth::id())->where('status',1)->sum('amount');
        $pending = Order::where('user_id',Auth::id())->where('status',2)->count();

        return view('user.subscription.log',compact('orders', 'total', 'totalPaid', 'pending'));
    }

    /**
     * store the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request, $gatewayid,$planid)
    {

      $plan     = Plan::where('status',1)->where('price','>',0)->findorFail($planid);

      $gateway  = Gateway::where('status',1)->findorFail($gatewayid);
      $tax      = get_option('tax');
      $tax      = $tax > 0 ? ($plan->price / 100) * $tax : 0;
      $total    = (double)$tax+$plan->price;

      $pendingOrder = Order::where('user_id', Auth::id())->where('status', 2)->first();
      if ($pendingOrder) {
          if ($pendingOrder->plan_id != $plan->id) {
              return redirect('/user/subscription')->with('saas_error', __('Anda memiliki tagihan tertunda untuk paket lain. Silakan batalkan terlebih dahulu.'));
          }
          $total = $pendingOrder->amount;
      } else {
          if ($gateway->is_auto == 0) {
              $sessionKey = 'payment_unique_code_' . $plan->id;
              if (Session::has($sessionKey)) {
                  $unique_code = Session::get($sessionKey);
                  if ($unique_code >= 1000) {
                      $payable = $total + $unique_code;
                      $exists = Order::where('status', 2)
                                     ->where('amount', $payable)
                                     ->exists();
                      if ($exists) {
                          do {
                              $unique_code = rand(1000, 1999);
                              $payable = $total + $unique_code;
                              $exists = Order::where('status', 2)
                                             ->where('amount', $payable)
                                             ->exists();
                          } while ($exists);
                          Session::put($sessionKey, $unique_code);
                      }
                  }
              } else {
                  $userId = Auth::user()->id;
                  if ($userId < 1000) {
                      $unique_code = $userId;
                  } else {
                      do {
                          $unique_code = rand(1000, 1999);
                          $payable = $total + $unique_code;
                          $exists = Order::where('status', 2)
                                         ->where('amount', $payable)
                                         ->exists();
                      } while ($exists);
                  }
                  Session::put($sessionKey, $unique_code);
              }
              $total = $total + $unique_code;
          }
      }

      $payable  = $total*$gateway->multiply+$gateway->charge;

        if ($gateway->min_amount > $payable ){
            return redirect()->back()->with('min-max', __('The minimum transaction amount is :amount '.$gateway->min_amount));
        }
        if ($gateway->max_amount != -1) {
            if($gateway->max_amount < $payable){
                return redirect()->back()->with('min-max', __('The maximum transaction amount is :amount '.$gateway->max_amount));
            }
        }
        
      

      if ($gateway->is_auto == 0) {

        $request->validate([
            'comment' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'max:2048'], // 2MB
        ]);

        $payment_data['comment'] = $request->input('comment') ?? '';
        $payment_data['screenshot'] = '';
        if ($request->hasFile('image')) {
            $path = 'uploads' . '/payments' . date('/y/m/');
            $name = uniqid().".".$request->file('image')->extension();
            Storage::put($path . $name, file_get_contents(Request()->file('image')));
            ;
            $payment_data['screenshot'] = Storage::url($path . $name);
        }
       }

      Session::put('plan_id',$plan->id);

      $payment_data['currency']   = $gateway->currency ?? 'USD';
      $payment_data['email']      = Auth::user()->email;
      $payment_data['name']       = Auth::user()->name;
      $payment_data['phone']      = $request->phone;
      $payment_data['billName']   = 'Plan Name: '.$plan->title;
      $payment_data['amount']     = $total;
      $payment_data['test_mode']  = $gateway->test_mode;
      $payment_data['charge']     = $gateway->charge ?? 0;
      $payment_data['pay_amount'] =  str_replace(',','',number_format($payable));
      $payment_data['getway_id']  = $gateway->id;
       
      $callback['success'] = url('user/subscription/plan/success');
      $callback['fail']    = url('user/subscription/plan/failed');
      Session::put('call_back',$callback);


        if (!empty($gateway->data)) {
            foreach (json_decode($gateway->data ?? '') ?? [] as $key => $info) {
                $payment_data[$key] = $info;
            };
        }
       
        return $gateway->namespace::make_payment($payment_data);
      

    }

    public function status($status)
    {
       abort_if(!Session::has('call_back') || !Session::has('plan_id'),404); 
     
       return $status == 'success' ? $this->success() : $this->faild();
    }

    public function success()
    {
        abort_if(!Session::has('payment_info'),404);

        $paymentInfo = Session::get('payment_info');

        Session::forget('payment_info');
        Session::forget('call_back');
        

        $plan     = Plan::where('status',1)->where('price','>',0)->findorFail(Session::get('plan_id'));
        Session::forget('plan_id');
        
        $redirectUrl = auth()->user()->will_expire == null ? '/user/device/create' : '/user/dashboard';
        
        auth()->user()->will_expire == null ? Session::put('new-user',__('Lets create a whatsapp device')) : null;
        
        
        if ($paymentInfo['status'] == 1) {
            $user = auth()->user();
            $user->plan = json_encode($plan->data);
            $user->plan_id = $plan->id;
            $user->will_expire = now()->addDays($plan->days);
            $user->save();
        }
        

        $tax      = get_option('tax');
        $tax      = $tax > 0 ? ($plan->price / 100) * $tax : 0;

        $order = Order::where('user_id', Auth::id())
                      ->where('plan_id', $plan->id)
                      ->where('status', 2)
                      ->first();
                      
        if (!$order) {
            $order = new Order;
            $order->plan_id     = $plan->id;
            $order->user_id     = Auth::id();
            $order->tax         = $tax;
        }
        
        $order->payment_id  = $paymentInfo['payment_id'];
        $order->gateway_id  = $paymentInfo['getway_id'];
        $order->amount      = $paymentInfo['amount'];
        $order->status      = $paymentInfo['status'] ?? 1;
        $order->will_expire = now()->addDays($plan->days);
        if (isset($paymentInfo['meta'])) {
            $order->meta    = json_encode($paymentInfo['meta']);
        }
        $order->save();

        $this->sentOrderMail($order);

        $message = $paymentInfo['status'] == 1 ? __('Your subscription payment is complete') : __('Your subscription payment is complete admin will review this payment manually for approval.');

        Session::flash('success',$message);

        if ($paymentInfo['status'] == 1) {
            return redirect($redirectUrl);
        }

        return redirect('user/dashboard');

    }

    public function faild()
    {
        $plan_id = Session::get('plan_id');

        Session::forget('payment_info');
        Session::forget('call_back');
        Session::forget('plan_id');

        Session::flash('error',true);

        return redirect('/user/subscription/'.$plan_id);
    }

    private function makeDynamicQRIS($staticQris, $amount)
    {
        if (substr($staticQris, -8, 4) === '6304') {
            $staticQris = substr($staticQris, 0, -8);
        }
        
        $i = 0;
        $len = strlen($staticQris);
        $arr = [];
        while ($i < $len) {
            $tag = substr($staticQris, $i, 2);
            if (strlen($tag) < 2) break;
            $lengthStr = substr($staticQris, $i + 2, 2);
            if (strlen($lengthStr) < 2) break;
            $length = intval($lengthStr);
            $value = substr($staticQris, $i + 4, $length);
            $arr[$tag] = $value;
            $i += 4 + $length;
        }
        
        $arr['54'] = (string)$amount;
        
        if (isset($arr['01'])) {
            $arr['01'] = '12';
        }
        
        ksort($arr);
        $reconstructed = "";
        foreach ($arr as $tag => $value) {
            $reconstructed .= $tag . str_pad(strlen($value), 2, '0', STR_PAD_LEFT) . $value;
        }
        
        $reconstructed .= "6304";
        
        $crc = 0xFFFF;
        $polynomial = 0x1021;
        for ($x = 0; $x < strlen($reconstructed); $x++) {
            $crc ^= (ord($reconstructed[$x]) << 8);
            for ($y = 0; $y < 8; $y++) {
                if ($crc & 0x8000) {
                    $crc = (($crc << 1) ^ $polynomial) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }
        $crcHex = strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
        
        return $reconstructed . $crcHex;
    }

    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())->where('status', 2)->findOrFail($id);
        $order->status = 0; // Set to declined/cancelled
        $order->save();
        
        $sessionKey = 'payment_unique_code_' . $order->plan_id;
        Session::forget($sessionKey);
        
        return back()->with('success', __('Tagihan pembayaran berhasil dibatalkan.'));
    }

}
