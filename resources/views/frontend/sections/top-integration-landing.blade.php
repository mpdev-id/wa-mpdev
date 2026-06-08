<!-- Modern Integration Section -->
<div class="py-24 bg-white relative overflow-hidden">
   <!-- Ambient backdrops -->
   <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-secondary/5 rounded-full blur-[120px] pointer-events-none"></div>

   <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16">
         <span class="text-xs font-extrabold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1.5 rounded-lg mb-4 inline-block">
            {{ __('Seamless Integrations') }}
         </span>
         <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4 tracking-tight">
            {{ $home->integration->title ?? '' }}
         </h2>
         <p class="text-gray-500 text-lg">
            {{ __('Connect your favorite tools and platforms to automate workflows with ease.') }}
         </p>
      </div>

      <!-- Integration Brands Grid -->
      <div class="flex flex-wrap justify-center items-center gap-5 sm:gap-6 max-w-4xl mx-auto mb-20">
         @foreach($brands as $brandKey => $brand)
            @if($brand->lang == 'integration')
               <div class="bg-white/80 border border-gray-150 rounded-2xl p-4 shadow-sm hover:shadow-xl hover:scale-110 hover:-translate-y-1 transition-all duration-300 w-20 h-20 sm:w-24 sm:h-24 flex items-center justify-center relative group">
                  <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                  <img src="{{ asset($brand->slug) }}" 
                       alt="Integration Logo" 
                       class="max-w-[70%] max-h-[70%] object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300" />
               </div>
            @endif
         @endforeach
      </div>

      <!-- Central Integration Mockup Image -->
      @if(!empty($home->integration_cover))
      <div class="relative max-w-4xl mx-auto group">
         <!-- Shadow Glow -->
         <div class="absolute -inset-4 rounded-3xl bg-gradient-to-tr from-primary/10 to-secondary/10 blur-2xl opacity-60 pointer-events-none"></div>
         
         <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white bg-white/50 p-2 backdrop-blur-sm">
            <img src="{{ asset($home->integration_cover) }}" 
                 alt="Integration Dashboard Mockup" 
                 class="w-full h-auto rounded-2xl object-cover group-hover:scale-[1.005] transition-transform duration-500" />
         </div>

         <!-- Left Floating Side Image -->
         @if(!empty($home->integration_left))
         <div class="absolute -left-8 bottom-12 hidden md:block w-24 h-24 bg-white border border-gray-150 rounded-2xl p-4 shadow-2xl animate-float">
            <img src="{{ asset($home->integration_left) }}" alt="Left Side Integration" class="w-full h-full object-contain" />
         </div>
         @endif

         <!-- Right Floating Side Image -->
         @if(!empty($home->integration_right))
         <div class="absolute -right-8 top-12 hidden md:block w-24 h-24 bg-white border border-gray-150 rounded-2xl p-4 shadow-2xl animate-float" style="animation-delay: 1.5s">
            <img src="{{ asset($home->integration_right) }}" alt="Right Side Integration" class="w-full h-full object-contain" />
         </div>
         @endif
      </div>
      @endif

   </div>
</div>
