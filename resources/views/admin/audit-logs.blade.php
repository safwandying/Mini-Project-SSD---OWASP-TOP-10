@extends('layouts.app')
@section('title','Audit Logs')
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
    <a href="{{ route('admin.users') }}"          class="sidebar-link">👥 Users</a>
    <a href="{{ route('admin.audit-logs') }}"     class="sidebar-link active">🔍 Audit Logs</a>
    <div style="border-top:1px solid var(--border);margin:8px 0;padding-top:8px">
      <a href="{{ route('products.index') }}" class="sidebar-link">🏠 View Store</a>
    </div>
  </aside>

  <div>
    <div style="font-size:16px;font-weight:700;margin-bottom:4px">🔍 Audit Logs</div>
    <div style="font-size:12px;color:var(--muted);margin-bottom:16px">OWASP ASVS V7 — Security event log. Passwords and tokens are never stored here.</div>

    {{-- Filters --}}
    <div class="card" style="margin-bottom:12px">
      <form method="GET" action="{{ route('admin.audit-logs') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
        <div>
          <label class="form-label" for="event">Event Type</label>
          <select name="event" class="form-control" style="min-width:180px">
            <option value="">All Events</option>
            @foreach($allowed as $ev)<option value="{{ $ev }}" @selected(request('event')===$ev)>{{ $ev }}</option>@endforeach
          </select>
        </div>
        <div>
          <label class="form-label" for="from">From Date</label>
          <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div>
          <label class="form-label" for="to">To Date</label>
          <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>
        <button type="submit" class="btn btn-orange">Filter</button>
        <a href="{{ route('admin.audit-logs') }}" class="btn btn-white">Clear</a>
      </form>
    </div>

    <div class="card">
      <table class="table">
        <thead><tr><th>#</th><th>Timestamp</th><th>Event</th><th>User</th><th>Description</th><th>IP Address</th></tr></thead>
        <tbody>
          @forelse($logs as $log)
          @php $icon=match(true){str_contains($log->event,'fail')||str_contains($log->event,'unauthorized')=>'🔴',str_contains($log->event,'limit')=>'🟡',str_contains($log->event,'success')||str_contains($log->event,'registered')||str_contains($log->event,'placed')=>'🟢',default=>'🔵'} @endphp
          <tr>
            <td style="color:var(--muted);font-size:11px">{{ $log->id }}</td>
            <td style="font-size:11px;white-space:nowrap;color:var(--muted)">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
            <td style="font-size:12px;white-space:nowrap">{{ $icon }} {{ $log->event }}</td>
            <td>
              <div style="font-size:12px;font-weight:500">{{ $log->user->name ?? 'Guest' }}</div>
              @if($log->user)<div style="font-size:11px;color:var(--muted)">{{ $log->user->email }}</div>@endif
            </td>
            <td style="font-size:12px;color:var(--muted)">{{ $log->description }}</td>
            <td style="font-size:11px;font-family:monospace">{{ $log->ip_address }}</td>
          </tr>
          @empty
          <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">No logs found.</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="pagination mt-2">{{ $logs->withQueryString()->links() }}</div>
    </div>
  </div>
</div>
@endsection
