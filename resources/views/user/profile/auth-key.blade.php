@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('API Authentication')])
@endsection

@section('content')

<div class="max-w-4xl mx-auto" x-data="{ showRegenerateConfirm: false }">
  
  <!-- Page Header -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-xl md:text-2xl break-words font-bold text-base-content mb-2">{{ __('API Authentication') }}</h1>
      <p class="text-base-content/60">{{ __('Manage your API authentication key') }}</p>
    </div>
    <a href="{{ url('user/dashboard') }}" class="btn btn-outline gap-2 rounded-full">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Dashboard') }}
    </a>
  </div>

  <!-- API Key Card -->
  <div class="card bg-white shadow-xl animate-slide-up">
    <div class="card-body">
      <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-full bg-warning/10 flex items-center justify-center">
          <span class="material-icons text-warning">key</span>
        </div>
        <div>
          <h2 class="text-xl font-bold">{{ __('Authentication Key') }}</h2>
          <p class="text-sm text-base-content/60">{{ __('Use this key to authenticate your API requests') }}</p>
        </div>
      </div>

      <!-- API Key Display -->
      <div class="form-control">
        <label class="label">
          <span class="label-text font-medium flex items-center gap-2">
            <span class="material-icons text-sm">vpn_key</span>
            {{ __('Your API Key') }}
          </span>
          <span class="label-text-alt text-warning">{{ __('Keep this secret!') }}</span>
        </label>
        <div class="flex gap-2">
          <input 
            type="text" 
            id="api-key-input"
            class="input input-bordered w-full font-mono" 
            value="{{ Auth::user()->authkey }}" 
            readonly 
          />
          <button 
            onclick="copyToClipboard()" 
            class="btn btn-square btn-outline rounded-full"
            title="{{ __('Copy to clipboard') }}"
          >
            <span class="material-icons">content_copy</span>
          </button>
        </div>
      </div>

      <!-- API Info Alert -->
      <div class="alert alert-warning mt-4">
        <span class="material-icons">warning</span>
        <div class="text-sm">
          <strong>{{ __('Important:') }}</strong>
          <ul class="list-disc list-inside mt-2">
            <li>{{ __('Keep your API key secret and secure') }}</li>
            <li>{{ __('Never share it publicly or commit to version control') }}</li>
            <li>{{ __('Regenerating will invalidate the old key') }}</li>
          </ul>
        </div>
      </div>

      <!-- Regenerate Button -->
      <div class="flex justify-end mt-6 pt-6 border-t border-base-200">
        <button 
          @click="showRegenerateConfirm = true" 
          class="btn btn-error gap-2 rounded-full"
        >
          <span class="material-icons text-sm">refresh</span>
          {{ __('Regenerate API Key') }}
        </button>
      </div>
    </div>
  </div>

  <!-- API Documentation -->
  <div class="card bg-base-100 shadow-md mt-6 animate-slide-up" style="animation-delay: 0.1s">
    <div class="card-body">
      <h3 class="card-title flex items-center gap-2">
        <span class="material-icons">integration_instructions</span>
        {{ __('Quick Start Guide') }}
      </h3>
      
      <div class="space-y-4 mt-4">
        <!-- API Endpoint -->
        <div>
          <h4 class="font-semibold mb-2">{{ __('API Endpoint') }}</h4>
          <div class="mockup-code">
            <pre><code>{{ url('/api/v1') }}</code></pre>
          </div>
        </div>

        <!-- Authentication Header -->
        <div>
          <h4 class="font-semibold mb-2">{{ __('Authentication Header') }}</h4>
          <div class="mockup-code">
            <pre><code>Authorization: Bearer {{ Auth::user()->authkey }}</code></pre>
          </div>
        </div>

        <!-- Example Request -->
        <div>
          <h4 class="font-semibold mb-2">{{ __('Example Request') }}</h4>
          <div class="mockup-code text-sm">
            <pre><code>curl -X POST {{ url('/api/v1/send-message') }} \
  -H "Authorization: Bearer {{ Auth::user()->authkey }}" \
  -H "Content-Type: application/json" \
  -d '{"phone": "628123456789", "message": "Hello"}'</code></pre>
          </div>
        </div>

        <!-- Documentation Link -->
        <div class="alert">
          <span class="material-icons">menu_book</span>
          <div>
            <h4 class="font-semibold">{{ __('Full Documentation') }}</h4>
            <p class="text-sm">{{ __('Visit our API documentation for complete reference') }}</p>
          </div>
          <a href="{{ url('/docs/api') }}" target="_blank" class="btn btn-sm btn-primary rounded-full">
            {{ __('View Docs') }}
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Regenerate Confirmation Modal -->
  <div x-show="showRegenerateConfirm" x-cloak class="modal modal-open" @click.self="showRegenerateConfirm = false">
    <div class="modal-box">
      <h3 class="font-bold text-lg flex items-center gap-2 text-error">
        <span class="material-icons">warning</span>
        {{ __('Regenerate API Key?') }}
      </h3>
      
      <div class="py-4">
        <p class="mb-4">{{ __('Are you sure you want to regenerate your API key?') }}</p>
        <div class="alert alert-error">
          <span class="material-icons">info</span>
          <div class="text-sm">
            <strong>{{ __('Warning:') }}</strong>
            <p>{{ __('This action will invalidate your current API key. All applications using the old key will stop working until updated.') }}</p>
          </div>
        </div>
      </div>

      <form method="POST" action="{{ route('user.profile.update','auth-key') }}" class="ajaxform_instant_reload">
        @csrf
        @method('PUT')
        
        <div class="modal-action">
          <button type="button" @click="showRegenerateConfirm = false" class="btn btn-outline rounded-full">
            {{ __('Cancel') }}
          </button>
          <button type="submit" class="btn btn-error submit-button gap-2 rounded-full">
            <span class="material-icons text-sm">refresh</span>
            {{ __('Yes, Regenerate') }}
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

@push('js')
<script>
function copyToClipboard() {
  const input = document.getElementById('api-key-input');
  input.select();
  input.setSelectionRange(0, 99999); // For mobile
  
  try {
    document.execCommand('copy');
    
    // Show success notification
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<span class="material-icons">check</span>';
    btn.classList.add('btn-success');
    
    setTimeout(() => {
      btn.innerHTML = originalHTML;
      btn.classList.remove('btn-success');
    }, 2000);
  } catch (err) {
    console.error('Failed to copy:', err);
  }
}
</script>
@endpush