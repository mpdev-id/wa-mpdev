<!-- Modern Why Choose Us Section -->
<div class="py-24 bg-slate-950 text-white relative overflow-hidden">
   
   <!-- Background decorative grid/lines or blur -->
   <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808008_1px,transparent_1px),linear-gradient(to_bottom,#80808008_1px,transparent_1px)] bg-[size:24px_24px] pointer-events-none"></div>
   <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-primary/10 rounded-full blur-[120px] pointer-events-none"></div>

   <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-12">
         <span class="text-xs font-extrabold uppercase tracking-widest text-primary bg-primary/15 px-3 py-1.5 rounded-lg mb-4 inline-block">
            {{ __('Trust & Reliability') }}
         </span>
         <h2 class="text-3xl sm:text-4xl font-black text-white mb-4 tracking-tight">
            {{ get_option('why-choose',true, true)->title ?? '' }}
         </h2>
         <p class="text-lg text-slate-400 font-medium leading-relaxed">
            {{ get_option('why-choose',true, true)->subtitle ?? '' }}
         </p>
      </div>

      <!-- Store Buttons -->
      <div class="flex justify-center items-center gap-4 flex-wrap">
         @if(!empty(get_option('why-choose',true, true)->left_button_image))
         <a href="{{ url(get_option('why-choose',true, true)->left_button_link ?? '') }}" class="hover:scale-105 active:scale-95 transition-all duration-300 shadow-lg hover:shadow-primary/5 rounded-xl overflow-hidden">
            <img src="{{ asset(get_option('why-choose',true, true)->left_button_image) }}" 
                 alt="Download" 
                 class="h-11 w-auto object-contain" />
         </a>
         @endif

         @if(!empty(get_option('why-choose',true, true)->right_button_image))
         <a href="{{ url(get_option('why-choose',true, true)->right_button_link ?? '') }}" class="hover:scale-105 active:scale-95 transition-all duration-300 shadow-lg hover:shadow-primary/5 rounded-xl overflow-hidden">
            <img src="{{ asset(get_option('why-choose',true, true)->right_button_image) }}" 
                 alt="Download" 
                 class="h-11 w-auto object-contain" />
         </a>
         @endif
      </div>

   </div>
</div>

<!-- Modern Counter Stats Section -->
<div class="py-12 bg-base-100 border-b border-gray-150 relative">
   <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto -mt-20 relative z-20">
         
         <!-- Left Block -->
         <div class="bg-white/95 backdrop-blur border border-gray-150 rounded-[2rem] p-6 shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex items-center gap-5 group">
            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center flex-none group-hover:scale-110 transition-transform">
               <img src="{{ asset(get_option('why-choose',true, true)->left_block_image ?? '') }}" 
                    alt="Stat Icon" 
                    class="w-10 h-10 object-contain" />
            </div>
            <div>
               <span class="text-3xl font-black text-primary block tracking-tight">
                  {{ get_option('why-choose',true, true)->left_block_value ?? '' }}
               </span>
               <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mt-0.5">
                  {{ get_option('why-choose',true, true)->left_block_title ?? '' }}
               </span>
            </div>
         </div>

         <!-- Center Block -->
         <div class="bg-white/95 backdrop-blur border border-gray-150 rounded-[2rem] p-6 shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex items-center gap-5 group">
            <div class="w-16 h-16 bg-secondary/10 rounded-2xl flex items-center justify-center flex-none group-hover:scale-110 transition-transform">
               <img src="{{ asset(get_option('why-choose',true, true)->center_block_image ?? '') }}" 
                    alt="Stat Icon" 
                    class="w-10 h-10 object-contain" />
            </div>
            <div>
               <span class="text-3xl font-black text-secondary block tracking-tight">
                  {{ get_option('why-choose',true, true)->center_block_value ?? '' }}
               </span>
               <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mt-0.5">
                  {{ get_option('why-choose',true, true)->center_block_title ?? '' }}
               </span>
            </div>
         </div>

         <!-- Right Block -->
         <div class="bg-white/95 backdrop-blur border border-gray-150 rounded-[2rem] p-6 shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex items-center gap-5 group">
            <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center flex-none group-hover:scale-110 transition-transform">
               <img src="{{ asset(get_option('why-choose',true, true)->right_block_image ?? '') }}" 
                    alt="Stat Icon" 
                    class="w-10 h-10 object-contain" />
            </div>
            <div>
               <span class="text-3xl font-black text-accent block tracking-tight">
                  {{ get_option('why-choose',true, true)->right_block_value ?? '' }}
               </span>
               <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mt-0.5">
                  {{ get_option('why-choose',true, true)->right_block_title ?? '' }}
               </span>
            </div>
         </div>

      </div>

   </div>
</div>
