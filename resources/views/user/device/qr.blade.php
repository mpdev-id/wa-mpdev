@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Connect Device')])
@endsection

@push('css')
<style>
[x-cloak] { display: none !important; }
.qr-code-display {
  min-height: 300px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.pairing-code-display {
  font-size: 3rem;
  letter-spacing: 0.5rem;
  font-weight: 800;
  font-family: 'Courier New', monospace;
}
@keyframes scan {
  0%, 100% { top: 0%; }
  50% { top: 100%; }
}
.scanner-line {
  animation: scan 3s ease-in-out infinite;
  box-shadow: 0 0 15px 4px var(--color-primary), 0 0 6px 1px var(--color-primary);
}
.scanner-corner {
  filter: drop-shadow(0 0 4px var(--color-primary));
}
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto">
  
  <!-- Back Button -->
  <div class="mb-6">
    <a href="{{ route('user.device.index') }}" class="btn btn-outline gap-2 rounded-full">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back to Devices') }}
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="deviceConnection()">
    
    <!-- Main Connection Card -->
    <div class="lg:col-span-2">
      <div class="card bg-white shadow-xl">
        <div class="card-body p-8">
          
          <!-- Header with Status & Logout -->
          <div class="flex items-center justify-between mb-6">
            <div class="flex-1">
              <h2 class="text-2xl font-bold mb-1">{{ __('Connect WhatsApp') }}</h2>
              
              <!-- Status Indicator -->
              <div class="flex items-center gap-2">
                <template x-if="connected">
                  <div class="flex items-center gap-2 text-success">
                    <span class="material-icons text-sm animate-pulse">check_circle</span>
                    <span class="font-semibold">{{ __('Online / Connected') }}</span>
                  </div>
                </template>
                <template x-if="!connected">
                  <p class="text-base-content/60">{{ __('Choose your preferred connection method') }}</p>
                </template>
              </div>
            </div>
            <button @click="logout()" x-show="connected" class="btn btn-error btn-sm gap-2 rounded-full">
              <span class="material-icons text-sm">logout</span>
              {{ __('Logout') }}
            </button>
          </div>

          <!-- Connection Method Tabs -->
          <div class="tabs tabs-boxed bg-base-200 mb-6">
            <button @click="switchMethod('qr')" 
                    :class="method === 'qr' ? 'tab tab-active' : 'tab'" 
                    class="gap-2 flex-1">
              <span class="material-icons text-sm">qr_code_scanner</span>
              {{ __('QR Code') }}
            </button>
            <button @click="switchMethod('phone')" 
                    :class="method === 'phone' ? 'tab tab-active' : 'tab'"
                    class="gap-2 flex-1">
              <span class="material-icons text-sm">phone_iphone</span>
              {{ __('Phone Pairing') }}
            </button>
          </div>

          <!-- QR Code Method -->
          <div x-show="method === 'qr'" x-cloak>
            <div class="qr-code-display">
              <!-- Loading State -->
              <div x-show="!qrCode && !connected && !error" class="text-center p-8 border border-primary/20 rounded-3xl bg-base-300/30 relative overflow-hidden backdrop-blur-md max-w-md mx-auto w-full">
                <!-- Glow effect -->
                <div class="absolute inset-0 bg-gradient-to-t from-primary/5 via-transparent to-transparent"></div>
                
                <!-- Hacker-style scanning line -->
                <div class="w-full h-0.5 bg-primary/45 absolute left-0 top-0 scanner-line"></div>
                
                <!-- Tech loader icon -->
                <div class="relative w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                  <div class="absolute inset-0 rounded-full border-4 border-dashed border-primary animate-spin" style="animation-duration: 8s;"></div>
                  <div class="absolute inset-2 rounded-full border-4 border-dotted border-secondary animate-spin" style="animation-duration: 4s; animation-direction: reverse;"></div>
                  <span class="material-symbols-outlined text-4xl text-primary animate-pulse">terminal</span>
                </div>
                
                <p class="text-lg font-bold tracking-wider text-primary font-mono mb-2 uppercase">{{ __('Initializing Socket...') }}</p>
                <div class="font-mono text-[10px] text-base-content/60 space-y-1 mb-2 text-left bg-base-300/50 p-4 rounded-xl border border-base-content/5 max-h-[140px] overflow-y-auto">
                  <template x-for="log in terminalLogs">
                    <div x-text="log" class="font-mono"></div>
                  </template>
                  <div class="animate-pulse font-mono text-primary/70">>> CONNECTING_WS_STREAM<span class="inline-block w-1.5 h-3 bg-primary ml-1 animate-pulse"></span></div>
                </div>
              </div>

              <!-- QR Code Display -->
              <div x-show="qrCode && !connected" class="flex flex-col items-center">
                <div class="relative p-6 bg-white rounded-2xl border-4 border-primary/20 shadow-2xl mb-6 group/qr overflow-hidden">
                  <!-- Laser Line Scanning Effect -->
                  <div class="absolute left-0 right-0 h-0.5 bg-primary/60 scanner-line z-10"></div>
                  <!-- Corner Neon brackets -->
                  <div class="absolute top-2 left-2 w-4 h-4 border-t-2 border-l-2 border-primary scanner-corner"></div>
                  <div class="absolute top-2 right-2 w-4 h-4 border-t-2 border-r-2 border-primary scanner-corner"></div>
                  <div class="absolute bottom-2 left-2 w-4 h-4 border-b-2 border-l-2 border-primary scanner-corner"></div>
                  <div class="absolute bottom-2 right-2 w-4 h-4 border-b-2 border-r-2 border-primary scanner-corner"></div>
                  
                  <img :src="qrCode" alt="QR Code" class="w-64 h-64 relative z-0" />
                </div>
                <p class="text-center font-mono font-bold uppercase tracking-wider text-primary animate-pulse mb-1">
                  <span class="inline-block w-2.5 h-2.5 rounded-full bg-primary animate-ping mr-1"></span>
                  {{ __('Waiting for Scan...') }}
                </p>
                <p class="text-center text-xs text-base-content/50 font-mono">{{ __('Navigate to WhatsApp > Linked Devices') }}</p>
              </div>

              <!-- Connected State -->
              <div x-show="connected" class="text-center p-8 border border-success/30 rounded-3xl bg-success/5 backdrop-blur-md max-w-md mx-auto w-full">
                <div class="relative w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                  <div class="absolute inset-0 rounded-full border border-success/40 animate-ping"></div>
                  <div class="w-16 h-16 rounded-full bg-success/15 flex items-center justify-center">
                    <span class="material-icons text-4xl text-success">wifi</span>
                  </div>
                </div>
                <h3 class="text-2xl font-black text-success font-mono uppercase tracking-wider mb-2">[ {{ __('Secure Connected') }} ]</h3>
                <div class="font-mono text-xs text-success/70 space-y-1 bg-success/5 p-4 rounded-xl border border-success/10 text-left">
                  <div>SYSTEM_STATUS: ONLINE</div>
                  <div>IP: SECURED</div>
                  <div>DEVICE_AGENT: READY_TO_BROADCAST</div>
                </div>
              </div>

              <!-- Error State -->
              <div x-show="error" class="text-center p-8 border border-error/30 rounded-3xl bg-error/5 backdrop-blur-md max-w-md mx-auto w-full cyber-glow-red">
                <div class="relative w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                  <div class="absolute inset-0 rounded-full border-2 border-dashed border-error animate-spin" style="animation-duration: 6s;"></div>
                  <div class="w-16 h-16 rounded-full bg-error/15 flex items-center justify-center">
                    <span class="material-symbols-outlined text-4xl text-error animate-pulse">dangerous</span>
                  </div>
                </div>
                <h3 class="text-2xl font-black text-error font-mono uppercase tracking-wider mb-2">[ {{ __('CONNECTION_TERMINATED') }} ]</h3>
                
                <div class="font-mono text-[10px] text-error/80 space-y-1 bg-error/10 p-4 rounded-xl border border-error/20 text-left mb-4">
                  <div class="font-bold">>> ERROR_CODE: SOCKET_FAILED</div>
                  <div>>> DIAGNOSTICS: <span x-text="errorMessage || 'HANDSHAKE_TIMEOUT'"></span></div>
                  <div class="animate-pulse text-error">>> SYSTEM: ACCESS_DENIED. PROTOCOL_ABORTED.</div>
                </div>
                
                <button @click="reconnect()" class="btn btn-error btn-outline rounded-full font-mono uppercase tracking-wider gap-2">
                  <span class="material-symbols-outlined">restart_alt</span>
                  {{ __('Reboot socket') }}
                </button>
              </div>
            </div>
          </div>

          <!-- Phone Pairing Method -->
          <div x-show="method === 'phone'" x-cloak>
            <div class="space-y-6">
              
              <!-- Important Warning -->
              <div x-show="!pairingCode && !connected" class="alert alert-warning shadow-lg">
                <div>
                  <span class="material-icons">warning</span>
                  <div>
                    <h3 class="font-bold">{{ __('Important!') }}</h3>
                    <div class="text-sm">
                      {{ __('Enter the SAME phone number as your WhatsApp account. Must include country code (e.g., 62 for Indonesia)') }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Phone Input Form -->
              <div x-show="!pairingCode && !connected" class="max-w-md mx-auto">
                <div class="form-control">
                  <label class="label">
                    <span class="label-text font-semibold text-lg">{{ __('Your WhatsApp Number') }}</span>
                  </label>
                  <div class="flex gap-3">
                    <input type="tel" 
                           x-model="phoneNumber" 
                           @keyup.enter="requestPairing()"
                           @input="formatPhoneNumber()"
                           class="input input-bordered input-lg flex-1" 
                           placeholder="08123456789 atau +628123456789" 
                           maxlength="20" />
                    <button @click="requestPairing()" 
                            :disabled="!phoneNumber || phoneNumber.length < 10 || loading"
                            class="btn btn-primary btn-lg gap-2 min-w-[150px] rounded-full">
                      <span x-show="!loading" class="material-icons">phone</span>
                      <span x-show="loading" class="loading loading-spinner"></span>
                      <span x-text="loading ? '{{ __('Getting...') }}' : '{{ __('Get Code') }}'"></span>
                    </button>
                  </div>
                  <label class="label">
                    <span class="label-text-alt text-info">{{ __('✓ Auto-converted to international format (62...)') }}</span>
                  </label>
                </div>
              </div>

              <!-- Pairing Code Display -->
              <div x-show="pairingCode && !connected" class="text-center">
                <div class="mb-8">
                  <div class="alert alert-info mb-6">
                    <span class="material-icons">info</span>
                    <span>{{ __('Enter this 8-digit code in WhatsApp') }}</span>
                  </div>
                  
                  <!-- PAIRING CODE - LARGE AND PROMINENT -->
                  <div class="bg-gradient-to-br from-primary/10 to-secondary/10 rounded-3xl p-8 mb-6 shadow-xl border-4 border-primary/20">
                    <p class="text-sm font-semibold text-base-content/70 mb-3 uppercase tracking-wider">{{ __('Your Pairing Code') }}</p>
                    <div class="flex justify-center items-center gap-2 mb-2">
                      <!-- Split code into 2 groups of 4 digits for better readability -->
                      <template x-if="pairingCode">
                        <div class="flex gap-3">
                          <div class="bg-white rounded-2xl px-8 py-6 shadow-lg">
                            <span class="text-6xl font-black text-primary tracking-widest font-mono" x-text="pairingCode.substring(0, 4)"></span>
                          </div>
                          <div class="flex items-center">
                            <span class="text-4xl text-base-content/30 font-bold">-</span>
                          </div>
                          <div class="bg-white rounded-2xl px-8 py-6 shadow-lg">
                            <span class="text-6xl font-black text-primary tracking-widest font-mono" x-text="pairingCode.substring(4, 8)"></span>
                          </div>
                        </div>
                      </template>
                    </div>
                    <p class="text-sm text-base-content/60 mt-4">
                      {{ __('Phone:') }} <span class="font-bold text-primary" x-text="phoneNumber"></span>
                    </p>
                  </div>

                  <!-- Copy Button -->
                  <button @click="navigator.clipboard.writeText(pairingCode); $dispatch('notify', {message: 'Code copied!'})" 
                          class="btn btn-outline btn-primary gap-2 mb-2 rounded-full">
                    <span class="material-icons text-sm">content_copy</span>
                    {{ __('Copy Code') }}
                  </button>
                  
                  <!-- Retry Button -->
                  <button @click="requestPairing()" 
                          class="btn btn-outline gap-2 rounded-full">
                    <span class="material-icons text-sm">refresh</span>
                    {{ __('Get New Code') }}
                  </button>
                </div>
                
                <!-- Troubleshooting Alert -->
                <div class="alert alert-error shadow-lg mb-4">
                  <div>
                    <span class="material-icons">error_outline</span>
                    <div>
                      <h3 class="font-bold">{{ __('If pairing fails:') }}</h3>
                      <ul class="text-xs list-disc ml-6 mt-1">
                        <li>{{ __('Make sure the phone number is EXACTLY the same as your WhatsApp') }}</li>
                        <li>{{ __('Check Settings → Profile in WhatsApp to verify your number') }}</li>
                        <li>{{ __('The number entered was:') }} <strong x-text="phoneNumber"></strong></li>
                        <li>{{ __('If wrong, click "Get New Code" with correct number') }}</li>
                      </ul>
                    </div>
                  </div>
                </div>

                <!-- Instructions -->
                <div class="card bg-base-100 max-w-md mx-auto">
                  <div class="card-body">
                    <h4 class="font-bold text-lg mb-3">{{ __('How to Link:') }}</h4>
                    <ol class="text-left space-y-2 text-sm">
                      <li class="flex gap-3">
                        <span class="font-bold text-primary">1.</span>
                        <span>{{ __('Open WhatsApp on your phone') }}</span>
                      </li>
                      <li class="flex gap-3">
                        <span class="font-bold text-primary">2.</span>
                        <span>{{ __('Go to Settings → Linked Devices') }}</span>
                      </li>
                      <li class="flex gap-3">
                        <span class="font-bold text-primary">3.</span>
                        <span>{{ __('Tap "Link a Device"') }}</span>
                      </li>
                      <li class="flex gap-3">
                        <span class="font-bold text-primary">4.</span>
                        <span>{{ __('Tap "Link with phone number instead"') }}</span>
                      </li>
                      <li class="flex gap-3">
                        <span class="font-bold text-primary">5.</span>
                        <span class="font-semibold text-error">{{ __('⚠️ VERIFY your phone number matches:') }} <span x-text="phoneNumber"></span></span>
                      </li>
                      <li class="flex gap-3">
                        <span class="font-bold text-primary">6.</span>
                        <span>{{ __('Enter the 8-digit code shown above') }}</span>
                      </li>
                    </ol>
                  </div>
                </div>

                <!-- Timer -->
                <div class="mt-4">
                  <p class="text-xs text-base-content/60">{{ __('Waiting for confirmation...') }}</p>
                  <span class="loading loading-dots loading-sm"></span>
                </div>
              </div>

              <!-- Connected State -->
              <div x-show="connected" class="text-center">
                <div class="w-24 h-24 rounded-full bg-success/20 flex items-center justify-center mx-auto mb-4">
                  <span class="material-icons text-6xl text-success">check_circle</span>
                </div>
                <h3 class="text-2xl font-bold text-success mb-2">{{ __('Connected!') }}</h3>
                <p class="text-base-content/60">{{ __('Your device is now connected') }}</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Instructions Sidebar -->
    <div class="lg:col-span-1">
      <!-- QR Instructions -->
      <div x-show="method === 'qr'" x-cloak class="card bg-base-100 shadow-md">
        <div class="card-body">
          <h3 class="card-title text-lg flex items-center gap-2">
            <span class="material-icons text-primary">help</span>
            {{ __('How to Scan') }}
          </h3>
          <ol class="text-sm space-y-3">
            <li class="flex gap-3">
              <span class="font-bold text-primary">1.</span>
              <span>{{ __('Open WhatsApp on your phone') }}</span>
            </li>
            <li class="flex gap-3">
              <span class="font-bold text-primary">2.</span>
              <span>{{ __('Tap Menu or Settings') }}</span>
            </li>
            <li class="flex gap-3">
              <span class="font-bold text-primary">3.</span>
              <span>{{ __('Select Linked Devices') }}</span>
            </li>
            <li class="flex gap-3">
              <span class="font-bold text-primary">4.</span>
              <span>{{ __('Tap Link a Device') }}</span>
            </li>
            <li class="flex gap-3">
              <span class="font-bold text-primary">5.</span>
              <span>{{ __('Scan the QR code') }}</span>
            </li>
          </ol>
        </div>
      </div>

      <!-- Phone Pairing Instructions -->
      <div x-show="method === 'phone'" x-cloak class="card bg-base-100 shadow-md">
        <div class="card-body">
          <h3 class="card-title text-lg flex items-center gap-2">
            <span class="material-icons text-primary">info</span>
            {{ __('Phone Pairing') }}
          </h3>
          <div class="text-sm space-y-3">
            <p>{{ __('Ideal for:') }}</p>
            <ul class="list-disc list-inside space-y-1 text-base-content/70">
              <li>{{ __('Mobile devices') }}</li>
              <li>{{ __('No camera available') }}</li>
              <li>{{ __('Same device pairing') }}</li>
            </ul>
            <div class="divider"></div>
            <p class="font-semibold">{{ __('Requirements:') }}</p>
            <ul class="list-disc list-inside space-y-1 text-base-content/70">
              <li>{{ __('WhatsApp installed') }}</li>
              <li>{{ __('Phone number registered') }}</li>
              <li>{{ __('Internet connection') }}</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Tips Card -->
      <div class="card bg-primary/5 shadow-md mt-4">
        <div class="card-body">
          <h3 class="card-title text-sm flex items-center gap-2">
            <span class="material-icons text-sm">tips_and_updates</span>
            {{ __('Tips') }}
          </h3>
          <ul class="text-xs space-y-2">
            <li>✓ {{ __('Keep both devices connected') }}</li>
            <li>✓ {{ __('Use stable internet') }}</li>
            <li>✓ {{ __('Allow notifications') }}</li>
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>

@push('js')
<script>
function deviceConnection() {
  return {
    method: 'qr',
    qrCode: null,
    pairingCode: null,
    phoneNumber: '',
    connected: false,
    error: false,
    errorMessage: '',
    loading: false,
    deviceId: '{{ $device->uuid }}',
    pollingInterval: null,
    terminalLogs: ['>> SYSTEM: INITIATING DIAL PROTOCOLS...'],
    logTimer: null,

    init() {
      // Start terminal log simulation
      this.simulateLogs();
      // Check existing session first
      this.checkExistingSession();
    },

    async checkExistingSession() {
      try {
        const response = await fetch('/user/check-session/{{ $device->uuid }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        });
        
        const data = await response.json();
        console.log('Checking existing session:', data);
        
        if (data.success && data.session) {
          // Already connected!
          this.connected = true;
          console.log('Device already connected');
        } else {
          // Not connected, proceed with connection based on method
          if (this.method === 'qr') {
            this.connectDevice();
          }
          // Phone pairing waits for user to click "Get Code"
        }
      } catch (err) {
        console.log('Session check error (proceeding with new connection):', err);
        // If check fails, proceed with new connection
        if (this.method === 'qr') {
          this.connectDevice();
        }
      }
    },

    switchMethod(newMethod) {
      console.log('Switching to method:', newMethod);
      
      // If already connected, just switch tab - don't destroy session!
      if (this.connected) {
        this.method = newMethod;
        console.log('Already connected, just switching tab view');
        return;
      }
      
      // Stop any ongoing polling
      if (this.pollingInterval) {
        clearInterval(this.pollingInterval);
        this.pollingInterval = null;
        console.log('Polling stopped');
      }
      if (this.logTimer) {
        clearInterval(this.logTimer);
        this.logTimer = null;
      }

      // Reset states (but not connected status)
      this.qrCode = null;
      this.pairingCode = null;
      this.error = false;
      this.errorMessage = '';
      this.loading = false;

      // Switch method
      this.method = newMethod;

      // Only create new session if not connected
      if (newMethod === 'qr' && !this.connected) {
        this.simulateLogs();
        this.connectDevice();
      }
      // For phone pairing, wait for user to click "Get Code"
    },

    simulateLogs() {
      const messages = [
        '>> ACCESS_TOKEN: VALIDATING...',
        '>> SOCKET_HOST: DIALING API SOCKET...',
        '>> TCP_ESTABLISHED: SHAKEHAND INITIATED...',
        '>> CRYPTO_SHIELD: INJECTING ENCRYPTION...',
        '>> SESSION_SECRET: VERIFIED...',
        '>> STREAM_CHANNEL: LISTENING FOR PACKETS...',
        '>> SYSTEM: SYNCING WITH BAILEYS ENGINE...',
        '>> GENERATING_QR_STREAM_PAYLOAD...'
      ];
      let idx = 0;
      this.terminalLogs = ['>> SYSTEM: INITIATING DIAL PROTOCOLS...'];
      if (this.logTimer) clearInterval(this.logTimer);
      this.logTimer = setInterval(() => {
        if (idx < messages.length) {
          this.terminalLogs.push(messages[idx]);
          idx++;
        } else {
          clearInterval(this.logTimer);
        }
      }, 500);
    },

    async deleteSessionAndReconnect() {
      try {
        // Delete old session first
        await fetch('/user/device/{{ $device->uuid }}/logout', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        });
        console.log('Old session deleted');
      } catch (err) {
        console.log('Delete session error (ignored):', err);
      }

      // Small delay then reconnect
      setTimeout(() => {
        if (this.method === 'qr') {
          this.connectDevice();
        }
      }, 500);
    },

    formatPhoneNumber() {
      let number = this.phoneNumber;
      
      // Remove all non-numeric characters (including + and spaces)
      number = number.replace(/[^0-9]/g, '');
      
      // If starts with 08, replace with 628
      if (number.startsWith('08')) {
        number = '62' + number.substring(1);
      }
      // If starts with 8 (user typed just 8...), add 62
      else if (number.startsWith('8') && number.length > 1) {
        number = '62' + number;
      }
      // If starts with 0 (but not 08), replace 0 with 62
      else if (number.startsWith('0')) {
        number = '62' + number.substring(1);
      }
      
      // Update the model
      this.phoneNumber = number;
      
      console.log('Formatted phone number:', number);
    },

    async connectDevice() {
      try {
        const response = await fetch('/user/create-session/{{ $device->uuid }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({
            phoneNumber: this.method === 'phone' ? this.phoneNumber : null
          })
        });

        // Check if response is ok
        if (!response.ok) {
          const text = await response.text();
          console.error('Response not ok:', response.status, text);
          throw new Error(`HTTP ${response.status}: ${text.substring(0, 100)}`);
        }

        const data = await response.json();
        console.log('Connect response:', data);

        if (data.success) {
          if (data.data && data.data.qr) {
            this.qrCode = data.data.qr;
            // Start polling only after QR received
            this.startPolling();
          } else if (data.data && data.data.pairingCode) {
            this.pairingCode = data.data.pairingCode;
            // Start polling only after pairing code received
            this.startPolling();
          }
        } else {
          this.error = true;
          this.errorMessage = data.message || 'Connection failed';
        }
      } catch (err) {
        this.error = true;
        this.errorMessage = err.message || 'Connection failed. Please try again.';
        console.error('Connect error:', err);
      }
    },

    async requestPairing() {
      if (!this.phoneNumber) {
        alert('Please enter phone number');
        return;
      }

      // Reset states
      this.pairingCode = null;
      this.connected = false;
      this.error = false;
      this.loading = true;
      
      // Stop any polling
      if (this.pollingInterval) {
        clearInterval(this.pollingInterval);
        this.pollingInterval = null;
      }

      try {
        const response = await fetch('/user/create-session/{{ $device->uuid }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({
            phoneNumber: this.phoneNumber
          })
        });

        const data = await response.json();
        console.log('Pairing response:', data);

        if (data.success && data.data && data.data.pairingCode) {
          this.pairingCode = data.data.pairingCode;
          console.log('Pairing code set:', this.pairingCode);
        } else {
          console.error('No pairing code in response:', data);
          alert(data.message || 'Failed to generate pairing code');
        }
      } catch (err) {
        alert('Error: ' + err.message);
        console.error('Pairing error:', err);
      } finally {
        this.loading = false;
      }
    },

    startPolling() {
      // Clear any existing polling first
      if (this.pollingInterval) {
        clearInterval(this.pollingInterval);
      }

      console.log('Starting polling...');
      this.pollingInterval = setInterval(async () => {
        try {
          const response = await fetch('/user/check-session/{{ $device->uuid }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          });
          
          const data = await response.json();
          
          if (data.success && data.session) {
            this.connected = true;
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
            console.log('Connected! Polling stopped.');
            setTimeout(() => {
              window.location.href = '{{ route('user.device.index') }}';
            }, 2000);
          }
        } catch (err) {
          console.error('Polling error:', err);
        }
      }, 3000);
    },

    reconnect() {
      this.error = false;
      this.qrCode = null;
      this.pairingCode = null;
      this.phoneNumber = '';
      this.simulateLogs();
      this.connectDevice();
    },

    async logout() {
      if (confirm('Are you sure you want to logout this device?')) {
        try {
          await fetch('/user/logout-session/{{ $device->uuid }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          });
          window.location.href = '{{ route('user.device.index') }}';
        } catch (err) {
          alert('Logout failed');
          console.error('Logout error:', err);
        }
      }
    }
  }
}
</script>
@endpush

@endsection
