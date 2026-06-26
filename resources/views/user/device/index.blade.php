@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',[
'title'=>__('My Devices'),
'buttons'=>[
[
'name'=>'<i class="material-icons text-sm">add</i>&nbsp'.__('Create Device'),
'url'=> route('user.device.create'),
]
]])
@endsection

@section('content')
<div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 1000)">
  
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Total Devices -->
    <div class="stats-card animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Devices') }}</p>
          <div x-show="loading" class="skeleton h-8 w-20"></div>
          <h3 x-show="!loading" x-cloak class="text-3xl font-bold text-gray-900" id="total-device">0</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-blue-500 to-blue-600">
          <span class="material-icons">devices</span>
        </div>
      </div>
    </div>

    <!-- Active Devices -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Active Devices') }}</p>
          <div x-show="loading" class="skeleton h-8 w-20"></div>
          <h3 x-show="!loading" x-cloak class="text-3xl font-bold text-green-600" id="total-active">0</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-green-500 to-green-600">
          <span class="material-icons">check_circle</span>
        </div>
      </div>
    </div>

    <!-- Inactive Devices -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.2s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Inactive Devices') }}</p>
          <div x-show="loading" class="skeleton h-8 w-20"></div>
          <h3 x-show="!loading" x-cloak class="text-3xl font-bold text-orange-600" id="total-inactive">0</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-orange-500 to-orange-600">
          <span class="material-icons">error</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Devices Grid -->
  @if(count($devices ?? []) > 0)
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @foreach($devices ?? [] as $device)
    <div class="card bg-white shadow-lg hover:shadow-xl transition-all duration-300 animate-fade-in !overflow-visible">
      <div class="card-body">
        <!-- Header -->
        <div class="flex items-start justify-between mb-4">
          <div class="flex-1">
            <h3 class="card-title text-lg font-bold text-gray-900 mb-2">{{ $device->name }}</h3>
            <div class="space-y-1 text-sm text-gray-600">
              <div class="flex items-center gap-2">
                <span class="material-icons text-sm">phone</span>
                @if(!empty($device->phone))
                <a href="{{ route('user.device.scan',$device->uuid) }}" class="hover:text-primary">
                  {{ $device->phone }}
                </a>
                @else
                <span class="text-gray-400">{{ __('Not connected') }}</span>
                @endif
              </div>
              <div class="flex items-center gap-2">
                <span class="material-icons text-sm">chat</span>
                <span>{{ number_format($device->smstransaction_count) }} {{ __('messages') }}</span>
              </div>
            </div>
          </div>
          
          <!-- Dropdown Menu -->
          <div class="flex items-center justify-end gap-1 flex-wrap max-w-[120px]">
            <a href="{{ route('user.device.scan',$device->uuid) }}" 
               class="btn btn-sm btn-ghost text-primary px-1"
               title="{{ __('Scan QR') }}">
              <span class="material-icons text-sm">qr_code_scanner</span>
            </a>

            @if($device->status == 1)
            <a href="{{ url('/user/device/chats/'.$device->uuid) }}" 
               class="btn btn-sm btn-ghost text-info px-1"
               title="{{ __('Chats') }}">
              <span class="material-icons text-sm">chat</span>
            </a>
            <a href="{{ url('/user/device/groups/'.$device->uuid) }}" 
               class="btn btn-sm btn-ghost text-success px-1"
               title="{{ __('Groups') }}">
              <span class="material-icons text-sm">groups</span>
            </a>
            @endif

            <a href="{{ route('user.device.edit',$device->uuid) }}" 
               class="btn btn-sm btn-ghost text-warning px-1"
               title="{{ __('Edit Device') }}">
              <span class="material-icons text-sm">edit</span>
            </a>

            <a href="{{ route('user.device.show',$device->uuid) }}" 
               class="btn btn-sm btn-ghost text-base-content/70 px-1"
               title="{{ __('View Log') }}">
              <span class="material-icons text-sm">history</span>
            </a>

            <a href="javascript:void(0)" 
               class="delete-confirm btn btn-sm btn-ghost text-red-600 px-1 rounded-full" 
               data-action="{{ route('user.device.destroy',$device->uuid) }}"
               title="{{ __('Remove') }}">
              <span class="material-icons text-sm">delete</span>
            </a>
          </div>
        </div>

        <!-- Status Badge -->
        <div class="card-actions flex flex-col gap-2">
          <!-- AI Label Toggle -->
          <div class="flex items-center justify-between w-full p-2 bg-base-50 rounded-lg border border-base-200">
            <span class="text-sm font-medium text-gray-700">{{ __('Chat Label AI') }}</span>
            <label class="swap swap-flip ai-label-toggle cursor-pointer" data-id="{{ $device->uuid }}">
              <input type="checkbox" {{ optional($device->aiResponse)->use_ai_label ? 'checked' : '' }} />
              <div class="swap-on text-success font-bold text-xs uppercase">{{ __('ON') }}</div>
              <div class="swap-off text-gray-400 font-bold text-xs uppercase">{{ __('OFF') }}</div>
            </label>
          </div>

          <a href="{{ route('user.device.scan',$device->uuid) }}" class="w-full">
            <div class="flex items-center justify-between p-3 rounded-xl transition-all duration-200 border border-transparent hover:border-primary/20 {{ $device->status == 1 ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }} status-indicator-container">
              <span class="text-sm font-mono tracking-wider font-semibold">{{ __('STATUS') }}</span>
              @if($device->status == 1)
                <span class="inline-flex items-center gap-1.5 font-mono text-xs font-bold uppercase">
                  <span class="w-2 h-2 rounded-full bg-success animate-ping"></span>
                  {{ __('ACTIVE') }}
                </span>
              @else
                <span class="inline-flex items-center gap-1.5 font-mono text-xs font-bold uppercase">
                  <span class="w-2 h-2 rounded-full bg-error animate-pulse"></span>
                  {{ __('INACTIVE') }}
                </span>
              @endif
            </div>
          </a>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @else
  <!-- Empty State -->
  <div class="flex flex-col items-center justify-center py-16 px-4">
    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
      <span class="material-icons text-6xl text-gray-400">phone_disabled</span>
    </div>
    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('No Devices Found') }}</h3>
    <p class="text-gray-600 mb-6 text-center">{{ __('Create your first device to start sending WhatsApp messages') }}</p>
    <a href="{{ route('user.device.create') }}" class="btn btn-primary gap-2 rounded-full">
      <span class="material-icons text-sm">add</span>
      {{ __('Create Device') }}
    </a>
  </div>
  @endif

</div>

<input type="hidden" id="base_url" value="{{ url('/') }}">

@endsection

@push('js')
<script src="{{ asset('assets/js/pages/user/device.js') }}"></script>
<script>
$(document).ready(function() {
    $('.ai-label-toggle input').on('change', function() {
        var $toggle = $(this);
        var uuid = $toggle.closest('.ai-label-toggle').data('id');
        var isChecked = $toggle.is(':checked');
        
        $toggle.prop('disabled', true);
        
        $.ajax({
            url: '/user/device/' + uuid + '/toggle-ai-label',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $toggle.prop('disabled', false);
                if(response.success) {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        close: true,
                        gravity: "bottom", 
                        position: "center", 
                        backgroundColor: "#4f46e5",
                    }).showToast();
                } else {
                    $toggle.prop('checked', !isChecked);
                }
            },
            error: function(xhr) {
                $toggle.prop('disabled', false);
                $toggle.prop('checked', !isChecked);
                Toastify({
                    text: 'Failed to update AI Label status',
                    duration: 3000,
                    close: true,
                    gravity: "bottom", 
                    position: "center", 
                    backgroundColor: "#ef4444",
                }).showToast();
            }
        });
    });
});
</script>
@endpush