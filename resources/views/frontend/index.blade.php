@extends('frontend.layouts.landing')

@section('content')
@include('frontend.layouts.header-landing')

<main class="pt-20 overflow-hidden">
   <!-- Hero Section -->
   @include('frontend.sections.hero-1')

   <!-- Features Area -->
   @if($features_area == 'active')
   <div id="features" class="py-24 bg-gradient-to-b from-base-200 to-white relative">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
         <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">
               {{ $home->features->title ?? '' }}
            </h2>
         </div>
         @include('frontend.sections.features-landing', ['limit' => 6])
      </div>
   </div>
   @endif

   <!-- Dashboard Info Area -->
   @include('frontend.sections.top-area-landing')

   <!-- Top FAQ Section -->
   @include('frontend.sections.top-faq-landing')

   <!-- Integration Area -->
   @include('frontend.sections.top-integration-landing')

   <!-- Pricing Table -->
   @include('frontend.sections.pricing-landing')

   <!-- Testimonials Area -->
   @include('frontend.sections.feedback-1-landing')

   <!-- Why Choose Us & Counters -->
   @include('frontend.whychoose-landing')

   <!-- General FAQ Section -->
   @include('frontend.sections.faq-landing')
</main>
@endsection
