@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Pembayaran Langganan')])
@endsection

@section('content')
<style>
  @keyframes scan {
    0%, 100% { top: 0%; }
    50% { top: 100%; }
  }
  .scanner-line {
    animation: scan 4s ease-in-out infinite;
  }
  .qris-container {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  }
</style>

<div class="max-w-5xl mx-auto px-4 py-4">
  
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-3xl font-black text-base-content tracking-tight">{{ __('Selesaikan Pembayaran') }}</h1>
      <p class="text-base-content/60 mt-1 text-sm md:text-base">{{ __('Pindai kode QRIS dan unggah bukti transfer Anda untuk mengaktifkan langganan') }}</p>
    </div>
    <a href="{{ route('user.subscription.index') }}" class="btn btn-ghost border-base-300 hover:bg-base-200 gap-2 shadow-sm rounded-full">
      <span class="material-icons text-sm">arrow_back</span>
      <span>{{ __('Kembali') }}</span>
    </a>
  </div>

  @if(empty($gateways) || $gateways->count() == 0)
    <div class="alert alert-error shadow-lg rounded-2xl">
      <span class="material-icons">error</span>
      <div>
        <h3 class="font-bold">{{ __('Payment Gateway Tidak Tersedia') }}</h3>
        <p class="text-sm">{{ __('Silakan hubungi administrator untuk mengonfigurasi metode pembayaran.') }}</p>
      </div>
    </div>
  @else
    @php
      $qrisGateway = $gateways->where('namespace', 'App\Gateway\CustomGateway')->first() ?? $gateways->first();
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- QRIS Scan & Instructions -->
      <div class="lg:col-span-7">
        <div class="card glass-card shadow-2xl rounded-[2rem] overflow-hidden border border-slate-100">
          <div class="card-body p-6 md:p-8">
            <div class="flex items-center justify-between mb-6">
              <h2 class="card-title text-xl font-bold text-gray-900 flex items-center gap-2">
                <span class="material-icons text-primary">qr_code_2</span>
                {{ __('Pembayaran QRIS') }}
              </h2>
              <span class="badge badge-success font-bold px-3 py-2 rounded-lg text-xs gap-1">
                <span class="material-icons text-xs">bolt</span>
                {{ __('Aktivasi Otomatis') }}
              </span>
            </div>

            <!-- QRIS Scan Area -->
            <div class="flex flex-col items-center justify-center p-6 bg-white border border-slate-150 rounded-3xl shadow-inner relative mb-6">
              <div class="w-full max-w-[280px] aspect-square relative rounded-2xl overflow-hidden border-4 border-slate-100 shadow-lg">
                <!-- Outer glowing border corners -->
                <div class="absolute top-0 left-0 w-6 h-6 border-t-4 border-l-4 border-primary rounded-tl-lg z-20"></div>
                <div class="absolute top-0 right-0 w-6 h-6 border-t-4 border-r-4 border-primary rounded-tr-lg z-20"></div>
                <div class="absolute bottom-0 left-0 w-6 h-6 border-b-4 border-l-4 border-primary rounded-bl-lg z-20"></div>
                <div class="absolute bottom-0 right-0 w-6 h-6 border-b-4 border-r-4 border-primary rounded-br-lg z-20"></div>
                
                <!-- Laser line scanner -->
                <div class="absolute left-0 w-full h-1 bg-gradient-to-r from-transparent via-primary to-transparent shadow-[0_0_10px_#4f46e5] z-10 scanner-line"></div>
                
                <!-- QR Code Image -->
                <img src="{{ asset($qrisGateway->logo) }}" alt="QRIS QR Code" class="w-full h-full object-cover z-0 p-2">
              </div>

              <!-- Brand Label inside the QR Card -->
              <div class="mt-4 flex flex-col items-center">
                <span class="text-xs uppercase tracking-widest text-slate-400 font-extrabold">{{ __('Pindai Kode QRIS') }}</span>
                <span class="text-sm font-black text-gray-800 mt-0.5">WHASAPI Payment Gateway</span>
              </div>
            </div>

            <!-- ⚡ Unique Code Notice -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex gap-3 items-start mb-4">
              <span class="material-icons text-amber-500 text-lg mt-0.5">warning</span>
              <div class="text-xs text-amber-800 leading-relaxed">
                <p class="font-black text-sm text-amber-900 mb-1">⚡ Wajib Transfer Nominal Tepat!</p>
                <p>Untuk aktivasi <strong>otomatis instan</strong>, transfer nominal <strong>persis</strong> sebesar:</p>
                <p class="text-lg font-black text-amber-900 my-2 tracking-tight">{{ amount_format($total, 'icon') }}</p>
                <p>Termasuk 3 digit unik di belakang sebagai kode identifikasi transaksi Anda. <strong>Jangan dibulatkan.</strong></p>
              </div>
            </div>

            <!-- Instructions List -->
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 space-y-4">
              <h3 class="font-bold text-gray-900 text-sm md:text-base flex items-center gap-2">
                <span class="material-icons text-primary text-lg">info</span>
                {{ __('Langkah-Langkah Pembayaran:') }}
              </h3>
              
              <ol class="space-y-3 text-xs md:text-sm text-gray-650">
                <li class="flex gap-3">
                  <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold flex-none text-xs">1</span>
                  <span>Buka aplikasi e-wallet (GoPay, OVO, DANA, LinkAja) atau Mobile Banking Anda.</span>
                </li>
                <li class="flex gap-3">
                  <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold flex-none text-xs">2</span>
                  <span>Pilih <strong>Scan / QRIS</strong> dan pindai kode QR di atas.</span>
                </li>
                <li class="flex gap-3">
                  <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold flex-none text-xs">3</span>
                  <span>Masukkan nominal <strong class="text-amber-700">{{ amount_format($total, 'icon') }}</strong> dengan angka yang sama persis — termasuk 3 digit terakhirnya.</span>
                </li>
                <li class="flex gap-3">
                  <span class="w-5 h-5 rounded-full bg-success/10 text-success flex items-center justify-center font-bold flex-none text-xs">4</span>
                  <span>Setelah transfer sukses, langganan Anda akan <strong class="text-success">aktif otomatis dalam hitungan detik</strong> tanpa perlu upload bukti manual.</span>
                </li>
              </ol>

              <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-200 text-xs text-slate-500">
                <span class="material-icons text-xs">smartphone</span>
                <span>Sistem verifikasi otomatis via perangkat Android 24 jam nonstop</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Payment Form & Order Summary -->
      <div class="lg:col-span-5 space-y-6">
        <!-- Order Summary Card -->
        <div class="card bg-white border border-slate-100 shadow-xl rounded-[2rem]">
          <div class="card-body p-6 md:p-8">
            <h3 class="card-title text-lg font-bold text-gray-900 mb-4">{{ __('Ringkasan Pesanan') }}</h3>
            
            <div class="space-y-3 text-sm">
              <div class="flex justify-between items-center py-2 border-b border-slate-50">
                <span class="text-gray-500 font-medium">{{ __('Paket Pilihan') }}</span>
                <span class="font-bold text-gray-900 bg-primary/5 text-primary px-3 py-1 rounded-lg">{{ $plan->title ?? 'Premium' }}</span>
              </div>
              
              <div class="flex justify-between items-center py-2 border-b border-slate-50">
                <span class="text-gray-500 font-medium">{{ __('Masa Aktif') }}</span>
                <span class="font-bold text-gray-800">{{ $plan->days }} {{ __('Hari') }}</span>
              </div>
              
              <div class="flex justify-between items-center py-2 border-b border-slate-50">
                <span class="text-gray-500 font-medium">{{ __('Harga Paket') }}</span>
                <span class="font-bold text-gray-800">{{ amount_format($plan->price, 'icon') }}</span>
              </div>
              
              <div class="flex justify-between items-center py-2 border-b border-slate-50">
                <span class="text-gray-500 font-medium">{{ __('Pajak') }}</span>
                <span class="font-bold text-gray-800">{{ amount_format($tax, 'icon') }}</span>
              </div>
              
              <div class="flex justify-between items-center pt-4 text-base font-black border-t-2 border-dashed border-slate-100">
                <span class="text-gray-900 text-lg">{{ __('Total Bayar') }}</span>
                <span class="text-primary text-xl font-extrabold">{{ amount_format($total, 'icon') }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Proof Form -->
        <div class="card bg-white border border-slate-100 shadow-xl rounded-[2rem]">
          <div class="card-body p-6 md:p-8">
            <h3 class="card-title text-lg font-bold text-gray-900 mb-4">{{ __('Konfirmasi Pembayaran') }}</h3>
            
            @if(session()->has('min-max'))
              <div class="alert alert-warning rounded-2xl mb-4 text-xs font-semibold">
                <span>{{ session()->get('min-max') }}</span>
              </div>
            @endif

            <form method="POST" action="{{ route('user.make-payment', [$qrisGateway->id, $plan->id]) }}" enctype="multipart/form-data" class="space-y-4">
              @csrf

              <!-- Info Status Otomatis -->
              <div class="bg-success/10 border border-success/20 text-success p-4 rounded-2xl text-xs leading-relaxed flex gap-2.5 items-start">
                <span class="material-icons text-success text-sm mt-0.5 animate-pulse">check_circle</span>
                <div>
                  <strong class="text-primary font-bold block mb-0.5">⚡ Konfirmasi Otomatis Instan</strong>
                  Setelah Anda melakukan transfer dengan nominal yang tepat, sistem kami akan memprosesnya dalam hitungan detik. Cukup tekan tombol konfirmasi di bawah.
                </div>
              </div>

              <!-- Main Submit Button (Automatic Confirmation) -->
              <button type="submit" class="btn btn-success text-white w-full gap-2 py-3 font-bold shadow-lg shadow-success/20 hover:shadow-success/30 hover:scale-[1.01] active:scale-[0.99] transition-all rounded-full" id="submit-btn">
                <span class="material-icons text-sm">bolt</span>
                <span>{{ __('Saya Sudah Transfer (Konfirmasi Otomatis)') }}</span>
              </button>

              <!-- Optional Manual Upload (Accordion / Collapse) -->
              <div class="collapse collapse-arrow bg-slate-50 border border-slate-200/60 rounded-2xl mt-4">
                <input type="checkbox" id="toggle-manual" class="peer" /> 
                <div class="collapse-title text-xs font-bold text-gray-500 py-3.5 px-4 flex items-center gap-1.5 peer-checked:text-primary transition-colors">
                  <span class="material-icons text-sm">upload_file</span>
                  {{ __('Ada kendala? Unggah bukti manual (Opsional)') }}
                </div>
                
                <div class="collapse-content px-4 pb-4 space-y-4 pt-2 border-t border-slate-100">
                  <p class="text-slate-450 text-[11px] leading-relaxed">
                    {{ __('Gunakan form ini hanya jika paket Anda tidak aktif secara otomatis dalam waktu 5 menit setelah pembayaran.') }}
                  </p>

                  <!-- Screenshot Upload -->
                  <div class="form-control w-full">
                    <label class="label pt-0">
                      <span class="label-text font-bold text-gray-700 text-xs">{{ __('Bukti Pembayaran (Screenshot)') }}</span>
                    </label>
                    
                    <div class="relative flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-4 bg-white hover:bg-slate-100/50 transition-colors cursor-pointer group" id="upload-box">
                      <input type="file" name="image" id="payment-screenshot" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*">
                      
                      <div class="flex flex-col items-center text-center space-y-2 py-3" id="upload-placeholder">
                        <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                          <span class="material-icons text-base">cloud_upload</span>
                        </div>
                        <div>
                          <p class="text-xs font-semibold text-gray-700">{{ __('Pilih berkas bukti transfer') }}</p>
                          <p class="text-[10px] text-gray-400 mt-0.5">{{ __('Mendukung JPG, PNG, JPEG (Maks. 2MB)') }}</p>
                        </div>
                      </div>

                      <!-- Image Preview Container -->
                      <div class="hidden w-full max-w-[150px] aspect-[3/4] relative rounded-lg overflow-hidden border border-slate-200 shadow-md z-0" id="preview-container">
                        <img id="preview-image" src="#" alt="Screenshot Bukti Transfer" class="w-full h-full object-cover">
                        <button type="button" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 shadow hover:bg-red-650 transition-colors z-20" id="remove-preview-btn">
                          <span class="material-icons text-[10px]">close</span>
                        </button>
                      </div>
                    </div>

                    @error('image')
                      <p class="text-xs text-error font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                  </div>

                  <!-- Comment Area -->
                  <div class="form-control w-full">
                    <label class="label">
                      <span class="label-text font-bold text-gray-700 text-xs">{{ __('Catatan Pembayaran') }}</span>
                    </label>
                    <textarea name="comment" class="textarea textarea-bordered h-20 rounded-2xl text-xs bg-white" placeholder="Contoh: Transfer via GoPay atas nama Budi">{{ old('comment') }}</textarea>
                    @error('comment')
                      <p class="text-xs text-error font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('payment-screenshot');
    const uploadBox = document.getElementById('upload-box');
    const placeholder = document.getElementById('upload-placeholder');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    const removeBtn = document.getElementById('remove-preview-btn');
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submit-btn');

    fileInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
          alert('Ukuran file melebihi batas maksimum 2MB.');
          fileInput.value = '';
          return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
          previewImage.src = e.target.result;
          placeholder.classList.add('hidden');
          previewContainer.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
      }
    });

    removeBtn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      fileInput.value = '';
      previewImage.src = '#';
      previewContainer.classList.add('hidden');
      placeholder.classList.remove('hidden');
    });

    if (form && submitBtn) {
      form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        const hasFile = fileInput.files && fileInput.files.length > 0;
        submitBtn.innerHTML = hasFile 
          ? '<span class="loading loading-spinner loading-xs"></span> Mengirim Bukti Manual...' 
          : '<span class="loading loading-spinner loading-xs"></span> Memproses Konfirmasi...';
      });
    }
  });
</script>
@endsection
