@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Automatic Replies')])
@endsection

@section('content')

<div class="max-w-7xl mx-auto" x-data="{ 
  showCreateModal: false, 
  showEditModal: false, 
  editData: {
    id: null,
    keyword: '',
    device: '',
    reply_type: 'text',
    match_type: 'equal',
    reply: '',
    caption: '',
    latitude: '',
    longitude: '',
    template_id: '',
    buttons: []
  },
  createButtons: [{displaytext: '', type: 'quickReplyButton', action: ''}],
  
  addCreateButton() {
    if (this.createButtons.length < 3) {
      this.createButtons.push({displaytext: '', type: 'quickReplyButton', action: ''});
    }
  },
  removeCreateButton(index) {
    this.createButtons.splice(index, 1);
  },
  
  addEditButton() {
    if (!this.editData.buttons) this.editData.buttons = [];
    if (this.editData.buttons.length < 3) {
      this.editData.buttons.push({displaytext: '', type: 'quickReplyButton', action: ''});
    }
  },
  removeEditButton(index) {
    this.editData.buttons.splice(index, 1);
  }
}">
  
  <!-- Page Header -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-xl md:text-2xl break-words font-bold text-base-content mb-2">{{ __('Automatic Replies') }}</h1>
      <p class="text-base-content/60">{{ __('Set up chatbot auto-reply rules for incoming messages') }}</p>
    </div>
    <button @click="showCreateModal = true; createButtons = [{displaytext: '', type: 'quickReplyButton', action: ''}]" class="btn btn-primary gap-2 rounded-full">
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
          <h3 class="text-xl md:text-2xl break-words md:text-3xl break-words font-bold text-gray-900">{{ number_format($total_replies) }}</h3>
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
          <h3 class="text-xl md:text-2xl break-words md:text-3xl break-words font-bold text-gray-900">{{ number_format($template_replies) }}</h3>
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
          <h3 class="text-xl md:text-2xl break-words md:text-3xl break-words font-bold text-gray-900">{{ number_format($text_replies) }}</h3>
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
    <a href="{{ url('/pricing') }}" class="btn btn-sm btn-primary rounded-full">{{ __('Upgrade Plan') }}</a>
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
    <button @click="showCreateModal = true" class="btn btn-primary gap-2 rounded-full">
      <span class="material-icons text-sm">add</span>
      {{ __('Create Reply') }}
    </button>
  </div>
  @else
  <!-- Replies Table -->
  <div class="card bg-white shadow-xl !overflow-visible">
    <div class="card-body p-0 !overflow-visible">
      <div class="overflow-x-auto pb-40">
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
                  <div class="flex items-center justify-end gap-2">
                    <a href="javascript:void(0)" 
                       @click="editData = {
                         action: '{{ route('user.chatbot.update', $reply->id) }}',
                         keyword: '{{ $reply->keyword }}',
                         device: '{{ $reply->device_id }}',
                         reply_type: '{{ $reply->reply_type }}',
                         match_type: '{{ $reply->match_type }}',
                         reply: `{{ in_array($reply->reply_type, ['image', 'video', 'audio', 'document', 'location', 'button']) ? (optional(json_decode($reply->reply))->message ?? '') : $reply->reply }}`,
                         caption: `{{ in_array($reply->reply_type, ['image', 'video']) ? (optional(json_decode($reply->reply))->caption ?? '') : '' }}`,
                         latitude: `{{ $reply->reply_type == 'location' ? (optional(json_decode($reply->reply))->latitude ?? '') : '' }}`,
                         longitude: `{{ $reply->reply_type == 'location' ? (optional(json_decode($reply->reply))->longitude ?? '') : '' }}`,
                         footer: `{{ $reply->reply_type == 'button' ? (optional(json_decode($reply->reply))->footer ?? '') : '' }}`,
                         buttons: {{ $reply->reply_type == 'button' ? json_encode(optional(json_decode($reply->reply))->buttons ?? []) : '[]' }},
                         template_id: '{{ $reply->template_id }}'
                       }; showEditModal = true"
                       class="btn btn-sm btn-ghost text-primary"
                       title="{{ __('Edit') }}">
                      <span class="material-icons text-sm">edit</span>
                    </a>
                    
                    <a href="javascript:void(0)" 
                       class="delete-confirm btn btn-sm btn-ghost text-red-600 rounded-full" 
                       data-action="{{ route('user.chatbot.destroy', $reply->id) }}"
                       title="{{ __('Delete') }}">
                      <span class="material-icons text-sm">delete</span>
                    </a>
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
      
      <form method="POST" action="{{ route('user.chatbot.store') }}" class="ajaxform_instant_reload space-y-4" enctype="multipart/form-data">
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
              <option value="button">{{ __('Button') }}</option>
              <option value="image">{{ __('Image') }}</option>
              <option value="audio">{{ __('Audio') }}</option>
              <option value="video">{{ __('Video') }}</option>
              <option value="document">{{ __('Document') }}</option>
              <option value="location">{{ __('Location') }}</option>
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

        <div class="form-control button-area hidden">
           <div class="form-control mb-4">
              <label class="label">
                <span class="label-text font-medium">{{ __('Message Body') }}</span>
              </label>
              <textarea class="textarea textarea-bordered h-24" name="button_message" maxlength="1000" placeholder="Main message content..."></textarea>
           </div>
           
           <div class="form-control mb-4">
              <label class="label">
                <span class="label-text font-medium">{{ __('Footer Text') }}</span>
              </label>
              <input type="text" name="footer_text" class="input input-bordered w-full" maxlength="50" placeholder="Footer text..." />
           </div>

           <div class="form-control mb-4">
              <label class="label">
                <span class="label-text font-medium">{{ __('Header Image (Optional)') }}</span>
              </label>
              <input type="file" name="button_image" class="file-input file-input-bordered w-full" accept="image/*" />
           </div>

           <div class="divider">{{ __('Buttons') }}</div>
           
           <div class="space-y-4">
              <template x-for="(button, index) in createButtons" :key="index">
                 <div class="bg-base-200 p-4 rounded-xl relative group">
                    <button type="button" @click="removeCreateButton(index)" class="absolute top-2 right-2 btn btn-xs btn-circle btn-error opacity-0 group-hover:opacity-100 transition-opacity rounded-full" x-show="createButtons.length > 1">✕</button>
                    
                    <div class="grid grid-cols-1 gap-2">
                       <select x-model="button.type" :name="'buttons['+index+'][type]'" class="select select-bordered select-sm w-full">
                          <option value="quickReplyButton">{{ __('Quick Reply') }}</option>
                          <option value="urlButton">{{ __('URL Link') }}</option>
                          <option value="callButton">{{ __('Call Phone') }}</option>
                          <option value="copyCodeButton">{{ __('Copy Code') }}</option>
                       </select>
                       
                       <input type="text" x-model="button.displaytext" :name="'buttons['+index+'][displaytext]'" class="input input-bordered input-sm w-full" placeholder="Button Label" required />
                       
                       <input type="text" x-model="button.action" :name="'buttons['+index+'][action]'" class="input input-bordered input-sm w-full" :placeholder="button.type === 'urlButton' ? 'https://...' : (button.type === 'callButton' ? '62xxx' : 'Value')" :required="button.type !== 'quickReplyButton'" />
                    </div>
                 </div>
              </template>
              
              <button type="button" @click="addCreateButton()" class="btn btn-outline btn-sm w-full border-dashed rounded-full" x-show="createButtons.length < 3">
                 + {{ __('Add Button') }}
              </button>
           </div>
        </div>

        <div class="form-control image-area hidden">
          <label class="label">
            <span class="label-text font-medium">{{ __('Select Image') }}</span>
          </label>
          <input type="file" name="image" class="file-input file-input-bordered w-full" accept="image/*" />
          <div class="label">
             <span class="label-text-alt">{{ __('Supported formats: jpg, jpeg, png, webp') }}</span>
          </div>
          <div class="form-control mt-2">
             <label class="label">
                <span class="label-text font-medium">{{ __('Caption (Optional)') }}</span>
             </label>
             <input type="text" name="caption" class="input input-bordered w-full" placeholder="Image caption..." />
          </div>
        </div>

        <div class="form-control audio-area hidden">
          <label class="label">
            <span class="label-text font-medium">{{ __('Select Audio') }}</span>
          </label>
          <input type="file" name="audio" class="file-input file-input-bordered w-full" accept="audio/*" />
          <div class="label">
             <span class="label-text-alt">{{ __('Supported formats: mp3, ogg, wav') }}</span>
          </div>
        </div>

        <div class="form-control video-area hidden">
          <label class="label">
            <span class="label-text font-medium">{{ __('Select Video') }}</span>
          </label>
          <input type="file" name="video" class="file-input file-input-bordered w-full" accept="video/*" />
          <div class="label">
             <span class="label-text-alt">{{ __('Supported formats: mp4') }}</span>
          </div>
          <div class="form-control mt-2">
             <label class="label">
                <span class="label-text font-medium">{{ __('Caption (Optional)') }}</span>
             </label>
             <input type="text" name="video_caption" class="input input-bordered w-full" placeholder="Video caption..." />
          </div>
        </div>

        <div class="form-control document-area hidden">
          <label class="label">
            <span class="label-text font-medium">{{ __('Select Document') }}</span>
          </label>
          <input type="file" name="document" class="file-input file-input-bordered w-full" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.txt" />
          <div class="label">
             <span class="label-text-alt">{{ __('Supported formats: pdf, doc, xls, etc.') }}</span>
          </div>
        </div>

        <div class="form-control location-area hidden">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text font-medium">{{ __('Latitude') }}</span>
                    </label>
                    <input type="text" name="latitude" class="input input-bordered w-full" placeholder="-6.200000" />
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-medium">{{ __('Longitude') }}</span>
                    </label>
                    <input type="text" name="longitude" class="input input-bordered w-full" placeholder="106.816666" />
                </div>
            </div>
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
          <button type="button" @click="showCreateModal = false" class="btn btn-outline rounded-full">{{ __('Cancel') }}</button>
          <button type="submit" class="btn btn-primary submit-btn gap-2 rounded-full">
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
      
      <form method="POST" :action="editData.action" class="ajaxform_instant_reload space-y-4" enctype="multipart/form-data">
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
              <option value="button">{{ __('Button') }}</option>
              <option value="image">{{ __('Image') }}</option>
              <option value="audio">{{ __('Audio') }}</option>
              <option value="video">{{ __('Video') }}</option>
              <option value="document">{{ __('Document') }}</option>
              <option value="location">{{ __('Location') }}</option>
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

        <div class="form-control" x-show="editData.reply_type === 'button'" id="button-area">
           <div class="form-control mb-4">
              <label class="label">
                <span class="label-text font-medium">{{ __('Message Body') }}</span>
              </label>
              <textarea class="textarea textarea-bordered h-24" name="button_message" x-model="editData.reply" maxlength="1000" placeholder="Main message content..."></textarea>
           </div>
           
           <div class="form-control mb-4">
              <label class="label">
                <span class="label-text font-medium">{{ __('Footer Text') }}</span>
              </label>
              <input type="text" name="footer_text" x-model="editData.footer" class="input input-bordered w-full" maxlength="50" placeholder="Footer text..." />
           </div>

           <div class="form-control mb-4">
              <label class="label">
                <span class="label-text font-medium">{{ __('Header Image (Optional)') }}</span>
              </label>
              <input type="file" name="button_image" class="file-input file-input-bordered w-full" accept="image/*" />
              <div class="label">
                 <span class="label-text-alt">{{ __('Leave empty to keep existing image') }}</span>
              </div>
           </div>

           <div class="divider">{{ __('Buttons') }}</div>
           
           <div class="space-y-4">
              <template x-for="(button, index) in editData.buttons" :key="index">
                 <div class="bg-base-200 p-4 rounded-xl relative group">
                    <button type="button" @click="removeEditButton(index)" class="absolute top-2 right-2 btn btn-xs btn-circle btn-error opacity-0 group-hover:opacity-100 transition-opacity rounded-full" x-show="editData.buttons.length > 1">✕</button>
                    
                    <div class="grid grid-cols-1 gap-2">
                       <select x-model="button.type" :name="'buttons['+index+'][type]'" class="select select-bordered select-sm w-full">
                          <option value="quickReplyButton">{{ __('Quick Reply') }}</option>
                          <option value="urlButton">{{ __('URL Link') }}</option>
                          <option value="callButton">{{ __('Call Phone') }}</option>
                          <option value="copyCodeButton">{{ __('Copy Code') }}</option>
                       </select>
                       
                       <input type="text" x-model="button.displaytext" :name="'buttons['+index+'][displaytext]'" class="input input-bordered input-sm w-full" placeholder="Button Label" required />
                       
                       <input type="text" x-model="button.action" :name="'buttons['+index+'][action]'" class="input input-bordered input-sm w-full" :placeholder="button.type === 'urlButton' ? 'https://...' : (button.type === 'callButton' ? '62xxx' : 'Value')" :required="button.type !== 'quickReplyButton'" />
                    </div>
                 </div>
              </template>
              
              <button type="button" @click="addEditButton()" class="btn btn-outline btn-sm w-full border-dashed rounded-full" x-show="editData.buttons && editData.buttons.length < 3">
                 + {{ __('Add Button') }}
              </button>
           </div>
        </div>

        <div class="form-control" x-show="editData.reply_type === 'image'" id="image-area">
          <label class="label">
            <span class="label-text font-medium">{{ __('Select Image') }}</span>
          </label>
          <input type="file" name="image" class="file-input file-input-bordered w-full" accept="image/*" />
          <div class="label">
             <span class="label-text-alt">{{ __('Leave empty to keep existing image') }}</span>
          </div>
          <div class="form-control mt-2">
             <label class="label">
                <span class="label-text font-medium">{{ __('Caption (Optional)') }}</span>
             </label>
             <input type="text" name="caption" x-model="editData.caption" class="input input-bordered w-full" placeholder="Image caption..." />
          </div>
        </div>

        <div class="form-control" x-show="editData.reply_type === 'audio'" id="audio-area">
          <label class="label">
            <span class="label-text font-medium">{{ __('Select Audio') }}</span>
          </label>
          <input type="file" name="audio" class="file-input file-input-bordered w-full" accept="audio/*" />
          <div class="label">
             <span class="label-text-alt">{{ __('Leave empty to keep existing audio') }}</span>
          </div>
        </div>

        <div class="form-control" x-show="editData.reply_type === 'video'" id="video-area">
          <label class="label">
            <span class="label-text font-medium">{{ __('Select Video') }}</span>
          </label>
          <input type="file" name="video" class="file-input file-input-bordered w-full" accept="video/*" />
          <div class="label">
             <span class="label-text-alt">{{ __('Leave empty to keep existing video') }}</span>
          </div>
          <div class="form-control mt-2">
             <label class="label">
                <span class="label-text font-medium">{{ __('Caption (Optional)') }}</span>
             </label>
             <input type="text" name="video_caption" x-model="editData.caption" class="input input-bordered w-full" placeholder="Video caption..." />
          </div>
        </div>

        <div class="form-control" x-show="editData.reply_type === 'document'" id="document-area">
          <label class="label">
            <span class="label-text font-medium">{{ __('Select Document') }}</span>
          </label>
          <input type="file" name="document" class="file-input file-input-bordered w-full" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.txt" />
          <div class="label">
             <span class="label-text-alt">{{ __('Leave empty to keep existing document') }}</span>
          </div>
        </div>

        <div class="form-control" x-show="editData.reply_type === 'location'" id="location-area">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text font-medium">{{ __('Latitude') }}</span>
                    </label>
                    <input type="text" name="latitude" x-model="editData.latitude" class="input input-bordered w-full" placeholder="-6.200000" />
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-medium">{{ __('Longitude') }}</span>
                    </label>
                    <input type="text" name="longitude" x-model="editData.longitude" class="input input-bordered w-full" placeholder="106.816666" />
                </div>
            </div>
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
          <button type="button" @click="showEditModal = false" class="btn btn-outline rounded-full">{{ __('Cancel') }}</button>
          <button type="submit" class="btn btn-primary submit-btn gap-2 rounded-full">
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
    const buttonArea = form.querySelector('.button-area');
    const imageArea = form.querySelector('.image-area');
    const audioArea = form.querySelector('.audio-area');
    const videoArea = form.querySelector('.video-area');
    const documentArea = form.querySelector('.document-area');
    const locationArea = form.querySelector('.location-area');
    
    // Hide all first
    if (textArea) textArea.classList.add('hidden');
    if (templates) templates.classList.add('hidden');
    if (buttonArea) buttonArea.classList.add('hidden');
    if (imageArea) imageArea.classList.add('hidden');
    if (audioArea) audioArea.classList.add('hidden');
    if (videoArea) videoArea.classList.add('hidden');
    if (documentArea) documentArea.classList.add('hidden');
    if (locationArea) locationArea.classList.add('hidden');

    // Show selected
    if (this.value === 'template') {
      if (templates) templates.classList.remove('hidden');
    } else if (this.value === 'button') {
      if (buttonArea) buttonArea.classList.remove('hidden');
    } else if (this.value === 'image') {
      if (imageArea) imageArea.classList.remove('hidden');
    } else if (this.value === 'audio') {
      if (audioArea) audioArea.classList.remove('hidden');
    } else if (this.value === 'video') {
      if (videoArea) videoArea.classList.remove('hidden');
    } else if (this.value === 'document') {
      if (documentArea) documentArea.classList.remove('hidden');
    } else if (this.value === 'location') {
      if (locationArea) locationArea.classList.remove('hidden');
    } else {
      if (textArea) textArea.classList.remove('hidden');
    }
  });
});
</script>
@endpush