@php
$features = \App\Models\Post::where('type','feature')
           ->where('featured',1)
           ->where('status',1)
           ->where('lang',app()->getLocale())
           ->with('preview','excerpt')
           ->latest()
           ->take($limit ?? 6)
           ->get();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
   @foreach($features as $feature)
   <div class="bg-white/70 backdrop-blur-sm border border-gray-150 rounded-[2rem] p-8 shadow-sm hover:shadow-2xl transition-all duration-300 hover:-translate-y-1.5 relative overflow-hidden flex flex-col justify-between group">
      
      <!-- Subtle Decorative Radial Gradient Glow on Hover -->
      <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
      <div class="absolute -top-12 -right-12 w-36 h-36 bg-primary/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>

      <div class="relative z-10">
         <!-- Feature Icon -->
         <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
            <img src="{{ asset($feature->preview->value ?? '') }}" 
                 alt="{{ $feature->title }}" 
                 class="w-10 h-10 object-contain" />
         </div>

         <!-- Feature Content -->
         <h4 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors">
            <a href="{{ url('feature/'.$feature->slug) }}" class="focus:outline-none">
               {{ Str::limit($feature->title, 28) }}
            </a>
         </h4>
         <p class="text-gray-500 text-sm leading-relaxed mb-6">
            {{ Str::limit($feature->excerpt->value ?? '', 140) }}
         </p>
      </div>

      <!-- Learn More Link -->
      <div class="pt-4 border-t border-gray-100/60 relative z-10">
         <a href="{{ url('feature/'.$feature->slug) }}" class="inline-flex items-center text-sm font-bold text-primary group-hover:text-primary/80 transition-colors gap-1.5">
            {{ __('Learn More') }}
            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
               <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
         </a>
      </div>

   </div>
   @endforeach
</div>
