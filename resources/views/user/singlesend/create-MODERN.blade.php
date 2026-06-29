@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Send Message')])
@endsection

@push('topcss')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select2/dist/css/select2.min.css') }}">
@endpush

@section('content')

<div class="max-w-7xl mx-auto">
  
  <!-- Page Header -->
  <div class="mb-6">
    <h1 class="text-xl md:text-2xl break-words font-bold text-base-content mb-2">{{ __('Send Custom Message') }}</h1>
    <p class="text-base-content/60">{{ __('Send personalized WhatsApp messages with various formats') }}</p>
  </div>

  <!-- Main Card with Tabs -->
  <div class="card bg-white shadow-xl">
    <div class="card-body p-0">
      
      <!-- Tab Navigation -->
      <div class="border-b border-base-200">
        <div class="tabs tabs-boxed bg-transparent p-4 pb-0 gap-2" role="tablist">
          <button class="tab tab-active gap-2 rounded-full" data-tab="plain-text">
            <span class="material-icons text-sm">text_fields</span>
            {{ __('Plain Text') }}
          </button>
          <button class="tab gap-2 rounded-full" data-tab="with-media">
            <span class="material-icons text-sm">image</span>
            {{ __('With Media') }}
          </button>
          <button class="tab gap-2 rounded-full" data-tab="with-button">
            <span class="material-icons text-sm">smart_button</span>
            {{ __('With Button') }}
          </button>
          <button class="tab gap-2 rounded-full" data-tab="location">
            <span class="material-icons text-sm">location_on</span>
            {{ __('Send Location') }}
          </button>
        </div>
      </div>

      <!-- Tab Content -->
      <div class="p-6">
        
        <!-- Plain Text Tab -->
        <div id="tab-plain-text" class="tab-content">
          <form method="POST" action="{{ route('user.sent.customtext','plain-text') }}" class="ajaxform_reset_form space-y-6">
            @csrf
            
            <!-- Device & Receiver -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Select Device -->
              <div class="form-control">
                <label class="label">
                  <span class="label-text font-medium flex items-center gap-2">
                    <span class="material-icons text-sm">devices</span>
                    {{ __('Select Device') }}
                  </span>
                </label>
                <select class="select select-bordered w-full" name="device" required>
                  @foreach($devices as $device)
                  <option value="{{ $device->id }}">{{ $device->name }} (+{{ $device->phone }})</option>
                  @endforeach
                </select>
              </div>

              <!-- Receiver Number -->
              <div class="form-control">
                <label class="label">
                  <span class="label-text font-medium flex items-center gap-2">
                    <span class="material-icons text-sm">phone</span>
                    {{ __('Message To (Receiver)') }}
                  </span>
                </label>
                <input 
                  type="number" 
                  class="input input-bordered w-full" 
                  name="phone" 
                  placeholder="{{ __('Enter phone number with country code') }}" 
                  required 
                  minlength="10" 
                  maxlength="15"
                />
              </div>
            </div>

            <!-- Message -->
            <div class="form-control">
              <label class="label">
                <span class="label-text font-medium flex items-center gap-2">
                  <span class="material-icons text-sm">message</span>
                  {{ __('Message') }}
                </span>
                <span class="label-text-alt text-base-content/60">Max 1000 characters</span>
              </label>
              
              <!-- Formatting Toolbar -->
              <div class="btn-group mb-2">
                <button type="button" class="btn btn-sm btn-outline rounded-full" onclick="insertTag('myTextarea', '*', '*')">
                  <strong>B</strong>
                </button>
                <button type="button" class="btn btn-sm btn-outline rounded-full" onclick="insertTag('myTextarea', '_', '_')">
                  <em>I</em>
                </button>
                <button type="button" class="btn btn-sm btn-outline rounded-full" onclick="insertTag('myTextarea', '```', '```')">
                  <code>{}</code>
                </button>
                <button type="button" class="btn btn-sm btn-outline rounded-full" onclick="insertTag('myTextarea', '~', '~')">
                  <s>S</s>
                </button>
                <button type="button" class="btn btn-sm btn-outline emojipick rounded-full">
                  <span class="material-icons text-sm">emoji_emotions</span>
                </button>
              </div>
              
              <textarea 
                id="myTextarea" 
                class="textarea textarea-bordered h-48 w-full" 
                name="message" 
                placeholder="{{ __('Type your message here...') }}"
                required 
                maxlength="1000"
              ></textarea>
            </div>

            <!-- Save as Template -->
            <div class="card bg-base-100">
              <div class="card-body p-4">
                <label class="label cursor-pointer justify-start gap-4">
                  <input type="checkbox" class="toggle toggle-primary save-template" name="templatestatus" id="plain-text" data-target=".plain-title" />
                  <div>
                    <span class="label-text font-semibold">{{ __('Save this as a template?') }}</span>
                    <p class="text-xs text-base-content/60">{{ __('Reuse this message format later') }}</p>
                  </div>
                </label>
                
                <div class="plain-title hidden mt-4">
                  <div class="form-control">
                    <label class="label">
                      <span class="label-text font-medium">{{ __('Template Name') }}</span>
                    </label>
                    <input type="text" name="template_name" class="input input-bordered w-full" placeholder="{{ __('e.g., Welcome Message') }}" />
                  </div>
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3">
              <button type="reset" class="btn btn-outline rounded-full">
                <span class="material-icons text-sm">refresh</span>
                {{ __('Reset') }}
              </button>
              <button type="submit" class="btn btn-primary gap-2 rounded-full">
                <span class="material-icons text-sm">send</span>
                {{ __('Send Message') }}
              </button>
            </div>
          </form>
        </div>

        <!-- Other tabs content would go here following same pattern -->
        <div id="tab-with-media" class="tab-content hidden">
          <div class="alert alert-info">
            <span class="material-icons">info</span>
            <span>{{ __('With Media tab content - Similar structure to Plain Text with file upload') }}</span>
          </div>
        </div>

        <div id="tab-with-button" class="tab-content hidden">
          <div class="alert alert-info">
            <span class="material-icons">info</span>
            <span>{{ __('With Button tab content - Similar structure with button configuration') }}</span>
          </div>
        </div>

        <div id="tab-location" class="tab-content hidden">
          <div class="alert alert-info">
            <span class="material-icons">info</span>
            <span>{{ __('Location tab content - Similar structure with map integration') }}</span>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Helper Card -->
  <div class="card bg-base-100 shadow-md mt-6">
    <div class="card-body">
      <h3 class="card-title text-lg flex items-center gap-2">
        <span class="material-icons">lightbulb</span>
        {{ __('Quick Tips') }}
      </h3>
      <ul class="list-disc list-inside space-y-2 text-sm text-base-content/70">
        <li>{{ __('Always include country code in phone numbers (e.g., +1234567890)') }}</li>
        <li>{{ __('Use formatting buttons to make your message stand out') }}</li>
        <li>{{ __('Save frequently used messages as templates for quick access') }}</li>
        <li>{{ __('Test messages with your own number first') }}</li>
      </ul>
    </div>
  </div>

</div>

@endsection

@push('topjs')
<script src="{{ asset('assets/vendor/select2/dist/js/select2.min.js') }}"></script>
@endpush

@push('js')
<script>
// Tab switching
document.querySelectorAll('.tab').forEach(button => {
  button.addEventListener('click', function() {
    const tabName = this.dataset.tab;
    
    // Update active tab
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('tab-active'));
    this.classList.add('tab-active');
    
    // Show corresponding content
    document.querySelectorAll('.tab-content').forEach(content => {
      content.classList.add('hidden');
    });
    document.getElementById(`tab-${tabName}`).classList.remove('hidden');
  });
});

// Save template toggle
document.querySelectorAll('.save-template').forEach(checkbox => {
  checkbox.addEventListener('change', function() {
    const target = document.querySelector(this.dataset.target);
    if (target) {
      target.classList.toggle('hidden');
    }
  });
});

// Text formatting function
function insertTag(textareaId, startTag, endTag) {
  const textarea = document.getElementById(textareaId);
  const start = textarea.selectionStart;
  const end = textarea.selectionEnd;
  const selectedText = textarea.value.substring(start, end);
  const replacement = startTag + selectedText + endTag;
  
  textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
  textarea.focus();
  textarea.selectionStart = start + startTag.length;
  textarea.selectionEnd = start + startTag.length + selectedText.length;
}
</script>
@endpush
