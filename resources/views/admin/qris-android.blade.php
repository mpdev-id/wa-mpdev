@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title' => __('QRIS Android Webhook')])
@endsection

@section('content')
<style>
  .status-dot {
    width: 10px; height: 10px; border-radius: 50%; display: inline-block;
    animation: pulse-dot 2s ease-in-out infinite;
  }
  .status-dot.online  { background: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.25); }
  .status-dot.offline { background: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,0.25); }
  @keyframes pulse-dot {
    0%, 100% { transform: scale(1); opacity: 1; }
    50%       { transform: scale(1.3); opacity: .7; }
  }
  .token-field { font-family: 'Courier New', monospace; letter-spacing: 0.04em; font-size: .85rem; }
  .step-badge  { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center;
                  justify-content: center; font-weight: 800; font-size: .75rem; flex-shrink: 0; }
</style>

{{-- Alerts --}}
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
    <i class="fi fi-rs-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="row g-4">

  {{-- ══════════ STATUS CARD ══════════ --}}
  <div class="col-12">
    <div class="card border-0 shadow" style="border-radius: 1.5rem; overflow:hidden;">
      <div class="card-body d-flex align-items-center gap-4 p-4"
           style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="p-3 rounded-3" style="background: rgba(255,255,255,.08);">
          <i class="fi fi-rs-smartphone text-white" style="font-size:2rem;"></i>
        </div>
        <div class="flex-grow-1">
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="status-dot {{ $deviceOnline ? 'online' : 'offline' }}"></span>
            <span class="text-white fw-bold fs-5">
              Perangkat Android
              <span class="{{ $deviceOnline ? 'text-success' : 'text-danger' }}">
                {{ $deviceOnline ? '● Online' : '● Offline' }}
              </span>
            </span>
          </div>
          <div class="text-white-50 small">
            Ping terakhir: <strong class="text-white">{{ $lastPingDiff }}</strong>
            &nbsp;·&nbsp; Webhook aktif dan siap menerima notifikasi QRIS
          </div>
        </div>
        <a href="{{ route('admin.qris.android.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
          <i class="fi fi-rs-refresh me-1"></i> Refresh
        </a>
      </div>
    </div>
  </div>

  {{-- ══════════ LEFT: SETUP GUIDE ══════════ --}}
  <div class="col-xl-7">
    <div class="card border-0 shadow h-100" style="border-radius: 1.5rem;">
      <div class="card-header border-0 bg-white pt-4 px-4 pb-0">
        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
          <span class="badge bg-primary rounded-pill p-2"><i class="fi fi-rs-book-alt"></i></span>
          Panduan Setup Android
        </h5>
      </div>
      <div class="card-body px-4 pb-4">

        <div class="alert alert-warning border-0 rounded-3 small mb-4" role="alert">
          <i class="fi fi-rs-info me-1"></i>
          Gunakan aplikasi <a href="https://play.google.com/store/apps/details?id=com.llamalab.automate" target="_blank" rel="noopener noreferrer"><strong>Automate</strong></a> atau <a href="https://play.google.com/store/apps/details?id=net.dinglisch.android.taskerm" target="_blank" rel="noopener noreferrer"><strong>Tasker</strong></a> di Android untuk
          mengirim notifikasi transfer masuk ke webhook ini secara otomatis.
        </div>

        {{-- Steps --}}
        <div class="d-flex flex-column gap-3">

          <div class="d-flex gap-3 align-items-start">
            <div class="step-badge bg-primary text-white">1</div>
            <div>
              <div class="fw-bold small">Install Aplikasi di HP Android Anda</div>
              <p class="small text-muted mb-0">
                Download <a href="https://play.google.com/store/apps/details?id=com.llamalab.automate" target="_blank" rel="noopener noreferrer" class="fw-bold text-decoration-underline">Automate (Gratis)</a> atau <a href="https://play.google.com/store/apps/details?id=net.dinglisch.android.taskerm" target="_blank" rel="noopener noreferrer" class="fw-bold text-decoration-underline">Tasker (Berbayar)</a> dari Google Play Store.
                Keduanya mampu membaca notifikasi e-wallet dan mengirim HTTP request ke server.
              </p>
            </div>
          </div>

          <div class="d-flex gap-3 align-items-start">
            <div class="step-badge bg-primary text-white">2</div>
            <div>
              <div class="fw-bold small">Buat Flow / Task Pembaca Notifikasi</div>
              <p class="small text-muted mb-0">
                Buat automation yang terpicu saat ada notifikasi masuk dari aplikasi e-wallet
                (GoPay, OVO, DANA, BCA, dll). Filter notifikasi yang mengandung kata
                <code>berhasil</code>, <code>masuk</code>, atau <code>diterima</code>.
              </p>
            </div>
          </div>

          <div class="d-flex gap-3 align-items-start">
            <div class="step-badge bg-primary text-white">3</div>
            <div>
              <div class="fw-bold small">Kirim HTTP POST ke Webhook URL Ini</div>
              <p class="small text-muted mb-0">
                Di dalam automation, tambahkan aksi <strong>HTTP Request</strong> dengan:
              </p>
                <div class="mt-2 p-3 rounded-3 small" style="background:#f8fafc; border: 1px solid #e2e8f0;">
                <div><span class="text-muted">Method:</span> <code>POST</code></div>
                <div><span class="text-muted">URL:</span>
                  <code class="token-field text-primary">{{ $webhookUrl }}</code>
                </div>
                <div class="mt-1"><span class="text-muted">Headers:</span></div>
                <div class="ps-2"><code>X-Auth-Token: {{ $authToken ? $maskedToken . ' (salin dari panel token ↓)' : '[generate token dulu ↓]' }}</code></div>
                <div class="ps-2"><code>Content-Type: application/json</code></div>
                <div class="mt-1"><span class="text-muted">Body (JSON):</span></div>
                <pre class="mb-0 small" style="color:#334155;">{
  "amount": "[nominal dari teks notifikasi]",
  "description": "[isi notifikasi lengkap]",
  "source": "android-notification",
  "app": "[nama aplikasi e-wallet]"
}</pre>
              </div>
            </div>
          </div>

          <div class="d-flex gap-3 align-items-start">
            <div class="step-badge bg-success text-white">4</div>
            <div>
              <div class="fw-bold small">Selesai — Sistem Aktif Otomatis</div>
              <p class="small text-muted mb-0">
                Setiap ada transfer masuk ke rekening QRIS Anda, webhook akan menerima data
                dan secara otomatis mencocokkan nominal dengan order yang pending, lalu
                mengaktifkan langganan pelanggan.
              </p>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>

  {{-- ══════════ RIGHT: CONFIG ══════════ --}}
  <div class="col-xl-5 d-flex flex-column gap-4">

    {{-- Webhook URL --}}
    <div class="card border-0 shadow" style="border-radius: 1.5rem;">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="fi fi-rs-link text-primary"></i> Webhook URL
        </h6>
        <div class="input-group">
          <input type="text" class="form-control token-field small" id="webhookUrl"
                 value="{{ $webhookUrl }}" readonly>
          <button class="btn btn-outline-primary" onclick="copyText('webhookUrl')" title="Salin URL">
            <i class="fi fi-rs-copy"></i>
          </button>
        </div>
        <div class="text-muted small mt-2">
          <i class="fi fi-rs-shield-check text-success me-1"></i>
          Endpoint ini hanya menerima POST request dengan token autentikasi yang valid.
        </div>
      </div>
    </div>

    {{-- Auth Token --}}
    <div class="card border-0 shadow" style="border-radius: 1.5rem;">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="fi fi-rs-key text-warning"></i> Token Autentikasi
          <span class="badge bg-danger rounded-pill ms-1" style="font-size:.65rem;">RAHASIA</span>
        </h6>

        @if($authToken)
          {{-- Hidden input holds the REAL token (not rendered in visible HTML body) --}}
          <input type="hidden" id="authTokenFull" value="{{ $authToken }}">

          {{-- Visible field shows MASKED version --}}
          <div class="input-group mb-2">
            <input type="text" class="form-control token-field small bg-light" id="authTokenDisplay"
                   value="{{ $maskedToken }}" readonly title="Klik Tampilkan untuk melihat token penuh">
            <button class="btn btn-outline-secondary" type="button"
                    onclick="toggleReveal()" id="btnReveal" title="Tampilkan / Sembunyikan">
              <i class="fi fi-rs-eye" id="eyeIcon"></i>
            </button>
            <button class="btn btn-outline-primary" type="button"
                    onclick="copyFullToken()" title="Salin Token">
              <i class="fi fi-rs-copy" id="copyIcon"></i>
            </button>
          </div>

          <div class="alert alert-warning border-0 rounded-3 small mb-3 py-2" role="alert">
            <i class="fi fi-rs-shield-exclamation me-1"></i>
            <strong>Jaga kerahasiaan token ini.</strong> Jangan bagikan ke siapapun.
            Token hanya dibutuhkan oleh aplikasi Automate/Tasker di HP Android Anda.
          </div>
          <div class="alert alert-info border-0 rounded-3 small mb-3 py-2" role="alert">
            <i class="fi fi-rs-info me-1"></i>
            Sertakan token ini di header <code>X-Auth-Token</code> setiap request dari Android.
          </div>
        @else
          <div class="alert alert-warning border-0 rounded-3 small mb-3 py-2" role="alert">
            <i class="fi fi-rs-exclamation me-1"></i>
            Belum ada token. Generate token baru di bawah ini.
          </div>
        @endif

        <form method="POST" action="{{ route('admin.qris.android.test') }}">
          @csrf
          <button type="submit" class="btn btn-warning w-100 fw-bold"
                  onclick="return confirm('Generate token baru? Token lama tidak akan berlaku lagi dan Anda harus update di aplikasi Android.')">
            <i class="fi fi-rs-refresh me-1"></i>
            {{ $authToken ? 'Generate Ulang Token' : 'Generate Token Sekarang' }}
          </button>
        </form>
      </div>
    </div>

  </div>

  {{-- ══════════ RECENT TRANSACTIONS ══════════ --}}
  <div class="col-12">
    <div class="card border-0 shadow" style="border-radius: 1.5rem;">
      <div class="card-header border-0 bg-white pt-4 px-4 pb-2">
        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
          <span class="badge bg-success rounded-pill p-2"><i class="fi fi-rs-check-circle"></i></span>
          10 Transaksi QRIS Terakhir (Terverifikasi Otomatis)
        </h5>
      </div>
      <div class="card-body p-0">
        @if($recentOrders->isEmpty())
          <div class="text-center py-5 text-muted">
            <i class="fi fi-rs-receipt" style="font-size:2.5rem; opacity:.3;"></i>
            <p class="mt-3 small">Belum ada transaksi QRIS yang terverifikasi otomatis.</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:.85rem;">
              <thead class="table-light">
                <tr>
                  <th class="ps-4 py-3">Order #</th>
                  <th>Pelanggan</th>
                  <th>Paket</th>
                  <th>Nominal</th>
                  <th>Waktu</th>
                  <th class="pe-4">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($recentOrders as $order)
                <tr>
                  <td class="ps-4 fw-bold text-primary">{{ $order->invoice ?? '#'.$order->id }}</td>
                  <td>{{ $order->user->name ?? '-' }}</td>
                  <td>{{ $order->plan->name ?? '-' }}</td>
                  <td class="fw-bold">{{ amount_format($order->amount, 'icon') }}</td>
                  <td class="text-muted">{{ $order->updated_at->diffForHumans() }}</td>
                  <td class="pe-4">
                    <span class="badge bg-success rounded-pill px-3">
                      <i class="fi fi-rs-check me-1"></i> Aktif
                    </span>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>

</div>

<script>
// Copy the visible URL field
function copyText(id) {
  const el = document.getElementById(id);
  navigator.clipboard.writeText(el.value).then(() => {
    const btn = el.nextElementSibling;
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="fi fi-rs-check"></i>';
    btn.classList.add('btn-success');
    setTimeout(() => { btn.innerHTML = orig; btn.classList.remove('btn-success'); }, 1500);
  });
}

// Copy full token from hidden input (not from visible masked display)
function copyFullToken() {
  const fullToken = document.getElementById('authTokenFull').value;
  navigator.clipboard.writeText(fullToken).then(() => {
    const btn = document.getElementById('copyIcon');
    const orig = btn.className;
    btn.className = 'fi fi-rs-check';
    btn.parentElement.classList.add('btn-success');
    setTimeout(() => {
      btn.className = orig;
      btn.parentElement.classList.remove('btn-success');
    }, 1500);
  });
}

// Toggle reveal/hide full token in the visible input
function toggleReveal() {
  const display = document.getElementById('authTokenDisplay');
  const full    = document.getElementById('authTokenFull');
  const eye     = document.getElementById('eyeIcon');
  const showing = display.value === full.value;

  if (showing) {
    display.value   = '{{ $maskedToken }}';
    eye.className   = 'fi fi-rs-eye';
  } else {
    display.value   = full.value;
    eye.className   = 'fi fi-rs-eye-crossed';
  }
}

// Auto-hide token after 30 seconds of inactivity
@if($authToken)
let hideTimer = null;
document.getElementById('authTokenDisplay')?.addEventListener('click', () => {
  clearTimeout(hideTimer);
  hideTimer = setTimeout(() => {
    const d = document.getElementById('authTokenDisplay');
    const f = document.getElementById('authTokenFull');
    if (d && d.value === f?.value) {
      d.value = '{{ $maskedToken }}';
      const eye = document.getElementById('eyeIcon');
      if (eye) eye.className = 'fi fi-rs-eye';
    }
  }, 30000);
});
@endif
</script>
@endsection
