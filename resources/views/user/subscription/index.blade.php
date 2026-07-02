@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Subscription')])
@endsection

@section('content')
<style>
  .premium-plan-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .premium-plan-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  }

  .feature-check {
    background: rgba(16, 185, 129, 0.08);
    color: #10b981;
  }
  .feature-cross {
    background: rgba(239, 68, 68, 0.08);
    color: #ef4444;
  }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
  
  {{-- Header section --}}
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
      <h1 class="text-2xl md:text-3xl font-black text-gray-955 tracking-tight flex items-center gap-2.5">
        <span class="p-2 rounded-2xl bg-primary/10 text-primary flex-none">
          <i class="fi fi-rs-receipt"></i>
        </span>
        {{ __('Subscription & Billing') }}
      </h1>
      <p class="text-sm text-gray-500 mt-1.5">{{ __('Kelola paket berlangganan aktif Anda, batasan pemakaian, dan riwayat tagihan.') }}</p>
    </div>
    <div class="flex items-center gap-3">
      <a href="{{ route('user.subscription.log') }}" class="btn btn-outline border-slate-200 hover:bg-slate-50 text-gray-700 gap-2 rounded-full px-5 shadow-sm btn-sm md:btn-md">
        <span class="material-icons text-sm">receipt_long</span>
        {{ __('Riwayat Invoice') }}
      </a>
    </div>
  </div>

  <!-- Current Plan Card -->
  <div class="card bg-white border border-slate-100 shadow-xl rounded-[2rem] mb-10 overflow-hidden">
    <div class="card-body p-6 md:p-8">
      
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <div class="p-3 rounded-2xl bg-gradient-to-br from-primary/10 to-indigo-500/10 text-primary">
            <span class="material-icons text-2xl">workspace_premium</span>
          </div>
          <div>
            <h2 class="text-lg font-bold text-gray-900">{{ __('Paket Anda Saat Ini') }}</h2>
            <p class="text-xs text-gray-500 mt-0.5">{{ __('Informasi status dan batasan pemakaian akun.') }}</p>
          </div>
        </div>
        
        <div>
          @if($subscription->will_expire && \Carbon\Carbon::parse($subscription->will_expire)->isFuture())
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-success/10 text-success text-xs font-bold border border-success/20">
              <span class="inline-block w-2 h-2 rounded-full bg-success animate-pulse"></span>
              {{ __('Aktif') }}
            </span>
          @elseif($subscription->will_expire)
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-error/10 text-error text-xs font-bold border border-error/20">
              <span class="inline-block w-2 h-2 rounded-full bg-error"></span>
              {{ __('Kedaluwarsa') }}
            </span>
          @else
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-warning/10 text-warning text-xs font-bold border border-warning/20">
              <span class="inline-block w-2 h-2 rounded-full bg-warning"></span>
              {{ __('Belum Ada Paket') }}
            </span>
          @endif
        </div>
      </div>
      
      <div class="h-px bg-slate-100 my-6"></div>
      
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
          <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Nama Paket') }}</p>
          <p class="text-base font-black text-gray-900">{{ $subscription->plan()->first()->title ?? __('Free') }}</p>
        </div>
        
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
          <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Biaya Langganan') }}</p>
          <p class="text-base font-black text-gray-900">
            @if($subscription->plan()->first())
              {{ amount_format($subscription->plan()->first()->price ?? 0) }}<span class="text-xs font-normal text-gray-400">/{{ $subscription->plan()->first()->days ?? 30 }} {{ __('hari') }}</span>
            @else
              {{ __('Gratis') }}
            @endif
          </p>
        </div>
        
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
          <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Masa Berlaku') }}</p>
          <p class="text-base font-black text-gray-900">
            @if($subscription->will_expire)
              {{ \Carbon\Carbon::parse($subscription->will_expire)->format('d M Y') }}
            @else
              {{ __('—') }}
            @endif
          </p>
        </div>
        
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
          <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Sisa Waktu') }}</p>
          <p class="text-base font-black text-gray-900">
            @if($subscription->will_expire && \Carbon\Carbon::parse($subscription->will_expire)->isFuture())
              <span class="text-primary">{{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($subscription->will_expire)) }} {{ __('hari') }}</span>
            @elseif($subscription->will_expire)
              <span class="text-error font-bold">{{ __('Kedaluwarsa') }}</span>
            @else
              {{ __('—') }}
            @endif
          </p>
        </div>
      </div>

      @php
        $planData = $subscription->plan()->first()?->data ?? [];
        if(is_string($planData)) $planData = json_decode($planData, true) ?? [];
      @endphp

      @if(!empty($planData))
      <div class="mt-8 pt-6 border-t border-slate-100">
        <h3 class="text-xs font-black text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-1.5">
          <span class="material-icons text-primary text-sm">checklist</span>
          {{ __('Detail Fitur & Batasan Paket Anda') }}
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
          @foreach($planData as $key => $value)
            <div class="flex items-center gap-2.5 py-2.5 px-4 rounded-xl bg-slate-50 border border-slate-100/60">
              <span class="material-icons text-success text-sm flex-none">check_circle</span>
              <span class="text-xs text-gray-700 font-medium">
                @if(is_bool($value) || $value === 1 || $value === '1')
                  {{ ucwords(str_replace('_', ' ', $key)) }}
                @elseif($value === -1 || $value === '-1')
                  {{ ucwords(str_replace('_', ' ', $key)) }}: <strong class="text-gray-900 font-black">{{ __('Unlimited') }}</strong>
                @else
                  {{ ucwords(str_replace('_', ' ', $key)) }}: <strong class="text-gray-900 font-black">{{ $value }}</strong>
                @endif
              </span>
            </div>
          @endforeach
        </div>
      </div>
      @endif

    </div>
  </div>

  <!-- Available Plans -->
  @if($plans->count() > 0)
  <div id="available-plans" class="scroll-mt-6">
    <div class="text-center max-w-xl mx-auto mb-10">
      <h2 class="text-xl md:text-2xl font-black text-gray-950 tracking-tight">{{ __('Pilihan Paket Yang Tersedia') }}</h2>
      <p class="text-xs md:text-sm text-gray-500 mt-2">{{ __('Pilihlah paket berlangganan terbaik untuk memaksimalkan performa bisnis Anda.') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach($plans as $plan)
      @php
        $isCurrentPlan = ($subscription->plan_id == $plan->id) && $subscription->will_expire && \Carbon\Carbon::parse($subscription->will_expire)->isFuture();
        $features = is_string($plan->data) ? json_decode($plan->data, true) : ($plan->data ?? []);
      @endphp
      <div class="premium-plan-card card bg-white border {{ $plan->is_recommended ? 'border-primary ring-4 ring-primary/5' : 'border-slate-100 shadow-lg' }} rounded-[2.5rem] relative overflow-hidden" style="animation-delay: {{ $loop->index * 0.1 }}s">
        
        @if($plan->is_recommended)
        <div class="absolute top-0 right-0 bg-gradient-to-l from-indigo-600 to-primary text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-bl-3xl shadow-sm z-30">
          {{ __('Populer') }}
        </div>
        @endif

        @if($isCurrentPlan)
        <div class="absolute top-0 left-0 bg-success text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-br-3xl shadow-sm z-30">
          {{ __('Aktif') }}
        </div>
        @endif

        <div class="card-body p-6 md:p-8 flex flex-col h-full">
          <div class="text-center mb-6 {{ $plan->is_recommended || $isCurrentPlan ? 'pt-4' : '' }}">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary/10 to-indigo-600/10 text-primary flex items-center justify-center mx-auto mb-4 shadow-sm">
              @php
                $icon = 'star';
                if (\Illuminate\Support\Str::contains($plan->iconname, 'star')) {
                    $icon = 'star';
                } elseif (\Illuminate\Support\Str::contains($plan->iconname, 'check-circle')) {
                    $icon = 'check_circle';
                } elseif (\Illuminate\Support\Str::contains($plan->iconname, 'angellist')) {
                    $icon = 'bolt';
                } else {
                    $icon = $plan->iconname ?: 'star';
                }
              @endphp
              <span class="material-icons text-2xl">{{ $icon }}</span>
            </div>
            
            <h3 class="text-lg font-black text-gray-950 uppercase tracking-tight">{{ $plan->title }}</h3>
            
            <div class="mt-4 flex items-baseline justify-center gap-1">
              <span class="text-2xl md:text-3xl font-black text-primary tracking-tight">{{ amount_format($plan->price) }}</span>
              <span class="text-gray-400 text-xs font-semibold">/ {{ $plan->days }} {{ __('hari') }}</span>
            </div>
          </div>

          <div class="h-px bg-slate-100 my-4"></div>

          @if(!empty($features))
          <ul class="space-y-3.5 my-6 flex-1">
            @foreach($features as $key => $value)
            <li class="flex items-start gap-2.5 text-xs text-gray-700">
              @if($value === false || $value === 0 || $value === '0')
                <span class="feature-cross rounded-full p-0.5 flex items-center justify-center flex-none">
                  <span class="material-icons text-[10px]">close</span>
                </span>
                <span class="text-gray-400 line-through font-medium">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
              @else
                <span class="feature-check rounded-full p-0.5 flex items-center justify-center flex-none">
                  <span class="material-icons text-[10px]">check</span>
                </span>
                <span class="font-medium text-gray-800">
                  @if(is_bool($value) || $value === 1 || $value === '1')
                    {{ ucwords(str_replace('_', ' ', $key)) }}
                  @elseif($value === -1 || $value === '-1')
                    {{ ucwords(str_replace('_', ' ', $key)) }}: <strong class="text-gray-900 font-bold">{{ __('Unlimited') }}</strong>
                  @else
                    {{ ucwords(str_replace('_', ' ', $key)) }}: <strong class="text-gray-900 font-bold">{{ $value }}</strong>
                  @endif
                </span>
              @endif
            </li>
            @endforeach
          </ul>
          @endif

          <div class="mt-6">
            @if($isCurrentPlan)
              <button class="btn btn-success text-white w-full rounded-full gap-2 font-bold shadow-lg shadow-success/15 border-0" disabled>
                <span class="material-icons text-sm">check</span>
                {{ __('Paket Aktif') }}
              </button>
            @else
              <a href="{{ route('user.subscription.show', $plan->id) }}" class="btn btn-primary text-white w-full rounded-full gap-2 font-bold shadow-lg shadow-primary/20 hover:shadow-primary/30 transition-all hover:scale-[1.01] active:scale-[0.99]">
                <span class="material-icons text-sm">shopping_cart</span>
                {{ __('Pilih Paket Ini') }}
              </a>
            @endif
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

</div>
@endsection