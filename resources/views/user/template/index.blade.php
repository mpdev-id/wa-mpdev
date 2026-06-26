@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Message Templates'),'buttons'=>[
	[
		'name'=>'<i class="material-icons text-sm">add</i>&nbsp'.__('Create Template'),
		'url'=> route('user.template.create'),
	]
]])
@endsection

@section('content')
<div x-data="{ showModal: false, modalTitle: '', modalId: '' }">
  
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Total Templates -->
    <div class="stats-card animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Templates') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ $limit }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-blue-500 to-blue-600">
          <span class="material-icons">description</span>
        </div>
      </div>
    </div>

    <!-- Active Templates -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Active Templates') }}</p>
          <h3 class="text-3xl font-bold text-green-600">{{ $active_templates }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-green-500 to-green-600">
          <span class="material-icons">check_circle</span>
        </div>
      </div>
    </div>

    <!-- Inactive Templates -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.2s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Inactive Templates') }}</p>
          <h3 class="text-3xl font-bold text-orange-600">{{ $inactive_templates }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-orange-500 to-orange-600">
          <span class="material-icons">error</span>
        </div>
      </div>
    </div>
  </div>

  @if(count($templates ?? []) == 0)
  <!-- Empty State -->
  <div class="flex flex-col items-center justify-center py-16 px-4 animate-fade-in">
    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
      <span class="material-icons text-6xl text-gray-400">description</span>
    </div>
    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('No Templates Yet') }}</h3>
    <p class="text-gray-600 mb-6 text-center max-w-md">{{ __('Create your first message template to save time and send messages faster') }}</p>
    <a href="{{ route('user.template.create') }}" class="btn btn-primary gap-2 rounded-full">
      <span class="material-icons text-sm">add</span>
      {{ __('Create Template') }}
    </a>
  </div>
  @else
  <!-- Templates Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @foreach($templates ?? [] as $template)
    <div class="card bg-white shadow-lg hover:shadow-xl transition-all duration-300 animate-fade-in group">
      <div class="card-body p-5">
        <div class="flex justify-between items-start mb-4">
          <div>
            <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $template->title }}</h3>
            <div class="flex items-center gap-2">
              <span class="badge badge-sm {{ $template->status ? 'badge-success text-white' : 'badge-warning' }}">
                {{ $template->status ? __('Active') : __('Inactive') }}
              </span>
              <span class="badge badge-sm badge-ghost capitalize">{{ str_replace(['text-with-', '-'], ['',' '], $template->type) }}</span>
            </div>
          </div>
          
          <!-- Actions -->
          <div class="flex items-center gap-1">
            <a href="{{ route('user.template.edit',$template->id) }}" 
               class="btn btn-sm btn-ghost text-primary"
               title="{{ __('Edit Template') }}">
              <span class="material-icons text-sm">edit</span>
            </a>
            
            <a href="javascript:void(0)" 
               @click="showModal = true; modalTitle = '{{ $template->title }}'; modalId = '{{ $template->uuid }}'"
               class="btn btn-sm btn-ghost text-info"
               title="{{ __('View Template ID') }}">
              <span class="material-icons text-sm">info</span>
            </a>
            
            <a href="javascript:void(0)" 
               class="delete-confirm btn btn-sm btn-ghost text-red-600 rounded-full" 
               data-action="{{ route('user.template.destroy',$template->id) }}"
               title="{{ __('Remove') }}">
              <span class="material-icons text-sm">delete</span>
            </a>
          </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-3 mb-4 h-24 overflow-hidden relative">
           <p class="text-gray-600 text-sm">
             {{ $template->body['text'] ?? ($template->body['caption'] ?? __('No text content')) }}
           </p>
           <div class="absolute bottom-0 left-0 right-0 h-8 bg-gradient-to-t from-gray-50 to-transparent"></div>
        </div>

        <div class="flex justify-end">
           <a href="{{ route('user.bulk-message.create') }}?template={{ $template->id }}" class="btn btn-primary gap-2 w-full rounded-full">
             <span class="material-icons text-sm">send</span>
             {{ __('Use Template') }}
           </a>
        </div>
      </div>
    </div>
    @endforeach

  </div>

  <!-- Pagination -->
  <div class="mt-6 flex justify-center">
    {{ $templates->links('vendor.pagination.bootstrap-4') }}
  </div>
  @endif

  <!-- Modal for Template ID -->
  <div x-show="showModal" 
       x-cloak
       @click.self="showModal = false"
       class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
       style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-slide-down">
      <!-- Modal Header -->
      <div class="flex items-start justify-between mb-4">
        <h3 class="text-xl font-bold text-gray-900" x-text="modalTitle"></h3>
        <button @click="showModal = false" class="btn btn-ghost btn-sm btn-circle rounded-full">
          <span class="material-icons">close</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="mb-6">
        <label class="label">
          <span class="label-text font-medium">{{ __('Template ID') }}</span>
        </label>
        <input type="text" 
               x-model="modalId" 
               class="input input-bordered w-full" 
               readonly 
               @click="$el.select()" />
        <p class="text-sm text-gray-500 mt-2">{{ __('Click to copy') }}</p>
      </div>

      <!-- Modal Footer -->
      <div class="flex justify-end gap-2">
        <button @click="showModal = false" class="btn btn-outline rounded-full">
          {{ __('Close') }}
        </button>
        <button @click="navigator.clipboard.writeText(modalId); $dispatch('toast', {message: 'Template ID copied!', type: 'success'})" 
                class="btn btn-primary gap-2 rounded-full">
          <span class="material-icons text-sm">content_copy</span>
          {{ __('Copy ID') }}
        </button>
      </div>
    </div>
  </div>

</div>

@endsection

@push('js')
<script src="{{ asset('assets/js/pages/user/template-index.js') }}"></script>
@endpush