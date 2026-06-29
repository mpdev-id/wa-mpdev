@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('New Support Ticket')])
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-xl md:text-2xl break-words font-bold text-base-content mb-2">{{ __('New Support Ticket') }}</h1>
      <p class="text-base-content/60">{{ __('We\'re here to help you') }}</p>
    </div>
    <a href="{{ route('user.support.index') }}" class="btn btn-outline gap-2 rounded-full">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back') }}
    </a>
  </div>

  <div class="card bg-white shadow-xl animate-slide-up">
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data" class="space-y-4" action="{{ route('user.support.store') }}">
        @csrf
        
        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Subject') }}</span>
          </label>
          <input 
            type="text" 
            name="subject" 
            class="input input-bordered w-full" 
            placeholder="{{ __('Brief description of your issue') }}"
            required 
          />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Category') }}</span>
            </label>
            <select name="category" class="select select-bordered w-full" required>
              <option value="">{{ __('Select Category') }}</option>
              <option value="technical">{{ __('Technical Issue') }}</option>
              <option value="billing">{{ __('Billing & Payment') }}</option>
              <option value="general">{{ __('General Question') }}</option>
              <option value="feature">{{ __('Feature Request') }}</option>
              <option value="bug">{{ __('Bug Report') }}</option>
            </select>
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Priority') }}</span>
            </label>
            <select name="priority" class="select select-bordered w-full" required>
              <option value="low">{{ __('Low') }}</option>
              <option value="medium" selected>{{ __('Medium') }}</option>
              <option value="high">{{ __('High') }}</option>
              <option value="urgent">{{ __('Urgent') }}</option>
            </select>
          </div>
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Description') }}</span>
          </label>
          <textarea 
            name="description" 
            class="textarea textarea-bordered h-40" 
            placeholder="{{ __('Please describe your issue in detail...') }}"
            required
          ></textarea>
          <label class="label">
            <span class="label-text-alt">{{ __('Provide as much detail as possible to help us assist you better') }}</span>
          </label>
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium flex items-center gap-2">
              <span class="material-icons text-sm">attach_file</span>
              {{ __('Attachments') }}
            </span>
            <span class="label-text-alt badge badge-sm">{{ __('Optional') }}</span>
          </label>
          <input 
            type="file" 
            name="attachments[]" 
            class="file-input file-input-bordered w-full" 
            multiple
            accept="image/*,.pdf,.doc,.docx"
          />
          <label class="label">
            <span class="label-text-alt">{{ __('Max 5 files, 10MB each. Supported: Images, PDF, DOC') }}</span>
          </label>
        </div>

        <div class="alert alert-info">
          <span class="material-icons">info</span>
          <div class="text-sm">
            <strong>{{ __('Response Time:') }}</strong>
            <ul class="list-disc list-inside mt-2 space-y-1">
              <li>{{ __('Low/Medium priority: Within 24 hours') }}</li>
              <li>{{ __('High priority: Within 12 hours') }}</li>
              <li>{{ __('Urgent: Within 4 hours') }}</li>
            </ul>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-base-200">
          <a href="{{ route('user.support.index') }}" class="btn btn-outline rounded-full">
            {{ __('Cancel') }}
          </a>
          <button type="submit" class="btn btn-primary gap-2 rounded-full">
            <span class="material-icons text-sm">send</span>
            {{ __('Submit Ticket') }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Help Card -->
  <div class="card bg-base-100 shadow-md mt-6 animate-slide-up" style="animation-delay: 0.1s">
    <div class="card-body">
      <h3 class="card-title text-base flex items-center gap-2">
        <span class="material-icons text-sm">help</span>
        {{ __('Before You Submit') }}
      </h3>
      <div class="text-sm text-base-content/70 space-y-2">
        <p>{{ __('Have you checked our FAQ or documentation?') }}</p>
        <div class="flex gap-2">
          <a href="#" class="btn btn-outline btn-sm gap-1 rounded-full">
            <span class="material-icons text-xs">book</span>
            {{ __('Documentation') }}
          </a>
          <a href="#" class="btn btn-outline btn-sm gap-1 rounded-full">
            <span class="material-icons text-xs">question_answer</span>
            {{ __('FAQ') }}
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection