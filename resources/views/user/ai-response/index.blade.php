@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('AI Response Settings')])
@endsection

@section('content')
<div class="min-h-screen bg-base-200/30">
  <div class="container mx-auto px-6 py-8 max-w-6xl">
    
    <!-- Page Title -->
    <div class="mb-8">
      <div class="flex items-center gap-4 mb-3">
        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-violet-600 to-purple-600 flex items-center justify-center shadow-lg">
          <span class="material-icons text-white text-2xl">psychology</span>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-base-content">{{ __('AI Response') }}</h1>
          <p class="text-sm text-base-content/60">{{ __('Intelligent auto-reply with Google Gemini') }}</p>
        </div>
      </div>
    </div>

    <!-- Info Alert -->
    <div class="alert bg-blue-50 border border-blue-200 mb-8">
      <span class="material-icons text-blue-600">info</span>
      <div class="flex-1">
        <p class="text-sm text-blue-900">
          <span class="font-semibold">{{ __('Get your free API key:') }}</span>
          <a href="https://makersuite.google.com/app/apikey" target="_blank" class="link link-primary font-semibold ml-1">
            Google AI Studio →
          </a>
        </p>
      </div>
    </div>

    @if($devices->count() == 0)
      <!-- Empty State -->
      <div class="card bg-base-100 shadow-lg">
        <div class="card-body text-center py-20">
          <div class="w-24 h-24 mx-auto mb-6 bg-base-200 rounded-full flex items-center justify-center">
            <span class="material-icons text-base-content/30" style="font-size: 48px;">phonelink_off</span>
          </div>
          <h3 class="text-xl font-bold mb-2">{{ __('No Devices Connected') }}</h3>
          <p class="text-base-content/60 mb-6">{{ __('Connect a device to enable AI Response') }}</p>
          <a href="{{ route('user.device.index') }}" class="btn btn-primary">
            <span class="material-icons text-lg">add</span>
            {{ __('Add Device') }}
          </a>
        </div>
      </div>
    @else
      <!-- Devices List -->
      <div class="space-y-6">
        @foreach($devices as $device)
          @php
            $aiSetting = $aiSettings->get($device->id);
            $isActive = $aiSetting && $aiSetting->is_active;
          @endphp
          
          <div class="card bg-base-100 shadow-lg">
            <div class="card-body p-8">
              
              <!-- Header -->
              <div class="flex items-center justify-between pb-6 border-b border-base-300">
                <div class="flex items-center gap-4">
                  <div class="w-14 h-14 rounded-xl {{ $isActive ? 'bg-gradient-to-br from-violet-600 to-purple-600' : 'bg-base-300' }} flex items-center justify-center shadow">
                    <span class="material-icons text-white text-2xl">{{ $isActive ? 'auto_awesome' : 'phonelink' }}</span>
                  </div>
                  <div>
                    <h2 class="text-xl font-bold text-base-content">{{ $device->name }}</h2>
                    <p class="text-sm text-base-content/60 flex items-center gap-1">
                      <span class="material-icons" style="font-size: 14px;">phone_android</span>
                      {{ $device->phone ?? __('Not connected') }}
                    </p>
                  </div>
                </div>
                @if($isActive)
                  <div class="badge badge-success badge-lg gap-2">
                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                    {{ __('Active') }}
                  </div>
                @endif
              </div>

              <!-- Toggle Section -->
              @if($aiSetting)
                <div class="mt-6 p-5 bg-base-200 rounded-xl">
                  <div class="flex items-center justify-between">
                    <div>
                      <h3 class="font-bold text-base-content mb-1">{{ __('AI Auto-Reply Status') }}</h3>
                      <p class="text-sm text-base-content/60">
                        {{ $isActive ? __('AI will automatically respond to messages') : __('AI responses are disabled') }}
                      </p>
                    </div>
                    <input type="checkbox" 
                           class="toggle toggle-success toggle-lg ai-toggle-switch"
                           data-ai-id="{{ $aiSetting->id }}"
                           {{ $isActive ? 'checked' : '' }} />
                  </div>
                </div>
              @endif

              <!-- Configuration Form -->
              <form class="ai-config-form mt-8" 
                    data-device-id="{{ $device->id }}" 
                    data-ai-id="{{ $aiSetting->id ?? '' }}">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                  
                  <!-- Left Column -->
                  <div class="space-y-5">
                    
                    <!-- AI Type -->
                    <div>
                      <label class="block mb-2">
                        <span class="font-semibold text-base-content">{{ __('AI Type') }}</span>
                        <span class="text-error">*</span>
                      </label>
                      <select name="ai_type" class="select select-bordered w-full ai-type-select">
                        <option value="default" {{ ($aiSetting->ai_type ?? 'default') == 'default' ? 'selected' : '' }}>
                          🆓 Default AI (Free - UnlimitedAI)
                        </option>
                        <option value="custom" {{ ($aiSetting->ai_type ?? '') == 'custom' ? 'selected' : '' }}>
                          🔑 Custom AI (Your API Key)
                        </option>
                      </select>
                      <label class="label">
                        <span class="label-text-alt text-success">
                          {{ __('Default AI is free and works well for most cases') }}
                        </span>
                      </label>
                    </div>

                    <!-- API Key (Only for Custom AI) -->
                    <div class="api-key-field {{ ($aiSetting->ai_type ?? 'default') == 'default' ? 'hidden' : '' }}">
                      <label class="block mb-2">
                        <span class="font-semibold text-base-content">{{ __('Gemini API Key') }}</span>
                        <span class="text-error api-key-required">*</span>
                      </label>
                      <input type="text" 
                             name="gemini_api_key"
                             value="{{ $aiSetting->gemini_api_key ?? '' }}"
                             class="input input-bordered w-full" 
                             placeholder="AIzaSy..." />
                      <label class="label">
                        <span class="label-text-alt">
                          <a href="https://makersuite.google.com/app/apikey" target="_blank" class="link link-primary">
                            Get API Key →
                          </a>
                        </span>
                      </label>
                    </div>

                    <!-- Model (Only for Custom AI) -->
                    <div class="model-field {{ ($aiSetting->ai_type ?? 'default') == 'default' ? 'hidden' : '' }}">
                      <label class="block mb-2">
                        <span class="font-semibold text-base-content">{{ __('AI Model') }}</span>
                      </label>
                      <select name="model" class="select select-bordered w-full">
                        <option value="gemini-2.5-flash" {{ ($aiSetting->model ?? 'gemini-2.5-flash') == 'gemini-2.5-flash' ? 'selected' : '' }}>
                          Gemini 2.5 Flash (Recommended)
                        </option>
                        <option value="gemini-2.0-flash-thinking-exp-01-21" {{ ($aiSetting->model ?? '') == 'gemini-2.0-flash-thinking-exp-01-21' ? 'selected' : '' }}>
                          Gemini 2.0 Flash Thinking
                        </option>
                      </select>
                    </div>

                    <!-- Max Tokens (Only for Custom AI) -->
                    <div x-show="aiType === 'custom'">
                      <label class="block mb-2">
                        <span class="font-semibold text-base-content">{{ __('Max Response Length') }}</span>
                      </label>
                      <input type="number" 
                             name="max_tokens"
                             value="{{ $aiSetting->max_tokens ?? 500 }}"
                             class="input input-bordered w-full" 
                             min="100" 
                             max="2000" />
                      <label class="label">
                        <span class="label-text-alt">{{ __('100-2000 tokens') }}</span>
                      </label>
                    </div>

                  </div>

                  <!-- Right Column -->
                  <div class="space-y-5">
                    
                    <!-- Temperature (Only for Custom AI) -->
                    <div x-show="aiType === 'custom'" x-data="{ temperature: {{ $aiSetting->temperature ?? 0.7 }} }">
                      <label class="block mb-2">
                        <span class="font-semibold text-base-content">{{ __('Creativity Level') }}</span>
                        <span class="text-primary ml-2 font-mono text-sm" x-text="temperature.toFixed(2)">{{ $aiSetting->temperature ?? '0.7' }}</span>
                      </label>
                      <input type="range" 
                             name="temperature"
                             x-model.number="temperature"
                             min="0" 
                             max="2" 
                             step="0.01" 
                             class="range range-primary" />
                      <div class="flex justify-between text-xs text-base-content/60 mt-2 px-1">
                        <span>🎯 Precise (0.0)</span>
                        <span>⚖️ Balanced (1.0)</span>
                        <span>🎨 Creative (2.0)</span>
                      </div>
                    </div>

                    <!-- System Prompt -->
                    <div>
                      <label class="block mb-2">
                        <span class="font-semibold text-base-content">{{ __('System Instructions') }}</span>
                        <span class="text-base-content/40 text-sm ml-1">({{ __('Optional') }})</span>
                      </label>
                      <textarea name="system_prompt" 
                                class="textarea textarea-bordered w-full" 
                                rows="4"
                                placeholder="{{ __('You are a helpful assistant...') }}">{{ $aiSetting->system_prompt ?? '' }}</textarea>
                    </div>

                    <!-- Reply to Groups -->
                    <div class="form-control">
                      <label class="label cursor-pointer justify-start gap-3 py-3 px-4 bg-base-200 rounded-lg">
                        <input type="checkbox" 
                               name="reply_to_groups" 
                               class="checkbox checkbox-primary" 
                               {{ ($aiSetting->reply_to_groups ?? false) ? 'checked' : '' }} />
                        <div>
                          <span class="font-semibold block">{{ __('Reply in Group Chats') }}</span>
                          <span class="text-xs text-base-content/60">{{ __('Enable AI to respond in WhatsApp groups') }}</span>
                        </div>
                      </label>
                    </div>

                    <!-- Contact Filter -->
                    <div class="divider text-sm">{{ __('Contact Filter') }}</div>
                    
                    <div>
                      <label class="block mb-2">
                        <span class="font-semibold text-base-content">{{ __('Filter Mode') }}</span>
                      </label>
                      <select name="contact_filter_mode" class="select select-bordered w-full contact-filter-mode">
                        <option value="all" {{ ($aiSetting->contact_filter_mode ?? 'all') == 'all' ? 'selected' : '' }}>
                          🌐 {{ __('Reply to All Contacts') }}
                        </option>
                        <option value="whitelist" {{ ($aiSetting->contact_filter_mode ?? '') == 'whitelist' ? 'selected' : '' }}>
                          ✅ {{ __('Whitelist (Only Listed Numbers)') }}
                        </option>
                        <option value="blacklist" {{ ($aiSetting->contact_filter_mode ?? '') == 'blacklist' ? 'selected' : '' }}>
                          ❌ {{ __('Blacklist (Block Listed Numbers)') }}
                        </option>
                      </select>
                      <label class="label">
                        <span class="label-text-alt text-base-content/60">
                          {{ __('Control which contacts can receive AI responses') }}
                        </span>
                      </label>
                    </div>

                    <div class="contact-numbers-field {{ ($aiSetting->contact_filter_mode ?? 'all') == 'all' ? 'hidden' : '' }}">
                      <label class="block mb-2">
                        <span class="font-semibold text-base-content">{{ __('Contact Numbers') }}</span>
                        <span class="text-base-content/40 text-sm ml-1">({{ __('One per line') }})</span>
                      </label>
                      <textarea name="contact_numbers" 
                                class="textarea textarea-bordered w-full" 
                                rows="5"
                                placeholder="62812345678&#10;62898765432&#10;62877123456">{{ $aiSetting->contact_numbers ?? '' }}</textarea>
                      <label class="label">
                        <span class="label-text-alt text-info">
                          💡 {{ __('Enter phone numbers without + or spaces (e.g., 62812345678)') }}
                        </span>
                      </label>
                    </div>

                  </div>

                </div>

                <!-- Actions -->
                <div class="divider"></div>
                <div class="flex gap-3">
                  <button type="submit" class="btn btn-primary flex-1">
                    <span class="material-icons text-lg">save</span>
                    {{ __('Save Configuration') }}
                  </button>
                  
                  @if($aiSetting)
                    <button type="button" 
                            class="btn btn-outline btn-error delete-ai-btn"
                            data-ai-id="{{ $aiSetting->id }}">
                      <span class="material-icons text-lg">delete_outline</span>
                      {{ __('Delete') }}
                    </button>
                  @endif
                </div>

              </form>

            </div>
          </div>
        @endforeach
      </div>
    @endif

  </div>
</div>

@push('js')
<script>
$(document).ready(function() {
  
  // Toggle AI Type Fields
  $('.ai-type-select').on('change', function() {
    const aiType = $(this).val();
    const form = $(this).closest('form');
    const apiKeyField = form.find('.api-key-field');
    const modelField = form.find('.model-field');
    const apiKeyInput = form.find('[name="gemini_api_key"]');
    
    if (aiType === 'default') {
      apiKeyField.addClass('hidden');
      modelField.addClass('hidden');
      apiKeyInput.removeAttr('required');
    } else {
      apiKeyField.removeClass('hidden');
      modelField.removeClass('hidden');
      apiKeyInput.attr('required', 'required');
    }
  });
  
  // Toggle Contact Filter Fields
  $('.contact-filter-mode').on('change', function() {
    const filterMode = $(this).val();
    const form = $(this).closest('form');
    const contactNumbersField = form.find('.contact-numbers-field');
    
    if (filterMode === 'all') {
      contactNumbersField.addClass('hidden');
    } else {
      contactNumbersField.removeClass('hidden');
    }
  });
  
  // Save Configuration
  $('.ai-config-form').on('submit', function(e) {
    e.preventDefault();
    
    const form = $(this);
    const submitBtn = form.find('button[type="submit"]');
    const originalText = submitBtn.html();
    
    submitBtn.prop('disabled', true).html('<span class="loading loading-spinner loading-sm"></span> {{ __('Saving...') }}');
    
    
    const aiType = form.find('[name="ai_type"]').val();
    const formData = {
      device_id: form.data('device-id'),
      ai_type: aiType,
      max_tokens: parseInt(form.find('[name="max_tokens"]').val()),
      temperature: parseFloat(form.find('[name="temperature"]').val()),
      system_prompt: form.find('[name="system_prompt"]').val(),
      reply_to_groups: form.find('[name="reply_to_groups"]').is(':checked') ? true : false,
      contact_filter_mode: form.find('[name="contact_filter_mode"]').val(),
      contact_numbers: form.find('[name="contact_numbers"]').val()
    };
    
    // Only include API key and model for custom AI
    if (aiType === 'custom') {
      formData.gemini_api_key = form.find('[name="gemini_api_key"]').val();
      formData.model = form.find('[name="model"]').val();
    }
    
    $.ajax({
      url: '{{ route('user.ai-response.store') }}',
      type: 'POST',
      data: JSON.stringify(formData),
      contentType: 'application/json',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      success: function(response) {
        showToast('success', response.message || '{{ __('Configuration saved successfully!') }}');
        setTimeout(() => window.location.reload(), 1500);
      },
      error: function(xhr) {
        const message = xhr.responseJSON?.message || '{{ __('Failed to save configuration') }}';
        showToast('error', message);
        submitBtn.prop('disabled', false).html(originalText);
      }
    });
  });
  
  // Toggle AI
  $('.ai-toggle-switch').on('change', function() {
    const toggle = $(this);
    const aiId = toggle.data('ai-id');
    
    $.ajax({
      url: `/user/ai-response/${aiId}/toggle`,
      type: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      success: function(response) {
        showToast('success', response.message);
        setTimeout(() => window.location.reload(), 1000);
      },
      error: function(xhr) {
        showToast('error', '{{ __('Failed to toggle AI status') }}');
        toggle.prop('checked', !toggle.prop('checked'));
      }
    });
  });
  
  // Delete Configuration
  $('.delete-ai-btn').on('click', function() {
    if (!confirm('{{ __('Are you sure you want to delete this AI configuration?') }}')) return;
    
    const aiId = $(this).data('ai-id');
    
    $.ajax({
      url: `/user/ai-response/${aiId}`,
      type: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      success: function(response) {
        showToast('success', response.message);
        setTimeout(() => window.location.reload(), 1000);
      },
      error: function(xhr) {
        showToast('error', '{{ __('Failed to delete configuration') }}');
      }
    });
  });
  
  // Toast Notifications
  function showToast(type, message) {
    if ($('#toast-container').length === 0) {
      $('body').append('<div id="toast-container" class="toast toast-top toast-end z-50"></div>');
    }
    
    const icon = type === 'success' ? 'check_circle' : 'error';
    const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
    
    const toast = $(`
      <div class="alert ${alertClass} shadow-lg">
        <span class="material-icons">${icon}</span>
        <span>${message}</span>
      </div>
    `);
    
    $('#toast-container').append(toast);
    
    setTimeout(() => {
      toast.fadeOut(300, function() {
        $(this).remove();
      });
    }, 4000);
  }
  
});
</script>
@endpush

@endsection
