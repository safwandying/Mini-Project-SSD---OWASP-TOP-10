@extends('layouts.app')
@section('title','Manage Orders')
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
    <a href="{{ route('admin.orders') }}"         class="sidebar-link active">📋 Orders</a>
    <a href="{{ route('admin.users') }}"          class="sidebar-link">👥 Users</a>
    <a href="{{ route('admin.audit-logs') }}"     class="sidebar-link">🔍 Audit Logs</a>
    <div style="border-top:1px solid var(--border);margin:8px 0;padding-top:8px">
      <a href="{{ route('products.index') }}" class="sidebar-link">🏠 View Store</a>
    </div>
  </aside>

  <div>
    <div style="font-size:16px;font-weight:700;margin-bottom:16px">📋 Orders</div>

    {{-- Status tabs --}}
    <div style="display:flex;gap:4px;margin-bottom:16px;flex-wrap:wrap">
      <a href="{{ route('admin.orders') }}" class="btn btn-sm {{ !request('status')?'btn-orange':'btn-white' }}">All</a>
      @foreach($statuses as $s)
        <a href="{{ route('admin.orders',['status'=>$s]) }}" class="btn btn-sm {{ request('status')===$s?'btn-orange':'btn-white' }}">{{ ucfirst($s) }}</a>
      @endforeach
    </div>

    <div class="card">
      <table class="table">
        <thead><tr><th>Order #</th><th>Customer</th><th>Date</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Update</th></tr></thead>
        <tbody>
          @php $sc=['pending'=>'badge-yellow','processing'=>'badge-blue','shipped'=>'badge-blue','delivered'=>'badge-green','completed'=>'badge-green','cancelled'=>'badge-red'] @endphp
          @forelse($orders as $o)
          <tr>
            <td style="font-weight:600">#{{ $o->id }}</td>
            <td>
              <div style="font-size:13px;font-weight:500">{{ $o->user->name }}</div>
              <div style="font-size:11px;color:var(--muted)">{{ $o->user->email }}</div>
            </td>
            <td style="font-size:12px;color:var(--muted);white-space:nowrap">{{ $o->created_at->format('d M Y') }}<br>{{ $o->created_at->format('h:i A') }}</td>
            <td style="font-size:12px">{{ $o->items->count() }} item(s)</td>
            <td style="font-weight:700;color:var(--orange)">RM {{ number_format($o->total_amount,2) }}</td>
            <td style="font-size:12px;color:var(--muted)">{{ ucwords(str_replace('_',' ',$o->payment_method??'-')) }}</td>
            <td><span class="badge {{ $sc[$o->status]??'badge-gray' }}">{{ strtoupper($o->status) }}</span></td>
            <td>
              <form method="POST" action="{{ route('admin.orders.status',$o) }}" style="display:flex;gap:4px">
                @csrf @method('PUT')
                <select name="status" class="form-control" style="width:auto;padding:4px 8px;font-size:12px">
                  @foreach($statuses as $s)<option value="{{ $s }}" @selected($o->status===$s)>{{ ucfirst($s) }}</option>@endforeach
                </select>
                <button type="submit" class="btn btn-orange btn-sm">✓</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--muted)">No orders found.</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="pagination mt-2">{{ $orders->withQueryString()->links() }}</div>
    </div>
  </div>
</div>
@endsection
