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
      <a href="{{ route('user.device.index') }}" class="btn btn-outline gap-2 rounded-full">
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
              <p id="device-phone" class="text-lg font-semibold">{{ $device->phone ?? __('Not connected') }}</p>
            </div>
            
            <div>
              <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Status') }}</p>
              <div id="status-badge-container">
                @if($device->status == 1 || $device->status == 'connected')
                  <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-success/10 border border-success/30 font-mono text-xs text-success cyber-glow-green">
                    <span class="w-2 h-2 rounded-full bg-success animate-ping"></span>
                    <span class="font-bold tracking-wider">[ONLINE_SECURE]</span>
                  </div>
                @else
                  <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-error/10 border border-error/30 font-mono text-xs text-error cyber-glow-red">
                    <span class="w-2 h-2 rounded-full bg-error animate-pulse"></span>
                    <span class="font-bold tracking-wider">[DISCONNECTED_OFFLINE]</span>
                  </div>
                @endif
              </div>
            </div>
            
            <div>
              <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Created At') }}</p>
              <p class="font-semibold">{{ !empty($device->created_at) ? \Carbon\Carbon::parse($device->created_at)->format('d M Y, H:i') : __('N/A') }}</p>
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
            <a href="{{ route('user.device.scan', $device->uuid) }}" class="btn btn-block btn-outline gap-2">
              <span class="material-icons text-sm">qr_code_scanner</span>
              {{ __('Scan QR Code') }}
            </a>
            
            <button id="test-connection-btn" class="btn btn-block btn-outline gap-2 rounded-full" onclick="testConnection()">
              <span id="test-connection-icon" class="material-icons text-sm">sync</span>
              <span id="test-connection-text">{{ __('Test Connection') }}</span>
            </button>
            
            <button class="btn btn-block btn-outline btn-error gap-2 delete-confirm rounded-full" data-action="{{ route('user.device.destroy', $device->uuid) }}">
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
              <p id="device-last-active" class="text-sm font-semibold">{{ !empty($device->updated_at) ? \Carbon\Carbon::parse($device->updated_at)->diffForHumans() : __('Never') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
function testConnection() {
    const btn = document.getElementById('test-connection-btn');
    const icon = document.getElementById('test-connection-icon');
    const text = document.getElementById('test-connection-text');
    
    // Change button to loading state
    btn.disabled = true;
    icon.classList.add('animate-spin');
    text.innerText = "{{ __('LINK_PINGING...') }}";

    // SweetAlert2 loading state
    Swal.fire({
        title: "{{ __('DIALING_LINK...') }}",
        text: "{{ __('Pinging session socket mainlink, checking authorization payload...') }}",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        showConfirmButton: false,
        onBeforeOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('{{ route("user.check-session", $device->uuid) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Reset button state
        btn.disabled = false;
        icon.classList.remove('animate-spin');
        text.innerText = "{{ __('Test Connection') }}";

        if (data.success && data.connected) {
            Swal.fire({
                title: "{{ __('Connected!') }}",
                text: data.message || "{{ __('Device is connected successfully.') }}",
                icon: "success",
                confirmButtonColor: "#9333ea"
            }).then(() => {
                // Refresh the page to show latest phone number, username, and stats
                location.reload();
            });
            
            // Update UI immediately (in case user takes time to click OK)
            const badgeContainer = document.getElementById('status-badge-container');
            if (badgeContainer) {
                badgeContainer.innerHTML = `
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-success/10 border border-success/30 font-mono text-xs text-success cyber-glow-green">
                      <span class="w-2 h-2 rounded-full bg-success animate-ping"></span>
                      <span class="font-bold tracking-wider">[ONLINE_SECURE]</span>
                    </div>
                `;
            }
        } else {
            Swal.fire({
                title: "{{ __('Disconnected') }}",
                text: data.message || "{{ __('Device is disconnected or session is inactive.') }}",
                icon: "error",
                confirmButtonColor: "#9333ea"
            });
            
            const badgeContainer = document.getElementById('status-badge-container');
            if (badgeContainer) {
                badgeContainer.innerHTML = `
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-error/10 border border-error/30 font-mono text-xs text-error cyber-glow-red">
                      <span class="w-2 h-2 rounded-full bg-error animate-pulse"></span>
                      <span class="font-bold tracking-wider">[DISCONNECTED_OFFLINE]</span>
                    </div>
                `;
            }
        }
    })
    .catch(error => {
        // Reset button state
        btn.disabled = false;
        icon.classList.remove('animate-spin');
        text.innerText = "{{ __('Test Connection') }}";

        Swal.fire({
            title: "{{ __('Error') }}",
            text: "{{ __('Failed to check device connection status.') }}",
            icon: "error",
            confirmButtonColor: "#9333ea"
        });
    });
}
</script>
@endpush
