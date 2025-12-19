<footer class="bg-white border-t border-gray-200 py-6 mt-auto">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
      <!-- Copyright -->
      <div class="text-sm text-gray-600">
        &copy; {{ date('Y') }} 
        <span class="font-semibold text-gray-900">{{ env('APP_NAME', 'WhatsApp API') }}</span>. 
        {{ __('All rights reserved.') }}
      </div>

      <!-- Links -->
      <div class="flex items-center gap-6 text-sm">
        @if(Request::is('user/*'))
        <a href="{{ url('/user/support') }}" class="text-gray-600 hover:text-primary transition-colors">
          {{ __('Support') }}
        </a>
        <a href="{{ url('/user/subscription') }}" class="text-gray-600 hover:text-primary transition-colors">
          {{ __('Subscription') }}
        </a>
        @endif
        <a href="#" class="text-gray-600 hover:text-primary transition-colors">
          {{ __('Privacy Policy') }}
        </a>
        <a href="#" class="text-gray-600 hover:text-primary transition-colors">
          {{ __('Terms of Service') }}
        </a>
      </div>

      <!-- Version -->
      <div class="text-xs text-gray-500">
        v1.0.0
      </div>
    </div>
  </div>
</footer>
