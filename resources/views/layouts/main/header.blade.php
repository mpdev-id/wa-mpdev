<header class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
  <div class="px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      
      <!-- Left: Mobile Menu Button -->
      <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" 
                class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors">
          <span class="material-icons">menu</span>
        </button>
        
        <!-- Page Title (Desktop) -->
        <div class="hidden md:block">
          <h1 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
        </div>
      </div>

      <!-- Right: Actions & User Menu -->
      <div class="flex items-center gap-2">
        
        @if(Request::is('user/*'))
        <!-- Notifications -->
        <div class="dropdown dropdown-end notifications-area hidden">
          <button tabindex="0" class="btn btn-ghost btn-circle">
            <div class="indicator">
              <span class="material-icons text-gray-600">notifications</span>
              <span class="badge badge-xs badge-primary indicator-item notification-count">0</span>
            </div>
          </button>
          <div tabindex="0" class="dropdown-content z-50 card card-compact w-80 md:w-96 p-0 shadow-2xl bg-base-100 mt-3 border border-gray-200 rounded-2xl">
            <div class="card-body p-0">
              <!-- Header -->
              <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-900">{{ __('Notifications') }}</h3>
                <p class="text-sm text-gray-500">{{ __('You have') }} <span class="font-semibold text-primary notification-count">0</span> {{ __('notifications') }}</p>
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
          <button tabindex="0" class="btn btn-ghost btn-circle">
            <span class="material-icons text-gray-600">apps</span>
          </button>
          <div tabindex="0" class="dropdown-content z-50 card card-compact w-80 p-4 shadow-2xl bg-base-100 mt-3 border border-gray-200 rounded-2xl">
            <h3 class="font-semibold text-gray-900 mb-3">{{ __('Quick Actions') }}</h3>
            <div class="grid grid-cols-3 gap-3">
              <a href="{{ url('/user/device') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-gray-100 transition-colors">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white shadow-lg">
                  <span class="material-icons">devices</span>
                </div>
                <span class="text-xs font-medium text-gray-700">{{ __('Devices') }}</span>
              </a>
              
              <a href="{{ url('/user/sent-text-message') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-gray-100 transition-colors">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white shadow-lg">
                  <span class="material-icons">send</span>
                </div>
                <span class="text-xs font-medium text-gray-700">{{ __('Single Send') }}</span>
              </a>
              
              <a href="{{ url('/user/bulk-message') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-gray-100 transition-colors">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white shadow-lg">
                  <span class="material-icons">rocket_launch</span>
                </div>
                <span class="text-xs font-medium text-gray-700">{{ __('Bulk Send') }}</span>
              </a>
              
              <a href="{{ url('/user/chatbot') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-gray-100 transition-colors">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg">
                  <span class="material-icons">smart_toy</span>
                </div>
                <span class="text-xs font-medium text-gray-700">{{ __('Chatbot') }}</span>
              </a>
              
              <a href="{{ url('/user/contact') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-gray-100 transition-colors">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center text-white shadow-lg">
                  <span class="material-icons">contacts</span>
                </div>
                <span class="text-xs font-medium text-gray-700">{{ __('Contacts') }}</span>
              </a>
              
              <a href="{{ url('/user/logs') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-gray-100 transition-colors">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white shadow-lg">
                  <span class="material-icons">article</span>
                </div>
                <span class="text-xs font-medium text-gray-700">{{ __('Reports') }}</span>
              </a>
            </div>
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
              <span class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</span>
            </div>
            <span class="material-icons text-gray-400 text-sm hidden md:block">expand_more</span>
          </button>
          
          <div tabindex="0" class="dropdown-content z-50 card card-compact w-64 p-0 shadow-2xl bg-base-100 mt-3 border border-gray-200 rounded-2xl overflow-hidden">
            <!-- User Info Header -->
            <div class="bg-gradient-to-br from-primary to-primary/80 px-4 py-4 text-white">
              <div class="flex items-center gap-3">
                <div class="avatar">
                  <div class="w-12 h-12 rounded-full ring-2 ring-white/30">
                    <img src="{{ Auth::user()->avatar == null ? 'https://ui-avatars.com/api/?name='.Auth::user()->name : asset(Auth::user()->avatar) }}" 
                         alt="{{ Auth::user()->name }}" />
                  </div>
                </div>
                <div>
                  <h4 class="font-semibold">{{ Auth::user()->name }}</h4>
                  <p class="text-xs opacity-90">{{ Auth::user()->email }}</p>
                </div>
              </div>
            </div>

            <!-- Menu Items -->
            <div class="p-2">

              
              @if(Request::is('user/*'))
              <a href="{{ url('/user/subscription') }}" 
                 class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                <span class="material-icons text-gray-600 text-xl">card_membership</span>
                <span class="text-sm font-medium text-gray-700">{{ __('Subscription') }}</span>
              </a>
              
              <a href="{{ url('/user/auth-key') }}" 
                 class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                <span class="material-icons text-gray-600 text-xl">vpn_key</span>
                <span class="text-sm font-medium text-gray-700">{{ __('Auth Key') }}</span>
              </a>
              @endif
              
              <a href="{{ Request::is('user/*') ? url('/user/support') : url('/admin/support') }}" 
                 class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                <span class="material-icons text-gray-600 text-xl">support_agent</span>
                <span class="text-sm font-medium text-gray-700">{{ __('Support') }}</span>
              </a>
              
              <div class="divider my-1"></div>
              
              <button class="logout-button flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-50 transition-colors text-red-600 w-full">
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