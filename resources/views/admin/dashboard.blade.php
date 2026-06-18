@extends('layouts.app')
@section('title','Admin Dashboard')
@section('content')
<div class="admin-wrap">
  <aside class="admin-sidebar">
    <div class="sidebar-user">
      <div class="sidebar-avatar">{{ Auth::user()->name[0] }}</div>
      <div class="sidebar-name">{{ Auth::user()->name }}</div>
      <div class="sidebar-role" style="color:var(--orange);font-weight:600">Administrator</div>
    </div>
    <a href="{{ route('admin.dashboard') }}"      class="sidebar-link active">📊 Dashboard</a>
    <a href="{{ route('admin.products.index') }}" class="sidebar-link">📦 Products</a>
    <a href="{{ route('admin.orders') }}"         class="sidebar-link">📋 Orders</a>
    <a href="{{ route('admin.users') }}"          class="sidebar-link">👥 Users</a>
    <a href="{{ route('admin.audit-logs') }}"     class="sidebar-link">🔍 Audit Logs</a>
    <div style="border-top:1px solid var(--border);margin:8px 0;padding-top:8px">
      <a href="{{ route('products.index') }}" class="sidebar-link">🏠 View Store</a>
    </div>
  </aside>

  <div>
    <div style="font-size:16px;font-weight:700;margin-bottom:16px">📊 Dashboard Overview</div>

    {{-- Stats grid --}}
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:20px">
      @php
      $statItems = [
        ['label'=>'Total Users','value'=>$stats['total_users'],'icon'=>'👥','color'=>'#1a94ff'],
        ['label'=>'Products','value'=>$stats['total_products'],'icon'=>'📦','color'=>'#26aa99'],
        ['label'=>'Total Orders','value'=>$stats['total_orders'],'icon'=>'📋','color'=>'#ee4d2d'],
        ['label'=>'Pending','value'=>$stats['pending_orders'],'icon'=>'⏳','color'=>'#f5a623'],
        ['label'=>'Revenue','value'=>'RM '.number_format($stats['revenue'],2),'icon'=>'💰','color'=>'#26aa99'],
      ];
      @endphp
      @foreach($statItems as $s)
      <div class="card" style="text-align:center;padding:16px">
        <div style="font-size:2rem;margin-bottom:8px">{{ $s['icon'] }}</div>
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">{{ $s['label'] }}</div>
        <div style="font-size:18px;font-weight:700;color:{{ $s['color'] }}">{{ $s['value'] }}</div>
      </div>
      @endforeach
    </div>

    {{-- Quick actions --}}
    <div class="card" style="margin-bottom:16px">
      <div style="font-size:14px;font-weight:600;margin-bottom:12px">Quick Actions</div>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        <a href="{{ route('admin.products.create') }}" class="btn btn-orange btn-sm">+ Add Product</a>
        <a href="{{ route('admin.orders',['status'=>'pending']) }}" class="btn btn-white btn-sm">⏳ Pending Orders</a>
        <a href="{{ route('admin.users') }}" class="btn btn-white btn-sm">👥 Manage Users</a>
        <a href="{{ route('admin.audit-logs') }}" class="btn btn-white btn-sm">🔍 Security Logs</a>
      </div>
    </div>

    {{-- Recent security events --}}
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <div style="font-size:14px;font-weight:600">🔍 Recent Security Events</div>
        <a href="{{ route('admin.audit-logs') }}" style="font-size:12px;color:var(--orange)">View all →</a>
      </div>
      <table class="table">
        <thead><tr><th>Time</th><th>Event</th><th>User</th><th>Description</th><th>IP</th></tr></thead>
        <tbody>
          @foreach($recentLogs as $log)
          @php $icon=match(true){str_contains($log->event,'fail')||str_contains($log->event,'unauthorized')=>'🔴',str_contains($log->event,'limit')=>'🟡',default=>'🟢'} @endphp
          <tr>
            <td style="white-space:nowrap;color:var(--muted)">{{ $log->created_at->diffForHumans() }}</td>
            <td>{{ $icon }} <span style="font-size:11px;font-weight:600">{{ $log->event }}</span></td>
            <td style="font-size:12px">{{ $log->user->name ?? 'Guest' }}</td>
            <td style="font-size:12px;color:var(--muted)">{{ Str::limit($log->description,60) }}</td>
            <td style="font-size:11px;color:var(--muted)">{{ $log->ip_address }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
