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
  editData: {name: '', phone: '', group: '', action: ''}
}" @reset-syncing.window="syncing = false" @start-syncing.window="syncing = true">
  
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Total Contacts -->
    <div class="stats-card animate-slide-up">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Contacts') }}</p>
          <h3 class="text-3xl font-bold text-gray-900">{{ $total_contacts }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-blue-500 to-blue-600">
          <span class="material-icons">contacts</span>
        </div>
      </div>
    </div>

    <!-- Contacts Statistics -->
    <div class="stats-card animate-slide-up" style="animation-delay: 0.1s">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Contact Statistics') }}</p>
          <h3 class="text-3xl font-bold text-green-600">{{ $limit }}</h3>
        </div>
        <div class="stats-card-icon bg-gradient-to-br from-green-500 to-green-600">
          <span class="material-icons">trending_up</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Action Buttons -->
  <div class="flex flex-wrap gap-3 mb-6">
    <a href="{{ route('user.contact.create') }}" class="btn btn-primary gap-2">
      <span class="material-icons text-sm">add</span>
      {{ __('Create Contact') }}
    </a>
    <a href="{{ route('user.group.index') }}" class="btn btn-outline gap-2">
      <span class="material-icons text-sm">group</span>
      {{ __('Contact Groups') }}
    </a>
    <button @click="showImportModal = true" class="btn btn-outline gap-2">
      <span class="material-icons text-sm">upload_file</span>
      {{ __('Import Contacts') }}
    </button>
    <button @click="showSyncModal = true" class="btn btn-outline btn-success gap-2">
      <span class="material-icons text-sm">sync</span>
      {{ __('Sync from WhatsApp') }}
    </button>
    <button @click="showBulkModal = true" class="btn btn-outline gap-2">
      <span class="material-icons text-sm">send</span>
      {{ __('Send Bulk Message') }}
    </button>
  </div>

  @if(count($contacts ?? []) > 0)
  <!-- Contacts List -->
  <div class="card bg-white shadow-lg">
    <div class="card-body p-0">
      <div class="overflow-x-auto">
        <table class="table table-zebra">
          <thead>
            <tr class="border-b border-gray-200">
              <th class="bg-gray-50 text-gray-700 font-semibold">{{ __('Contact Name') }}</th>
              <th class="bg-gray-50 text-gray-700 font-semibold">{{ __('Groups') }}</th>
              <th class="bg-gray-50 text-gray-700 font-semibold text-right">{{ __('WhatsApp Number') }}</th>
              <th class="bg-gray-50 text-gray-700 font-semibold text-right">{{ __('Actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($contacts ?? [] as $contact)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="font-medium text-gray-900">
                <div class="flex items-center gap-3">
                  <div class="avatar placeholder">
                    <div class="bg-primary/10 text-primary rounded-full w-10 h-10 flex items-center justify-center">
                      <span class="text-sm font-semibold">{{ substr($contact->name, 0, 1) }}</span>
                    </div>
                  </div>
                  <span>{{ $contact->name }}</span>
                </div>
              </td>
              <td>
                <div class="flex flex-wrap gap-1">
                  @foreach($contact->groupcontact as $groupcontact)
                  <span class="badge badge-primary badge-sm">{{ $groupcontact->name }}</span>
                  @endforeach
                </div>
              </td>
              <td class="text-right font-mono text-sm">{{ $contact->phone }}</td>
              <td class="text-right">
                <div class="dropdown dropdown-end">
                  <button tabindex="0" class="btn btn-ghost btn-sm btn-circle">
                    <span class="material-icons text-sm">more_vert</span>
                  </button>
                  <ul tabindex="0" class="dropdown-content z-50 menu p-2 shadow-xl bg-white rounded-xl w-48 border border-gray-200">
                    <li>
                      <a href="javascript:void(0)" 
                         @click="editData = {
                           name: '{{ $contact->name }}', 
                           phone: '{{ $contact->phone }}', 
                           group: '{{ $contact->groupcontact[0]->id ?? '' }}', 
                           action: '{{ route('user.contact.update',$contact->id) }}'
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
                         data-action="{{ route('user.contact.destroy',$contact->id) }}">
                        <span class="material-icons text-sm">delete</span>
                        {{ __('Remove') }}
                      </a>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="p-4 border-t border-gray-200">
        <div class="flex justify-center">
          {{ $contacts->links('vendor.pagination.bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>
  @else
  <!-- Empty State -->
  <div class="flex flex-col items-center justify-center py-16 px4 animate-fade-in">
    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
      <span class="material-icons text-6xl text-gray-400">contacts</span>
    </div>
    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('No Contacts Found') }}</h3>
    <p class="text-gray-600 mb-6 text-center max-w-md">{{ __('Start building your contact list to send WhatsApp messages') }}</p>
    <div class="flex gap-3">
      <a href="{{ route('user.contact.create') }}" class="btn btn-primary gap-2">
        <span class="material-icons text-sm">add</span>
        {{ __('Create Contact') }}
      </a>
      <button @click="showImportModal = true" class="btn btn-outline gap-2">
        <span class="material-icons text-sm">upload_file</span>
        {{ __('Import CSV') }}
      </button>
    </div>
  </div>
  @endif


<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form type="POST" action="" class="edit-modal ajaxform_instant_reload">
				@csrf
				@method('PUT')

				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Contact') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>{{ __('User Name') }}</label>
						<input type="text" name="name" id="user_name" placeholder="Jhone Doe" maxlength="50" class="form-control" required="">
					</div>
					<div class="form-group">
						<label>{{ __('Whatsapp Number') }}</label>
						<input type="number" name="phone" id="user_phone" placeholder="{{ __('Enter Phone Number With Country Code') }}" maxlength="15" class="form-control">
					</div>
					<div class="form-group">
						<label>{{ __('Select Group') }}</label>
						<select name="group" class="form-control" id="group-list">
							@foreach($groups as $group)
							<option value="{{ $group->id }}">{{ $group->name }}</option>
							@endforeach
						</select>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
					<button type="submit" class="btn btn-primary submit-btn">{{ __('Save changes') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="import-contact" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form type="POST" action="{{ route('user.contact.import') }}" class="ajaxform_instant_reload" enctype="multipart/form-data">
				@csrf
				

				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{ __('Import Contact') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>{{ __('Select CSV') }} <a href="{{ asset('uploads/demo-contact.csv') }}" download="">{{ __('(Download Sample)')  }}</a></label>
						<input type="file" accept=".csv" name="file"  class="form-control" required="">
					</div>	

					<div class="form-group">
						<label>{{ __('Select Group') }}</label>
						<select name="group" class="form-control" >
							@foreach($groups as $group)
							<option value="{{ $group->id }}">{{ $group->name }}</option>
							@endforeach
						</select>
					</div>									
				</div>

				

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
					<button type="submit" class="btn btn-primary submit-btn">{{ __('Import') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Sync Contacts Modal -->
<div x-show="showSyncModal" 
     x-cloak
     @click.away="showSyncModal = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
  
  <!-- Backdrop -->
  <div class="fixed inset-0 bg-black bg-opacity-50"></div>
  
  <!-- Modal -->
  <div class="flex items-center justify-center min-h-screen p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full"
         @click.stop
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
      
      <form id="sync-form" action="{{ route('user.contact.sync') }}" method="POST">
        @csrf
        
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
              <span class="material-icons text-green-600">sync</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900">{{ __('Sync Contacts') }}</h3>
          </div>
          <button type="button" @click="showSyncModal = false" class="text-gray-400 hover:text-gray-600">
            <span class="material-icons">close</span>
          </button>
        </div>
        
        <!-- Body -->
        <div class="p-6 space-y-4">
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex gap-3">
              <span class="material-icons text-blue-600">info</span>
              <div class="flex-1">
                <p class="text-sm text-blue-900 font-medium">{{ __('Sync Active Contacts') }}</p>
                <p class="text-xs text-blue-700 mt-1">{{ __('This will import contacts from your active chat history. Contacts without recent conversations may not be included.') }}</p>
              </div>
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              {{ __('Select WhatsApp Device') }}
            </label>
            <select name="device_id" class="select select-bordered w-full" required>
              <option value="">{{ __('Choose a device...') }}</option>
              @foreach($devices as $device)
              <option value="{{ $device->id }}">{{ $device->name }} - {{ $device->phone }}</option>
              @endforeach
            </select>
          </div>
        </div>
        
        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
          <button type="button" @click="showSyncModal = false" class="btn btn-ghost" :disabled="syncing">
            {{ __('Cancel') }}
          </button>
          <button type="submit" 
                  class="btn btn-success gap-2" 
                  :disabled="syncing">
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
              <svg class="animate-spin h-16 w-16 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </div>
            <p class="text-lg font-semibold text-gray-900">{{ __('Syncing Contacts...') }}</p>
            <p class="text-sm text-gray-600 mt-1">{{ __('Please wait while we import your contacts') }}</p>
          </div>
        </div>
      </form>
      
    </div>
  </div>
</div>




<div class="modal fade" id="send-template-bulk" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
	<div class="modal-dialog">
		@if(count($templates) > 0 && count($contacts) > 0)
		<div class="modal-content">
			
			<form type="POST" action="{{ route('user.contact.send-template-bulk') }}" class="ajaxform bulk_send_form">
				@csrf
				<input type="hidden" name="page_url" value="{{ url()->full() }}">
				
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{ __('Send Bulk Message With Template') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					
					<div class="form-group">
						<label>{{ __('Select Template') }}</label>
						<select  class="form-control" name="template">
							@foreach($templates as $template)
								<option value="{{ $template->id }}">{{ $template->title }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label>{{ __('Select Device') }}</label>
						<select  class="form-control" name="device">
							@foreach($devices as $device)
								<option value="{{ $device->id }}">{{ $device->name }} - {{ $device->phone }}</option>
							@endforeach
						</select>
					</div>
					
					<div class="form-group receivers">
						<label>{{ __('Select Receivers') }}</label>
						<select  class="form-control select2" name="contacts[]" multiple="">
							@foreach($contacts as $conatct)
								<option value="{{ $conatct->id }}">({{ $conatct->name }}) - {{ $conatct->phone }}</option>
							@endforeach
						</select>
					</div>
					
					<div class="form-group d-flex">
						<label class="custom-toggle custom-toggle-primary">
							<input type="checkbox"  name="sentall" id="plain-text"  data-target=".plain-title" class="save-template">
							<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
						</label>
						<label class="mt-1 ml-1" for="plain-text"><h4>{{ __('Sent this template to all user?') }}</h4></label>
					</div>
					

				</div>

				<div class="modal-footer">
					
					<button type="submit" class="btn btn-neutral submit-btn float-right">{{ __('Sent Now') }}</button>
				</div>
			</form>
		</div>
		@else
		<div class="alert  bg-gradient-primary text-white"><span class="text-left">{{ __('Create some template and contacts') }}</span></div>
		@endif
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
    // Use event delegation for dynamically rendered form
    $(document).on('submit', '#sync-form', function(e) {
        e.preventDefault();
        
        console.log('Sync form submitted'); // Debug log
        
        var form = $(this);
        var url = form.attr('action');
        var formData = form.serialize();
        
        console.log('URL:', url); // Debug log
        console.log('Data:', formData); // Debug log
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function() {
                console.log('Sending request...'); // Debug log
                window.dispatchEvent(new CustomEvent('start-syncing'));
            },
            success: function(response) {
                console.log('Success:', response); // Debug log
                
                // Show success message with statistics
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
                
                // Reload page after 2 seconds
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function(xhr, status, error) {
                console.log('Error:', xhr, status, error); // Debug log
                
                // Reset syncing state via DOM manipulation
                var syncButton = form.find('button[type="submit"]');
                syncButton.prop('disabled', false);
                
                // Trigger Alpine to reset syncing state
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
        
        return false; // Prevent any default behavior
    });
});
</script>
@endpush
