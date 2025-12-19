@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Ticket Details')])
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <div class="flex items-center gap-3 mb-2">
        <h1 class="text-2xl font-bold text-base-content">{{ __('Ticket') }} #{{ $support->id }}</h1>
        @if($support->status == 1)
          <span class="badge badge-warning">{{ __('Open') }}</span>
        @elseif($support->status == 2)
          <span class="badge badge-info">{{ __('In Progress') }}</span>
        @else
          <span class="badge badge-success">{{ __('Resolved') }}</span>
        @endif
      </div>
      <p class="text-base-content/60">{{ $support->subject }}</p>
    </div>
    <a href="{{ route('user.support.index') }}" class="btn btn-outline gap-2">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back') }}
    </a>
  </div>

  <!-- Ticket Info -->
  <div class="card bg-white shadow-xl mb-6 animate-slide-up">
    <div class="card-body">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <p class="text-sm text-gray-600">{{ __('Category') }}</p>
          <p class="font-semibold">{{ $support->category ?? __('General') }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-600">{{ __('Priority') }}</p>
          <p class="font-semibold">{{ $support->priority ?? __('Medium') }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-600">{{ __('Created') }}</p>
          <p class="font-semibold">{{ $support->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-600">{{ __('Last Updated') }}</p>
          <p class="font-semibold">{{ $support->updated_at->diffForHumans() }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Conversation Thread -->
  <div class="card bg-white shadow-xl mb-6 animate-slide-up" style="animation-delay: 0.1s">
    <div class="card-body">
      <h2 class="card-title mb-4 flex items-center gap-2">
        <span class="material-icons">forum</span>
        {{ __('Conversation') }}
      </h2>
      
      <div class="space-y-4">
        @forelse($support->conversations ?? [] as $reply)
        <div class="flex gap-4 {{ $reply->is_admin ? 'flex-row-reverse' : '' }}">
          <!-- Avatar -->
          <div class="avatar placeholder">
            <div class="w-10 h-10 rounded-full {{ $reply->is_admin ? 'bg-secondary' : 'bg-primary' }}">
              <span class="material-icons text-sm text-white">
                {{ $reply->is_admin ? 'support_agent' : 'person' }}
              </span>
            </div>
          </div>
          
          <!-- Message -->
          <div class="flex-1 max-w-2xl">
            <div class="flex items-center gap-2 mb-1">
              <span class="font-semibold text-sm">
                {{ $reply->is_admin ? __('Support Agent') : __('You') }}
              </span>
              <span class="text-xs text-gray-500">
                {{ $reply->created_at->format('d M Y, h:i A') }}
                @if($reply->is_admin == 0 && $reply->seen == 1)
                  <span class="text-success">({{ __('Seen') }})</span>
                @endif
              </span>
            </div>
            <div class="p-4 rounded-lg {{ $reply->is_admin ? 'bg-secondary/10' : 'bg-base-200' }}">
              <p class="text-sm">{{ $reply->comment }}</p>
            </div>
          </div>
        </div>
        @empty
        <div class="text-center py-8 text-gray-500">
          {{ __('No messages yet') }}
        </div>
        @endforelse
      </div>
    </div>
  </div>

  <!-- Reply Form -->
  @if($support->status == 1)
  <div class="card bg-white shadow-xl animate-slide-up" style="animation-delay: 0.2s">
    <div class="card-body">
      <h3 class="card-title text-lg mb-4">{{ __('Add Reply') }}</h3>
      
      <form method="POST" class="ajaxform_instant_reload space-y-4" action="{{ route('user.support.update', $support->id) }}">
        @csrf
        @method('PUT')
        
        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Your Message') }}</span>
          </label>
          <textarea 
            name="message" 
            class="textarea textarea-bordered h-32" 
            maxlength="1000"
            placeholder="{{ __('Type your reply...') }}"
            required
          ></textarea>
          <label class="label">
            <span class="label-text-alt">{{ __('Maximum 1000 characters') }}</span>
          </label>
        </div>

        <div class="flex justify-end gap-3">
          <button type="submit" class="btn btn-primary gap-2">
            <span class="material-icons text-sm">send</span>
            {{ __('Send Reply') }}
          </button>
        </div>
      </form>
    </div>
  </div>
  @else
  <div class="card bg-base-100 shadow-md animate-slide-up" style="animation-delay: 0.2s">
    <div class="card-body">
      <div class="alert alert-info">
        <span class="material-icons">info</span>
        <span>{{ __('This ticket is closed. You cannot add more replies.') }}</span>
      </div>
    </div>
  </div>
  @endif
</div>
@endsection