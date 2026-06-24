<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/toastify-js/src/toastify.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/pace/pace-theme-default.min.css') }}">
  
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('css')
  
  @if(Request::is('admin/*'))
  <link rel="stylesheet" href="{{ asset('assets/css/argon.css?v=1.1.1') }}" type="text/css">
  <style>
    /* Fix DaisyUI tab-content conflict with Bootstrap in Admin Panel */
    .tab-content {
      display: block !important;
    }
    
    /* Prevent horizontal scrolling on admin mobile views */
    body {
      overflow-x: hidden !important;
    }
    
    /* Force desktop sidebar (aside) to be hidden on mobile regardless of argon.css */
    @media (max-width: 767.98px) {
      aside {
        display: none !important;
      }
    }

    /* Solve mobile squeezing of equal-width grid columns in admin stats panels */
    @media (max-width: 767.98px) {
      .row.d-flex.justify-content-between.flex-wrap > .col {
        flex: 0 0 100% !important;
        max-width: 100% !important;
        margin-bottom: 1rem;
      }
    }
    @media (min-width: 768px) and (max-width: 991.98px) {
      .row.d-flex.justify-content-between.flex-wrap > .col {
        flex: 0 0 50% !important;
        max-width: 50% !important;
        margin-bottom: 1rem;
      }
    }
    
    /* Force tables inside table-responsive to have a readable minimum width on mobile/tablet */
    @media (max-width: 991.98px) {
      .table-responsive table {
        min-width: 800px !important;
      }
    }

    /* ══════════ MODERN PREMIUM ADMIN TABLES ══════════ */
    /* Container style */
    .table-responsive {
      border-radius: 1rem !important;
      border: 1px solid #e2e8f0 !important;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -2px rgba(0, 0, 0, 0.03) !important;
      background-color: #ffffff !important;
      overflow: hidden !important;
      margin-bottom: 1.5rem !important;
    }

    /* Table element */
    .table-responsive table.table {
      width: 100% !important;
      border-collapse: collapse !important;
      margin-bottom: 0 !important;
    }

    /* Table headers */
    .table-responsive table.table thead th {
      background-color: #f8fafc !important;
      color: #475569 !important;
      font-size: 0.725rem !important;
      font-weight: 700 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.06em !important;
      padding: 1rem 1.25rem !important;
      border-bottom: 2px solid #e2e8f0 !important;
      border-top: none !important;
    }

    /* Table body cells */
    .table-responsive table.table tbody td {
      padding: 1rem 1.25rem !important;
      vertical-align: middle !important;
      border-bottom: 1px solid #f1f5f9 !important;
      color: #334155 !important;
      font-size: 0.825rem !important;
      background-color: transparent !important;
    }

    /* Row hover effect */
    .table-responsive table.table tbody tr {
      transition: background-color 0.2s ease !important;
      background-color: #ffffff !important;
    }
    .table-responsive table.table tbody tr:hover {
      background-color: #f8fafc !important;
    }

    /* Stripe row option */
    .table-responsive table.table.table-striped tbody tr:nth-of-type(odd) {
      background-color: #fdfdfd !important;
    }
    .table-responsive table.table.table-striped tbody tr:nth-of-type(odd):hover {
      background-color: #f8fafc !important;
    }

    /* Table actions buttons (Edit/Delete icons) */
    .table-responsive table.table tbody td .btn {
      border-radius: 0.5rem !important;
      padding: 0.35rem 0.75rem !important;
      font-size: 0.725rem !important;
      font-weight: 600 !important;
      transition: all 0.2s ease !important;
    }
    
    /* Clean look for Badges in Tables */
    .table-responsive table.table tbody td .badge {
      border-radius: 9999px !important;
      padding: 0.25rem 0.75rem !important;
      font-size: 0.7rem !important;
      font-weight: 700 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.04em !important;
      border: none !important;
    }

    /* Style admin navbar-nav links inside mobile drawer cleanly */
    .admin-mobile-nav .navbar-heading {
      font-size: 0.65rem;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: #8898aa;
      padding: 1rem 0.5rem 0.25rem;
      font-weight: 600;
    }
    .admin-mobile-nav .navbar-nav .nav-item .nav-link {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      border-radius: 8px;
      font-size: 0.875rem;
      font-weight: 500;
      color: #525f7f;
      transition: all 0.2s ease;
      text-decoration: none;
      margin-bottom: 2px;
    }
    .admin-mobile-nav .navbar-nav .nav-item .nav-link i {
      font-size: 1rem;
      width: 20px;
      text-align: center;
      flex-shrink: 0;
    }
    .admin-mobile-nav .navbar-nav .nav-item .nav-link:hover,
    .admin-mobile-nav .navbar-nav .nav-item .nav-link.active {
      background: linear-gradient(to right, rgb(147 51 234), rgb(126 34 206));
      color: white !important;
    }
    .admin-mobile-nav .navbar-nav .nav-item .nav-link.active .nav-link-text,
    .admin-mobile-nav .navbar-nav .nav-item .nav-link:hover .nav-link-text {
      color: white !important;
    }
    .admin-mobile-nav .nav.nav-sm .nav-item .nav-link {
      padding: 6px 12px 6px 2.5rem;
      font-size: 0.8rem;
      color: #6b7280;
      border-radius: 6px;
    }
    .admin-mobile-nav .nav.nav-sm .nav-item .nav-link:hover {
      background: rgb(147 51 234 / 0.08);
      color: rgb(147 51 234);
    }

    .bg-white{
       background-color: #f8fafc !important;
    }

  </style>
  @endif
  
  <style>
    [x-cloak] { display: none !important; }
  </style>
</head>

<body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: false, mobileMenuOpen: false }">

  <!-- Mobile Sidebar Drawer -->
  <div x-show="sidebarOpen" class="relative z-50 md:!hidden" role="dialog" aria-modal="true" x-cloak>
    <!-- Background backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/80" 
         @click="sidebarOpen = false"></div>

    <div class="fixed inset-0 flex">
      <div x-show="sidebarOpen" 
           x-transition:enter="transition ease-in-out duration-300 transform"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in-out duration-300 transform"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="relative mr-16 flex w-full max-w-xs flex-1 transform transition duration-300 ease-in-out bg-white">
        
        <!-- Close button -->
        <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
          <button type="button" class="-m-2.5 p-2.5 text-white" @click="sidebarOpen = false">
            <span class="sr-only">Close sidebar</span>
            <span class="material-icons">close</span>
          </button>
        </div>

        <!-- Sidebar component -->
        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-4 pb-4 w-full h-full">
          <div class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200">
            <a href="{{ Request::is('admin/*') ? url('/admin/dashboard') : url('/user/dashboard') }}" class="flex items-center gap-3">
              <img src="{{ asset(get_option('primary_data',true)->logo ?? '') }}" class="h-20" alt="{{ env('APP_NAME') }}">
            </a>
          </div>
          <nav class="flex flex-1 flex-col overflow-y-auto scrollbar-hide py-2 admin-mobile-nav">
            @include('layouts.main.sidebar')
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Desktop Sidebar -->
  <aside class="!hidden md:!flex md:flex-shrink-0">
    <div class="flex flex-col w-64 border-r border-gray-200 bg-white fixed inset-y-0 z-50">
      <!-- Logo -->
      <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
        <a href="{{ url('/user/dashboard') }}" class="flex items-center gap-3">
          <img src="{{ asset(get_option('primary_data',true)->logo ?? '') }}" class="h-24" alt="{{ env('APP_NAME') }}">
        </a>
      </div>
      
      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto scrollbar-hide py-4 px-3">
        @include('layouts.main.sidebar')
      </nav>
    </div>
  </aside>


  <!-- Main Content -->
  <div class="md:pl-64 flex flex-col min-h-screen">
    <!-- Top Header -->
    @include('layouts.main.header')
    
    <!-- Page Header -->
    @yield('head')
    
    <!-- Page Content -->
    <main class="flex-1 {{ Request::is('admin/*') ? 'pb-6' : 'pb-24 md:pb-6' }}">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
      </div>
    </main>

    <!-- Desktop Footer -->
    <footer class="hidden md:block">
      @include('layouts.main.footer')
    </footer>
  </div>

  @if(!Request::is('admin/*'))
  <!-- Mobile Bottom Navigation -->
  <div class="fixed bottom-0 left-0 right-0 md:hidden z-50 border-t border-gray-200 bg-white shadow-lg">
    <div class="flex items-center justify-around h-16">
      <a href="{{ route('user.dashboard.index') }}" 
         class="btm-nav-item {{ Request::is('user/dashboard*') ? 'active' : '' }}">
        <span class="material-icons text-2xl">dashboard</span>
        <span class="btm-nav-label text-xs">Dashboard</span>
      </a>
      
      <a href="{{ route('user.device.index') }}" 
         class="btm-nav-item {{ Request::is('user/device*') ? 'active' : '' }}">
        <span class="material-icons text-2xl">devices</span>
        <span class="btm-nav-label text-xs">Devices</span>
      </a>
      
      <a href="{{ url('user/sent-text-message') }}" 
         class="btm-nav-item {{ Request::is('user/sent-text-message*') ? 'active' : '' }}">
        <span class="material-icons text-2xl">send</span>
        <span class="btm-nav-label text-xs">Send</span>
      </a>
      
      <a href="{{ route('user.chatbot.index') }}" 
         class="btm-nav-item {{ Request::is('user/chatbot*') ? 'active' : '' }}">
        <span class="material-icons text-2xl">smart_toy</span>
        <span class="btm-nav-label text-xs">Chatbot</span>
      </a>
      
      <a href="{{ url('/user/profile') }}" 
         class="btm-nav-item {{ Request::is('user/profile*') ? 'active' : '' }}">
        <span class="material-icons text-2xl">person</span>
        <span class="btm-nav-label text-xs">Profile</span>
      </a>
    </div>
  </div>

  <style>
  .btm-nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2px;
    padding: 8px 12px;
    color: rgb(107 114 128);
    transition: all 0.2s ease;
    position: relative;
    flex: 1;
    text-decoration: none;
  }

  .btm-nav-item.active {
    background: linear-gradient(to top, rgb(147 51 234 / 0.15), transparent);
    color: rgb(147 51 234);
    font-weight: 600;
  }

  .btm-nav-item.active::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(to right, rgb(147 51 234), rgb(126 34 206));
    border-radius: 0 0 3px 3px;
  }

  .btm-nav-item.active .material-icons {
    transform: scale(1.1);
  }

  .btm-nav-item:active {
    transform: scale(0.95);
  }
  </style>

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
  @endif

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
