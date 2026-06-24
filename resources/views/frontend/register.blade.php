<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="default">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ __('Daftar') }} — {{ env('APP_NAME') }}</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset(get_option('primary_data',true)->favicon ?? '') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-base-200 via-base-100 to-base-200 flex items-center justify-center p-4">

  <div class="w-full max-w-2xl animate-fade-in">

    {{-- ── Logo & heading ── --}}
    <div class="flex flex-col items-center gap-3 mb-6">
      <div class="bg-base-100 rounded-2xl p-4 shadow-lg border border-base-300">
        <img src="{{ asset('uploads/wasapi/logo.png') }}"
             class="h-12 w-auto" alt="{{ env('APP_NAME') }}">
      </div>
      <span class="text-xs font-bold tracking-[.25em] uppercase text-base-content/40">{{ env('APP_NAME') }}</span>
    </div>

    {{-- ── Card ── --}}
    <div class="card shadow-2xl border border-base-300 bg-base-100">
      <div class="card-body p-8 sm:p-10">

        {{-- Plan badge --}}
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
          <div>
            <h1 class="text-2xl font-extrabold text-base-content">Buat akun baru ✨</h1>
            <p class="text-sm text-base-content/50 mt-0.5">Isi data berikut untuk memulai</p>
          </div>
          <div class="flex flex-col items-end">
            <div class="badge badge-primary badge-lg px-4 py-3 font-bold text-sm gap-1.5">
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
              </svg>
              {{ $plan->title }}
            </div>
            <span class="text-xs text-base-content/40 mt-1 font-semibold">
              Rp {{ number_format($plan->price, 0, ',', '.') }} / {{ $plan->duration ?? 30 }} hari
            </span>
          </div>
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

        {{-- Form --}}
        <form method="POST" action="{{ url('register-plan', $plan->id) }}" id="registerForm">
          @csrf

          {{-- Row 1: Name + Email --}}
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">

            {{-- Name --}}
            <div class="form-control gap-1">
              <label class="label py-0" for="name">
                <span class="label-text font-semibold text-base-content/70 text-sm">Nama Lengkap</span>
              </label>
              <label class="input input-bordered flex items-center gap-2 @error('name') input-error @enderror">
                <svg class="w-4 h-4 opacity-40 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <input id="name" type="text" name="name"
                       value="{{ old('name') }}"
                       placeholder="Nama lengkap"
                       class="grow text-sm" required autofocus autocomplete="name">
              </label>
              @error('name')
                <span class="text-error text-xs pl-1">{{ $message }}</span>
              @enderror
            </div>

            {{-- Email --}}
            <div class="form-control gap-1">
              <label class="label py-0" for="email">
                <span class="label-text font-semibold text-base-content/70 text-sm">Email</span>
              </label>
              <label class="input input-bordered flex items-center gap-2 @error('email') input-error @enderror">
                <svg class="w-4 h-4 opacity-40 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <input id="email" type="email" name="email"
                       value="{{ $request->email ?? old('email') }}"
                       placeholder="nama@email.com"
                       class="grow text-sm" required autocomplete="email">
              </label>
              @error('email')
                <span class="text-error text-xs pl-1">{{ $message }}</span>
              @enderror
            </div>

          </div>

          {{-- Row 2: Password --}}
          <div class="form-control gap-1 mb-2">
            <label class="label py-0" for="password">
              <span class="label-text font-semibold text-base-content/70 text-sm">Password</span>
            </label>
            <label class="input input-bordered flex items-center gap-2 @error('password') input-error @enderror">
              <svg class="w-4 h-4 opacity-40 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
              <input id="password" type="password" name="password"
                     placeholder="Min. 8 karakter"
                     class="grow text-sm" required autocomplete="new-password">
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

          {{-- Password strength --}}
          <div id="strengthWrap" class="hidden mb-4">
            <div class="flex gap-1.5 mb-1">
              <div class="h-1.5 flex-1 rounded-full bg-base-300 transition-all duration-300" id="s1"></div>
              <div class="h-1.5 flex-1 rounded-full bg-base-300 transition-all duration-300" id="s2"></div>
              <div class="h-1.5 flex-1 rounded-full bg-base-300 transition-all duration-300" id="s3"></div>
              <div class="h-1.5 flex-1 rounded-full bg-base-300 transition-all duration-300" id="s4"></div>
            </div>
            <div class="text-xs text-base-content/40" id="strengthLabel"></div>
          </div>

          {{-- Terms --}}
          <div class="flex items-start gap-2.5 mb-5">
            <input type="checkbox" id="terms" required
                   class="checkbox checkbox-primary checkbox-sm mt-0.5 flex-shrink-0">
            <label for="terms" class="text-xs text-base-content/50 leading-relaxed cursor-pointer">
              Saya menyetujui
              <a href="{{ url('/') }}" class="text-primary hover:text-primary/75 font-semibold">Syarat & Ketentuan</a>
              dan
              <a href="{{ url('/') }}" class="text-primary hover:text-primary/75 font-semibold">Kebijakan Privasi</a>
              dari {{ env('APP_NAME') }}.
            </label>
          </div>

          {{-- Submit --}}
          <button type="submit" id="btnRegister" class="btn btn-primary w-full gap-2">
            <span class="loading loading-spinner loading-sm hidden" id="spinner"></span>
            <svg id="btnIcon" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            <span id="btnText">Buat Akun & Lanjutkan ke Pembayaran</span>
          </button>

        </form>

        {{-- Footer links --}}
        <div class="divider text-xs text-base-content/30 my-4">SUDAH PUNYA AKUN?</div>

        <div class="flex items-center justify-between flex-wrap gap-3">
          <a href="{{ url('/login') }}"
             class="btn btn-ghost btn-sm gap-1.5 text-primary font-bold">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Masuk sekarang
          </a>
          <a href="{{ url('/pricing') }}"
             class="inline-flex items-center gap-1.5 text-xs text-base-content/35 hover:text-base-content/60 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Halaman Harga
          </a>
        </div>

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

    // Password strength
    const pwdInput     = document.getElementById('password');
    const strengthWrap = document.getElementById('strengthWrap');
    const strengthLbl  = document.getElementById('strengthLabel');
    const bars         = ['s1','s2','s3','s4'].map(id => document.getElementById(id));
    const levels       = [
      { color: 'bg-error',   label: 'Terlalu lemah' },
      { color: 'bg-warning', label: 'Lemah' },
      { color: 'bg-info',    label: 'Cukup kuat' },
      { color: 'bg-success', label: 'Kuat ✓' },
    ];
    pwdInput?.addEventListener('input', () => {
      const v     = pwdInput.value;
      let score   = 0;
      if (v.length >= 8)           score++;
      if (/[A-Z]/.test(v))         score++;
      if (/[0-9]/.test(v))         score++;
      if (/[^A-Za-z0-9]/.test(v))  score++;
      strengthWrap.classList.toggle('hidden', v.length === 0);
      bars.forEach((b, i) => {
        b.className = 'h-1.5 flex-1 rounded-full transition-all duration-300 ' +
          (i < score ? levels[score - 1].color : 'bg-base-300');
      });
      strengthLbl.textContent = v.length ? (levels[score - 1]?.label ?? '') : '';
    });

    // Loading on submit
    document.getElementById('registerForm')?.addEventListener('submit', () => {
      const btn    = document.getElementById('btnRegister');
      const sp     = document.getElementById('spinner');
      const icon   = document.getElementById('btnIcon');
      const text   = document.getElementById('btnText');
      btn.disabled = true;
      sp.classList.remove('hidden');
      icon.classList.add('hidden');
      text.textContent = 'Memproses...';
    });
  </script>

</body>
</html>
