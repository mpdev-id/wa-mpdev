@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Message Logs')])
@endsection

@section('content')

<div class="max-w-7xl mx-auto">
  
  <!-- Page Header -->
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Message Logs') }}</h1>
    <p class="text-base-content/60">{{ __('Track all your WhatsApp message transactions') }}</p>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Total Messages -->
    <div class="stats-card animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Messages') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($total_messages) }}</h3>
          <p class="text-xs text-gray-500 mt-1">{{ __('All time') }}</p>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-primary to-secondary">
          <span class="material-icons">send</span>
        </div>
      </div>
    </div>

    <!-- Today's Messages -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __("Today's Messages") }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($today_messages) }}</h3>
          <p class="text-xs text-gray-500 mt-1">{{ date('d M Y') }}</p>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-info to-info/80">
          <span class="material-icons">today</span>
        </div>
      </div>
    </div>

    <!-- Last 30 Days -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.2s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Last 30 Days') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($last30_messages) }}</h3>
          <p class="text-xs text-gray-500 mt-1">{{ __('Past month') }}</p>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-success to-success/80">
          <span class="material-icons">event</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Logs Table / Empty State -->
  @if(count($logs ?? []) == 0)
  <!-- Empty State -->
  <div class="flex flex-col items-center justify-center py-16 px-4 animate-fade-in">
    <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center mb-4">
      <span class="material-icons text-6xl text-base-content/40">history</span>
    </div>
    <h3 class="text-xl font-semibold text-base-content mb-2">{{ __('No Message Logs Yet') }}</h3>
    <p class="text-base-content/60 mb-6 text-center max-w-md">
      {{ __('Start sending messages to see your transaction logs here') }}
    </p>
    <a href="{{ route('user.sent.customtext', 'plain-text') }}" class="btn btn-primary gap-2 rounded-full">
      <span class="material-icons text-sm">send</span>
      {{ __('Send Message') }}
    </a>
  </div>
  @else
  <!-- Logs Table -->
  <div class="card bg-white shadow-xl">
    <div class="card-body p-0">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th class="bg-base-200">
                <div class="flex items-center gap-2">
                  <span class="material-icons text-sm">call_made</span>
                  {{ __('From') }}
                </div>
              </th>
              <th class="bg-base-200">
                <div class="flex items-center gap-2">
                  <span class="material-icons text-sm">call_received</span>
                  {{ __('To') }}
                </div>
              </th>
              <th class="bg-base-200">{{ __('Type') }}</th>
              <th class="bg-base-200">{{ __('Request Type') }}</th>
              <th class="bg-base-200 text-right">
                <div class="flex items-center justify-end gap-2">
                  <span class="material-icons text-sm">schedule</span>
                  {{ __('Date') }}
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach($logs ?? [] as $log)
            <tr class="hover">
              <td>
                <div class="flex items-center gap-2">
                  <div class="avatar placeholder">
                    <div class="bg-primary/10 text-primary rounded-full w-8 h-8 flex items-center justify-center">
                      <span class="material-icons text-sm">phone</span>
                    </div>
                  </div>
                  <span class="font-medium">{{ $log->from }}</span>
                </div>
              </td>
              <td>
                <div class="flex items-center gap-2">
                  <div class="avatar placeholder">
                    <div class="bg-success/10 text-success rounded-full w-8 h-8 flex items-center justify-center">
                      <span class="material-icons text-sm">person</span>
                    </div>
                  </div>
                  <span class="font-medium">{{ $log->to }}</span>
                </div>
              </td>
              <td>
                <span class="badge badge-outline">
                  {{ $log->template != null ? __('Template') : __('Plain Text') }}
                </span>
              </td>
              <td>
                <span class="badge badge-primary">{{ ucfirst($log->type) }}</span>
              </td>
              <td class="text-right">
                <div class="text-sm">
                  {{ $log->created_at->format('d M Y') }}
                  <div class="text-xs text-gray-500">
                    {{ $log->created_at->format('g:i A') }}
                  </div>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="p-4 border-t border-base-200">
        <div class="flex justify-center">
          {{ $logs->links('vendor.pagination.bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>
  @endif

</div>

@endsection

