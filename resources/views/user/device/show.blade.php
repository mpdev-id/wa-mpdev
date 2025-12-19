@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Device Details')])
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-base-content mb-2">{{ $device->name }}</h1>
      <p class="text-base-content/60">{{ __('Device information and details') }}</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('user.device.edit', $device->id) }}" class="btn btn-outline gap-2">
        <span class="material-icons text-sm">edit</span>
        {{ __('Edit') }}
      </a>
      <a href="{{ route('user.device.index') }}" class="btn btn-outline gap-2">
        <span class="material-icons text-sm">arrow_back</span>
        {{ __('Back') }}
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2">
      <div class="card bg-white shadow-xl animate-slide-up">
        <div class="card-body">
          <h2 class="card-title flex items-center gap-2">
            <span class="material-icons">info</span>
            {{ __('Device Information') }}
          </h2>
          <div class="divider"></div>
          
          <div class="grid grid-cols-2 gap-6">
            <div>
              <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Device Name') }}</p>
              <p class="text-lg font-semibold">{{ $device->name }}</p>
            </div>
            
            <div>
              <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Phone Number') }}</p>
              <p class="text-lg font-semibold">{{ $device->phone ?? __('Not connected') }}</p>
            </div>
            
            <div>
              <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Status') }}</p>
              @if($device->status == 'connected')
                <span class="badge badge-success gap-1">
                  <span class="material-icons text-xs">check_circle</span>
                  {{ __('Connected') }}
                </span>
              @else
                <span class="badge badge-error gap-1">
                  <span class="material-icons text-xs">cancel</span>
                  {{ __('Disconnected') }}
                </span>
              @endif
            </div>
            
            <div>
              <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Created At') }}</p>
              <p class="font-semibold">{{ $device->created_at->format('d M Y, H:i') }}</p>
            </div>

            @if($device->webhook)
            <div class="col-span-2">
              <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Webhook URL') }}</p>
              <p class="font-mono text-sm">{{ $device->webhook }}</p>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div>
      <div class="card bg-white shadow-xl animate-slide-up" style="animation-delay: 0.1s">
        <div class="card-body">
          <h3 class="card-title text-base flex items-center gap-2">
            <span class="material-icons text-sm">bolt</span>
            {{ __('Quick Actions') }}
          </h3>
          <div class="divider my-2"></div>
          
          <div class="space-y-2">
            <a href="{{ route('user.device.qr', $device->id) }}" class="btn btn-block btn-outline gap-2">
              <span class="material-icons text-sm">qr_code_scanner</span>
              {{ __('Scan QR Code') }}
            </a>
            
            <button class="btn btn-block btn-outline gap-2" onclick="alert('Test connection')">
              <span class="material-icons text-sm">sync</span>
              {{ __('Test Connection') }}
            </button>
            
            <button class="btn btn-block btn-outline btn-error gap-2 delete-confirm" data-action="{{ route('user.device.destroy', $device->id) }}">
              <span class="material-icons text-sm">delete</span>
              {{ __('Delete Device') }}
            </button>
          </div>
        </div>
      </div>

      <!-- Stats -->
      <div class="card bg-base-100 shadow-md mt-4 animate-slide-up" style="animation-delay: 0.2s">
        <div class="card-body">
          <h3 class="card-title text-base">{{ __('Statistics') }}</h3>
          <div class="divider my-2"></div>
          <div class="space-y-3">
            <div>
              <p class="text-xs text-gray-600">{{ __('Messages Sent') }}</p>
              <p class="text-2xl font-bold">{{ number_format($device->messages_count ?? 0) }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-600">{{ __('Last Active') }}</p>
              <p class="text-sm font-semibold">{{ $device->updated_at->diffForHumans() }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
