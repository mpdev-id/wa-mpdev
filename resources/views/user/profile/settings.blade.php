@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Profile Settings')])
@endsection

@section('content')

<div class="max-w-4xl mx-auto">
  
  <!-- Page Header -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-xl md:text-2xl break-words font-bold text-base-content mb-2">{{ __('Profile Settings') }}</h1>
      <p class="text-base-content/60">{{ __('Manage your account information and security') }}</p>
    </div>
    <a href="{{ url('user/dashboard') }}" class="btn btn-outline gap-2 rounded-full">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Dashboard') }}
    </a>
  </div>

  <!-- General Settings -->
  <div class="card bg-white shadow-xl mb-6 animate-slide-up">
    <div class="card-body">
      <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
          <span class="material-icons text-primary">person</span>
        </div>
        <div>
          <h2 class="text-xl font-bold">{{ __('General Settings') }}</h2>
          <p class="text-sm text-base-content/60">{{ __('Update your profile information') }}</p>
        </div>
      </div>

      <form class="ajaxform" action="{{ route('user.profile.update','general') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="space-y-4">
          <!-- Name -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Full Name') }}</span>
            </label>
            <input 
              type="text" 
              name="name" 
              class="input input-bordered w-full" 
              value="{{ Auth::user()->name }}" 
              required 
            />
          </div>

          <!-- Email -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Email Address') }}</span>
            </label>
            <input 
              type="email" 
              name="email" 
              class="input input-bordered w-full" 
              value="{{ Auth::user()->email }}" 
              required 
            />
          </div>

          <!-- Phone -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Phone Number') }}</span>
            </label>
            <input 
              type="text" 
              name="phone" 
              class="input input-bordered w-full" 
              value="{{ Auth::user()->phone }}" 
              required 
            />
          </div>

          <!-- Address -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Address') }}</span>
              <span class="label-text-alt text-base-content/60">{{ __('Used for invoices') }}</span>
            </label>
            <input 
              type="text" 
              name="address" 
              class="input input-bordered w-full" 
              value="{{ Auth::user()->address }}" 
              required 
            />
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end mt-6 pt-6 border-t border-base-200">
          <button type="submit" class="btn btn-primary submit-button gap-2 rounded-full">
            <span class="material-icons text-sm">save</span>
            {{ __('Update Profile') }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Password Settings -->
  <div class="card bg-white shadow-xl animate-slide-up" style="animation-delay: 0.1s">
    <div class="card-body">
      <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-full bg-error/10 flex items-center justify-center">
          <span class="material-icons text-error">lock</span>
        </div>
        <div>
          <h2 class="text-xl font-bold">{{ __('Change Password') }}</h2>
          <p class="text-sm text-base-content/60">{{ __('Update your account password') }}</p>
        </div>
      </div>

      <form class="ajaxform_reset_form" action="{{ route('user.profile.update','password') }}">
        @csrf
        @method('PUT')
        
        <div class="space-y-4">
          <!-- Old Password -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Current Password') }}</span>
            </label>
            <label class="input input-bordered flex items-center gap-2">
              <span class="material-icons text-base-content/40">lock</span>
              <input 
                type="password" 
                name="oldpassword" 
                class="grow" 
                placeholder="{{ __('Enter current password') }}"
                required 
              />
            </label>
          </div>

          <!-- New Password -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('New Password') }}</span>
            </label>
            <label class="input input-bordered flex items-center gap-2">
              <span class="material-icons text-base-content/40">lock_reset</span>
              <input 
                type="password" 
                name="password" 
                class="grow" 
                placeholder="{{ __('Enter new password') }}"
                required 
              />
            </label>
          </div>

          <!-- Confirm Password -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Confirm New Password') }}</span>
            </label>
            <label class="input input-bordered flex items-center gap-2">
              <span class="material-icons text-base-content/40">check_circle</span>
              <input 
                type="password" 
                name="password_confirmation" 
                class="grow" 
                placeholder="{{ __('Confirm new password') }}"
                required 
              />
            </label>
          </div>

          <!-- Password Requirements -->
          <div class="alert alert-info">
            <span class="material-icons">info</span>
            <div class="text-sm">
              <strong>{{ __('Password Requirements:') }}</strong>
              <ul class="list-disc list-inside mt-2">
                <li>{{ __('Minimum 8 characters') }}</li>
                <li>{{ __('Include uppercase and lowercase letters') }}</li>
                <li>{{ __('Include numbers') }}</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end mt-6 pt-6 border-t border-base-200">
          <button type="submit" class="btn btn-error submit-button gap-2 rounded-full">
            <span class="material-icons text-sm">lock_reset</span>
            {{ __('Update Password') }}
          </button>
        </div>
      </form>
    </div>
  </div>

</div>

@endsection