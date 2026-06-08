<!-- Modern Hero Section -->
<div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-base-200 via-base-100 to-base-200 py-20 lg:py-0">
  
  <!-- Ambient Gradient Mesh Background -->
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-primary/10 rounded-full blur-[120px] animate-pulse"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] bg-secondary/8 rounded-full blur-[140px] animate-pulse" style="animation-delay: 2s"></div>
    <div class="absolute top-[40%] left-[50%] transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-accent/5 rounded-full blur-[160px] animate-pulse" style="animation-delay: 1s"></div>
    
    <!-- Fine Grid Decorator -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808008_1px,transparent_1px),linear-gradient(to_bottom,#80808008_1px,transparent_1px)] bg-[size:32px_32px]"></div>
  </div>

  <!-- Social Links (Desktop Side Panel) -->
  <div class="hidden xl:flex fixed left-8 top-1/2 transform -translate-y-1/2 flex-col gap-4 z-20">
    @if(!empty(get_option('primary_data',true)->socials->twitter))
    <a href="{{ get_option('primary_data',true)->socials->twitter }}" 
       class="w-11 h-11 rounded-xl bg-white/80 backdrop-blur border border-white/20 shadow-md flex items-center justify-center text-gray-600 hover:text-primary hover:scale-110 hover:shadow-lg transition-all duration-300">
      <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
    </a>
    @endif
    @if(!empty(get_option('primary_data',true)->socials->facebook))
    <a href="{{ get_option('primary_data',true)->socials->facebook }}" 
       class="w-11 h-11 rounded-xl bg-white/80 backdrop-blur border border-white/20 shadow-md flex items-center justify-center text-gray-600 hover:text-primary hover:scale-110 hover:shadow-lg transition-all duration-300">
      <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg>
    </a>
    @endif
    @if(!empty(get_option('primary_data',true)->socials->instagram))
    <a href="{{ get_option('primary_data',true)->socials->instagram }}" 
       class="w-11 h-11 rounded-xl bg-white/80 backdrop-blur border border-white/20 shadow-md flex items-center justify-center text-gray-600 hover:text-primary hover:scale-110 hover:shadow-lg transition-all duration-300">
      <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
    </a>
    @endif
  </div>

  <!-- Scroll Down Indicator -->
  <a href="#features" class="hidden xl:flex fixed right-8 bottom-8 flex-col items-center gap-2 z-20 hover:text-primary transition-colors">
    <span class="text-xs text-gray-500 font-bold uppercase tracking-widest rotate-90 origin-center mb-8">Scroll Down</span>
    <div class="w-6 h-10 border-2 border-gray-400 rounded-full flex justify-center p-1">
      <div class="w-1.5 h-2.5 bg-gray-500 rounded-full animate-bounce"></div>
    </div>
  </a>

  <!-- Hero Container -->
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center max-w-7xl mx-auto">
      
      <!-- Left Column: Copy & Actions -->
      <div class="lg:col-span-6 flex flex-col items-start text-left space-y-6 animate-slide-up">
        
        <!-- Premium Tagline Badge -->
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 border border-primary/20 text-primary text-xs font-bold uppercase tracking-wider animate-pulse">
          <span class="flex h-2 w-2 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
          </span>
          {{ __('Next-Gen WhatsApp API Gateway') }} 🚀
        </div>

        <!-- Heading Text -->
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 leading-tight tracking-tight">
          {!! filterXss($heading) !!}
        </h1>
        
        <!-- Description Subtitle -->
        <p class="text-lg text-gray-600 leading-relaxed max-w-xl">
          {{ __('Send WhatsApp messages at scale with our ultra-fast REST API. Automate notifications, marketing, and customer support effortlessly.') }}
        </p>
        
        <!-- CTA Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto pt-2">
          <a href="{{ route('register') }}" 
             class="btn btn-primary btn-lg rounded-2xl gap-3 shadow-xl shadow-primary/20 hover:shadow-2xl hover:scale-[1.03] active:scale-[0.97] transition-all duration-300 px-8 text-white">
            <span class="material-icons">rocket_launch</span>
            {{ __('Get Started Free') }}
          </a>
          <a href="#features" 
             class="btn btn-outline btn-lg rounded-2xl gap-3 hover:bg-gray-150/50 hover:scale-[1.03] active:scale-[0.97] transition-all duration-300 px-8 text-gray-800">
            <span class="material-icons">play_circle</span>
            {{ __('See Features') }}
          </a>
        </div>

        <!-- Trust Badges Section -->
        <div class="pt-6 grid grid-cols-2 sm:grid-cols-3 gap-y-3 gap-x-6 text-sm text-gray-600 font-semibold w-full border-t border-gray-150">
          <div class="flex items-center gap-2">
            <span class="material-icons text-emerald-500 text-lg">check_circle</span>
            {{ __('No credit card required') }}
          </div>
          <div class="flex items-center gap-2">
            <span class="material-icons text-emerald-500 text-lg">check_circle</span>
            {{ __('Instant API Access') }}
          </div>
          <div class="flex items-center gap-2 col-span-2 sm:col-span-1">
            <span class="material-icons text-emerald-500 text-lg">check_circle</span>
            {{ __('Cancel subscription anytime') }}
          </div>
        </div>
        
      </div>

      <!-- Right Column: Interactive Illustration / Mockup -->
      <div class="lg:col-span-6 relative flex justify-center items-center animate-fade-in" style="animation-delay: 0.2s">
        
        <div class="relative w-full max-w-lg lg:max-w-xl">
          <!-- Main Mockup Container -->
          <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white bg-white/75 p-2 backdrop-blur hover-lift transition-all duration-500 z-10">
            <img src="{{ asset($home->hero_image ?? 'assets/frontend/img/hero/hero-bg.png') }}" 
                 alt="WhatsApp API Dashboard" 
                 class="w-full h-auto rounded-[2rem] object-cover" />
          </div>

          <!-- Decorative Floating Widgets -->
          <!-- Left Widget: Connection status -->
          <div class="absolute -left-6 top-1/4 hidden sm:flex items-center gap-3 bg-white/90 backdrop-blur border border-white/20 rounded-2xl p-4 shadow-xl z-20 animate-float">
            <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500">
              <span class="material-icons">link</span>
            </div>
            <div>
              <span class="text-xs text-gray-500 block font-bold uppercase tracking-wider">{{ __('WhatsApp status') }}</span>
              <span class="text-sm text-gray-900 font-bold flex items-center gap-1.5">
                <span class="status-online"></span>
                {{ __('Connected') }}
              </span>
            </div>
          </div>

          <!-- Right Widget: Message counter -->
          <div class="absolute -right-6 bottom-1/4 hidden sm:flex items-center gap-3 bg-white/90 backdrop-blur border border-white/20 rounded-2xl p-4 shadow-xl z-20 animate-float" style="animation-delay: 0.7s">
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
              <span class="material-icons">send</span>
            </div>
            <div>
              <span class="text-xs text-gray-500 block font-bold uppercase tracking-wider">{{ __('Delivery Rate') }}</span>
              <span class="text-sm text-gray-900 font-extrabold">{{ __('99.9% Uptime') }}</span>
            </div>
          </div>

          <!-- Background Glowing Sphere Behind Mockup -->
          <div class="absolute inset-0 bg-gradient-to-tr from-primary/20 to-accent/20 rounded-full blur-[100px] -z-10 scale-95"></div>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Add custom floating animations -->
<style>
@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-15px);
  }
}

.animate-float {
  animation: float 4s ease-in-out infinite;
}
</style>