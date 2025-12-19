<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="default">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ __('Login') }} - {{ env('APP_NAME') }}</title>
  
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset(get_option('primary_data',true)->favicon ?? '') }}">
  
  <!-- Vite Assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
  
<!-- Hero Pattern Background -->
<div class="min-h-screen hero bg-base-200">
  <div class="hero-content flex-col lg:flex-row-reverse w-full max-w-6xl p-4 gap-8">
    
    <!-- Branding Side -->
    <div class="text-center lg:text-left flex-1">
      <!-- Logo -->
      <div class="flex items-center justify-center lg:justify-start mb-6">
        <a href="{{ url('/') }}">
          <img src="{{ asset(get_option('primary_data',true)->logo ?? '') }}" class="h-12" alt="{{ env('APP_NAME') }}">
        </a>
      </div>
      
      <p class="text-lg mb-8 opacity-80">
        {{ __('Send messages at scale with our powerful WhatsApp API platform') }}
      </p>
      
      <!-- Features -->
      <div class="grid grid-cols-1 gap-4 max-w-md mx-auto lg:mx-0">
        <div class="card bg-base-100 shadow-sm">
          <div class="card-body p-4 flex-row items-center gap-4">
            <div class="badge badge-primary badge-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="text-left">
              <h3 class="font-semibold">{{ __('Easy Integration') }}</h3>
              <p class="text-sm opacity-70">{{ __('Simple REST API') }}</p>
            </div>
          </div>
        </div>
        
        <div class="card bg-base-100 shadow-sm">
          <div class="card-body p-4 flex-row items-center gap-4">
            <div class="badge badge-secondary badge-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="text-left">
              <h3 class="font-semibold">{{ __('Reliable Delivery') }}</h3>
              <p class="text-sm opacity-70">{{ __('99.9% uptime') }}</p>
            </div>
          </div>
        </div>
        
        <div class="card bg-base-100 shadow-sm">
          <div class="card-body p-4 flex-row items-center gap-4">
            <div class="badge badge-accent badge-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
              </svg>
            </div>
            <div class="text-left">
              <h3 class="font-semibold">{{ __('24/7 Support') }}</h3>
              <p class="text-sm opacity-70">{{ __('Always here to help') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Login Card -->
    <div class="card shrink-0 w-full max-w-md shadow-2xl bg-base-100">
      <div class="card-body">
        <!-- Header -->
        <div class="text-center mb-4">
          <h2 class="text-2xl font-bold">{{ __('Welcome Back!') }}</h2>
          <p class="text-sm opacity-70">{{ __('Sign in to continue to your account') }}</p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
          @csrf
          
          <!-- Email Input -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Email Address') }}</span>
            </label>
            <label class="input input-bordered flex items-center gap-2 @error('email') input-error @enderror">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70">
                <path d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z" />
                <path d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z" />
              </svg>
              <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                placeholder="your@email.com"
                class="grow" 
                required 
                autocomplete="email" 
                autofocus
              />
            </label>
            @error('email')
              <label class="label">
                <span class="label-text-alt text-error">{{ $message }}</span>
              </label>
            @enderror
          </div>

          <!-- Password Input -->
          <div class="form-control mt-4">
            <label class="label">
              <span class="label-text font-medium">{{ __('Password') }}</span>
            </label>
            <label class="input input-bordered flex items-center gap-2 @error('password') input-error @enderror">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70">
                <path fill-rule="evenodd" d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z" clip-rule="evenodd" />
              </svg>
              <input 
                id="password" 
                type="password" 
                name="password" 
                placeholder="••••••••"
                class="grow" 
                required 
                autocomplete="current-password"
              />
            </label>
            @error('password')
              <label class="label">
                <span class="label-text-alt text-error">{{ $message }}</span>
              </label>
            @enderror
          </div>

          <!-- Remember & Forgot -->
          <div class="form-control mt-4">
            <label class="label cursor-pointer justify-start gap-2">
              <input type="checkbox" class="checkbox checkbox-primary checkbox-sm" id="remember" name="remember" />
              <span class="label-text">{{ __('Remember me') }}</span>
            </label>
          </div>

          <!-- Submit Button -->
          <div class="form-control mt-6">
            <button type="submit" class="btn btn-primary gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                <path fill-rule="evenodd" d="M2 4.75A2.75 2.75 0 0 1 4.75 2h3a2.75 2.75 0 0 1 2.75 2.75v.5a.75.75 0 0 1-1.5 0v-.5c0-.69-.56-1.25-1.25-1.25h-3c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h3c.69 0 1.25-.56 1.25-1.25v-.5a.75.75 0 0 1 1.5 0v.5A2.75 2.75 0 0 1 7.75 14h-3A2.75 2.75 0 0 1 2 11.25v-6.5Zm9.47.47a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06l-2.25 2.25a.75.75 0 1 1-1.06-1.06l.97-.97H5.25a.75.75 0 0 1 0-1.5h7.19l-.97-.97a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
              </svg>
              {{ __('Sign In') }}
            </button>
          </div>

          <!-- Forgot Password -->
          <div class="text-center mt-4">
            <a href="{{ route('password.request') }}" class="link link-hover text-sm">
              {{ __('Forgot your password?') }}
            </a>
          </div>

          <!-- Divider -->
          <div class="divider">{{ __('OR') }}</div>

          <!-- Sign Up Link -->
          <div class="text-center">
            <p class="text-sm">
              {{ __("Don't have an account?") }}
              <a href="{{ url('/pricing') }}" class="link link-primary font-semibold">
                {{ __('Sign up now') }}
              </a>
            </p>
          </div>

          <!-- Back to Home -->
          <div class="text-center mt-4">
            <a href="{{ url('/') }}" class="btn btn-ghost btn-sm gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                <path fill-rule="evenodd" d="M14 8a.75.75 0 0 1-.75.75H4.56l3.22 3.22a.75.75 0 1 1-1.06 1.06l-4.5-4.5a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 1.06L4.56 7.25h8.69A.75.75 0 0 1 14 8Z" clip-rule="evenodd" />
              </svg>
              {{ __('Back to Home') }}
            </a>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

<!-- Footer -->
<footer class="footer footer-center p-4 bg-base-200 text-base-content">
  <aside>
    <p class="text-sm opacity-70">© {{ date('Y') }} {{ env('APP_NAME') }}. {{ __('All rights reserved.') }}</p>
  </aside>
</footer>

</body>
</html>
