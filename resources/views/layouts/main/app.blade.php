<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="default">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#10b981">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

  <title>{{ env('APP_NAME') }}</title>
  
  <!-- Favicon -->
  <link rel="icon" href="{{ asset(get_option('primary_data',true)->favicon ?? '') }}" type="image/png">
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  
  <!-- Legacy Icons (for compatibility) -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/uicons-regular-straight.css') }}">
  
  @stack('topcss')
  
  <!-- Vite Assets -->
  
  <!-- Plugins CSS -->
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/toastify-js/src/toastify.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/pace/pace-theme-default.min.css') }}">
  
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('css')
  
  <style>
    [x-cloak] { display: none !important; }
  </style>
</head>

<body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: false, mobileMenuOpen: false }">

  <!-- Desktop Sidebar -->
  <aside class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 border-r border-gray-200 bg-white fixed inset-y-0 z-50">
      <!-- Logo -->
      <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
        <a href="{{ url('/user/dashboard') }}" class="flex items-center gap-3">
          <img src="{{ asset(get_option('primary_data',true)->logo ?? '') }}" class="h-8" alt="{{ env('APP_NAME') }}">
        </a>
      </div>
      
      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto scrollbar-hide py-4 px-3">
        @include('layouts.main.sidebar')
      </nav>
    </div>
  </aside>

  <!-- Mobile Sidebar Overlay -->
  <div x-show="sidebarOpen" 
       x-cloak
       @click="sidebarOpen = false"
       x-transition:enter="transition-opacity ease-linear duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition-opacity ease-linear duration-300"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="fixed inset-0 bg-gray-900/50 z-40 md:hidden backdrop-blur-sm">
  </div>

  <!-- Mobile Sidebar -->
  <aside x-show="sidebarOpen"
         x-cloak
         @click.away="sidebarOpen = false"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl md:hidden">
    <!-- Logo -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
      <a href="{{ url('/user/dashboard') }}" class="flex items-center gap-3">
        <img src="{{ asset(get_option('primary_data',true)->logo ?? '') }}" class="h-8" alt="{{ env('APP_NAME') }}">
      </a>
      <button @click="sidebarOpen = false" class="text-gray-500 hover:text-gray-700">
        <span class="material-icons">close</span>
      </button>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto scrollbar-hide py-4 px-3">
      @include('layouts.main.sidebar')
    </nav>
  </aside>

  <!-- Main Content -->
  <div class="md:pl-64 flex flex-col min-h-screen">
    <!-- Top Header -->
    @include('layouts.main.header')
    
    <!-- Page Header -->
    @yield('head')
    
    <!-- Page Content -->
    <main class="flex-1 pb-20 md:pb-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
      </div>
    </main>

    <!-- Desktop Footer -->
    <footer class="hidden md:block">
      @include('layouts.main.footer')
    </footer>
  </div>

  <!-- Mobile Bottom Navigation -->
  <div class="btm-nav md:hidden z-50 border-t border-gray-200 bg-white shadow-lg">
    <a href="{{ route('user.dashboard.index') }}" 
       class="{{ Request::is('user/dashboard*') ? 'active text-primary' : 'text-gray-600' }}">
      <span class="material-icons text-2xl">dashboard</span>
      <span class="btm-nav-label text-xs">Dashboard</span>
    </a>
    
    <a href="{{ route('user.device.index') }}" 
       class="{{ Request::is('user/device*') ? 'active text-primary' : 'text-gray-600' }}">
      <span class="material-icons text-2xl">devices</span>
      <span class="btm-nav-label text-xs">Devices</span>
    </a>
    
    <a href="{{ url('user/sent-text-message') }}" 
       class="{{ Request::is('user/sent-text-message*') ? 'active text-primary' : 'text-gray-600' }}">
      <span class="material-icons text-2xl">send</span>
      <span class="btm-nav-label text-xs">Send</span>
    </a>
    
    <a href="{{ route('user.chatbot.index') }}" 
       class="{{ Request::is('user/chatbot*') ? 'active text-primary' : 'text-gray-600' }}">
      <span class="material-icons text-2xl">smart_toy</span>
      <span class="btm-nav-label text-xs">Chatbot</span>
    </a>
    
    <button @click="mobileMenuOpen = !mobileMenuOpen" 
            class="text-gray-600">
      <span class="material-icons text-2xl">menu</span>
      <span class="btm-nav-label text-xs">More</span>
    </button>
  </div>

  <!-- Mobile More Menu -->
  <div x-show="mobileMenuOpen"
       x-cloak
       @click.away="mobileMenuOpen = false"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0 translate-y-4"
       x-transition:enter-end="opacity-100 translate-y-0"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="opacity-100 translate-y-0"
       x-transition:leave-end="opacity-0 translate-y-4"
       class="fixed inset-x-0 bottom-16 mx-4 mb-2 bg-white rounded-2xl shadow-2xl z-40 md:hidden border border-gray-200 max-h-96 overflow-y-auto">
    <div class="p-4 space-y-2">
      <a href="{{ route('user.apps.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100">
        <span class="material-icons text-gray-600">apps</span>
        <span>My Apps</span>
      </a>
      <a href="{{ route('user.contact.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100">
        <span class="material-icons text-gray-600">contacts</span>
        <span>Contacts</span>
      </a>
      <a href="{{ url('user/template') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100">
        <span class="material-icons text-gray-600">description</span>
        <span>Templates</span>
      </a>
      <a href="{{ url('user/profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100">
        <span class="material-icons text-gray-600">settings</span>
        <span>Settings</span>
      </a>
      <button class="logout-button flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-red-50 text-red-600 w-full">
        <span class="material-icons">logout</span>
        <span>Logout</span>
      </button>
    </div>
  </div>

  <!-- Logout Form -->
  <form action="{{ route('logout') }}" method="post" id="logout-form">@csrf</form>

  <!-- Scripts -->
  <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/js-cookie/js.cookie.js') }}"></script>
  
  @stack('topjs')
  
  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  
  <!-- Plugins -->
  <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/toastify-js/src/toastify.js') }}"></script>
  <script src="{{ asset('assets/plugins/form.js?v=2') }}"></script>
  
  @stack('js')
  
  <script src="{{ asset('assets/plugins/pace/pace.min.js') }}"></script>
  
  @stack('bottomjs')
  
  @if(Request::is('user/*'))
  <script src="{{ asset('assets/js/pages/notifications.js') }}"></script>
  @endif

</body>
</html>
