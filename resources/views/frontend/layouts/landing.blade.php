<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="default">

<head>
   <meta charset="utf-8">
   <meta http-equiv="x-ua-compatible" content="ie=edge">
   <!-- CSRF Token -->
   <meta name="csrf-token" content="{{ csrf_token() }}">
   {!! SEOMeta::generate() !!}
   {!! OpenGraph::generate() !!}
   {!! Twitter::generate() !!}
   {!! JsonLd::generate() !!}
   
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <!-- Favicon -->
   <link rel="shortcut icon" type="image/x-icon" href="{{ asset(get_option('primary_data',true)->favicon ?? '') }}">

   <!-- Google Fonts (Outfit) & Material Icons -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
   <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
   
   <!-- Vite Assets (Tailwind CSS v4 & DaisyUI) -->
   @vite(['resources/css/app.css', 'resources/js/app.js'])

   <style>
      body {
         font-family: 'Outfit', sans-serif;
      }
   </style>
</head>

<body class="bg-gradient-to-br from-base-200 via-base-100 to-base-200 min-h-screen text-gray-800 antialiased scroll-smooth">

   @yield('content')

   @include('frontend.layouts.footer-landing')

   <script type="text/javascript">
      // Language switch handler if needed
      const langSwitch = document.querySelector('.language-switch');
      if (langSwitch) {
         langSwitch.addEventListener('change', (e) => {
            window.location.href = `{{ url('/local') }}/` + e.target.value;
         });
      }
   </script>
</body>
</html>
