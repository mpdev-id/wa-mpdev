@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Chats')])
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
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
    .btn {
        border-radius: 9999px !important;
    }
    
    /* Hacker theme overrides for live chat */
    html[data-theme="hacker"] .chat-bg {
        background-color: var(--color-base-100) !important;
        background-image: radial-gradient(rgba(0, 255, 136, 0.05) 1.5px, transparent 1.5px) !important;
        background-size: 20px 20px !important;
    }
    html[data-theme="hacker"] .bg-white,
    html[data-theme="hacker"] .modal-box {
        background-color: var(--color-base-200) !important;
        color: var(--color-base-content) !important;
    }
    html[data-theme="hacker"] .border-gray-200,
    html[data-theme="hacker"] .border-gray-100 {
        border-color: var(--color-base-300) !important;
    }
    html[data-theme="hacker"] .bg-\[\#f0f2f5\],
    html[data-theme="hacker"] .h-16 {
        background-color: var(--color-base-300) !important;
    }
    html[data-theme="hacker"] .bg-gray-100,
    html[data-theme="hacker"] .bg-gray-50,
    html[data-theme="hacker"] .input-bordered {
        background-color: var(--color-base-100) !important;
        border-color: var(--color-base-300) !important;
        color: var(--color-base-content) !important;
    }
    html[data-theme="hacker"] .text-gray-800,
    html[data-theme="hacker"] .text-gray-700 {
        color: var(--color-base-content) !important;
    }
    html[data-theme="hacker"] .bg-emerald-500 {
        background-color: var(--color-primary) !important;
        color: var(--color-primary-content) !important;
    }
    html[data-theme="hacker"] .text-emerald-600,
    html[data-theme="hacker"] .text-emerald-700 {
        color: var(--color-primary) !important;
    }
    html[data-theme="hacker"] .hover\:bg-emerald-50:hover {
        background-color: rgba(0, 255, 136, 0.1) !important;
    }
    html[data-theme="hacker"] .hover\:bg-gray-100:hover,
    html[data-theme="hacker"] .hover\:bg-gray-50:hover {
        background-color: var(--color-base-300) !important;
    }
    html[data-theme="hacker"] .bg-emerald-50 {
        background-color: rgba(0, 255, 136, 0.08) !important;
        border-color: rgba(0, 255, 136, 0.2) !important;
    }
    html[data-theme="hacker"] .border-emerald-500 {
        border-color: var(--color-primary) !important;
    }
    html[data-theme="hacker"] .border-b-\[6px\] {
        border-bottom-color: var(--color-primary) !important;
    }
    html[data-theme="hacker"] .bg-\[\#efeae2\] {
        background-color: var(--color-base-100) !important;
    }
    html[data-theme="hacker"] .bg-gray-50.border-b-\[6px\] {
        background-color: var(--color-base-200) !important;
        border-bottom-color: var(--color-primary) !important;
    }
    /* Message bubble styling overrides */
    html[data-theme="hacker"] .bg-\[\#d9fdd3\] {
        background-color: rgba(0, 255, 136, 0.15) !important;
        border: 1px solid var(--color-primary) !important;
        color: var(--color-base-content) !important;
    }
    html[data-theme="hacker"] .bg-white.text-gray-800 {
        background-color: var(--color-base-300) !important;
        border: 1px solid var(--color-base-200) !important;
        color: var(--color-base-content) !important;
    }
    html[data-theme="hacker"] .bg-\[\#ffeecd\] {
        background-color: rgba(251, 191, 36, 0.15) !important;
        border: 1px solid var(--color-warning) !important;
        color: var(--color-warning) !important;
    }
    html[data-theme="hacker"] .bg-\[\#ffeecd\] .text-gray-800 {
        color: var(--color-warning) !important;
    }
    html[data-theme="hacker"] .dropdown-content {
        background-color: var(--color-base-300) !important;
        border-color: var(--color-base-200) !important;
    }
    html[data-theme="hacker"] .material-icons {
        color: var(--color-primary) !important;
    }
    html[data-theme="hacker"] .text-gray-300 {
        color: var(--color-base-content) !important;
        opacity: 0.5;
    }
    html[data-theme="hacker"] .text-gray-500 {
        color: var(--color-base-content) !important;
        opacity: 0.7;
    }
    html[data-theme="hacker"] .text-gray-400 {
        color: var(--color-base-content) !important;
        opacity: 0.6;
    }
</style>
@endsection

@section('content')
<div x-data="chatApp()">
    
    <!-- Global Toast Notification -->
    <template x-if="showToast">
        <div class="toast toast-top toast-center z-[200]">
            <div class="alert alert-success text-white bg-emerald-500 border-none shadow-lg py-2 px-4 rounded-lg">
                <span class="text-sm font-medium" x-text="toastMessage"></span>
            </div>
        </div>
    </template>

    <div class="h-[calc(100vh-180px)] -mt-6 -mx-4 md:-mx-8 flex overflow-hidden bg-white relative">

    <!-- Left Sidebar: Chat List -->
    <div class="w-full md:w-[400px] flex flex-col border-r border-gray-200 bg-white" :class="{'hidden md:flex': activeChat}">
        <!-- Header -->
        <div class="h-16 bg-[#f0f2f5] flex items-center justify-between px-4 border-b border-gray-200 shrink-0">
            <div class="avatar">
                <div class="w-10 rounded-full">
                    <img src="{{ Auth::user()->avatar == null ? 'https://ui-avatars.com/api/?name='.Auth::user()->name : asset(Auth::user()->avatar) }}" />
                </div>
            </div>
            <div class="flex gap-2 text-gray-600">
                <button @click="fetchChats()" class="btn btn-ghost btn-circle btn-sm rounded-full" title="{{ __('Sync Chats') }}" :class="{'animate-spin': loading}">
                    <span class="material-icons text-lg">sync</span>
                </button>
                <button @click="showNewChatModal = true" class="btn btn-ghost btn-circle btn-sm rounded-full" title="{{ __('New Chat') }}">
                    <span class="material-icons text-lg">chat</span>
                </button>
                
                <!-- More Dropdown -->
                <div class="dropdown dropdown-end">
                    <button tabindex="0" class="btn btn-ghost btn-circle btn-sm rounded-full" title="{{ __('Menu') }}">
                        <span class="material-icons text-lg">more_vert</span>
                    </button>
                    <ul tabindex="0" class="dropdown-content z-[100] menu p-2 shadow bg-white border border-gray-100 rounded-box w-52 text-gray-700">
                        <li>
                            <a href="{{ url('/user/device') }}" class="hover:bg-gray-100 py-2">
                                <span class="material-icons text-sm mr-2 text-gray-500">arrow_back</span>
                                {{ __('Back to Devices') }}
                            </a>
                        </li>
                        <li>
                            <button @click="triggerLogout()" class="text-red-600 hover:bg-red-50 py-2 rounded-full">
                                <span class="material-icons text-sm mr-2 text-red-500">power_settings_new</span>
                                {{ __('Logout WA Session') }}
                            </button>
                        </li>
                    </ul>
                </div>
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
                       class="input input-sm input-bordered w-full pl-10 bg-gray-100 border-none focus:ring-0 rounded-lg text-gray-700 text-sm" 
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
                    <span class="material-icons text-xl md:text-2xl break-words md:text-3xl break-words md:text-4xl break-words mb-2">chat_bubble_outline</span>
                    <span class="text-sm">{{ __('No chats found') }}</span>
                    <button @click="fetchChats()" class="btn btn-sm btn-link no-underline mt-2 text-emerald-600 rounded-full">{{ __('Try Syncing') }}</button>
                </div>
            </template>

            <template x-for="chat in filteredChats" :key="chat.jid || chat.number">
                <div @click="selectChat(chat)" 
                     class="flex items-center gap-3 p-3 cursor-pointer hover:bg-gray-50 transition-colors border-b border-gray-100"
                     :class="{'bg-[#f0f2f5]': activeChat && (activeChat.jid === chat.jid || activeChat.number === chat.number)}">
                    <div class="avatar placeholder shrink-0">
                        <div class="bg-emerald-500 text-white font-semibold rounded-full w-12 flex items-center justify-center">
                            <span class="text-base" x-text="String(getChatDisplayName(chat)).substring(0, 2).toUpperCase()"></span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <h3 class="font-medium text-gray-900 truncate" x-text="getChatDisplayName(chat)"></h3>
                                <template x-if="hasSavedContact(chat)">
                                    <span class="text-[10px] text-gray-400 shrink-0 font-normal" x-text="'(' + chat.number + ')'"></span>
                                </template>
                            </div>
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
                    <h2 class="text-xl md:text-2xl break-words md:text-3xl break-words font-light text-gray-600 mb-4">{{ __('WhatsApp Web') }}</h2>
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
                        <button @click="activeChat = null" class="md:hidden btn btn-ghost btn-circle btn-sm rounded-full">
                            <span class="material-icons">arrow_back</span>
                        </button>
                        <div class="avatar placeholder">
                            <div class="bg-emerald-500 text-white font-semibold rounded-full w-10 flex items-center justify-center">
                                <span x-text="String(getChatDisplayName(activeChat)).substring(0, 2).toUpperCase()"></span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900" x-text="getChatDisplayName(activeChat)"></h3>
                            <p class="text-xs text-gray-500">
                                <template x-if="activeChat.jid && activeChat.jid.endsWith('@g.us')">
                                    <span>{{ __('Group') }}</span>
                                </template>
                                <template x-if="!(activeChat.jid && activeChat.jid.endsWith('@g.us'))">
                                    <span x-text="hasSavedContact(activeChat) ? activeChat.number : '{{ __('Online') }}'"></span>
                                </template>
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-4 text-gray-600">
                        <button class="btn btn-ghost btn-circle btn-sm rounded-full">
                            <span class="material-icons">search</span>
                        </button>
                        <button class="btn btn-ghost btn-circle btn-sm rounded-full">
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
                                    <div class="chat-bubble shadow-sm max-w-[75%] rounded-lg px-3 py-1.5 flex flex-col animate-fadeIn"
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

                    <!-- Scroll to Bottom Badge (WhatsApp style) -->
                    <template x-if="showScrollBadge">
                        <button @click="scrollToBottom()" class="absolute bottom-[90px] right-6 z-50 btn btn-circle shadow-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-500 w-10 h-10 min-h-0 animate-bounce">
                            <span class="material-icons text-xl">keyboard_arrow_down</span>
                            <template x-if="unreadMessagesCount > 0">
                                <div class="absolute -top-1 -right-1 bg-emerald-500 text-white text-[10px] font-bold h-5 min-w-[1.25rem] rounded-full flex items-center justify-center px-1 shadow-sm" x-text="unreadMessagesCount"></div>
                            </template>
                        </button>
                    </template>
                </div>

                <!-- Input Area -->
                <div class="bg-[#f0f2f5] px-4 py-3 flex items-end gap-2 shrink-0 border-t border-gray-200">
                    
                    <!-- Emoji Picker Trigger & Container -->
                    <div class="relative shrink-0">
                        <div x-show="showEmojiPicker" 
                             @click.away="showEmojiPicker = false" 
                             class="absolute bottom-12 left-0 z-50 bg-white border border-gray-200 rounded-lg p-3 shadow-xl w-64 animate-fadeIn">
                            <h4 class="text-xs font-semibold text-gray-400 mb-2 uppercase">{{ __('Popular Emojis') }}</h4>
                            <div class="grid grid-cols-6 gap-2 text-center text-xl">
                                <template x-for="emoji in ['😀','😂','😍','👍','🙏','❤️','🎉','🔥','👏','💡','🚀','⭐','😎','😊','😅','😉','😮','😢']">
                                    <span @click="insertEmoji(emoji)" class="cursor-pointer hover:bg-gray-100 p-1.5 rounded transition-transform active:scale-95" x-text="emoji"></span>
                                </template>
                            </div>
                        </div>
                        <button @click="showEmojiPicker = !showEmojiPicker" class="btn btn-ghost btn-circle btn-sm text-gray-500 rounded-full" title="{{ __('Emojis') }}">
                            <span class="material-icons text-lg">sentiment_satisfied_alt</span>
                        </button>
                    </div>

                    <!-- Send Template (Attachment Button) -->
                    <button @click="showTemplateModal = true" class="btn btn-ghost btn-circle btn-sm text-gray-500 shrink-0 rounded-full" title="{{ __('Send Predefined Template') }}">
                        <span class="material-icons text-lg">attach_file</span>
                    </button>
                    
                    <div class="flex-1 bg-white rounded-lg flex items-center border border-white focus-within:border-white">
                        <textarea x-model="messageInput" 
                                  @keydown.enter.prevent="sendMessage()"
                                  rows="1"
                                  class="w-full px-4 py-2 bg-transparent border-none focus:ring-0 resize-none max-h-32 text-gray-700 placeholder-gray-400 text-sm" 
                                  placeholder="{{ __('Type a message') }}"></textarea>
                    </div>

                    <button @click="sendMessage()" 
                            class="btn btn-circle btn-sm border-none shrink-0 rounded-full"
                            :class="messageInput.trim() ? 'btn-success text-white' : 'btn-ghost text-gray-500'">
                        <span class="material-icons text-lg" x-text="messageInput.trim() ? 'send' : 'mic'"></span>
                    </button>
                </div>
            </div>
        </template>
    </div>

    </div> <!-- Close the h-[calc(100vh-180px)] overflow-hidden container -->

    <!-- New Chat Modal -->
    <div x-show="showNewChatModal" x-cloak class="modal modal-open" @click.self="showNewChatModal = false">
        <div class="modal-box bg-white max-w-sm flex flex-col max-h-[80vh] z-50">
            <h3 class="font-bold text-lg text-gray-900 mb-2 shrink-0">{{ __('New Chat') }}</h3>
            
            <!-- Direct Number Input -->
            <div class="form-control w-full mb-3 shrink-0">
                <label class="label py-1">
                    <span class="label-text font-medium text-gray-600 text-xs">{{ __('Enter Phone Number') }}</span>
                </label>
                <div class="flex gap-2">
                    <input type="text" 
                           x-model="newChatNumber" 
                           placeholder="e.g. 628123456789" 
                           class="input input-bordered input-sm flex-1 bg-gray-50 border-gray-200 text-gray-800 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none text-sm" 
                           @keydown.enter.prevent="startNewChat()">
                    <button class="btn btn-success btn-sm text-white rounded-full" @click="startNewChat()">{{ __('Start') }}</button>
                </div>
            </div>
            
            <div class="divider text-[10px] text-gray-400 my-1 shrink-0 font-semibold uppercase tracking-wider">{{ __('OR SELECT FROM CONTACTS') }}</div>
            
            <!-- Contacts Search -->
            <div class="relative mb-2 shrink-0">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                    <span class="material-icons text-gray-400 text-sm">search</span>
                </div>
                <input type="text" 
                       x-model="contactSearch" 
                       class="input input-sm input-bordered w-full pl-8 bg-gray-50 border-gray-200 text-gray-800 focus:ring-emerald-500 focus:border-emerald-500 text-xs focus:outline-none" 
                       placeholder="{{ __('Search contacts...') }}">
            </div>
            
            <!-- Contacts List -->
            <div class="flex-1 overflow-y-auto custom-scrollbar my-1 pr-1 min-h-[180px]">
                <template x-if="filteredContacts.length === 0">
                    <p class="text-gray-400 text-xs text-center py-6">{{ __('No contacts found.') }}</p>
                </template>
                <div class="flex flex-col gap-1">
                    <template x-for="contact in filteredContacts" :key="contact.id">
                        <div @click="selectContact(contact)" 
                             class="p-2 border border-gray-50 rounded hover:bg-emerald-50 cursor-pointer transition-colors flex justify-between items-center group">
                            <div class="min-w-0">
                                <h4 class="font-medium text-gray-800 text-xs group-hover:text-emerald-700 truncate" x-text="contact.name"></h4>
                                <span class="text-[10px] text-gray-400" x-text="contact.phone"></span>
                            </div>
                            <span class="material-icons text-gray-300 text-sm group-hover:text-emerald-500 transition-colors">chat</span>
                        </div>
                    </template>
                </div>
            </div>
            
            <div class="modal-action mt-2 shrink-0">
                <button class="btn btn-ghost text-gray-500 btn-sm hover:bg-gray-100 rounded-full" @click="showNewChatModal = false">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>

    <!-- Send Template Modal -->
    <div x-show="showTemplateModal" x-cloak class="modal modal-open" @click.self="showTemplateModal = false">
        <div class="modal-box bg-white max-w-md z-50">
            <h3 class="font-bold text-lg text-gray-900 mb-4">{{ __('Send a Predefined Template') }}</h3>
            <div class="max-h-60 overflow-y-auto custom-scrollbar flex flex-col gap-2">
                @if(count($templates) === 0)
                    <p class="text-gray-400 text-sm text-center py-4">{{ __('No templates found. Go to Template page to create one.') }}</p>
                @else
                    @foreach($templates as $tpl)
                        <div @click="sendTemplate({{ $tpl->id }})" 
                             class="p-3 border border-gray-100 rounded-lg hover:bg-emerald-50 cursor-pointer transition-colors flex justify-between items-center group">
                            <div>
                                <h4 class="font-medium text-gray-800 group-hover:text-emerald-700">{{ $tpl->title }}</h4>
                                <span class="text-[10px] text-gray-400 uppercase font-semibold bg-gray-100 px-1.5 py-0.5 rounded">{{ $tpl->type }}</span>
                            </div>
                            <span class="material-icons text-gray-300 group-hover:text-emerald-500 transition-colors">send</span>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="modal-action">
                <button class="btn btn-ghost text-gray-500 btn-sm hover:bg-gray-100 rounded-full" @click="showTemplateModal = false">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>

</div>

@push('js')
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

            // Contacts list loaded from DB
            contactsList: @json($contacts ?? []) || [],
            contactSearch: '',

            // Dialog and popup toggles
            showNewChatModal: false,
            showTemplateModal: false,
            showEmojiPicker: false,
            newChatNumber: '',


            // Polling and UX
            pollingMessages: false,
            showScrollBadge: false,
            unreadMessagesCount: 0,
            shouldAutoScroll: true,
            chatListInterval: null,
            messageInterval: null,
            notificationAudio: new Audio('data:audio/wav;base64,UklGRmYBAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YUoBAAApSjN/h4+XoaeqrK2ur7CwsbKztLW2t7i5uru8vb6/wMHCw8TFxsfIycrLzM3Oz9DR0tPU1dbX2Nna29zd3t/g4eLj5OXm5+jp6uvs7e7v8PHy8/T19vf4+fr7/P3+/wAAAQIDBAUGBwgJCgsMDQ4PEBESExQVFhcYGRobHB0eHx8gISIjJCUmJygpKissLS4vMDEyMzQ1Njc4OTo7PD0+P0BBQkNERUZHSElKS0xNTk9QUVJTVFVWV1hZWltcXV5fYGFiY2RlZmdoaWprbG1ub3BxcnN0dXZ3eHl6e3x9fn+AgYKDhIWGh4iJiouMjY6PkJGSk5SVlpeYmZqbnJ2en6ChoqOkpaanqKmqq6ytrq+wsbKztLW2t7i5uru8vb6/wMHCw8TFxsfIycrLzM3Oz9DR0tPU1dbX2Nna29zd3t/g4eLj5OXm5+jp6uvs7e7v8PHy8/T19vf4+fr7/P3+/w=='),

            // Toast system
            showToast: false,
            toastMessage: '',

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

            get filteredContacts() {
                if (!this.contactsList || !Array.isArray(this.contactsList)) return [];
                if (!this.contactSearch) return this.contactsList;
                const query = this.contactSearch.toLowerCase();
                return this.contactsList.filter(c => {
                    const name = String(c.name || '').toLowerCase();
                    const phone = String(c.phone || '').toLowerCase();
                    return name.includes(query) || phone.includes(query);
                });
            },

            init() {
                this.fetchChats();

                // Connect to Socket.io server
                let socketUrl = '{{ config("services.whatsapp.server_url") }}';
                // Try to upgrade to HTTPS if current site is HTTPS to avoid mixed content
                if (window.location.protocol === 'https:' && socketUrl.startsWith('http://')) {
                    socketUrl = socketUrl.replace('http://', 'https://');
                }

                const startPolling = () => {
                    if (!this.chatListInterval) {
                        this.chatListInterval = setInterval(() => this.silentFetchChats(), 5000);
                    }
                    if (!this.messageInterval) {
                        this.messageInterval = setInterval(() => {
                            if (this.activeChat && !this.pollingMessages) {
                                this.fetchMessages(this.activeChat, true);
                            }
                        }, 1500);
                    }
                };

                const stopPolling = () => {
                    if (this.chatListInterval) { clearInterval(this.chatListInterval); this.chatListInterval = null; }
                    if (this.messageInterval) { clearInterval(this.messageInterval); this.messageInterval = null; }
                };

                if (typeof io !== 'undefined') {
                    const socket = io(socketUrl, {
                        reconnectionAttempts: 3,
                        timeout: 5000
                    });
                    
                    socket.on('connect', () => {
                        console.log('Socket connected successfully, stopping polling');
                        stopPolling();
                    });

                    socket.on('connect_error', (error) => {
                        console.warn('Socket connection failed, falling back to polling:', error);
                        startPolling();
                    });

                    socket.on('new_message', (data) => {
                        if (data.sessionId !== 'device_{{ $device->id }}') return;
                        
                        this.silentFetchChats();

                        if (this.activeChat) {
                            const activeJid = this.activeChat.jid || (this.activeChat.number ? this.activeChat.number + '@s.whatsapp.net' : '');
                            const isGroup = activeJid.endsWith('@g.us');
                            
                            let compareActiveJid = activeJid;
                            if (compareActiveJid === data.remoteJid) {
                                if (!this.pollingMessages) {
                                    this.fetchMessages(this.activeChat, true);
                                }
                            }
                        } else {
                            if (!data.message.key.fromMe) {
                                this.notificationAudio.play().catch(e => console.log('Audio play blocked', e));
                            }
                        }
                    });
                } else {
                    // Fallback to polling if socket.io library fails to load completely
                    startPolling();
                }

                // Instantly fetch when user returns to tab
                document.addEventListener("visibilitychange", () => {
                    if (document.visibilityState === "visible") {
                        if (this.activeChat && !this.pollingMessages) {
                            this.fetchMessages(this.activeChat, true);
                        }
                        this.silentFetchChats();
                    }
                });

                // Scroll detection for smart scrolling
                this.$nextTick(() => {
                    const container = document.getElementById('messages-container');
                    if (container) {
                        container.addEventListener('scroll', () => {
                            const isAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 50;
                            if (isAtBottom) {
                                this.showScrollBadge = false;
                                this.unreadMessagesCount = 0;
                                this.shouldAutoScroll = true;
                            } else {
                                this.shouldAutoScroll = false;
                            }
                        });
                    }
                });
            },

            scrollToBottom() {
                const container = document.getElementById('messages-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                    this.showScrollBadge = false;
                    this.unreadMessagesCount = 0;
                    this.shouldAutoScroll = true;
                }
            },

            playNotification() {
                try {
                    this.notificationAudio.currentTime = 0;
                    this.notificationAudio.play().catch(e => console.log('Audio play prevented', e));
                } catch(e) {}
            },

            triggerToast(msg) {
                this.toastMessage = msg;
                this.showToast = true;
                setTimeout(() => {
                    this.showToast = false;
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
                this.showScrollBadge = false;
                this.unreadMessagesCount = 0;
                this.shouldAutoScroll = true;
                this.pollingMessages = false;
                this.fetchMessages(chat, false);
            },

            fetchMessages(chat, silent = false) {
                if (!chat) return;
                const jid = chat.jid || (chat.number ? chat.number + '@s.whatsapp.net' : '');
                if (!jid) return;

                if (this.pollingMessages && silent) return;
                this.pollingMessages = true;

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
                        
                        const newLength = data.length;
                        const newLastId = newLength > 0 ? data[newLength - 1].key.id : null;

                        let hasNewIncomingMessage = false;

                        // Only reassign if data actually changed to prevent DOM flicker
                        if (oldLastId !== newLastId || oldLength !== newLength) {
                            // Check if the new message is an incoming message
                            if (oldLength > 0 && newLastId && oldLastId !== newLastId) {
                                const newestMsg = data[newLength - 1];
                                if (!newestMsg.key.fromMe) {
                                    hasNewIncomingMessage = true;
                                }
                            }
                            
                            this.activeChatMessages = data;

                            this.$nextTick(() => {
                                if (oldLength === 0) {
                                    // Initial load, always scroll to bottom
                                    this.scrollToBottom();
                                } else if (hasNewIncomingMessage) {
                                    // New incoming message
                                    if (this.shouldAutoScroll) {
                                        this.scrollToBottom();
                                    } else {
                                        this.showScrollBadge = true;
                                        this.unreadMessagesCount++;
                                    }
                                    // Play sound for incoming message
                                    this.playNotification();
                                } else if (oldLastId !== newLastId) {
                                    // Likely user sent a message (fromMe = true) or replaced tempId
                                    this.scrollToBottom();
                                }
                            });
                        } else {
                            // Soft update for statuses without replacing the array to avoid DOM flicker
                            for (let i = 0; i < data.length; i++) {
                                if (this.activeChatMessages[i] && this.activeChatMessages[i].key.id === data[i].key.id) {
                                    if (this.activeChatMessages[i].status !== data[i].status) {
                                        this.activeChatMessages[i].status = data[i].status;
                                    }
                                }
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching messages:', error);
                })
                .finally(() => {
                    this.loadingMessages = false;
                    this.pollingMessages = false;
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
                        this.triggerToast('Failed to send message.');
                    } else {
                        // Success - silently fetch messages to sync the true message list from Baileys
                        this.fetchMessages(this.activeChat, true);
                    }
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                    const found = this.activeChatMessages.find(m => m.key.id === tempId);
                    if (found) found.status = 0;
                    this.triggerToast('Failed to send message.');
                });
            },

            startNewChat() {
                if (!this.newChatNumber.trim()) return;
                let num = this.newChatNumber.replace(/\D/g, '');
                if (num.startsWith('0')) {
                    num = '62' + num.substring(1);
                }
                if (num.length < 9) {
                    alert('Please enter a valid phone number with country code.');
                    return;
                }

                const targetJid = num + '@s.whatsapp.net';
                
                // Check if already in list
                let existing = this.chats.find(c => c.jid === targetJid || c.number === num);
                if (!existing) {
                    existing = {
                        number: num,
                        jid: targetJid,
                        name: num,
                        unread: 0,
                        timestamp: Math.floor(Date.now() / 1000),
                        last_message: ''
                    };
                    this.chats.unshift(existing);
                }

                this.selectChat(existing);
                this.showNewChatModal = false;
                this.newChatNumber = '';
                this.triggerToast('New chat opened for: ' + num);
            },

            selectContact(contact) {
                let num = String(contact.phone).replace(/\D/g, '');
                if (num.startsWith('0')) {
                    num = '62' + num.substring(1);
                }
                const targetJid = num + '@s.whatsapp.net';

                let existing = this.chats.find(c => c.jid === targetJid || c.number === num);
                if (!existing) {
                    existing = {
                        number: num,
                        jid: targetJid,
                        name: contact.name,
                        unread: 0,
                        timestamp: Math.floor(Date.now() / 1000),
                        last_message: ''
                    };
                    this.chats.unshift(existing);
                } else {
                    existing.name = contact.name; // Keep name synced
                }

                this.selectChat(existing);
                this.showNewChatModal = false;
                this.contactSearch = '';
                this.triggerToast('Chat opened for: ' + contact.name);
            },

            sendTemplate(templateId) {
                if (!this.activeChat) return;
                const jid = this.activeChat.jid || (this.activeChat.number ? this.activeChat.number + '@s.whatsapp.net' : '');
                const receiver = this.activeChat.number;
                
                this.showTemplateModal = false;
                this.triggerToast('Sending template...');

                // Optimistic indicator
                const tempId = 'temp_tpl_' + Date.now();
                this.activeChatMessages.push({
                    key: {
                        id: tempId,
                        fromMe: true,
                        remoteJid: jid
                    },
                    message: {
                        conversation: '[Sending Template...]'
                    },
                    messageTimestamp: Math.floor(Date.now() / 1000),
                    status: 1
                });

                this.$nextTick(() => {
                    const container = document.getElementById('messages-container');
                    if (container) container.scrollTop = container.scrollHeight;
                });

                const isGroup = jid.endsWith('@g.us');
                const url = isGroup 
                    ? '{{ route("user.group.send-message", $device->uuid) }}' 
                    : '{{ route("user.chat.send-message", $device->uuid) }}';

                const bodyPayload = isGroup 
                    ? {
                        group: jid,
                        group_name: this.activeChat.name || this.activeChat.number,
                        selecttype: 'template',
                        template: templateId
                      }
                    : {
                        reciver: receiver,
                        selecttype: 'template',
                        template: templateId
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
                        console.error('Template send failed:', data.error);
                        const found = this.activeChatMessages.find(m => m.key.id === tempId);
                        if (found) found.status = 0;
                        this.triggerToast('Failed to send template.');
                    } else {
                        this.triggerToast('Template sent successfully!');
                        this.fetchMessages(this.activeChat, true);
                    }
                })
                .catch(error => {
                    console.error('Template send error:', error);
                    const found = this.activeChatMessages.find(m => m.key.id === tempId);
                    if (found) found.status = 0;
                    this.triggerToast('Failed to send template.');
                });
            },

            triggerLogout() {
                if (!confirm('Are you sure you want to logout your WhatsApp session? This will disconnect your device.')) return;
                
                this.triggerToast('Logging out WhatsApp session...');

                fetch('{{ url("/user/logout-session/".$device->uuid) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    this.triggerToast('Logged out successfully.');
                    setTimeout(() => {
                        window.location.href = '{{ url("/user/device") }}';
                    }, 1000);
                })
                .catch(error => {
                    console.error('Logout error:', error);
                    this.triggerToast('Failed to logout session.');
                });
            },

            insertEmoji(emoji) {
                this.messageInput += emoji;
                this.showEmojiPicker = false;
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
            },

            getChatDisplayName(chat) {
                if (!chat) return '';
                if (chat.jid && chat.jid.endsWith('@g.us')) {
                    return chat.name || chat.pushName || chat.number;
                }
                return chat.name || chat.pushName || chat.number;
            },

            hasSavedContact(chat) {
                if (!chat) return false;
                if (chat.jid && chat.jid.endsWith('@g.us')) {
                    return false;
                }
                return !!(chat.name || chat.pushName);
            },

            getContactByNumber(number) {
                if (!number) return null;
                if (!this.contactsList || !Array.isArray(this.contactsList)) return null;
                const cleanNum = String(number).replace(/\D/g, '');
                
                const normalize = (num) => {
                    let s = String(num).replace(/\D/g, '');
                    if (s.startsWith('0')) {
                        s = '62' + s.substring(1);
                    }
                    return s;
                };

                const normNum = normalize(cleanNum);

                return this.contactsList.find(c => {
                    if (!c.phone) return false;
                    return normalize(c.phone) === normNum;
                });
            }
        }
    }
</script>
@endpush
@endsection