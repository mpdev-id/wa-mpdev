@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Dashboard'),'buttons'=>[
  [
    'name'=>'<i class="fa fa-plus"></i>&nbsp'.__('Create Device'),
    'url'=> route('user.device.create'),
  ],
  [
    'name'=>'<i class="fi fi-rs-paper-plane"></i>&nbsp'.__('Send Message'),
    'url'=> url('/user/sent-text-message'),
  ],
]])
@endsection

@section('content')
<div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 1500)">
  
  <!-- Stats Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
    <!-- Total Devices -->
    <div class="stats-card group hover:scale-105 animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Devices') }}</p>
          <div x-show="loading" class="skeleton h-8 w-20"></div>
          <h3 x-show="!loading" x-cloak class="text-3xl font-bold text-gray-900" id="total-device">0</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-blue-500 to-blue-600 group-hover:scale-110 transition-transform">
          <span class="material-icons">devices</span>
        </div>
      </div>
    </div>

    <!-- Total Messages -->
    <div class="stats-card group hover:scale-105 animate-slide-up" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Messages') }}</p>
          <div x-show="loading" class="skeleton h-8 w-20"></div>
          <h3 x-show="!loading" x-cloak class="text-3xl font-bold text-gray-900" id="total-messages">0</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-purple-500 to-purple-600 group-hover:scale-110 transition-transform">
          <span class="material-icons">chat</span>
        </div>
      </div>
    </div>

    <!-- Pending Schedules -->
    <div class="stats-card group hover:scale-105 animate-slide-up" style="animation-delay: 0.2s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Pending Schedules') }}</p>
          <div x-show="loading" class="skeleton h-8 w-20"></div>
          <h3 x-show="!loading" x-cloak class="text-3xl font-bold text-gray-900" id="total-schedule">0</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-orange-500 to-orange-600 group-hover:scale-110 transition-transform">
          <span class="material-icons">schedule_send</span>
        </div>
      </div>
    </div>

    <!-- Total Contacts -->
    <div class="stats-card group hover:scale-105 animate-slide-up" style="animation-delay: 0.3s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Contacts') }}</p>
          <div x-show="loading" class="skeleton h-8 w-20"></div>
          <h3 x-show="!loading" x-cloak class="text-3xl font-bold text-gray-900" id="total-contacts">0</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-green-500 to-green-600 group-hover:scale-110 transition-transform">
          <span class="material-icons">contacts</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Alerts -->
  @if(Session::has('success'))
  <div class="alert alert-success shadow-lg mb-6 animate-slide-down">
    <span class="material-icons">check_circle</span>
    <div>
      <h3 class="font-bold">{{ __('Congratulations!') }}</h3>
      <div class="text-sm">{{ Session::get('success') }}</div>
    </div>
    <button class="btn btn-sm btn-ghost rounded-full" onclick="this.parentElement.remove()">
      <span class="material-icons">close</span>
    </button>
  </div>
  @endif

  @if(Session::has('saas_error'))
  <div class="alert alert-warning shadow-lg mb-6 animate-slide-down">
    <span class="material-icons">warning</span>
    <div>
      <h3 class="font-bold">{{ __('Oops!') }}</h3>
      <div class="text-sm">
        <a href="{{ url(Auth::user()->plan_id == null ? '/user/subscription' : '/user/subscription/'.Auth::user()->plan_id) }}" class="underline">
          {{ Session::get('saas_error') }}
        </a>
      </div>
    </div>
  </div>
  @endif

  <!-- Charts Row -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Messages Transaction Chart -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 animate-fade-in">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">{{ __('Messages Transaction') }}</h3>
        <select class="select select-bordered select-sm" id="period">
          <option value="7">{{ __('Last 7 Days') }}</option>
          <option value="1">{{ __('Today') }}</option>
          <option value="30">{{ __('Last 30 Days') }}</option>
        </select>
      </div>
      <div class="h-64">
        <canvas id="chart-sales"></canvas>
      </div>
    </div>

    <!-- Automatic Replies Chart -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 animate-fade-in" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">{{ __('Automatic Replies') }}</h3>
        <select class="select select-bordered select-sm" id="automaticReply">
          <option value="7">{{ __('Last 7 Days') }}</option>
          <option value="1">{{ __('Today') }}</option>
          <option value="30">{{ __('Last 30 Days') }}</option>
        </select>
      </div>
      <div class="h-64">
        <canvas id="chart-bars"></canvas>
      </div>
    </div>
  </div>

  <!-- Second Charts Row -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Messages Types Chart -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 animate-fade-in" style="animation-delay: 0.2s">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">{{ __('Messages Types') }}</h3>
        <select class="select select-bordered select-sm" id="messagesTypes">
          <option value="7">{{ __('Last 7 Days') }}</option>
          <option value="1">{{ __('Today') }}</option>
          <option value="30">{{ __('Last 30 Days') }}</option>
        </select>
      </div>
      <div class="h-64">
        <canvas id="chart-doughnut"></canvas>
      </div>
    </div>

    <!-- Devices Statistics -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 animate-fade-in" style="animation-delay: 0.3s">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Devices Statistics') }}</h3>
      <div class="space-y-3" id="device-list">
        <!-- Will be populated by JavaScript -->
      </div>
    </div>
  </div>

</div>

<input type="hidden" id="static-data" value="{{ route('user.dashboard.static') }}">
<input type="hidden" id="base_url" value="{{ url('/') }}">

@endsection

@push('js')
<script src="{{ asset('assets/vendor/chart.js/dist/chart.min.js') }}"></script>
<script src="{{ asset('assets/plugins/canvas-confetti/confetti.browser.min.js') }}"></script>
<script>
  // Define Charts object if missing to prevent ReferenceError in dashboard.js
  if (typeof Charts === 'undefined') {
    window.Charts = {
      colors: {
        gray: { 200: '#e2e8f0' },
        theme: {
          'primary': '#7934f3',
          'info': '#0dcaf0',
          'success': '#198754',
          'danger': '#dc3545',
          'warning': '#ffc107',
          'dark': '#212529'
        }
      }
    };
  }
</script>
@endpush

@push('bottomjs')
<script src="{{ asset('assets/js/pages/user/dashboard.js') }}"></script>
@endpush
