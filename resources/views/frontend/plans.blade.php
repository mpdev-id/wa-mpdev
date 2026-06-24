@extends('frontend.layouts.landing')
@section('content')
@include('frontend.layouts.header-landing')

<main class="pt-24">
    <!-- Breadcrumb & Header Section -->
    <div class="relative py-20 overflow-hidden">
        <!-- Background elements -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary/5 rounded-full blur-[100px] animate-pulse"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-secondary/5 rounded-full blur-[100px] animate-pulse" style="animation-delay: 2s"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808003_1px,transparent_1px),linear-gradient(to_bottom,#80808003_1px,transparent_1px)] bg-[size:24px_24px]"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <!-- Navigation path -->
            <div class="flex items-center justify-center gap-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">
                <a href="{{ url('/') }}" class="hover:text-primary transition-colors duration-200">{{ __('Home') }}</a>
                <span class="material-icons text-xs text-gray-300">chevron_right</span>
                <span class="text-primary font-black">{{ __('Pricing') }}</span>
            </div>

            <!-- Page Title -->
            <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                {{ __('Harga Layanan WHASAPI') }} 💎
            </h1>
            <p class="text-sm sm:text-base text-slate-500 max-w-xl mx-auto font-medium leading-relaxed">
                {{ __('Mulai perjalanan Anda bersama kami. Pilih paket yang paling pas dengan skala bisnis Anda.') }}
            </p>
        </div>
    </div>

    <!-- Pricing Area -->
    <div id="price" class="pb-20">
        @include('frontend.pricings')
    </div>

    <!-- Why Choose Us -->
    @include('frontend.whychoose-landing')

    <!-- FAQ Section -->
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
                    {{ __('Temukan jawaban untuk pertanyaan yang paling sering diajukan tentang WHASAPI.') }}
                </p>
            </div>

            <!-- FAQ Accordion (DaisyUI Pure CSS Collapse) -->
            <div class="max-w-4xl mx-auto flex flex-col gap-4">
                @php $i = 0; @endphp
                @foreach($faqs as $key => $faq)
                    @if($faq->slug != 'top')
                        <div class="collapse collapse-arrow bg-white border border-gray-150 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300">
                            <input type="radio" name="faq-accordion" id="faq-item-{{ $key }}" {{ $i == 0 ? 'checked' : '' }} class="peer" /> 
                            
                            <div class="collapse-title text-base sm:text-lg font-bold text-gray-800 py-5 px-6 pr-12 peer-checked:text-primary transition-colors">
                                {{ $faq->title }}
                            </div>
                            
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
</main>
@endsection