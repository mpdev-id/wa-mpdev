@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Send Message')])
@endsection

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
.form-section { @apply space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
  
  <!-- Page Header -->
  <div class="mb-8">
    <h1 class="text-3xl font-bold text-base-content mb-2">{{ __('Send Custom Message') }}</h1>
    <p class="text-base-content/60 text-lg">{{ __('Send WhatsApp messages using different formats') }}</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="{ 
    activeTab: 'plain',
    tabs: [
      { id: 'plain', icon: 'text_fields', label: '{{ __('Plain Text') }}' },
      { id: 'media', icon: 'image', label: '{{ __('With Media') }}' },
      { id: 'button', icon: 'smart_button', label: '{{ __('With Button') }}' },
      { id: 'list', icon: 'list', label: '{{ __('List Message') }}' },
      { id: 'poll', icon: 'poll', label: '{{ __('Poll Message') }}' },
      { id: 'location', icon: 'location_on', label: '{{ __('Location') }}' }
    ]
  }">
    
    <!-- Mobile Navigation (Select Option) -->
    <div class="lg:hidden mb-6">
      <div class="glass-card rounded-3xl p-4">
        <label class="label px-1 pt-0">
          <span class="label-text font-bold text-base-content/60 uppercase tracking-widest text-xs">{{ __('Message Type') }}</span>
        </label>
        <div class="relative">
          <select x-model="activeTab" class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300 font-bold">
            <template x-for="tab in tabs" :key="tab.id">
              <option :value="tab.id" x-text="tab.label"></option>
            </template>
          </select>
          <div class="absolute inset-y-0 right-10 flex items-center pointer-events-none text-primary">
            <span class="material-symbols-outlined">expand_more</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar Navigation (Desktop) -->
    <div class="hidden lg:block lg:col-span-3 p-4">
      <div class="glass-card rounded-3xl sticky top-24 overflow-hidden">
        <div class="p-6">
          <div class="flex items-center gap-3 mb-8 px-2">
            <div class="w-10 h-10 rounded-2xl gradient-bg flex items-center justify-center shadow-lg shadow-primary/20">
              <span class="material-symbols-outlined">category</span>
            </div>
            <h3 class="font-black text-xl tracking-tight">{{ __('Message Type') }}</h3>
          </div>
          
          <nav class="space-y-2">
            <template x-for="tab in tabs" :key="tab.id">
              <button @click="activeTab = tab.id" 
                      :class="activeTab === tab.id ? 'nav-item-active' : 'text-base-content/60 hover:bg-base-200/50'"
                      class="flex items-center gap-4 w-full px-4 py-4 rounded-2xl transition-all duration-300 group">
                <span class="material-symbols-outlined transition-transform group-hover:scale-110" x-text="tab.icon"></span>
                <span class="font-semibold" x-text="tab.label"></span>
                <span x-show="activeTab === tab.id" class="ml-auto w-2 h-2 rounded-full bg-primary animate-pulse"></span>
              </button>
            </template>
          </nav>
        </div>
        
        <!-- Quick Stats/Info -->
        <div class="bg-primary/5 p-6 mt-4 border-t border-primary/10">
          <div class="flex items-center gap-3 text-primary mb-2">
            <span class="material-symbols-outlined text-sm">info</span>
            <span class="text-xs font-bold uppercase tracking-wider">{{ __('Quick Tip') }}</span>
          </div>
          <p class="text-xs text-base-content/70 leading-relaxed">
            {{ __('Use templates for faster messaging and better engagement with your customers.') }}
          </p>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="lg:col-span-9">
      <div class="glass-card rounded-3xl overflow-hidden p-4">
        <div class="p-8 md:p-12">
          
          <!-- Plain Text Tab -->
          <div x-show="activeTab === 'plain'" x-cloak class="form-section">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
              <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl gradient-bg flex items-center justify-center shadow-xl shadow-primary/30">
                  <span class="material-symbols-outlined text-3xl">text_fields</span>
                </div>
                <div>
                  <h2 class="text-3xl font-black tracking-tight">{{ __('Plain Text Message') }}</h2>
                  <p class="text-base-content/60 font-medium">{{ __('Send simple text message to any number') }}</p>
                </div>
              </div>
              <div class="badge badge-lg badge-outline gap-2 py-4 px-6 rounded-xl border-base-300">
                <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                <span class="font-bold text-xs uppercase tracking-widest">{{ __('Direct Send') }}</span>
              </div>
            </div>

            <form method="POST" action="{{ url('/user/sent-whatsapp-custom-text/plain-text') }}" class="message-send-form space-y-8">
              @csrf
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="form-control w-full">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Select Device') }}</span>
                  </label>
                  <div class="relative group">
                    <select name="device" class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" required>
                      <option value="">{{ __('Choose device...') }}</option>
                      @foreach($devices ?? [] as $device)
                        <option value="{{ $device->id }}">{{ $device->name }} ({{ $device->phone }})</option>
                      @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-10 flex items-center pointer-events-none text-base-content/30 group-hover:text-primary transition-colors">
                      <span class="material-symbols-outlined">devices</span>
                    </div>
                  </div>
                </div>

                <div class="form-control w-full">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Recipient Number') }}</span>
                  </label>
                  <div class="relative group">
                    <input type="text" name="phone" class="input input-bordered input-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" placeholder="628xxxxxxxxxx" required />
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-base-content/30 group-hover:text-primary transition-colors">
                      <span class="material-symbols-outlined">call</span>
                    </div>
                  </div>
                  <label class="label px-1">
                    <span class="label-text-alt text-base-content/50 font-medium">{{ __('Include country code (e.g. 62 for Indonesia)') }}</span>
                  </label>
                </div>
              </div>

              <div class="form-control p-12">
                <label class="label px-1">
                  <span class="label-text font-bold text-base-content/80">{{ __('Message Content') }}</span>
                </label>
                <div class="relative">
                  <textarea name="message" class="textarea textarea-bordered textarea-lg h-48 w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300 leading-relaxed" placeholder="{{ __('Type your message here...') }}" required></textarea>
                  <div class="absolute bottom-4 right-4 text-xs font-bold text-base-content/30 uppercase tracking-widest">
                    {{ __('WhatsApp Standard') }}
                  </div>
                </div>
              </div>

              <div class="pt-4">
                <button type="submit" class="btn btn-primary btn-lg w-full md:w-auto min-w-[240px] h-16 rounded-2xl shadow-xl shadow-primary/20 gap-3 group">
                  <span class="font-bold text-lg">{{ __('Send Message') }}</span>
                  <span class="material-symbols-outlined transition-transform group-hover:translate-x-1 group-hover:-translate-y-1">send</span>
                </button>
              </div>
            </form>
          </div>

          <!-- Text With Media Tab -->
          <div x-show="activeTab === 'media'" x-cloak class="form-section">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
              <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl gradient-bg flex items-center justify-center shadow-xl shadow-primary/30">
                  <span class="material-symbols-outlined text-3xl">image</span>
                </div>
                <div>
                  <h2 class="text-3xl font-black tracking-tight">{{ __('Text With Media') }}</h2>
                  <p class="text-base-content/60 font-medium">{{ __('Send image, video or document with caption') }}</p>
                </div>
              </div>
            </div>

            <form method="POST" action="{{ url('/user/sent-whatsapp-custom-text/text-with-media') }}" enctype="multipart/form-data" class="message-send-form space-y-8">
              @csrf
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="form-control w-full">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Select Device') }}</span>
                  </label>
                  <select name="device" class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" required>
                    <option value="">{{ __('Choose device...') }}</option>
                    @foreach($devices ?? [] as $device)
                      <option value="{{ $device->id }}">{{ $device->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-control w-full">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Recipient Number') }}</span>
                  </label>
                  <input type="text" name="phone" class="input input-bordered input-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" placeholder="628xxxxxxxxxx" required />
                </div>
              </div>

              <div class="form-control">
                <label class="label px-1">
                  <span class="label-text font-bold text-base-content/80">{{ __('Media File') }}</span>
                </label>
                <div class="flex items-center justify-center w-full">
                  <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-base-300 rounded-3xl cursor-pointer bg-base-100/50 hover:bg-base-100 hover:border-primary/50 transition-all group">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                      <span class="material-symbols-outlined text-4xl text-base-content/30 group-hover:text-primary transition-colors mb-3">cloud_upload</span>
                      <p class="mb-2 text-sm text-base-content/60 font-semibold">{{ __('Click to upload or drag and drop') }}</p>
                      <p class="text-xs text-base-content/40 uppercase tracking-widest font-bold">{{ __('Images, Videos, PDF (Max 10MB)') }}</p>
                    </div>
                    <input type="file" name="file" class="hidden" accept="image/*,video/*,.pdf" required />
                  </label>
                </div>
              </div>

              <div class="form-control">
                <label class="label px-1">
                  <span class="label-text font-bold text-base-content/80">{{ __('Caption (Optional)') }}</span>
                </label>
                <textarea name="message" class="textarea textarea-bordered textarea-lg h-32 w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" placeholder="{{ __('Type your caption here...') }}"></textarea>
              </div>

              <div class="pt-4">
                <button type="submit" class="btn btn-primary btn-lg w-full md:w-auto min-w-[240px] h-16 rounded-2xl shadow-xl shadow-primary/20 gap-3 group">
                  <span class="font-bold text-lg">{{ __('Send Media') }}</span>
                  <span class="material-symbols-outlined transition-transform group-hover:translate-x-1 group-hover:-translate-y-1">send</span>
                </button>
              </div>
            </form>
          </div>


          <!-- List Message Tab -->
          <div x-show="activeTab === 'list'" x-cloak class="form-section" x-data="{ sections: [{title: '', rows: [{title: '', description: ''}]}] }">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
              <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl gradient-bg flex items-center justify-center shadow-xl shadow-primary/30">
                  <span class="material-symbols-outlined text-3xl">list</span>
                </div>
                <div>
                  <h2 class="text-3xl font-black tracking-tight">{{ __('List Message') }}</h2>
                  <p class="text-base-content/60 font-medium">{{ __('Send interactive list with multiple options') }}</p>
                </div>
              </div>
            </div>

            <form method="POST" action="{{ url('/user/sent-whatsapp-custom-text/text-with-list') }}" class="message-send-form space-y-8">
              @csrf
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="form-control w-full">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Select Device') }}</span>
                  </label>
                  <select name="device" class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" required>
                    <option value="">{{ __('Choose device...') }}</option>
                    @foreach($devices ?? [] as $device)
                      <option value="{{ $device->id }}">{{ $device->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-control w-full">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Recipient Number') }}</span>
                  </label>
                  <input type="text" name="phone" class="input input-bordered input-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" placeholder="628xxxxxxxxxx" required />
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="form-control">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Header Title') }}</span>
                  </label>
                  <input type="text" name="header_title" class="input input-bordered input-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" maxlength="30" placeholder="{{ __('List header') }}" required />
                </div>

                <div class="form-control">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Button Text') }}</span>
                  </label>
                  <input type="text" name="button_text" class="input input-bordered input-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" maxlength="30" placeholder="{{ __('View options') }}" required />
                </div>
              </div>

              <div class="form-control">
                <label class="label px-1">
                  <span class="label-text font-bold text-base-content/80">{{ __('Message Body') }}</span>
                </label>
                <textarea name="message" class="textarea textarea-bordered textarea-lg h-24 w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" maxlength="300" placeholder="{{ __('Your list description...') }}" required></textarea>
              </div>

              <div class="form-control">
                <label class="label px-1">
                  <span class="label-text font-bold text-base-content/80">{{ __('Footer Text') }}</span>
                </label>
                <input type="text" name="footer_text" class="input input-bordered input-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" maxlength="30" placeholder="{{ __('Footer text') }}" required />
              </div>

              <div class="space-y-6">
                <div class="flex items-center justify-between px-1">
                  <h3 class="text-xl font-black tracking-tight">{{ __('List Sections') }}</h3>
                  <span class="badge badge-primary badge-lg font-bold">{{ __('Max 20') }}</span>
                </div>

                <template x-for="(section, sIndex) in sections" :key="sIndex">
                  <div class="bg-base-200/30 rounded-3xl p-6 md:p-8 border border-base-300 relative group/section">
                    <button type="button" @click="sections.splice(sIndex, 1)" class="absolute top-4 right-4 btn btn-ghost btn-sm btn-circle text-error opacity-0 group-hover/section:opacity-100 transition-opacity" x-show="sections.length > 1">
                      <span class="material-symbols-outlined">close</span>
                    </button>

                    <div class="flex items-center gap-3 mb-6">
                      <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                        <span class="text-xs font-black" x-text="sIndex + 1"></span>
                      </div>
                      <input type="text" x-model="section.title" :name="'section['+sIndex+'][title]'" class="input input-ghost input-lg font-bold text-xl w-full focus:bg-base-100 rounded-xl" placeholder="{{ __('Section Title') }}" maxlength="50" required />
                    </div>
                    
                    <div class="space-y-4">
                      <template x-for="(row, rIndex) in section.rows" :key="rIndex">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start group/row">
                          <div class="md:col-span-5">
                            <input type="text" x-model="row.title" :name="'section['+sIndex+'][value]['+rIndex+'][title]'" class="input input-bordered w-full rounded-xl bg-base-100" placeholder="{{ __('Option Title') }}" maxlength="50" required />
                          </div>
                          <div class="md:col-span-6">
                            <input type="text" x-model="row.description" :name="'section['+sIndex+'][value]['+rIndex+'][description]'" class="input input-bordered w-full rounded-xl bg-base-100" placeholder="{{ __('Description (Optional)') }}" maxlength="50" />
                          </div>
                          <div class="md:col-span-1 flex justify-center pt-2">
                            <button type="button" @click="section.rows.splice(rIndex, 1)" class="btn btn-ghost btn-sm btn-circle text-error opacity-0 group-hover/row:opacity-100 transition-opacity" x-show="section.rows.length > 1">
                              <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                          </div>
                        </div>
                      </template>
                    </div>
                    
                    <button type="button" @click="section.rows.push({title: '', description: ''})" class="btn btn-ghost btn-sm gap-2 mt-6 text-primary font-bold hover:bg-primary/10 rounded-xl">
                      <span class="material-symbols-outlined text-sm">add_circle</span>
                      {{ __('Add Option') }}
                    </button>
                  </div>
                </template>

                <button type="button" @click="sections.push({title: '', rows: [{title: '', description: ''}]})" class="btn btn-outline btn-lg w-full border-dashed border-2 rounded-3xl gap-3 hover:bg-primary/5 hover:border-primary transition-all" x-show="sections.length < 20">
                  <span class="material-symbols-outlined">add_box</span>
                  <span class="font-bold">{{ __('Add New Section') }}</span>
                </button>
              </div>

              <div class="pt-8">
                <button type="submit" class="btn btn-primary btn-lg w-full md:w-auto min-w-[240px] h-16 rounded-2xl shadow-xl shadow-primary/20 gap-3 group">
                  <span class="font-bold text-lg">{{ __('Send List Message') }}</span>
                  <span class="material-symbols-outlined transition-transform group-hover:translate-x-1 group-hover:-translate-y-1">send</span>
                </button>
              </div>
            </form>
          </div>

          <!-- Button Message Tab -->
          <div x-show="activeTab === 'button'" x-cloak class="form-section" x-data="{ buttons: [{displaytext: '', type: 'quickReplyButton', action: ''}] }">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
              <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl gradient-bg flex items-center justify-center shadow-xl shadow-primary/30">
                  <span class="material-symbols-outlined text-3xl">smart_button</span>
                </div>
                <div>
                  <h2 class="text-3xl font-black tracking-tight">{{ __('Template Message') }}</h2>
                  <p class="text-base-content/60 font-medium">{{ __('Send message with interactive buttons') }}</p>
                </div>
              </div>
            </div>

            <form method="POST" action="{{ url('/user/sent-whatsapp-custom-text/text-with-template') }}" enctype="multipart/form-data" class="message-send-form space-y-8">
              @csrf
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="form-control w-full">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Select Device') }}</span>
                  </label>
                  <select name="device" class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" required>
                    <option value="">{{ __('Choose device...') }}</option>
                    @foreach($devices ?? [] as $device)
                      <option value="{{ $device->id }}">{{ $device->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-control w-full">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Recipient Number') }}</span>
                  </label>
                  <input type="text" name="phone" class="input input-bordered input-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" placeholder="628xxxxxxxxxx" required />
                </div>
              </div>

              <div class="form-control">
                <label class="label px-1">
                  <span class="label-text font-bold text-base-content/80">{{ __('Media Header (Optional)') }}</span>
                </label>
                <input type="file" name="file" class="file-input file-input-bordered w-full rounded-2xl bg-base-100/50" accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.csv,.txt" />
              </div>

              <div class="form-control">
                <label class="label px-1">
                  <span class="label-text font-bold text-base-content/80">{{ __('Message Body') }}</span>
                </label>
                <textarea name="message" class="textarea textarea-bordered textarea-lg h-32 w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" placeholder="{{ __('Your message content...') }}" required></textarea>
              </div>

              <div class="form-control">
                <label class="label px-1">
                  <span class="label-text font-bold text-base-content/80">{{ __('Footer Text') }}</span>
                </label>
                <input type="text" name="footer_text" class="input input-bordered input-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" maxlength="100" placeholder="{{ __('Footer text...') }}" required />
              </div>

              <div class="space-y-6">
                <div class="flex items-center justify-between px-1">
                  <h3 class="text-xl font-black tracking-tight">{{ __('Template Buttons') }}</h3>
                  <span class="badge badge-primary badge-lg font-bold">{{ __('Max 3') }}</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <template x-for="(button, index) in buttons" :key="index">
                    <div class="bg-base-200/30 rounded-3xl p-6 border border-base-300 relative group/btn">
                      <button type="button" @click="buttons.splice(index, 1)" class="absolute -top-2 -right-2 btn btn-error btn-xs btn-circle shadow-lg opacity-0 group-hover/btn:opacity-100 transition-opacity" x-show="buttons.length > 1">
                        <span class="material-symbols-outlined text-xs">close</span>
                      </button>

                      <div class="space-y-4">
                        <select x-model="button.type" :name="'buttons['+index+'][type]'" class="select select-bordered select-sm w-full rounded-xl bg-base-100" required>
                          <option value="quickReplyButton">{{ __('Quick Reply') }}</option>
                          <option value="urlButton">{{ __('URL Link') }}</option>
                          <option value="callButton">{{ __('Call Phone') }}</option>
                          <option value="copyCodeButton">{{ __('Copy Code') }}</option>
                        </select>

                        <div class="flex gap-2">
                          <input type="text" x-model="button.icon" :name="'buttons['+index+'][icon]'" class="input input-bordered input-sm w-12 text-center rounded-xl bg-base-100" placeholder="🔤" maxlength="5" />
                          <input type="text" x-model="button.displaytext" :name="'buttons['+index+'][displaytext]'" class="input input-bordered input-sm flex-1 rounded-xl bg-base-100" placeholder="{{ __('Label') }}" maxlength="50" required />
                        </div>
                        
                        <input type="text" x-model="button.action" :name="'buttons['+index+'][action]'" class="input input-bordered input-sm w-full rounded-xl bg-base-100" :placeholder="button.type === 'urlButton' ? 'https://...' : (button.type === 'callButton' ? '628...' : 'Value')" :required="button.type !== 'quickReplyButton'" maxlength="50" />
                      </div>
                    </div>
                  </template>

                  <button type="button" @click="buttons.push({displaytext: '', type: 'quickReplyButton', action: ''})" class="btn btn-outline h-auto py-8 border-dashed border-2 rounded-3xl gap-3 hover:bg-primary/5 hover:border-primary transition-all" x-show="buttons.length < 3">
                    <span class="material-symbols-outlined">add_circle</span>
                    <span class="font-bold">{{ __('Add Button') }}</span>
                  </button>
                </div>
              </div>

              <div class="pt-8">
                <button type="submit" class="btn btn-primary btn-lg w-full md:w-auto min-w-[240px] h-16 rounded-2xl shadow-xl shadow-primary/20 gap-3 group">
                  <span class="font-bold text-lg">{{ __('Send Template') }}</span>
                  <span class="material-symbols-outlined transition-transform group-hover:translate-x-1 group-hover:-translate-y-1">send</span>
                </button>
              </div>
            </form>
          </div>

          <!-- Poll Message Tab -->
          <div x-show="activeTab === 'poll'" x-cloak class="form-section" x-data="{ options: ['', ''] }">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
              <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl gradient-bg flex items-center justify-center shadow-xl shadow-primary/30">
                  <span class="material-symbols-outlined text-3xl">poll</span>
                </div>
                <div>
                  <h2 class="text-3xl font-black tracking-tight">{{ __('Poll Message') }}</h2>
                  <p class="text-base-content/60 font-medium">{{ __('Create interactive polls for your audience') }}</p>
                </div>
              </div>
            </div>

            <form method="POST" action="{{ url('/user/sent-whatsapp-custom-text/text-with-poll') }}" class="message-send-form space-y-8">
              @csrf
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="form-control w-full">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Select Device') }}</span>
                  </label>
                  <select name="device" class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" required>
                    <option value="">{{ __('Choose device...') }}</option>
                    @foreach($devices ?? [] as $device)
                      <option value="{{ $device->id }}">{{ $device->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-control w-full">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Recipient Number') }}</span>
                  </label>
                  <input type="text" name="phone" class="input input-bordered input-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" placeholder="628xxxxxxxxxx" required />
                </div>
              </div>

              <div class="form-control">
                <label class="label px-1">
                  <span class="label-text font-bold text-base-content/80">{{ __('Question') }}</span>
                </label>
                <input type="text" name="message" class="input input-bordered input-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" placeholder="{{ __('What is your question?') }}" required maxlength="255" />
              </div>

              <div class="space-y-4">
                <div class="flex items-center justify-between px-1">
                  <h3 class="text-lg font-bold">{{ __('Poll Options') }}</h3>
                  <span class="badge badge-ghost font-bold">{{ __('Min 2') }}</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <template x-for="(option, index) in options" :key="index">
                    <div class="flex gap-2 group/opt">
                      <input type="text" :name="'options[]'" x-model="options[index]" class="input input-bordered w-full rounded-xl bg-base-100" :placeholder="'Option ' + (index + 1)" required maxlength="100" />
                      <button type="button" @click="options.splice(index, 1)" class="btn btn-ghost btn-circle btn-sm text-error opacity-0 group-hover/opt:opacity-100 transition-opacity" x-show="options.length > 2">
                        <span class="material-symbols-outlined text-sm">delete</span>
                      </button>
                    </div>
                  </template>
                </div>

                <button type="button" @click="options.push('')" class="btn btn-ghost btn-sm gap-2 text-primary font-bold hover:bg-primary/10 rounded-xl" x-show="options.length < 12">
                  <span class="material-symbols-outlined text-sm">add_circle</span>
                  {{ __('Add Option') }}
                </button>
              </div>

              <div class="form-control">
                <label class="label cursor-pointer justify-start gap-4 p-4 bg-base-200/50 rounded-2xl border border-base-300">
                  <input type="checkbox" name="selectable_count" value="1" class="checkbox checkbox-primary rounded-lg" />
                  <span class="label-text font-bold text-base-content/70">{{ __('Allow multiple selection') }}</span>
                </label>
              </div>

              <div class="pt-8">
                <button type="submit" class="btn btn-primary btn-lg w-full md:w-auto min-w-[240px] h-16 rounded-2xl shadow-xl shadow-primary/20 gap-3 group">
                  <span class="font-bold text-lg">{{ __('Send Poll') }}</span>
                  <span class="material-symbols-outlined transition-transform group-hover:translate-x-1 group-hover:-translate-y-1">send</span>
                </button>
              </div>
            </form>
          </div>

          <!-- Location Tab -->
          <div x-show="activeTab === 'location'" x-cloak class="form-section">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
              <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl gradient-bg flex items-center justify-center shadow-xl shadow-primary/30">
                  <span class="material-symbols-outlined text-3xl">location_on</span>
                </div>
                <div>
                  <h2 class="text-3xl font-black tracking-tight">{{ __('Send Location') }}</h2>
                  <p class="text-base-content/60 font-medium">{{ __('Send GPS coordinates location') }}</p>
                </div>
              </div>
            </div>

            <form method="POST" action="{{ url('/user/sent-whatsapp-custom-text/text-with-location') }}" class="message-send-form space-y-8">
              @csrf
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="form-control w-full">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Select Device') }}</span>
                  </label>
                  <select name="device" class="select select-bordered select-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" required>
                    <option value="">{{ __('Choose device...') }}</option>
                    @foreach($devices ?? [] as $device)
                      <option value="{{ $device->id }}">{{ $device->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-control w-full">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Recipient Number') }}</span>
                  </label>
                  <input type="text" name="phone" class="input input-bordered input-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" placeholder="628xxxxxxxxxx" required />
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="form-control">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Latitude') }}</span>
                  </label>
                  <input type="text" name="degreesLatitude" class="input input-bordered input-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" placeholder="-6.200000" required />
                </div>

                <div class="form-control">
                  <label class="label px-1">
                    <span class="label-text font-bold text-base-content/80">{{ __('Longitude') }}</span>
                  </label>
                  <input type="text" name="degreesLongitude" class="input input-bordered input-lg w-full rounded-2xl bg-base-100/50 focus:bg-base-100 transition-all border-base-300" placeholder="106.816666" required />
                </div>
              </div>

              <div class="p-6 bg-info/10 rounded-3xl border border-info/20 flex gap-4 items-start">
                <span class="material-symbols-outlined text-info">info</span>
                <div>
                  <p class="text-sm font-bold text-info uppercase tracking-wider mb-1">{{ __('Coordinate Tip') }}</p>
                  <p class="text-sm text-info/80 leading-relaxed">{{ __('Example: Jakarta coordinates are -6.200000, 106.816666. You can find these on Google Maps.') }}</p>
                </div>
              </div>

              <div class="pt-8">
                <button type="submit" class="btn btn-primary btn-lg w-full md:w-auto min-w-[240px] h-16 rounded-2xl shadow-xl shadow-primary/20 gap-3 group">
                  <span class="font-bold text-lg">{{ __('Send Location') }}</span>
                  <span class="material-symbols-outlined transition-transform group-hover:translate-x-1 group-hover:-translate-y-1">send</span>
                </button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>

  </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets/vendor/select2/dist/js/select2.full.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('.select2').select2();
    
    $('.message-send-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalContent = submitBtn.html();
        
        submitBtn.prop('disabled', true);
        submitBtn.html(`
            <span class="loading loading-spinner loading-md"></span>
            <span class="font-bold uppercase tracking-widest text-sm">{{ __('Processing...') }}</span>
        `);
        
        const formData = new FormData(this);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    title: '{{ __('Success!') }}',
                    text: response.message || '{{ __('Message sent successfully!') }}',
                    icon: 'success',
                    background: '#fff',
                    borderRadius: '24px',
                    confirmButtonColor: 'var(--p)',
                    customClass: {
                        popup: 'rounded-3xl',
                        confirmButton: 'rounded-xl px-8 py-3 font-bold'
                    }
                });
                
                if (!form.find('input[name="phone"]').val().includes(',')) {
                    form[0].reset();
                }
                
                submitBtn.prop('disabled', false);
                submitBtn.html(originalContent);
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || '{{ __('Failed to send message.') }}';
                
                Swal.fire({
                    title: '{{ __('Error!') }}',
                    text: message,
                    icon: 'error',
                    background: '#fff',
                    borderRadius: '24px',
                    confirmButtonColor: '#ef4444',
                    customClass: {
                        popup: 'rounded-3xl',
                        confirmButton: 'rounded-xl px-8 py-3 font-bold'
                    }
                });
                
                submitBtn.prop('disabled', false);
                submitBtn.html(originalContent);
            }
        });
    });
});
</script>
@endpush
