@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Schedule Details')])
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold">{{ __('Schedule Details') }}</h1>
      <p class="text-base-content/60">{{ __('Scheduled message information') }}</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('user.schedule-message.index') }}" class="btn btn-outline gap-2 rounded-full">
        <span class="material-icons text-sm">arrow_back</span>
        {{ __('Back') }}
      </a>
    </div>
  </div>

  <div class="card bg-white shadow-xl">
    <div class="card-body">
      <h2 class="card-title">{{ __('Information') }}</h2>
      <div class="divider"></div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <p class="text-sm text-gray-600">{{ __('Title') }}</p>
          <p class="font-semibold">{{ $info->title }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-600">{{ __('Message') }}</p>
          <p class="font-semibold">{{ $info->body ?? __('Template Message') }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-600">{{ __('Scheduled For') }}</p>
          <p class="font-semibold">{{ \Carbon\Carbon::parse($info->schedule_at)->format('d M Y H:i') }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-600">{{ __('Status') }}</p>
          <span class="badge badge-info">{{ ucfirst($info->status) }}</span>
        </div>
        <div>
          <p class="text-sm text-gray-600">{{ __('Created') }}</p>
          <p class="font-semibold">{{ $info->created_at->format('d M Y') }}</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection