<!-- Modern Header -->
<header id="header-sticky" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 py-5">
   <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="navbar bg-transparent min-h-0 p-0 flex justify-between items-center">
         
         <!-- Logo -->
         <div class="flex-none">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
               <img src="{{ asset(get_option('primary_data',true)->footer_logo ?? '') }}" 
                    alt="Logo" 
                    class="h-10 w-auto object-contain transition-transform duration-300 hover:scale-105" />
            </a>
         </div>

         <!-- Center Menu (Desktop) -->
         <div class="hidden lg:flex items-center gap-8">
            <a href="{{ url('/') }}" class="text-sm font-semibold text-gray-700 hover:text-primary transition-colors duration-200 relative py-1 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:after:w-full">
               {{ __('Home') }}
            </a>
            <a href="#features" class="text-sm font-semibold text-gray-700 hover:text-primary transition-colors duration-200 relative py-1 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:after:w-full">
               {{ __('Features') }}
            </a>
            <a href="#pricing" class="text-sm font-semibold text-gray-700 hover:text-primary transition-colors duration-200 relative py-1 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:after:w-full">
               {{ __('Pricing') }}
            </a>
            <a href="#faq" class="text-sm font-semibold text-gray-700 hover:text-primary transition-colors duration-200 relative py-1 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:after:w-full">
               {{ __('FAQ') }}
            </a>
            <a href="{{ url('/contact') }}" class="text-sm font-semibold text-gray-700 hover:text-primary transition-colors duration-200 relative py-1 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:after:w-full">
               {{ __('Contact') }}
            </a>
         </div>

         <!-- Right Action Buttons (Desktop) -->
         <div class="hidden md:flex items-center gap-4">
            @if(!Auth::check())
               <a href="{{ url('/login') }}" class="text-sm font-semibold text-gray-700 hover:text-primary transition-colors duration-200 px-4 py-2">
                  {{ __('Login') }}
               </a>
               <a href="{{ url('/register') }}" class="btn btn-primary btn-md rounded-xl text-white font-medium shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                  {{ __('Get Started') }}
               </a>
            @else
               <a href="{{ url('/login') }}" class="btn btn-primary btn-md rounded-xl text-white font-medium shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                  <span class="material-icons text-sm">dashboard</span>
                  {{ __('Dashboard') }}
               </a>
            @endif
         </div>

         <!-- Mobile Menu Button (Hamburger) -->
         <div class="lg:hidden flex items-center">
            <label for="mobile-drawer" class="btn btn-ghost btn-circle text-gray-700">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
               </svg>
            </label>
         </div>

      </div>
   </div>
</header>

<!-- Mobile Navigation Drawer -->
<div class="drawer drawer-end z-50">
   <input id="mobile-drawer" type="checkbox" class="drawer-toggle" />
   <div class="drawer-side">
      <label for="mobile-drawer" class="drawer-overlay"></label>
      <div class="menu p-6 w-80 min-h-full bg-white text-gray-800 flex flex-col justify-between">
         
         <div>
            <!-- Header inside Drawer -->
            <div class="flex items-center justify-between pb-6 border-b border-gray-100">
               <img src="{{ asset(get_option('primary_data',true)->footer_logo ?? '') }}" 
                    alt="Logo" 
                    class="h-8 w-auto object-contain" />
               <label for="mobile-drawer" class="btn btn-sm btn-circle btn-ghost">✕</label>
            </div>

            <!-- Navigation Links -->
            <div class="flex flex-col gap-4 py-6">
               <a href="{{ url('/') }}" class="flex items-center gap-3 text-lg font-medium text-gray-700 hover:text-primary transition-colors p-2 rounded-lg hover:bg-gray-50">
                  <span class="material-icons text-gray-400">home</span>
                  {{ __('Home') }}
               </a>
               <a href="#features" class="flex items-center gap-3 text-lg font-medium text-gray-700 hover:text-primary transition-colors p-2 rounded-lg hover:bg-gray-50">
                  <span class="material-icons text-gray-400">featured_play_list</span>
                  {{ __('Features') }}
               </a>
               <a href="#pricing" class="flex items-center gap-3 text-lg font-medium text-gray-700 hover:text-primary transition-colors p-2 rounded-lg hover:bg-gray-50">
                  <span class="material-icons text-gray-400">payments</span>
                  {{ __('Pricing') }}
               </a>
               <a href="#faq" class="flex items-center gap-3 text-lg font-medium text-gray-700 hover:text-primary transition-colors p-2 rounded-lg hover:bg-gray-50">
                  <span class="material-icons text-gray-400">quiz</span>
                  {{ __('FAQ') }}
               </a>
               <a href="{{ url('/contact') }}" class="flex items-center gap-3 text-lg font-medium text-gray-700 hover:text-primary transition-colors p-2 rounded-lg hover:bg-gray-50">
                  <span class="material-icons text-gray-400">contact_support</span>
                  {{ __('Contact') }}
               </a>
            </div>
         </div>

         <!-- CTA inside Drawer -->
         <div class="border-t border-gray-100 pt-6 flex flex-col gap-3">
            @if(!Auth::check())
               <a href="{{ url('/login') }}" class="btn btn-outline btn-md w-full rounded-xl">
                  {{ __('Login') }}
               </a>
               <a href="{{ url('/register') }}" class="btn btn-primary btn-md w-full text-white rounded-xl shadow-lg">
                  {{ __('Get Started') }}
               </a>
            @else
               <a href="{{ url('/login') }}" class="btn btn-primary btn-md w-full text-white rounded-xl shadow-lg">
                  <span class="material-icons text-sm">dashboard</span>
                  {{ __('Dashboard') }}
               </a>
            @endif
         </div>

      </div>
   </div>
</div>

<script>
   // Sticky Navbar Scroll Effect
   document.addEventListener('DOMContentLoaded', () => {
      const header = document.getElementById('header-sticky');
      const handleScroll = () => {
         if (window.scrollY > 20) {
            header.classList.add('bg-white/80', 'backdrop-blur-md', 'shadow-md', 'py-3');
            header.classList.remove('py-5');
         } else {
            header.classList.remove('bg-white/80', 'backdrop-blur-md', 'shadow-md', 'py-3');
            header.classList.add('py-5');
         }
      };
      
      window.addEventListener('scroll', handleScroll);
      handleScroll(); // Run initially
      
      // Close drawer on click of anchor links
      const drawerToggle = document.getElementById('mobile-drawer');
      const drawerLinks = document.querySelectorAll('.drawer-side a');
      drawerLinks.forEach(link => {
         link.addEventListener('click', () => {
            drawerToggle.checked = false;
         });
      });
   });
</script>
