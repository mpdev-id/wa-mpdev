@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Group Chats')])
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
  
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Group Chats') }}</h1>
    <p class="text-base-content/60">{{ __('Manage group conversations') }}</p>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="stats-card animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Groups') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($total ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-primary to-secondary">
          <span class="material-icons">groups</span>
        </div>
      </div>
    </div>

    <div class="stats-card animate-slide-up" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Active') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($active ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-success to-success/80">
          <span class="material-icons">check_circle</span>
        </div>
      </div>
    </div>

    <div class="stats-card animate-slide-up" style="animation-delay: 0.2s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Members') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($members ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-info to-info/80">
          <span class="material-icons">people</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Groups Table -->
  @if(count($groups ?? []) > 0)
  <div class="card bg-white shadow-xl">
    <div class="card-body p-0">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th class="bg-base-200">{{ __('Group Name') }}</th>
              <th class="bg-base-200">{{ __('Members') }}</th>
              <th class="bg-base-200">{{ __('Last Activity') }}</th>
              <th class="bg-base-200">{{ __('Status') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($groups as $group)
            <tr class="hover">
              <td>
                <div class="flex items-center gap-3">
                  <div class="avatar placeholder">
                    <div class="bg-secondary text-secondary-content rounded-full w-10">
                      <span class="material-icons text-sm">group</span>
                    </div>
                  </div>
                  <div class="font-semibold">{{ $group->name }}</div>
                </div>
              </td>
              <td>{{ $group->members_count }} {{ __('members') }}</td>
              <td>{{ $group->last_activity ?? __('Never') }}</td>
              <td>
                <span class="badge badge-success">{{ __('Active') }}</span>
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
      <span class="material-icons text-6xl text-base-content/40">group</span>
    </div>
    <h3 class="text-xl font-semibold text-base-content mb-2">{{ __('No Groups Yet') }}</h3>
    <p class="text-base-content/60 text-center max-w-md">
      {{ __('Group conversations will appear here') }}
    </p>
  </div>
  @endif
</div>
@endsection