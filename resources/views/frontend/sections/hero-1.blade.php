<!-- Modern Hero Section -->
<div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-primary/5 via-white to-secondary/5">
  <!-- Animated Background Elements -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute top-20 left-10 w-72 h-72 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-secondary/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-accent/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 0.5s"></div>
  </div>

  <!-- Social Links (Desktop) -->
  <div class="hidden xl:flex fixed left-8 top-1/2 transform -translate-y-1/2 flex-col gap-4 z-20">
    @if(!empty(get_option('primary_data',true)->socials->twitter))
    <a href="{{ get_option('primary_data',true)->socials->twitter }}" 
       class="w-12 h-12 rounded-full bg-white shadow-lg flex items-center justify-center text-gray-600 hover:text-primary hover:scale-110 transition-all duration-300">
      <i class="fab fa-twitter text-xl"></i>
    </a>
    @endif
    @if(!empty(get_option('primary_data',true)->socials->facebook))
    <a href="{{ get_option('primary_data',true)->socials->facebook }}" 
       class="w-12 h-12 rounded-full bg-white shadow-lg flex items-center justify-center text-gray-600 hover:text-primary hover:scale-110 transition-all duration-300">
      <i class="fab fa-facebook-f text-xl"></i>
    </a>
    @endif
    @if(!empty(get_option('primary_data',true)->socials->instagram))
    <a href="{{ get_option('primary_data',true)->socials->instagram }}" 
       class="w-12 h-12 rounded-full bg-white shadow-lg flex items-center justify-center text-gray-600 hover:text-primary hover:scale-110 transition-all duration-300">
      <i class="fab fa-instagram text-xl"></i>
    </a>
    @endif
    @if(!empty(get_option('primary_data',true)->socials->linkedin))
    <a href="{{ get_option('primary_data',true)->socials->linkedin }}" 
       class="w-12 h-12 rounded-full bg-white shadow-lg flex items-center justify-center text-gray-600 hover:text-primary hover:scale-110 transition-all duration-300">
      <i class="fab fa-linkedin text-xl"></i>
    </a>
    @endif
  </div>

  <!-- Scroll Down Indicator -->
  <a href="#features" class="hidden xl:flex fixed right-8 bottom-8 flex-col items-center gap-2 z-20 animate-bounce">
    <span class="text-sm text-gray-600 font-medium rotate-90 origin-center mb-8">Scroll Down</span>
    <div class="w-6 h-10 border-2 border-gray-400 rounded-full flex justify-center">
      <div class="w-1 h-3 bg-gray-400 rounded-full mt-2 animate-pulse"></div>
    </div>
  </a>

  <!-- Hero Content -->
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="max-w-6xl mx-auto">
      
      <!-- Text Content -->
      <div class="text-center mb-16 animate-fade-in">
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 mb-6 leading-tight">
          {!! filterXss($heading) !!}
        </h1>
        <p class="text-lg sm:text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
          {{ __('Send WhatsApp messages at scale with our powerful API. Connect, automate, and grow your business.') }}
        </p>
        
        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
          <a href="{{ route('register') }}" 
             class="btn btn-primary btn-lg gap-3 shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 px-8">
            <span class="material-icons">rocket_launch</span>
            {{ __('Get Started Free') }}
          </a>
          <a href="#features" 
             class="btn btn-outline btn-lg gap-3 hover:bg-gray-100 px-8">
            <span class="material-icons">play_circle</span>
            {{ __('See How It Works') }}
          </a>
        </div>

        <!-- Trust Badge -->
        <div class="mt-8 flex items-center justify-center gap-6 text-sm text-gray-600">
          <div class="flex items-center gap-2">
            <span class="material-icons text-green-500">check_circle</span>
            {{ __('No Credit Card Required') }}
          </div>
          <div class="flex items-center gap-2">
            <span class="material-icons text-green-500">check_circle</span>
            {{ __('Free Trial') }}
          </div>
          <div class="flex items-center gap-2 hidden sm:flex">
            <span class="material-icons text-green-500">check_circle</span>
            {{ __('Cancel Anytime') }}
          </div>
        </div>
      </div>

      <!-- Hero Image/Illustration -->
      <div class="relative animate-slide-up" style="animation-delay: 0.3s">
        <div class="relative mx-auto max-w-5xl">
          <!-- Main Image -->
          <div class="relative rounded-3xl overflow-hidden shadow-2xl border-8 border-white/50 backdrop-blur-sm">
            <img src="{{ asset($home->hero_image ?? 'assets/frontend/img/hero/hero-bg.png') }}" 
                 alt="WhatsApp API Dashboard" 
                 class="w-full h-auto" />
          </div>

          <!-- Floating Elements -->
          @if(!empty($home->hero_left_image))
          <div class="absolute -left-8 top-1/4 transform -translate-y-1/2 hidden md:block animate-float">
            <div class="bg-white rounded-2xl shadow-2xl p-4 border border-gray-100">
              <img src="{{ asset($home->hero_left_image) }}" 
                   alt="Feature" 
                   class="w-32 h-32 object-contain" />
            </div>
          </div>
          @endif

          @if(!empty($home->hero_right_image))
          <div class="absolute -right-8 top-1/2 transform -translate-y-1/2 hidden md:block animate-float" style="animation-delay: 0.5s">
            <div class="bg-white rounded-2xl shadow-2xl p-4 border border-gray-100">
              <img src="{{ asset($home->hero_right_image) }}" 
                   alt="Feature" 
                   class="w-32 h-32 object-contain" />
            </div>
          </div>
          @endif
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Add custom animations -->
<style>
@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-20px);
  }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}
</style>