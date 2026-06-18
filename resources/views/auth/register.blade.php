@extends('layouts.app')
@section('title','Register')
@section('content')
<div style="max-width:440px;margin:40px auto;padding:0 16px">
  <div class="card">
    <div style="text-align:center;margin-bottom:24px">
      <div style="font-size:2rem;font-weight:700;color:var(--orange);letter-spacing:-1px">SecureShop</div>
      <div style="color:var(--muted);font-size:13px;margin-top:4px">Create your free account</div>
    </div>
    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div class="form-group">
        <label class="form-label" for="name">Full Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required maxlength="100" placeholder="Your full name">
        @error('name')<div class="form-error">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label" for="email">Email Address</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required maxlength="255" placeholder="your@email.com">
        @error('email')<div class="form-error">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <input type="password" name="password" class="form-control" required minlength="8" maxlength="255" placeholder="Min 8 chars with upper, lower, number, symbol">
        <div class="form-hint">e.g. MyPass@123</div>
        @error('password')<div class="form-error">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label" for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" required minlength="8" placeholder="Re-enter password">
      </div>
      <button type="submit" class="btn btn-orange btn-full btn-lg">Create Account</button>
    </form>
    <div style="margin-top:16px;text-align:center;font-size:13px;color:var(--muted)">
      Already have an account? <a href="{{ route('login') }}" style="color:var(--orange);font-weight:600">Login</a>
    </div>
  </div>
</div>
@endsection
