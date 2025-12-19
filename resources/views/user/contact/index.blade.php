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
  showBulkModal: false,
  editData: {name: '', phone: '', group: '', action: ''}
}">
  
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
@endpush
