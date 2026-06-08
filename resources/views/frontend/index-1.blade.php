@extends('frontend.layouts.landing')

@section('content')
@include('frontend.layouts.header-landing')

<main class="pt-20 overflow-hidden">
    @include('frontend.sections.hero-2')

    <!-- Partner Brands Section -->
    @if($brand_area == 'active')
    <div class="py-16 bg-white border-b border-gray-150 relative overflow-hidden">
       <div class="container mx-auto px-4">
          <h3 class="text-xs font-extrabold text-center text-gray-400 uppercase tracking-widest mb-10">{{ $home->brand->title ?? '' }}</h3>
          <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12 max-w-5xl mx-auto">
             @foreach($brands as $brand)
                @if($brand->lang == 'partner')
                   <div class="hover:scale-110 hover:-translate-y-1 transition-all duration-300 w-24 h-12 flex items-center justify-center filter grayscale hover:grayscale-0">
                      <a href="{{ $brand->title }}" target="_blank"><img src="{{ asset($brand->slug) }}" alt="" class="max-w-full max-h-full object-contain"></a>
                   </div>
                @endif
             @endforeach
          </div>
       </div>
    </div>
    @endif

    <!-- CTA Area 1 -->
    <div class="py-16 bg-gradient-to-r from-primary/5 to-secondary/5 relative overflow-hidden border-b border-gray-150">
       <div class="container mx-auto px-4 max-w-6xl relative z-10">
          <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center bg-white border border-gray-150 rounded-[2rem] p-8 shadow-xl relative overflow-hidden group">
             <!-- Ambient Background Hover Glow -->
             <div class="absolute inset-0 bg-gradient-to-r from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

             <div class="md:col-span-8 flex flex-col gap-4 text-left relative z-10">
                <h3 class="text-2xl sm:text-3xl font-black text-gray-900 leading-tight tracking-tight">{{ $home->cta->title ?? '' }}</h3>
             </div>
             
             <div class="md:col-span-4 flex justify-center items-center gap-4 relative z-10">
                @if(!empty($home->cta_thumbnail))
                <div class="relative rounded-2xl overflow-hidden border border-gray-100 shadow-md">
                   <img src="{{ asset($home->cta_thumbnail) }}" alt="" class="h-16 w-auto object-contain">
                </div>
                @endif
                @if(!empty($home->cta_logo))
                <div class="relative rounded-2xl overflow-hidden border border-gray-100 shadow-md">
                   <img src="{{ asset($home->cta_logo) }}" alt="" class="h-14 w-auto object-contain">
                </div>
                @endif
             </div>
          </div>
       </div>
    </div>

    <!-- Features Area -->
    @if($features_area == 'active')
    <div class="py-24 bg-base-100">
       <div class="container mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center max-w-3xl mx-auto mb-16">
             <span class="text-xs font-extrabold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1.5 rounded-lg mb-4 inline-block">
                {{ __('Core Capabilities') }}
             </span>
             <h3 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">{{ $home->features->title ?? '' }}</h3>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto items-stretch">
             @include('frontend.sections.features',['limit'=>3])
          </div>
       </div>
    </div>
    @endif

    <!-- Platform Area -->
    <div class="py-24 bg-white relative overflow-hidden border-t border-gray-150">
       <!-- Ambient blob -->
       <div class="absolute top-1/2 right-[-10%] w-96 h-96 bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>

       <div class="container mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center max-w-6xl mx-auto">
             
             <!-- Left: Platform Thumbnail -->
             <div class="relative group">
                <div class="absolute -inset-4 rounded-3xl bg-gradient-to-tr from-primary/10 to-secondary/10 blur-xl opacity-75 pointer-events-none"></div>
                <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white bg-white/50 p-2 backdrop-blur-sm">
                   <img src="{{ asset($home->platform_thumbnail ?? '') }}" alt="" class="w-full h-auto rounded-[2rem] object-cover group-hover:scale-[1.005] transition-transform duration-500">
                </div>
             </div>

             <!-- Right: Platform Content -->
             <div class="flex flex-col gap-6 items-start text-left">
                <span class="text-xs font-extrabold uppercase tracking-widest text-secondary bg-secondary/10 px-3 py-1.5 rounded-lg">
                   {{ __('Powerful API') }}
                </span>
                <h3 class="text-3xl sm:text-4xl font-black text-gray-900 leading-tight tracking-tight">{{ $home->platform->title ?? '' }}</h3>
                <p class="text-gray-600 text-lg leading-relaxed">{{ $home->platform->description ?? '' }}</p>
                <a class="btn btn-primary btn-lg rounded-2xl gap-3 shadow-lg shadow-primary/20 hover:shadow-xl hover:scale-[1.03] active:scale-[0.97] transition-all duration-300 text-white" href="{{ url($home->platform->button_link ?? '') }}">
                   <span>{{ $home->platform->button_title ?? '' }}</span>
                   <span class="material-icons">arrow_forward</span>
                </a>
             </div>

          </div>
       </div>
    </div>

    <!-- Pricing Area -->
    @include('frontend.pricings')

    <!-- Account Area -->
    <div class="py-24 bg-base-100 border-t border-b border-gray-150 relative overflow-hidden">
       <!-- Ambient blur -->
       <div class="absolute bottom-[-10%] left-[-10%] w-[50%] h-[50%] bg-secondary/5 rounded-full blur-[120px] pointer-events-none"></div>

       <div class="container mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center max-w-6xl mx-auto">
             
             <!-- Left: Account Form & Download -->
             <div class="flex flex-col gap-6 items-start text-left">
                <span class="text-xs font-extrabold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1.5 rounded-lg">
                   {{ $home->account_area->subheading ?? '' }}
                </span>
                <h3 class="text-3xl sm:text-4xl font-black text-gray-900 leading-tight tracking-tight">{{ $home->account_area->heading ?? '' }}</h3>
                <p class="text-gray-600 text-lg leading-relaxed">{{ $home->account_area->description ?? '' }}</p>
                
                <!-- Custom Form -->
                <div class="w-full max-w-md mt-4">
                   <form action="{{ url($home->account_area->form_link ?? '') }}" class="flex flex-col sm:flex-row gap-3">
                      <input type="email" placeholder="example@email.com" name="email" class="input input-bordered rounded-2xl flex-grow h-12 focus:ring-2 focus:ring-primary/20 focus:border-primary px-5" required>
                      <button class="btn btn-primary h-12 rounded-2xl text-white font-bold gap-2 px-6">
                         {{ __('Sign Up') }}
                         <span class="material-icons text-sm">send</span>
                      </button>
                   </form>
                </div>

                <!-- Apps Download Section -->
                <div class="mt-6">
                   <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-3">{{ __('# Also available mobile apps') }}</span>
                   <div class="flex items-center gap-4 flex-wrap">
                      @if(!empty($home->account_footer_left_image))
                      <a href="{{ url($home->account_area->button_link1 ?? '') }}" class="hover:scale-105 active:scale-95 transition-all duration-300 shadow-md hover:shadow-lg rounded-xl overflow-hidden">
                         <img src="{{ asset($home->account_footer_left_image) }}" alt="" class="h-10 w-auto object-contain">
                      </a>
                      @endif
                      @if(!empty($home->account_footer_right_image))
                      <a href="{{ url($home->account_area->button_link2 ?? '') }}" class="hover:scale-105 active:scale-95 transition-all duration-300 shadow-md hover:shadow-lg rounded-xl overflow-hidden">
                         <img src="{{ asset($home->account_footer_right_image) }}" alt="" class="h-10 w-auto object-contain">
                      </a>
                      @endif
                   </div>
                </div>
             </div>
             
             <!-- Right: Illustration / Images Mockups -->
             <div class="relative group">
                <div class="absolute -inset-4 rounded-3xl bg-gradient-to-tr from-primary/10 to-secondary/10 blur-xl opacity-70"></div>
                <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white bg-white/50 p-2 backdrop-blur-sm">
                   <img src="{{ asset($home->account_area_thumbnail ?? '') }}" alt="" class="w-full h-auto rounded-[2rem] object-cover">
                </div>
                
                @if(!empty($home->account_area_top_thumbnail))
                <div class="absolute -top-6 -left-6 hidden md:block w-36 h-36 bg-white border border-gray-150 rounded-2xl p-4 shadow-2xl animate-float">
                   <img src="{{ asset($home->account_area_top_thumbnail) }}" alt="" class="w-full h-full object-contain">
                </div>
                @endif
                
                @if(!empty($home->account_area_bottom_thumbnail))
                <div class="absolute -bottom-6 -right-6 hidden md:block w-36 h-36 bg-white border border-gray-150 rounded-2xl p-4 shadow-2xl animate-float" style="animation-delay: 1.5s">
                   <img src="{{ asset($home->account_area_bottom_thumbnail) }}" alt="" class="w-full h-full object-contain">
                </div>
                @endif
             </div>

          </div>
       </div>
    </div>

    <!-- Testimonials Area -->
    <div class="py-24 bg-white relative overflow-hidden">
       <div class="container mx-auto px-4 sm:px-6 lg:px-8">
          
          <div class="text-center max-w-3xl mx-auto mb-16">
             <span class="text-xs font-extrabold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1.5 rounded-lg mb-4 inline-block">
                {{ __('Testimonials') }}
             </span>
             <h3 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">{{ __('What our customers say') }}</h3>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto items-stretch">
             @foreach($testimonials as $testimonial)
                <div class="bg-white/95 backdrop-blur border border-gray-150 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group relative">
                   <span class="absolute top-4 right-6 text-gray-100 text-6xl font-serif select-none pointer-events-none group-hover:text-primary/5 transition-colors">”</span>
                   <div>
                      <div class="flex items-center gap-0.5 text-amber-400 mb-3">
                         <span class="material-icons text-base">star</span>
                         <span class="material-icons text-base">star</span>
                         <span class="material-icons text-base">star</span>
                         <span class="material-icons text-base">star</span>
                         <span class="material-icons text-base">star</span>
                      </div>
                      <p class="text-gray-600 text-sm sm:text-base italic leading-relaxed mb-4">"{{ Str::limit($testimonial->excerpt->value ?? '',160) }}"</p>
                   </div>
                   <div class="flex items-center gap-3 pt-4 border-t border-gray-100 mt-4">
                      <div class="w-11 h-11 rounded-full overflow-hidden border border-gray-100 bg-gray-50 flex-none ring-2 ring-primary/10">
                         <img src="{{ asset($testimonial->preview->value ?? '') }}" alt="" class="w-full h-full object-cover">
                      </div>
                      <div class="text-left">
                         <h4 class="text-sm font-bold text-gray-900">{{ $testimonial->title ?? '' }}</h4>
                         <span class="text-xs text-gray-400 font-semibold">{{ $testimonial->slug ?? '' }}</span>
                      </div>
                   </div>
                </div>
             @endforeach
          </div>

       </div>
    </div>

    <!-- CTA Area 2 (Dark theme) -->
    <div class="py-20 bg-slate-950 text-white relative overflow-hidden">
       <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808005_1px,transparent_1px),linear-gradient(to_bottom,#80808005_1px,transparent_1px)] bg-[size:24px_24px] pointer-events-none"></div>
       <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[250px] bg-primary/10 rounded-full blur-[100px] pointer-events-none"></div>
       
       <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center max-w-4xl flex flex-col items-center gap-8">
          <h3 class="text-3xl sm:text-4xl font-black text-white leading-tight tracking-tight">{{ $home->calltoaction->title ?? '' }}</h3>
          <a class="btn btn-primary btn-lg rounded-2xl gap-3 shadow-xl shadow-primary/20 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 px-8 text-white" href="{{ url($home->calltoaction->button_link ?? '') }}">
             <span>{{ $home->calltoaction->button_title ?? '' }}</span>
             <span class="material-icons">arrow_forward</span>
          </a>
       </div>
    </div>

    <!-- FAQ Area -->
    <div class="py-24 bg-base-100 border-b border-gray-150">
       <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
             
             <!-- FAQ list -->
             <div class="lg:col-span-7 flex flex-col gap-4">
                <div class="accordion flex flex-col gap-4" id="accordionExample">
                   @php $bottomIndex = 0; @endphp
                   @foreach($faqs as $key => $faq)
                      @if($faq->slug != 'top')
                         <div class="collapse collapse-arrow bg-white border border-gray-150 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300">
                            <input type="radio" name="bottom-faq-accordion" id="bottom-faq-{{ $key }}" {{ $bottomIndex == 0 ? 'checked' : '' }} class="peer" />
                            
                            <div class="collapse-title text-base sm:text-lg font-bold text-gray-800 py-5 px-6 pr-12 peer-checked:text-primary transition-colors">
                               {{ $faq->title }}
                            </div>
                            
                            <div class="collapse-content px-6 pb-6 text-sm sm:text-base text-gray-650 leading-relaxed border-t border-transparent peer-checked:border-gray-50 pt-4">
                               <p>{{ $faq->excerpt->value ?? '' }}</p>
                            </div>
                         </div>
                         @php $bottomIndex++; @endphp
                      @endif
                   @endforeach
                </div>
             </div>
             
             <!-- Direct Query Card -->
             <div class="lg:col-span-5 flex justify-center">
                <div class="bg-white border border-gray-150 rounded-[2.5rem] p-8 shadow-xl text-center max-w-sm w-full relative overflow-hidden group">
                   <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent rounded-[2.5rem] opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                   
                   <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-6 text-primary group-hover:scale-110 transition-transform">
                      <span class="material-icons text-3xl">mail</span>
                   </div>
                   
                   <h4 class="text-xl font-bold text-gray-900 mb-3">{{ __('Do you have more questions?') }}</h4>
                   <p class="text-gray-500 text-sm mb-6 leading-relaxed">{{ __('Feel free to contact us directly. Our support team is here to help you.') }}</p>
                   <a class="btn btn-primary w-full rounded-xl text-white font-bold h-12 shadow-md hover:shadow-lg active:scale-98 transition-all" href="{{ url('/contact') }}">{{ __('Shoot a Direct Mail') }}</a>
                </div>
             </div>

          </div>
       </div>
    </div>
</main>
@endsection
