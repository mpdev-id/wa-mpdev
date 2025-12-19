@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Payment')])
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Complete Payment') }}</h1>
      <p class="text-base-content/60">{{ __('Choose your payment method') }}</p>
    </div>
    <a href="{{ route('user.subscription.index') }}" class="btn btn-outline gap-2">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back') }}
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Payment Form -->
    <div class="lg:col-span-2">
      <div class="card bg-white shadow-xl animate-slide-up">
        <div class="card-body">
          <h2 class="card-title mb-4">{{ __('Payment Method') }}</h2>
          
          <form method="POST" action="{{ route('user.subscription.payment.process') }}" class="space-y-4">
            @csrf
            
            <div class="space-y-3">
              <label class="flex items-center gap-3 p-4 border-2 rounded-lg cursor-pointer hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                <input type="radio" name="payment_method" value="credit_card" class="radio radio-primary" checked />
                <span class="material-icons">credit_card</span>
                <div class="flex-1">
                  <div class="font-semibold">{{ __('Credit / Debit Card') }}</div>
                  <div class="text-sm text-gray-500">{{ __('Visa, Mastercard, Amex') }}</div>
                </div>
              </label>

              <label class="flex items-center gap-3 p-4 border-2 rounded-lg cursor-pointer hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                <input type="radio" name="payment_method" value="bank_transfer" class="radio radio-primary" />
                <span class="material-icons">account_balance</span>
                <div class="flex-1">
                  <div class="font-semibold">{{ __('Bank Transfer') }}</div>
                  <div class="text-sm text-gray-500">{{ __('Direct bank transfer') }}</div>
                </div>
              </label>

              <label class="flex items-center gap-3 p-4 border-2 rounded-lg cursor-pointer hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                <input type="radio" name="payment_method" value="paypal" class="radio radio-primary" />
                <span class="material-icons">payment</span>
                <div class="flex-1">
                  <div class="font-semibold">{{ __('PayPal') }}</div>
                  <div class="text-sm text-gray-500">{{ __('Pay with PayPal') }}</div>
                </div>
              </label>
            </div>

            <div class="alert alert-info mt-6">
              <span class="material-icons">lock</span>
              <div class="text-sm">
                {{ __('Your payment information is secure and encrypted') }}
              </div>
            </div>

            <div class="flex gap-3 pt-6 border-t">
              <a href="{{ route('user.subscription.index') }}" class="btn btn-outline flex-1">
                {{ __('Cancel') }}
              </a>
              <button type="submit" class="btn btn-primary flex-1 gap-2">
                <span class="material-icons text-sm">payment</span>
                {{ __('Pay Now') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Order Summary -->
    <div class="lg:col-span-1">
      <div class="card bg-white shadow-xl animate-slide-up" style="animation-delay: 0.1s">
        <div class="card-body">
          <h3 class="card-title text-lg mb-4">{{ __('Order Summary') }}</h3>
          
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-gray-600">{{ __('Plan') }}</span>
              <span class="font-semibold">{{ $plan->name ?? 'Premium' }}</span>
            </div>
            
            <div class="flex justify-between">
              <span class="text-gray-600">{{ __('Billing Period') }}</span>
              <span class="font-semibold">{{ __('Monthly') }}</span>
            </div>
            
            <div class="divider my-2"></div>
            
            <div class="flex justify-between">
              <span class="text-gray-600">{{ __('Subtotal') }}</span>
              <span>${{ number_format($plan->price ?? 29.99, 2) }}</span>
            </div>
            
            <div class="flex justify-between">
              <span class="text-gray-600">{{ __('Tax') }}</span>
              <span>${{ number_format($tax ?? 0, 2) }}</span>
            </div>
            
            <div class="divider my-2"></div>
            
            <div class="flex justify-between text-lg font-bold">
              <span>{{ __('Total') }}</span>
              <span class="text-primary">${{ number_format($total ?? 29.99, 2) }}</span>
            </div>
          </div>

          <div class="alert alert-success mt-6">
            <span class="material-icons text-sm">info</span>
            <div class="text-xs">
              {{ __('Billed monthly. Cancel anytime.') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
