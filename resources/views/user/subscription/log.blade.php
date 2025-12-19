@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Transaction History')])
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Transaction History') }}</h1>
      <p class="text-base-content/60">{{ __('All your payment transactions') }}</p>
    </div>
    <a href="{{ route('user.subscription.index') }}" class="btn btn-outline gap-2">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back') }}
    </a>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="stats-card animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Transactions') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($total ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-primary to-secondary">
          <span class="material-icons">receipt</span>
        </div>
      </div>
    </div>

    <div class="stats-card animate-slide-up" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Paid') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">${{ number_format($totalPaid ?? 0, 2) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-success to-success/80">
          <span class="material-icons">paid</span>
        </div>
      </div>
    </div>

    <div class="stats-card animate-slide-up" style="animation-delay: 0.2s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Pending') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($pending ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-warning to-warning/80">
          <span class="material-icons">schedule</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Transactions Table -->
  @if(count($transactions ?? []) > 0)
  <div class="card bg-white shadow-xl">
    <div class="card-body p-0">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th class="bg-base-200">{{ __('Date') }}</th>
              <th class="bg-base-200">{{ __('Plan') }}</th>
              <th class="bg-base-200">{{ __('Amount') }}</th>
              <th class="bg-base-200">{{ __('Status') }}</th>
              <th class="bg-base-200 text-right">{{ __('Invoice') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($transactions as $tx)
            <tr class="hover">
              <td>{{ $tx->created_at->format('d M Y') }}</td>
              <td>{{ $tx->plan_name }}</td>
              <td class="font-semibold">${{ number_format($tx->amount, 2) }}</td>
              <td>
                @if($tx->status == 'paid')
                  <span class="badge badge-success">{{ __('Paid') }}</span>
                @elseif($tx->status == 'pending')
                  <span class="badge badge-warning">{{ __('Pending') }}</span>
                @else
                  <span class="badge badge-error">{{ __('Failed') }}</span>
                @endif
              </td>
              <td class="text-right">
                <button class="btn btn-ghost btn-sm gap-1">
                  <span class="material-icons text-xs">download</span>
                  {{ __('Download') }}
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @else
  <!-- Empty State -->
  <div class="flex flex-col items-center justify-center py-16 animate-fade-in">
    <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center mb-4">
      <span class="material-icons text-6xl text-base-content/40">receipt_long</span>
    </div>
    <h3 class="text-xl font-semibold text-base-content mb-2">{{ __('No Transactions') }}</h3>
    <p class="text-base-content/60 text-center max-w-md">
      {{ __('Your payment history will appear here') }}
    </p>
  </div>
  @endif
</div>
@endsection