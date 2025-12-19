@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Automatic Replies')])
@endsection

@section('content')

<div class="max-w-7xl mx-auto" x-data="{ showCreateModal: false, showEditModal: false, editData: {} }">
  
  <!-- Page Header -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Automatic Replies') }}</h1>
      <p class="text-base-content/60">{{ __('Set up chatbot auto-reply rules for incoming messages') }}</p>
    </div>
    <button @click="showCreateModal = true" class="btn btn-primary gap-2">
      <span class="material-icons text-sm">add</span>
      {{ __('Create Reply') }}
    </button>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Total Replies -->
    <div class="stats-card animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Replies') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($total_replies) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-primary to-secondary">
          <span class="material-icons">smart_toy</span>
        </div>
      </div>
    </div>

    <!-- Template Replies -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Template Replies') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($template_replies) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-info to-info/80">
          <span class="material-icons">description</span>
        </div>
      </div>
    </div>

    <!-- Text Replies -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.2s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Text Replies') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ number_format($text_replies) }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-success to-success/80">
          <span class="material-icons">text_fields</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Subscription Alert -->
  @if(getUserPlanData('chatbot') == false)
  <div class="alert alert-warning mb-6">
    <span class="material-icons">info</span>
    <div>
      <h3 class="font-bold">{{ __('Feature Not Available') }}</h3>
      <div class="text-xs">{{ __('Chatbot features is not available in your subscription plan') }}</div>
    </div>
    <a href="{{ url('/pricing') }}" class="btn btn-sm btn-primary">{{ __('Upgrade Plan') }}</a>
  </div>
  @endif

  <!-- Replies Table / Empty State -->
  @if(count($replies ?? []) == 0)
  <!-- Empty State -->
  <div class="flex flex-col items-center justify-center py-16 px-4 animate-fade-in">
    <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center mb-4">
      <span class="material-icons text-6xl text-base-content/40">smart_toy</span>
    </div>
    <h3 class="text-xl font-semibold text-base-content mb-2">{{ __('No Auto Replies Yet') }}</h3>
    <p class="text-base-content/60 mb-6 text-center max-w-md">
      {{ __('Create your first auto-reply rule to automatically respond to messages') }}
    </p>
    <button @click="showCreateModal = true" class="btn btn-primary gap-2">
      <span class="material-icons text-sm">add</span>
      {{ __('Create Reply') }}
    </button>
  </div>
  @else
  <!-- Replies Table -->
  <div class="card bg-white shadow-xl">
    <div class="card-body p-0">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th class="bg-base-200">
                <div class="flex items-center gap-2">
                  <span class="material-icons text-sm">vpn_key</span>
                  {{ __('Keyword') }}
                </div>
              </th>
              <th class="bg-base-200">
                <div class="flex items-center gap-2">
                  <span class="material-icons text-sm">phone_android</span>
                  {{ __('Device') }}
                </div>
              </th>
              <th class="bg-base-200">{{ __('Reply Type') }}</th>
              <th class="bg-base-200">{{ __('Match Type') }}</th>
              <th class="bg-base-200 text-right">{{ __('Actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($replies ?? [] as $reply)
            <tr class="hover">
              <td>
                <div class="flex items-center gap-2">
                  <span class="material-icons text-primary">label</span>
                  <span class="font-medium">{{ $reply->keyword }}</span>
                </div>
              </td>
              <td>
                <span class="badge badge-outline">{{ $reply->device->phone ?? '' }}</span>
              </td>
              <td>
                <span class="badge {{ $reply->reply_type == 'template' ? 'badge-info' : 'badge-success' }}">
                  {{ ucfirst($reply->reply_type) }}
                </span>
              </td>
              <td>
                <span class="badge badge-outline">
                  {{ $reply->match_type == 'equal' ? __('Hanya Kata') : __('Terdapat Kata') }}
                </span>
              </td>
              <td>
                <div class="flex justify-end">
                  <div class="dropdown dropdown-end">
                    <button tabindex="0" class="btn btn-ghost btn-sm btn-circle">
                      <span class="material-icons text-sm">more_vert</span>
                    </button>
                    <ul tabindex="0" class="dropdown-content z-50 menu p-2 shadow-xl bg-white rounded-xl w-48 border border-base-200">
                      <li>
                        <a href="javascript:void(0)" 
                           @click="editData = {
                             action: '{{ route('user.chatbot.update', $reply->id) }}',
                             keyword: '{{ $reply->keyword }}',
                             device: '{{ $reply->device_id }}',
                             reply_type: '{{ $reply->reply_type }}',
                             match_type: '{{ $reply->match_type }}',
                             reply: '{{ $reply->reply }}',
                             template_id: '{{ $reply->template_id }}'
                           }; showEditModal = true"
                           class="flex items-center gap-2">
                          <span class="material-icons text-sm">edit</span>
                          {{ __('Edit') }}
                        </a>
                      </li>
                      <div class="divider my-0"></div>
                      <li>
                        <a href="javascript:void(0)" 
                           class="delete-confirm text-red-600 hover:bg-red-50 flex items-center gap-2" 
                           data-action="{{ route('user.chatbot.destroy', $reply->id) }}">
                          <span class="material-icons text-sm">delete</span>
                          {{ __('Delete') }}
                        </a>
                      </li>
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
          {{ $replies->links('vendor.pagination.bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>
  @endif

  <!-- Create Modal -->
  <div x-show="showCreateModal" x-cloak class="modal modal-open" @click.self="showCreateModal = false">
    <div class="modal-box max-w-2xl">
      <h3 class="font-bold text-lg flex items-center gap-2 mb-4">
        <span class="material-icons">add_circle</span>
        {{ __('Create Auto Reply') }}
      </h3>
      
      <form method="POST" action="{{ route('user.chatbot.store') }}" class="ajaxform_instant_reload space-y-4">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Keyword') }}</span>
            </label>
            <input type="text" name="keyword" class="input input-bordered w-full" maxlength="50" required />
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Device') }}</span>
            </label>
            <select class="select select-bordered w-full" name="device" required>
              @foreach($devices as $device)
              <option value="{{ $device->id }}">{{ $device->name }} - {{ $device->phone }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Reply Type') }}</span>
            </label>
            <select class="select select-bordered w-full reply_type" name="reply_type" required>
              <option value="text">{{ __('Plain Text') }}</option>
              <option value="template">{{ __('Template') }}</option>
            </select>
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Match Type') }}</span>
            </label>
            <select class="select select-bordered w-full" name="match_type" required>
              <option value="equal">{{ __('Hanya Kata') }}</option>
              <option value="like">{{ __('Terdapat Kata') }}</option>
            </select>
          </div>
        </div>

        <div class="form-control text-area">
          <label class="label">
            <span class="label-text font-medium">{{ __('Reply Message') }}</span>
          </label>
          <textarea class="textarea textarea-bordered h-32" name="reply" maxlength="1000"></textarea>
        </div>

        <div class="form-control templates hidden">
          <label class="label">
            <span class="label-text font-medium">{{ __('Select Template') }}</span>
          </label>
          <select class="select select-bordered w-full" name="template">
            @foreach($templates ?? [] as $template)
            <option value="{{ $template->id }}">{{ $template->title }}</option>
            @endforeach
          </select>
        </div>

        <div class="modal-action">
          <button type="button" @click="showCreateModal = false" class="btn btn-outline">{{ __('Cancel') }}</button>
          <button type="submit" class="btn btn-primary submit-btn gap-2">
            <span class="material-icons text-sm">save</span>
            {{ __('Create Reply') }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Modal -->
  <div x-show="showEditModal" x-cloak class="modal modal-open" @click.self="showEditModal = false">
    <div class="modal-box max-w-2xl">
      <h3 class="font-bold text-lg flex items-center gap-2 mb-4">
        <span class="material-icons">edit</span>
        {{ __('Edit Auto Reply') }}
      </h3>
      
      <form method="POST" :action="editData.action" class="ajaxform_instant_reload space-y-4">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Keyword') }}</span>
            </label>
            <input type="text" name="keyword" x-model="editData.keyword" class="input input-bordered w-full" maxlength="50" required />
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Device') }}</span>
            </label>
            <select class="select select-bordered w-full" name="device" x-model="editData.device" required>
              @foreach($devices as $device)
              <option value="{{ $device->id }}">{{ $device->name }} - {{ $device->phone }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Reply Type') }}</span>
            </label>
            <select class="select select-bordered w-full reply_type" name="reply_type" x-model="editData.reply_type" required>
              <option value="text">{{ __('Plain Text') }}</option>
              <option value="template">{{ __('Template') }}</option>
            </select>
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Match Type') }}</span>
            </label>
            <select class="select select-bordered w-full" name="match_type" x-model="editData.match_type" required>
              <option value="equal">{{ __('Hanya Kata') }}</option>
              <option value="like">{{ __('Terdapat Kata') }}</option>
            </select>
          </div>
        </div>

        <div class="form-control" x-show="editData.reply_type === 'text'" id="reply-area">
          <label class="label">
            <span class="label-text font-medium">{{ __('Reply Message') }}</span>
          </label>
          <textarea class="textarea textarea-bordered h-32" name="reply" x-model="editData.reply" maxlength="1000"></textarea>
        </div>

        <div class="form-control" x-show="editData.reply_type === 'template'" id="templates-area">
          <label class="label">
            <span class="label-text font-medium">{{ __('Select Template') }}</span>
          </label>
          <select class="select select-bordered w-full" name="template" x-model="editData.template_id">
            @foreach($templates ?? [] as $template)
            <option value="{{ $template->id }}">{{ $template->title }}</option>
            @endforeach
          </select>
        </div>

        <div class="modal-action">
          <button type="button" @click="showEditModal = false" class="btn btn-outline">{{ __('Cancel') }}</button>
          <button type="submit" class="btn btn-primary submit-btn gap-2">
            <span class="material-icons text-sm">save</span>
            {{ __('Update Reply') }}
          </button>
        </div>
      </form>
    </div>
  </div>

</div>

<style>
[x-cloak] { display: none !important; }
</style>

@endsection

@push('js')
<script src="{{ asset('assets/js/pages/user/chatbot.js') }}"></script>
<script>
// Toggle reply type fields
document.querySelectorAll('.reply_type').forEach(select => {
  select.addEventListener('change', function() {
    const form = this.closest('form');
    const textArea = form.querySelector('.text-area');
    const templates = form.querySelector('.templates');
    
    if (this.value === 'template') {
      if (textArea) textArea.classList.add('hidden');
      if (templates) templates.classList.remove('hidden');
    } else {
      if (textArea) textArea.classList.remove('hidden');
      if (templates) templates.classList.add('hidden');
    }
  });
});
</script>
@endpush