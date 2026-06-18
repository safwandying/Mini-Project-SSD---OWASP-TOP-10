@extends('layouts.app')
@section('title','Login')
@section('content')
<div style="max-width:420px;margin:40px auto;padding:0 16px">
  <div class="card">
    <div style="text-align:center;margin-bottom:24px">
      <div style="font-size:2rem;font-weight:700;color:var(--orange);letter-spacing:-1px">SecureShop</div>
      <div style="color:var(--muted);font-size:13px;margin-top:4px">Login to your account</div>
    </div>
    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autocomplete="email" maxlength="255" placeholder="Enter your email">
        @error('email')<div class="form-error">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required minlength="8" maxlength="255" autocomplete="current-password" placeholder="Enter your password">
        @error('password')<div class="form-error">{{ $message }}</div>@enderror
      </div>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;font-size:13px">
        <label style="display:flex;align-items:center;gap:6px;cursor:pointer"><input type="checkbox" name="remember" value="1"> Remember me</label>
      </div>
      <button type="submit" class="btn btn-orange btn-full btn-lg">Login</button>
    </form>
    <div style="margin-top:16px;text-align:center;font-size:13px;color:var(--muted)">
      Don't have an account? <a href="{{ route('register') }}" style="color:var(--orange);font-weight:600">Register</a>
    </div>
    <div style="margin-top:16px;background:#fff7f5;border:1px solid #ffd4c8;border-radius:4px;padding:10px 12px;font-size:12px;color:var(--muted)">
      <strong>Demo accounts:</strong><br>
      👤 user@secureshop.com / User@1234!<br>
      🔑 admin@secureshop.com / Admin@1234!
    </div>
  </div>
  <div style="text-align:center;margin-top:16px;font-size:12px;color:var(--muted)">
    🔒 Secured by bcrypt · CSRF · Rate limiting · Audit logging
  </div>
</div>
@endsection
