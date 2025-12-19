@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Edit Template')])
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Edit Template') }}</h1>
      <p class="text-base-content/60">{{ __('Update template') }}</p>
    </div>
    <a href="{{ route('user.template.index') }}" class="btn btn-outline gap-2">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back') }}
    </a>
  </div>

  <div class="card bg-white shadow-xl animate-slide-up">
    <div class="card-body">
      <form method="POST" class="ajaxform_instant_reload space-y-4" action="{{ route('user.template.update', $template->id) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Template Title') }}</span>
            </label>
            <input 
              type="text" 
              name="title" 
              class="input input-bordered w-full" 
              value="{{ $template->title }}" 
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
            required
          >{{ $template->body }}</textarea>
          <label class="label">
            <span class="label-text-alt">
              {{ __('Variables: {1}, {2}, {3}') }}
            </span>
          </label>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-base-200">
          <a href="{{ route('user.template.index') }}" class="btn btn-outline">
            {{ __('Cancel') }}
          </a>
          <button type="submit" class="btn btn-primary submit-btn gap-2">
            <span class="material-icons text-sm">save</span>
            {{ __('Update Template') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection