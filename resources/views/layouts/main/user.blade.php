<div class="h-full flex flex-col">

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
          <span class="nav-dot"></span>
        </a>
        <a href="{{ route('user.device.index') }}" class="nav-item {{ Request::is('user/device*') ? 'active' : '' }}">
          <span class="material-icons">devices</span>
          <span>Devices</span>
          <span class="nav-dot"></span>
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
          <span class="nav-dot"></span>
        </a>
        <a href="{{ url('user/bulk-message') }}" class="nav-item {{ Request::is('user/bulk-message*') ? 'active' : '' }}">
          <span class="material-icons">campaign</span>
          <span>Bulk Message</span>
          <span class="nav-dot"></span>
        </a>
        <a href="{{ url('user/schedule-message') }}" class="nav-item {{ Request::is('user/schedule-message*') ? 'active' : '' }}">
          <span class="material-icons">schedule</span>
          <span>Schedule</span>
          <span class="nav-dot"></span>
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
          <span class="nav-dot"></span>
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
          <span class="nav-dot"></span>
        </a>
        <a href="{{ url('user/template') }}" class="nav-item {{ Request::is('user/template*') ? 'active' : '' }}">
          <span class="material-icons">description</span>
          <span>Templates</span>
          <span class="nav-dot"></span>
        </a>
        <a href="{{ route('user.apps.index') }}" class="nav-item {{ Request::is('user/apps*') ? 'active' : '' }}">
          <span class="material-icons">apps</span>
          <span>Apps</span>
          <span class="nav-dot"></span>
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
          <span class="nav-dot"></span>
        </a>
        <a href="{{ url('user/webhooks') }}" class="nav-item {{ Request::is('user/webhooks*') ? 'active' : '' }}">
          <span class="material-icons">webhook</span>
          <span>Webhooks</span>
          <span class="nav-dot"></span>
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
          <span class="nav-dot"></span>
        </a>
        <a href="{{ url('/user/profile') }}" class="nav-item {{ Request::is('user/profile*') ? 'active' : '' }}">
          <span class="material-icons">person</span>
          <span>Profile</span>
          <span class="nav-dot"></span>
        </a>
        <a href="{{ url('/user/auth-key') }}" class="nav-item {{ Request::is('user/auth-key*') ? 'active' : '' }}">
          <span class="material-icons">key</span>
          <span>API Key</span>
          <span class="nav-dot"></span>
        </a>
        <a href="{{ url('/user/support') }}" class="nav-item {{ Request::is('user/support*') ? 'active' : '' }}">
          <span class="material-icons">support</span>
          <span>Support</span>
          <span class="nav-dot"></span>
        </a>
      </div>
    </div>

  </div>

  <!-- Logout -->
  <div class="p-3 border-t border-base-300">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="nav-item nav-item-logout w-full rounded-full">
        <span class="material-icons">logout</span>
        <span>Logout</span>
      </button>
    </form>
  </div>

</div>

<style>
.nav-item {
  display: flex !important;
  align-items: center !important;
  gap: 10px !important;
  padding: 9px 12px !important;
  border-radius: 10px !important;
  font-size: 14px !important;
  font-weight: 500 !important;
  color: var(--color-base-content) !important;
  background: transparent !important;
  background-color: transparent !important;
  background-image: none !important;
  box-shadow: none !important;
  opacity: 0.5;
  transition: color 0.15s ease, opacity 0.15s ease;
  text-decoration: none !important;
}

.nav-item .material-icons {
  font-size: 18px;
  width: 18px;
  height: 18px;
  flex-shrink: 0;
}

/* Grey dot — inactive */
.nav-dot {
  display: inline-block;
  width: 6px;
  height: 6px;
  min-width: 6px;
  border-radius: 50%;
  background-color: #3a3a3a;
  margin-left: auto;
  flex-shrink: 0;
}

/* Hover */
.nav-item:hover {
  background: transparent !important;
  background-color: transparent !important;
  background-image: none !important;
  color: var(--color-base-content) !important;
  opacity: 0.85;
}

/* Active — text green only, NO background */
.nav-item.active {
  background: transparent !important;
  background-color: transparent !important;
  background-image: none !important;
  box-shadow: none !important;
  color: var(--color-primary) !important;
  font-weight: 600 !important;
  opacity: 1;
}

/* Green pulsing dot when active */
.nav-item.active .nav-dot {
  background-color: var(--color-primary);
  animation: nav-dot-pulse 1.4s ease-in-out infinite;
}

@keyframes nav-dot-pulse {
  0%   { box-shadow: 0 0 0 0 rgba(0, 232, 122, 0.6); }
  70%  { box-shadow: 0 0 0 6px rgba(0, 232, 122, 0); }
  100% { box-shadow: 0 0 0 0 rgba(0, 232, 122, 0); }
}

/* AI item */
.nav-item-ai:not(.active) {
  border: 1px solid var(--color-base-300) !important;
  opacity: 0.65;
}
.nav-item-ai:not(.active):hover {
  color: var(--color-primary) !important;
  border-color: var(--color-primary) !important;
  opacity: 1;
}
.nav-item-ai.active {
  border: 1px solid rgba(0, 232, 122, 0.25) !important;
}

/* Logout */
.nav-item-logout {
  color: var(--color-error) !important;
  opacity: 0.65;
}
.nav-item-logout:hover {
  color: var(--color-error) !important;
  opacity: 1;
}
</style>
