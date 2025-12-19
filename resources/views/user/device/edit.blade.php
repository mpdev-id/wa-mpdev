@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Edit Device')])
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Edit Device') }}</h1>
      <p class="text-base-content/60">{{ __('Update device settings') }}</p>
    </div>
    <a href="{{ route('user.device.index') }}" class="btn btn-outline gap-2">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back') }}
    </a>
  </div>

  <div class="card bg-white shadow-xl animate-slide-up">
    <div class="card-body">
      <form method="POST" class="ajaxform_instant_reload space-y-6" action="{{ route('user.device.update', $device->id) }}">
        @csrf
        @method('PUT')
        
        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium flex items-center gap-2">
              <span class="material-icons text-sm">phone_android</span>
              {{ __('Device Name') }}
            </span>
          </label>
          <input 
            type="text" 
            name="name" 
            class="input input-bordered w-full" 
            value="{{ $device->name }}" 
            required 
          />
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium flex items-center gap-2">
              <span class="material-icons text-sm">webhook</span>
              {{ __('Webhook URL') }}
            </span>
            <span class="label-text-alt badge badge-sm">{{ __('Optional') }}</span>
          </label>
          <input 
            type="url" 
            name="webhook_url" 
            class="input input-bordered w-full" 
            value="{{ $device->webhook ?? '' }}" 
            placeholder="https://your-domain.com/webhook"
          />
          <label class="label">
            <span class="label-text-alt text-info">
              {{ __('Incoming messages will be sent to this URL') }}
            </span>
          </label>
        </div>

        <div class="alert alert-warning">
          <span class="material-icons">warning</span>
          <div class="text-sm">
            {{ __('Changing device settings may require re-scanning the QR code') }}
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-base-200">
          <a href="{{ route('user.device.index') }}" class="btn btn-outline">
            {{ __('Cancel') }}
          </a>
          <button type="submit" class="btn btn-primary submit-btn gap-2">
            <span class="material-icons text-sm">save</span>
            {{ __('Update Device') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection