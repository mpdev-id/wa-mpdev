@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Schedule Message')])
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold">{{ __('Schedule Message') }}</h1>
      <p class="text-base-content/60">{{ __('Schedule a message to send later') }}</p>
    </div>
    <a href="{{ route('user.schedule-message.index') }}" class="btn btn-outline gap-2">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back') }}
    </a>
  </div>

  <div class="card bg-white shadow-xl">
    <div class="card-body">
      <form method="POST" class="space-y-4" action="{{ route('user.schedule-message.store') }}">
        @csrf
        
        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Title') }}</span>
          </label>
          <input type="text" name="title" class="input input-bordered" placeholder="{{ __('Campaign name') }}" required maxlength="100" />
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Device') }}</span>
          </label>
          <select name="device" class="select select-bordered" required>
            <option value="">{{ __('Select Device') }}</option>
            @foreach($devices ?? [] as $device)
              <option value="{{ $device->id }}">+{{ $device->phone }} ({{ $device->name }})</option>
            @endforeach
          </select>
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Recipient Type') }}</span>
          </label>
          <select name="recipient_type" class="select select-bordered" id="recipient_type" required>
            <option value="group">{{ __('Contact Group') }}</option>
            <option value="single">{{ __('Single Number') }}</option>
          </select>
        </div>

        <div class="form-control" id="group_field">
          <label class="label">
            <span class="label-text font-medium">{{ __('Contact Group') }}</span>
          </label>
          <select name="group" class="select select-bordered" id="group_select">
            <option value="">{{ __('Select Group') }}</option>
            @foreach($groups ?? [] as $group)
              <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->contacts_count }} {{ __('contacts') }})</option>
            @endforeach
          </select>
        </div>

        <div class="form-control hidden" id="single_field">
          <label class="label">
            <span class="label-text font-medium">{{ __('Phone Number') }}</span>
          </label>
          <input type="text" name="phone" class="input input-bordered" id="phone_input" placeholder="628xxx" />
          <label class="label">
            <span class="label-text-alt text-base-content/60">{{ __('Enter WhatsApp number with country code (e.g., 628123456789)') }}</span>
          </label>
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Message Type') }}</span>
          </label>
          <select name="message_type" class="select select-bordered" id="message_type" required>
            <option value="text">{{ __('Plain Text') }}</option>
            <option value="template">{{ __('Template') }}</option>
          </select>
        </div>

        <div class="form-control" id="message_field">
          <label class="label">
            <span class="label-text font-medium">{{ __('Message') }}</span>
          </label>
          <textarea name="message" class="textarea textarea-bordered h-32" maxlength="1000"></textarea>
        </div>

        <div class="form-control hidden" id="template_field">
          <label class="label">
            <span class="label-text font-medium">{{ __('Template') }}</span>
          </label>
          <select name="template" class="select select-bordered">
            <option value="">{{ __('Select Template') }}</option>
            @foreach($templates ?? [] as $template)
              <option value="{{ $template->id }}">{{ $template->title }}</option>
            @endforeach
          </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Date') }}</span>
            </label>
            <input type="date" name="date" class="input input-bordered" required />
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Time') }}</span>
            </label>
            <input type="time" name="time" class="input input-bordered" required />
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Timezone') }}</span>
            </label>
            <select name="timezone" class="select select-bordered" required>
              <option value="Asia/Jakarta">Asia/Jakarta (WIB)</option>
              <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
              <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
            </select>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t">
          <a href="{{ route('user.schedule-message.index') }}" class="btn btn-outline">{{ __('Cancel') }}</a>
          <button type="submit" class="btn btn-primary gap-2">
            <span class="material-icons text-sm">schedule_send</span>
            {{ __('Schedule') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('js')
<script>
// Toggle recipient type
document.getElementById('recipient_type').addEventListener('change', function() {
  const groupField = document.getElementById('group_field');
  const singleField = document.getElementById('single_field');
  const groupSelect = document.getElementById('group_select');
  const phoneInput = document.getElementById('phone_input');
  
  if (this.value === 'single') {
    groupField.classList.add('hidden');
    groupSelect.removeAttribute('required');
    groupSelect.value = '';
    
    singleField.classList.remove('hidden');
    phoneInput.setAttribute('required', 'required');
  } else {
    groupField.classList.remove('hidden');
    groupSelect.setAttribute('required', 'required');
    
    singleField.classList.add('hidden');
    phoneInput.removeAttribute('required');
    phoneInput.value = '';
  }
});

// Toggle message type
document.getElementById('message_type').addEventListener('change', function() {
  const messageField = document.getElementById('message_field');
  const templateField = document.getElementById('template_field');
  
  if (this.value === 'template') {
    messageField.classList.add('hidden');
    messageField.querySelector('textarea').removeAttribute('required');
    templateField.classList.remove('hidden');
    templateField.querySelector('select').setAttribute('required', 'required');
  } else {
    messageField.classList.remove('hidden');
    messageField.querySelector('textarea').setAttribute('required', 'required');
    templateField.classList.add('hidden');
    templateField.querySelector('select').removeAttribute('required');
  }
});

// Set initial state
document.getElementById('group_select').setAttribute('required', 'required');

// Handle form submission
document.querySelector('form').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  const submitBtn = this.querySelector('button[type="submit"]');
  
  // Disable submit button
  submitBtn.disabled = true;
  submitBtn.classList.add('loading');
  
  // Show loading
  Swal.fire({
    title: 'Creating Schedule...',
    html: '<div class="text-base-content/70">Please wait while we process your request</div>',
    icon: 'info',
    showConfirmButton: false,
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });
  
  fetch(this.action, {
    method: 'POST',
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    }
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const contentType = response.headers.get('content-type');
    if (contentType && contentType.includes('application/json')) {
      return response.json();
    } else {
      return response.text().then(text => ({ message: text }));
    }
  })
  .then(data => {
    submitBtn.disabled = false;
    submitBtn.classList.remove('loading');
    
    Swal.fire({
      title: '🎉 Success!',
      html: '<div class="text-base-content/70">Schedule message created successfully!</div>',
      icon: 'success',
      confirmButtonText: '<span class="font-bold">✓ View Schedules</span>',
      showCancelButton: true,
      cancelButtonText: '<span class="font-bold">+ Create Another</span>',
      confirmButtonColor: '#10b981',
      cancelButtonColor: '#8b5cf6',
      reverseButtons: true,
      customClass: {
        confirmButton: 'btn btn-success btn-lg rounded-2xl shadow-xl shadow-success/30 px-8',
        cancelButton: 'btn btn-primary btn-lg rounded-2xl shadow-xl shadow-primary/30 px-8'
      },
      buttonsStyling: false
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = '{{ route("user.schedule-message.index") }}';
      } else {
        // Reset form for new entry
        document.querySelector('form').reset();
        document.getElementById('group_select').setAttribute('required', 'required');
      }
    });
  })
  .catch(error => {
    submitBtn.disabled = false;
    submitBtn.classList.remove('loading');
    
    Swal.fire({
      title: '❌ Error!',
      html: '<div class="text-base-content/70">Failed to create schedule. Please try again.</div>',
      icon: 'error',
      confirmButtonText: '<span class="font-bold">OK</span>',
      confirmButtonColor: '#ef4444',
      customClass: {
        confirmButton: 'btn btn-error btn-lg rounded-2xl shadow-xl shadow-error/30 px-8'
      },
      buttonsStyling: false
    });
  });
});
</script>
@endpush
@endsection