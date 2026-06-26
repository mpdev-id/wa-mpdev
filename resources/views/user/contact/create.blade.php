@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Create Contact')])
<style>
  .btn {
    border-radius: 9999px !important;
  }
</style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
  
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold">{{ __('Add Contact') }}</h1>
      <p class="text-base-content/60">{{ __('Add new contact to your list') }}</p>
    </div>
    <a href="{{ route('user.contact.index') }}" class="btn btn-outline gap-2 rounded-full">
      <span class="material-icons text-sm">arrow_back</span>
      {{ __('Back') }}
    </a>
  </div>

  <div class="glass-card rounded-3xl shadow-xl">
    <div class="card-body">
      <form method="POST" class="space-y-4" action="{{ route('user.contact.store') }}">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Name') }}</span>
            </label>
            <input type="text" name="name" class="input input-bordered" required />
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">{{ __('Phone') }}</span>
            </label>
            <input type="text" name="phone" class="input input-bordered" placeholder="628xxx" required />
          </div>

          <div class="form-control md:col-span-2">
            <label class="label">
              <span class="label-text font-medium">{{ __('Group') }}</span>
            </label>
            <select name="group_id" class="select select-bordered">
              <option value="">{{ __('No Group') }}</option>
              @foreach($groups ?? [] as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t">
          <a href="{{ route('user.contact.index') }}" class="btn btn-outline rounded-full">{{ __('Cancel') }}</a>
          <button type="submit" class="btn btn-primary gap-2 rounded-full">
            <span class="material-icons text-sm">person_add</span>
            {{ __('Add Contact') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection