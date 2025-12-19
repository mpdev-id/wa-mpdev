@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Chats')])
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
  
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Chat List') }}</h1>
    <p class="text-base-content/60">{{ __('All your conversations') }}</p>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="stats-card animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Chats') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($total ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-primary to-secondary">
          <span class="material-icons">chat</span>
        </div>
      </div>
    </div>

    <div class="stats-card animate-slide-up" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Unread') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($unread ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-warning to-warning/80">
          <span class="material-icons">mark_chat_unread</span>
        </div>
      </div>
    </div>

    <div class="stats-card animate-slide-up" style="animation-delay: 0.2s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Today') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($today ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-success to-success/80">
          <span class="material-icons">today</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Chats Table -->
  @if(count($chats ?? []) > 0)
  <div class="card bg-white shadow-xl">
    <div class="card-body p-0">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th class="bg-base-200">{{ __('Contact') }}</th>
              <th class="bg-base-200">{{ __('Last Message') }}</th>
              <th class="bg-base-200">{{ __('Time') }}</th>
              <th class="bg-base-200">{{ __('Status') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($chats as $chat)
            <tr class="hover">
              <td>
                <div class="flex items-center gap-3">
                  <div class="avatar placeholder">
                    <div class="bg-primary text-primary-content rounded-full w-10">
                      <span>{{ substr($chat->contact, 0, 2) }}</span>
                    </div>
                  </div>
                  <div>
                    <div class="font-semibold">{{ $chat->contact }}</div>
                    <div class="text-sm text-gray-500">{{ $chat->phone }}</div>
                  </div>
                </div>
              </td>
              <td>{{ Str::limit($chat->last_message ?? '', 50) }}</td>
              <td>{{ $chat->last_message_time ?? '' }}</td>
              <td>
                @if($chat->unread > 0)
                  <span class="badge badge-warning">{{ $chat->unread }} unread</span>
                @else
                  <span class="badge badge-success">Read</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @else
  <!-- Empty State -->
  <div class="flex flex-col items-center justify-center py-16 animate-fade-in">
    <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center mb-4">
      <span class="material-icons text-6xl text-base-content/40">chat_bubble_outline</span>
    </div>
    <h3 class="text-xl font-semibold text-base-content mb-2">{{ __('No Chats Yet') }}</h3>
    <p class="text-base-content/60 text-center max-w-md">
      {{ __('Your conversations will appear here') }}
    </p>
  </div>
  @endif
</div>
@endsection