@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Edit Schedule')])
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold">{{ __('Edit Schedule') }}</h1>
      <p class="text-base-content/60">{{ __('Update scheduled message') }}</p>
    </div>
    <a href="{{ route('user.schedule-message.index') }}" class="btn btn-outline gap-2">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back') }}
    </a>
  </div>

  <div class="card bg-white shadow-xl">
    <div class="card-body">
      <form method="POST" class="space-y-4" action="{{ route('user.schedule-message.update', $schedule->id) }}">
        @csrf
        @method('PUT')
        
        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Message') }}</span>
          </label>
          <textarea name="message" class="textarea textarea-bordered h-32" required>{{ $schedule->message }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Date') }}</span>
            </label>
            <input type="date" name="date" class="input input-bordered" value="{{ $schedule->date }}" required />
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Time') }}</span>
            </label>
            <input type="time" name="time" class="input input-bordered" value="{{ $schedule->time }}" required />
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t">
          <a href="{{ route('user.schedule-message.index') }}" class="btn btn-outline">{{ __('Cancel') }}</a>
          <button type="submit" class="btn btn-primary gap-2">
            <span class="material-icons text-sm">save</span>
            {{ __('Update') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
