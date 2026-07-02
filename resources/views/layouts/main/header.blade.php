<header class="bg-base-100 border-b border-base-300 sticky top-0 z-30 shadow-sm">
  <div class="px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      
      <!-- Left: Mobile Menu Button -->
      <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" 
                class="md:hidden p-2 text-base-content/75 hover:bg-base-200 transition-colors rounded-full">
          <span class="material-icons">menu</span>
        </button>
        
        <!-- Page Title (Desktop) -->
        <div class="hidden md:block">
          <h1 class="text-xl font-semibold text-base-content">@yield('page-title', 'Dashboard')</h1>
        </div>
      </div>

      <!-- Right: Actions & User Menu -->
      <div class="flex items-center gap-2">
        
        @if(Request::is('user/*'))
        <!-- Notifications -->
        <div class="dropdown dropdown-end notifications-area hidden">
          <button tabindex="0" class="btn btn-ghost btn-circle rounded-full">
            <div class="indicator">
              <span class="material-icons text-base-content/75">notifications</span>
              <span class="badge badge-xs badge-primary indicator-item notification-count">0</span>
            </div>
          </button>
          <div tabindex="0" class="dropdown-content z-50 card card-compact w-80 md:w-96 p-0 shadow-2xl bg-base-100 mt-3 border border-base-300 rounded-2xl">
            <div class="card-body p-0">
              <!-- Header -->
              <div class="px-4 py-3 border-b border-base-300">
                <h3 class="font-semibold text-base-content">{{ __('Notifications') }}</h3>
                <p class="text-sm text-base-content/60">{{ __('You have') }} <span class="font-semibold text-primary notification-count">0</span> {{ __('notifications') }}</p>
              </div>
              
              <!-- Notifications List -->
              <div class="notifications-list max-h-96 overflow-y-auto">
                <!-- Will be populated by JavaScript -->
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="dropdown dropdown-end hidden md:block">
          <button tabindex="0" class="btn btn-ghost btn-circle rounded-full">
            <span class="material-icons text-base-content/75">apps</span>
          </button>
          <div tabindex="0" class="dropdown-content z-50 card card-compact w-80 p-4 shadow-2xl bg-base-100 mt-3 border border-base-300 rounded-2xl">
            <h3 class="font-semibold text-base-content mb-3">{{ __('Quick Actions') }}</h3>
            <div class="grid grid-cols-3 gap-3">
              <a href="{{ url('/user/device') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-base-200 transition-colors">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white shadow-lg">
                  <span class="material-icons">devices</span>
                </div>
                <span class="text-xs font-medium text-base-content/80">{{ __('Devices') }}</span>
              </a>
              
              <a href="{{ url('/user/sent-text-message') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-base-200 transition-colors">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white shadow-lg">
                  <span class="material-icons">send</span>
                </div>
                <span class="text-xs font-medium text-base-content/80">{{ __('Single Send') }}</span>
              </a>
              
              <a href="{{ url('/user/bulk-message') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-base-200 transition-colors">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white shadow-lg">
                  <span class="material-icons">rocket_launch</span>
                </div>
                <span class="text-xs font-medium text-base-content/80">{{ __('Bulk Send') }}</span>
              </a>
              
              <a href="{{ url('/user/chatbot') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-base-200 transition-colors">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg">
                  <span class="material-icons">smart_toy</span>
                </div>
                <span class="text-xs font-medium text-base-content/80">{{ __('Chatbot') }}</span>
              </a>
              
              <a href="{{ url('/user/contact') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-base-200 transition-colors">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center text-white shadow-lg">
                  <span class="material-icons">contacts</span>
                </div>
                <span class="text-xs font-medium text-base-content/80">{{ __('Contacts') }}</span>
              </a>
              
              <a href="{{ url('/user/logs') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-base-200 transition-colors">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white shadow-lg">
                  <span class="material-icons">article</span>
                </div>
                <span class="text-xs font-medium text-base-content/80">{{ __('Reports') }}</span>
              </a>
            </div>
            
            <div class="divider my-1"></div>
            
            <button onclick="toggleTheme()" class="w-full flex items-center justify-between p-3 rounded-xl hover:bg-base-200 transition-colors">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-base-300 flex items-center justify-center text-base-content">
                  <span class="material-icons" id="theme-icon">dark_mode</span>
                </div>
                <div class="text-left">
                  <div class="text-sm font-semibold text-base-content">{{ __('Theme') }}</div>
                  <div class="text-xs text-base-content/60" id="theme-text">{{ __('Hacker (Dark)') }}</div>
                </div>
              </div>
              <span class="material-icons text-base-content/40">swap_horiz</span>
            </button>
            
            <script>
                const THEMES = [
                  { id: 'mptheme', icon: 'gamepad', name: 'MP HUD (Light)' },
                  { id: 'profesional', icon: 'light_mode', name: 'Professional (Light)' },
                  { id: 'hacker', icon: 'dark_mode', name: 'Hacker (Dark)' },
                  { id: 'ocean', icon: 'water_drop', name: 'Ocean Breeze (Blue)' },
                  { id: 'aurora', icon: 'auto_awesome', name: 'Aurora (Eye-Catching)' }
                ];

                function toggleTheme() {
                  var currentTheme = localStorage.getItem('app-theme') || 'profesional';
                  var currentIndex = THEMES.findIndex(t => t.id === currentTheme);
                  var nextIndex = (currentIndex + 1) % THEMES.length;
                  var newTheme = THEMES[nextIndex].id;
                  
                  localStorage.setItem('app-theme', newTheme);
                  document.documentElement.setAttribute('data-theme', newTheme);
                  updateThemeUI();
                }
                
                function updateThemeUI() {
                  var currentTheme = localStorage.getItem('app-theme') || 'profesional';
                  var themeObj = THEMES.find(t => t.id === currentTheme) || THEMES[0];
                  
                  var icon = document.getElementById('theme-icon');
                  var text = document.getElementById('theme-text');
                  var mobIcon = document.getElementById('mobile-theme-icon');
                  var mobText = document.getElementById('mobile-theme-text');
                  
                  if(icon && text) {
                    icon.textContent = themeObj.icon;
                    text.textContent = themeObj.name;
                  }
                  
                  if(mobIcon && mobText) {
                    mobIcon.textContent = themeObj.icon;
                    mobText.textContent = themeObj.name;
                  }
                }
                
                // Run on load
                document.addEventListener('DOMContentLoaded', updateThemeUI);
              </script>
          </div>
        </div>
        @endif

        <!-- User Profile Dropdown -->
        <div class="dropdown dropdown-end">
          <button tabindex="0" class="btn btn-ghost gap-2 pl-2 pr-3">
            <div class="avatar">
              <div class="w-9 h-9 rounded-full ring-2 ring-primary/20">
                <img src="{{ Auth::user()->avatar == null ? 'https://ui-avatars.com/api/?name='.Auth::user()->name : asset(Auth::user()->avatar) }}" 
                     alt="{{ Auth::user()->name }}" />
              </div>
            </div>
            <div class="hidden md:flex flex-col items-start">
              <span class="text-sm font-semibold text-base-content">{{ Auth::user()->name }}</span>
            </div>
            <span class="material-icons text-base-content/40 text-sm hidden md:block">expand_more</span>
          </button>
          
          <div tabindex="0" class="dropdown-content z-50 card card-compact w-64 p-0 shadow-2xl bg-base-100 mt-3 border border-base-300 rounded-2xl overflow-hidden">
            <!-- User Info Header -->
            <div class="bg-gradient-to-br from-primary to-primary/80 px-4 py-4 text-primary-content">
              <div class="flex items-center gap-3">
                <div class="avatar">
                  <div class="w-12 h-12 rounded-full ring-2 ring-white/30">
                    <img src="{{ Auth::user()->avatar == null ? 'https://ui-avatars.com/api/?name='.Auth::user()->name : asset(Auth::user()->avatar) }}" 
                         alt="{{ Auth::user()->name }}" />
                  </div>
                </div>
                <div>
                  <h4 class="font-semibold text-white">{{ Auth::user()->name }}</h4>
                  <p class="text-xs opacity-90 text-white/90">{{ Auth::user()->email }}</p>
                </div>
              </div>
            </div>

            <!-- Menu Items -->
            <div class="p-2">

              
              @if(Request::is('user/*'))
              <a href="{{ url('/user/subscription') }}" 
                 class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-base-200 transition-colors">
                <span class="material-icons text-base-content/70 text-xl">card_membership</span>
                <span class="text-sm font-medium text-base-content/85">{{ __('Subscription') }}</span>
              </a>
              
              <a href="{{ url('/user/auth-key') }}" 
                 class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-base-200 transition-colors">
                <span class="material-icons text-base-content/70 text-xl">vpn_key</span>
                <span class="text-sm font-medium text-base-content/85">{{ __('Auth Key') }}</span>
              </a>
              @endif
              
              <a href="{{ Request::is('user/*') ? url('/user/support') : url('/admin/support') }}" 
                 class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-base-200 transition-colors">
                <span class="material-icons text-base-content/70 text-xl">support_agent</span>
                <span class="text-sm font-medium text-base-content/85">{{ __('Support') }}</span>
              </a>
              
              <div class="divider my-1"></div>
              
              <button class="logout-button flex items-center gap-3 px-3 py-2 hover:bg-red-500/10 transition-colors text-red-500 w-full rounded-full">
                <span class="material-icons text-xl">logout</span>
                <span class="text-sm font-medium">{{ __('Logout') }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>