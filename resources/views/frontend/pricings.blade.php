<div id="price" class="py-20 bg-gradient-to-b from-white to-slate-50 border-t border-gray-100 relative overflow-hidden">
   
   <!-- Ambient glows for premium look -->
   <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-primary/5 rounded-full blur-[120px] pointer-events-none"></div>

   <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      
      <!-- Compact Section Header -->
      <div class="text-center max-w-2xl mx-auto mb-14">
         <span class="text-[10px] font-extrabold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1.5 rounded-full mb-3 inline-block">
            {{ __('Flexible Plans') }}
         </span>
         <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mb-3 tracking-tight">
            {{ __('Pricing to suite all size of business') }}
         </h3>
         <p class="text-slate-500 text-sm font-medium">
            {{ __('*We help companies of all sizes succeed') }}
         </p>
      </div>

      <!-- Pricing Cards Grid: Compact, Slim & Smooth -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto items-stretch">
         @foreach($plans ?? [] as $plan)
            @php
               $isBest = $plan->is_recommended == 1;
            @endphp
            
            <div class="bg-white border rounded-3xl p-6 transition-all duration-500 flex flex-col justify-between relative group hover:-translate-y-2
               {{ $isBest 
                  ? 'border-primary shadow-[0_15px_40px_rgba(16,185,129,0.12)] scale-[1.02] z-10 hover:shadow-[0_25px_50px_rgba(16,185,129,0.22)]' 
                  : 'border-slate-100 shadow-sm hover:shadow-xl hover:border-primary/30' }}">
               
               <!-- Most Popular Badge -->
               @if($isBest)
                  <span class="absolute -top-3.5 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-primary to-emerald-400 text-white text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-md">
                     {{ __('Most Popular') }}
                  </span>
               @endif

               <div>
                  <!-- Plan Header (Compact) -->
                  <div class="flex justify-between items-center mb-4">
                     <span class="text-base font-extrabold text-slate-800 tracking-tight">{{ $plan->title }}</span>
                     <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-primary/10 text-primary group-hover:scale-110 transition-transform duration-300">
                        <span class="material-icons text-base">payments</span>
                     </span>
                  </div>

                  <!-- Price Section -->
                  <div class="mb-5 flex items-baseline">
                     <span class="text-3xl font-black text-slate-900 tracking-tight">
                        {{ amount_format($plan->price,'icon') }}
                     </span>
                     <span class="text-slate-400 ml-1.5 text-xs font-semibold uppercase tracking-wider">
                        {{ $plan->days == 30 ? '/ Bulan' : '/ Tahun' }}
                     </span>
                  </div>

                  <!-- Thin Divider -->
                  <div class="h-[1px] bg-slate-100 mb-5"></div>

                  <!-- Features List (Compact spacing & smaller text) -->
                  <ul class="space-y-2.5 mb-6">
                     @foreach($plan->data ?? [] as $key => $data)
                        @php
                           $pData = planData($key, $data);
                           $isDisabled = ($pData['value'] == false && $pData['is_bool'] == true);
                        @endphp
                        
                        @if(!$isDisabled)
                           <li class="flex items-center gap-2.5 text-xs text-slate-600">
                              <span class="w-4.5 h-4.5 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 flex-none">
                                 <span class="material-icons text-[10px] font-bold">check</span>
                              </span>
                              <span class="font-medium text-slate-700 text-left">{{ ucfirst(str_replace('_',' ',$pData['title'])) }}</span>
                           </li>
                        @endif
                     @endforeach
                  </ul>
               </div>

               <!-- Compact CTA Button -->
               <div class="pt-5 border-t border-slate-50">
                  <a href="{{ url('/register',$plan->id) }}" 
                     class="w-full rounded-xl font-bold text-xs transition-all duration-300 py-3 flex items-center justify-center gap-1.5
                        {{ $isBest 
                           ? 'bg-primary hover:bg-primary-variant text-white shadow-lg shadow-primary/20 hover:shadow-xl hover:scale-[1.01] active:scale-[0.99]' 
                           : 'border border-slate-200 hover:border-primary hover:bg-primary/5 text-slate-700 hover:text-primary active:scale-[0.99]' }}">
                     <span>{{ $plan->is_trial == 1 ? __('Free '.$plan->trial_days.' days trial') : __('Sign Up Now') }}</span>
                     <span class="material-icons text-sm">arrow_forward</span>
                  </a>
               </div>

            </div>
         @endforeach
      </div>

   </div>
</div>