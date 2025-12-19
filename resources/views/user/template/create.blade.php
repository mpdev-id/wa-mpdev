@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Create Template')])
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Create Template') }}</h1>
      <p class="text-base-content/60">{{ __('Create reusable message template') }}</p>
    </div>
    <a href="{{ route('user.template.index') }}" class="btn btn-outline gap-2">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back') }}
    </a>
  </div>

  <div class="card bg-white shadow-xl animate-slide-up">
    <div class="card-body">
      <form method="POST" class="ajaxform_instant_reload space-y-4" action="{{ route('user.template.store') }}">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Template Title') }}</span>
            </label>
            <input 
              type="text" 
              name="title" 
              class="input input-bordered w-full" 
              placeholder="{{ __('Welcome Message') }}"
              required 
            />
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Category') }}</span>
            </label>
            <select name="category" class="select select-bordered w-full">
              <option value="general">{{ __('General') }}</option>
              <option value="marketing">{{ __('Marketing') }}</option>
              <option value="support">{{ __('Support') }}</option>
              <option value="notification">{{ __('Notification') }}</option>
            </select>
          </div>
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Template Content') }}</span>
          </label>
          <textarea 
            name="content" 
            class="textarea textarea-bordered h-32" 
            placeholder="{{ __('Hello {1}, welcome to {2}!') }}"
            required
          ></textarea>
          <label class="label">
            <span class="label-text-alt flex items-center gap-1">
              <span class="material-icons text-xs">info</span>
              {{ __('Use {1}, {2}, {3} for variables that can be replaced') }}
            </span>
          </label>
        </div>

        <div class="alert alert-info">
          <span class="material-icons">tips_and_updates</span>
          <div class="text-sm">
            <strong>{{ __('Tips:') }}</strong>
            <ul class="list-disc list-inside mt-2 space-y-1">
              <li>{{ __('Use variables like {1}, {2} for dynamic content') }}</li>
              <li>{{ __('Keep messages clear and concise') }}</li>
              <li>{{ __('Test templates before mass sending') }}</li>
            </ul>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-base-200">
          <a href="{{ route('user.template.index') }}" class="btn btn-outline">
            {{ __('Cancel') }}
          </a>
          <button type="submit" class="btn btn-primary submit-btn gap-2">
            <span class="material-icons text-sm">save</span>
            {{ __('Create Template') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection