@extends('layouts.app')
@section('title','Manage Users')
@section('content')
<div class="admin-wrap">
  <aside class="admin-sidebar">
    <div class="sidebar-user">
      <div class="sidebar-avatar">{{ Auth::user()->name[0] }}</div>
      <div class="sidebar-name">{{ Auth::user()->name }}</div>
      <div class="sidebar-role" style="color:var(--orange);font-weight:600">Administrator</div>
    </div>
    <a href="{{ route('admin.dashboard') }}"      class="sidebar-link">📊 Dashboard</a>
    <a href="{{ route('admin.products.index') }}" class="sidebar-link">📦 Products</a>
    <a href="{{ route('admin.orders') }}"         class="sidebar-link">📋 Orders</a>
    <a href="{{ route('admin.users') }}"          class="sidebar-link active">👥 Users</a>
    <a href="{{ route('admin.audit-logs') }}"     class="sidebar-link">🔍 Audit Logs</a>
    <div style="border-top:1px solid var(--border);margin:8px 0;padding-top:8px">
      <a href="{{ route('products.index') }}" class="sidebar-link">🏠 View Store</a>
    </div>
  </aside>

  <div>
    <div style="font-size:16px;font-weight:700;margin-bottom:16px">👥 Users <span style="font-size:13px;color:var(--muted);font-weight:400">({{ $users->total() }} total)</span></div>
    <div class="card">
      <table class="table">
        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Joined</th><th>Action</th></tr></thead>
        <tbody>
          @foreach($users as $u)
          <tr>
            <td style="color:var(--muted);font-size:12px">#{{ $u->id }}</td>
            <td>
              <div style="display:flex;align-items:center;gap:8px">
                <div style="width:32px;height:32px;border-radius:50%;background:var(--orange);color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0">{{ $u->name[0] }}</div>
                <span style="font-size:13px;font-weight:500">{{ $u->name }}</span>
              </div>
            </td>
            <td style="font-size:12px;color:var(--muted)">{{ $u->email }}</td>
            <td><span class="badge {{ $u->isAdmin()?'badge-orange':'badge-gray' }}">{{ ucfirst($u->role) }}</span></td>
            <td><span class="badge {{ $u->is_active?'badge-green':'badge-red' }}">{{ $u->is_active?'Active':'Banned' }}</span></td>
            <td style="font-size:12px;color:var(--muted)">{{ $u->created_at->format('d M Y') }}</td>
            <td>
              @if(!$u->isAdmin())
                <form method="POST" action="{{ route('admin.users.toggle',$u) }}">
                  @csrf
                  <button type="submit" class="btn btn-sm {{ $u->is_active?'btn-danger':'btn-white' }}" style="{{ !$u->is_active?'color:var(--green);border-color:var(--green)':'' }}">
                    {{ $u->is_active?'🚫 Ban':'✅ Activate' }}
                  </button>
                </form>
              @else
                <span style="font-size:12px;color:var(--muted)">🔒 Protected</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="pagination mt-2">{{ $users->links() }}</div>
    </div>
  </div>
</div>
@endsection
