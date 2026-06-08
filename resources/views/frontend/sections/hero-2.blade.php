<!-- Modern Centered Hero Section for Theme 2 -->
<div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-base-200 via-base-100 to-base-200 pt-32 pb-20">
   
   <!-- Ambient Gradient Mesh Background -->
   <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute top-[-20%] left-[-10%] w-[60%] h-[50%] bg-primary/10 rounded-full blur-[140px] animate-pulse"></div>
      <div class="absolute bottom-[-15%] right-[-15%] w-[60%] h-[50%] bg-secondary/8 rounded-full blur-[140px] animate-pulse" style="animation-delay: 2.5s"></div>
      
      <!-- Fine Grid Decorator -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808006_1px,transparent_1px),linear-gradient(to_bottom,#80808008_1px,transparent_1px)] bg-[size:32px_32px]"></div>
   </div>

   <!-- Hero Content -->
   <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
      <div class="max-w-4xl mx-auto flex flex-col items-center text-center space-y-8 animate-slide-up">
         
         <!-- Centered Header Tagline -->
         <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 leading-tight tracking-tight max-w-3xl">
            {!! filterXss($heading) !!}
         </h1>

         <!-- Store App Download buttons -->
         <div class="flex justify-center items-center gap-4 flex-wrap pb-2">
            @if(!empty($home->left_button_image))
            <a href="{{ $home->heading->left_button_link ?? '#' }}" class="hover:scale-105 active:scale-95 transition-all duration-300 shadow-md hover:shadow-lg rounded-xl overflow-hidden">
               <img src="{{ asset($home->left_button_image) }}" alt="Get it on Google Play" class="h-11 w-auto object-contain">
            </a>
            @endif
            @if(!empty($home->right_button_image))
            <a href="{{ $home->heading->right_button_link ?? '#' }}" class="hover:scale-105 active:scale-95 transition-all duration-300 shadow-md hover:shadow-lg rounded-xl overflow-hidden">
               <img src="{{ asset($home->right_button_image) }}" alt="Download on App Store" class="h-11 w-auto object-contain">
            </a>
            @endif
         </div>

         <!-- Contact Link -->
         <div class="text-sm font-semibold text-gray-500">
            <a href="{{ url('/contact') }}" class="hover:text-primary transition-colors flex items-center justify-center gap-1.5 group">
               <span class="material-icons text-gray-400 group-hover:text-primary transition-colors">chat_bubble</span>
               {{ __('Have a query?') }} 
               <span class="text-primary border-b border-primary/20 group-hover:border-primary transition-all">{{ __('Contact us') }}</span>
            </a>
         </div>

         <!-- Central Mockup Container -->
         <div class="relative w-full max-w-4xl mt-12 animate-slide-up" style="animation-delay: 0.3s">
            
            <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white bg-white/70 p-2 backdrop-blur hover-lift transition-all duration-500 z-10">
               <img src="{{ asset($home->hero_image ?? '') }}" alt="WhatsApp API Mockup" class="w-full h-auto rounded-[2rem] object-cover" />
            </div>

            <!-- Left Floating Widget -->
            @if(!empty($home->hero_left_image))
            <div class="absolute -left-8 top-1/4 hidden lg:flex items-center bg-white border border-gray-150 rounded-2xl p-4 shadow-2xl z-20 animate-float">
               <img src="{{ asset($home->hero_left_image) }}" alt="Feature Left" class="w-32 h-auto object-contain rounded-xl" />
            </div>
            @endif

            <!-- Right Floating Widget -->
            @if(!empty($home->hero_right_image))
            <div class="absolute -right-8 top-1/2 hidden lg:flex items-center bg-white border border-gray-150 rounded-2xl p-4 shadow-2xl z-20 animate-float" style="animation-delay: 1.5s">
               <img src="{{ asset($home->hero_right_image) }}" alt="Feature Right" class="w-32 h-auto object-contain rounded-xl" />
            </div>
            @endif

            <!-- Glowing Backdrop Blur sphere -->
            <div class="absolute inset-0 bg-gradient-to-tr from-primary/15 to-secondary/15 rounded-full blur-[120px] -z-10 scale-90"></div>
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