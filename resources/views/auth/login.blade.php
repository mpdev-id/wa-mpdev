<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="default">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ __('Login') }} — {{ env('APP_NAME') }}</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset(get_option('primary_data',true)->favicon ?? '') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-base-200 via-base-100 to-base-200 flex items-center justify-center p-4">

  <div class="w-full max-w-4xl animate-fade-in">
    <div class="card lg:card-side shadow-2xl overflow-hidden border border-base-300 bg-base-100">

      {{-- ══ LEFT: Branding ══ --}}
      <div class="hidden lg:flex flex-col items-center justify-center w-[380px] flex-shrink-0 p-10 relative overflow-hidden
                  bg-gradient-to-br from-primary via-primary to-primary/90 text-primary-content">

        {{-- Decorative blobs --}}
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-16 -left-16 w-64 h-64 rounded-full bg-white/5"></div>
        <div class="absolute top-1/3 -right-8 w-32 h-32 rounded-full bg-secondary/25"></div>

        {{-- Content --}}
        <div class="relative z-10 flex flex-col items-center text-center gap-8">

          {{-- Logo --}}
          <div class="flex flex-col items-center gap-3">
            <div class="bg-white rounded-2xl p-4 shadow-xl">
              <img src="{{ asset('uploads/wasapi/logo.png') }}"
                   class="h-14 w-auto" alt="{{ env('APP_NAME') }}">
            </div>
            <span class="text-xs font-bold tracking-[.25em] uppercase text-primary-content/60">
              {{ env('APP_NAME') }}
            </span>
          </div>

          {{-- Tagline --}}
          <div>
            <h2 class="text-2xl font-extrabold leading-snug mb-2">
              WhatsApp API<br>untuk Bisnis Anda
            </h2>
            <p class="text-sm text-primary-content/65 leading-relaxed">
              Kirim pesan massal, otomatisasi notifikasi, dan integrasi chatbot — semua dalam satu platform.
            </p>
          </div>

          {{-- Features --}}
          <ul class="w-full flex flex-col gap-3 text-left">
            @foreach([
              ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'REST API Mudah', 'sub' => 'Kirim pesan via HTTP request'],
              ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Uptime 99.9%', 'sub' => 'Infrastruktur stabil & andal'],
              ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'QRIS Otomatis', 'sub' => 'Verifikasi pembayaran instan'],
            ] as $f)
            <li class="flex items-center gap-3 bg-white/10 rounded-xl px-4 py-3 border border-white/15">
              <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}"/>
                </svg>
              </div>
              <div>
                <div class="text-sm font-semibold">{{ $f['title'] }}</div>
                <div class="text-xs text-primary-content/55">{{ $f['sub'] }}</div>
              </div>
            </li>
            @endforeach
          </ul>

        </div>
      </div>

      {{-- ══ RIGHT: Form ══ --}}
      <div class="flex-1 flex flex-col justify-center p-8 sm:p-10 lg:p-12">

        {{-- Mobile: logo --}}
        <div class="flex lg:hidden flex-col items-center gap-2 mb-8">
          <div class="bg-base-200 rounded-2xl p-3 shadow-md">
            <img src="{{ asset('uploads/wasapi/logo.png') }}"
                 class="h-12 w-auto" alt="{{ env('APP_NAME') }}">
          </div>
          <span class="text-xs font-bold tracking-[.2em] uppercase text-base-content/40">{{ env('APP_NAME') }}</span>
        </div>

        {{-- Header --}}
        <div class="mb-6">
          <h1 class="text-2xl font-extrabold text-base-content">Selamat datang 👋</h1>
          <p class="text-sm text-base-content/50 mt-1">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        {{-- Alerts --}}
        @if($errors->any())
          <div role="alert" class="alert alert-error mb-5 text-sm py-3 animate-slide-down">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <div>
              @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
              @endforeach
            </div>
          </div>
        @endif

        @if(session('status'))
          <div role="alert" class="alert alert-success mb-5 text-sm py-3 animate-slide-down">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('status') }}
          </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" id="loginForm" class="flex flex-col gap-4 w-full">
          @csrf

          {{-- Email --}}
          <div class="form-control gap-1">
            <label class="label py-0" for="email">
              <span class="label-text font-semibold text-base-content/70 text-sm">Email</span>
            </label>
            <label class="input input-bordered flex items-center gap-2 w-full @error('email') input-error @enderror">
              <svg class="w-4 h-4 opacity-40 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              <input id="email" type="email" name="email"
                     value="{{ old('email') }}"
                     placeholder="nama@email.com"
                     class="grow text-sm" required autocomplete="email" autofocus>
            </label>
            @error('email')
              <span class="text-error text-xs pl-1">{{ $message }}</span>
            @enderror
          </div>

          {{-- Password --}}
          <div class="form-control gap-1">
            <label class="label py-0" for="password">
              <span class="label-text font-semibold text-base-content/70 text-sm">Password</span>
            </label>
            <label class="input input-bordered flex items-center gap-2 w-full @error('password') input-error @enderror">
              <svg class="w-4 h-4 opacity-40 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
              <input id="password" type="password" name="password"
                     placeholder="••••••••"
                     class="grow text-sm" required autocomplete="current-password">
              <button type="button" id="togglePass"
                      class="opacity-30 hover:opacity-60 transition-opacity cursor-pointer">
                <svg id="eyeShow" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg id="eyeHide" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
              </button>
            </label>
            @error('password')
              <span class="text-error text-xs pl-1">{{ $message }}</span>
            @enderror
          </div>

          {{-- Remember & Forgot --}}
          <div class="flex items-center justify-between mt-1">
            <label class="cursor-pointer flex items-center gap-2">
              <input type="checkbox" id="remember" name="remember"
                     class="checkbox checkbox-primary checkbox-sm">
              <span class="text-sm text-base-content/55">Ingat saya</span>
            </label>
            <a href="{{ route('password.request') }}"
               class="text-sm font-semibold text-primary hover:text-primary/75 transition-colors">
              Lupa password?
            </a>
          </div>

          {{-- Submit --}}
          <button type="submit" id="btnLogin"
                  class="btn btn-primary w-full gap-2 mt-1">
            <span class="loading loading-spinner loading-sm hidden" id="spinner"></span>
            <svg id="btnIcon" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            <span id="btnText">Masuk ke Akun</span>
          </button>

        </form>

        {{-- Divider --}}
        <div class="divider text-xs text-base-content/30 my-3">ATAU</div>

        {{-- Register --}}
        <p class="text-center text-sm text-base-content/45">
          Belum punya akun?
          <a href="{{ url('/pricing') }}"
             class="font-bold text-primary hover:text-primary/75 transition-colors">
            Daftar sekarang
          </a>
        </p>

        {{-- Back --}}
        <div class="text-center mt-4">
          <a href="{{ url('/') }}"
             class="inline-flex items-center gap-1.5 text-xs text-base-content/35 hover:text-base-content/60 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Beranda
          </a>
        </div>

        {{-- Copyright --}}
        <p class="text-center text-xs text-base-content/20 mt-6">
          © {{ date('Y') }} {{ env('APP_NAME') }}. Semua hak dilindungi.
        </p>

      </div>
    </div>
  </div>

  <script>
    // Toggle password
    document.getElementById('togglePass')?.addEventListener('click', () => {
      const input   = document.getElementById('password');
      const eyeShow = document.getElementById('eyeShow');
      const eyeHide = document.getElementById('eyeHide');
      const show    = input.type === 'password';
      input.type    = show ? 'text' : 'password';
      eyeShow.classList.toggle('hidden', show);
      eyeHide.classList.toggle('hidden', !show);
    });

    // Loading on submit
    document.getElementById('loginForm')?.addEventListener('submit', () => {
      const btn     = document.getElementById('btnLogin');
      const spinner = document.getElementById('spinner');
      const icon    = document.getElementById('btnIcon');
      const text    = document.getElementById('btnText');
      btn.disabled  = true;
      spinner.classList.remove('hidden');
      icon.classList.add('hidden');
      text.textContent = 'Memproses...';
    });
  </script>

</body>
</html>
