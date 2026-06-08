<!-- Modern Footer -->
<footer class="bg-gray-900 text-gray-300 pt-16 pb-12 overflow-hidden relative">
   
   <!-- Subtle Background Glow -->
   <div class="absolute -top-40 left-1/2 transform -translate-x-1/2 w-[600px] h-[300px] bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>

   <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      
      <!-- Top Call-to-Action Section -->
      <div class="text-center max-w-3xl mx-auto mb-16">
         <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 tracking-tight">
            {{ get_option('header_footer',true,true)->footer->title ?? '' }}
         </h2>
         <p class="text-lg text-gray-400 mb-8 leading-relaxed">
            {{ get_option('header_footer',true,true)->footer->description ?? '' }}
         </p>
         
         <!-- App/Play Store Buttons -->
         <div class="flex justify-center items-center gap-4 flex-wrap">
            @isset(get_option('header_footer',true,true)->footer_button_image)
            <a href="{{ get_option('header_footer',true,true)->footer->right_image_link ?? '#' }}" class="hover:scale-105 transition-transform duration-200">
               <img src="{{ asset(get_option('header_footer',true,true)->footer_button_image) }}" 
                    alt="Get it on Google Play" 
                    class="h-12 w-auto object-contain" />
            </a>
            @endisset
            
            @isset(get_option('header_footer',true,true)->footer_left_button_image)
            <a href="{{ get_option('header_footer',true,true)->footer->right_image_link ?? '#' }}" class="hover:scale-105 transition-transform duration-200">
               <img src="{{ asset(get_option('header_footer',true,true)->footer_left_button_image) }}" 
                    alt="Download on App Store" 
                    class="h-12 w-auto object-contain" />
            </a>
            @endisset
         </div>
      </div>

      <!-- Divider -->
      <div class="h-px bg-gray-800 my-10"></div>

      <!-- Bottom Footer Section -->
      <div class="flex flex-col md:flex-row justify-between items-center gap-8">
         
         <!-- Logo & Copyright -->
         <div class="flex flex-col items-center md:items-start gap-4">
            <a href="{{ url('/') }}">
               <img src="{{ asset(get_option('primary_data',true)->footer_logo ?? '') }}" 
                    alt="Logo" 
                    class="h-9 w-auto object-contain brightness-0 invert" />
            </a>
            <p class="text-sm text-gray-500">
               &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
         </div>

         <!-- Bottom Menu Links -->
         <div class="hidden sm:flex items-center gap-6 flex-wrap justify-center">
            <a href="{{ url('/features') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">
               {{ __('Features') }}
            </a>
            <a href="{{ url('/about') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">
               {{ __('About Us') }}
            </a>
            <a href="{{ url('/pricing') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">
               {{ __('Pricing') }}
            </a>
            <a href="{{ url('/faq') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">
               {{ __('FAQ') }}
            </a>
            <a href="{{ url('/blogs') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">
               {{ __('News') }}
            </a>
         </div>

         <!-- Language Selector -->
         <div>
            @php
            $local = Session::get('locale');
            @endphp
            <div class="flex items-center gap-2">
               <span class="material-icons text-gray-500 text-sm">language</span>
               <select class="select select-bordered select-xs text-gray-300 bg-gray-800 border-gray-700 focus:outline-none focus:ring-1 focus:ring-primary language-switch">
                  @foreach(get_option('languages',true) ?? [] as $key => $lang)
                  <option value="{{ $key }}" @if($local == $key) selected="" @endif>{{ $lang }}</option>
                  @endforeach
               </select>
            </div>
         </div>

      </div>

   </div>
</footer>
