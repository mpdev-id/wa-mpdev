<!-- Modern Top FAQ Section -->
<div class="py-24 bg-gradient-to-b from-base-200 to-white relative overflow-hidden">
   
   <!-- Background Blur Decorator -->
   <div class="absolute top-1/2 left-[-10%] w-96 h-96 bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>

   <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center max-w-6xl mx-auto">
         
         <!-- Left Side: Content & Accordion (col-span-7) -->
         <div class="lg:col-span-7 flex flex-col items-start text-left">
            
            <!-- Section Header -->
            <div class="mb-10 w-full">
               <span class="text-xs font-extrabold uppercase tracking-widest text-secondary bg-secondary/10 px-3 py-1.5 rounded-lg mb-4 inline-block">
                  {{ __('Quick Help') }}
               </span>
               <h3 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4 leading-tight tracking-tight">
                  {{ $home->top_faq->title ?? '' }}
               </h3>
               @if(!empty($home->top_faq->description))
               <p class="text-gray-500 text-lg leading-relaxed">
                  {{ $home->top_faq->description }}
               </p>
               @endif
            </div>

            <!-- Accordion -->
            <div class="flex flex-col gap-4 w-full">
               @php $topIndex = 0; @endphp
               @foreach($faqs as $faqKey => $topfaq)
                  @if($topfaq->slug == 'top')
                     <div class="collapse collapse-arrow bg-white border border-gray-150 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 group">
                        <input type="radio" name="top-faq-accordion" id="top-faq-{{ $faqKey }}" {{ $topIndex == 0 ? 'checked' : '' }} class="peer" /> 
                        
                        <!-- Accordion Title -->
                        <div class="collapse-title text-base sm:text-lg font-bold text-gray-800 py-5 px-6 pr-12 peer-checked:text-primary transition-colors">
                           {{ $topfaq->title ?? '' }}
                        </div>
                        
                        <!-- Accordion Content -->
                        <div class="collapse-content px-6 pb-6 text-sm sm:text-base text-gray-600 leading-relaxed border-t border-transparent peer-checked:border-gray-50 pt-4"> 
                           <p>{{ $topfaq->excerpt->value ?? '' }}</p>
                        </div>
                     </div>
                     @php $topIndex++; @endphp
                  @endif
               @endforeach
            </div>
         </div>

         <!-- Right Side: Illustration Image (col-span-5) -->
         <div class="lg:col-span-5 flex justify-center items-center">
            <div class="relative max-w-sm w-full group">
               <!-- Gradient background glow behind illustration -->
               <div class="absolute inset-0 bg-gradient-to-tr from-primary/20 to-secondary/15 rounded-full filter blur-[80px] opacity-75 group-hover:scale-110 transition-transform duration-500"></div>
               <img src="https://ik.imagekit.io/gupshup/revamp/assets/v3/images/home/v5-casual-life.png?ik-sdk-version=react-1.1.1" 
                    alt="Illustration" 
                    class="w-full h-auto object-contain relative z-10 hover:scale-105 hover:rotate-1 transition-all duration-500" />
            </div>
         </div>

      </div>
   </div>
</div>
