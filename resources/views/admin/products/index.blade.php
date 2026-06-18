@extends('layouts.app')
@section('title','Manage Products')
@section('content')
<div class="admin-wrap">
  <aside class="admin-sidebar">
    <div class="sidebar-user">
      <div class="sidebar-avatar">{{ Auth::user()->name[0] }}</div>
      <div class="sidebar-name">{{ Auth::user()->name }}</div>
      <div class="sidebar-role" style="color:var(--orange);font-weight:600">Administrator</div>
    </div>
    <a href="{{ route('admin.dashboard') }}"      class="sidebar-link">📊 Dashboard</a>
    <a href="{{ route('admin.products.index') }}" class="sidebar-link active">📦 Products</a>
    <a href="{{ route('admin.orders') }}"         class="sidebar-link">📋 Orders</a>
    <a href="{{ route('admin.users') }}"          class="sidebar-link">👥 Users</a>
    <a href="{{ route('admin.audit-logs') }}"     class="sidebar-link">🔍 Audit Logs</a>
    <div style="border-top:1px solid var(--border);margin:8px 0;padding-top:8px">
      <a href="{{ route('products.index') }}" class="sidebar-link">🏠 View Store</a>
    </div>
  </aside>

  <div>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
      <div style="font-size:16px;font-weight:700">📦 Products <span style="font-size:13px;color:var(--muted);font-weight:400">({{ $products->total() }} total)</span></div>
      <a href="{{ route('admin.products.create') }}" class="btn btn-orange">+ Add Product</a>
    </div>
    <div class="card">
      <table class="table">
        <thead><tr><th>ID</th><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Sold</th><th>Rating</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($products as $p)
          <tr>
            <td style="color:var(--muted);font-size:11px">#{{ $p->id }}</td>
            <td>
              <div style="display:flex;align-items:center;gap:8px">
                <div style="width:40px;height:40px;background:#f5f5f5;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0">{{ $p->emoji }}</div>
                <div>
                  <div style="font-weight:500;font-size:13px">{{ Str::limit($p->name,40) }}</div>
                  <div style="font-size:11px;color:var(--muted)">RM {{ number_format($p->original_price??0,2) }} orig.</div>
                </div>
              </div>
            </td>
            <td><span class="badge badge-orange">{{ $p->category }}</span></td>
            <td style="font-weight:600;color:var(--orange)">RM {{ number_format($p->price,2) }}</td>
            <td style="color:{{ $p->stock<5?'#ee4d2d':'var(--text)' }};font-weight:{{ $p->stock<5?'700':'400' }}">{{ $p->stock }}</td>
            <td style="color:var(--muted)">{{ number_format($p->sold_count) }}</td>
            <td>★ {{ $p->rating }}</td>
            <td><span class="badge {{ $p->is_active?'badge-green':'badge-red' }}">{{ $p->is_active?'Active':'Inactive' }}</span></td>
            <td>
              <div style="display:flex;gap:4px">
                <a href="{{ route('admin.products.edit',$p) }}" class="btn btn-white btn-sm">Edit</a>
                <form method="POST" action="{{ route('admin.products.destroy',$p) }}" onsubmit="return confirm('Delete {{ addslashes($p->name) }}?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm" style="background:#fff5f5;color:#ee4d2d;border:1px solid #fde8e8">Delete</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="9" style="text-align:center;padding:40px;color:var(--muted)">No products yet. <a href="{{ route('admin.products.create') }}" style="color:var(--orange)">Add one</a></td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="pagination mt-2">{{ $products->links() }}</div>
    </div>
  </div>
</div>
@endsection
