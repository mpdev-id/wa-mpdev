@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Subscription')])
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
  
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('My Subscription') }}</h1>
    <p class="text-base-content/60">{{ __('Manage your plan and billing') }}</p>
  </div>

  <!-- Current Plan Card -->
  <div class="card bg-white shadow-xl mb-6 animate-slide-up">
    <div class="card-body">
      <div class="flex items-center justify-between mb-4">
        <h2 class="card-title flex items-center gap-2">
          <span class="material-icons">workspace_premium</span>
          {{ __('Current Plan') }}
        </h2>
        <span class="badge badge-success badge-lg">{{ __('Active') }}</span>
      </div>
      
      <div class="divider"></div>
      
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div>
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Plan Name') }}</p>
          <p class="text-xl font-bold">{{ $subscription->plan->title ?? 'Free' }}</p>
        </div>
        
        <div>
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Price') }}</p>
          <p class="text-xl font-bold">{{ amount_format($subscription->plan->price ?? 0) }}/{{ __('month') }}</p>
        </div>
        
        <div>
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Expires On') }}</p>
          <p class="text-xl font-bold">{{ $subscription->expires_at ?? __('Never') }}</p>
        </div>
        
        <div>
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Messages Used') }}</p>
          <p class="text-xl font-bold">
            {{ number_format($subscription->messages_used ?? 0) }}/{{ $subscription->plan->messages_limit ?? '∞' }}
          </p>
        </div>
      </div>

      <div class="flex gap-3 mt-6 pt-6 border-t border-base-200">
        <button class="btn btn-primary gap-2 rounded-full">
          <span class="material-icons text-sm">upgrade</span>
          {{ __('Upgrade Plan') }}
        </button>
        <button class="btn btn-outline gap-2 rounded-full">
          <span class="material-icons text-sm">credit_card</span>
          {{ __('Payment Method') }}
        </button>
        <a href="{{ route('user.subscription.log') }}" class="btn btn-outline gap-2 rounded-full">
          <span class="material-icons text-sm">receipt_long</span>
          {{ __('View History') }}
        </a>
      </div>
    </div>
  </div>

  <!-- Features List -->
  <div class="card  shadow-md animate-slide-up" style="animation-delay: 0.1s">
    <div class="card-body">
      <h3 class="card-title text-lg mb-4">{{ __('Plan Features') }}</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex items-center gap-3">
          <span class="material-icons text-success">check_circle</span>
          <span>{{ __('Unlimited devices') }}</span>
        </div>
        <div class="flex items-center gap-3">
          <span class="material-icons text-success">check_circle</span>
          <span>{{ __('API access') }}</span>
        </div>
        <div class="flex items-center gap-3">
          <span class="material-icons text-success">check_circle</span>
          <span>{{ __('Priority support') }}</span>
        </div>
        <div class="flex items-center gap-3">
          <span class="material-icons text-success">check_circle</span>
          <span>{{ __('Advanced analytics') }}</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection