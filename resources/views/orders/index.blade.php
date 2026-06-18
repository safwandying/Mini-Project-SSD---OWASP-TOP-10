@extends('layouts.app')
@section('title','My Orders')
@section('content')
<div class="page-wrap" style="display:grid;grid-template-columns:200px 1fr;gap:16px;max-width:1200px;margin:16px auto;padding:0 16px">
  {{-- Sidebar --}}
  <div class="admin-sidebar">
    <div class="sidebar-user">
      <div class="sidebar-avatar">{{ Auth::user()->name[0] }}</div>
      <div class="sidebar-name">{{ Auth::user()->name }}</div>
      <div class="sidebar-role">Member</div>
    </div>
    <a href="{{ route('profile.show') }}" class="sidebar-link">👤 My Profile</a>
    <a href="{{ route('orders.index') }}" class="sidebar-link active">📦 My Orders</a>
    <a href="{{ route('cart.index') }}" class="sidebar-link">🛒 My Cart</a>
    <a href="{{ route('products.index') }}" class="sidebar-link">🏠 Continue Shopping</a>
  </div>

  {{-- Orders --}}
  <div>
    <div style="font-size:16px;font-weight:700;margin-bottom:16px">📦 My Orders</div>

    @if($orders->isEmpty())
      <div class="card" style="text-align:center;padding:60px 20px">
        <div style="font-size:4rem;margin-bottom:12px">📦</div>
        <div style="font-size:15px;font-weight:600;margin-bottom:8px">No orders yet</div>
        <p style="color:var(--muted);margin-bottom:16px">You haven't placed any orders yet.</p>
        <a href="{{ route('products.index') }}" class="btn btn-orange">Start Shopping</a>
      </div>
    @else
      @php $statusSteps=['pending','processing','shipped','delivered','completed'] @endphp
      @php $sc=['pending'=>'badge-yellow','processing'=>'badge-blue','shipped'=>'badge-blue','delivered'=>'badge-green','completed'=>'badge-green','cancelled'=>'badge-red'] @endphp
      @foreach($orders as $order)
      <div class="card" style="margin-bottom:12px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid #f0f0f0">
          <div style="font-size:13px">
            <strong>Order #{{ $order->id }}</strong>
            <span style="color:var(--muted);margin-left:8px">{{ $order->created_at->format('d M Y, h:i A') }}</span>
            @if($order->tracking_number)<span style="margin-left:8px;font-size:11px;color:var(--muted)">Tracking: {{ $order->tracking_number }}</span>@endif
          </div>
          <span class="badge {{ $sc[$order->status]??'badge-gray' }}">{{ strtoupper($order->status) }}</span>
        </div>

        @foreach($order->items as $item)
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
          <div style="width:52px;height:52px;background:#f5f5f5;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0">{{ $item->product?->emoji ?? '📦' }}</div>
          <div style="flex:1;min-width:0">
            <div style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $item->product?->name ?? 'Deleted product' }}</div>
            <div style="font-size:12px;color:var(--muted)">Qty: {{ $item->quantity }} × RM {{ number_format($item->unit_price,2) }}</div>
          </div>
          <div style="font-size:13px;font-weight:600;color:var(--orange);flex-shrink:0">RM {{ number_format($item->unit_price*$item->quantity,2) }}</div>
        </div>
        @endforeach

        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:12px;padding-top:12px;border-top:1px solid #f0f0f0">
          <div style="font-size:13px;color:var(--muted)">
            Payment: {{ ucwords(str_replace('_',' ',$order->payment_method ?? '-')) }}
          </div>
          <div style="display:flex;align-items:center;gap:12px">
            <span style="font-size:13px">Total: <strong style="color:var(--orange);font-size:15px">RM {{ number_format($order->total_amount,2) }}</strong></span>
            <a href="{{ route('orders.show',$order->id) }}" class="btn btn-white btn-sm">View Details</a>
            @if(in_array($order->status,['pending','processing']))
              <form method="POST" action="{{ route('orders.cancel',$order->id) }}">
                @csrf
                <button type="submit" class="btn btn-white btn-sm" onclick="return confirm('Cancel this order?')" style="color:#ee4d2d;border-color:#ee4d2d">Cancel</button>
              </form>
            @endif
          </div>
        </div>
      </div>
      @endforeach
      <div class="pagination">{{ $orders->links() }}</div>
    @endif
  </div>
</div>
@endsection
