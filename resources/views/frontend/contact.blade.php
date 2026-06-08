@extends('frontend.layouts.landing')
@section('content')
@include('frontend.layouts.header-landing')

<main class="pt-20">
   <!-- Breadcrumb / Header Area -->
   <div class="relative bg-gradient-to-b from-primary/5 via-base-100 to-base-100 py-16 md:py-24 border-b border-gray-150 overflow-hidden">
      <!-- Ambient background blur -->
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-primary/10 rounded-full blur-[100px] pointer-events-none"></div>
      
      <div class="container mx-auto px-4 relative z-10 text-center">
         <span class="text-xs font-extrabold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1.5 rounded-lg mb-4 inline-block">
            {{ __('Get In Touch') }}
         </span>
         <h1 class="text-3xl sm:text-5xl font-black text-gray-900 tracking-tight mb-4">
            {{ __('Hubungi Kami') }}
         </h1>
         <div class="flex items-center justify-center gap-2 text-sm text-gray-500 font-medium">
            <a href="{{ url('/') }}" class="hover:text-primary transition-colors">{{ __('Beranda') }}</a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-800">{{ __('Kontak Kami') }}</span>
         </div>
      </div>
   </div>

   <!-- Contact Info Cards -->
   <div class="py-16 bg-white relative">
      <div class="container mx-auto px-4 max-w-6xl">
         <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            
            <!-- Visit Us Card -->
            <div class="bg-base-100/50 backdrop-blur border border-gray-150 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 text-center group">
               <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-6 text-primary group-hover:scale-110 transition-transform">
                  <span class="material-icons text-2xl">location_on</span>
               </div>
               <h4 class="text-lg font-bold text-gray-900 mb-2">{{ __('Kunjungi Kami') }}</h4>
               <p class="text-gray-650 text-sm leading-relaxed">
                  <a href="{{ $contact_page->map_link ?? '#' }}" target="_blank" class="hover:text-primary transition-colors">
                     {{ $contact_page->address ?? '' }}<br>{{ $contact_page->country ?? '' }}
                  </a>
               </p>
            </div>

            <!-- Phone Card -->
            <div class="bg-base-100/50 backdrop-blur border border-gray-150 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 text-center group">
               <div class="w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center mx-auto mb-6 text-secondary group-hover:scale-110 transition-transform">
                  <span class="material-icons text-2xl">phone</span>
               </div>
               <h4 class="text-lg font-bold text-gray-900 mb-2">{{ __('Hubungi Telepon') }}</h4>
               <p class="text-gray-650 text-sm leading-relaxed">
                  <a href="tel:{{ $contact_page->contact1 ?? '' }}" class="hover:text-secondary transition-colors block">{{ $contact_page->contact1 ?? '' }}</a>
                  <a href="tel:{{ $contact_page->contact2 ?? '' }}" class="hover:text-secondary transition-colors block mt-1">{{ $contact_page->contact2 ?? '' }}</a>
               </p>
            </div>

            <!-- Email Card -->
            <div class="bg-base-100/50 backdrop-blur border border-gray-150 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 text-center group">
               <div class="w-14 h-14 bg-accent/10 rounded-2xl flex items-center justify-center mx-auto mb-6 text-accent group-hover:scale-110 transition-transform">
                  <span class="material-icons text-2xl">mail</span>
               </div>
               <h4 class="text-lg font-bold text-gray-900 mb-2">{{ __('Kirim Email') }}</h4>
               <p class="text-gray-650 text-sm leading-relaxed">
                  <a href="mailto:{{ $contact_page->email1 ?? '' }}" class="hover:text-accent transition-colors block">{{ $contact_page->email1 ?? '' }}</a>
                  <a href="mailto:{{ $contact_page->email2 ?? '' }}" class="hover:text-accent transition-colors block mt-1">{{ $contact_page->email2 ?? '' }}</a>
               </p>
            </div>

         </div>

         <!-- Contact Form Box -->
         <div id="contact" class="bg-base-200/50 border border-gray-150 rounded-[2.5rem] p-8 md:p-12 shadow-md max-w-4xl mx-auto relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
            
            <div class="text-center mb-10 relative z-10">
               <h3 class="text-2xl sm:text-3xl font-black text-gray-900 mb-3">{{ __('Kirimkan Pesan Anda 💬') }}</h3>
               <p class="text-gray-500 text-sm sm:text-base">{{ __('Isi formulir di bawah ini, tim kami akan segera menghubungi Anda.') }}</p>
            </div>

            @if ($errors->any())
            <div class="alert alert-error mb-8 shadow-sm rounded-2xl flex items-start text-left">
               <span class="material-icons text-error-content text-xl mr-2">error</span>
               <ul class="list-disc pl-5 text-sm text-error-content/90">
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
               </ul>
            </div>
            @endif

            @if(Session::has('success'))                       
            <div class="alert alert-success mb-8 shadow-sm rounded-2xl flex items-center text-left">
               <span class="material-icons text-success-content text-xl mr-2">check_circle</span>
               <span class="text-sm font-semibold text-success-content/90">{{ Session::get('success') }}</span>
            </div>
            @endif

            @if(Session::has('error'))                       
            <div class="alert alert-error mb-8 shadow-sm rounded-2xl flex items-center text-left">
               <span class="material-icons text-error-content text-xl mr-2">error</span>
               <span class="text-sm font-semibold text-error-content/90">{{ Session::get('error') }}</span>
            </div>
            @endif

            <form action="{{ route('send.mail') }}" method="post" class="space-y-6 relative z-10 text-left">
               @csrf
               <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  
                  <!-- Name -->
                  <div class="flex flex-col gap-2">
                     <label class="text-xs font-extrabold uppercase tracking-wider text-gray-500">{{ __('Nama Lengkap') }}</label>
                     <input type="text" required name="name" maxlength="20" placeholder="{{ __('Masukkan nama lengkap Anda') }}" class="input input-bordered rounded-2xl h-12 focus:ring-2 focus:ring-primary/20 focus:border-primary @error('name') input-error @enderror">
                  </div>

                  <!-- Email -->
                  <div class="flex flex-col gap-2">
                     <label class="text-xs font-extrabold uppercase tracking-wider text-gray-500">{{ __('Alamat Email') }}</label>
                     <input type="email" required name="email" maxlength="40" placeholder="{{ __('Masukkan alamat email Anda') }}" class="input input-bordered rounded-2xl h-12 focus:ring-2 focus:ring-primary/20 focus:border-primary @error('email') input-error @enderror">
                  </div>

                  <!-- Number -->
                  <div class="flex flex-col gap-2">
                     <label class="text-xs font-extrabold uppercase tracking-wider text-gray-500">{{ __('Nomor Telepon / WhatsApp') }}</label>
                     <input type="number" required name="phone" maxlength="15" placeholder="{{ __('Contoh: 628123456789') }}" class="input input-bordered rounded-2xl h-12 focus:ring-2 focus:ring-primary/20 focus:border-primary @error('phone') input-error @enderror">
                  </div>

                  <!-- Subject -->
                  <div class="flex flex-col gap-2">
                     <label class="text-xs font-extrabold uppercase tracking-wider text-gray-500">{{ __('Subjek Pesan') }}</label>
                     <input type="text" required name="subject" placeholder="{{ __('Topik pesan Anda') }}" maxlength="100" class="input input-bordered rounded-2xl h-12 focus:ring-2 focus:ring-primary/20 focus:border-primary @error('subject') input-error @enderror">
                  </div>

               </div>

               <!-- Message -->
               <div class="flex flex-col gap-2">
                  <label class="text-xs font-extrabold uppercase tracking-wider text-gray-500">{{ __('Isi Pesan') }}</label>
                  <textarea required name="message" placeholder="{{ __('Ketikkan pesan lengkap Anda di sini...') }}" maxlength="500" class="textarea textarea-bordered rounded-2xl min-h-32 focus:ring-2 focus:ring-primary/20 focus:border-primary @error('message') textarea-error @enderror"></textarea>
               </div>

               <!-- Submit Button -->
               <div class="text-center pt-4">
                  <button type="submit" class="btn btn-primary btn-lg rounded-2xl text-white font-bold gap-2 shadow-lg shadow-primary/20 hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 w-full sm:w-auto px-8">
                     <span>{{ __('Kirim Pesan') }}</span>
                     <span class="material-icons">send</span>
                  </button>
               </div>

            </form>
         </div>

      </div>
   </div>
</main>
@endsection