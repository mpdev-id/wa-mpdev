@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Chats')])
<style>
    .chat-bg {
        background-color: #efeae2;
        background-image: url("https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png");
        background-blend-mode: overlay;
        opacity: 0.98;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.15);
        border-radius: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.25);
    }
</style>
@endsection

@section('content')
<div class="h-[calc(100vh-180px)] -mt-6 -mx-4 md:-mx-8 flex overflow-hidden bg-white" x-data="chatApp()">
    <!-- Left Sidebar: Chat List -->
    <div class="w-full md:w-[400px] flex flex-col border-r border-gray-200 bg-white" :class="{'hidden md:flex': activeChat}">
        <!-- Header -->
        <div class="h-16 bg-[#f0f2f5] flex items-center justify-between px-4 border-b border-gray-200 shrink-0">
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
                       class="input input-sm input-bordered w-full pl-10 bg-gray-100 border-none focus:ring-0 rounded-lg text-gray-700" 
                       placeholder="{{ __('Search or start new chat') }}">
            </div>
        </div>

        <!-- Chat List -->
        <div class="flex-1 overflow-y-auto custom-scrollbar bg-white">
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

            <template x-for="chat in filteredChats" :key="chat.jid || chat.number">
                <div @click="selectChat(chat)" 
                     class="flex items-center gap-3 p-3 cursor-pointer hover:bg-gray-50 transition-colors border-b border-gray-100"
                     :class="{'bg-[#f0f2f5]': activeChat && (activeChat.jid === chat.jid || activeChat.number === chat.number)}">
                    <div class="avatar placeholder shrink-0">
                        <div class="bg-emerald-500 text-white font-semibold rounded-full w-12 flex items-center justify-center">
                            <span class="text-base" x-text="String(chat.name || chat.pushName || chat.number).substring(0, 2).toUpperCase()"></span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="font-medium text-gray-900 truncate" x-text="chat.name || chat.pushName || chat.number"></h3>
                            <span class="text-xs text-gray-500" x-text="formatTime(chat.timestamp)"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-500 truncate" x-text="getLastMessageText(chat) || '{{ __('Click to view chat') }}'"></p>
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
    <div class="flex-1 flex flex-col bg-[#efeae2] relative" :class="{'hidden md:flex': !activeChat, 'flex w-full': activeChat}">
        <template x-if="!activeChat">
            <div class="flex-1 flex flex-col items-center justify-center bg-gray-50 border-b-[6px] border-emerald-500">
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
                <div class="h-16 bg-[#f0f2f5] flex items-center justify-between px-4 border-b border-gray-200 shrink-0">
                    <div class="flex items-center gap-3">
                        <button @click="activeChat = null" class="md:hidden btn btn-ghost btn-circle btn-sm">
                            <span class="material-icons">arrow_back</span>
                        </button>
                        <div class="avatar placeholder">
                            <div class="bg-emerald-500 text-white font-semibold rounded-full w-10 flex items-center justify-center">
                                <span x-text="String(activeChat.name || activeChat.pushName || activeChat.number).substring(0, 2).toUpperCase()"></span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900" x-text="activeChat.name || activeChat.pushName || activeChat.number"></h3>
                            <p class="text-xs text-gray-500" x-text="activeChat.jid && activeChat.jid.endsWith('@g.us') ? '{{ __('Group') }}' : '{{ __('Online') }}'"></p>
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
                <div class="flex-1 overflow-y-auto p-4 chat-bg custom-scrollbar flex flex-col" id="messages-container">
                    <template x-if="loadingMessages">
                        <div class="flex-1 flex flex-col items-center justify-center text-gray-400">
                            <span class="loading loading-spinner loading-md"></span>
                            <span class="text-sm mt-2">{{ __('Loading messages...') }}</span>
                        </div>
                    </template>

                    <template x-if="!loadingMessages">
                        <div class="w-full">
                            <div class="flex justify-center my-2">
                                <div class="bg-[#ffeecd] text-gray-800 text-xs px-4 py-2 rounded-lg shadow-sm text-center max-w-md">
                                    <span class="material-icons text-yellow-600 text-xs align-middle mr-1">lock</span>
                                    {{ __('Messages are end-to-end encrypted. No one outside of this chat, not even WhatsApp, can read or listen to them.') }}
                                </div>
                            </div>

                            <!-- Messages Loop -->
                            <template x-for="msg in activeChatMessages" :key="msg.key.id">
                                <div class="chat my-2" :class="msg.key.fromMe ? 'chat-end' : 'chat-start'">
                                    <!-- Bubble -->
                                    <div class="chat-bubble shadow-sm max-w-[75%] rounded-lg px-3 py-1.5 flex flex-col"
                                         :class="msg.key.fromMe ? 'bg-[#d9fdd3] text-gray-800' : 'bg-white text-gray-800'">
                                        <!-- Group sender display -->
                                        <template x-if="!msg.key.fromMe && activeChat.jid && activeChat.jid.endsWith('@g.us')">
                                            <span class="text-xs font-bold text-emerald-600 mb-0.5" 
                                                  x-text="msg.pushName || (msg.key.participant ? msg.key.participant.split('@')[0] : '')"></span>
                                        </template>
                                        <span class="whitespace-pre-wrap break-words text-[14px]" x-text="getMessageText(msg)"></span>
                                    </div>
                                    <!-- Footer (Time & status) -->
                                    <div class="chat-footer opacity-75 text-[10px] flex items-center gap-1 mt-0.5"
                                         :class="msg.key.fromMe ? 'justify-end text-gray-500' : 'justify-start text-gray-500'">
                                        <span x-text="formatTime(msg.messageTimestamp)"></span>
                                        <template x-if="msg.key.fromMe">
                                            <span class="material-icons text-xs leading-none" 
                                                  :class="msg.status === 4 || msg.status === 5 ? 'text-sky-500' : 'text-gray-400'"
                                                  x-text="msg.status >= 3 ? 'done_all' : (msg.status === 2 ? 'done' : 'schedule')"></span>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Input Area -->
                <div class="bg-[#f0f2f5] px-4 py-3 flex items-end gap-2 shrink-0 border-t border-gray-200">
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
                                  class="w-full px-4 py-2 bg-transparent border-none focus:ring-0 resize-none max-h-32 text-gray-700 placeholder-gray-400 text-sm" 
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
            loadingMessages: false,
            search: '',
            activeChat: null,
            messageInput: '',
            activeChatMessages: [],

            get filteredChats() {
                if (!this.search) return this.chats;
                const query = this.search.toLowerCase();
                return this.chats.filter(chat => {
                    const name = String(chat.name || '').toLowerCase();
                    const pushName = String(chat.pushName || '').toLowerCase();
                    const number = String(chat.number || '').toLowerCase();
                    return name.includes(query) || pushName.includes(query) || number.includes(query);
                });
            },

            init() {
                this.fetchChats();

                // 1. Silent poll for the chat list every 10 seconds
                setInterval(() => {
                    this.silentFetchChats();
                }, 10000);

                // 2. Silent poll for new messages in the active chat every 3 seconds
                setInterval(() => {
                    if (this.activeChat) {
                        this.fetchMessages(this.activeChat, true);
                    }
                }, 3000);
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
                    if (data.chats) {
                        this.chats = data.chats;
                    }
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error fetching chats:', error);
                    this.loading = false;
                });
            },

            silentFetchChats() {
                fetch('{{ url("/user/get-chats/".$device->uuid) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.chats) {
                        this.chats = data.chats;
                        // Keep current activeChat details up-to-date
                        if (this.activeChat) {
                            const updated = this.chats.find(c => c.jid === this.activeChat.jid);
                            if (updated) {
                                Object.assign(this.activeChat, updated);
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Silent fetch chats error:', error);
                });
            },

            selectChat(chat) {
                this.activeChat = chat;
                this.activeChatMessages = [];
                this.fetchMessages(chat, false);
            },

            fetchMessages(chat, silent = false) {
                if (!chat) return;
                const jid = chat.jid || (chat.number ? chat.number + '@s.whatsapp.net' : '');
                if (!jid) return;

                if (!silent) {
                    this.loadingMessages = true;
                }

                fetch('{{ url("/user/get-messages/".$device->uuid) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ jid: jid })
                })
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data)) {
                        const oldLength = this.activeChatMessages.length;
                        const oldLastId = oldLength > 0 ? this.activeChatMessages[oldLength - 1].key.id : null;
                        
                        this.activeChatMessages = data;
                        
                        const newLength = data.length;
                        const newLastId = newLength > 0 ? data[newLength - 1].key.id : null;

                        // Scroll to bottom if this is initial load, or if new messages arrived
                        if (oldLength === 0 || oldLastId !== newLastId) {
                            this.$nextTick(() => {
                                const container = document.getElementById('messages-container');
                                if (container) container.scrollTop = container.scrollHeight;
                            });
                        }
                    }
                    this.loadingMessages = false;
                })
                .catch(error => {
                    console.error('Error fetching messages:', error);
                    this.loadingMessages = false;
                });
            },

            sendMessage() {
                if (!this.messageInput.trim() || !this.activeChat) return;

                const text = this.messageInput;
                const receiver = this.activeChat.number;
                const jid = this.activeChat.jid || (this.activeChat.number ? this.activeChat.number + '@s.whatsapp.net' : '');

                this.messageInput = '';

                // 1. Optimistic rendering (Pending state)
                const tempId = 'temp_' + Date.now();
                const tempMsg = {
                    key: {
                        id: tempId,
                        fromMe: true,
                        remoteJid: jid
                    },
                    message: {
                        conversation: text
                    },
                    messageTimestamp: Math.floor(Date.now() / 1000),
                    status: 1 // grey clock icon
                };
                
                this.activeChatMessages.push(tempMsg);
                
                this.$nextTick(() => {
                    const container = document.getElementById('messages-container');
                    if (container) container.scrollTop = container.scrollHeight;
                });

                // Determine routing
                const isGroup = jid.endsWith('@g.us');
                const url = isGroup 
                    ? '{{ route("user.group.send-message", $device->uuid) }}' 
                    : '{{ route("user.chat.send-message", $device->uuid) }}';
                
                const bodyPayload = isGroup 
                    ? {
                        group: jid,
                        group_name: this.activeChat.name || this.activeChat.number,
                        selecttype: 'plain-text',
                        message: text
                      }
                    : {
                        reciver: receiver,
                        selecttype: 'plain-text',
                        message: text
                      };

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(bodyPayload)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Send failed:', data.error);
                        // Mark as failed visually
                        const found = this.activeChatMessages.find(m => m.key.id === tempId);
                        if (found) found.status = 0;
                    } else {
                        // Success - silently fetch messages to sync the true message list from Baileys
                        this.fetchMessages(this.activeChat, true);
                    }
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                    const found = this.activeChatMessages.find(m => m.key.id === tempId);
                    if (found) found.status = 0;
                });
            },

            getMessageText(msg) {
                if (!msg || !msg.message) return '';
                const m = msg.message;
                if (typeof m === 'string') return m;
                if (m.conversation) return m.conversation;
                if (m.extendedTextMessage && m.extendedTextMessage.text) return m.extendedTextMessage.text;
                if (m.imageMessage) return m.imageMessage.caption || '[📷 Photo]';
                if (m.videoMessage) return m.videoMessage.caption || '[🎥 Video]';
                if (m.documentMessage) return m.documentMessage.caption || '[📄 Document]';
                if (m.audioMessage) return '[🎵 Audio]';
                if (m.stickerMessage) return '[👾 Sticker]';
                if (m.buttonsResponseMessage && m.buttonsResponseMessage.selectedDisplayText) return m.buttonsResponseMessage.selectedDisplayText;
                if (m.templateButtonReplyMessage && m.templateButtonReplyMessage.selectedDisplayText) return m.templateButtonReplyMessage.selectedDisplayText;
                if (m.listResponseMessage && m.listResponseMessage.title) return m.listResponseMessage.title;
                
                // Nested types
                if (m.ephemeralMessage && m.ephemeralMessage.message) {
                    return this.getMessageText({ message: m.ephemeralMessage.message });
                }
                if (m.viewOnceMessage && m.viewOnceMessage.message) {
                    return this.getMessageText({ message: m.viewOnceMessage.message });
                }
                if (m.viewOnceMessageV2 && m.viewOnceMessageV2.message) {
                    return this.getMessageText({ message: m.viewOnceMessageV2.message });
                }
                
                return '';
            },

            getLastMessageText(chat) {
                if (!chat) return '';
                if (typeof chat.last_message === 'string') return chat.last_message;
                if (chat.last_message && typeof chat.last_message === 'object') {
                    return this.getMessageText({ message: chat.last_message });
                }
                return '';
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