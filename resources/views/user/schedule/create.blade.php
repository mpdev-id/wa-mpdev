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
    <a href="{{ route('user.schedule.index') }}" class="btn btn-outline gap-2">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back') }}
    </a>
  </div>

  <div class="card bg-white shadow-xl">
    <div class="card-body">
      <form method="POST" class="space-y-4" action="{{ route('user.schedule.store') }}">
        @csrf
        
        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Device') }}</span>
          </label>
          <select name="device_id" class="select select-bordered" required>
            @foreach($devices ?? [] as $device)
              <option value="{{ $device->id }}">{{ $device->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Message') }}</span>
          </label>
          <textarea name="message" class="textarea textarea-bordered h-32" required></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t">
          <a href="{{ route('user.schedule.index') }}" class="btn btn-outline">{{ __('Cancel') }}</a>
          <button type="submit" class="btn btn-primary gap-2">
            <span class="material-icons text-sm">schedule_send</span>
            {{ __('Schedule') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection