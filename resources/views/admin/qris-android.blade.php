@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title' => __('QRIS Android Webhook')])
@endsection

@section('content')
<style>
  /* ── Status dot ── */
  .status-dot {
    width: 12px; height: 12px; border-radius: 50%; display: inline-block; flex-shrink: 0;
    animation: pulse-dot 2s ease-in-out infinite;
  }
  .status-dot.online  { background: #10b981; box-shadow: 0 0 0 4px rgba(16,185,129,.2); }
  .status-dot.offline { background: #ef4444; box-shadow: 0 0 0 4px rgba(239,68,68,.2); }
  @keyframes pulse-dot {
    0%,100% { transform:scale(1); opacity:1; }
    50%      { transform:scale(1.35); opacity:.7; }
  }

  /* ── Mono font ── */
  .mono { font-family:'Courier New',monospace; letter-spacing:.04em; font-size:.82rem; }

  /* ── Step badge ── */
  .step-num {
    width:32px; height:32px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-weight:800; font-size:.78rem; flex-shrink:0;
    background:linear-gradient(135deg,#6366f1,#8b5cf6);
    color:#fff; box-shadow:0 4px 12px rgba(99,102,241,.35);
  }
  .step-num.done {
    background:linear-gradient(135deg,#10b981,#059669);
    box-shadow:0 4px 12px rgba(16,185,129,.35);
  }

  /* ── Code block ── */
  .code-block {
    background:#0f172a; border-radius:.75rem; padding:1rem 1.25rem;
    font-family:'Courier New',monospace; font-size:.78rem;
    line-height:1.7; overflow-x:auto; color:#94a3b8;
  }
  .code-block .key   { color:#7dd3fc; }
  .code-block .val   { color:#86efac; }
  .code-block .str   { color:#fca5a5; }
  .code-block .cmt   { color:#64748b; }
  .code-block .url   { color:#f0abfc; }

  /* ── Token reveal ── */
  .token-reveal { cursor:pointer; }

  /* ── Status header gradient ── */
  .status-header {
    background:linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#0f172a 100%);
    border-radius:1.25rem; overflow:hidden; position:relative;
  }
  .status-header::before {
    content:''; position:absolute; inset:0;
    background:radial-gradient(ellipse at 70% 50%, rgba(99,102,241,.15) 0%, transparent 70%);
  }

  /* ── Card hover ── */
  .info-card {
    border-radius:1.25rem; border:0; box-shadow:0 2px 16px rgba(0,0,0,.06);
    transition:box-shadow .2s;
  }
  .info-card:hover { box-shadow:0 6px 28px rgba(0,0,0,.1); }

  /* ── Tab pill ── */
  .method-tab {
    display:flex; gap:.5rem; background:#f1f5f9;
    border-radius:.75rem; padding:.35rem; width:fit-content; flex-wrap:wrap;
  }
  .method-tab button {
    border:0; background:transparent; border-radius:.5rem;
    padding:.35rem .85rem; font-size:.8rem; font-weight:600; color:#64748b;
    transition:all .2s; cursor:pointer;
  }
  .method-tab button.active {
    background:#fff; color:#6366f1; box-shadow:0 2px 8px rgba(0,0,0,.1);
  }

  /* ── Test result box ── */
  .test-result {
    display:none; background:#0f172a; border-radius:.75rem;
    padding:1rem; font-family:monospace; font-size:.78rem; color:#94a3b8;
    white-space:pre-wrap; max-height:200px; overflow-y:auto; margin-top:.75rem;
  }
  .test-result.show { display:block; }
  .test-result.ok   { border-left:3px solid #10b981; }
  .test-result.fail { border-left:3px solid #ef4444; }
</style>

{{-- ── Flash alerts ── --}}
@if(session('success'))
  <div class="alert border-0 rounded-3 mb-4 py-3 px-4 d-flex align-items-center gap-3"
       style="background:#ecfdf5; color:#065f46;">
    <i class="fi fi-rs-check-circle fs-5"></i>
    <div>{{ session('success') }}</div>
  </div>
@endif
@if(session('error'))
  <div class="alert border-0 rounded-3 mb-4 py-3 px-4 d-flex align-items-center gap-3"
       style="background:#fef2f2; color:#991b1b;">
    <i class="fi fi-rs-exclamation fs-5"></i>
    <div>{{ session('error') }}</div>
  </div>
@endif

<div class="d-flex flex-column gap-4">

  {{-- ══════════ 1. STATUS BAR ══════════ --}}
  <div class="status-header p-4">
    <div class="row align-items-center g-3 position-relative">
      <div class="col-md-7 d-flex align-items-center gap-4">
        <div class="p-3 rounded-3 d-flex align-items-center justify-content-center"
             style="background:rgba(255,255,255,.08); width:56px; height:56px;">
          <i class="fi fi-rs-smartphone text-white" style="font-size:1.6rem;"></i>
        </div>
        <div>
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="status-dot {{ $deviceOnline ? 'online' : 'offline' }}"></span>
            <span class="fw-bold text-white fs-6">
              Perangkat Android —
              <span class="{{ $deviceOnline ? 'text-success' : 'text-danger' }}">
                {{ $deviceOnline ? 'Online' : 'Offline' }}
              </span>
            </span>
          </div>
          <div class="small" style="color:rgba(255,255,255,.55);">
            Ping terakhir:
            <strong class="text-white">{{ $lastPingDiff }}</strong>
            &nbsp;·&nbsp; Endpoint: <span class="mono" style="color:#a5b4fc;">{{ $webhookUrl }}</span>
          </div>
        </div>
      </div>
      <div class="col-md-5 d-flex justify-content-md-end gap-2 flex-wrap">
        {{-- Ping test button --}}
        <button class="btn btn-sm btn-outline-light rounded-pill px-3" id="btnPingTest">
          <i class="fi fi-rs-signal-stream me-1"></i> Tes Koneksi
        </button>
        <a href="{{ route('admin.qris.android.index') }}" class="btn btn-sm rounded-pill px-3"
           style="background:rgba(255,255,255,.12); color:#fff; border:1px solid rgba(255,255,255,.2);">
          <i class="fi fi-rs-refresh me-1"></i> Refresh
        </a>
      </div>
    </div>

    {{-- Ping result inline --}}
    <div id="pingResult" class="mt-3 small rounded-3 px-4 py-2 d-none"
         style="background:rgba(255,255,255,.08); color:#a5b4fc; font-family:monospace;"></div>
  </div>

  {{-- ══════════ 2. TWO COLUMNS: SETUP + CONFIG ══════════ --}}
  <div class="row g-4">

    {{-- ── LEFT: Setup Guide ── --}}
    <div class="col-xl-7">
      <div class="info-card card h-100">
        <div class="card-header border-0 bg-white pt-4 px-4 pb-2">
          <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
            <span class="badge rounded-3 p-2" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
              <i class="fi fi-rs-book-alt text-white"></i>
            </span>
            Panduan Setup Penerima Notifikasi
          </h5>
          <p class="text-muted small mt-1 mb-0">Ikuti langkah berikut untuk menghubungkan HP Android ke sistem QRIS.</p>
        </div>

        <div class="card-body px-4 pb-4">

          {{-- Method tabs --}}
          <div class="method-tab mb-4" id="methodTabs">
            <button class="active" onclick="showMethod('wasapi', this)">
              <i class="fi fi-rs-smartphone me-1"></i> Aplikasi WASAPI (Rekomendasi)
            </button>
            <button onclick="showMethod('automate', this)">
              <i class="fi fi-rs-settings me-1"></i> Automate (Gratis)
            </button>
            <button onclick="showMethod('curl', this)">
              <i class="fi fi-rs-terminal me-1"></i> cURL / API Manual
            </button>
          </div>

          {{-- === WASAPI NATIVE APP GUIDE === --}}
          <div id="method-wasapi" class="method-content">
            <div class="d-flex flex-column gap-4">

              <div class="d-flex gap-3">
                <div class="step-num">1</div>
                <div class="w-100">
                  <div class="fw-semibold small mb-1">Download & Install Aplikasi WASAPI Receiver</div>
                  <p class="small text-muted mb-2">
                    Unduh dan pasang aplikasi Android kustom <strong>WASAPI Receiver</strong> di HP Anda.
                  </p>
                  @if(file_exists(public_path('uploads/wasapi-receiver.apk')))
                    <a href="{{ asset('uploads/wasapi-receiver.apk') }}"
                       class="btn btn-sm btn-primary rounded-pill px-3" download>
                      <i class="fi fi-rs-download me-1"></i> Download APK Sekarang
                    </a>
                  @else
                    <div class="alert border-0 rounded-3 small py-2 px-3 mb-0"
                         style="background:#fffbeb; color:#92400e; border-left:3px solid #f59e0b !important;">
                      <i class="fi fi-rs-info me-1"></i>
                      <strong>Petunjuk Server:</strong> Upload file <code>app-debug.apk</code> yang baru dicompile ke direktori server Anda di: <code>public/uploads/wasapi-receiver.apk</code> agar tombol download aktif di halaman ini.
                    </div>
                  @endif
                </div>
              </div>

              <div class="d-flex gap-3">
                <div class="step-num">2</div>
                <div>
                  <div class="fw-semibold small mb-1">Aktifkan Izin Notifikasi</div>
                  <p class="small text-muted mb-0">
                    Buka aplikasi di HP, ketuk tombol <strong>"GRANT PERMISSION"</strong> (atau Izin Notifikasi), lalu pilih <strong>WHASAPI Receiver</strong> dan berikan izin akses pembacaan notifikasi.
                  </p>
                </div>
              </div>

              <div class="d-flex gap-3">
                <div class="step-num">3</div>
                <div>
                  <div class="fw-semibold small mb-1">Konfigurasi Webhook & Token</div>
                  <p class="small text-muted mb-2">
                    Salin <strong>Webhook URL</strong> dan <strong>Token Autentikasi</strong> dari panel kanan, lalu masukkan ke dalam kolom input di aplikasi HP.
                  </p>
                  <p class="small text-muted mb-0">
                    Aktifkan switch <strong>DANA</strong> (atau e-wallet lain yang ingin Anda terima otomatis), lalu klik tombol <strong>"SAVE CONFIG"</strong>.
                  </p>
                </div>
              </div>

              <div class="d-flex gap-3">
                <div class="step-num done">4</div>
                <div>
                  <div class="fw-semibold small mb-1">✅ Tes Konektivitas</div>
                  <p class="small text-muted mb-0">
                    Ketuk tombol <strong>"TEST WEBHOOK"</strong> di HP Anda. Jika respon log di HP menampilkan sukses, selamat! HP Anda sudah terhubung ke sistem.
                  </p>
                </div>
              </div>

            </div>
          </div>

          {{-- === AUTOMATE GUIDE === --}}
          <div id="method-automate" class="method-content d-none">
            <div class="d-flex flex-column gap-4">

              <div class="d-flex gap-3">
                <div class="step-num">1</div>
                <div>
                  <div class="fw-semibold small mb-1">Download & Install Automate</div>
                  <p class="small text-muted mb-2">
                    Download aplikasi <strong>Automate by LlamaLab</strong> dari Google Play Store (gratis).
                    Berikan izin akses notifikasi setelah diinstall.
                  </p>
                  <a href="https://play.google.com/store/apps/details?id=com.llamalab.automate"
                     target="_blank" rel="noopener"
                     class="btn btn-sm rounded-pill px-3"
                     style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; font-size:.8rem;">
                    <i class="fi fi-rs-arrow-up-right-from-square me-1"></i> Buka Google Play
                  </a>
                </div>
              </div>

              <div class="d-flex gap-3">
                <div class="step-num">2</div>
                <div class="w-100">
                  <div class="fw-semibold small mb-1">Buat Flow Baru di Automate</div>
                  <p class="small text-muted mb-2">
                    Buat Flow dengan block berikut (urutan dari atas ke bawah):
                  </p>
                  <div class="d-flex flex-column gap-2">
                    <div class="rounded-3 px-3 py-2 d-flex align-items-start gap-2"
                         style="background:#f8fafc; border:1px solid #e2e8f0;">
                      <span class="badge rounded-pill mt-1" style="background:#6366f1; font-size:.6rem;">A</span>
                      <div class="small">
                        <strong>Notification received</strong> — trigger saat ada notifikasi masuk dari GoPay / OVO / DANA / ShopeePay / BCA
                      </div>
                    </div>
                    <div class="rounded-3 px-3 py-2 d-flex align-items-start gap-2"
                         style="background:#f8fafc; border:1px solid #e2e8f0;">
                      <span class="badge rounded-pill mt-1" style="background:#6366f1; font-size:.6rem;">B</span>
                      <div class="small">
                        <strong>Text match</strong> — filter notifikasi yang mengandung kata: <code>masuk</code> / <code>diterima</code> / <code>berhasil</code> / <code>Rp</code>
                      </div>
                    </div>
                    <div class="rounded-3 px-3 py-2 d-flex align-items-start gap-2"
                         style="background:#f8fafc; border:1px solid #e2e8f0;">
                      <span class="badge rounded-pill mt-1" style="background:#6366f1; font-size:.6rem;">C</span>
                      <div class="small">
                        <strong>HTTP Request</strong> — kirim POST ke webhook URL (lihat konfigurasi ↓)
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex gap-3">
                <div class="step-num">3</div>
                <div class="w-100">
                  <div class="fw-semibold small mb-1">Konfigurasi Block HTTP Request</div>
                  <p class="small text-muted mb-1">Isi field pada block <strong>HTTP Request</strong> di Automate:</p>
                  <div class="code-block">
<span class="cmt">// Method</span>
<span class="key">Method    </span>: <span class="val">POST</span>

<span class="cmt">// URL</span>
<span class="key">URL       </span>: <span class="url">{{ $webhookUrl }}</span>

<span class="cmt">// Headers (tap + untuk tambah header)</span>
<span class="key">X-Auth-Token </span>: <span class="str">{{ $authToken ? '[token dari panel ↓]' : '[generate token dulu ↓]' }}</span>
<span class="key">Content-Type </span>: <span class="val">application/json</span>

<span class="cmt">// Body — pilih format JSON</span>
{
  <span class="key">"amount"</span>     : <span class="str">"[nominal dari notif, contoh: 25123]"</span>,
  <span class="key">"description"</span>: <span class="str">"@{{noti_text}}"</span>,
  <span class="key">"source"</span>     : <span class="str">"android-automate"</span>
}</div>
                  <div class="alert border-0 rounded-3 small mt-3 py-2 px-3"
                       style="background:#fffbeb; color:#92400e; border-left:3px solid #f59e0b !important;">
                    <i class="fi fi-rs-info me-1"></i>
                    <strong>Tip Automate:</strong> Gunakan variable <code>@{{noti_text}}</code> untuk menangkap isi notifikasi otomatis.
                  </div>
                </div>
              </div>

            </div>
          </div>

          {{-- === CURL GUIDE === --}}
          <div id="method-curl" class="method-content d-none">
            <p class="small text-muted mb-3">Test webhook langsung dari terminal atau tools seperti Postman / Insomnia:</p>
            <div class="code-block">
<span class="cmt"># 1. Tes konektivitas (tanpa token)</span>
<span class="val">curl</span> -X GET <span class="url">{{ $pingUrl }}</span>

<span class="cmt"># 2. Kirim notifikasi pembayaran</span>
<span class="val">curl</span> -X POST <span class="url">{{ $webhookUrl }}</span> \
  -H <span class="str">"X-Auth-Token: {{ $authToken ?: 'TOKEN_ANDA' }}"</span> \
  -H <span class="str">"Content-Type: application/json"</span> \
  -d <span class="str">'{
    "amount": 25000,
    "description": "Transfer berhasil Rp 25.000",
    "source": "curl-test"
  }'</span>

<span class="cmt"># 3. Kirim via Authorization Bearer (alternatif)</span>
<span class="val">curl</span> -X POST <span class="url">{{ $webhookUrl }}</span> \
  -H <span class="str">"Authorization: Bearer {{ $authToken ?: 'TOKEN_ANDA' }}"</span> \
  -H <span class="str">"Content-Type: application/json"</span> \
  -d <span class="str">'{"amount": 25000, "description": "Test"}'</span>
            </div>
            <div class="alert border-0 rounded-3 small mt-3 py-2 px-3"
                 style="background:#f0f9ff; color:#0c4a6e; border-left:3px solid #38bdf8 !important;">
              <i class="fi fi-rs-shield-check me-1"></i>
              Token diterima via: <code>X-Auth-Token</code> header, <code>Authorization: Bearer</code>, atau field <code>token</code> di body JSON.
            </div>
          </div>

        </div>
      </div>
    </div>

    {{-- ── RIGHT: Config Panel ── --}}
    <div class="col-xl-5 d-flex flex-column gap-4">

      {{-- Webhook URL --}}
      <div class="info-card card">
        <div class="card-body p-4">
          <h6 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <i class="fi fi-rs-link" style="color:#6366f1;"></i> Webhook URL
          </h6>
          <p class="small text-muted mb-3">URL endpoint yang menerima notifikasi dari HP Android.</p>
          <div class="input-group">
            <input type="text" class="form-control mono small" id="webhookUrl"
                   value="{{ $webhookUrl }}" readonly>
            <button class="btn btn-outline-primary" onclick="copyField('webhookUrl', this)" title="Salin">
              <i class="fi fi-rs-copy"></i>
            </button>
          </div>

          {{-- Ping URL --}}
          <div class="mt-3">
            <div class="small text-muted mb-1">URL Ping / Tes Koneksi (GET):</div>
            <div class="input-group">
              <input type="text" class="form-control mono small" id="pingUrl"
                     value="{{ $pingUrl }}" readonly>
              <button class="btn btn-outline-secondary" onclick="copyField('pingUrl', this)" title="Salin">
                <i class="fi fi-rs-copy"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- Auth Token --}}
      <div class="info-card card">
        <div class="card-body p-4">
          <h6 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <i class="fi fi-rs-key" style="color:#f59e0b;"></i> Token Autentikasi
            <span class="badge rounded-pill ms-1 text-white" style="background:#ef4444; font-size:.6rem;">RAHASIA</span>
          </h6>
          <p class="small text-muted mb-3">
            Sertakan di header <code>X-Auth-Token</code> setiap request dari Android.
          </p>

          @if($authToken)
            <input type="hidden" id="tokenFull" value="{{ $authToken }}">
            <div class="input-group mb-2">
              <input type="text" class="form-control mono small bg-light" id="tokenDisplay"
                     value="{{ $maskedToken }}" readonly>
              <button class="btn btn-outline-secondary" type="button" onclick="toggleToken()" id="btnEye" title="Tampilkan/Sembunyikan">
                <i class="fi fi-rs-eye" id="eyeIcon"></i>
              </button>
              <button class="btn btn-outline-primary" type="button" onclick="copyToken()" title="Salin token">
                <i class="fi fi-rs-copy" id="copyTokenIcon"></i>
              </button>
            </div>
            <div class="small text-muted mt-1">
              <i class="fi fi-rs-shield-exclamation text-warning me-1"></i>
              Jangan bagikan token ini. Auto-tersembunyi setelah 30 detik.
            </div>
          @else
            <div class="rounded-3 p-3 text-center mb-3" style="background:#fffbeb; border:1px dashed #f59e0b;">
              <i class="fi fi-rs-exclamation" style="color:#f59e0b; font-size:1.5rem;"></i>
              <div class="small mt-1 text-muted">Belum ada token. Generate token baru untuk mulai menggunakan webhook.</div>
            </div>
          @endif

          <form method="POST" action="{{ route('admin.qris.android.test') }}">
            @csrf
            <button type="submit" class="btn w-100 fw-bold rounded-3 mt-1"
                    style="background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; border:0;"
                    onclick="return confirm('{{ $authToken ? 'Generate token baru? Token lama tidak berlaku lagi.' : 'Generate token sekarang?' }}')">
              <i class="fi fi-rs-refresh me-2"></i>
              {{ $authToken ? 'Generate Ulang Token' : 'Generate Token Sekarang' }}
            </button>
          </form>
        </div>
      </div>

      {{-- Simulate Webhook --}}
      <div class="info-card card">
        <div class="card-body p-4">
          <h6 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <i class="fi fi-rs-paper-plane" style="color:#10b981;"></i> Simulasi Webhook
          </h6>
          <p class="small text-muted mb-3">
            Kirim simulasi pembayaran untuk memverifikasi endpoint berfungsi.
            @if($pendingOrders->isNotEmpty())
              <br>Order pending yang ada:
              @foreach($pendingOrders as $po)
                <span class="badge rounded-pill me-1 mt-1 fw-normal"
                      style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0;">
                  Rp {{ number_format($po->amount, 0, ',', '.') }}
                </span>
              @endforeach
            @endif
          </p>
          <form method="POST" action="{{ route('admin.qris.android.simulate') }}">
            @csrf
            <div class="input-group">
              <span class="input-group-text text-muted small">Rp</span>
              <input type="number" name="amount" class="form-control" placeholder="Nominal, contoh: 25123" min="100"
                     @if($pendingOrders->isNotEmpty()) value="{{ $pendingOrders->first()->amount }}" @endif>
              <button type="submit" class="btn fw-bold"
                      style="background:linear-gradient(135deg,#10b981,#059669); color:#fff; border:0;"
                      @if(!$authToken) disabled title="Generate token terlebih dahulu" @endif>
                <i class="fi fi-rs-paper-plane me-1"></i> Kirim
              </button>
            </div>
            @if(!$authToken)
              <div class="small text-danger mt-1"><i class="fi fi-rs-exclamation me-1"></i>Generate token terlebih dahulu.</div>
            @endif
          </form>
        </div>
      </div>

    </div>
  </div>

  {{-- ══════════ 3. RECENT TRANSACTIONS ══════════ --}}
  <div class="info-card card">
    <div class="card-header border-0 bg-white pt-4 px-4 pb-2 d-flex align-items-center justify-content-between">
      <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
        <span class="badge rounded-3 p-2" style="background:linear-gradient(135deg,#10b981,#059669);">
          <i class="fi fi-rs-check-circle text-white"></i>
        </span>
        Transaksi QRIS Terverifikasi Otomatis
      </h5>
      <span class="badge rounded-pill px-3" style="background:#ecfdf5; color:#065f46;">
        {{ $recentOrders->count() }} transaksi
      </span>
    </div>
    <div class="card-body p-0">
      @if($recentOrders->isEmpty())
        <div class="text-center py-5 text-muted">
          <i class="fi fi-rs-receipt" style="font-size:3rem; opacity:.2;"></i>
          <p class="mt-3 small mb-0">Belum ada transaksi yang diverifikasi otomatis via webhook.</p>
          <p class="small">Gunakan tombol <strong>Simulasi Webhook</strong> di atas untuk mencoba.</p>
        </div>
      @else
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size:.85rem;">
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
                <td class="ps-4 fw-bold" style="color:#6366f1;">{{ $order->invoice_no ?? '#'.$order->id }}</td>
                <td>{{ $order->user->name ?? '-' }}</td>
                <td>{{ $order->plan->title ?? '-' }}</td>
                <td class="fw-bold">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                <td class="text-muted">{{ $order->updated_at->diffForHumans() }}</td>
                <td class="pe-4">
                  <span class="badge rounded-pill px-3" style="background:#ecfdf5; color:#065f46;">
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

<script>
// ── Tab switching ──────────────────────────────────────────────────
function showMethod(id, btn) {
  document.querySelectorAll('.method-content').forEach(el => el.classList.add('d-none'));
  document.querySelector('#method-' + id).classList.remove('d-none');
  document.querySelectorAll('#methodTabs button').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}

// ── Copy field ─────────────────────────────────────────────────────
function copyField(id, btn) {
  const val = document.getElementById(id).value;
  navigator.clipboard.writeText(val).then(() => {
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="fi fi-rs-check"></i>';
    btn.classList.replace('btn-outline-primary', 'btn-success');
    btn.classList.replace('btn-outline-secondary', 'btn-success');
    setTimeout(() => {
      btn.innerHTML = orig;
      btn.classList.replace('btn-success', 'btn-outline-primary');
      btn.classList.replace('btn-success', 'btn-outline-secondary');
    }, 1500);
  });
}

// ── Token reveal ───────────────────────────────────────────────────
let hideTimer = null;
function toggleToken() {
  const display = document.getElementById('tokenDisplay');
  const full    = document.getElementById('tokenFull');
  const eye     = document.getElementById('eyeIcon');
  if (!display || !full) return;

  const showing = display.value === full.value;
  clearTimeout(hideTimer);

  if (showing) {
    display.value = '{{ $maskedToken }}';
    eye.className = 'fi fi-rs-eye';
  } else {
    display.value = full.value;
    eye.className = 'fi fi-rs-eye-crossed';
    // Auto-hide after 30 seconds
    hideTimer = setTimeout(() => {
      display.value = '{{ $maskedToken }}';
      eye.className = 'fi fi-rs-eye';
    }, 30000);
  }
}

function copyToken() {
  const full = document.getElementById('tokenFull');
  if (!full) return;
  navigator.clipboard.writeText(full.value).then(() => {
    const icon = document.getElementById('copyTokenIcon');
    const btn  = icon.parentElement;
    icon.className = 'fi fi-rs-check';
    btn.classList.replace('btn-outline-primary', 'btn-success');
    setTimeout(() => {
      icon.className = 'fi fi-rs-copy';
      btn.classList.replace('btn-success', 'btn-outline-primary');
    }, 1500);
  });
}

// ── Ping test ──────────────────────────────────────────────────────
document.getElementById('btnPingTest').addEventListener('click', async function() {
  const btn = this;
  const result = document.getElementById('pingResult');

  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menghubungkan...';
  result.classList.remove('d-none');
  result.style.color = '#a5b4fc';
  result.textContent = 'Mengirim ping ke ' + '{{ $pingUrl }}' + ' ...';

  try {
    const res  = await fetch('{{ $pingUrl }}', { method: 'GET' });
    const data = await res.json();
    if (res.ok) {
      result.style.color = '#86efac';
      result.textContent = '✅ Koneksi berhasil! Response: ' + JSON.stringify(data, null, 2);
    } else {
      result.style.color = '#fca5a5';
      result.textContent = '❌ Server merespons dengan error ' + res.status + ': ' + JSON.stringify(data);
    }
  } catch(e) {
    result.style.color = '#fca5a5';
    result.textContent = '❌ Koneksi gagal: ' + e.message;
  }

  btn.disabled = false;
  btn.innerHTML = '<i class="fi fi-rs-signal-stream me-1"></i> Tes Koneksi';
});
</script>

@endsection
