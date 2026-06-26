@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Support Tickets')])
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Support Tickets') }}</h1>
      <p class="text-base-content/60">{{ __('Get help from our support team') }}</p>
    </div>
    <a href="{{ route('user.support.create') }}" class="btn btn-primary gap-2 rounded-full">
      <span class="material-icons text-sm">add</span>
      {{ __('New Ticket') }}
    </a>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="stats-card animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Tickets') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($total ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-primary to-secondary">
          <span class="material-icons">support_agent</span>
        </div>
      </div>
    </div>

    <div class="stats-card animate-slide-up" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Open') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($open ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-warning to-warning/80">
          <span class="material-icons">pending</span>
        </div>
      </div>
    </div>

    <div class="stats-card animate-slide-up" style="animation-delay: 0.2s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Resolved') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($resolved ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-success to-success/80">
          <span class="material-icons">check_circle</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Tickets Table -->
  @if(count($tickets ?? []) > 0)
  <div class="card bg-white shadow-xl">
    <div class="card-body p-0">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th class="bg-base-200">#</th>
              <th class="bg-base-200">{{ __('Subject') }}</th>
              <th class="bg-base-200">{{ __('Category') }}</th>
              <th class="bg-base-200">{{ __('Status') }}</th>
              <th class="bg-base-200">{{ __('Created') }}</th>
              <th class="bg-base-200 text-right">{{ __('Actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tickets as $ticket)
            <tr class="hover">
              <td class="font-mono">#{{ $ticket->id }}</td>
              <td class="font-semibold">{{ $ticket->subject }}</td>
              <td>
                <span class="badge badge-outline">{{ $ticket->category }}</span>
              </td>
              <td>
                @if($ticket->status == 'open')
                  <span class="badge badge-warning">{{ __('Open') }}</span>
                @elseif($ticket->status == 'in_progress')
                  <span class="badge badge-info">{{ __('In Progress') }}</span>
                @else
                  <span class="badge badge-success">{{ __('Resolved') }}</span>
                @endif
              </td>
              <td>{{ $ticket->created_at->format('d M Y') }}</td>
              <td class="text-right">
                <a href="{{ route('user.support.show', $ticket->id) }}" class="btn btn-ghost btn-sm gap-1">
                  <span class="material-icons text-xs">visibility</span>
                  {{ __('View') }}
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if(method_exists($tickets, 'links'))
      <div class="p-4 border-t border-base-200">
        <div class="flex justify-center">
          {{ $tickets->links() }}
        </div>
      </div>
      @endif
    </div>
  </div>
  @else
  <!-- Empty State -->
  <div class="flex flex-col items-center justify-center py-16 animate-fade-in">
    <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center mb-4">
      <span class="material-icons text-6xl text-base-content/40">support_agent</span>
    </div>
    <h3 class="text-xl font-semibold text-base-content mb-2">{{ __('No Support Tickets') }}</h3>
    <p class="text-base-content/60 mb-6 text-center max-w-md">
      {{ __('You haven\'t created any support tickets yet') }}
    </p>
    <a href="{{ route('user.support.create') }}" class="btn btn-primary gap-2 rounded-full">
      <span class="material-icons text-sm">add</span>
      {{ __('Create First Ticket') }}
    </a>
  </div>
  @endif
</div>
@endsection
