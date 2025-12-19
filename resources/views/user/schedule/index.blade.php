@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Schedule Messages')])
@endsection

@section('content')

<div class="max-w-7xl mx-auto">
  
  <!-- Page Header -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Schedule Messages') }}</h1>
      <p class="text-base-content/60">{{ __('Manage your scheduled WhatsApp messages') }}</p>
    </div>
    <a href="{{ route('user.schedule-message.create') }}" class="btn btn-primary gap-2">
      <span class="material-icons text-sm">schedule_send</span>
      {{ __('Create Schedule') }}
    </a>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total -->
    <div class="stats-card animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Schedules') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalSchedule ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-primary to-secondary">
          <span class="material-icons">event</span>
        </div>
      </div>
    </div>

    <!-- Pending -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Pending') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($pendingSchedule ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-warning to-warning/80">
          <span class="material-icons">pending</span>
        </div>
      </div>
    </div>

    <!-- Executed -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.2s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Executed') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($deliveredSchedule ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-success to-success/80">
          <span class="material-icons">check_circle</span>
        </div>
      </div>
    </div>

    <!-- Failed -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.3s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Failed') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($failedSchedule ?? 0) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-error to-error/80">
          <span class="material-icons">error</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Subscription Alert -->
  @if(getUserPlanData('schedule_message') == false)
  <div class="alert alert-warning mb-6">
    <span class="material-icons">info</span>
    <div>
      <h3 class="font-bold">{{ __('Feature Not Available') }}</h3>
      <div class="text-xs">{{ __('Schedule Message feature is not available in your subscription plan') }}</div>
    </div>
    <a href="{{ url('/pricing') }}" class="btn btn-sm btn-primary">{{ __('Upgrade Plan') }}</a>
  </div>
  @endif

  <!-- Schedules Table -->
  @if(count($posts) > 0)
  <div class="card bg-white shadow-xl">
    <div class="card-body p-0">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th class="bg-base-200">
                <div class="flex items-center gap-2">
                  <span class="material-icons text-sm">phone</span>
                  {{ __('Send From') }}
                </div>
              </th>
              <th class="bg-base-200">
                <div class="flex items-center gap-2">
                  <span class="material-icons text-sm">title</span>
                  {{ __('Title') }}
                </div>
              </th>
              <th class="bg-base-200">{{ __('Type') }}</th>
              <th class="bg-base-200">{{ __('Status') }}</th>
              <th class="bg-base-200">
                <div class="flex items-center gap-2">
                  <span class="material-icons text-sm">schedule</span>
                  {{ __('Delivery Date') }}
                </div>
              </th>
              <th class="bg-base-200 text-right">{{ __('Actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($posts as $post)
            <tr class="hover">
              <td>
                <div class="flex items-center gap-2">
                  <span class="material-icons text-base-content/40">phone_android</span>
                  <span class="font-medium">{{ $post->device->phone ?? '' }}</span>
                </div>
              </td>
              <td>
                <div class="max-w-md">
                  <div class="font-medium text-gray-900">{{ Str::limit($post->title ?? '', 50) }}</div>
                  @if($post->body)
                  <div class="text-xs text-gray-500 truncate">{{ Str::limit($post->body, 60) }}</div>
                  @endif
                </div>
              </td>
              <td>
                <span class="badge badge-outline">
                  {{ $post->body != null ? __('Plain Text') : __('Template') }}
                </span>
              </td>
              <td>
                @php
                  $statusClass = match($post->status) {
                    'delivered', 'executed' => 'badge-success',
                    'pending' => 'badge-warning',
                    'failed' => 'badge-error',
                    default => 'badge-info'
                  };
                @endphp
                <span class="badge {{ $statusClass }}">{{ ucfirst($post->status) }}</span>
              </td>
              <td>
                <div class="flex items-center gap-2 text-sm">
                  <span class="material-icons text-xs text-base-content/40">schedule</span>
                  {{ Carbon\Carbon::parse($post->schedule_at)->copy()->tz($post->zone)->format('M j, Y g:i A') }}
                </div>
              </td>
              <td>
                <div class="flex justify-end">
                  <div class="dropdown dropdown-end">
                    <button tabindex="0" class="btn btn-ghost btn-sm btn-circle">
                      <span class="material-icons text-sm">more_vert</span>
                    </button>
                    <ul tabindex="0" class="dropdown-content z-50 menu p-2 shadow-xl bg-white rounded-xl w-48 border border-base-200">
                      <li>
                        <a href="{{ route('user.schedule-message.show', $post->id) }}" class="flex items-center gap-2">
                          <span class="material-icons text-sm">visibility</span>
                          {{ __('View Log') }}
                        </a>
                      </li>
                      @if($post->status == 'pending')
                      <div class="divider my-0"></div>
                      <li>
                        <a href="javascript:void(0)" 
                           class="delete-confirm text-red-600 hover:bg-red-50 flex items-center gap-2" 
                           data-action="{{ route('user.schedule-message.destroy', $post->id) }}">
                          <span class="material-icons text-sm">delete</span>
                          {{ __('Remove') }}
                        </a>
                      </li>
                      @endif
                    </ul>
                  </div>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="p-4 border-t border-base-200">
        <div class="flex justify-center">
          {{ $posts->links('vendor.pagination.bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>
  @else
  <!-- Empty State -->
  <div class="flex flex-col items-center justify-center py-16 px-4 animate-fade-in">
    <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center mb-4">
      <span class="material-icons text-6xl text-base-content/40">schedule_send</span>
    </div>
    <h3 class="text-xl font-semibold text-base-content mb-2">{{ __('No Schedules Found') }}</h3>
    <p class="text-base-content/60 mb-6 text-center max-w-md">
      {{ __('Create your first scheduled message to send WhatsApp messages at specific times') }}
    </p>
    <a href="{{ route('user.schedule-message.create') }}" class="btn btn-primary gap-2">
      <span class="material-icons text-sm">add</span>
      {{ __('Create Schedule') }}
    </a>
  </div>
  @endif

</div>

<input type="hidden" id="base_url" value="{{ url('/') }}">
<input type="hidden" id="bulk_message_link" value="{{ route('user.bulk-message.store') }}">
@csrf

@endsection

@push('js')
<script src="{{ asset('assets/js/pages/bulk/jquery.csv.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/bulk/bulkmessage.js') }}"></script>
@endpush