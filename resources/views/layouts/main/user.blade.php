<div class="h-full flex flex-col bg-base-100">
  
  <!-- Logo -->
  <div class="p-4 border-b border-base-300">
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 rounded bg-primary flex items-center justify-center">
        <span class="material-icons text-white text-base">send</span>
      </div>
      <span class="font-bold text-sm">WASender</span>
    </div>
  </div>

  <!-- Menu -->
  <div class="flex-1 overflow-y-auto p-3 space-y-6">
    
    <!-- MAIN -->
    <div>
      <div class="px-2 mb-1">
        <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Main</p>
      </div>
      <div class="space-y-1">
        <a href="{{ route('user.dashboard.index') }}" class="nav-item {{ Request::is('user/dashboard*') ? 'active' : '' }}">
          <span class="material-icons">dashboard</span>
          <span>Dashboard</span>
        </a>
        <a href="{{ route('user.device.index') }}" class="nav-item {{ Request::is('user/device*') ? 'active' : '' }}">
          <span class="material-icons">devices</span>
          <span>Devices</span>
        </a>
      </div>
    </div>

    <!-- MESSAGING -->
    <div>
      <div class="px-2 mb-1">
        <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Messaging</p>
      </div>
      <div class="space-y-1">
        <a href="{{ url('user/sent-text-message') }}" class="nav-item {{ Request::is('user/sent-text-message*') ? 'active' : '' }}">
          <span class="material-icons">send</span>
          <span>Kirim Pesan</span>
        </a>
        <a href="{{ url('user/bulk-message') }}" class="nav-item {{ Request::is('user/bulk-message*') ? 'active' : '' }}">
          <span class="material-icons">campaign</span>
          <span>Bulk Message</span>
        </a>
        <a href="{{ url('user/schedule-message') }}" class="nav-item {{ Request::is('user/schedule-message*') ? 'active' : '' }}">
          <span class="material-icons">schedule</span>
          <span>Schedule</span>
        </a>
      </div>
    </div>

    <!-- AUTOMATION -->
    <div>
      <div class="px-2 mb-1">
        <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Automation</p>
      </div>
      <div class="space-y-1">
        <a href="{{ route('user.chatbot.index') }}" class="nav-item {{ Request::is('user/chatbot*') ? 'active' : '' }}">
          <span class="material-icons">smart_toy</span>
          <span>Chatbot</span>
        </a>
        <a href="{{ url('user/ai-response') }}" class="nav-item nav-item-ai {{ Request::is('user/ai-response*') ? 'active' : '' }}">
          <span class="material-icons">psychology</span>
          <span>AI Response</span>
          <span class="badge badge-xs badge-success ml-auto">AI</span>
        </a>
      </div>
    </div>

    <!-- MANAGEMENT -->
    <div>
      <div class="px-2 mb-1">
        <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Management</p>
      </div>
      <div class="space-y-1">
        <a href="{{ route('user.contact.index') }}" class="nav-item {{ Request::is('user/contact*') ? 'active' : '' }}">
          <span class="material-icons">contacts</span>
          <span>Contacts</span>
        </a>
        <a href="{{ url('user/template') }}" class="nav-item {{ Request::is('user/template*') ? 'active' : '' }}">
          <span class="material-icons">description</span>
          <span>Templates</span>
        </a>
        <a href="{{ route('user.apps.index') }}" class="nav-item {{ Request::is('user/apps*') ? 'active' : '' }}">
          <span class="material-icons">apps</span>
          <span>Apps</span>
        </a>
      </div>
    </div>

    <!-- REPORTS -->
    <div>
      <div class="px-2 mb-1">
        <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Reports</p>
      </div>
      <div class="space-y-1">
        <a href="{{ url('user/logs') }}" class="nav-item {{ Request::is('user/logs*') ? 'active' : '' }}">
          <span class="material-icons">receipt_long</span>
          <span>Message Logs</span>
        </a>
        <a href="{{ url('user/webhooks') }}" class="nav-item {{ Request::is('user/webhooks*') ? 'active' : '' }}">
          <span class="material-icons">webhook</span>
          <span>Webhooks</span>
        </a>
      </div>
    </div>

    <!-- SETTINGS -->
    <div>
      <div class="px-2 mb-1">
        <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Settings</p>
      </div>
      <div class="space-y-1">
        <a href="{{ url('/user/subscription') }}" class="nav-item {{ Request::is('user/subscription*') ? 'active' : '' }}">
          <span class="material-icons">card_membership</span>
          <span>Subscription</span>
        </a>
        <a href="{{ url('/user/profile') }}" class="nav-item {{ Request::is('user/profile*') ? 'active' : '' }}">
          <span class="material-icons">person</span>
          <span>Profile</span>
        </a>
        <a href="{{ url('/user/auth-key') }}" class="nav-item {{ Request::is('user/auth-key*') ? 'active' : '' }}">
          <span class="material-icons">key</span>
          <span>API Key</span>
        </a>
        <a href="{{ url('/user/support') }}" class="nav-item {{ Request::is('user/support*') ? 'active' : '' }}">
          <span class="material-icons">support</span>
          <span>Support</span>
        </a>
      </div>
    </div>

  </div>

  <!-- Logout -->
  <div class="p-3 border-t border-base-300">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="nav-item nav-item-logout w-full">
        <span class="material-icons">logout</span>
        <span>Logout</span>
      </button>
    </form>
  </div>

</div>

<style>
.nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 500;
  color: rgb(var(--bc) / 0.7);
  transition: all 0.2s ease;
  text-decoration: none;
  position: relative;
}

.nav-item .material-icons {
  font-size: 18px;
  width: 18px;
  height: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.nav-item:hover {
  background: rgb(var(--b2));
  color: rgb(var(--bc));
  transform: translateX(2px);
}

.nav-item.active {
  background: linear-gradient(to right, rgb(147 51 234), rgb(126 34 206));
  color: white;
  font-weight: 600;
  box-shadow: 0 2px 12px rgb(147 51 234 / 0.4);
}

.nav-item.active::before {
  content: '';
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  width: 3px;
  height: 60%;
  background: white;
  border-radius: 0 2px 2px 0;
}

.nav-item.active .material-icons {
  transform: scale(1.1);
}

.nav-item-ai:not(.active) {
  background: rgb(168 85 247 / 0.1);
  color: rgb(147 51 234);
  border: 1px solid rgb(168 85 247 / 0.2);
}

.nav-item-ai:not(.active):hover {
  background: rgb(168 85 247 / 0.15);
  border-color: rgb(168 85 247 / 0.3);
  transform: translateX(2px);
}

.nav-item-ai.active {
  background: linear-gradient(to right, rgb(147 51 234), rgb(126 34 206));
  color: white;
  font-weight: 600;
  border: none;
  box-shadow: 0 2px 12px rgb(147 51 234 / 0.4);
}

.nav-item-logout {
  color: rgb(220 38 38);
  background: transparent;
}

.nav-item-logout:hover {
  background: rgb(220 38 38 / 0.1);
  transform: translateX(2px);
}
</style>
