@section('page-title', $title ?? 'Page')

<div class="bg-white border-b border-gray-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      
      <!-- Left: Title & Breadcrumbs -->
      <div class="flex items-center gap-3">
        @isset($prev)
        <a href="{{ url($prev) }}" 
           class="btn btn-circle btn-ghost btn-sm">
          <span class="material-icons text-xl">arrow_back</span>
        </a>
        @endisset
        
        <div>
          <h2 class="text-2xl font-bold text-gray-900 mb-1">{!! $title ?? '' !!}</h2>
          
          <!-- Breadcrumbs -->
          <nav class="hidden md:block">
            <ol class="flex items-center gap-2 text-sm">
              <li>
                <a href="{{ url('/user/dashboard') }}" class="text-gray-500 hover:text-primary transition-colors">
                  <span class="material-icons text-base">home</span>
                </a>
              </li>
              @if(isset($title))
              <li class="flex items-center gap-2">
                <span class="material-icons text-gray-400 text-sm">chevron_right</span>
                <span class="text-gray-600">{!! $title ?? '' !!}</span>
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
                    class="btn btn-primary btn-sm gap-2">
              {!! $button['name'] ?? '' !!}
            </button>
          @else
            <a href="{{ $button['url'] ?? '' }}" 
               {!! $button['components'] ?? '' !!} 
               class="btn btn-primary btn-sm gap-2">
              {!! $button['name'] ?? '' !!}
            </a>
          @endif
        @endforeach
        @endisset
      </div>
      
    </div>
  </div>
</div>