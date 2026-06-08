<div id="faq" class="py-24 bg-base-100 border-t border-gray-150 relative overflow-hidden">
   
   <!-- Background Glow -->
   <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>

   <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16">
         <span class="text-xs font-extrabold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1.5 rounded-lg mb-4 inline-block">
            {{ __('Got Questions?') }}
         </span>
         <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4 tracking-tight">
            {{ __('Frequently asked questions') }} 📣
         </h2>
         <p class="text-gray-500 text-lg">
            {{ __('Find answers to common questions about our WhatsApp API services.') }}
         </p>
      </div>

      <!-- FAQ Accordion (DaisyUI Pure CSS Collapse) -->
      <div class="max-w-4xl mx-auto flex flex-col gap-4">
         @php $i = 0; @endphp
         @foreach($faqs as $key => $faq)
            @if($faq->slug != 'top')
               <div class="collapse collapse-arrow bg-white border border-gray-150 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300">
                  <!-- Radio type makes only one accordion open at a time -->
                  <input type="radio" name="faq-accordion" id="faq-item-{{ $key }}" {{ $i == 0 ? 'checked' : '' }} class="peer" /> 
                  
                  <!-- Accordion Title -->
                  <div class="collapse-title text-base sm:text-lg font-bold text-gray-800 py-5 px-6 pr-12 peer-checked:text-primary transition-colors">
                     {{ $faq->title }}
                  </div>
                  
                  <!-- Accordion Content -->
                  <div class="collapse-content px-6 pb-6 text-sm sm:text-base text-gray-650 leading-relaxed border-t border-transparent peer-checked:border-gray-50 pt-4"> 
                     <p>{{ $faq->excerpt->value ?? '' }}</p>
                  </div>
               </div>
               @php $i++; @endphp
            @endif
         @endforeach
      </div>

   </div>
</div>
