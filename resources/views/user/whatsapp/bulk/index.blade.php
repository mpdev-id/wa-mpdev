@extends('layouts.main.app')
@push('topcss')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select2/dist/css/select2.min.css') }}">
<style>
[x-cloak] { display: none !important; }
.glass-card {
    @apply bg-white/70 backdrop-blur-xl border border-white/20 shadow-2xl;
}
.gradient-text {
    @apply bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary;
}
.gradient-bg {
    @apply bg-gradient-to-br from-primary to-secondary text-primary-content;
}
.nav-item-active {
    @apply bg-gradient-to-r from-primary/10 to-transparent border-l-4 border-primary text-primary font-bold;
}
.stat-card {
    @apply glass-card p-6 rounded-3xl transition-all duration-300 hover:scale-[1.02] hover:shadow-primary/10;
}
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    activeTab: 'history',
    tabs: [
      { id: 'history', icon: 'history', label: '{{ __('History') }}', url: '{{ route('user.bulk-message.index') }}' },
      { id: 'send', icon: 'send', label: '{{ __('Send Bulk') }}', url: '{{ route('user.bulk-message.create') }}' }
    ]
}">
  
  <!-- Page Header -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 p-6 rounded-2xl bg-[#fff]">
    <div>
      <h1 class="text-4xl font-black text-base-content tracking-tight mb-2">{{ __('Bulk Messages') }}</h1>
      <p class="text-base-content/60 text-lg font-medium">{{ __('Manage and track your bulk message campaigns') }}</p>
    </div>
    <div class="flex flex-wrap gap-3">
      <a href="{{ route('user.bulk-message.create') }}" class="btn btn-primary btn-lg rounded-2xl shadow-xl shadow-primary/20 gap-2 px-8">
        <span class="material-symbols-outlined">add_circle</span>
        <span class="font-bold">{{ __('New Campaign') }}</span>
      </a>
      <button data-toggle="modal" data-target="#send-template-bulk" class="btn btn-outline btn-lg rounded-2xl gap-2 px-8 border-2">
        <span class="material-symbols-outlined">description</span>
        <span class="font-bold">{{ __('Use Template') }}</span>
      </button>
    </div>
  </div>

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
    <div class="stat-card group">
      <div class="flex items-center justify-between mb-4 p-6 rounded-2xl  bg-[#fff]">
        <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500">
          <span class="material-symbols-outlined text-3xl">rocket_launch</span>
        </div>
        <div class="text-right">
          <div class="text-3xl font-black tracking-tight">{{ number_format($total) }}</div>
          <div class="text-xs font-bold text-base-content/40 uppercase tracking-widest">{{ __('Total Sent') }}</div>
        </div>
      </div>
      <div class="h-1.5 w-full bg-base-200 rounded-full overflow-hidden">
        <div class="h-full bg-primary rounded-full" style="width: 70%"></div>
      </div>
    </div>

    <div class="stat-card group">
      <div class="flex items-center justify-between mb-4 p-6 rounded-2xl  bg-[#fff]">
        <div class="w-14 h-14 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary group-hover:scale-110 transition-transform duration-500">
          <span class="material-symbols-outlined text-3xl">calendar_month</span>
        </div>
        <div class="text-right">
          <div class="text-3xl font-black tracking-tight">{{ number_format($last30_messages ?? 0) }}</div>
          <div class="text-xs font-bold text-base-content/40 uppercase tracking-widest">{{ __('Last 30 Days') }}</div>
        </div>
      </div>
      <div class="h-1.5 w-full bg-base-200 rounded-full overflow-hidden">
        <div class="h-full bg-secondary rounded-full" style="width: 45%"></div>
      </div>
    </div>

    <div class="stat-card group">
      <div class="flex items-center justify-between mb-4 p-6 rounded-2xl  bg-[#fff]">
        <div class="w-14 h-14 rounded-2xl bg-accent/10 flex items-center justify-center text-accent group-hover:scale-110 transition-transform duration-500">
          <span class="material-symbols-outlined text-3xl">today</span>
        </div>
        <div class="text-right">
          <div class="text-3xl font-black tracking-tight">{{ number_format($today_transaction) }}</div>
          <div class="text-xs font-bold text-base-content/40 uppercase tracking-widest">{{ __('Today') }}</div>
        </div>
      </div>
      <div class="h-1.5 w-full bg-base-200 rounded-full overflow-hidden">
        <div class="h-full bg-accent rounded-full" style="width: 30%"></div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 ">
    
    <!-- Mobile Navigation -->
    <div class="lg:hidden mb-6 col-span-1 p-6 rounded-2xl  bg-[#fff]">
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
      <div class="glass-card rounded-3xl sticky top-24 overflow-hidden  rounded-2xl  bg-[#fff]">
        <div class="p-6">
          <div class="flex items-center gap-3 mb-8 px-2 ">
            <div class="w-10 h-10 rounded-2xl gradient-bg flex items-center justify-center shadow-lg shadow-primary/20">
              <span class="material-symbols-outlined">dashboard</span>
            </div>
            <h3 class="font-black text-xl tracking-tight">{{ __('Navigation') }}</h3>
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
      <div class="glass-card rounded-3xl overflow-hidden">
        <div class="p-8 md:p-10 rounded-2xl  bg-[#fff]">
          <div class="flex items-center justify-between mb-10 ">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-2xl gradient-bg flex items-center justify-center shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined">history</span>
              </div>
              <h2 class="text-2xl font-black tracking-tight">{{ __('Transaction History') }}</h2>
            </div>
          </div>

          @if(count($posts) > 0)
          <div class="overflow-x-auto -mx-8 md:-mx-10 px-8 md:px-10">
            <table class="table table-zebra w-full">
              <thead>
                <tr class="border-b-2 border-base-200">
                  <th class="bg-transparent text-xs font-black uppercase tracking-widest text-base-content/40 py-6">{{ __('From') }}</th>
                  <th class="bg-transparent text-xs font-black uppercase tracking-widest text-base-content/40 py-6">{{ __('To') }}</th>
                  <th class="bg-transparent text-xs font-black uppercase tracking-widest text-base-content/40 py-6">{{ __('Type') }}</th>
                  <th class="bg-transparent text-xs font-black uppercase tracking-widest text-base-content/40 py-6">{{ __('Template') }}</th>
                  <th class="bg-transparent text-xs font-black uppercase tracking-widest text-base-content/40 py-6 text-right">{{ __('Date') }}</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-base-200">
                @foreach($posts as $log)
                <tr class="group hover:bg-base-200/30 transition-colors">
                  <td class="py-6">
                    <div class="flex items-center gap-3">
                      <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-sm">devices</span>
                      </div>
                      <span class="font-bold text-sm">{{ $log->from }}</span>
                    </div>
                  </td>
                  <td class="py-6">
                    <span class="font-bold text-sm text-base-content/70">{{ $log->to }}</span>
                  </td>
                  <td class="py-6">
                    <span class="badge badge-ghost font-bold text-[10px] uppercase tracking-wider px-3">
                      {{ $log->template != null ? 'Template' : 'Plain Text' }}
                    </span>
                  </td>
                  <td class="py-6">
                    <span class="font-medium text-sm">{{ $log->template->title ?? '-' }}</span>
                  </td>
                  <td class="py-6 text-right">
                    <span class="font-bold text-xs text-base-content/40 uppercase tracking-tighter">{{ $log->created_at->format('d M Y') }}</span>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="mt-10">
            {{ $posts->links('vendor.pagination.bootstrap-4') }}
          </div>
          @else
          <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center mb-6">
              <span class="material-symbols-outlined text-5xl text-base-content/20">inbox</span>
            </div>
            <h3 class="text-xl font-bold mb-2">{{ __('No Transactions Found') }}</h3>
            <p class="text-base-content/50 max-w-xs mx-auto">{{ __('Start sending bulk messages to see your history here.') }}</p>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Template Modal -->
<div class="modal fade" id="send-template-bulk" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content glass-card rounded-[2rem] border-0 overflow-hidden shadow-2xl">
      @if(count($templates) > 0 && count($groups) > 0)
      <form method="POST" action="{{ route('user.contact.send-template-bulk') }}" class="ajaxform_instant_reload">
        @csrf
        <div class="p-8">
          <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-2xl gradient-bg flex items-center justify-center shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined">description</span>
              </div>
              <h5 class="text-2xl font-black tracking-tight">{{ __('Send with Template') }}</h5>
            </div>
            <button type="button" class="btn btn-ghost btn-circle" data-dismiss="modal">
              <span class="material-symbols-outlined">close</span>
            </button>
          </div>

          <div class="space-y-6">
            <div class="form-control">
              <label class="label px-1">
                <span class="label-text font-bold text-base-content/80">{{ __('Select Template') }}</span>
              </label>
              <select class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50" name="template" required>
                @foreach($templates as $template)
                <option value="{{ $template->id }}">{{ $template->title }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-control">
              <label class="label px-1">
                <span class="label-text font-bold text-base-content/80">{{ __('Select Device') }}</span>
              </label>
              <select class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50" name="device" required>
                @foreach($devices as $device)
                <option value="{{ $device->id }}">{{ $device->name }} - {{ $device->phone }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-control">
              <label class="label px-1">
                <span class="label-text font-bold text-base-content/80">{{ __('Select Group') }}</span>
              </label>
              <select class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50 select2" name="group" required>
                @foreach($groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="mt-10">
            <button type="submit" class="btn btn-primary btn-lg w-full rounded-2xl h-16 shadow-xl shadow-primary/20 gap-3 group">
              <span class="font-bold text-lg">{{ __('Send Campaign Now') }}</span>
              <span class="material-symbols-outlined transition-transform group-hover:translate-x-1 group-hover:-translate-y-1">send</span>
            </button>
          </div>
        </div>
      </form>
      @else
      <div class="p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-error/10 flex items-center justify-center mx-auto mb-6">
          <span class="material-symbols-outlined text-4xl text-error">warning</span>
        </div>
        <h3 class="text-xl font-bold mb-2">{{ __('Missing Requirements') }}</h3>
        <p class="text-base-content/50 mb-8">{{ __('You need at least one template and one contact group to use this feature.') }}</p>
        <div class="flex flex-col gap-3">
          <a href="{{ route('user.template.index') }}" class="btn btn-outline rounded-xl">{{ __('Create Template') }}</a>
          <a href="{{ route('user.contact.index') }}" class="btn btn-outline rounded-xl">{{ __('Manage Contacts') }}</a>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
@push('topjs')
<script src="{{ asset('assets/vendor/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/user/wa-bulk-index.js?v=1') }}" ></script>
@endpush