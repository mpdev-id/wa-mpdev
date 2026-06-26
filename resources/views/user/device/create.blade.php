@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Create Device')])
@endsection

@section('content')

<div class="max-w-4xl mx-auto">
  
  <!-- Welcome Alert (for new users) -->
  @if(Session::has('new-user'))
  <div class="alert alert-success mb-6 animate-slide-up">
    <span class="material-icons">celebration</span>
    <div>
      <h3 class="font-bold">{{ __('Welcome!') }}</h3>
      <div class="text-sm">{{ __('Hi') }} <strong>{{ Auth::user()->name }}</strong>! {{ __('Welcome to') }} {{ env('APP_NAME') }}</div>
    </div>
  </div>
  @endif

  <!-- Page Header -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Create New Device') }}</h1>
      <p class="text-base-content/60">{{ __('Connect a new WhatsApp device to send messages') }}</p>
    </div>
    <a href="{{ route('user.device.index') }}" class="btn btn-outline gap-2 rounded-full">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back') }}
    </a>
  </div>

  <!-- Form Card -->
  <div class="card bg-white shadow-xl animate-slide-up">
    <div class="card-body">
      <form method="POST" class="ajaxform_instant_reload space-y-6" action="{{ route('user.device.store') }}">
        @csrf
        
        <!-- Device Name -->
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
            placeholder="{{ __('My iPhone 13 Pro') }}"
            required 
          />
          <label class="label">
            <span class="label-text-alt">{{ __('Give your device a recognizable name') }}</span>
          </label>
        </div>

        <!-- Webhook URL -->
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
            placeholder="https://your-domain.com/webhook"
          />
          <label class="label">
            <span class="label-text-alt text-warning">
              <span class="material-icons text-xs">info</span>
              {{ env('APP_NAME') }} {{ __('will send incoming messages to this URL via POST method') }}
            </span>
          </label>
        </div>

        <!-- Info Alert -->
        <div class="alert alert-info">
          <span class="material-icons">lightbulb</span>
          <div class="text-sm">
            <strong>{{ __('Next Steps:') }}</strong>
            <ul class="list-disc list-inside mt-2">
              <li>{{ __('After creating, scan the QR code with WhatsApp') }}</li>
              <li>{{ __('Keep your phone connected to internet') }}</li>
              <li>{{ __('Device will be ready to send messages') }}</li>
            </ul>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3 pt-6 border-t border-base-200">
          <a href="{{ route('user.device.index') }}" class="btn btn-outline rounded-full">
            {{ __('Cancel') }}
          </a>
          <button type="submit" class="btn btn-primary submit-btn gap-2 rounded-full">
            <span class="material-icons text-sm">add</span>
            {{ __('Create Device') }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Help Card -->
  <div class="card bg-base-100 shadow-md mt-6 animate-slide-up" style="animation-delay: 0.1s">
    <div class="card-body">
      <h3 class="card-title flex items-center gap-2">
        <span class="material-icons">help</span>
        {{ __('Need Help?') }}
      </h3>
      <div class="text-sm text-base-content/70 space-y-2">
        <p>{{ __('Having trouble connecting your device?') }}</p>
        <ul class="list-disc list-inside space-y-1">
          <li>{{ __('Make sure WhatsApp is installed on your phone') }}</li>
          <li>{{ __('Ensure stable internet connection') }}</li>
          <li>{{ __('Use WhatsApp Business for better reliability') }}</li>
        </ul>
      </div>
    </div>
  </div>

</div>

@endsection