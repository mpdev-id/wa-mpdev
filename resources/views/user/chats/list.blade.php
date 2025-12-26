@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Chats')])
<style>
    .chat-bg {
        background-color: #e5ddd5;
        background-image: url("https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png");
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection

@section('content')
<div class="h-[calc(100vh-180px)] -mt-6 -mx-4 md:-mx-8 flex overflow-hidden bg-white" x-data="chatApp()">
    <!-- Left Sidebar: Chat List -->
    <div class="w-full md:w-[400px] flex flex-col border-r border-gray-200 bg-white" :class="{'hidden md:flex': activeChat}">
        <!-- Header -->
        <div class="h-16 bg-gray-100 flex items-center justify-between px-4 border-b border-gray-200 shrink-0">
            <div class="avatar">
                <div class="w-10 rounded-full">
                    <img src="{{ Auth::user()->avatar == null ? 'https://ui-avatars.com/api/?name='.Auth::user()->name : asset(Auth::user()->avatar) }}" />
                </div>
            </div>
            <div class="flex gap-4 text-gray-600">
                <button @click="fetchChats()" class="btn btn-ghost btn-circle btn-sm" title="{{ __('Sync Chats') }}" :class="{'animate-spin': loading}">
                    <span class="material-icons">sync</span>
                </button>
                <button class="btn btn-ghost btn-circle btn-sm" title="{{ __('New Chat') }}">
                    <span class="material-icons">chat</span>
                </button>
                <button class="btn btn-ghost btn-circle btn-sm">
                    <span class="material-icons">more_vert</span>
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="p-2 bg-white border-b border-gray-100 shrink-0">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-icons text-gray-400 text-sm">search</span>
                </div>
                <input type="text" 
                       x-model="search" 
                       class="input input-sm input-bordered w-full pl-10 bg-gray-100 border-none focus:ring-0 rounded-lg" 
                       placeholder="{{ __('Search or start new chat') }}">
            </div>
        </div>

        <!-- Chat List -->
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            <template x-if="loading">
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <span class="loading loading-spinner loading-md"></span>
                    <span class="text-sm mt-2">{{ __('Loading chats...') }}</span>
                </div>
            </template>

            <template x-if="!loading && filteredChats.length === 0">
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <span class="material-icons text-4xl mb-2">chat_bubble_outline</span>
                    <span class="text-sm">{{ __('No chats found') }}</span>
                    <button @click="fetchChats()" class="btn btn-sm btn-link no-underline mt-2">{{ __('Try Syncing') }}</button>
                </div>
            </template>

            <template x-for="chat in filteredChats" :key="chat.number">
                <div @click="selectChat(chat)" 
                     class="flex items-center gap-3 p-3 cursor-pointer hover:bg-gray-50 transition-colors border-b border-gray-100"
                     :class="{'bg-gray-100': activeChat && activeChat.number === chat.number}">
                    <div class="avatar placeholder shrink-0">
                        <div class="bg-gray-300 text-gray-600 rounded-full w-12">
                            <span class="text-lg" x-text="String(chat.name || chat.pushName || chat.number).substring(0, 2)"></span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="font-medium text-gray-900 truncate" x-text="chat.name || chat.pushName || chat.number"></h3>
                            <span class="text-xs text-gray-500" x-text="formatTime(chat.timestamp)"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-500 truncate" x-text="chat.last_message || '{{ __('Click to view chat') }}'"></p>
                            <template x-if="chat.unread > 0">
                                <span class="badge badge-success badge-sm text-white border-none h-5 min-w-[1.25rem]" x-text="chat.unread"></span>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Right Main Area: Chat View -->
    <div class="flex-1 flex flex-col bg-[#e5ddd5] relative" :class="{'hidden md:flex': !activeChat, 'flex w-full': activeChat}">
        <template x-if="!activeChat">
            <div class="flex-1 flex flex-col items-center justify-center bg-gray-50 border-b-[6px] border-success">
                <div class="text-center max-w-md px-6">
                    <h2 class="text-3xl font-light text-gray-600 mb-4">{{ __('WhatsApp Web') }}</h2>
                    <p class="text-gray-500 text-sm mb-8">
                        {{ __('Send and receive messages without keeping your phone online.') }}<br>
                        {{ __('Use WhatsApp on up to 4 linked devices and 1 phone at the same time.') }}
                    </p>
                    <div class="flex items-center justify-center gap-2 text-gray-400 text-xs">
                        <span class="material-icons text-sm">lock</span>
                        {{ __('End-to-end encrypted') }}
                    </div>
                </div>
            </div>
        </template>

        <template x-if="activeChat">
            <div class="flex flex-col h-full">
                <!-- Chat Header -->
                <div class="h-16 bg-gray-100 flex items-center justify-between px-4 border-b border-gray-200 shrink-0">
                    <div class="flex items-center gap-4">
                        <button @click="activeChat = null" class="md:hidden btn btn-ghost btn-circle btn-sm">
                            <span class="material-icons">arrow_back</span>
                        </button>
                        <div class="avatar placeholder">
                            <div class="bg-gray-300 text-gray-600 rounded-full w-10">
                                <span x-text="String(activeChat.number).substring(0, 2)"></span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900" x-text="activeChat.number"></h3>
                            <p class="text-xs text-gray-500">{{ __('Online') }}</p>
                        </div>
                    </div>
                    <div class="flex gap-4 text-gray-600">
                        <button class="btn btn-ghost btn-circle btn-sm">
                            <span class="material-icons">search</span>
                        </button>
                        <button class="btn btn-ghost btn-circle btn-sm">
                            <span class="material-icons">more_vert</span>
                        </button>
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto p-4 chat-bg custom-scrollbar" id="messages-container">
                    <!-- Placeholder for messages since we can't fetch history yet -->
                    <div class="flex justify-center my-4">
                        <span class="bg-[#e1f3fb] text-gray-600 text-xs px-3 py-1.5 rounded-lg shadow-sm uppercase">
                            {{ __('Today') }}
                        </span>
                    </div>
                    
                    <div class="flex justify-center my-4">
                        <div class="bg-[#ffeecd] text-gray-800 text-sm px-4 py-2 rounded-lg shadow-sm text-center max-w-md">
                            <span class="material-icons text-yellow-600 text-sm align-middle mr-1">lock</span>
                            {{ __('Messages are end-to-end encrypted. No one outside of this chat, not even WhatsApp, can read or listen to them.') }}
                        </div>
                    </div>

                    <!-- Sent messages (local only for now) -->
                    <template x-for="msg in activeChatMessages" :key="msg.id">
                        <div class="chat chat-end">
                            <div class="chat-bubble chat-bubble-success text-white shadow-sm" x-text="msg.text"></div>
                            <div class="chat-footer opacity-50 text-xs flex items-center gap-1 mt-1">
                                <span x-text="formatTime(Date.now() / 1000)"></span>
                                <span class="material-icons text-[10px]">done_all</span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Input Area -->
                <div class="bg-gray-100 px-4 py-3 flex items-end gap-2 shrink-0">
                    <button class="btn btn-ghost btn-circle btn-sm text-gray-500">
                        <span class="material-icons">sentiment_satisfied_alt</span>
                    </button>
                    <button class="btn btn-ghost btn-circle btn-sm text-gray-500">
                        <span class="material-icons">attach_file</span>
                    </button>
                    
                    <div class="flex-1 bg-white rounded-lg flex items-center border border-white focus-within:border-white">
                        <textarea x-model="messageInput" 
                                  @keydown.enter.prevent="sendMessage()"
                                  rows="1"
                                  class="w-full px-4 py-2 bg-transparent border-none focus:ring-0 resize-none max-h-32 text-gray-700 placeholder-gray-400" 
                                  placeholder="{{ __('Type a message') }}"></textarea>
                    </div>

                    <button @click="sendMessage()" 
                            class="btn btn-circle btn-sm border-none"
                            :class="messageInput.trim() ? 'btn-success text-white' : 'btn-ghost text-gray-500'">
                        <span class="material-icons" x-text="messageInput.trim() ? 'send' : 'mic'"></span>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>

@push('scripts')
<script>
    function chatApp() {
        return {
            chats: [],
            loading: true,
            search: '',
            activeChat: null,
            messageInput: '',
            activeChatMessages: [], // Local storage for sent messages in this session

            get filteredChats() {
                if (!this.search) return this.chats;
                return this.chats.filter(chat => 
                    String(chat.number).toLowerCase().includes(this.search.toLowerCase())
                );
            },

            init() {
                this.fetchChats();
            },

            fetchChats() {
                this.loading = true;
                fetch('{{ url("/user/get-chats/".$device->uuid) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Chats data:', data);
                    if (data.chats) {
                        this.chats = data.chats;
                    } else if (data.message) {
                        console.error('Server message:', data.message);
                    }
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.loading = false;
                    // alert('Failed to load chats. Check console for details.');
                });
            },

            selectChat(chat) {
                this.activeChat = chat;
                this.activeChatMessages = []; // Reset messages for new chat (since we can't fetch history)
                // Scroll to bottom of messages
                this.$nextTick(() => {
                    const container = document.getElementById('messages-container');
                    if (container) container.scrollTop = container.scrollHeight;
                });
            },

            sendMessage() {
                if (!this.messageInput.trim() || !this.activeChat) return;

                const text = this.messageInput;
                const receiver = this.activeChat.number;

                // Optimistic update
                this.activeChatMessages.push({
                    id: Date.now(),
                    text: text,
                    sent: true
                });
                
                this.messageInput = '';
                
                this.$nextTick(() => {
                    const container = document.getElementById('messages-container');
                    if (container) container.scrollTop = container.scrollHeight;
                });

                fetch('{{ route("user.chat.send-message", $device->uuid) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        reciver: receiver,
                        selecttype: 'plain-text',
                        message: text
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        // Handle error (maybe show toast)
                        console.error('Send failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            },

            formatTime(timestamp) {
                if (!timestamp) return '';
                const date = new Date(timestamp * 1000);
                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }
        }
    }
</script>
@endpush
@endsection