@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Contact Groups')])
@endsection

@section('content')

<div class="max-w-7xl mx-auto" x-data="{ showCreateModal: false, showEditModal: false, editData: { id: '', name: '', action: '' } }">
  
  <!-- Page Header -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Contact Groups') }}</h1>
      <p class="text-base-content/60">{{ __('Organize your contacts into groups') }}</p>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('user.contact.index') }}" class="btn btn-outline gap-2">
        <span class="material-icons text-sm">contacts</span>
        {{ __('Contact List') }}
      </a>
      <button @click="showCreateModal = true" class="btn btn-primary gap-2">
        <span class="material-icons text-sm">add</span>
        {{ __('Create Group') }}
      </button>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Total Groups -->
    <div class="stats-card animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Groups') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ $total_groups ?? 0 }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-primary to-secondary">
          <span class="material-icons">folder</span>
        </div>
      </div>
    </div>

    <!-- Total Contacts in Groups -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Contacts') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ $limit ?? 0 }}</h3>
         <p class="text-xs text-gray-500 mt-1">{{ __('In all groups') }}</p>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-success to-success/80">
          <span class="material-icons">groups</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Groups List -->
  @if(count($groups ?? []) > 0)
  <div class="card bg-white shadow-xl">
    <div class="card-body p-0">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th class="bg-base-200">
                <div class="flex items-center gap-2">
                  <span class="material-icons text-sm">label</span>
                  {{ __('Group Name') }}
                </div>
              </th>
              <th class="bg-base-200 text-center">
                <div class="flex items-center justify-center gap-2">
                  <span class="material-icons text-sm">people</span>
                  {{ __('Total Contacts') }}
                </div>
              </th>
              <th class="bg-base-200 text-right">{{ __('Actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($groups ?? [] as $group)
            <tr class="hover">
              <td>
                <div class="flex items-center gap-3">
                  <div class="avatar placeholder">
                    <div class="bg-primary/10 text-primary rounded-full w-10 h-10 flex items-center justify-center">
                      <span class="material-icons">folder</span>
                    </div>
                  </div>
                  <div>
                    <div class="font-semibold text-gray-900">{{ $group->name }}</div>
                    <div class="text-xs text-gray-500">{{ __('Contact Group') }}</div>
                  </div>
                </div>
              </td>
              <td class="text-center">
                <span class="badge badge-primary badge-lg">
                  {{ $group->groupcontacts_count }} {{ __('contacts') }}
                </span>
              </td>
              <td>
                <div class="flex justify-end">
                  <div class="dropdown dropdown-end">
                    <button tabindex="0" class="btn btn-ghost btn-sm btn-circle">
                      <span class="material-icons text-sm">more_vert</span>
                    </button>
                    <ul tabindex="0" class="dropdown-content z-[9999] menu p-2 shadow-xl bg-white rounded-xl w-48 border border-base-200">
                      <li>
                        <a href="javascript:void(0)" 
                           @click="editData = { name: '{{ $group->name }}', action: '{{ route('user.group.update', $group->id) }}' }; showEditModal = true"
                           class="flex items-center gap-2">
                          <span class="material-icons text-sm">edit</span>
                          {{ __('Edit') }}
                        </a>
                      </li>
                      <div class="divider my-0"></div>
                      <li>
                        <a href="javascript:void(0)" 
                           class="delete-confirm text-red-600 hover:bg-red-50 flex items-center gap-2" 
                           data-action="{{ route('user.group.destroy', $group->id) }}">
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
          {{ $groups->links('vendor.pagination.bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>
  @else
  <!-- Empty State -->
  <div class="flex flex-col items-center justify-center py-16 px-4 animate-fade-in">
    <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center mb-4">
      <span class="material-icons text-6xl text-base-content/40">folder_off</span>
    </div>
    <h3 class="text-xl font-semibold text-base-content mb-2">{{ __('No Groups Found') }}</h3>
    <p class="text-base-content/60 mb-6 text-center max-w-md">
      {{ __('Create your first contact group to organize your contacts better') }}
    </p>
    <button @click="showCreateModal = true" class="btn btn-primary gap-2">
      <span class="material-icons text-sm">add</span>
      {{ __('Create Group') }}
    </button>
  </div>
  @endif

  <!-- Create Modal -->
  <div x-show="showCreateModal" 
       x-cloak 
       class="modal modal-open" 
       @click.self="showCreateModal = false">
    <div class="modal-box">
      <h3 class="font-bold text-lg flex items-center gap-2">
        <span class="material-icons">add_circle</span>
        {{ __('Create New Group') }}
      </h3>
      
      <form method="POST" action="{{ route('user.group.store') }}" class="ajaxform_instant_reload mt-4">
        @csrf
        
        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Group Name') }}</span>
          </label>
          <input 
            type="text" 
            name="name" 
            class="input input-bordered w-full" 
            placeholder="{{ __('Enter group name') }}" 
            maxlength="50" 
            required
          />
        </div>

        <div class="modal-action">
          <button type="button" @click="showCreateModal = false" class="btn btn-outline">
            {{ __('Cancel') }}
          </button>
          <button type="submit" class="btn btn-primary submit-btn gap-2">
            <span class="material-icons text-sm">save</span>
            {{ __('Create Group') }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Modal -->
  <div x-show="showEditModal" 
       x-cloak 
       class="modal modal-open" 
       @click.self="showEditModal = false">
    <div class="modal-box">
      <h3 class="font-bold text-lg flex items-center gap-2">
        <span class="material-icons">edit</span>
        {{ __('Edit Group') }}
      </h3>
      
      <form method="POST" :action="editData.action" class="ajaxform_instant_reload mt-4">
        @csrf
        @method('PUT')
        
        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">{{ __('Group Name') }}</span>
          </label>
          <input 
            type="text" 
            name="name" 
            x-model="editData.name"
            class="input input-bordered w-full" 
            placeholder="{{ __('Enter group name') }}" 
            maxlength="50" 
            required
          />
        </div>

        <div class="modal-action">
          <button type="button" @click="showEditModal = false" class="btn btn-outline">
            {{ __('Cancel') }}
          </button>
          <button type="submit" class="btn btn-primary submit-btn gap-2">
            <span class="material-icons text-sm">save</span>
            {{ __('Save Changes') }}
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

