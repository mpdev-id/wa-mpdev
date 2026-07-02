<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="mptheme">

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
      var defaultTheme = "{{ Request::is('user/*') || Request::is('admin/*') ? 'mptheme' : 'hacker' }}";
      var theme = localStorage.getItem('app-theme') || defaultTheme;
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

  


  <!-- MPTheme Styles -->
  @if(Request::is('user/*') || Request::is('admin/*'))
  <style>
    /* Light Theme - Softer Game HUD */
    html[data-theme="mptheme"] {
        --bg-main: #E0F2FE;
        --bg-card: rgba(255, 255, 255, 0.72);
        --bg-card-hover: rgba(255, 255, 255, 0.88);
        --bg-inner: rgba(240, 249, 255, 0.75);
        
        --text-main: #0369A1;
        --text-body: #0369A1;
        --text-secondary: #0284C7;
        
        --border-color: rgba(14, 165, 233, 0.24);
        
        --color-primary: #0284C7;
        --color-accent: #0369A1;
        
        --particle-color: rgba(3, 105, 161, 0.14);
        --hud-glow: 0 0 10px rgba(2, 132, 199, 0.06);
        --hud-glow-hover: 0 0 16px rgba(2, 132, 199, 0.18);
    }
    
    /* Dark Theme - Softer Space HUD */
    html[data-theme="mptheme-dark"] {
        --bg-main: #1E293B;
        --bg-card: rgba(15, 23, 42, 0.45);
        --bg-card-hover: rgba(30, 41, 59, 0.65);
        --bg-inner: rgba(15, 23, 42, 0.7);
        
        --text-main: #F8FAFC;
        --text-body: #F8FAFC;
        --text-secondary: #94A3B8;
        
        --border-color: rgba(255, 255, 255, 0.12);
        
        --color-primary: #3B82F6;
        --color-accent: #F59E0B;
        
        --particle-color: rgba(255, 255, 255, 0.08);
        --hud-glow: 0 0 10px rgba(59, 130, 246, 0.08);
        --hud-glow-hover: 0 0 18px rgba(59, 130, 246, 0.22);
    }
    
    html[data-theme^="mptheme"] {
        background-color: var(--bg-main) !important;
    }

    html[data-theme^="mptheme"] body {
        background-color: transparent !important;
        color: var(--text-main) !important;
    }

    html[data-theme^="mptheme"] .gaming-grid {
        position: fixed;
        inset: 0;
        background-image: 
            linear-gradient(var(--border-color) 1.5px, transparent 1.5px),
            linear-gradient(90deg, var(--border-color) 1.5px, transparent 1.5px);
        background-size: 40px 40px;
        opacity: 0.26;
        pointer-events: none;
        z-index: -3;
    }

    html[data-theme^="mptheme"] .scanner-line {
        position: fixed;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, transparent, var(--color-primary), transparent);
        opacity: 0.28;
        pointer-events: none;
        z-index: -2;
        animation: scan-vertical 14s infinite linear;
    }

    html[data-theme^="mptheme"] #particles-container {
        z-index: -1 !important;
    }

    @keyframes scan-vertical {
        0% { top: -20px; }
        100% { top: 110%; }
    }

    @keyframes float-particle-1 { 0% { transform: translateY(0) rotate(0deg) translateX(0); } 33% { transform: translateY(-40px) rotate(120deg) translateX(20px); } 66% { transform: translateY(25px) rotate(240deg) translateX(-20px); } 100% { transform: translateY(0) rotate(360deg) translateX(0); } }
    @keyframes float-particle-2 { 0% { transform: translateY(0) rotate(0deg) translateX(0); } 50% { transform: translateY(60px) rotate(-180deg) translateX(-30px); } 100% { transform: translateY(0) rotate(-360deg) translateX(0); } }
    @keyframes float-particle-3 { 0% { transform: translateY(0) rotate(0deg) translateX(0); } 33% { transform: translateY(-30px) rotate(90deg) translateX(-15px); } 66% { transform: translateY(-60px) rotate(270deg) translateX(15px); } 100% { transform: translateY(0) rotate(360deg) translateX(0); } }
    @keyframes float-particle-4 { 0% { transform: translateY(0) rotate(0deg) translateX(0); } 50% { transform: translateY(-55px) rotate(180deg) translateX(40px); } 100% { transform: translateY(0) rotate(360deg) translateX(0); } }

    /* Override UI Elements for mptheme */
    html[data-theme^="mptheme"] .bg-base-100 { background-color: transparent !important; }
    html[data-theme^="mptheme"] .card, 
    html[data-theme^="mptheme"] .bg-white,
    html[data-theme^="mptheme"] aside .bg-base-100 {
        background-color: var(--bg-card) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--border-color);
        box-shadow: var(--hud-glow);
        color: var(--text-main);
    }
    html[data-theme^="mptheme"] .card:hover {
        box-shadow: var(--hud-glow-hover);
        background-color: var(--bg-card-hover) !important;
    }
  </style>
  @endif

</head>

<body class="font-sans antialiased bg-base-100 text-base-content" x-data="{ sidebarOpen: false, mobileMenuOpen: false }">

  @if(Request::is('user/*') || Request::is('admin/*'))
  <div class="gaming-grid" x-show="document.documentElement.getAttribute('data-theme').startsWith('mptheme')"></div>
  <div class="scanner-line" x-show="document.documentElement.getAttribute('data-theme').startsWith('mptheme')"></div>
  <div class="particles fixed inset-0 overflow-hidden pointer-events-none z-0" id="particles-container" x-show="document.documentElement.getAttribute('data-theme').startsWith('mptheme')"></div>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!document.documentElement.getAttribute('data-theme').startsWith('mptheme')) return;
        
        const container = document.getElementById('particles-container');
        if(!container) return;
        
        const svgs = [
            `<svg viewBox="0 0 30 30"><circle cx="15" cy="15" r="9" stroke="var(--particle-color)" fill="none" stroke-width="2"/><circle cx="15" cy="15" r="2" fill="var(--particle-color)"/><path d="M15,2 L15,7 M15,23 L15,28 M2,15 L7,15 M23,15 L28,15" stroke="var(--particle-color)" stroke-width="2" stroke-linecap="round"/></svg>`,
            `<svg viewBox="0 0 30 30"><polygon points="15,2 28,15 15,28 2,15" stroke="var(--particle-color)" fill="none" stroke-width="2"/><polygon points="15,8 22,15 15,22 8,15" stroke="var(--particle-color)" fill="none" stroke-width="1" stroke-dasharray="2,2"/></svg>`,
            `<svg viewBox="0 0 24 24"><path d="M4,4 h2 v2 h-2 z M18,4 h2 v2 h-2 z M6,6 h12 v2 h-12 z M8,8 h8 v2 h-8 z M4,10 h16 v2 h-16 z M2,12 h20 v2 h-20 z M2,14 h2 v2 h-2 z M6,14 h12 v2 h-12 z M20,14 h2 v2 h-2 z M4,16 h2 v2 h-2 z M18,16 h2 v2 h-2 z" fill="var(--particle-color)"/></svg>`,
            `<svg viewBox="0 0 30 30"><path d="M15,2 L18,9 L25,9 L20,14 L22,21 L15,17 L8,21 L10,14 L5,9 L12,9 Z" stroke="var(--particle-color)" fill="none" stroke-width="2"/></svg>`,
            `<svg viewBox="0 0 30 30"><rect x="3" y="3" width="24" height="24" rx="2" stroke="var(--particle-color)" fill="none" stroke-width="2"/><path d="M15,3 L15,27 M3,15 L27,15" stroke="var(--particle-color)" stroke-width="1.5" stroke-dasharray="2,2"/></svg>`,
            `<svg viewBox="0 0 30 30"><circle cx="15" cy="15" r="11" stroke="var(--particle-color)" fill="none" stroke-width="2"/></svg>`,
            `<svg viewBox="0 0 24 24"><path d="M4,4 h2 v2 h-2 z M18,4 h2 v2 h-2 z M6,6 h12 v2 h-12 z M8,8 h8 v2 h-8 z M4,10 h16 v2 h-16 z M2,12 h20 v2 h-20 z" fill="var(--particle-color)"/></svg>`,
            `<svg viewBox="0 0 30 30"><path d="M6,6 L24,24 M6,24 L24,6" stroke="var(--particle-color)" stroke-width="2.5" stroke-linecap="round"/></svg>`,
            `<svg viewBox="0 0 30 30"><polygon points="15,4 27,25 3,25" stroke="var(--particle-color)" fill="none" stroke-width="2"/></svg>`
        ];

        const numParticles = 65; // FULL implementation
        for(let i = 0; i < numParticles; i++) {
            const el = document.createElement('div');
            el.classList.add('particle');
            el.style.position = 'absolute';
            el.style.zIndex = '0';
            
            el.innerHTML = svgs[Math.floor(Math.random() * svgs.length)];
            const svgEl = el.querySelector('svg');
            
            const size = Math.floor(Math.random() * 50) + 15; 
            svgEl.setAttribute('width', size);
            svgEl.setAttribute('height', size);
            
            el.style.top = Math.floor(Math.random() * 100) + '%';
            el.style.left = Math.floor(Math.random() * 100) + '%';
            
            const duration = Math.floor(Math.random() * 30) + 20; 
            const delay = Math.floor(Math.random() * -50); 
            const animType = Math.floor(Math.random() * 4) + 1; 
            
            el.style.animation = `float-particle-${animType} ${duration}s infinite linear`;
            el.style.animationDelay = `${delay}s`;
            
            el.style.opacity = Math.random() * 0.7 + 0.3; 

            container.appendChild(el);
        }
    });
  </script>
  @endif


  

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
  
    /* MPTheme overrides */
    #hacker-preloader.theme-mptheme {
      background: var(--bg-main, #f0fdf4);
    }
    #hacker-preloader.theme-mptheme #preloader-scanline {
      background: repeating-linear-gradient(to bottom, transparent 0px, transparent 3px, rgba(16, 185, 129, 0.03) 3px, rgba(16, 185, 129, 0.03) 4px);
    }
    #hacker-preloader.theme-mptheme #preloader-brackets {
      color: var(--primary, #10b981);
    }
    #hacker-preloader.theme-mptheme #preloader-brackets span:first-child,
    #hacker-preloader.theme-mptheme #preloader-brackets span:last-child {
      color: var(--text-primary, #1e293b);
    }
    #hacker-preloader.theme-mptheme #preloader-brand {
      color: var(--text-muted, #64748b);
    }
    #hacker-preloader.theme-mptheme #preloader-terminal {
      background: var(--bg-card, rgba(255, 255, 255, 0.7));
      border-color: var(--border-color, rgba(16, 185, 129, 0.2));
      backdrop-filter: blur(12px);
      box-shadow: 0 8px 32px rgba(16, 185, 129, 0.1);
    }
    #hacker-preloader.theme-mptheme .term-line {
      color: var(--text-primary, #1e293b);
    }
    #hacker-preloader.theme-mptheme .term-line.visible {
      color: var(--text-primary, #1e293b);
    }
    #hacker-preloader.theme-mptheme .term-line:last-child.visible {
      color: var(--primary, #10b981);
    }
    #hacker-preloader.theme-mptheme #preloader-bar-wrap {
      background: rgba(16, 185, 129, 0.1);
    }
    #hacker-preloader.theme-mptheme #preloader-bar {
      background: var(--primary, #10b981);
      box-shadow: 0 0 12px var(--primary, #10b981);
    }
    </style>
    <script>
    (function() {
      var isUserAdmin = {{ Request::is('user/*') || Request::is('admin/*') ? 'true' : 'false' }};
      var currentTheme = localStorage.getItem('app-theme') || (isUserAdmin ? 'mptheme' : 'hacker');
      
      if (currentTheme === 'profesional') {
      var loader = document.getElementById('hacker-preloader');
      if (loader) loader.remove();
      return;
    }

    // ── Random emoji icon ──
          var loader = document.getElementById('hacker-preloader');
      if (loader && currentTheme.startsWith('mptheme')) {
          loader.classList.add('theme-mptheme');
      }

      // Random emoji icon
      const emojis = ['👾','🤖','⚡','🚀','💻','🕹️','🔋','⚙️','🔧','🛡️','🔒','📡','🔌','🔮','🧿','🌀','💠'];
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
