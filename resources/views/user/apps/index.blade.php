@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Apps Integration')])
@endsection

@section('content')

<div class="max-w-7xl mx-auto" x-data="{ showCreateModal: false }">
  
  <!-- Page Header -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-xl md:text-2xl break-words font-bold text-base-content mb-2">{{ __('Apps Integration') }}</h1>
      <p class="text-base-content/60">{{ __('Manage your connected applications') }}</p>
    </div>
    <button @click="showCreateModal = true" class="btn btn-primary gap-2 rounded-full">
      <span class="material-icons text-sm">add</span>
      {{ __('Create App') }}
    </button>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Total Apps -->
    <div class="stats-card animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Apps') }}</p>
          <h3 class="text-xl md:text-2xl break-words md:text-3xl break-words font-bold text-gray-900">{{ $limit }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-primary to-secondary">
          <span class="material-icons">apps</span>
        </div>
      </div>
    </div>

    <!-- Total Messages -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Messages Sent') }}</p>
          <h3 class="text-xl md:text-2xl break-words md:text-3xl break-words font-bold text-gray-900">{{ number_format($total) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-success to-success/80">
          <span class="material-icons">send</span>
        </div>
      </div>
    </div>

    <!-- Last 30 Days -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.2s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Last 30 Days') }}</p>
          <h3 class="text-xl md:text-2xl break-words md:text-3xl break-words font-bold text-gray-900">{{ number_format($last30_messages ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-info to-info/80">
          <span class="material-icons">trending_up</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Apps Grid / Empty State -->
  @if(count($apps ?? []) == 0)
  <!-- Empty State -->
  <div class="flex flex-col items-center justify-center py-16 px-4 animate-fade-in">
    <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center mb-4">
      <span class="material-icons text-6xl text-base-content/40">apps</span>
    </div>
    <h3 class="text-xl font-semibold text-base-content mb-2">{{ __('No Apps Yet') }}</h3>
    <p class="text-base-content/60 mb-6 text-center max-w-md">
      {{ __('Create your first app to integrate WhatsApp messaging into your applications') }}
    </p>
    <button @click="showCreateModal = true" class="btn btn-primary gap-2 rounded-full">
      <span class="material-icons text-sm">add</span>
      {{ __('Create App') }}
    </button>
  </div>
  @else
  <!-- Apps Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($apps as $app)
    <div class="card bg-white shadow-xl hover:shadow-2xl transition-shadow duration-300 animate-slide-up !overflow-visible" style="animation-delay: {{ $loop->index * 0.1 }}s">
      <div class="card-body">
        <div class="flex items-start justify-between mb-4">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
              <span class="material-icons text-primary">widgets</span>
            </div>
            <div>
              <h3 class="font-bold text-lg">{{ $app->title }}</h3>
              <p class="text-sm text-base-content/60">{{ __('Messages') }}: {{ number_format($app->live_messages_count) }}</p>
            </div>
          </div>
          
          <div class="flex items-center justify-end gap-1">
            <a href="{{ route('user.app.logs', $app->uuid) }}" 
               class="btn btn-sm btn-ghost text-primary"
               title="{{ __('Message Logs') }}">
              <span class="material-icons text-sm">history</span>
            </a>
            
            <a href="{{ route('user.app.integration', $app->uuid) }}" 
               class="btn btn-sm btn-ghost text-info"
               title="{{ __('REST API') }}">
              <span class="material-icons text-sm">code</span>
            </a>
            
            <a href="javascript:void(0)" 
               class="delete-confirm btn btn-sm btn-ghost text-red-600 rounded-full" 
               data-action="{{ route('user.apps.destroy', $app->uuid) }}"
               title="{{ __('Remove') }}">
              <span class="material-icons text-sm">delete</span>
            </a>
          </div>
        </div>

        <div class="space-y-3">
          <div class="flex items-center gap-2 text-sm">
            <span class="material-icons text-xs text-base-content/40">phone_android</span>
            <span class="text-base-content/70">{{ $app->device->phone ?? __('No device') }}</span>
          </div>
          
          <div class="flex items-center gap-2 text-sm">
            <span class="material-icons text-xs text-base-content/40">link</span>
            <a href="{{ $app->website ?? '#' }}" target="_blank" class="text-primary hover:underline truncate">
              {{ Str::limit($app->website ?? __('No website'), 30) }}
            </a>
          </div>
        </div>

        <div class="card-actions justify-end mt-4 pt-4 border-t border-base-200">
          <a href="{{ route('user.app.integration', $app->uuid) }}" class="btn btn-sm btn-outline gap-2">
            <span class="material-icons text-xs">integration_instructions</span>
            {{ __('Integration') }}
          </a>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  <!-- Pagination -->
  <div class="flex justify-center mt-6">
    {{ $apps->links('vendor.pagination.bootstrap-4') }}
  </div>
  @endif

  <!-- Create Modal -->
  <div x-show="showCreateModal" x-cloak class="modal modal-open" @click.self="showCreateModal = false">
    <div class="modal-box">
      <h3 class="font-bold text-lg flex items-center gap-2 mb-4">
        <span class="material-icons">add_circle</span>
        {{ __('Create New App') }}
      </h3>
      
      <form method="POST" action="{{ route('user.apps.store') }}" class="ajaxform_instant_reload space-y-4">
        @csrf
        
        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Select Device') }}</span>
          </label>
          <select class="select select-bordered w-full" name="device" required>
            <option value="">{{ __('Choose device...') }}</option>
            @foreach($devices as $device)
            <option value="{{ $device->id }}">
              {{ $device->name }} 
              @if(!empty($device->phone))
                ({{ $device->phone }})
              @endif
            </option>
            @endforeach
          </select>
          <label class="label">
            <span class="label-text-alt">{{ __('Messages will be sent from this device') }}</span>
          </label>
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('App Name') }}</span>
          </label>
          <input 
            type="text" 
            name="name" 
            class="input input-bordered w-full" 
            placeholder="{{ __('My Application') }}"
            required 
          />
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Website URL') }}</span>
          </label>
          <input 
            type="url" 
            name="website" 
            class="input input-bordered w-full" 
            placeholder="https://example.com"
            required 
          />
        </div>

        <div class="modal-action">
          <button type="button" @click="showCreateModal = false" class="btn btn-outline rounded-full">
            {{ __('Cancel') }}
          </button>
          <button type="submit" class="btn btn-primary gap-2 rounded-full">
            <span class="material-icons text-sm">add</span>
            {{ __('Create App') }}
          </button>
        </div>
      </form>
    </div>
  </div>

</div>

<style>
[x-cloak] { display: none !important; }
</style>

@endsection