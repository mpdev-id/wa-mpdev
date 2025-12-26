@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Create Template')])
@endsection

@section('content')
<div class="max-w-7xl mx-auto" x-data="templateForm()">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-base-content mb-2">{{ __('Create Template') }}</h1>
      <p class="text-base-content/60">{{ __('Create reusable message template') }}</p>
    </div>
    <a href="{{ route('user.template.index') }}" class="btn btn-outline gap-2">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back') }}
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Section -->
    <div class="lg:col-span-2 space-y-6">
      <div class="card bg-white shadow-xl">
        <div class="card-body">
          <form method="POST" class="ajaxform_instant_reload space-y-6" :action="submitUrl" enctype="multipart/form-data">
            @csrf
            
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="form-control">
                <label class="label">
                  <span class="label-text font-medium">{{ __('Template Name') }}</span>
                </label>
                <input type="text" name="template_name" class="input input-bordered w-full" placeholder="e.g. Welcome Message" required />
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-medium">{{ __('Message Type') }}</span>
                </label>
                <select x-model="type" class="select select-bordered w-full">
                  <option value="plain-text">{{ __('Text Message') }}</option>
                  <option value="text-with-media">{{ __('Media Message') }}</option>
                  <option value="text-with-button">{{ __('Button Message') }}</option>
                  <option value="text-with-list">{{ __('List Message') }}</option>
                  <option value="text-with-location">{{ __('Location Message') }}</option>
                </select>
              </div>
            </div>

            <!-- Message Content -->
            <div class="form-control">
              <label class="label">
                <span class="label-text font-medium">{{ __('Message Content') }}</span>
              </label>
              <textarea 
                x-model="message" 
                name="message" 
                class="textarea textarea-bordered h-32 font-mono text-sm" 
                placeholder="Type your message here..."
                required
              ></textarea>
              
              <!-- Variable Helpers -->
              <div class="flex flex-wrap gap-2 mt-2">
                <button type="button" @click="insertVar('{1}')" class="btn btn-xs btn-outline btn-sm normal-case">{1}</button>
                <button type="button" @click="insertVar('{2}')" class="btn btn-xs btn-outline btn-sm normal-case">{2}</button>
                <button type="button" @click="insertVar('{3}')" class="btn btn-xs btn-outline btn-sm normal-case">{3}</button>
                <button type="button" @click="insertVar('{name}')" class="btn btn-xs btn-outline btn-sm normal-case">{name}</button>
                <button type="button" @click="insertVar('{phone}')" class="btn btn-xs btn-outline btn-sm normal-case">{phone}</button>
              </div>
              <label class="label">
                <span class="label-text-alt text-gray-500">{{ __('Click variables to insert them into the message.') }}</span>
              </label>
            </div>

            <!-- Media Fields -->
            <div x-show="type === 'text-with-media'" class="form-control p-4 bg-base-100 rounded-lg border border-base-200">
              <label class="label">
                <span class="label-text font-medium">{{ __('Attachment') }}</span>
              </label>
              <input type="file" name="file" class="file-input file-input-bordered w-full" accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx,.csv,.txt" />
              <label class="label">
                <span class="label-text-alt">{{ __('Supported: Images, PDF, Docs. Max 1MB.') }}</span>
              </label>
            </div>

            <!-- Button Fields -->
            <div x-show="type === 'text-with-button'" class="space-y-4 p-4 bg-base-100 rounded-lg border border-base-200">
              <div class="form-control">
                <label class="label"><span class="label-text font-medium">{{ __('Footer Text') }}</span></label>
                <input type="text" x-model="footer" name="footer_text" class="input input-bordered w-full" placeholder="e.g. Unsubscribe" />
              </div>
              
              <div class="divider text-sm">{{ __('Buttons (Max 3)') }}</div>
              
              <template x-for="(btn, index) in buttons" :key="index">
                <div class="flex gap-2">
                  <input type="text" :name="'buttons['+index+']'" x-model="buttons[index]" class="input input-bordered w-full" :placeholder="'Button ' + (index + 1)" required />
                  <button type="button" @click="removeButton(index)" class="btn btn-square btn-outline btn-error">
                    <span class="material-icons">delete</span>
                  </button>
                </div>
              </template>
              
              <button type="button" @click="addButton()" class="btn btn-sm btn-outline w-full" x-show="buttons.length < 3">
                <span class="material-icons text-sm">add</span> {{ __('Add Button') }}
              </button>
            </div>

            <!-- List Fields -->
            <div x-show="type === 'text-with-list'" class="space-y-4 p-4 bg-base-100 rounded-lg border border-base-200">
              <div class="grid grid-cols-2 gap-4">
                <div class="form-control">
                  <label class="label"><span class="label-text font-medium">{{ __('Header Title') }}</span></label>
                  <input type="text" x-model="header" name="header_title" class="input input-bordered w-full" placeholder="e.g. Menu" />
                </div>
                <div class="form-control">
                  <label class="label"><span class="label-text font-medium">{{ __('Footer Text') }}</span></label>
                  <input type="text" x-model="footer" name="footer_text" class="input input-bordered w-full" placeholder="e.g. Select one" />
                </div>
              </div>
              <div class="form-control">
                <label class="label"><span class="label-text font-medium">{{ __('Button Text') }}</span></label>
                <input type="text" x-model="listButton" name="button_text" class="input input-bordered w-full" placeholder="e.g. View Menu" />
              </div>
              
              <div class="divider text-sm">{{ __('List Sections') }}</div>
              
              <template x-for="(section, sIndex) in listSections" :key="sIndex">
                <div class="card bg-base-100 border border-base-200 p-4 mb-4">
                  <div class="flex justify-between items-start mb-2">
                    <div class="form-control w-full mr-2">
                      <label class="label py-0"><span class="label-text text-xs font-bold">{{ __('Section Title') }}</span></label>
                      <input type="text" :name="'section['+sIndex+'][title]'" x-model="section.title" class="input input-sm input-bordered w-full" placeholder="e.g. Main Menu" required />
                    </div>
                    <button type="button" @click="removeSection(sIndex)" class="btn btn-ghost btn-xs text-error" x-show="listSections.length > 1">
                      <span class="material-icons text-sm">close</span>
                    </button>
                  </div>

                  <div class="space-y-2 pl-4 border-l-2 border-base-200">
                    <template x-for="(item, iIndex) in section.items" :key="iIndex">
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-2 relative group">
                        <input type="text" :name="'section['+sIndex+'][value]['+iIndex+'][title]'" x-model="item.title" class="input input-xs input-bordered w-full" placeholder="Option Title" required />
                        <input type="text" :name="'section['+sIndex+'][value]['+iIndex+'][description]'" x-model="item.description" class="input input-xs input-bordered w-full" placeholder="Description (Optional)" />
                        <button type="button" @click="removeListItem(sIndex, iIndex)" class="absolute -right-6 top-0 btn btn-ghost btn-xs text-error opacity-0 group-hover:opacity-100" x-show="section.items.length > 1">
                          <span class="material-icons text-[10px]">close</span>
                        </button>
                      </div>
                    </template>
                    <button type="button" @click="addListItem(sIndex)" class="btn btn-ghost btn-xs gap-1">
                      <span class="material-icons text-xs">add</span> {{ __('Add Option') }}
                    </button>
                  </div>
                </div>
              </template>

              <button type="button" @click="addSection()" class="btn btn-sm btn-outline w-full" x-show="listSections.length < 20">
                <span class="material-icons text-sm">add</span> {{ __('Add Section') }}
              </button>
            </div>

            <!-- Location Fields -->
            <div x-show="type === 'text-with-location'" class="grid grid-cols-2 gap-4 p-4 bg-base-100 rounded-lg border border-base-200">
              <div class="form-control">
                <label class="label"><span class="label-text font-medium">{{ __('Latitude') }}</span></label>
                <input type="text" name="degreesLatitude" class="input input-bordered w-full" placeholder="-6.200000" />
              </div>
              <div class="form-control">
                <label class="label"><span class="label-text font-medium">{{ __('Longitude') }}</span></label>
                <input type="text" name="degreesLongitude" class="input input-bordered w-full" placeholder="106.816666" />
              </div>
            </div>

            <!-- Submit Actions -->
            <div class="flex justify-end gap-3 pt-6 border-t border-base-200">
              <a href="{{ route('user.template.index') }}" class="btn btn-outline">
                {{ __('Cancel') }}
              </a>
              <button type="submit" class="btn btn-primary submit-btn gap-2" :disabled="!isValid">
                <span class="material-icons text-sm">save</span>
                {{ __('Create Template') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Preview Section -->
    <div class="lg:col-span-1">
      <div class="sticky top-6">
        <h3 class="font-bold text-lg mb-4 text-gray-700">{{ __('Live Preview') }}</h3>
        <div class="mockup-phone border-primary">
          <div class="camera"></div> 
          <div class="display">
            <div class="artboard artboard-demo phone-1 bg-[#e5ddd5] justify-start pt-12 px-4 overflow-y-auto">
              <!-- Chat Bubble -->
              <div class="chat chat-start w-full">
                <div class="chat-bubble bg-white text-gray-800 shadow-sm p-2 text-sm w-full max-w-[90%] break-words">
                  
                  <!-- Header/Media -->
                  <template x-if="type === 'text-with-media'">
                    <div class="bg-gray-200 h-32 w-full rounded-lg mb-2 flex items-center justify-center text-gray-400">
                      <span class="material-icons">image</span>
                    </div>
                  </template>
                  <template x-if="type === 'text-with-list' && header">
                    <div class="font-bold text-gray-900 mb-1" x-text="header"></div>
                  </template>

                  <!-- Body -->
                  <div class="whitespace-pre-wrap" x-text="message || '{{ __('Your message will appear here...') }}'"></div>
                  
                  <!-- Footer -->
                  <template x-if="footer">
                    <div class="text-xs text-gray-500 mt-1" x-text="footer"></div>
                  </template>
                </div>
                <div class="chat-footer opacity-50 text-xs mt-1">Now</div>
              </div>

              <!-- Buttons Preview -->
              <template x-if="type === 'text-with-button' && buttons.length > 0">
                <div class="flex flex-col gap-2 mt-2 w-full max-w-[90%]">
                  <template x-for="btn in buttons">
                    <button class="btn btn-sm bg-white border-none text-blue-500 hover:bg-gray-50 shadow-sm w-full normal-case" x-text="btn"></button>
                  </template>
                </div>
              </template>

              <!-- List Button Preview -->
              <template x-if="type === 'text-with-list' && listButton">
                 <div class="mt-2 w-full max-w-[90%]">
                    <button class="btn btn-sm bg-white border-none text-blue-500 hover:bg-gray-50 shadow-sm w-full normal-case flex justify-between items-center px-4">
                      <span class="material-icons text-sm">list</span>
                      <span x-text="listButton"></span>
                    </button>
                 </div>
              </template>

            </div>
          </div>
        </div>
        
        <div class="alert alert-info mt-6 shadow-lg">
          <span class="material-icons">info</span>
          <div class="text-xs">
            {{ __('This is a preview. Actual rendering may vary slightly on different devices.') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('js')
<script>
  function templateForm() {
    return {
      type: 'plain-text',
      message: '',
      footer: '',
      header: '',
      listButton: '',
      buttons: [],
      listSections: [],
      
      get submitUrl() {
        return "{{ url('user/template/store') }}/" + this.type;
      },

      get isValid() {
        if (!this.message) return false;
        if (this.type === 'text-with-button' && this.buttons.length === 0) return false;
        if (this.type === 'text-with-list' && this.listSections.length === 0) return false;
        return true;
      },

      init() {
        // Initialize with one section for list
        this.addSection();
      },

      insertVar(variable) {
        const textarea = document.querySelector('textarea[name="message"]');
        if (!textarea) return;
        
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = this.message;
        
        this.message = text.substring(0, start) + variable + text.substring(end);
        textarea.value = this.message;
        textarea.dispatchEvent(new Event('input'));

        this.$nextTick(() => {
          textarea.focus();
          textarea.setSelectionRange(start + variable.length, start + variable.length);
        });
      },

      addButton() {
        if (this.buttons.length < 3) {
          this.buttons.push('');
        }
      },

      removeButton(index) {
        this.buttons.splice(index, 1);
      },

      addSection() {
        if (this.listSections.length < 20) {
          this.listSections.push({
            title: '',
            items: [{ title: '', description: '' }]
          });
        }
      },

      removeSection(index) {
        this.listSections.splice(index, 1);
      },

      addListItem(sectionIndex) {
        this.listSections[sectionIndex].items.push({ title: '', description: '' });
      },

      removeListItem(sectionIndex, itemIndex) {
        this.listSections[sectionIndex].items.splice(itemIndex, 1);
      }
    }
  }
</script>
@endpush
@endsection