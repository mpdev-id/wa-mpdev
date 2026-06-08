<!-- Modern Testimonials Section -->
<div class="py-24 bg-gradient-to-b from-white to-base-200 relative overflow-hidden">
   <!-- Ambient backdrops -->
   <div class="absolute top-1/2 left-[-10%] w-96 h-96 bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>

   <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16">
         <span class="text-xs font-extrabold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1.5 rounded-lg mb-4 inline-block">
            {{ __('Customer Reviews') }}
         </span>
         <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4 tracking-tight">
            {{ $home->testimonial->title ?? '' }}
         </h2>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center max-w-6xl mx-auto">
         
         <!-- Left Side: Cover Image (col-span-5) -->
         <div class="lg:col-span-5 relative group">
            <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-tr from-primary/10 to-secondary/10 blur-xl opacity-75 pointer-events-none"></div>
            <div class="relative rounded-[2rem] overflow-hidden shadow-2xl border-4 border-white bg-white p-2">
               <img src="{{ asset($home->testimonial_cover ?? '') }}" 
                    alt="Customer Testimonials" 
                    class="w-full h-auto rounded-[1.5rem] object-cover group-hover:scale-[1.01] transition-transform duration-500" />
            </div>
         </div>

         <!-- Right Side: Testimonials List (col-span-7) -->
         <div class="lg:col-span-7 flex flex-col gap-6">
            @foreach($testimonials as $testimonial)
               <div class="bg-white/90 backdrop-blur border border-gray-150 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex gap-4 items-start relative group">
                  
                  <!-- Quote mark decorator -->
                  <span class="absolute top-4 right-6 text-gray-100 text-6xl font-serif select-none pointer-events-none group-hover:text-primary/5 transition-colors duration-300">”</span>

                  <!-- Author Image with Gradient Ring -->
                  <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-white ring-2 ring-primary/20 flex-none bg-gray-50 group-hover:ring-primary/40 transition-all">
                     <img src="{{ asset($testimonial->preview->value ?? '') }}" 
                          alt="{{ $testimonial->title }}" 
                          class="w-full h-full object-cover" />
                  </div>

                  <!-- Testimonial Content -->
                  <div class="flex-grow">
                     
                     <!-- Star Rating -->
                     <div class="flex items-center gap-0.5 text-amber-400 mb-2">
                        <span class="material-icons text-base">star</span>
                        <span class="material-icons text-base">star</span>
                        <span class="material-icons text-base">star</span>
                        <span class="material-icons text-base">star</span>
                        <span class="material-icons text-base">star</span>
                     </div>

                     <p class="text-gray-650 text-sm sm:text-base italic leading-relaxed mb-3">
                        "{{ Str::limit($testimonial->excerpt->value ?? '', 160) }}"
                      </p>
                     
                     <h4 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        {{ $testimonial->title ?? '' }}
                        @if(!empty($testimonial->slug))
                        <span class="text-xs font-semibold text-primary bg-primary/10 px-2.5 py-0.5 rounded-full">
                           {{ $testimonial->slug }}
                        </span>
                        @endif
                     </h4>
                  </div>

               </div>
            @endforeach
         </div>

      </div>

   </div>
</div>
