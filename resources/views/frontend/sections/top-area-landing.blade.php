<!-- Modern About Section -->
<div class="py-24 bg-white relative overflow-hidden">
   <!-- Decorative Background Mesh -->
   <div class="absolute inset-0 bg-gradient-to-tr from-base-200/50 via-transparent to-transparent pointer-events-none"></div>

   <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center max-w-6xl mx-auto">
         
         <!-- Left Side: Cover Image -->
         <div class="relative group">
            <!-- Ambient shadow glow behind the mockup -->
            <div class="absolute -inset-4 rounded-[2.5rem] bg-gradient-to-tr from-primary/15 to-secondary/15 blur-2xl opacity-60 transition-opacity duration-500 group-hover:opacity-90"></div>
            
            <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white bg-white/50 p-2 backdrop-blur-sm">
               <img src="{{ asset($home->about_cover ?? '') }}" 
                    alt="Info Dashboard" 
                    class="w-full h-auto rounded-[2rem] object-cover group-hover:scale-[1.01] transition-transform duration-500" />
            </div>
         </div>

         <!-- Right Side: Content -->
         <div class="flex flex-col gap-6 items-start text-left">
            
            <!-- Section Tag -->
            <span class="text-xs font-extrabold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1.5 rounded-lg">
               {{ __('Explore Dashboard') }}
            </span>

            <h3 class="text-3xl sm:text-4xl font-black text-gray-900 leading-tight tracking-tight">
               {{ $home->about->title ?? '' }}
            </h3>
            
            <p class="text-gray-650 text-lg leading-relaxed">
               {{ $home->about->description ?? '' }}
            </p>

            <!-- Download Action Area -->
            @if(!empty($home->about->action_area_title))
            <div class="mt-4 pt-6 border-t border-gray-150 w-full">
               <h4 class="text-base font-bold text-gray-900 mb-4 tracking-wide uppercase">{{ $home->about->action_area_title }}</h4>
               <div class="flex items-center gap-4 flex-wrap">
                  @isset(get_option('header_footer',true,true)->footer_button_image)
                  <a href="{{ get_option('header_footer',true,true)->footer->right_image_link ?? '' }}" class="hover:scale-105 active:scale-95 transition-all duration-300 shadow-md hover:shadow-xl rounded-xl overflow-hidden">
                     <img src="{{ asset(get_option('header_footer',true,true)->footer_button_image) }}" 
                          alt="Get it on Google Play" 
                          class="h-11 w-auto object-contain" />
                  </a>
                  @endisset

                  @isset(get_option('header_footer',true,true)->footer_left_button_image)
                  <a href="{{ get_option('header_footer',true,true)->footer->right_image_link ?? '' }}" class="hover:scale-105 active:scale-95 transition-all duration-300 shadow-md hover:shadow-xl rounded-xl overflow-hidden">
                     <img src="{{ asset(get_option('header_footer',true,true)->footer_left_button_image) }}" 
                          alt="Download on App Store" 
                          class="h-11 w-auto object-contain" />
                  </a>
                  @endisset
               </div>
            </div>
            @endif
         </div>

      </div>
   </div>
</div>
