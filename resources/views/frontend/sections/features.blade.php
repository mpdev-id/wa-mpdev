@php
$features = \App\Models\Post::where('type','feature')
           ->where('featured',1)
           ->where('status',1)
           ->where('lang',app()->getLocale())
           ->with('preview','excerpt')
           ->latest()
           ->take($limit ?? 3)
           ->get();
@endphp

@foreach($features as $feature)
<div class="bg-white/80 backdrop-blur border border-gray-150 rounded-[2rem] p-8 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group">
   
   <div class="text-left">
      <!-- Feature Icon -->
      <div class="w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
         <img src="{{ asset($feature->preview->value ?? '') }}" alt="{{ $feature->title }}" class="w-10 h-10 object-contain">
      </div> 
      
      <!-- Feature Text -->
      <h4 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors">
         <a href="{{ url('feature/'.$feature->slug) }}">{{ Str::limit($feature->title, 24) }}</a>
      </h4>
      <p class="text-gray-500 text-sm leading-relaxed mb-6">{{ Str::limit($feature->excerpt->value ?? '', 120) }}</p>
   </div>

   <!-- Arrow Link -->
   <div class="pt-4 border-t border-gray-100/60 text-left">
      <a href="{{ url('feature/'.$feature->slug) }}" class="inline-flex items-center text-sm font-bold text-primary group-hover:text-primary/80 transition-colors gap-2">
         {{ __('Learn More') }}
         <svg class="w-5 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7-7 7M3 12h18" />
         </svg>
      </a>
   </div> 

</div>
@endforeach
