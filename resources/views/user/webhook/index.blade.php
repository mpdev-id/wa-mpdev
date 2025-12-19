@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Webhook Logs')])
@endsection

@section('content')

<div class="max-w-7xl mx-auto">
  
  <!-- Page Header -->
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Webhook Logs') }}</h1>
    <p class="text-base-content/60">{{ __('Monitor webhook payload deliveries and responses') }}</p>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Total Payloads -->
    <div class="stats-card animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Payloads') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($total) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-primary to-secondary">
          <span class="material-icons">webhook</span>
        </div>
      </div>
    </div>

    <!-- Sent Hooks -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Sent Hooks') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($sent_hooks) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-success to-success/80">
          <span class="material-icons">check_circle</span>
        </div>
      </div>
    </div>

    <!-- Failed Hooks -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.2s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Failed Hooks') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($failed) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-error to-error/80">
          <span class="material-icons">error</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Webhook Logs Table / Empty State -->
  @if(count($hooks ?? []) == 0)
  <!-- Empty State -->
  <div class="flex flex-col items-center justify-center py-16 px-4 animate-fade-in">
    <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center mb-4">
      <span class="material-icons text-6xl text-base-content/40">webhook</span>
    </div>
    <h3 class="text-xl font-semibold text-base-content mb-2">{{ __('No Webhook Logs Yet') }}</h3>
    <p class="text-base-content/60 mb-6 text-center max-w-md">
      {{ __('Webhook logs will appear here once you configure webhooks and start sending messages') }}
    </p>
    <a href="{{ url('/docs/webhooks') }}" target="_blank" class="btn btn-primary gap-2">
      <span class="material-icons text-sm">menu_book</span>
      {{ __('View Documentation') }}
    </a>
  </div>
  @else
  <!-- Webhook Logs Table -->
  <div class="card bg-white shadow-xl">
    <div class="card-body p-0">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th class="bg-base-200">
                <div class="flex items-center gap-2">
                  <span class="material-icons text-sm">devices</span>
                  {{ __('Device') }}
                </div>
              </th>
              <th class="bg-base-200">
                <div class="flex items-center gap-2">
                  <span class="material-icons text-sm">link</span>
                  {{ __('Hook URL') }}
                </div>
              </th>
              <th class="bg-base-200">{{ __('Status') }}</th>
              <th class="bg-base-200">{{ __('HTTP Code') }}</th>
              <th class="bg-base-200 text-right">
                <div class="flex items-center justify-end gap-2">
                  <span class="material-icons text-sm">schedule</span>
                  {{ __('Date') }}
                </div>
              </th>
              <th class="bg-base-200 text-right">{{ __('Actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($hooks ?? [] as $hook)
            <tr class="hover">
              <td>
                <div class="flex items-center gap-2">
                  <span class="material-icons text-primary">phone_android</span>
                  <span class="font-medium">{{ $hook->device->name ?? 'None' }}</span>
                </div>
              </td>
              <td>
                <div class="max-w-xs">
                  <code class="text-xs truncate block">{{ $hook->hook }}</code>
                </div>
              </td>
              <td>
                @php
                  $statusClass = $hook->status == 1 ? 'badge-success' : ($hook->status == 2 ? 'badge-warning' : 'badge-error');
                  $statusText = $hook->status == 1 ? 'Sent' : ($hook->status == 2 ? 'Pending' : 'Failed');
                @endphp
                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
              </td>
              <td>
                <span class="badge badge-outline">{{ $hook->status_code ?? 'N/A' }}</span>
              </td>
              <td class="text-right">
                <div class="text-sm">
                  {{ $hook->created_at->format('d M Y') }}
                  <div class="text-xs text-gray-500">{{ $hook->created_at->format('g:i A') }}</div>
                </div>
              </td>
              <td>
                <div class="flex justify-end">
                  <button 
                    onclick="showPayload{{ $hook->id }}()" 
                    class="btn btn-ghost btn-sm btn-circle"
                    title="{{ __('View Payload') }}"
                  >
                    <span class="material-icons text-sm">code</span>
                  </button>
                </div>
              </td>
            </tr>
            <!-- Hidden payload modal trigger -->
            <script>
            function showPayload{{ $hook->id }}() {
              document.getElementById('payload-modal-{{ $hook->id }}').showModal();
            }
            </script>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="p-4 border-t border-base-200">
        <div class="flex justify-center">
          {{ $hooks->links('vendor.pagination.bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>

  <!-- Payload Modals -->
  @foreach($hooks ?? [] as $hook)
  <dialog id="payload-modal-{{ $hook->id }}" class="modal">
    <div class="modal-box max-w-2xl">
      <h3 class="font-bold text-lg flex items-center gap-2">
        <span class="material-icons">code</span>
        {{ __('Webhook Payload') }}
      </h3>
      
      <div class="py-4">
        <div class="mockup-code">
          <pre class="text-xs"><code>{{ $hook->payload }}</code></pre>
        </div>
        
        <div class="mt-4 grid grid-cols-2 gap-4">
          <div>
            <p class="text-sm font-medium text-gray-600">{{ __('Status') }}</p>
            <p class="font-semibold">{{ $hook->status == 1 ? 'Success' : 'Failed' }}</p>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-600">{{ __('HTTP Code') }}</p>
            <p class="font-semibold">{{ $hook->status_code ?? 'N/A' }}</p>
          </div>
        </div>
      </div>

      <div class="modal-action">
        <form method="dialog">
          <button class="btn">{{ __('Close') }}</button>
        </form>
      </div>
    </div>
  </dialog>
  @endforeach
  @endif

</div>

@endsection
