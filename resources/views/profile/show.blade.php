@extends('layouts.app')
@section('title','My Profile')
@section('content')
<div style="max-width:1200px;margin:16px auto;padding:0 16px;display:grid;grid-template-columns:200px 1fr;gap:16px">
  <div class="admin-sidebar">
    <div class="sidebar-user">
      <div class="sidebar-avatar">{{ Auth::user()->name[0] }}</div>
      <div class="sidebar-name">{{ Auth::user()->name }}</div>
      <div class="sidebar-role">{{ ucfirst(Auth::user()->role) }}</div>
    </div>
    <a href="{{ route('profile.show') }}" class="sidebar-link active">👤 My Profile</a>
    <a href="{{ route('orders.index') }}" class="sidebar-link">📦 My Orders</a>
    <a href="{{ route('cart.index') }}" class="sidebar-link">🛒 My Cart</a>
    <a href="{{ route('products.index') }}" class="sidebar-link">🏠 Continue Shopping</a>
  </div>

  <div>
    <div style="font-size:16px;font-weight:700;margin-bottom:16px">My Profile</div>
    <div class="grid-2">
      <div class="card">
        <div style="font-size:14px;font-weight:600;margin-bottom:16px;padding-bottom:8px;border-bottom:1px solid #f0f0f0">Account Information</div>
        <form method="POST" action="{{ route('profile.update') }}">
          @csrf @method('PUT')
          <div class="form-group">
            <label class="form-label" for="name">Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name',auth()->user()->name) }}" required maxlength="100">
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <input name="email" type="text" class="form-control" value="{{ auth()->user()->email }}" disabled style="background:#f5f5f5;color:var(--muted)">
          </div>
          <div class="form-group">
            <label class="form-label" for="account">Account Type</label>
            <input name="account" type="text" class="form-control" value="{{ ucfirst(auth()->user()->role) }}" disabled style="background:#f5f5f5;color:var(--muted)">
          </div>
          <button type="submit" class="btn btn-orange">Save Changes</button>
        </form>
      </div>

      <div class="card">
        <div style="font-size:14px;font-weight:600;margin-bottom:16px;padding-bottom:8px;border-bottom:1px solid #f0f0f0">Change Password</div>
        <form method="POST" action="{{ route('profile.password') }}">
          @csrf @method('PUT')
          <div class="form-group">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
            @error('current_password')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control" required minlength="8">
            <div class="form-hint">Min 8 chars · upper · lower · number · special char (e.g. MyPass@123)</div>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control" required minlength="8">
          </div>
          <button type="submit" class="btn btn-white" style="border-color:var(--orange);color:var(--orange)">Update Password</button>
          <div class="form-hint" style="margin-top:8px">⚠ You will be logged out after changing your password.</div>
        </form>
      </div>
    </div>

    <div class="card mt-2" style="margin-top:16px">
      <div style="font-size:14px;font-weight:600;margin-bottom:12px">Recent Activity</div>
      @forelse($recentActivity as $log)
      <div style="display:flex;align-items:flex-start;gap:10px;padding:8px 0;border-bottom:1px solid #f9f9f9;font-size:12px">
        <span style="flex-shrink:0">{{ match(true){str_contains($log->event,'fail')||str_contains($log->event,'unauthorized')=>'🔴',str_contains($log->event,'limit')=>'🟡',default=>'🟢'} }}</span>
        <div style="flex:1"><span style="font-weight:500">{{ $log->event }}</span> — <span style="color:var(--muted)">{{ $log->description }}</span></div>
        <span style="color:var(--muted);white-space:nowrap">{{ $log->created_at->diffForHumans() }}</span>
      </div>
      @empty
      <p style="color:var(--muted);font-size:13px">No activity yet.</p>
      @endforelse
    </div>
  </div>
</div>
@endsection
