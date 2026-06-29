@extends('layouts.main.app')
@push('topcss')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css" integrity="sha512-qveKnGrvOChbSzAdtSs8p69eoLegyh+1hwOMbmpCViIwj7rn4oJjdmMvWOuyQlTOZgTlZA0N2PXA7iA8/2TUYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
[x-cloak] { display: none !important; }
.gradient-text {
    @apply bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary;
}
.gradient-bg {
    @apply bg-gradient-to-br from-primary to-secondary text-primary-content;
}
.nav-item-active {
    @apply bg-gradient-to-r from-primary/10 to-transparent border-l-4 border-primary text-primary font-bold;
}
.control-panel {
    @apply glass-card p-8 rounded-[2.5rem] mb-8 border-primary/10 bg-primary/5;
}
.noUi-connect {
    @apply bg-primary;
}
.noUi-target {
    @apply border-0 bg-base-200 shadow-none h-2;
}
.noUi-handle {
    @apply border-4 border-primary bg-white shadow-lg w-5 h-5 -right-2.5 -top-2 rounded-full cursor-pointer after:hidden before:hidden;
}
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    activeTab: 'send',
    tabs: [
      { id: 'history', icon: 'history', label: '{{ __('History') }}', url: '{{ route('user.bulk-message.index') }}' },
      { id: 'send', icon: 'send', label: '{{ __('Send Bulk') }}', url: '{{ route('user.bulk-message.create') }}' }
    ]
}">
  
  <!-- Page Header -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
    <div>
      <h1 class="text-xl md:text-2xl break-words md:text-3xl break-words md:text-4xl break-words font-black text-base-content tracking-tight mb-2">{{ __('Bulk Sender') }}</h1>
      <p class="text-base-content/60 text-lg font-medium">{{ __('Create and launch high-impact messaging campaigns') }}</p>
    </div>
    <div class="flex flex-wrap gap-3">
      <button onclick="addRecord.showModal()" class="btn btn-primary btn-lg shadow-xl shadow-primary/20 gap-2 px-6 rounded-full">
        <span class="material-symbols-outlined">add_circle</span>
        <span class="font-bold">{{ __('Add Record') }}</span>
      </button>
      <button onclick="import_from_contact.showModal()" class="btn btn-secondary btn-lg shadow-xl shadow-secondary/20 gap-2 px-6 rounded-full">
        <span class="material-symbols-outlined">group</span>
        <span class="font-bold">{{ __('From Contacts') }}</span>
      </button>
      <button onclick="exampleModal.showModal()" class="btn btn-accent btn-lg shadow-xl shadow-accent/20 gap-2 px-6 rounded-full">
        <span class="material-symbols-outlined">upload_file</span>
        <span class="font-bold">{{ __('Import CSV') }}</span>
      </button>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Mobile Navigation -->
    <div class="lg:hidden mb-6 col-span-1">
      <div class="glass-card rounded-3xl p-4">
        <div class="relative">
          <select @change="window.location.href = $event.target.value" class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50 font-bold">
            <template x-for="tab in tabs" :key="tab.id">
              <option :value="tab.url" x-text="tab.label" :selected="activeTab === tab.id"></option>
            </template>
          </select>
        </div>
      </div>
    </div>

    <!-- Sidebar Navigation -->
    <div class="hidden lg:block lg:col-span-3">
      <div class="glass-card rounded-3xl sticky top-24 overflow-hidden">
        <div class="p-6">
          <div class="flex items-center gap-3 mb-8 px-2">
            <div class="w-10 h-10 rounded-2xl gradient-bg flex items-center justify-center shadow-lg shadow-primary/20">
              <span class="material-symbols-outlined">campaign</span>
            </div>
            <h3 class="font-black text-xl tracking-tight">{{ __('Campaign') }}</h3>
          </div>
          
          <nav class="space-y-2">
            <template x-for="tab in tabs" :key="tab.id">
              <a :href="tab.url" 
                 :class="activeTab === tab.id ? 'nav-item-active' : 'text-base-content/60 hover:bg-base-200/50'"
                 class="flex items-center gap-4 w-full px-4 py-4 rounded-2xl transition-all duration-300 group">
                <span class="material-symbols-outlined transition-transform group-hover:scale-110" x-text="tab.icon"></span>
                <span class="font-semibold" x-text="tab.label"></span>
                <span x-show="activeTab === tab.id" class="ml-auto w-2 h-2 rounded-full bg-primary animate-pulse"></span>
              </a>
            </template>
          </nav>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="lg:col-span-9">
      <!-- Control Panel -->
      <div class="control-panel">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
          <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-3xl bg-primary flex flex-col items-center justify-center text-primary-content shadow-2xl shadow-primary/40">
              <span class="text-xl md:text-2xl break-words font-black leading-none total_sent">0</span>
              <div class="w-8 h-px bg-primary-content/30 my-1"></div>
              <span class="text-xs font-bold opacity-60 total_records">0</span>
            </div>
            <div>
              <h3 class="text-xl font-black tracking-tight mb-1">{{ __('Campaign Progress') }}</h3>
              <p class="text-base-content/50 text-sm font-medium">{{ __('Messages queued for delivery') }}</p>
            </div>
          </div>

          <div class="flex-1 max-w-md w-full">
            <div class="flex items-center justify-between mb-4 px-1">
              <span class="text-xs font-black uppercase tracking-widest text-base-content/40">{{ __('Delay Interval') }}</span>
              <div class="flex gap-2">
                <span class="badge badge-primary font-bold"><span id="start_time"></span>s</span>
                <span class="text-base-content/20 font-black">-</span>
                <span class="badge badge-primary font-bold"><span id="end_time"></span>s</span>
              </div>
            </div>
            <div id="slider" class="mx-2"></div>
          </div>

          <button class="btn btn-primary btn-lg h-20 px-10 shadow-2xl shadow-primary/30 gap-3 group send_now rounded-full">
            <span class="font-black text-lg uppercase tracking-wider">{{ __('Launch Now') }}</span>
            <span class="material-symbols-outlined text-xl md:text-2xl break-words md:text-3xl break-words transition-transform group-hover:translate-x-1 group-hover:-translate-y-1">rocket_launch</span>
          </button>
        </div>
      </div>

      <!-- Table Card -->
      <div class="glass-card rounded-[2.5rem] overflow-hidden">
        <div class="p-8 md:p-10">
          <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-base-200 flex items-center justify-center">
              <span class="material-symbols-outlined">list_alt</span>
            </div>
            <h2 class="text-xl md:text-2xl break-words font-black tracking-tight">{{ __('Queue List') }}</h2>
          </div>

          <div class="overflow-x-auto -mx-8 md:-mx-10 px-8 md:px-10">
            <table class="table w-full">
              <thead>
                <tr class="border-b-2 border-base-200">
                  <th class="bg-transparent text-xs font-black uppercase tracking-widest text-base-content/40 py-6">{{ __('Recipient') }}</th>
                  <th class="bg-transparent text-xs font-black uppercase tracking-widest text-base-content/40 py-6">{{ __('Device') }}</th>
                  <th class="bg-transparent text-xs font-black uppercase tracking-widest text-base-content/40 py-6">{{ __('Message') }}</th>
                  <th class="bg-transparent text-xs font-black uppercase tracking-widest text-base-content/40 py-6 text-right">{{ __('Status') }}</th>
                </tr>
              </thead>
              <tbody class="tbody divide-y divide-base-200">
                <!-- Populated by JS -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modals -->
<!-- Modals -->
<dialog id="exampleModal" class="modal">
  <div class="modal-box glass-card rounded-[2rem] border-0 overflow-hidden shadow-2xl p-0 max-w-2xl max-h-[90vh] flex flex-col">
    <div class="p-8 overflow-y-auto flex-1">
      <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-accent flex items-center justify-center text-accent-content shadow-lg shadow-accent/20">
            <span class="material-symbols-outlined">upload_file</span>
          </div>
          <h5 class="text-xl md:text-2xl break-words font-black tracking-tight">{{ __('Import from CSV') }}</h5>
        </div>
        <form method="dialog">
          <button class="btn btn-ghost btn-circle rounded-full">
            <span class="material-symbols-outlined">close</span>
          </button>
        </form>
      </div>

      <div class="space-y-6">
        <div class="form-control">
          <label class="label px-1">
            <span class="label-text font-bold text-base-content/80">{{ __('Select Device') }}</span>
          </label>
          <select class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50" id="device_import" name="device" required>
            <option value="" disabled selected>{{ __('Choose device...') }}</option>
            @foreach($devices as $device)
            <option value="{{ $device->id }}">{{ $device->name }} (+{{ $device->phone }})</option>
            @endforeach
          </select>
        </div>

        <div class="form-control csv_row">
          <label class="label px-1">
            <span class="label-text font-bold text-base-content/80">{{ __('CSV File') }}</span>
            <a href="{{ asset('uploads/demo.csv') }}" download class="label-text-alt text-primary font-bold flex items-center gap-1">
              <span class="material-symbols-outlined text-xs">download</span>
              {{ __('Sample') }}
            </a>
          </label>
          <input type="file" name="csv" class="file-input file-input-bordered w-full rounded-2xl bg-base-100/50 csv" required accept=".csv">
        </div>
      </div>
    </div>

    <div class="p-8 pt-0 border-t border-base-200/50">
        <form method="dialog" class="w-full">
          <button class="btn btn-outline btn-lg w-full rounded-full">{{ __('Close') }}</button>
        </form>
      </div>
    </div>
  </div>
  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</dialog>

<dialog id="import_from_contact" class="modal">
  <div class="modal-box glass-card rounded-[2rem] border-0 overflow-hidden shadow-2xl p-0 max-w-2xl max-h-[90vh] flex flex-col">
    <form class="contact-list-form flex flex-col h-full">
      <div class="p-8 overflow-y-auto flex-1">
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-secondary flex items-center justify-center text-secondary-content shadow-lg shadow-secondary/20">
              <span class="material-symbols-outlined">group</span>
            </div>
            <h5 class="text-xl md:text-2xl break-words font-black tracking-tight">{{ __('Import from Contacts') }}</h5>
          </div>
          <div class="btn btn-ghost btn-circle rounded-full" onclick="import_from_contact.close()">
            <span class="material-symbols-outlined">close</span>
          </div>
        </div>

        <div class="space-y-6">
          <div class="form-control">
            <label class="label px-1">
              <span class="label-text font-bold text-base-content/80">{{ __('Select Device') }}</span>
            </label>
            <select class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50" id="wa-device" name="device" required>
              <option value="" disabled selected>{{ __('Choose device...') }}</option>
              @foreach($devices as $device)
              <option value="{{ $device->id }}">{{ $device->name }} (+{{ $device->phone }})</option>
              @endforeach
            </select>
          </div>

          <div class="form-control">
            <label class="label px-1">
              <span class="label-text font-bold text-base-content/80">{{ __('Receiver Group') }}</span>
            </label>
            <select class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50 select2" id="wa-contacts" name="group" required>
              @foreach($groups ?? [] as $group)
              <option value="{{ $group->id }}" data-contacts="{{ $group->contacts }}">{{ $group->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-control">
            <label class="label px-1">
              <span class="label-text font-bold text-base-content/80">{{ __('Message') }}</span>
            </label>
            <textarea class="textarea textarea-bordered h-48 w-full rounded-2xl bg-base-100/50" id="wa_temp_message" maxlength="1000" placeholder="{{ __('Type your message here...') }}" required></textarea>
            <span class="text-error text-xs font-bold mt-2 none message_alert">{{ __('Message is required') }}</span>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4 p-8 pt-0 border-t border-base-200/50">
          <button type="button" class="btn btn-outline btn-lg rounded-full" onclick="import_from_contact.close()">{{ __('Close') }}</button>
          <button type="submit" class="btn btn-primary btn-lg shadow-xl shadow-primary/20 rounded-full">{{ __('Import Now') }}</button>
        </div>
      </div>
    </form>
  </div>
  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</dialog>

<dialog id="addRecord" class="modal">
  <div class="modal-box glass-card rounded-[2rem] border-0 overflow-hidden shadow-2xl p-0 max-w-2xl max-h-[90vh] flex flex-col">
    <div class="p-8 overflow-y-auto flex-1">
      <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-primary flex items-center justify-center text-primary-content shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined">add_circle</span>
          </div>
          <h5 class="text-xl md:text-2xl break-words font-black tracking-tight">{{ __('Add Custom Row') }}</h5>
        </div>
        <form method="dialog">
          <button class="btn btn-ghost btn-circle rounded-full">
            <span class="material-symbols-outlined">close</span>
          </button>
        </form>
      </div>

      <div class="space-y-6">
        <div class="form-control">
          <label class="label px-1">
            <span class="label-text font-bold text-base-content/80">{{ __('Select Device') }}</span>
          </label>
          <select class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50" id="device_custom" name="device" required>
            @foreach($devices as $device)
            <option value="{{ $device->id }}">+{{ $device->phone }} ({{ $device->name }})</option>
            @endforeach
          </select>
        </div>

        <div class="form-control">
          <label class="label px-1">
            <span class="label-text font-bold text-base-content/80">{{ __('Recipient(s)') }}</span>
          </label>
          <textarea class="textarea textarea-bordered h-20 w-full rounded-2xl bg-base-100/50" id="to" placeholder="{{ __('628xxx, 628xxx...') }}" required></textarea>
          <label class="label">
            <span class="label-text-alt text-base-content/40 font-bold uppercase tracking-widest text-[10px]">{{ __('Use comma for multiple numbers') }}</span>
          </label>
          <span class="text-error text-xs font-bold mt-2 none to_alert">{{ __('Recipient is required') }}</span>
        </div>

        <div class="form-control">
          <label class="label px-1">
            <span class="label-text font-bold text-base-content/80">{{ __('Message') }}</span>
          </label>
          <textarea class="textarea textarea-bordered h-32 w-full rounded-2xl bg-base-100/50" id="wa_message" maxlength="1000" placeholder="{{ __('Type your message here...') }}" required></textarea>
          <span class="text-error text-xs font-bold mt-2 none message_alert">{{ __('Message is required') }}</span>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-4 p-8 pt-0 border-t border-base-200/50">
      <form method="dialog" class="w-full">
        <button class="btn btn-outline btn-lg w-full rounded-full">{{ __('Close') }}</button>
      </form>
      <button class="btn btn-primary btn-lg shadow-xl shadow-primary/20 add_custom_row rounded-full">{{ __('Add Now') }}</button>
    </div>
  </div>
  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</dialog>





<input type="hidden" id="base_url" value="{{ url('/') }}">
<input type="hidden" id="bulk_message_link" value="{{ route('user.bulk-message.store') }}">
@csrf
@endsection

@push('js')
<script src="{{ asset('assets/vendor/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/bulk/jquery.csv.min.js') }}" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js" integrity="sha512-UOJe4paV6hYWBnS0c9GnIRH8PLm2nFK22uhfAvsTIqd3uwnWsVri1OPn5fJYdLtGY3wB11LGHJ4yPU1WFJeBYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/pages/bulk/bulkmessage.js?v=2.0') }}" ></script>

@endpush