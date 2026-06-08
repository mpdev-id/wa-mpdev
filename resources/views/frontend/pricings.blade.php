<div id="price" class="py-24 bg-gradient-to-b from-white to-base-200 border-t border-gray-150 relative overflow-hidden">
   <!-- Ambient blur -->
   <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>

   <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16">
         <span class="text-xs font-extrabold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1.5 rounded-lg mb-4 inline-block">
            {{ __('Flexible Plans') }}
         </span>
         <h3 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4 tracking-tight">
            {{ __('Pricing to suite all size of business') }}
         </h3>
         <p class="text-gray-505 text-lg font-medium">
            {{ __('*We help companies of all sizes succeed') }}
         </p>
      </div>

      <!-- Pricing Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto items-stretch">
         @foreach($plans ?? [] as $plan)
            @php
               $isBest = $plan->is_recommended == 1;
            @endphp
            
            <div class="bg-white border rounded-[2rem] p-8 transition-all duration-300 flex flex-col justify-between relative
               {{ $isBest 
                  ? 'border-primary ring-4 ring-primary/5 shadow-2xl shadow-primary/15 scale-105 z-10' 
                  : 'border-gray-180 shadow-sm hover:shadow-xl hover:-translate-y-1.5' }}">
               
               <!-- Most Popular Badge -->
               @if($isBest)
                  <span class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-primary to-accent text-white text-xs font-black px-5 py-2 rounded-full uppercase tracking-widest shadow-lg">
                     {{ __('Most Popular') }}
                  </span>
               @endif

               <div>
                  <!-- Plan Header -->
                  <div class="flex justify-between items-center mb-6">
                     <span class="text-lg font-bold text-gray-900">{{ $plan->title }}</span>
                     <span class="w-10 h-10 rounded-xl flex items-center justify-center bg-primary/10 text-primary">
                        <span class="material-icons text-lg">payments</span>
                     </span>
                  </div>

                  <!-- Price -->
                  <div class="mb-8 flex items-baseline">
                     <span class="text-4xl font-extrabold text-gray-900 tracking-tight">
                        {{ amount_format($plan->price,'icon') }}
                     </span>
                     <span class="text-gray-500 ml-2 text-sm font-semibold uppercase tracking-wider">
                        {{ $plan->days == 30 ? '/month' : '/year' }}
                     </span>
                  </div>

                  <!-- Divider -->
                  <div class="h-px bg-gray-100 my-6"></div>

                  <!-- Features List -->
                  <ul class="space-y-4 mb-8">
                     @foreach($plan->data ?? [] as $key => $data)
                        @php
                           $pData = planData($key, $data);
                           $isDisabled = ($pData['value'] == false && $pData['is_bool'] == true);
                        @endphp
                        
                        @if(!$isDisabled)
                           <li class="flex items-start gap-3 text-sm text-gray-650">
                              <span class="w-5 h-5 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 flex-none mt-0.5">
                                 <span class="material-icons text-xs font-bold">check</span>
                              </span>
                              <span class="font-medium text-left">{{ ucfirst(str_replace('_',' ',$pData['title'])) }}</span>
                           </li>
                        @endif
                     @endforeach
                  </ul>
               </div>

               <!-- CTA Button -->
               <div class="pt-6 border-t border-gray-100/60">
                  <a class="btn w-full rounded-xl font-bold transition-all duration-300 py-3 flex items-center justify-center gap-2
                     {{ $isBest 
                        ? 'btn-primary text-white shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.98]' 
                        : 'btn-outline border-gray-300 hover:bg-gray-50 text-gray-700 active:scale-[0.98]' }}" 
                     href="{{ url('/register',$plan->id) }}">
                     <span>{{ $plan->is_trial == 1 ? __('Free '.$plan->trial_days.' days trial') : __('Sign Up Now') }}</span>
                     <span class="material-icons text-sm">arrow_forward</span>
                  </a>
               </div>

            </div>
         @endforeach
      </div>

   </div>
</div>