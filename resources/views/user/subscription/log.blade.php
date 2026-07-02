@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Transaction History')])
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-xl md:text-2xl break-words font-bold text-base-content mb-2">{{ __('Transaction History') }}</h1>
      <p class="text-base-content/60">{{ __('All your payment transactions') }}</p>
    </div>
    <a href="{{ route('user.subscription.index') }}" class="btn btn-outline gap-2 rounded-full">
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
          <h3 class="text-xl md:text-2xl break-words md:text-3xl break-words font-bold text-gray-900">{{ number_format($total ?? 0) }}</h3>
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
          <h3 class="text-xl md:text-2xl break-words md:text-3xl break-words font-bold text-gray-900">{{ amount_format($totalPaid ?? 0) }}</h3>
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
          <h3 class="text-xl md:text-2xl break-words md:text-3xl break-words font-bold text-gray-900">{{ number_format($pending ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-warning to-warning/80">
          <span class="material-icons">schedule</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Transactions Table -->
  @if(count($orders ?? []) > 0)
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
            @foreach($orders as $tx)
            <tr class="hover">
              <td>{{ $tx->created_at->format('d M Y') }}</td>
              <td>{{ $tx->plan->title ?? 'N/A' }}</td>
              <td class="font-semibold">{{ amount_format($tx->amount) }}</td>
              <td>
                @if($tx->status == 1)
                  <span class="badge badge-success">{{ __('Paid') }}</span>
                @elseif($tx->status == 2)
                  <span class="badge badge-warning">{{ __('Pending') }}</span>
                @else
                  <span class="badge badge-error">{{ __('Failed') }}</span>
                @endif
              </td>
              <td class="text-right flex items-center justify-end gap-2">
                @if($tx->status == 2)
                  <form method="POST" action="{{ route('user.subscription.cancel', $tx->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan tagihan ini?');" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-error btn-outline btn-xs gap-1">
                      <span class="material-icons text-[10px]">cancel</span>
                      {{ __('Batalkan') }}
                    </button>
                  </form>
                @endif
                <a href="{{ route('user.subscription.invoice', $tx->id) }}" class="btn btn-ghost btn-sm gap-1">
                  <span class="material-icons text-xs">visibility</span>
                  {{ __('Invoice') }}
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @if($orders->hasPages())
      <div class="p-4 border-t border-base-250">
        {{ $orders->links() }}
      </div>
      @endif
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