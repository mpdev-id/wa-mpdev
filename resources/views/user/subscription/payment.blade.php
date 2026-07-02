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
      <h1 class="text-xl md:text-2xl break-words md:text-3xl break-words font-black text-base-content tracking-tight">{{ __('Selesaikan Pembayaran') }}</h1>
      <p class="text-base-content/60 mt-1 text-sm md:text-base">{{ __('Pindai kode QRIS dan unggah bukti transfer Anda untuk mengaktifkan langganan') }}</p>
    </div>
    <div class="flex items-center gap-2">
      @if(isset($order))
        <form method="POST" action="{{ route('user.subscription.cancel', $order->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan tagihan ini?');" class="inline">
          @csrf
          <button type="submit" class="btn btn-error btn-outline gap-2 shadow-sm rounded-full btn-sm md:btn-md">
            <span class="material-icons text-sm">cancel</span>
            <span>{{ __('Batalkan Tagihan') }}</span>
          </button>
        </form>
      @endif
      <a href="{{ route('user.subscription.index') }}" class="btn btn-ghost border-base-300 hover:bg-base-200 gap-2 shadow-sm rounded-full btn-sm md:btn-md">
        <span class="material-icons text-sm">arrow_back</span>
        <span>{{ __('Kembali') }}</span>
      </a>
    </div>
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
              <div class="w-full max-w-[280px] aspect-square relative rounded-2xl overflow-hidden border-4 border-slate-100 shadow-lg flex items-center justify-center bg-white p-2">
                <!-- Outer glowing border corners -->
                <div class="absolute top-0 left-0 w-6 h-6 border-t-4 border-l-4 border-primary rounded-tl-lg z-20"></div>
                <div class="absolute top-0 right-0 w-6 h-6 border-t-4 border-r-4 border-primary rounded-tr-lg z-20"></div>
                <div class="absolute bottom-0 left-0 w-6 h-6 border-b-4 border-l-4 border-primary rounded-bl-lg z-20"></div>
                <div class="absolute bottom-0 right-0 w-6 h-6 border-b-4 border-r-4 border-primary rounded-br-lg z-20"></div>
                
                <!-- Laser line scanner -->
                <div class="absolute left-0 w-full h-1 bg-gradient-to-r from-transparent via-primary to-transparent shadow-[0_0_10px_#4f46e5] z-10 scanner-line"></div>
                
                <!-- QR Code Container -->
                <div id="qris-qrcode" class="z-0 w-full h-full flex items-center justify-center">
                  @if(empty($dynamic_qris))
                    <img src="{{ asset($qrisGateway->logo) }}" alt="QRIS QR Code" class="w-full h-full object-cover p-2">
                  @endif
                </div>
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
        
        <!-- Status Box (Automatic Verification) -->
        <div id="payment-status-box" class="card bg-white border border-slate-100 shadow-xl rounded-[2rem]">
          <div class="card-body p-6 md:p-8 text-center space-y-4">
            <div class="flex justify-center">
              <div class="relative flex items-center justify-center">
                <span class="animate-ping absolute inline-flex h-12 w-12 rounded-full bg-success/20 opacity-75"></span>
                <div class="relative rounded-full p-4 bg-success/10 text-success">
                  <span class="material-icons text-3xl animate-spin" style="animation-duration: 3s;">sync</span>
                </div>
              </div>
            </div>
            
            <h4 class="font-extrabold text-gray-900 text-lg">{{ __('Menunggu Pembayaran...') }}</h4>
            <p class="text-xs text-gray-500 leading-relaxed max-w-sm mx-auto">
              Silakan lakukan transfer menggunakan QR Code atau nomor rekening di sebelah kiri. Sistem kami sedang memantau pembayaran Anda secara otomatis. Halaman ini akan mendeteksi pembayaran secara real-time.
            </p>

            <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-50 border border-slate-100 text-[10px] text-gray-500 font-bold">
              <span class="inline-block w-2 h-2 rounded-full bg-success animate-pulse"></span>
              {{ __('MONITORING AKTIF') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    @if(isset($order) && $order->status == 2)
      const checkStatusUrl = "{{ route('user.subscription.check-status', $order->id) }}";
      
      const interval = setInterval(async () => {
        try {
          const res = await fetch(checkStatusUrl);
          const data = await res.json();
          if (data.status == 1) {
            clearInterval(interval);
            const statusBox = document.getElementById('payment-status-box');
            if (statusBox) {
              statusBox.innerHTML = `
                <div class="card bg-white border border-slate-100 shadow-xl rounded-[2rem] overflow-hidden">
                  <div class="card-body p-6 md:p-8 text-center space-y-4">
                    <div class="flex justify-center">
                      <div class="rounded-full p-4 bg-success/10 text-success">
                        <span class="material-icons text-4xl animate-bounce">check_circle</span>
                      </div>
                    </div>
                    <h4 class="font-extrabold text-gray-900 text-lg">Pembayaran Sukses!</h4>
                    <p class="text-xs text-gray-555 leading-relaxed max-w-sm mx-auto">
                      Sistem telah berhasil memverifikasi pembayaran Anda secara otomatis. Akun Anda telah diaktifkan. Mengalihkan ke dashboard...
                    </p>
                  </div>
                </div>
              `;
            }
            setTimeout(() => {
              window.location.href = "{{ url('/user/dashboard') }}";
            }, 2500);
          }
        } catch (e) {
          console.error("Gagal memeriksa status pembayaran", e);
        }
      }, 3000);
    @endif
  });
</script>

@if(!empty($dynamic_qris))
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    new QRCode(document.getElementById("qris-qrcode"), {
      text: "{{ $dynamic_qris }}",
      width: 250,
      height: 250,
      colorDark : "#000000",
      colorLight : "#ffffff",
      correctLevel : QRCode.CorrectLevel.M
    });
  });
</script>
@endif
@endsection
