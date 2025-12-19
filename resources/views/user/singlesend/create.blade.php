@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Send Message')])
@endsection

@push('topcss')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select2/dist/css/select2.min.css') }}">
<style>
[x-cloak] { display: none !important; }
.form-section { @apply space-y-6; }
.form-row { @apply grid grid-cols-1 md:grid-cols-2 gap-4; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
  
  <!-- Page Header -->
  <div class="mb-8">
    <h1 class="text-3xl font-bold text-base-content mb-2">{{ __('Send Custom Message') }}</h1>
    <p class="text-base-content/60 text-lg">{{ __('Send WhatsApp messages using different formats') }}</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6" x-data="{ activeTab: 'plain' }">
    
    <!-- Sidebar Navigation -->
    <div class="lg:col-span-1">
      <div class="card bg-white shadow-xl sticky top-6">
        <div class="card-body p-6">
          <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
            <span class="material-icons text-primary">category</span>
            {{ __('Message Type') }}
          </h3>
          <ul class="menu w-full space-y-1">
            <li>
              <a @click="activeTab = 'plain'" :class="activeTab === 'plain' ? 'active bg-primary text-primary-content' : ''" class="gap-3 rounded-lg">
                <span class="material-icons text-lg">text_fields</span>
                <span class="flex-1">{{ __('Plain Text') }}</span>
              </a>
            </li>
            <li>
              <a @click="activeTab = 'media'" :class="activeTab === 'media' ? 'active bg-primary text-primary-content' : ''" class="gap-3 rounded-lg">
                <span class="material-icons text-lg">image</span>
                <span class="flex-1">{{ __('With Media') }}</span>
              </a>
            </li>
            <li>
              <a @click="activeTab = 'button'" :class="activeTab === 'button' ? 'active bg-primary text-primary-content' : ''" class="gap-3 rounded-lg">
                <span class="material-icons text-lg">smart_button</span>
                <span class="flex-1">{{ __('With Button') }}</span>
              </a>
            </li>
            <li>
              <a @click="activeTab = 'list'" :class="activeTab === 'list' ? 'active bg-primary text-primary-content' : ''" class="gap-3 rounded-lg">
                <span class="material-icons text-lg">list</span>
                <span class="flex-1">{{ __('List Message') }}</span>
              </a>
            </li>
            <li>
              <a @click="activeTab = 'poll'" :class="activeTab === 'poll' ? 'active bg-primary text-primary-content' : ''" class="gap-3 rounded-lg">
                <span class="material-icons text-lg">poll</span>
                <span class="flex-1">{{ __('Poll Message') }}</span>
              </a>
            </li>
            <li>
              <a @click="activeTab = 'location'" :class="activeTab === 'location' ? 'active bg-primary text-primary-content' : ''" class="gap-3 rounded-lg">
                <span class="material-icons text-lg">location_on</span>
                <span class="flex-1">{{ __('Location') }}</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="lg:col-span-3">
      <div class="card bg-white shadow-xl">
        <div class="card-body p-8">
          
          <!-- Plain Text Tab -->
          <div x-show="activeTab === 'plain'" x-cloak class="form-section">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <span class="material-icons text-primary text-2xl">text_fields</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold">{{ __('Plain Text Message') }}</h2>
                <p class="text-base-content/60">{{ __('Send simple text message') }}</p>
              </div>
            </div>

            <form method="POST" action="{{ url('/user/sent-whatsapp-custom-text/plain-text') }}" class="message-send-form space-y-6">
              @csrf
              
              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Select Device') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <select name="device" class="select select-bordered w-full" required>
                  <option value="">{{ __('Choose device...') }}</option>
                  @foreach($devices ?? [] as $device)
                    <option value="{{ $device->id }}">{{ $device->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Phone Number') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <input type="text" name="phone" class="input input-bordered w-full" placeholder="628xxxxxxxxxx" required />
                <label class="label">
                  <span class="label-text-alt">{{ __('Example: 628123456789') }}</span>
                </label>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Message') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <textarea name="message" class="textarea textarea-bordered h-40 w-full" placeholder="{{ __('Type your message here...') }}" required></textarea>
              </div>

              <div class="divider"></div>

              <div class="flex justify-end gap-3">
                <button type="submit" class="btn btn-primary btn-lg gap-2 min-w-[200px]">
                  <span class="material-icons">send</span>
                  {{ __('Send Message') }}
                </button>
              </div>
            </form>
          </div>

          <!-- Text With Media Tab -->
          <div x-show="activeTab === 'media'" x-cloak class="form-section">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <span class="material-icons text-primary text-2xl">image</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold">{{ __('Text With Media') }}</h2>
                <p class="text-base-content/60">{{ __('Send image or video with caption') }}</p>
              </div>
            </div>

            <form method="POST" action="{{ url('/user/sent-whatsapp-custom-text/text-with-media') }}" enctype="multipart/form-data" class="message-send-form space-y-6">
              @csrf
              
              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Select Device') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <select name="device" class="select select-bordered w-full" required>
                  <option value="">{{ __('Choose device...') }}</option>
                  @foreach($devices ?? [] as $device)
                    <option value="{{ $device->id }}">{{ $device->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Phone Number') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <input type="text" name="phone" class="input input-bordered w-full" placeholder="628xxxxxxxxxx" required />
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Media File') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <input type="file" name="file" class="file-input file-input-bordered w-full" accept="image/*,video/*,.pdf" required />
                <label class="label">
                  <span class="label-text-alt">{{ __('Accepted: Images, Videos, PDF (Max 10MB)') }}</span>
                </label>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Caption') }}</span>
                </label>
                <textarea name="message" class="textarea textarea-bordered h-32 w-full"placeholder="{{ __('Optional caption for your media...') }}"></textarea>
              </div>

              <div class="divider"></div>

              <div class="flex justify-end gap-3">
                <button type="submit" class="btn btn-primary btn-lg gap-2 min-w-[200px]">
                  <span class="material-icons">send</span>
                  {{ __('Send Message') }}
                </button>
              </div>
            </form>
          </div>


          <!-- List Message Tab -->
          <div x-show="activeTab === 'list'" x-cloak class="form-section" x-data="{ sections: [{title: '', rows: [{title: '', description: ''}]}] }">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <span class="material-icons text-primary text-2xl">list</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold">{{ __('List Message') }}</h2>
                <p class="text-base-content/60">{{ __('Send interactive list with multiple options') }}</p>
              </div>
            </div>

            <form method="POST" action="{{ url('/user/sent-whatsapp-custom-text/text-with-list') }}" class="message-send-form space-y-6">
              @csrf
              
              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Select Device') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <select name="device" class="select select-bordered w-full" required>
                  <option value="">{{ __('Choose device...') }}</option>
                  @foreach($devices ?? [] as $device)
                    <option value="{{ $device->id }}">{{ $device->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Phone Number') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <input type="text" name="phone" class="input input-bordered w-full" placeholder="628xxxxxxxxxx" required />
              </div>

              <div class="divider my-6"></div>

              <div class="form-row">
                <div class="form-control">
                  <label class="label">
                    <span class="label-text font-semibold text-base">{{ __('Header Title') }}</span>
                    <span class="label-text-alt text-error">*</span>
                  </label>
                  <input type="text" name="header_title" class="input input-bordered w-full" maxlength="30" placeholder="{{ __('List header') }}" required />
                </div>

                <div class="form-control">
                  <label class="label">
                    <span class="label-text font-semibold text-base">{{ __('Button Text') }}</span>
                    <span class="label-text-alt text-error">*</span>
                  </label>
                  <input type="text" name="button_text" class="input input-bordered w-full" maxlength="30" placeholder="{{ __('View options') }}" required />
                </div>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Message') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <textarea name="message" class="textarea textarea-bordered h-24 w-full" maxlength="300" placeholder="{{ __('Your list description...') }}" required></textarea>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Footer Text') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <input type="text" name="footer_text" class="input input-bordered w-full" maxlength="30" placeholder="{{ __('Footer text') }}" required />
              </div>

              <div class="divider my-6"></div>

              <div class="form-control">
                <label class="label mb-3">
                  <span class="label-text font-semibold text-lg">{{ __('List Sections') }}</span>
                  <span class="badge badge-primary">{{ __('Max 20') }}</span>
                </label>

                <template x-for="(section, sIndex) in sections" :key="sIndex">
                  <div class="card bg-base-100 border-2 border-base-300 p-6 mb-4">
                    <div class="flex items-center justify-between mb-4">
                      <div class="flex items-center gap-2">
                        <span class="material-icons text-primary">folder</span>
                        <span class="font-bold text-lg">{{ __('Section') }} <span x-text="sIndex + 1"></span></span>
                      </div>
                      <button type="button" @click="sections.splice(sIndex, 1)" class="btn btn-ghost btn-sm btn-circle" x-show="sections.length > 1">
                        <span class="material-icons">close</span>
                      </button>
                    </div>

                    <input type="text" x-model="section.title" :name="'section['+sIndex+'][title]'" class="input input-bordered w-full mb-4" placeholder="{{ __('Section title') }}" maxlength="50" required />
                    
                    <div class="space-y-3">
                      <template x-for="(row, rIndex) in section.rows" :key="rIndex">
                        <div class="flex gap-3">
                          <input type="text" x-model="row.title" :name="'section['+sIndex+'][value]['+rIndex+'][title]'" class="input input-bordered flex-1" placeholder="{{ __('Option title') }}" maxlength="50" required />
                          <input type="text" x-model="row.description" :name="'section['+sIndex+'][value]['+rIndex+'][description]'" class="input input-bordered flex-1" placeholder="{{ __('Description') }}" maxlength="50" />
                          <button type="button" @click="section.rows.splice(rIndex, 1)" class="btn btn-ghost btn-sm btn-circle" x-show="section.rows.length > 1">
                            <span class="material-icons">close</span>
                          </button>
                        </div>
                      </template>
                    </div>
                    
                    <button type="button" @click="section.rows.push({title: '', description: ''})" class="btn btn-outline btn-sm gap-2 mt-4">
                      <span class="material-icons text-sm">add</span>
                      {{ __('Add Row') }}
                    </button>
                  </div>
                </template>

                <button type="button" @click="sections.push({title: '', rows: [{title: '', description: ''}]})" class="btn btn-outline gap-2" x-show="sections.length < 20">
                  <span class="material-icons">add</span>
                  {{ __('Add Section') }}
                </button>
              </div>

              <div class="divider"></div>

              <div class="flex justify-end gap-3">
                <button type="submit" class="btn btn-primary btn-lg gap-2 min-w-[200px]">
                  <span class="material-icons">send</span>
                  {{ __('Send List') }}
                </button>
              </div>
            </form>
          </div>

          <!-- Button Message Tab -->
          <div x-show="activeTab === 'button'" x-cloak class="form-section" x-data="{ buttons: [{displaytext: '', type: 'quickReplyButton', action: ''}] }">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <span class="material-icons text-primary text-2xl">web_stories</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold">{{ __('Template Message') }}</h2>
                <p class="text-base-content/60">{{ __('Send message with interactive buttons') }}</p>
              </div>
            </div>

            <form method="POST" action="{{ url('/user/sent-whatsapp-custom-text/text-with-template') }}" enctype="multipart/form-data" class="message-send-form space-y-6">
              @csrf
              
              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Select Device') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <select name="device" class="select select-bordered w-full" required>
                  <option value="">{{ __('Choose device...') }}</option>
                  @foreach($devices ?? [] as $device)
                    <option value="{{ $device->id }}">{{ $device->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Media Header') }}</span>
                  <span class="label-text-alt text-base-content/50">{{ __('Optional (Image, Video, Document)') }}</span>
                </label>
                <input type="file" name="file" class="file-input file-input-bordered w-full" accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.csv,.txt" />
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Phone Number') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <input type="text" name="phone" class="input input-bordered w-full" placeholder="628xxxxxxxxxx" required />
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Message') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <textarea name="message" class="textarea textarea-bordered h-32 w-full" placeholder="{{ __('Your message content...') }}" required></textarea>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Footer Text') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <input type="text" name="footer_text" class="input input-bordered w-full" maxlength="100" placeholder="{{ __('Footer text...') }}" required />
              </div>

              <div class="divider my-6"></div>

              <div class="form-control">
                <label class="label mb-3">
                  <span class="label-text font-semibold text-lg">{{ __('Template Buttons') }}</span>
                  <div class="flex gap-2">
                    <span class="badge badge-primary">{{ __('Max 3') }}</span>
                    <span class="badge badge-ghost text-xs">{{ __('Use emojis for icons') }}</span>
                  </div>
                </label>

                <template x-for="(button, index) in buttons" :key="index">
                  <div class="card bg-base-100 border-2 border-base-300 p-5 mb-4">
                    <div class="flex items-center justify-between mb-4">
                      <div class="flex items-center gap-2">
                        <span class="material-icons text-primary">link</span>
                        <span class="font-bold">{{ __('Button') }} <span x-text="index + 1"></span></span>
                      </div>
                      <button type="button" @click="buttons.splice(index, 1)" class="btn btn-ghost btn-xs btn-circle" x-show="buttons.length > 1">
                        <span class="material-icons text-sm">close</span>
                      </button>
                    </div>

                    <div class="space-y-3">
                      <select x-model="button.type" :name="'buttons['+index+'][type]'" class="select select-bordered w-full" required>
                        <option value="quickReplyButton">{{ __('Quick Reply Button') }}</option>
                        <option value="urlButton">{{ __('URL Button') }}</option>
                        <option value="callButton">{{ __('Call Button') }}</option>
                        <option value="copyCodeButton">{{ __('Copy Code Button') }}</option>
                      </select>

                      <div class="flex gap-2">
                        <input type="text" x-model="button.icon" :name="'buttons['+index+'][icon]'" class="input input-bordered w-20 text-center" placeholder="Emoji" maxlength="5" />
                        <input type="text" x-model="button.displaytext" :name="'buttons['+index+'][displaytext]'" class="input input-bordered flex-1" placeholder="{{ __('Button text') }}" maxlength="50" required />
                      </div>
                      
                      <input type="text" x-model="button.action" :name="'buttons['+index+'][action]'" class="input input-bordered w-full" :placeholder="button.type === 'urlButton' ? 'https://example.com' : (button.type === 'callButton' ? '628xxx' : (button.type === 'copyCodeButton' ? 'Code to copy' : 'Optional'))" :required="button.type !== 'quickReplyButton'" maxlength="50" />
                    </div>
                  </div>
                </template>

                <button type="button" @click="buttons.push({displaytext: '', type: 'quickReplyButton', action: ''})" class="btn btn-outline gap-2" x-show="buttons.length < 3">
                  <span class="material-icons">add</span>
                  {{ __('Add Button') }}
                </button>
              </div>

              <div class="divider"></div>

              <div class="flex justify-end gap-3">
                <button type="submit" class="btn btn-primary btn-lg gap-2 min-w-[200px]">
                  <span class="material-icons">send</span>
                  {{ __('Send Template') }}
                </button>
              </div>
            </form>
          </div>

          <!-- Poll Message Tab -->
          <div x-show="activeTab === 'poll'" x-cloak class="form-section" x-data="{ options: ['', ''] }">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <span class="material-icons text-primary text-2xl">poll</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold">{{ __('Poll Message') }}</h2>
                <p class="text-base-content/60">{{ __('Create a poll with multiple options') }}</p>
              </div>
            </div>

            <form method="POST" action="{{ url('/user/sent-whatsapp-custom-text/text-with-poll') }}" class="message-send-form space-y-6">
              @csrf
              
              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Select Device') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <select name="device" class="select select-bordered w-full" required>
                  <option value="">{{ __('Choose device...') }}</option>
                  @foreach($devices ?? [] as $device)
                    <option value="{{ $device->id }}">{{ $device->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Phone Number') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <input type="text" name="phone" class="input input-bordered w-full" placeholder="628xxxxxxxxxx" required />
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Question') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <input type="text" name="message" class="input input-bordered w-full" placeholder="{{ __('What is your question?') }}" required maxlength="255" />
              </div>

              <div class="form-control">
                <label class="label mb-3">
                  <span class="label-text font-semibold text-lg">{{ __('Poll Options') }}</span>
                  <span class="badge badge-primary">{{ __('Min 2') }}</span>
                </label>

                <div class="space-y-3 mb-4">
                  <template x-for="(option, index) in options" :key="index">
                    <div class="flex gap-2">
                      <input type="text" :name="'options[]'" x-model="options[index]" class="input input-bordered flex-1" :placeholder="'Option ' + (index + 1)" required maxlength="100" />
                      <button type="button" @click="options.splice(index, 1)" class="btn btn-ghost btn-circle btn-sm" x-show="options.length > 2">
                        <span class="material-icons text-error">delete</span>
                      </button>
                    </div>
                  </template>
                </div>

                <button type="button" @click="options.push('')" class="btn btn-outline btn-sm gap-2 w-fit" x-show="options.length < 10">
                  <span class="material-icons text-sm">add</span>
                  {{ __('Add Option') }}
                </button>
              </div>

              <div class="form-control">
                <label class="label cursor-pointer justify-start gap-3">
                  <input type="checkbox" name="selectable_count" value="1" class="checkbox checkbox-primary" />
                  <span class="label-text font-medium">{{ __('Allow multiple selection') }}</span>
                </label>
              </div>

              <div class="divider"></div>

              <div class="flex justify-end gap-3">
                <button type="submit" class="btn btn-primary btn-lg gap-2 min-w-[200px]">
                  <span class="material-icons">send</span>
                  {{ __('Send Poll') }}
                </button>
              </div>
            </form>
          </div>

          <!-- Location Tab -->
          <div x-show="activeTab === 'location'" x-cloak class="form-section">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <span class="material-icons text-primary text-2xl">location_on</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold">{{ __('Send Location') }}</h2>
                <p class="text-base-content/60">{{ __('Send GPS coordinates location') }}</p>
              </div>
            </div>

            <form method="POST" action="{{ url('/user/sent-whatsapp-custom-text/text-with-location') }}" class="message-send-form space-y-6">
              @csrf
              
              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Select Device') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <select name="device" class="select select-bordered w-full" required>
                  <option value="">{{ __('Choose device...') }}</option>
                  @foreach($devices ?? [] as $device)
                    <option value="{{ $device->id }}">{{ $device->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold text-base">{{ __('Phone Number') }}</span>
                  <span class="label-text-alt text-error">*</span>
                </label>
                <input type="text" name="phone" class="input input-bordered w-full" placeholder="628xxxxxxxxxx" required />
              </div>

              <div class="divider my-6"></div>

              <div class="form-row">
                <div class="form-control">
                  <label class="label">
                    <span class="label-text font-semibold text-base">{{ __('Latitude') }}</span>
                    <span class="label-text-alt text-error">*</span>
                  </label>
                  <input type="text" name="degreesLatitude" class="input input-bordered w-full" placeholder="-6.200000" required />
                </div>

                <div class="form-control">
                  <label class="label">
                    <span class="label-text font-semibold text-base">{{ __('Longitude') }}</span>
                    <span class="label-text-alt text-error">*</span>
                  </label>
                  <input type="text" name="degreesLongitude" class="input input-bordered w-full" placeholder="106.816666" required />
                </div>
              </div>

              <div class="alert alert-info">
                <span class="material-icons">info</span>
                <span class="text-sm">{{ __('Example: Jakarta coordinates are -6.200000, 106.816666') }}</span>
              </div>

              <div class="divider"></div>

              <div class="flex justify-end gap-3">
                <button type="submit" class="btn btn-primary btn-lg gap-2 min-w-[200px]">
                  <span class="material-icons">send</span>
                  {{ __('Send Location') }}
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
    
    // Handle form submissions with notifications
    $('.message-send-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        // Show loading
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="loading loading-spinner loading-sm"></span> Sending...');
        
        // Get form data
        const formData = new FormData(this);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Show success notification
                showNotification('success', response.message || 'Message sent successfully!');
                
                // Reset form
                form[0].reset();
                
                // Reset button
                submitBtn.prop('disabled', false);
                submitBtn.html(originalText);
            },
            error: function(xhr) {
                // Show error notification
                const message = xhr.responseJSON?.message || 'Failed to send message. Please try again.';
                showNotification('error', message);
                
                // Reset button
                submitBtn.prop('disabled', false);
                submitBtn.html(originalText);
            }
        });
    });
    
    // Notification function
    function showNotification(type, message) {
        // Create toast container if not exists
        if ($('#toast-container').length === 0) {
            $('body').append('<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>');
        }
        
        const bgColor = type === 'success' ? 'bg-success' : 'bg-error';
        const icon = type === 'success' ? 'check_circle' : 'error';
        
        const toast = $(`
            <div class="alert ${bgColor} text-white shadow-lg animate-slide-in-right max-w-md">
                <div>
                    <span class="material-icons">${icon}</span>
                    <span>${message}</span>
                </div>
            </div>
        `);
        
        $('#toast-container').append(toast);
        
        // Auto remove after 5 seconds
        setTimeout(function() {
            toast.fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }
});
</script>

<style>
@keyframes slide-in-right {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.animate-slide-in-right {
    animation: slide-in-right 0.3s ease-out;
}
</style>
@endpush
