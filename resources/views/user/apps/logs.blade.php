@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('App Message Logs')])
@endsection

@section('content')

<div class="max-w-7xl mx-auto">
  
  <!-- Page Header -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('App Message Logs') }}</h1>
      <p class="text-base-content/60">{{ __('Track all messages sent through this app') }}</p>
    </div>
    <a href="{{ route('user.apps.index') }}" class="btn btn-outline gap-2 rounded-full">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back to Apps') }}
    </a>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Total Used -->
    <div class="stats-card animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Messages') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalUsed) }}</h3>
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
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Today') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($todaysMessage) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-success to-success/80">
          <span class="material-icons">today</span>
        </div>
      </div>
    </div>

    <!-- Last 30 Days -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.2s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Last 30 Days') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($monthlyMessages) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-info to-info/80">
          <span class="material-icons">calendar_month</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Logs Table -->
  @if(count($logs ?? []) > 0)
  <div class="card bg-white shadow-xl">
    <div class="card-body p-0">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th class="bg-base-200">
                <div class="flex items-center gap-2">
                  <span class="material-icons text-sm">phone_forwarded</span>
                  {{ __('From') }}
                </div>
              </th>
              <th class="bg-base-200">
                <div class="flex items-center gap-2">
                  <span class="material-icons text-sm">person</span>
                  {{ __('To') }}
                </div>
              </th>
              <th class="bg-base-200">{{ __('Type') }}</th>
              <th class="bg-base-200">{{ __('Time') }}</th>
              <th class="bg-base-200 text-right">{{ __('Date') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($logs as $log)
            <tr class="hover">
              <td>
                <div class="flex items-center gap-2">
                  <span class="material-icons text-primary text-sm">phone_android</span>
                  <span class="font-medium">{{ $log->from ?? __('N/A') }}</span>
                </div>
              </td>
              <td>{{ $log->to }}</td>
              <td>
                @if($log->template_id != null)
                  <span class="badge badge-info">{{ __('Template') }}</span>
                @else
                  <span class="badge badge-success">{{ __('Plain Text') }}</span>
                @endif
              </td>
              <td>
                <span class="text-sm text-base-content/70">{{ $log->created_at->diffForHumans() }}</span>
              </td>
              <td class="text-right">
                <div class="text-sm">
                  {{ $log->created_at->format('d M Y') }}
                  <div class="text-xs text-gray-500">{{ $log->created_at->format('g:i A') }}</div>
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
          {{ $logs->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>
  @else
  <!-- Empty State -->
  <div class="flex flex-col items-center justify-center py-16 px-4 animate-fade-in">
    <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center mb-4">
      <span class="material-icons text-6xl text-base-content/40">message</span>
    </div>
    <h3 class="text-xl font-semibold text-base-content mb-2">{{ __('No Messages Yet') }}</h3>
    <p class="text-base-content/60 mb-6 text-center max-w-md">
      {{ __('Messages sent through this app will appear here') }}
    </p>
    <a href="{{ route('user.app.integration', request()->route('uuid')) }}" class="btn btn-primary gap-2">
      <span class="material-icons text-sm">code</span>
      {{ __('View API Docs') }}
    </a>
  </div>
  @endif

</div>

@endsection