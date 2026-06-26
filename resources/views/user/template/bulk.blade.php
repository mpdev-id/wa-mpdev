@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Bulk Import Templates')])
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Bulk Import') }}</h1>
      <p class="text-base-content/60">{{ __('Import multiple templates at once') }}</p>
    </div>
    <a href="{{ route('user.template.index') }}" class="btn btn-outline gap-2 rounded-full">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back') }}
    </a>
  </div>

  <div class="card bg-white shadow-xl animate-slide-up">
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data" class="space-y-6" action="{{ route('user.template.bulkstore') }}">
        @csrf
        
        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium flex items-center gap-2">
              <span class="material-icons text-sm">upload_file</span>
              {{ __('Upload CSV/Excel File') }}
            </span>
          </label>
          <input 
            type="file" 
            name="file" 
            class="file-input file-input-bordered w-full" 
            accept=".csv,.xlsx,.xls" 
            required 
          />
          <label class="label">
            <span class="label-text-alt">{{ __('Supported formats: CSV, XLSX, XLS') }}</span>
          </label>
        </div>

        <div class="alert alert-info">
          <span class="material-icons">info</span>
          <div class="text-sm">
            <strong>{{ __('File Format:') }}</strong>
            <div class="mt-2 p-3 bg-base-200 rounded font-mono text-xs">
              <p>title,category,content</p>
              <p>Welcome Message,general,Hello {1}! Welcome to our service.</p>
              <p>Order Confirm,marketing,Thank you {1} for order #{2}</p>
            </div>
          </div>
        </div>

        <div class="alert alert-warning">
          <span class="material-icons">warning</span>
          <div class="text-sm">
            <strong>{{ __('Important:') }}</strong>
            <ul class="list-disc list-inside mt-2 space-y-1">
              <li>{{ __('First row must be column headers') }}</li>
              <li>{{ __('Maximum 1000 templates per import') }}</li>
              <li>{{ __('Invalid rows will be skipped') }}</li>
            </ul>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-base-200">
          <a href="{{ route('user.template.index') }}" class="btn btn-outline rounded-full">
            {{ __('Cancel') }}
          </a>
          <button type="submit" class="btn btn-primary submit-btn gap-2 rounded-full">
            <span class="material-icons text-sm">cloud_upload</span>
            {{ __('Import Templates') }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Download Sample -->
  <div class="card bg-base-100 shadow-md mt-6 animate-slide-up" style="animation-delay: 0.1s">
    <div class="card-body">
      <h3 class="card-title text-base flex items-center gap-2">
        <span class="material-icons text-sm">download</span>
        {{ __('Download Sample File') }}
      </h3>
      <p class="text-sm text-base-content/70 mb-4">
        {{ __('Download a sample file to see the correct format') }}
      </p>
      <a href="#" class="btn btn-outline btn-sm gap-2 rounded-full">
        <span class="material-icons text-xs">download</span>
        {{ __('Download CSV Sample') }}
      </a>
    </div>
  </div>
</div>
@endsection