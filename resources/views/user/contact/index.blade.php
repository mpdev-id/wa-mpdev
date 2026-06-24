@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',[
'title' => __('Contact List'),
'buttons' =>[
	[
		'name'=>__('Create Contact'),
		'url'=> route('user.contact.create'),
	],
	[
		'name'=>__('Contact Groups'),
		'url'=> route('user.group.index'),
	],
	[
		'name'=>__('Import Contacts'),
		'url'=>'#',
		'components'=>'data-toggle="modal" data-target="#import-contact"', 
		'is_button'=>true
	],
	[
		'name'=>__('Sent Bulk With Template'),
		'url'=>'#',
		'components'=>'data-toggle="modal" data-target="#send-template-bulk" id="send-template-bulks"',
		'is_button'=>true
	]
]])
@endsection
@push('topcss')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select2/dist/css/select2.min.css') }}">

@endpush
@section('content')
<div x-data="{ 
  showEditModal: false, 
  showImportModal: false,
  showSyncModal: false,
  showBulkModal: false,
  syncing: false,
  sendAll: false,
  editData: {name: '', phone: '', group: '', action: ''}
}" 
     @reset-syncing.window="syncing = false" 
     @start-syncing.window="syncing = true"
     @open-import-modal.window="showImportModal = true"
     @open-bulk-modal.window="showBulkModal = true">
  
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Total Contacts -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-md">
      <div class="flex-1">
        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ __('Total Contacts') }}</p>
        <h3 class="text-3xl font-extrabold text-gray-800">{{ $total_contacts }}</h3>
      </div>
      <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
        <span class="material-icons text-2xl">contacts</span>
      </div>
    </div>

    <!-- Contacts Statistics -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-md">
      <div class="flex-1">
        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ __('Contact Statistics') }}</p>
        <h3 class="text-3xl font-extrabold text-gray-850 text-emerald-600">{{ $limit }}</h3>
      </div>
      <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
        <span class="material-icons text-2xl">trending_up</span>
      </div>
    </div>
  </div>

  <!-- Action Buttons -->
  <div class="flex flex-wrap items-center gap-3 mb-8 bg-gray-50 p-4 rounded-2xl border border-gray-100">
    <a href="{{ route('user.contact.create') }}" class="btn btn-success text-white bg-gradient-to-r from-emerald-500 to-teal-600 border-none shadow-sm hover:shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all gap-2">
      <span class="material-icons text-lg">add</span>
      <span class="font-semibold text-sm">{{ __('Create Contact') }}</span>
    </a>
    <a href="{{ route('user.group.index') }}" class="btn btn-outline border-gray-250 hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-700 bg-white text-gray-600 shadow-sm transition-all gap-2">
      <span class="material-icons text-lg">group</span>
      <span class="font-semibold text-sm">{{ __('Contact Groups') }}</span>
    </a>
    <button @click="showImportModal = true" class="btn btn-outline border-gray-250 hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-700 bg-white text-gray-600 shadow-sm transition-all gap-2">
      <span class="material-icons text-lg">upload_file</span>
      <span class="font-semibold text-sm">{{ __('Import Contacts') }}</span>
    </button>
    <button @click="showSyncModal = true" class="btn btn-outline border-emerald-200 hover:border-emerald-600 hover:bg-emerald-600 hover:text-white bg-white text-emerald-600 shadow-sm transition-all gap-2">
      <span class="material-icons text-lg">sync</span>
      <span class="font-semibold text-sm">{{ __('Sync from WhatsApp') }}</span>
    </button>
    <button @click="showBulkModal = true" class="btn btn-outline border-gray-250 hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-700 bg-white text-gray-600 shadow-sm transition-all gap-2">
      <span class="material-icons text-lg">send</span>
      <span class="font-semibold text-sm">{{ __('Send Bulk Message') }}</span>
    </button>
  </div>

  @if($total_contacts > 0 || !empty($search))
  <!-- Search and Filter Bar -->
  <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
    <div class="text-sm font-semibold text-gray-700">
      @if(!empty($search))
        {{ __('Search results for') }}: <span class="text-emerald-600 font-bold">"{{ $search }}"</span>
      @else
        {{ __('All Contacts') }}
      @endif
    </div>
    <form method="GET" action="{{ route('user.contact.index') }}" class="w-full md:w-80 relative flex gap-2">
      <div class="relative flex-1">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <span class="material-icons text-gray-400 text-sm">search</span>
        </div>
        <input 
          type="text" 
          name="search" 
          value="{{ $search ?? '' }}"
          placeholder="{{ __('Search by name or number...') }}" 
          class="input input-sm input-bordered w-full pl-9 bg-gray-50 text-gray-855 focus:outline-none focus:border-emerald-500 text-xs"
        />
      </div>
      <button type="submit" class="btn btn-success btn-xs text-white h-8 px-3">{{ __('Search') }}</button>
      @if(!empty($search))
        <a href="{{ route('user.contact.index') }}" class="btn btn-ghost btn-xs h-8 text-red-650 hover:bg-red-50 px-2 flex items-center justify-center" title="{{ __('Clear Search') }}">
          <span class="material-icons text-sm">close</span>
        </a>
      @endif
    </form>
  </div>
  @endif

  @if(count($contacts ?? []) > 0)
  <!-- Contacts List Table -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="overflow-x-auto">
      <table class="table w-full">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100 text-gray-700">
            <th class="py-4 pl-6 text-xs font-bold uppercase tracking-wider text-gray-500">{{ __('Contact Name') }}</th>
            <th class="py-4 text-xs font-bold uppercase tracking-wider text-gray-500">{{ __('Groups') }}</th>
            <th class="py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500">{{ __('WhatsApp Number') }}</th>
            <th class="py-4 pr-6 text-right text-xs font-bold uppercase tracking-wider text-gray-500">{{ __('Actions') }}</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach($contacts ?? [] as $contact)
          <tr class="hover:bg-gray-50/80 transition-colors">
            <td class="py-4 pl-6 font-medium text-gray-800">
              <div class="flex items-center gap-3">
                <div class="avatar placeholder shrink-0">
                  <div class="bg-emerald-100 text-emerald-800 rounded-full w-9 h-9 flex items-center justify-center font-bold">
                    <span class="text-xs uppercase" x-text="String('{{ $contact->name }}').substring(0, 1).toUpperCase()"></span>
                  </div>
                </div>
                <span class="text-sm font-semibold text-gray-900">{{ $contact->name }}</span>
              </div>
            </td>
            <td class="py-4">
              <div class="flex flex-wrap gap-1">
                @foreach($contact->groupcontact as $groupcontact)
                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100/50">{{ $groupcontact->name }}</span>
                @endforeach
              </div>
            </td>
            <td class="py-4 text-right font-mono text-sm text-gray-600">{{ $contact->phone }}</td>
            <td class="py-4 pr-6 text-right">
              <div class="flex items-center justify-end gap-1">
                <a href="javascript:void(0)" 
                   @click="editData = {
                     name: '{{ $contact->name }}', 
                     phone: '{{ $contact->phone }}', 
                     group: '{{ $contact->groupcontact[0]->id ?? '' }}', 
                     action: '{{ route('user.contact.update',$contact->id) }}'
                   }; showEditModal = true"
                   class="btn btn-sm btn-ghost btn-circle text-emerald-600 hover:bg-emerald-50"
                   title="{{ __('Edit') }}">
                  <span class="material-icons text-sm">edit</span>
                </a>
                
                <a href="javascript:void(0)" 
                   class="delete-confirm btn btn-sm btn-ghost btn-circle text-red-600 hover:bg-red-50" 
                   data-action="{{ route('user.contact.destroy',$contact->id) }}"
                   title="{{ __('Remove') }}">
                  <span class="material-icons text-sm">delete</span>
                </a>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    
    <!-- Pagination -->
    <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex justify-center">
      {{ $contacts->links('vendor.pagination.bootstrap-4') }}
    </div>
  </div>
  @else
  <!-- Empty State -->
  <div class="flex flex-col items-center justify-center py-20 px-4 bg-white rounded-2xl border border-gray-100 shadow-sm animate-fade-in w-full">
    <div class="w-20 h-20 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 shadow-inner">
      <span class="material-icons text-4xl">contacts</span>
    </div>
    <h3 class="text-xl font-bold text-gray-900 mb-1">
      @if(!empty($search))
        {{ __('No Matching Contacts') }}
      @else
        {{ __('No Contacts Found') }}
      @endif
    </h3>
    <p class="text-gray-500 mb-6 text-center max-w-sm text-sm">
      @if(!empty($search))
        {{ __('Try searching for a different name or number, or clear the search query.') }}
      @else
        {{ __('Start building your contact list to send WhatsApp messages easily.') }}
      @endif
    </p>
    <div class="flex gap-3">
      @if(!empty($search))
        <a href="{{ route('user.contact.index') }}" class="btn btn-outline border-gray-200 hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-700 bg-white text-gray-600 shadow-sm transition-all gap-2 btn-sm">
          <span class="material-icons text-sm">clear_all</span>
          {{ __('Clear Search') }}
        </a>
      @else
        <a href="{{ route('user.contact.create') }}" class="btn btn-success text-white bg-gradient-to-r from-emerald-500 to-teal-600 border-none shadow-sm hover:shadow-md transition-all gap-2">
          <span class="material-icons text-sm">add</span>
          {{ __('Create Contact') }}
        </a>
        <button @click="showImportModal = true" class="btn btn-outline border-gray-200 hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-700 bg-white text-gray-600 shadow-sm transition-all gap-2">
          <span class="material-icons text-sm">upload_file</span>
          {{ __('Import CSV') }}
        </button>
      @endif
    </div>
  </div>
  @endif


  <!-- Edit Contact Modal -->
  <div x-show="showEditModal" 
       x-cloak 
       class="fixed inset-0 z-50 overflow-y-auto"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
      <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full z-10 p-6"
           @click.away="showEditModal = false"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0 scale-95"
           x-transition:enter-end="opacity-100 scale-100"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100 scale-100"
           x-transition:leave-end="opacity-0 scale-95">
        
        <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
          <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900">
            <span class="material-icons text-emerald-600">edit</span>
            {{ __('Edit Contact') }}
          </h3>
          <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-gray-650">
            <span class="material-icons">close</span>
          </button>
        </div>

        <form method="POST" :action="editData.action" class="ajaxform_instant_reload space-y-4">
          @csrf
          @method('PUT')
          
          <div class="form-control">
            <label class="label py-1">
              <span class="label-text font-semibold text-gray-700 text-xs">{{ __('Contact Name') }}</span>
            </label>
            <input 
              type="text" 
              name="name" 
              x-model="editData.name"
              class="input input-bordered w-full bg-gray-50 text-gray-800 focus:outline-none focus:border-emerald-500 text-sm" 
              placeholder="e.g. John Doe" 
              maxlength="50" 
              required
            />
          </div>

          <div class="form-control">
            <label class="label py-1">
              <span class="label-text font-semibold text-gray-700 text-xs">{{ __('WhatsApp Number') }}</span>
            </label>
            <input 
              type="text" 
              name="phone" 
              x-model="editData.phone"
              class="input input-bordered w-full bg-gray-50 text-gray-800 focus:outline-none focus:border-emerald-500 text-sm" 
              placeholder="e.g. 628123456789" 
              maxlength="15" 
              required
            />
          </div>

          <div class="form-control">
            <label class="label py-1">
              <span class="label-text font-semibold text-gray-700 text-xs">{{ __('Select Group') }}</span>
            </label>
            <select name="group" x-model="editData.group" class="select select-bordered w-full bg-gray-50 text-gray-850 focus:outline-none focus:border-emerald-500 text-sm">
              @foreach($groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="modal-action pt-4 border-t border-gray-100 flex justify-end gap-2">
            <button type="button" @click="showEditModal = false" class="btn btn-ghost text-gray-500 hover:bg-gray-100 btn-sm">
              {{ __('Cancel') }}
            </button>
            <button type="submit" class="btn btn-success text-white submit-btn btn-sm gap-1.5 shadow-sm">
              <span class="material-icons text-sm">save</span>
              {{ __('Save Changes') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Import Contact Modal -->
  <div x-show="showImportModal" 
       x-cloak 
       class="fixed inset-0 z-50 overflow-y-auto"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
      <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full z-10 p-6"
           @click.away="showImportModal = false"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0 scale-95"
           x-transition:enter-end="opacity-100 scale-100"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100 scale-100"
           x-transition:leave-end="opacity-0 scale-95">
        
        <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
          <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900">
            <span class="material-icons text-emerald-600">upload_file</span>
            {{ __('Import Contacts') }}
          </h3>
          <button type="button" @click="showImportModal = false" class="text-gray-400 hover:text-gray-650">
            <span class="material-icons">close</span>
          </button>
        </div>

        <form method="POST" action="{{ route('user.contact.import') }}" class="ajaxform_instant_reload space-y-4" enctype="multipart/form-data">
          @csrf
          
          <div class="form-control">
            <label class="label py-1">
              <span class="label-text font-semibold text-gray-700 text-xs">{{ __('Select CSV File') }}</span>
              <a href="{{ asset('uploads/demo-contact.csv') }}" download class="label-text-alt text-emerald-600 hover:underline flex items-center gap-0.5 font-medium">
                <span class="material-icons text-xs">download</span>
                {{ __('Download Sample') }}
              </a>
            </label>
            <input 
              type="file" 
              accept=".csv" 
              name="file" 
              class="file-input file-input-bordered w-full bg-gray-50 text-gray-800 text-sm focus:outline-none focus:border-emerald-500" 
              required
            />
          </div>

          <div class="form-control">
            <label class="label py-1">
              <span class="label-text font-semibold text-gray-700 text-xs">{{ __('Assign to Group') }}</span>
            </label>
            <select name="group" class="select select-bordered w-full bg-gray-50 text-gray-800 text-sm focus:outline-none focus:border-emerald-500">
              @foreach($groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="modal-action pt-4 border-t border-gray-100 flex justify-end gap-2">
            <button type="button" @click="showImportModal = false" class="btn btn-ghost text-gray-500 hover:bg-gray-100 btn-sm">
              {{ __('Cancel') }}
            </button>
            <button type="submit" class="btn btn-success text-white submit-btn btn-sm gap-1.5 shadow-sm">
              <span class="material-icons text-sm">upload</span>
              {{ __('Import Now') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Sync Contacts Modal -->
  <div x-show="showSyncModal" 
       x-cloak
       class="fixed inset-0 z-50 overflow-y-auto"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
      <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full z-10"
           @click.away="showSyncModal = false"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0 scale-95"
           x-transition:enter-end="opacity-100 scale-100"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100 scale-100"
           x-transition:leave-end="opacity-0 scale-95">
        
        <form id="sync-form" action="{{ route('user.contact.sync') }}" method="POST">
          @csrf
          
          <!-- Header -->
          <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-icons">sync</span>
              </div>
              <h3 class="text-lg font-bold text-gray-900">{{ __('Sync Contacts') }}</h3>
            </div>
            <button type="button" @click="showSyncModal = false" class="text-gray-400 hover:text-gray-600">
              <span class="material-icons">close</span>
            </button>
          </div>
          
          <!-- Body -->
          <div class="p-6 space-y-4">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-blue-900">
              <div class="flex gap-3">
                <span class="material-icons text-blue-600">info</span>
                <div class="flex-1">
                  <p class="text-sm font-semibold">{{ __('Sync Active Contacts') }}</p>
                  <p class="text-xs text-blue-700 mt-1">{{ __('This will import contacts from your active chat history. Contacts without recent conversations may not be included.') }}</p>
                </div>
              </div>
            </div>
            
            <div class="form-control">
              <label class="label py-1">
                <span class="label-text font-semibold text-gray-700 text-xs">{{ __('Select WhatsApp Device') }}</span>
              </label>
              <select name="device_id" class="select select-bordered w-full bg-gray-50 text-gray-800 text-sm focus:outline-none focus:border-emerald-500" required>
                <option value="">{{ __('Choose a device...') }}</option>
                @foreach($devices as $device)
                <option value="{{ $device->id }}">{{ $device->name }} - {{ $device->phone }}</option>
                @endforeach
              </select>
            </div>
          </div>
          
          <!-- Footer -->
          <div class="flex items-center justify-end gap-2 p-6 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl">
            <button type="button" @click="showSyncModal = false" class="btn btn-ghost text-gray-500 btn-sm hover:bg-gray-100" :disabled="syncing">
              {{ __('Cancel') }}
            </button>
            <button type="submit" class="btn btn-success text-white submit-btn btn-sm gap-1.5 shadow-sm" :disabled="syncing">
              <span class="material-icons text-sm" :class="{'animate-spin': syncing}">sync</span>
              <span x-text="syncing ? '{{ __('Syncing...') }}' : '{{ __('Sync Now') }}'"></span>
            </button>
          </div>
          
          <!-- Loading Overlay -->
          <div x-show="syncing" 
               x-cloak
               class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center rounded-2xl z-10">
            <div class="text-center">
              <div class="w-16 h-16 mx-auto mb-4">
                <svg class="animate-spin h-12 w-12 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </div>
              <p class="text-lg font-bold text-gray-900">{{ __('Syncing Contacts...') }}</p>
              <p class="text-sm text-gray-500 mt-1">{{ __('Please wait while we import your contacts') }}</p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Send Bulk Message Modal -->
  <div x-show="showBulkModal" 
       x-cloak 
       class="fixed inset-0 z-50 overflow-y-auto"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
      <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full z-10 p-6"
           @click.away="showBulkModal = false"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0 scale-95"
           x-transition:enter-end="opacity-100 scale-100"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100 scale-100"
           x-transition:leave-end="opacity-0 scale-95">
        
        <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
          <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900">
            <span class="material-icons text-emerald-600">send</span>
            {{ __('Send Bulk Message') }}
          </h3>
          <button type="button" @click="showBulkModal = false" class="text-gray-400 hover:text-gray-650">
            <span class="material-icons">close</span>
          </button>
        </div>

        @if(count($templates) > 0 && count($contacts) > 0)
        <form method="POST" action="{{ route('user.contact.send-template-bulk') }}" 
              :class="sendAll ? 'ajaxform_instant_reload bulk_send_form space-y-4' : 'ajaxform bulk_send_form space-y-4'">
          @csrf
          <input type="hidden" name="page_url" value="{{ url()->full() }}">
          
          <div class="form-control">
            <label class="label py-1">
              <span class="label-text font-semibold text-gray-700 text-xs">{{ __('Select Template') }}</span>
            </label>
            <select name="template" class="select select-bordered w-full bg-gray-50 text-gray-800 text-sm focus:outline-none focus:border-emerald-500">
              @foreach($templates as $template)
                <option value="{{ $template->id }}">{{ $template->title }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-control">
            <label class="label py-1">
              <span class="label-text font-semibold text-gray-700 text-xs">{{ __('Select Device') }}</span>
            </label>
            <select name="device" class="select select-bordered w-full bg-gray-50 text-gray-800 text-sm focus:outline-none focus:border-emerald-500">
              @foreach($devices as $device)
                <option value="{{ $device->id }}">{{ $device->name }} - {{ $device->phone }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-control receivers animate-fade-in" x-show="!sendAll">
            <label class="label py-1">
              <span class="label-text font-semibold text-gray-700 text-xs">{{ __('Select Receivers') }}</span>
            </label>
            <select name="contacts[]" class="form-control select2 w-full" multiple style="width: 100%">
              @foreach($contacts as $conatct)
                <option value="{{ $conatct->id }}">({{ $conatct->name }}) - {{ $conatct->phone }}</option>
              @endforeach
            </select>
          </div>

          <div class="flex items-center gap-3 py-2 bg-gray-50 p-3 rounded-xl border border-gray-100">
            <input 
              type="checkbox" 
              name="sentall" 
              id="plain-text" 
              x-model="sendAll"
              class="checkbox checkbox-success checkbox-sm"
            />
            <label for="plain-text" class="cursor-pointer text-xs font-semibold text-gray-700 select-none">
              {{ __('Send this template to all contacts?') }}
            </label>
          </div>

          <div class="modal-action pt-4 border-t border-gray-100 flex justify-end gap-2">
            <button type="button" @click="showBulkModal = false" class="btn btn-ghost text-gray-500 hover:bg-gray-100 btn-sm">
              {{ __('Cancel') }}
            </button>
            <button type="submit" class="btn btn-success text-white submit-btn btn-sm gap-1.5 shadow-sm">
              <span class="material-icons text-sm">send</span>
              {{ __('Send Now') }}
            </button>
          </div>
        </form>
        @else
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-amber-900 text-sm flex gap-2">
          <span class="material-icons text-amber-600">warning</span>
          <span>{{ __('Please create at least one template and save contacts first.') }}</span>
        </div>
        <div class="modal-action pt-4">
          <button type="button" @click="showBulkModal = false" class="btn btn-ghost text-gray-500 hover:bg-gray-100 btn-sm">
            {{ __('Close') }}
          </button>
        </div>
        @endif
      </div>
    </div>
  </div>
@endsection
@push('topjs')
<script src="{{ asset('assets/vendor/select2/dist/js/select2.min.js') }}"></script>
@endpush
@push('js')
<script src="{{ asset('assets/js/pages/user/contact.js?V=1') }}"></script>
<script>
$(document).ready(function() {
    // Intercept clicks on legacy header buttons to open Alpine modals
    $(document).on('click', '[data-target="#import-contact"]', function(e) {
        e.preventDefault();
        window.dispatchEvent(new CustomEvent('open-import-modal'));
    });

    $(document).on('click', '[data-target="#send-template-bulk"]', function(e) {
        e.preventDefault();
        window.dispatchEvent(new CustomEvent('open-bulk-modal'));
    });

    // Use event delegation for dynamically rendered form
    $(document).on('submit', '#sync-form', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var url = form.attr('action');
        var formData = form.serialize();
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function() {
                window.dispatchEvent(new CustomEvent('start-syncing'));
            },
            success: function(response) {
                var message = response.message;
                if (response.synced !== undefined) {
                    message += '\n\nSynced: ' + response.synced + 
                               '\nSkipped: ' + response.skipped + 
                               '\nTotal: ' + response.total;
                }
                
                Toastify({
                    text: message,
                    duration: 5000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                }).showToast();
                
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function(xhr, status, error) {
                var syncButton = form.find('button[type="submit"]');
                syncButton.prop('disabled', false);
                
                window.dispatchEvent(new CustomEvent('reset-syncing'));
                
                var message = 'Error syncing contacts';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                Toastify({
                    text: message,
                    duration: 5000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                }).showToast();
            }
        });
        
        return false;
    });
});
</script>
@endpush

@push('css')
<style>
  [x-cloak] { display: none !important; }
  
  /* Select2 premium overrides */
  .select2-container--default .select2-selection--multiple {
    background-color: #f9fafb !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 0.5rem !important;
    padding: 0.25rem 0.5rem !important;
    transition: all 0.2s !important;
  }
  .select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #10b981 !important;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2) !important;
    background-color: #fff !important;
  }
  .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #ecfdf5 !important;
    border: 1px solid #a7f3d0 !important;
    color: #065f46 !important;
    border-radius: 0.375rem !important;
    padding: 0.125rem 0.5rem !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
  }
  .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #047857 !important;
    margin-right: 0.25rem !important;
  }
  .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #b91c1c !important;
    background: transparent !important;
  }
  .select2-dropdown {
    border: 1px solid #f3f4f6 !important;
    border-radius: 0.5rem !important;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1) !important;
    z-index: 9999 !important;
  }

  /* Style Bootstrap pagination to be horizontal and modern */
  .pagination {
    display: flex !important;
    list-style: none !important;
    padding-left: 0 !important;
    border-radius: 0.5rem !important;
    gap: 0.25rem !important;
    align-items: center !important;
  }
  .pagination li {
    display: inline !important;
  }
  .pagination li a, 
  .pagination li span {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 2.25rem !important;
    height: 2.25rem !important;
    padding: 0.25rem 0.75rem !important;
    font-size: 0.875rem !important;
    font-weight: 600 !important;
    color: #4b5563 !important; /* text-gray-600 */
    background-color: #fff !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 0.5rem !important;
    text-decoration: none !important;
    transition: all 0.2s !important;
  }
  .pagination li a:hover {
    color: #047857 !important; /* text-emerald-700 */
    background-color: #ecfdf5 !important; /* bg-emerald-50 */
    border-color: #a7f3d0 !important; /* border-emerald-200 */
  }
  .pagination li.active span {
    color: #fff !important;
    background-color: #10b981 !important; /* bg-emerald-500 */
    border-color: #10b981 !important;
  }
  .pagination li.disabled span {
    color: #9ca3af !important; /* text-gray-400 */
    background-color: #f9fafb !important; /* bg-gray-50 */
    border-color: #e5e7eb !important;
    cursor: not-allowed !important;
  }
</style>
@endpush
