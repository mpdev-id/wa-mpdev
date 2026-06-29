<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="hacker">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#10b981">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

  <title>{{ env('APP_NAME') }}</title>
  
  <script>
    (function() {
      var theme = localStorage.getItem('app-theme') || 'hacker';
      document.documentElement.setAttribute('data-theme', theme);
    })();
  </script>
  
  <!-- Favicon -->
  <link rel="icon" href="{{ asset(get_option('primary_data',true)->favicon ?? '') }}" type="image/png">
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
  
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
  </style>
  @endif
  
  <style>
    [x-cloak] { display: none !important; }

    /* Global horizontal styling for Bootstrap 4 pagination */
    .pagination {
      display: flex !important;
      list-style: none !important;
      padding-left: 0 !important;
      border-radius: 0.5rem !important;
      gap: 0.25rem !important;
      align-items: center !important;
    }
    .pagination li {
      display: inline !important;
    }
    .pagination li a, 
    .pagination li span {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      min-width: 2.25rem !important;
      height: 2.25rem !important;
      padding: 0.25rem 0.75rem !important;
      font-size: 0.875rem !important;
      font-weight: 600 !important;
      color: #4b5563 !important;
      background-color: #fff !important;
      border: 1px solid #e5e7eb !important;
      border-radius: 0.5rem !important;
      text-decoration: none !important;
      transition: all 0.2s !important;
    }
    .pagination li a:hover {
      color: #10b981 !important;
      background-color: #ecfdf5 !important;
      border-color: #a7f3d0 !important;
    }
    .pagination li.active span {
      color: #fff !important;
      background-color: #10b981 !important;
      border-color: #10b981 !important;
    }
    .pagination li.disabled span {
      color: #9ca3af !important;
      background-color: #f9fafb !important;
      border-color: #e5e7eb !important;
      cursor: not-allowed !important;
    }

    /* Professional Theme: Remove gradients */
    html[data-theme="profesional"] [class*="bg-gradient-"] {
      background-image: none !important;
      background-color: var(--tw-gradient-from, var(--color-primary)) !important;
    }
    
    html[data-theme="profesional"] .bg-gradient-to-br,
    html[data-theme="profesional"] .bg-gradient-to-r,
    html[data-theme="profesional"] .bg-gradient-to-tr,
    html[data-theme="profesional"] .bg-gradient-to-bl,
    html[data-theme="profesional"] .bg-gradient-to-t,
    html[data-theme="profesional"] .bg-gradient-to-b,
    html[data-theme="profesional"] .bg-gradient-to-l {
      background-image: none !important;
    }
  </style>
</head>

<body class="font-sans antialiased bg-base-100 text-base-content" x-data="{ sidebarOpen: false, mobileMenuOpen: false }">

  <!-- ═══ HACKER PRELOADER ═══ -->
  <div id="hacker-preloader">
    <canvas id="matrix-canvas"></canvas>
    <div id="preloader-content">
      <div id="preloader-logo">
        <div id="preloader-brackets"><span>[</span><span id="preloader-icon"></span><span>]</span></div>
        <div id="preloader-brand">{{ env('APP_NAME', 'WASAPI') }}</div>
      </div>
      <div id="preloader-terminal">
        <div class="term-line" data-delay="0">▶ Initializing secure connection...</div>
        <div class="term-line" data-delay="300">▶ Loading session modules...</div>
        <div class="term-line" data-delay="600">▶ Authenticating user credentials...</div>
        <div class="term-line" data-delay="900">▶ <span style="color:#00e87a">ACCESS GRANTED</span></div>
      </div>
      <div id="preloader-bar-wrap">
        <div id="preloader-bar"></div>
      </div>
    </div>
    <div id="preloader-scanline"></div>
  </div>

  <style>
  #hacker-preloader {
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: #0d0d0d;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.5s ease, visibility 0.5s ease;
    overflow: hidden;
  }
  #hacker-preloader.hidden {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
  }

  /* Matrix canvas background */
  #matrix-canvas {
    position: absolute;
    inset: 0;
    opacity: 0.12;
  }

  /* Scanline overlay */
  #preloader-scanline {
    position: absolute;
    inset: 0;
    background: repeating-linear-gradient(
      to bottom,
      transparent 0px,
      transparent 3px,
      rgba(0,0,0,0.15) 3px,
      rgba(0,0,0,0.15) 4px
    );
    pointer-events: none;
    z-index: 2;
  }

  /* Center content */
  #preloader-content {
    position: relative;
    z-index: 3;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 28px;
    width: 320px;
  }

  /* Logo */
  #preloader-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
  }
  #preloader-brackets {
    font-family: 'JetBrains Mono', 'Space Mono', monospace;
    font-size: 2.2rem;
    color: #00e87a;
    letter-spacing: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
    animation: preloader-glow 2s ease-in-out infinite alternate;
  }
  #preloader-brackets span:first-child,
  #preloader-brackets span:last-child {
    color: #3a3a3a;
  }
  #preloader-icon {
    animation: preloader-bounce 0.8s ease-in-out infinite alternate;
    display: inline-block;
    font-style: normal;
    line-height: 1;
  }
  #preloader-brand {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.7rem;
    letter-spacing: 0.4em;
    color: #555;
    text-transform: uppercase;
    margin-top: 2px;
  }

  /* Terminal lines */
  #preloader-terminal {
    width: 100%;
    background: rgba(0,0,0,0.4);
    border: 1px solid #1e1e1e;
    border-radius: 6px;
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .term-line {
    font-family: 'JetBrains Mono', 'Space Mono', monospace;
    font-size: 0.72rem;
    color: #3a3a3a;
    opacity: 0;
    transition: opacity 0.3s ease, color 0.3s ease;
    white-space: nowrap;
    overflow: hidden;
  }
  .term-line.visible {
    opacity: 1;
    color: #666;
  }
  .term-line:last-child.visible {
    color: #00e87a;
  }

  /* Progress bar */
  #preloader-bar-wrap {
    width: 100%;
    height: 2px;
    background: #1a1a1a;
    border-radius: 2px;
    overflow: hidden;
  }
  #preloader-bar {
    height: 100%;
    width: 0%;
    background: #00e87a;
    border-radius: 2px;
    transition: width 0.1s linear;
    box-shadow: 0 0 8px rgba(0, 232, 122, 0.6);
  }

  @keyframes preloader-glow {
    0%   { text-shadow: 0 0 8px rgba(0,232,122,0.4); }
    100% { text-shadow: 0 0 18px rgba(0,232,122,0.9), 0 0 40px rgba(0,232,122,0.3); }
  }
  @keyframes preloader-bounce {
    0%   { transform: translateY(0) scale(1); }
    100% { transform: translateY(-6px) scale(1.15); }
  }
  </style>

  <script>
  (function() {
    if (localStorage.getItem('app-theme') === 'profesional') {
      var loader = document.getElementById('hacker-preloader');
      if (loader) loader.remove();
      return;
    }

    // ── Random emoji icon ──
    const emojis = ['🤪','😵‍💫','🥴','😵','😱','🥸','🥹','🥺','🤠','🤥','🤓','🫣','👻','🤖','👾','🙊','🙈'];
    const icon = document.getElementById('preloader-icon');
    icon.textContent = emojis[Math.floor(Math.random() * emojis.length)];

    // ── Matrix rain ──
    const canvas = document.getElementById('matrix-canvas');
    const ctx = canvas.getContext('2d');
    canvas.width  = window.innerWidth;
    canvas.height = window.innerHeight;
    const cols = Math.floor(canvas.width / 16);
    const drops = Array(cols).fill(1);
    const chars = 'アイウエオカキクケコ01ABCDEF<>{}[];:/*'.split('');
    function drawMatrix() {
      ctx.fillStyle = 'rgba(13,13,13,0.05)';
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      ctx.fillStyle = '#00e87a';
      ctx.font = '13px monospace';
      drops.forEach((y, i) => {
        const ch = chars[Math.floor(Math.random() * chars.length)];
        ctx.fillText(ch, i * 16, y * 16);
        if (y * 16 > canvas.height && Math.random() > 0.975) drops[i] = 0;
        drops[i]++;
      });
    }
    const matrixInterval = setInterval(drawMatrix, 50);

    // ── Terminal lines ──
    document.querySelectorAll('.term-line').forEach(line => {
      const delay = parseInt(line.getAttribute('data-delay')) || 0;
      setTimeout(() => line.classList.add('visible'), delay);
    });

    // ── Progress bar ──
    const bar = document.getElementById('preloader-bar');
    let prog = 0;
    const progInterval = setInterval(() => {
      prog += Math.random() * 12;
      if (prog > 90) prog = 90;
      bar.style.width = prog + '%';
    }, 120);

    // ── Hide on load ──
    window.addEventListener('load', function() {
      clearInterval(progInterval);
      bar.style.width = '100%';
      setTimeout(() => {
        clearInterval(matrixInterval);
        const loader = document.getElementById('hacker-preloader');
        loader.classList.add('hidden');
        setTimeout(() => loader.remove(), 600);
      }, 400);
    });
  })();
  </script>

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
          <button type="button" class="-m-2.5 p-2.5 text-white rounded-full" @click="sidebarOpen = false">
            <span class="sr-only">Close sidebar</span>
            <span class="material-icons">close</span>
          </button>
        </div>

        <!-- Sidebar component -->
        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-base-100 px-4 pb-4 w-full h-full">
          <div class="flex h-16 shrink-0 items-center justify-between border-b border-base-300">
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
    <div class="flex flex-col w-64 border-r border-base-300 bg-base-100 fixed inset-y-0 z-50">
      <!-- Logo -->
      <div class="flex items-center justify-between h-16 px-6 border-b border-base-300">
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
  <div class="fixed bottom-0 left-0 right-0 md:hidden z-50 bg-base-100 shadow-[0_-4px_25px_-5px_rgba(0,0,0,0.05)] border-t border-base-200/50" style="padding-bottom: env(safe-area-inset-bottom);">
    <div class="flex items-center justify-around h-16 px-1">
      <a href="{{ route('user.dashboard.index') }}" 
         class="btm-nav-item group {{ Request::is('user/dashboard*') ? 'active' : '' }}">
        <div class="btm-nav-icon-wrap group-active:scale-95">
          <span class="material-icons text-[22px]">dashboard</span>
        </div>
        <span class="btm-nav-label">Dashboard</span>
      </a>
      
      <a href="{{ route('user.device.index') }}" 
         class="btm-nav-item group {{ Request::is('user/device*') ? 'active' : '' }}">
        <div class="btm-nav-icon-wrap group-active:scale-95">
          <span class="material-icons text-[22px]">devices</span>
        </div>
        <span class="btm-nav-label">Devices</span>
      </a>
      
      <a href="{{ url('user/sent-text-message') }}" 
         class="btm-nav-item group {{ Request::is('user/sent-text-message*') ? 'active' : '' }}">
        <div class="btm-nav-icon-wrap group-active:scale-95">
          <span class="material-icons text-[22px]">send</span>
        </div>
        <span class="btm-nav-label">Send</span>
      </a>
      
      <a href="{{ route('user.chatbot.index') }}" 
         class="btm-nav-item group {{ Request::is('user/chatbot*') ? 'active' : '' }}">
        <div class="btm-nav-icon-wrap group-active:scale-95">
          <span class="material-icons text-[22px]">smart_toy</span>
        </div>
        <span class="btm-nav-label">Chatbot</span>
      </a>
      
      <a href="{{ url('/user/profile') }}" 
         class="btm-nav-item group {{ Request::is('user/profile*') ? 'active' : '' }}">
        <div class="btm-nav-icon-wrap group-active:scale-95">
          <span class="material-icons text-[22px]">person</span>
        </div>
        <span class="btm-nav-label">Profile</span>
      </a>
    </div>
  </div>

  <style>
  .btm-nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    height: 100%;
    flex: 1;
    text-decoration: none;
    color: oklch(from var(--color-base-content) l c h / 0.5);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .btm-nav-icon-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    /* padding: 4px 16px; */
    border-radius: 9999px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .btm-nav-label {
    font-size: 0.65rem;
    font-weight: 500;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  /* Hover state for non-active */
  .btm-nav-item:not(.active):hover {
    color: oklch(from var(--color-base-content) l c h / 0.8);
  }

  /* Active State (Soft Pill Design) */
  .btm-nav-item.active {
    color: var(--color-primary);
  }

  .btm-nav-item.active .btm-nav-icon-wrap {
    background-color: oklch(from var(--color-primary) l c h / 0.12);
  }

  .btm-nav-item.active .btm-nav-label {
    font-weight: 700;
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
       class="fixed inset-x-0 bottom-16 mx-4 mb-2 bg-base-100 rounded-2xl shadow-2xl z-40 md:hidden border border-base-300 max-h-96 overflow-y-auto">
    <div class="p-4 space-y-2">
      <a href="{{ route('user.apps.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-base-200">
        <span class="material-icons text-base-content/60">apps</span>
        <span>My Apps</span>
      </a>
      <a href="{{ route('user.contact.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-base-200">
        <span class="material-icons text-base-content/60">contacts</span>
        <span>Contacts</span>
      </a>
      <a href="{{ url('user/template') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-base-200">
        <span class="material-icons text-base-content/60">description</span>
        <span>Templates</span>
      </a>
      <a href="{{ url('user/profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-base-200">
        <span class="material-icons text-base-content/60">settings</span>
        <span>Settings</span>
      </a>
      
      <div class="divider my-1"></div>
      
      <button onclick="toggleTheme()" class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-base-200 w-full text-left">
        <div class="flex items-center gap-3">
          <span class="material-icons text-base-content/60" id="mobile-theme-icon">dark_mode</span>
          <span id="mobile-theme-text">Hacker (Dark)</span>
        </div>
        <span class="material-icons text-base-content/40">swap_horiz</span>
      </button>

      <button class="logout-button flex items-center gap-3 px-4 py-3 hover:bg-error/10 text-error w-full rounded-full mt-2">
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
