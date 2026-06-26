@section('page-title', $title ?? 'Page')

<div class="bg-base-100 border-b border-base-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      
      <!-- Left: Title & Breadcrumbs -->
      <div class="flex items-center gap-3">
        @isset($prev)
        <a href="{{ url($prev) }}" 
           class="btn btn-circle btn-ghost btn-sm rounded-full">
          <span class="material-icons text-xl">arrow_back</span>
        </a>
        @endisset
        
        <div>
          <h2 class="text-2xl font-bold text-base-content mb-1">{!! $title ?? '' !!}</h2>
          
          <!-- Breadcrumbs -->
          <nav class="hidden md:block">
            <ol class="flex items-center gap-2 text-sm">
              <li>
                <a href="{{ url('/user/dashboard') }}" class="text-base-content/60 hover:text-primary transition-colors">
                  <span class="material-icons text-base">home</span>
                </a>
              </li>
              @if(isset($title))
              <li class="flex items-center gap-2">
                <span class="material-icons text-base-content/40 text-sm">chevron_right</span>
                <span class="text-base-content/80">{!! $title ?? '' !!}</span>
              </li>
              @endif
            </ol>
          </nav>
        </div>
      </div>

      <!-- Right: Action Buttons -->
      <div class="flex items-center gap-2 flex-wrap">
        @isset($buttons)
        @foreach($buttons as $button)
          @if(isset($button['is_button']) && $button['is_button'] == true)
            <button type="button" 
                    {!! $button['components'] ?? '' !!} 
                    class="btn btn-primary btn-sm gap-2 rounded-full">
              {!! $button['name'] ?? '' !!}
            </button>
          @else
            <a href="{{ $button['url'] ?? '' }}" 
               {!! $button['components'] ?? '' !!} 
               class="btn btn-primary btn-sm gap-2 rounded-full">
              {!! $button['name'] ?? '' !!}
            </a>
          @endif
        @endforeach
        @endisset
      </div>
      
    </div>
  </div>
</div>