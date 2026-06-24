@extends('frontend.layouts.landing')

@section('content')
@include('frontend.layouts.header-landing')

<main class="pt-20 overflow-hidden bg-slate-50">
   <!-- Hero Section with Interactive Code Sandbox & Phone Simulator -->
   <section class="relative min-h-screen flex items-center justify-center py-20 bg-gradient-to-br from-purple-100 via-violet-50 to-white text-slate-800 overflow-hidden">
      <!-- Background Ambient Glows -->
      <div class="absolute inset-0 overflow-hidden pointer-events-none">
         <div class="absolute -top-40 -left-40 w-[600px] h-[600px] bg-purple-400/20 rounded-full blur-[150px] animate-pulse"></div>
         <div class="absolute -bottom-40 -right-40 w-[600px] h-[600px] bg-primary/20 rounded-full blur-[150px] animate-pulse" style="animation-delay: 3s"></div>
         <div class="absolute inset-0 bg-[linear-gradient(to_right,#6b21a805_1px,transparent_1px),linear-gradient(to_bottom,#6b21a805_1px,transparent_1px)] bg-[size:40px_40px]"></div>
      </div>

      <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full max-w-7xl">
         <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            
            <!-- Left Column: Copy & Value Proposition -->
            <div class="lg:col-span-6 flex flex-col items-start text-left space-y-8 animate-fade-in">
               <!-- Premium Tagline Badge -->
               <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-purple-150 border border-purple-200 text-purple-700 text-xs font-bold uppercase tracking-wider">
                  <span class="flex h-2.5 w-2.5 relative">
                     <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                     <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-purple-600"></span>
                  </span>
                  {{ __('Theme 3 • Ultimate WhatsApp API Gateway') }}
               </div>

               <!-- Big Title -->
               <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-slate-900 leading-tight tracking-tight">
                  {!! filterXss($heading) !!}
               </h1>

               <!-- Subheading description -->
               <p class="text-slate-600 text-lg leading-relaxed max-w-xl">
                  {{ __('Elevate your business communication with our lightning-fast, secure, and developer-friendly WhatsApp API integration. Automate workflows in seconds.') }}
               </p>

               <!-- Action Buttons -->
               <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                  @if(!Auth::check())
                     <a href="{{ route('login') }}" 
                        class="btn btn-primary btn-lg rounded-2xl gap-3 shadow-xl shadow-primary/30 hover:shadow-2xl hover:scale-[1.03] active:scale-[0.97] transition-all duration-300 px-8 text-white">
                        <span class="material-icons">login</span>
                        {{ __('Login') }}
                     </a>
                     <a href="{{ route('register') }}" 
                        class="btn btn-outline btn-lg border-purple-300 hover:border-purple-600 text-purple-700 hover:bg-purple-50 rounded-2xl gap-3 hover:scale-[1.03] active:scale-[0.97] transition-all duration-300 px-8">
                        <span class="material-icons">rocket_launch</span>
                        {{ __('Try Me Free') }}
                     </a>
                  @else
                     <a href="{{ url('/login') }}" 
                        class="btn btn-primary btn-lg rounded-2xl gap-3 shadow-xl shadow-primary/30 hover:shadow-2xl hover:scale-[1.03] active:scale-[0.97] transition-all duration-300 px-8 text-white">
                        <span class="material-icons">dashboard</span>
                        {{ __('Dashboard') }}
                     </a>
                  @endif
               </div>

               <!-- Micro trust signals -->
               <div class="pt-6 grid grid-cols-2 gap-4 text-sm text-slate-500 font-semibold w-full border-t border-purple-100">
                  <div class="flex items-center gap-2">
                     <span class="material-icons text-purple-600">offline_bolt</span>
                     {{ __('99.9% Message Delivery') }}
                  </div>
                  <div class="flex items-center gap-2">
                     <span class="material-icons text-purple-600">code</span>
                     {{ __('Multi-Language SDK support') }}
                  </div>
               </div>
            </div>

            <!-- Right Column: Interactive Code Sandbox & Message Stream Simulator -->
            <div class="lg:col-span-6 flex flex-col justify-center items-center">
               <div class="w-full max-w-xl bg-white border border-purple-100 rounded-[2rem] shadow-2xl overflow-hidden p-6 relative group">
                  
                  <!-- Glowing ambient border hover -->
                  <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent rounded-[2rem] pointer-events-none"></div>
                  
                  <!-- Custom tabs for simulator code -->
                  <div class="flex items-center justify-between border-b border-purple-100 pb-4 mb-4">
                     <div class="flex gap-2">
                        <span class="w-3.5 h-3.5 rounded-full bg-red-400"></span>
                        <span class="w-3.5 h-3.5 rounded-full bg-yellow-400"></span>
                        <span class="w-3.5 h-3.5 rounded-full bg-green-400"></span>
                     </div>
                     
                     <!-- Interactive Language Tabs -->
                     <div class="flex gap-1 bg-purple-50 p-1 rounded-xl border border-purple-100/50">
                        <button onclick="switchTab('curl')" id="tab-curl" class="tab-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-purple-600 text-white shadow">cURL</button>
                        <button onclick="switchTab('node')" id="tab-node" class="tab-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-purple-600 hover:text-purple-800">NodeJS</button>
                        <button onclick="switchTab('php')" id="tab-php" class="tab-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-purple-600 hover:text-purple-800">PHP</button>
                     </div>
                  </div>

                  <!-- Code Content Display -->
                  <div class="relative min-h-[160px] bg-purple-950 p-4 rounded-2xl border border-purple-900 font-mono text-xs text-purple-100 leading-relaxed overflow-x-auto">
                     <!-- cURL Snippet -->
                     <pre id="code-curl" class="code-block"><code>curl -X POST "{{ url('/api/v1/send') }}" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "to": "628123456789",
    "message": "Hello from Whasapi! 🚀"
  }'</code></pre>

                     <!-- Node Snippet -->
                     <pre id="code-node" class="code-block hidden"><code>const axios = require('axios');

axios.post('{{ url('/api/v1/send') }}', {
  to: '628123456789',
  message: 'Hello from Whasapi! 🚀'
}, {
  headers: { 'Authorization': 'Bearer YOUR_API_KEY' }
})
.then(res => console.log('Message Sent!'))
.catch(err => console.error(err));</code></pre>

                     <!-- PHP Snippet -->
                     <pre id="code-php" class="code-block hidden"><code>&lt;?php

use Illuminate\Support\Facades\Http;

$response = Http::withHeaders([
    'Authorization' => 'Bearer YOUR_API_KEY'
])->post('{{ url('/api/v1/send') }}', [
    'to' => '628123456789',
    'message' => 'Hello from Whasapi! 🚀'
]);

echo $response->json()['message'];</code></pre>
                  </div>

                  <!-- Run Code Button -->
                  <div class="mt-4 flex items-center justify-between">
                     <span class="text-[10px] uppercase font-bold tracking-widest text-slate-500">{{ __('Response code: 200 OK') }}</span>
                     <button onclick="simulateMessageSend()" class="btn btn-primary btn-sm rounded-xl px-5 gap-2 text-white font-bold transition-transform active:scale-95">
                        <span class="material-icons text-sm">play_arrow</span>
                        {{ __('Simulate Send') }}
                     </button>
                  </div>

                  <!-- Simulated WhatsApp Chat Window on bottom -->
                  <div class="mt-6 border-t border-slate-850 pt-5">
                     <div class="bg-[#efeae2] rounded-2xl overflow-hidden shadow-inner border border-slate-800/20 max-w-sm mx-auto">
                        <!-- Chat Header -->
                        <div class="bg-[#008069] text-white px-4 py-2.5 flex items-center gap-3">
                           <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-[#008069] font-bold text-sm">W</div>
                           <div>
                              <div class="text-xs font-bold">Whasapi Gateway</div>
                              <div class="text-[9px] opacity-80">Online</div>
                           </div>
                        </div>

                        <!-- Chat Messages Container -->
                        <div id="chat-body" class="p-3 space-y-2 min-h-[120px] max-h-[160px] overflow-y-auto flex flex-col justify-end">
                           <div class="bg-white text-slate-800 p-2 rounded-lg text-xs max-w-[80%] shadow-sm self-start leading-tight">
                              {{ __('Hey! Use the button above to simulate a REST API message dispatch.') }}
                           </div>
                        </div>
                     </div>
                  </div>

               </div>
            </div>

         </div>
      </div>
   </section>

   <!-- Live Try It Out Widget -->
   <section class="py-24 bg-white relative">
      <div class="container mx-auto px-4 max-w-5xl">
         <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-extrabold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1.5 rounded-lg mb-4 inline-block">
               {{ __('Try it Now') }}
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-4">
               {{ __('Format your WhatsApp Message') }}
            </h2>
            <p class="text-slate-500">
               {{ __('See how message templates and custom formats instantly output to WhatsApp nodes.') }}
            </p>
         </div>

         <!-- Try Out Card Interface -->
         <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-stretch bg-slate-50/50 border border-slate-100 rounded-[2.5rem] p-8 shadow-xl">
            <!-- Left panel: input forms -->
            <div class="flex flex-col gap-5 justify-between">
               <div>
                  <h3 class="text-lg font-black text-slate-800 mb-4">{{ __('Message Editor') }}</h3>
                  
                  <div class="form-group mb-4">
                     <label class="label text-xs font-bold text-slate-500">{{ __('Sender Alias') }}</label>
                     <input type="text" id="demo-sender" value="Johnny Cash" class="input input-bordered w-full rounded-2xl h-12 bg-white" placeholder="Masukkan nama...">
                  </div>

                  <div class="form-group">
                     <label class="label text-xs font-bold text-slate-500">{{ __('Message Content') }}</label>
                     <textarea id="demo-text" class="textarea textarea-bordered w-full rounded-2xl h-32 bg-white leading-relaxed" placeholder="Tulis pesan Anda disini...">*Halo customer setia!* 🌟 

Pemesanan dengan nomor invoice *#INV-8910* telah berhasil diproses dan akan segera dikirim.

Terima kasih atas kepercayaan Anda!</textarea>
                  </div>
               </div>

               <button onclick="updateDemoChat()" class="btn btn-primary w-full h-12 rounded-2xl font-bold gap-2 text-white shadow-lg shadow-primary/20">
                  <span class="material-icons">send</span>
                  {{ __('Update Chat Preview') }}
               </button>
            </div>

            <!-- Right panel: simulated smart phone -->
            <div class="flex justify-center items-center">
               <div class="w-full max-w-[320px] bg-slate-900 rounded-[2.5rem] p-3.5 shadow-2xl border-4 border-slate-950 relative">
                  <!-- Phone Notch -->
                  <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-28 h-5 bg-slate-950 rounded-b-xl z-20"></div>

                  <!-- Inside Screen Container -->
                  <div class="bg-[#efeae2] rounded-[1.8rem] overflow-hidden w-full min-h-[380px] relative flex flex-col justify-between">
                     <!-- Whatsapp Top header bar -->
                     <div class="bg-[#008069] text-white px-4 pt-6 pb-2.5 flex items-center justify-between z-10 shadow-md">
                        <div class="flex items-center gap-2.5">
                           <span class="material-icons text-sm">arrow_back</span>
                           <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-[#008069] font-bold text-xs">C</div>
                           <div>
                              <div id="phone-sender" class="text-xs font-bold leading-none">Johnny Cash</div>
                              <span class="text-[8px] opacity-75">Online</span>
                           </div>
                        </div>
                        <div class="flex items-center gap-3">
                           <span class="material-icons text-base">videocam</span>
                           <span class="material-icons text-base">call</span>
                        </div>
                     </div>

                     <!-- Message Streams Area -->
                     <div class="p-3 flex-grow flex flex-col justify-end space-y-2 overflow-y-auto max-h-[280px]">
                        <div id="phone-msg" class="bg-[#d9fdd3] text-slate-800 p-2.5 rounded-lg text-xs max-w-[85%] shadow-sm self-end leading-relaxed text-left whitespace-pre-line relative">
                           <!-- Text body will output here dynamically -->
                        </div>
                     </div>

                     <!-- Input chat footer simulation -->
                     <div class="p-2 bg-[#f0f2f5] flex items-center gap-2 border-t border-slate-200">
                        <div class="bg-white rounded-full flex-grow h-8 px-3 flex items-center justify-between text-slate-400">
                           <span class="text-[10px]">{{ __('Type message...') }}</span>
                           <span class="material-icons text-sm">sentiment_satisfied</span>
                        </div>
                        <div class="w-8 h-8 bg-[#00a884] text-white rounded-full flex items-center justify-center">
                           <span class="material-icons text-sm">mic</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

      </div>
   </section>

   <!-- Core Features Section -->
   @if($features_area == 'active')
   <section id="features" class="py-24 bg-gradient-to-b from-slate-50 to-white relative">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
         <div class="text-center max-w-3xl mx-auto mb-20">
            <span class="text-xs font-extrabold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1.5 rounded-lg mb-4 inline-block">
               {{ __('Enterprise Features') }}
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-4">
               {{ __('Uncompromising features build for scaling') }}
            </h2>
         </div>

         <!-- Load Custom Features Landing Section grid -->
         @include('frontend.sections.features-landing', ['limit' => 6])
      </div>
   </section>
   @endif

   <!-- Platform Details Section -->
   <section class="py-24 bg-white border-t border-slate-100">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
         <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            
            <!-- Left: Platform Content -->
            <div class="flex flex-col gap-6 items-start text-left">
               <span class="text-xs font-extrabold uppercase tracking-widest text-secondary bg-secondary/10 px-3 py-1.5 rounded-lg">
                  {{ __('Secure & Scalable') }}
               </span>
               <h3 class="text-3xl sm:text-4xl font-black text-slate-900 leading-tight tracking-tight">{{ $home->platform->title ?? '' }}</h3>
               <p class="text-slate-500 text-lg leading-relaxed">{{ $home->platform->description ?? '' }}</p>
               
               <div class="grid grid-cols-2 gap-6 w-full pt-4">
                  <div class="border-l-4 border-primary pl-4">
                     <span class="block text-2xl font-black text-slate-800">50ms</span>
                     <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('Average Latency') }}</span>
                  </div>
                  <div class="border-l-4 border-secondary pl-4">
                     <span class="block text-2xl font-black text-slate-800">99.99%</span>
                     <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('Uptime Guaranteed') }}</span>
                  </div>
               </div>

               <a class="btn btn-primary btn-lg rounded-2xl gap-3 shadow-lg shadow-primary/20 hover:shadow-xl hover:scale-[1.03] active:scale-[0.97] transition-all duration-300 text-white mt-6" href="{{ url($home->platform->button_link ?? '') }}">
                  <span>{{ $home->platform->button_title ?? '' }}</span>
                  <span class="material-icons">arrow_forward</span>
               </a>
            </div>

            <!-- Right: Platform Thumbnail & Floating widgets -->
            <div class="relative group flex justify-center">
               <div class="absolute -inset-4 rounded-3xl bg-gradient-to-tr from-primary/10 to-secondary/10 blur-xl opacity-75 pointer-events-none"></div>
               <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100 bg-white p-2">
                  <img src="{{ asset($home->platform_thumbnail ?? '') }}" alt="Whasapi Platform UI" class="w-full max-w-md h-auto rounded-[2rem] object-cover group-hover:scale-[1.01] transition-transform duration-500">
               </div>
            </div>

         </div>
      </div>
   </section>

   <!-- Integration Partners Section -->
   @if($brand_area == 'active')
   <section class="py-20 bg-slate-50/50 border-t border-b border-slate-100 relative overflow-hidden">
      <div class="container mx-auto px-4 max-w-6xl">
         <h3 class="text-xs font-extrabold text-center text-slate-400 uppercase tracking-widest mb-10">{{ $home->brand->title ?? '' }}</h3>
         <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12">
            @foreach($brands as $brand)
               @if($brand->lang == 'partner')
                  <div class="hover:scale-110 hover:-translate-y-1 transition-all duration-300 w-24 h-12 flex items-center justify-center filter grayscale hover:grayscale-0">
                     <a href="{{ $brand->title }}" target="_blank"><img src="{{ asset($brand->slug) }}" alt="" class="max-w-full max-h-full object-contain"></a>
                  </div>
               @endif
            @endforeach
         </div>
      </div>
   </section>
   @endif

   <!-- Interactive FAQ & Why Choose Us sections -->
   <section class="py-24 bg-white relative">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
         <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            
            <!-- Why Choose Us left columns -->
            <div class="lg:col-span-5 flex flex-col gap-6 text-left">
               <span class="text-xs font-extrabold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1.5 rounded-lg w-max">
                  {{ $home->why_choose->title ?? 'Why Choose Us' }}
               </span>
               <h3 class="text-3xl sm:text-4xl font-black text-slate-900 leading-tight tracking-tight">
                  {{ $home->why_choose->subtitle ?? __('Simple. Reliable. Fast.') }}
               </h3>
               
               <div class="space-y-4 mt-4">
                  <div class="flex items-start gap-3.5">
                     <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 flex-none mt-1">
                        <span class="material-icons text-lg">check_circle</span>
                     </div>
                     <div>
                        <h4 class="text-sm font-bold text-slate-900">{{ __('No Server Maintenance') }}</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ __('We manage all nodes, hosting, and QR codes on our high-performance clusters.') }}</p>
                     </div>
                  </div>

                  <div class="flex items-start gap-3.5">
                     <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 flex-none mt-1">
                        <span class="material-icons text-lg">check_circle</span>
                     </div>
                     <div>
                        <h4 class="text-sm font-bold text-slate-900">{{ __('Instant Dynamic Webhooks') }}</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ __('Receive status callbacks and incoming chat triggers in near real-time.') }}</p>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Direct Support Chat Panel right columns -->
            <div class="lg:col-span-7 flex justify-center">
               <div class="bg-slate-50 border border-slate-100 rounded-[2.5rem] p-8 shadow-xl text-center max-w-md w-full relative overflow-hidden group">
                  <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent rounded-[2.5rem] opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                  
                  <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-6 text-primary group-hover:scale-110 transition-transform">
                     <span class="material-icons text-3xl">mail</span>
                  </div>
                  
                  <h4 class="text-xl font-bold text-slate-900 mb-3">{{ __('Do you have more questions?') }}</h4>
                  <p class="text-slate-500 text-sm mb-6 leading-relaxed">{{ __('Feel free to contact us directly. Our support team is here to help you.') }}</p>
                  <a class="btn btn-primary w-full rounded-2xl text-white font-bold h-12 shadow-md hover:shadow-lg active:scale-95 transition-all" href="{{ url('/contact') }}">{{ __('Shoot a Direct Mail') }}</a>
               </div>
            </div>

         </div>
      </div>
   </section>

   <!-- Pricing Plan Section -->
   <section class="border-t border-slate-100">
      @include('frontend.sections.pricing-landing')
   </section>

   <!-- Testimonials Section -->
   <section class="py-24 bg-slate-50 relative overflow-hidden border-t border-slate-100">
      <div class="container mx-auto px-4 max-w-6xl text-center">
         <span class="text-xs font-extrabold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1.5 rounded-lg mb-4 inline-block">
            {{ __('Testimonials') }}
         </span>
         <h3 class="text-3xl sm:text-4xl font-black text-slate-900 mb-16 tracking-tight">{{ __('What our customers say') }}</h3>
         
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
            @foreach($testimonials as $testimonial)
               <div class="bg-white border border-slate-100 rounded-[2rem] p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group relative text-left">
                  <span class="absolute top-4 right-6 text-slate-100 text-6xl font-serif select-none pointer-events-none group-hover:text-primary/5 transition-colors">”</span>
                  <div>
                     <div class="flex items-center gap-0.5 text-amber-400 mb-3">
                        <span class="material-icons text-base">star</span>
                        <span class="material-icons text-base">star</span>
                        <span class="material-icons text-base">star</span>
                        <span class="material-icons text-base">star</span>
                        <span class="material-icons text-base">star</span>
                     </div>
                     <p class="text-slate-600 text-sm sm:text-base italic leading-relaxed mb-4">"{{ Str::limit($testimonial->excerpt->value ?? '',160) }}"</p>
                  </div>
                  <div class="flex items-center gap-3 pt-4 border-t border-slate-100 mt-4">
                     <div class="w-11 h-11 rounded-full overflow-hidden border border-slate-100 bg-slate-50 flex-none ring-2 ring-primary/10">
                        <img src="{{ asset($testimonial->preview->value ?? '') }}" alt="" class="w-full h-full object-cover">
                     </div>
                     <div>
                        <h4 class="text-sm font-bold text-slate-900 leading-none mb-1">{{ $testimonial->title ?? '' }}</h4>
                        <span class="text-[10px] text-slate-400 font-semibold">{{ $testimonial->slug ?? '' }}</span>
                     </div>
                  </div>
               </div>
            @endforeach
         </div>
      </div>
   </section>

   <!-- Bottom CTA Banner (Light Purple layout) -->
   <section class="py-20 bg-gradient-to-r from-purple-100 to-indigo-50 text-slate-800 relative overflow-hidden border-t border-b border-purple-200/50">
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#6b21a805_1px,transparent_1px),linear-gradient(to_bottom,#6b21a805_1px,transparent_1px)] bg-[size:24px_24px] pointer-events-none"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[250px] bg-purple-300/10 rounded-full blur-[100px] pointer-events-none"></div>
      
      <div class="container mx-auto px-4 relative z-10 text-center max-w-3xl flex flex-col items-center gap-8">
         <h3 class="text-3xl sm:text-4xl font-black text-slate-900 leading-tight tracking-tight">{{ $home->calltoaction->title ?? '' }}</h3>
         <a class="btn btn-primary btn-lg rounded-2xl gap-3 shadow-xl shadow-primary/30 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 px-8 text-white" href="{{ url($home->calltoaction->button_link ?? '') }}">
            <span>{{ $home->calltoaction->button_title ?? '' }}</span>
            <span class="material-icons">arrow_forward</span>
         </a>
      </div>
   </section>
</main>

<!-- Code Switcher and Simulation Script -->
<script type="text/javascript">
   // Language Switcher Function
   function switchTab(lang) {
      // Hide all code blocks
      document.querySelectorAll('.code-block').forEach(el => {
         el.classList.add('hidden');
      });
      // Show chosen block
      document.getElementById('code-' + lang).classList.remove('hidden');

      // Update button styles
      document.querySelectorAll('.tab-btn').forEach(btn => {
         btn.className = 'tab-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-purple-600 hover:text-purple-800';
      });
      document.getElementById('tab-' + lang).className = 'tab-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-purple-600 text-white shadow';
   }

   // Simulate API Message Send
   function simulateMessageSend() {
      const chatBody = document.getElementById('chat-body');
      
      // Create message bubble
      const msgBubble = document.createElement('div');
      msgBubble.className = 'bg-white text-slate-800 p-2 rounded-lg text-xs max-w-[80%] shadow-sm self-start leading-tight transform scale-90 opacity-0 transition-all duration-300';
      msgBubble.innerText = 'Hello from Whasapi! 🚀';
      
      chatBody.appendChild(msgBubble);
      
      // Trigger animations
      setTimeout(() => {
         msgBubble.classList.remove('scale-90', 'opacity-0');
         msgBubble.classList.add('scale-100', 'opacity-100');
         chatBody.scrollTop = chatBody.scrollHeight;
      }, 50);
   }

   // Update Demo Chat Preview
   function updateDemoChat() {
      const sender = document.getElementById('demo-sender').value || 'Johnny Cash';
      const rawText = document.getElementById('demo-text').value || '';
      
      // Parse markdown to HTML format
      let formattedText = rawText
         .replace(/\*(.*?)\*/g, '<strong>$1</strong>')
         .replace(/_(.*?)_/g, '<em>$1</em>')
         .replace(/~(.*?)~/g, '<del>$1</del>');

      // Update phone display
      document.getElementById('phone-sender').innerText = sender;
      document.getElementById('phone-msg').innerHTML = formattedText;
   }

   // Initialize the Try Out preview on load
   document.addEventListener('DOMContentLoaded', () => {
      updateDemoChat();
   });
</script>
@endsection
